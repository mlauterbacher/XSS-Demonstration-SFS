<?php

require_once('gbentry.php');

class Guestbook {
	
	private $location = '';		# *.txt location
	private $entries = array(); # instances of GbEntry
		
	public function save() {
		$data = serialize($this->entries);
		file_put_contents($this->location, $data);
	}
	
	public function load() {
		if ($data = @file_get_contents($this->location)) {
			$this->entries = unserialize(($data));
		}
	}
	
	// Truncate Guestbook
	public function clean() {
		$this->entries = array();
		$this->save();
	}
	
	public function __construct($location) {
		$this->location = $location;
		$this->load();
	}
	
	public function addEntry(GbEntry $e) {
		$this->entries[] = $e;
		return $this;
	}
	
	public function getEntries() {
		return $this->entries;
	}
}

