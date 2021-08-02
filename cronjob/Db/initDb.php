<?php
require_once ('/var/www/html/whatsappSolution/cronjob/Db/MysqliDb.php');
//	$mysqli = new mysqli ('localhost', 'root', '3ur+6CjH?pye', 'webpanel');
	$smsDbconn = new mysqli ('127.0.0.1', 'root', 'yiudpC*s>6me', 'whatsapp_solution');
  $smsDb = new MysqliDb ($smsDbconn);

?>
