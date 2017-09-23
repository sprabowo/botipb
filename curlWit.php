<?php
date_default_timezone_set('Asia/Jakarta');
$context = stream_context_create(array(
    'http' => array(
        'header'  => "Authorization: Bearer GARBPU36ZBU73SYODXAKYQZBDMVWFFBT"
    )
));
$url = "https://api.wit.ai/message?v=".date('d-m-Y')."&q=".$_GET['id'];
$data = file_get_contents($url, false, $context);
?>