#!/usr/bin/php -q
<?php
//sleep(5);
require_once '/var/www/html/whatsappSolution/cronjob/Db/initDb.php';
date_default_timezone_set("Asia/Kolkata");

function generateToken()
	{
		mt_srand((double)microtime()*10000);
		$charid = strtolower(md5(uniqid(rand(), true)));
		$salt =  substr($charid, 0, 2).substr($charid, 4, 2).substr($charid,9, 2).substr($charid,12, 2);
		return $salt;
	}

function createCsv($contacts)
  {
    $leadPath = '/var/www/html/whatsappSolution/public/uploads/scrubCsv/';
    $name = generateToken().'.csv';
    $file = $leadPath.$name;
    if ($contacts) {
      //$contacts = array_map('trim',$contacts);
      $fp = fopen("$file", 'w');
      foreach($contacts as $line){
        //$val = explode(",",$line);
        fputcsv($fp, $line);
      }
      fclose($fp);

      return $name;
    }
    return false;
  }

if (isset($argv[1]))
{


  $id = $argv[1];

  $smsDb->where ('is_status', 0);
  $smsDb->where ('id', $id);
  $row = $smsDb->getOne('wc_scrubs');
  $smsDb->count;
  if ($smsDb->count > 0) {

		$userId = $row['user_id'];
    $instance = $row['instance_token'];
		$lead = $row['leads_file'];
    $registered = array();
    $notRegistered = array();
		if (($handle = fopen('/var/www/html/whatsappSolution/public/uploads/scrubCsv/'.$lead, "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

            $dataPost = array(
  					    'id' => $instance,
  					    'number' => $data['0']
  					);
  					$payload = json_encode($dataPost);
  					// Prepare new cURL resource
  					$ch = curl_init('https://api.textnator.com:9000/scrub-number');
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
  						$statusMessage = "Registered";
  						$status = '1';
              $registered[] = array($data['0'],$statusMessage);
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
              $notRegistered[] = array($data['0'],$statusMessage);
  					}


  		}
      fclose($handle);
      $registeredFile = createCsv($registered);
      $notRegisteredFile = createCsv($notRegistered);

			$registeredCount = count($registered);
			$notRegisteredCount = count($notRegistered);

      $id = $row['id'];
      $data = Array ('registered_file' => $registeredFile,
                      'not_registered_file' => $notRegisteredFile,
											'registered_count' => $registeredCount,
											'not_registered_count' => $notRegisteredCount,
                      'is_status' => '1');
      $smsDb->where('id', $id);
      $return = $smsDb->update ('wc_scrubs', $data);

    }
  }
}

?>
