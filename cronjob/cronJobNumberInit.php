#!/usr/bin/php
<?php
require_once '/var/www/html/whatsappSolution/cronjob/Db/initDb.php';
date_default_timezone_set("Asia/Kolkata");
$today = date("d-m-y");

while (true) {
  $cdateTime =date("Y-m-d H:i:s");
  $totalProcess = 10;
  $freeProcess = 0;
  $runningProcess = shell_exec("ps -ef | grep cronJobNumberPriority.php | grep -v grep | wc -l");
  @$freeProcess = $totalProcess - $runningProcess;

  $select_distinct_campaign = "SELECT DISTINCT `user_id` FROM `wc_campaigns` WHERE `is_status` ='0' AND `start_at`<='$cdateTime'";
  $select_distinct_campaign_result = $smsDb->rawQuery($select_distinct_campaign);
  $distinct_count = $smsDb->count;
  if ($freeProcess) {
      if ($freeProcess < $distinct_count) {
  		  $output = $select_distinct_campaign_result;
  		  for ($x = $freeProcess; $x >= 0; $x--) {
  			  $userId = $output[$x];
  			  $userId = $userId['user_id'];


  			  $smsDb->where ('is_status', 0);
  			  $smsDb->where ('user_id', $userId);
  			  $row = $smsDb->getOne('wc_campaigns');
  			  $totalLeads = $smsDb->count;

  			 // echo "SINGLE - campaigID = $userId = $totalLeads\sn";
  			  $id = $row['id'];
  			  $data = Array ('is_status' => '2');
  			  $smsDb->where('id', $id);
  			  $smsDb->where ('start_at', $cdateTime, "<=");
  			  $smsDb->update ('wc_campaigns', $data);

  			  if ($totalLeads > 0) {
            shell_exec('/usr/bin/php /var/www/html/whatsappSolution/cronjob/cronJobNumberPriority.php '.$id.' 2> /dev/null > /dev/null  &');
  			  }
  			}
    	}else {

	      if ($distinct_count > 0) {
  			  $select_per_campaign = ceil($freeProcess/$distinct_count);
  			  foreach ($select_distinct_campaign_result as $distinct_row) {
  			      $userId = $distinct_row['user_id'];

  			      $smsDb->where ('is_status', 0);
  			      $smsDb->where ('user_id', $userId);
  			      $smsDb->where ('start_at', $cdateTime, "<=");
  			      $results = $smsDb->get('wc_campaigns', Array(0, $select_per_campaign));
  			      $totalLeads = $smsDb->count;

  			     // echo "FULL - campaigID = $userId = $totalLeads\n";
  			      if ($totalLeads > 0) {
    					  foreach ($results as $row) {
  					  	  $id = $row['id'];
                  $data = Array ('is_status' => '2');
  					  	  $smsDb->where('id', $id);
  					  	  $smsDb->update ('wc_campaigns', $data);

  						    shell_exec('/usr/bin/php /var/www/html/whatsappSolution/cronjob/cronJobNumberPriority.php '.$id.' 2> /dev/null > /dev/null  &');
    					  }
  		       }
  		   	}
			  }
   		}
   	}

}
