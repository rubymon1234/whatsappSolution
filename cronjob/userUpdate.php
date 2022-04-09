<?php
require_once '/var/www/html/whatsappSolution/cronjob/Db/initDb.php';

$users = $smsDb->get('wc_users');
foreach ($users as $user) { 
	$id = $user['id'];
	$resellerId = $user['reseller_id'];
	$reportData = Array ("user_id" => $id,
                    "reseller_id" => $resellerId,
		    "credits" => '10000',
       		);
	$smsDb->insert('wc_accounts', $reportData);
}
