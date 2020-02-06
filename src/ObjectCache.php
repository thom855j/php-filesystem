<?php

namespace PHP\Filesystem;

class ObjectCache {

	protected $path = null;
	protected $duration = null;
	
	public function __construct ($path, $duration = 60) {
		$this->path = $path;
		$this->duration = $duration;
	}
	
	public function get($id) {
		$file = $this->path . $id . '.cache';
		if (file_exists($file) && time() - filemtime($file) < $this->duration) {
			return unserialize( file_get_contents($file) );			
		} else {
			return null;
		}
	}
	
	public function set($id, $obj) {
		$file = $this->path . $id . '.cache';
		file_put_contents($file, serialize($obj));
	}
}