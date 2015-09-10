<?php 

namespace thom855j\PHPFilesystem; 

class Cache { 

    public static function start() { 
        //$time_start = microtime(true); 
        // Settings 
        $cachedir = PATH_CACHE; // Directory to cache files in (keep outside web root) 
        $cachetime = CACHE_TIME; // Seconds to cache files for 
        $cacheext = CACHE_EXT; // Extension to give cached files (usually cache, htm, txt) 
        // Ignore List 
        $ignore_list = unserialize(CACHE_IGNORE); 

        // Script 
        $page = Router::uri(); // Requested page 
        $cachefile = $cachedir . md5($page) . '.' . $cacheext; // Cache file to either load or create 
        $ignore_page = false; 
        for ($i = 0; $i < count($ignore_list); $i++) { 
            $ignore_page = (strpos($page, $ignore_list[$i]) !== false) ? true : $ignore_page; 
        } 
        $cachefile_created = ((file_exists($cachefile)) and ( $ignore_page === false)) ? filemtime($cachefile) : 0; 
        clearstatcache(); 

        // Show file from cache if still valid 
        if (time() - $cachetime < $cachefile_created) { 
            ob_start('ob_gzhandler'); 
            readfile($cachefile); 
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

    public static function stop() { 
        $cachedir = PATH_CACHE; // Directory to cache files in (keep outside web root) 
        $cacheext = 'html'; // Extension to give cached files (usually cache, htm, txt) 
        $page = Router::uri(); // Requested page 
        $cachefile = $cachedir . md5($page) . '.' . $cacheext; // Cache file to either load or create 
        // Now the script has run, generate a new cache file 
        $fp = @fopen($cachefile, 'w'); 

        // save the contents of output buffer to the file 
        fwrite($fp, ob_get_contents()); 
        fclose($fp); 

        ob_end_flush(); 
    } 

    public static function clear() { 
        // Settings 
        $cachedir = PATH_CACHE; // Directory to cache files in (keep outside web root) 

        if ($handle = opendir($cachedir)) { 
            while (false !== ($file = readdir($handle))) { 
                if ($file != '.' and $file != '..') { 
                    //echo $file . ' deleted.<br>'; 
                    unlink($cachedir . '/' . $file); 
                } 
            } 
            closedir($handle); 
        } 

        //curl http://www.your_domain.com/empty_caching.php >/dev/null 2>&1 
    } 

} 
