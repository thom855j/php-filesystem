<?php

require_once '../src/Cache.php';

use WebSupportDK\PHPFilesystem\Cache;

$cache = new Cache();
$cache->setDir('cache');
$cache->setTime(1800);
$cache->setExt('html');

$cache->start();

echo 'This is a cache test';

$cache->stop();