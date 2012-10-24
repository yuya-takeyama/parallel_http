<?php
require_once dirname(__FILE__) . '/../vendor/SplClassLoader/SplClassLoader.php';

$loader = new SplClassLoader('Yuyat', dirname(__FILE__) . '/../src');
$loader->setNamespaceSeparator('_');
$loader->register();

$loader = new SplClassLoader('Edps', dirname(__FILE__) . '/../vendor/yuya-takeyama/edps/src');
$loader->setNamespaceSeparator('_');
$loader->register();

$urls = array(
    'http://blog.yuyat.jp/sleep.php?sleep=4',
    'http://blog.yuyat.jp/sleep.php?sleep=2',
    'http://blog.yuyat.jp/sleep.php?sleep=5',
    'http://blog.yuyat./sleep.php?sleep=1',
    'http://blog.yuyat.jp/sleep.php?sleep=3',
);

$loop   = new Yuyat_ParallelHttp_EventLoop;
$client = new Yuyat_ParallelHttp_Client($loop);

foreach ($urls as $url) {
    $request = $client->request($url, function ($status, $headers, $body) {
        echo "Status: {$status}", PHP_EOL;
        var_dump($body);
        echo PHP_EOL;
    });

    $request->on('error', function ($curl, $info) {
        echo "Error:", PHP_EOL;
        var_dump($curl, $info);
        echo PHP_EOL;
    });
}

$loop->run();
