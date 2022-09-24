<?php
$dataPost = array(
    'id' => '000000',
    'number' => '919895909009',
    'message' => 'Test Message',
    'type' => 'text',
    'file' => '',
    'opt' => '1',
    'optmessage' => '*TO STOP FUTURE MESSAGES FROM US*',
    'option' => array(array("text" => "UNSUBSCRIBE"),array("text" => "REPORT"))
);
$payload = json_encode($dataPost);
echo $payload;

 ?>
