<?php
/**
 * Cache system
 */
namespace WebSupportDK\PHPFilesystem;

use WebSupportDK\PHPHttp\Url;

class Cache
{

	public $url;
	protected $_dir, $_time, $_ext, $_ignoreList;
	private $_page, $_file;

	/**
	 * Directory to cache files in (keep outside web root) 
	 */
	public function setDir($directory)
	{

		$this->_dir = $directory;
	}

	/**
	 * Seconds to cache files for 
	 */
	public function setTime($time)
	{

		$this->_time = $time;
	}

	/**
	 * Extension to give cached files (usually cache, htm, txt) 
	 */
	public function setExt($extension)
	{
		$this->_ext = $extension;
	}

	/**
	 * Ignore list of pages NOT to cache
	 */
	public function setIgnore($params = array())
	{
		$this->_ignoreList = $params;
	}

	/**
	 * Set the public, current url
	 */
	public function setUrl($current_url)
	{
		$this->url = $current_url;
	}

	/**
	 * Start caching BEFORE script
	 */
	public function start()
	{
		if (!isset($this->_dir)) {
			return FALSE;
		}
		$this->_page = $this->url; // Requested page 
		$this->_file = $this->_dir . md5($this->_page) . '.' . $this->_ext; // Cache file to either load or create 
		$ignore_page = false;
		for ($i = 0; $i < count($this->_ignoreList); $i++) {
			$ignore_page = (strpos($this->_page, $this->_ignoreList[$i]) !== false) ? true : $ignore_page;
		}
		$cachefile_created = ((file_exists($this->_file)) and ( $ignore_page === false)) ? filemtime($this->_file) : 0;
		clearstatcache();

		// Show file from cache if still valid 
		if (time() - $this->_time < $cachefile_created) {
			ob_start('ob_gzhandler');
			readfile($this->_file);
			//$time_end = microtime(true); 
			//$time = $time_end - $time_start; 
			//echo '<!-- generated in ' . $time . ' cached page - '.date('l jS \of F Y h:i:s A', filemtime($cachefile)).', Page : '.$page.' -->'; 
			ob_end_flush();
			exit();
		}

		// If we're still here, we need to generate a cache file 
		//Turn on output buffering with gzip compression. 
		ob_start('ob_gzhandler');
	}

	/**
	 * Run stop AFTER script
	 */
	public function stop()
	{
		// Now the script has run, generate a new cache file 
		$fp = @fopen($this->_file, 'w');

		// save the contents of output buffer to the file 
		fwrite($fp, ob_get_contents());
		fclose($fp);

		ob_end_flush();
	}

	public function clear()
	{

		if ($handle = opendir($this->_dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != '.' and $file != '..') {
					//echo $file . ' deleted.<br>'; 
					unlink($this->_dir . '/' . $file);
				}
			}
			closedir($handle);
		}

		//curl http://www.your_domain.com/empty_caching.php >/dev/null 2>&1 
	}
}
