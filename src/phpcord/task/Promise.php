<?php

namespace phpcord\task;

use Exception;
use phpcord\Discord;
use phpcord\utils\ArrayUtils;
use function call_user_func;
use function get_class;
use function serialize;
use function unserialize;

class Promise extends AsyncTask {
	
	/** @var callable $workload */
	protected $workload;
	
	/** @var callable | null $then */
	public $then;
	
	/** @var callable | null $failure */
	public $failure;
	
	/** @var null|string $failed */
	protected $failed;
	
	/** @var array $parameters */
	protected $parameters;
	
	/**
	 * Promise constructor.
	 *
	 * @param callable $workload
	 * @param mixed ...$parameters
	 */
	public function __construct(callable $workload, ...$parameters) {
		$this->workload = $workload;
		$this->parameters = $parameters;
		parent::__construct();
	}
	
	/**
	 * Creates a new promise instance
	 *
	 * @api
	 *
	 * @param callable $workload
	 * @param mixed ...$parameters
	 *
	 * @return Promise
	 */
	public static function create(callable $workload, ...$parameters): Promise {
		$promise = new Promise($workload, $parameters);
		Discord::getInstance()->getAsyncPool()->submitTask($promise);
		return $promise;
	}
	
	/**
	 * @internal
	 */
	public function execute() {
		try {
			$array = ArrayUtils::asArray($this->parameters);
			$this->setResult(call_user_func($this->workload, ...$array[0]));
		} catch (Exception $exception) {
			$this->failed = serialize(["message" => $exception->getMessage(), "code" => $exception->getCode(), "class" => get_class($exception)]);
		}
	}
	
	/**
	 * The callable set here will be executed once the workload was successfully completed
	 *
	 * @api
	 *
	 * @param callable $result
	 *
	 * @return $this
	 */
	public function then(callable $result): Promise {
		$this->then = $result;
		Discord::getInstance()->getAsyncPool()->updateTask($this);
		return $this;
	}
	
	/**
	 * The callable set here will be executed if there was an error while running the workload
	 *
	 * @api
	 *
	 * @param callable $failure
	 *
	 * @return $this
	 */
	public function catch(callable $failure): Promise {
		$this->failure = $failure;
		Discord::getInstance()->getAsyncPool()->updateTask($this);
		return $this;
	}
	
	/**
	 * @internal
	 *
	 * @param Discord $discord
	 */
	public function onCompletion(Discord $discord): void {
		if ($this->failed !== null and $this->failure !== null) {
			$data = unserialize($this->failed);
			
			($this->failure)($data);
			return;
		}
		if ($this->then !== null) @($this->then)($this->getResult());
	}
}