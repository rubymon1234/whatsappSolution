#!/usr/bin/php
<?php
//sleep(5);
require_once '/var/www/html/whatsappSolution/cronjob/Db/initDb.php';
date_default_timezone_set("Asia/Kolkata");

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

		if (($handle = fopen('/var/www/html/whatsappSolution/public/uploads/csv/'.$lead, "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$cdateTime =date("Y-m-d H:i:s");
					$ctimestamp = strtotime($cdateTime);

					$dataPost = array(
					    'id' => $instance,
					    'number' => $data['0'],
					    'message' => $message,
					    'type' => $type,
							'file' => $file
					);
					$payload = json_encode($dataPost);
					// Prepare new cURL resource
					$ch = curl_init('http://localhost:8000/send-message');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLINFO_HEADER_OUT, true);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
					// Set HTTP Header for POST request
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					    'Content-Type: application/json',
					    'Content-Length: ' . strlen($payload),
					    'X-Authentication-Key:93c0c7c7cd2a23ccaa3ebed05d442ce4')
					);
					$result = json_decode(curl_exec($ch), true);
					curl_close($ch);

					if ($result['status'] == 1){
						$errorCode = "";
						$statusMessage = "Sent";
						$status = '1';
					}else{
						$errorCode = $result['code'];
						$status = '0';
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
					$reportData = Array ("user_id" => $userId,
			      				"campaign_id" => $id,
			      				"instance_token" => "$instance",
			      				"type" => $type,
										"number" => $data['0'],
			      				"message" => $message,
			      				"media_file_name" =>"$file",
										"time" => $ctimestamp,
			      				"is_status" => $status,
			      				"error_code" => "$errorCode",
			      				"status_message" => "$statusMessage",
			      		);
		      $smsDb->insert('wc_campaigns_outbound', $reportData);
  		}
      fclose($handle);

      $id = $row['id'];
      $data = Array ('is_status' => '1');
      $smsDb->where('id', $id);
      $smsDb->update ('wc_campaigns', $data);

    }
  }
}

?>