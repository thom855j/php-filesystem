<?php

require_once '../src/Cache.php';

use WebSupportDK\PHPFilesystem\Cache;

$cache = new Cache();
$cache->setDir('cache/');
$cache->setTime(1800);
$cache->setExt('html');

// get a query string
$page = $_GET['page'];

// set it is the current page
$cache->setUrl($page);

// start the cache
$cache->start();

echo "The current page is {$page}";

// stop the cache
$cache->stop();

// to delete all cache files
//$cache->clear();