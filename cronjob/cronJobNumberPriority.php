#!/usr/bin/php -q
<?php
//sleep(5);
require_once '/var/www/html/whatsappSolution/cronjob/Db/initDb.php';
date_default_timezone_set("Asia/Kolkata");

function updateCounter($smsDb){
  $cdate =date("Y-m-d");
  $update = "UPDATE `wc_instances` SET `counter` = '0', `updated_at`=NOW() WHERE DATE(updated_at) != '$cdate'";
  $smsDb->rawQuery($update);
}

function getInstance($smsDb){
  $smsDb->where ('is_status', 1);
  $smsDb->where ('type', 2);
  $row = $smsDb->getOne('wc_instances');
  $id = $row['id'];
  $token = $row['token'];
  $counter = $row['counter'];
  if ($counter == '1000'){
    $data = Array ('is_status' => '3');
    $smsDb->where('id', $id);
    $smsDb->update ('wc_instances', $data);
  }else{
    $data = Array ('counter' => $smsDb->inc(1));
    $smsDb->where('id', $id);
    $smsDb->update ('wc_instances', $data);
  }

  return $token;
}

if (isset($argv[1]))
{


  $id = $argv[1];

  $smsDb->where ('is_status', 2);
  $smsDb->where ('id', $id);
  $row = $smsDb->getOne('wc_campaigns');
  $smsDb->count;
  if ($smsDb->count > 0) {

		$userId = $row['user_id'];
    $instance = $row['instance_token'];
		$type = $row['type'];
		$message = $row['message'];
		$file = $row['media_file_name'];
		$lead = $row['leads_file'];
    $optOut = $row['opt_out'];

    $smsDb->where ('instance_token', $instance);
    $rowInstance = $smsDb->getOne('wc_instances');
    $webUrl = $rowInstance['web_hook_url'];
    // if($optOut){
    //   $message = $message.'%0A%0A*Reply \'STOP\' to unsubscribe*';
    // }

    if ($type != 'text'){
      $cf = new CURLFile("/var/www/html/whatsappSolution/public/uploads/media/$file");
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "https://api.textnator.com:5000/receive-file");
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, ["file" => $cf]);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($ch);
      curl_close($ch);
    }

		if (($handle = fopen('/var/www/html/whatsappSolution/public/uploads/csv/'.$lead, "r")) !== FALSE) {
      $leadCount = count(file('/var/www/html/whatsappSolution/public/uploads/csv/'.$lead));
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$cdateTime =date("Y-m-d H:i:s");
					$ctimestamp = strtotime($cdateTime);
          if ($leadCount <= 10){
              $leadData = Array ("instance_token" => "$instance",
                                "user_id"=> "$userId",
    										        "number" => $data['0'],
    			      		);
    		      //$smsDb->insert('wc_priority', $leadData);
              $smsDb->setQueryOption ('LOW_PRIORITY')->replace('wc_priority', $leadData);
          }

          $smsDb->where ('is_status', 1);
          $smsDb->where ('number', $data['0']);
          $smsDb->getOne('wc_blacklists');
          $smsDb->count;
          if ($smsDb->count > 0) {
            $errorCode = "ERR106";
						$statusMessage = "Destination Blacklisted";
						$status = '0';

          }else{
            $dataPost = array(
  					    'id' => $instance,
  					    'number' => $data['0'],
  					    'message' => $message,
  					    'type' => $type,
  							'file' => $file,
                'optout' => $optOut
  					);
  					$payload = json_encode($dataPost);
  					// Prepare new cURL resource
  					$ch = curl_init('https://api.textnator.com:5000/send-message');
  					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  					curl_setopt($ch, CURLOPT_HEADER, false);
  					curl_setopt($ch, CURLINFO_HEADER_OUT, true);
  					curl_setopt($ch, CURLOPT_POST, true);
  					curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
  					// Set HTTP Header for POST request
  					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  					    'Content-Type: application/json',
  					    'Content-Length: ' . strlen($payload),
  					    'X-Authentication-Key:3ec5d070a0b165c00c5c06673fdb59a2')
  					);
  					$result = json_decode(curl_exec($ch), true);
  					curl_close($ch);

  					if ($result['status'] == 1){
  						$errorCode = "";
  						$statusMessage = "Message Has Been Sent";
  						$status = '1';
              $msgId = $result['response']['id'];
  					}else{
  						$errorCode = $result['code'];
  						$status = '0';
              $msgId = "";
  						if ($errorCode == 'ERR103'){
  							$statusMessage = "Not Registered";
  						}else if($errorCode == 'ERR500'){
  							$statusMessage = "Validation Failed";
  						}else if ($errorCode == 'ERR102'){
  							$statusMessage = "Invalid Instance";
  						}else if ($errorCode == 'ERR105'){
  							$statusMessage = "Instance Offline";
  						}
  					}
          }

					$reportData = Array ("msg_id" => $msgId,
                    "user_id" => $userId,
			      				"campaign_id" => $id,
			      				"instance_token" => "$instance",
			      				"type" => $type,
										"number" => $data['0'],
			      				"message" => $message,
			      				"media_file_name" =>"$file",
										"sent_time" => $ctimestamp,
			      				"is_status" => $status,
			      				"error_code" => "$errorCode",
			      				"status_message" => "$statusMessage",
			      		);
		      $smsDb->insert('wc_campaigns_outbounds', $reportData);
          if (isset($webUrl)){
            $dataPost = array(
  					    'messaging_product' => "whatsapp",
  					    'method' => "campaign",
                'campaign_id' => $id,
                'msg_id' => $msgId,
  					    'from' => $data['0'],
                'sent_time' => $ctimestamp,
  					    'status' => $status,
  							'error_code' => $errorCode,
                'status_message' => $statusMessage
  					);
  					$payload = json_encode($dataPost);
  					// Prepare new cURL resource
  					$ch = curl_init($webUrl);
  					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  					curl_setopt($ch, CURLOPT_HEADER, false);
  					curl_setopt($ch, CURLINFO_HEADER_OUT, true);
  					curl_setopt($ch, CURLOPT_POST, true);
  					curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
  					// Set HTTP Header for POST request
  					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  					    'Content-Type: application/json',
  					    'Content-Length: ' . strlen($payload))
  					);
  					$result = json_decode(curl_exec($ch), true);
  					curl_close($ch);
          }
          sleep(mt_rand(5,12));

  		}
      fclose($handle);

      $id = $row['id'];
      $data = Array ('is_status' => '1');
      $smsDb->where('id', $id);
      $return = $smsDb->update ('wc_campaigns', $data);


    }
  }
}

?>
