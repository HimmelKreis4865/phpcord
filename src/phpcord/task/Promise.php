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
	
	protected $workload;
	
	public $then;
	
	public $failure;
	
	protected $failed;
	
	protected $parameters;
	
	public function __construct(callable $workload, ...$parameters) {
		$this->workload = $workload;
		$this->parameters = $parameters;
		parent::__construct();
	}
	
	public static function create(callable $workload, ...$parameters): Promise {
		$promise = new Promise($workload, $parameters);
		Discord::getInstance()->getAsyncPool()->submitTask($promise);
		return $promise;
	}
	
	public function execute() {
		try {
			$array = ArrayUtils::asArray($this->parameters);
			$this->setResult(call_user_func($this->workload, ...$array[0]));
		} catch (Exception $exception) {
			$this->failed = serialize(["message" => $exception->getMessage(), "code" => $exception->getCode(), "class" => get_class($exception)]);
		}
	}
	
	public function then(callable $result): Promise {
		$this->then = $result;
		Discord::getInstance()->getAsyncPool()->updateTask($this);
		return $this;
	}
	
	public function catch(callable $failure): Promise {
		$this->failure = $failure;
		Discord::getInstance()->getAsyncPool()->updateTask($this);
		return $this;
	}
	
	public function onCompletion(Discord $discord): void {
		if ($this->failed !== null and $this->failure !== null) {
			$data = unserialize($this->failed);
			
			($this->failure)($data);
			return;
		}
		if ($this->then !== null) @($this->then)($this->getResult());
	}
}