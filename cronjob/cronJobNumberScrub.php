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

public function createCsv($contacts)
  {
    $leadPath = '/var/www/html/whatsappSolution/public/uploads/csv/';
    $name = generateToken().'.csv';
    $file = $leadPath.$name;
    if ($contacts) {
      $contacts = array_map('trim',$contacts);

      $fp = fopen("$file", 'w');
      foreach($contacts as $line){
        $val = explode(",",$line);
        fputcsv($fp, $val);
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
		if (($handle = fopen('/var/www/html/whatsappSolution/public/uploads/csv/'.$lead, "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

            $dataPost = array(
  					    'id' => $instance,
  					    'number' => $data['0']
  					);
  					$payload = json_encode($dataPost);
  					// Prepare new cURL resource
  					$ch = curl_init('http://localhost:8000/scrub-number');
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
  						$statusMessage = "Registered";
  						$status = '1';
              $registered[] = $data['0'];
  					}else{
  						$errorCode = $result['code'];
  						$status = '0';
  						$statusMessage = "Not Registered";
              $notRegistered[] = $data['0'];
  					}


  		}
      fclose($handle);
      $registeredFile = createCsv($registered);
      $notRegisteredFile = createCsv($notRegistered);

      $id = $row['id'];
      $data = Array ('registered_file' => $registeredFile,
                      'not_registered_file' => $notRegisteredFile,
                      'is_status' => '1');
      $smsDb->where('id', $id);
      $return = $smsDb->update ('wc_scrubs', $data);

      print_r($registered);
      print_r($notRegistered);
    }
  }
}

?>
