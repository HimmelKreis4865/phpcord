<?php

/*
 *         .__                                       .___
 * ______  |  |__  ______    ____   ____ _______   __| _/
 * \____ \ |  |  \ \____ \ _/ ___\ /  _ \\_  __ \ / __ |
 * |  |_> >|   Y  \|  |_> >\  \___(  <_> )|  | \// /_/ |
 * |   __/ |___|  /|   __/  \___  >\____/ |__|   \____ |
 * |__|         \/ |__|         \/                    \/
 *
 *
 * This library is developed by HimmelKreis4865 © 2022
 *
 * https://github.com/HimmelKreis4865/phpcord
 */

namespace phpcord\message\sendable;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use phpcord\message\component\IComponent;
use phpcord\message\MessageFlags;
use phpcord\message\MessageReference;
use phpcord\message\Sendable;
use function array_map;
use function implode;
use function json_encode;
use function substr;
use function var_dump;

class MessageBuilder implements Sendable, JsonSerializable {
	
	private const BOUNDARY = '----boundary';
	
	/** @var MessageReference|null $reference */
	private ?MessageReference $reference = null;
	
	/** @var Embed[] $embeds */
	private array $embeds = [];
	
	/** @var IComponent[] $components */
	private array $components = [];
	
	/** @var Attachment[] $attachments */
	private array $attachments = [];
	
	/** @var int $flags */
	private int $flags = 0;
	
	/**
	 * @param string|null $content
	 * @param bool $tts
	 */
	public function __construct(private ?string $content, private bool $tts = false) { }
	
	/**
	 * @param string|null $content
	 * @param bool $tts
	 *
	 * @return MessageBuilder
	 */
	#[Pure] public static function build(?string $content = null, bool $tts = false): MessageBuilder {
		return new MessageBuilder($content, $tts);
	}
	
	/**
	 * @param MessageReference|null $reference
	 *
	 * @return MessageBuilder
	 */
	public function setReference(?MessageReference $reference): MessageBuilder {
		$this->reference = $reference;
		return $this;
	}
	
	/**
	 * @return MessageBuilder
	 */
	public function setEphemeral(): MessageBuilder {
		$this->setFlag(MessageFlags::EPHEMERAL());
		return $this;
	}
	
	/**
	 * @param Embed $embed
	 *
	 * @return MessageBuilder
	 */
	public function addEmbed(Embed $embed): MessageBuilder {
		$this->embeds[] = $embed;
		return $this;
	}
	
	/**
	 * @param Attachment $attachment
	 *
	 * @return MessageBuilder
	 */
	public function addAttachment(Attachment $attachment): MessageBuilder {
		$this->attachments[] = $attachment;
		return $this;
	}
	
	/**
	 * @param IComponent $component
	 *
	 * @return MessageBuilder
	 */
	public function  addComponent(IComponent $component): MessageBuilder {
		$this->components[] = $component;
		return $this;
	}
	
	public function getContentType(): string {
		return (count($this->attachments) ? 'multipart/form-data; boundary=' . substr(self::BOUNDARY, 2) : 'application/json');
	}
	
	public function getBody(): string {
		if (!count($this->attachments)) return json_encode($this);
		$parts = [
			['fields' => ['name' => 'payload_json'],
			'content-type' => 'application/json',
			'value' => json_encode($this)]
		];
		foreach ($this->attachments as $k => $attachment)
			$parts[] = ['content-type' => $attachment->getImageData()->getMime(), 'value' => $attachment->getImageData()->getBytes(), 'fields' => ['name' => 'files[' . $k . ']', 'filename' => $attachment->getFilename()]];
		
		$body = self::BOUNDARY . "\n";
		$body .= implode("\n" . self::BOUNDARY . "\n", array_map(function(array $part): string {
			return 'Content-Disposition: form-data; ' . $this->stringifyFields($part['fields'] ?? []) . "\nContent-Type: " . $part['content-type'] . "\n\n" . $part['value'];
		}, $parts));
		
		$body .= "\n" . self::BOUNDARY . '--';
		return $body;
	}
	
	
	/**
	 * @param int $bitflag
	 *
	 * @return MessageBuilder
	 */
	public function setFlag(int $bitflag): MessageBuilder {
		$this->flags |= $bitflag;
		return $this;
	}
	
	#[ArrayShape(['content' => "string|null", 'tts' => "bool", 'embeds' => "array", 'components' => "array"])]
	public function jsonSerialize(): array {
		$offset = 0;
		return ([
			'content' => $this->content,
			'tts' => $this->tts,
			'embeds' => $this->embeds,
			'components' => $this->components,
			'flags' => $this->flags,
			'attachments' => array_map(function(Attachment $attachment) use (&$offset): array {
				return $attachment->encode($offset++);
			}, $this->attachments),
		] + ($this->reference ? ['message_reference' => $this->reference] : []));
	}
	
	private function stringifyFields(array $fields): string {
		$ar = [];
		foreach ($fields as $name => $value) $ar[] = $name . '="' . $value . '"';
		return implode('; ', $ar);
	}
}