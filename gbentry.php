<?php

class GbEntry {
	private $text;
	private $sender;
	private $timestamp;
	
	public function setText($t) {
		$this->text = $t;
		return $this;
	}
	
	public function setSender($s) {
		$this->sender = $s;
		return $this;
	}
	
	public function getText() {
		return $this->text;
	}
	
	public function getSender() {
		return $this->sender;
	}
	
	public function getTimestamp() {
		return $this->timestamp;
	}
	
	public function __construct() {
		$this->timestamp = time();
	}
}
