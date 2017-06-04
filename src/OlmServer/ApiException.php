<?php
namespace OlmServer;

class ApiException extends \Exception {
	public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
		$this->code = $code;
		$this->message = $message;
		parent::__construct($message, $code, $previous);
	}

	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}
}
