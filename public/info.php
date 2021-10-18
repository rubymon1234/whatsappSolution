<?php
function kbytes_to_string($kb)
{
    $units = array('TB','GB','MB','KB');
    $scale = 1024*1024*1024;
    $ui = 0;

    while (($kb < $scale) && ($scale > 1))
    {
        $ui++;
        $scale = $scale / 1024;
    }
    return sprintf("%0.2f %s", ($kb/$scale),$units[$ui]);
}

function getSystemInfo()
{
  // cpu load
  $get_cpuload = file_get_contents('/proc/loadavg');
  $cpuload = explode(' ', $get_cpuload);

  $cpu = [
  	$cpuload[0],
  	$cpuload[1],
  	$cpuload[2]
  ];


  // mem usage
  $get_meminfo = file('/proc/meminfo');

  $meminfo_total = filter_var($get_meminfo[0], FILTER_SANITIZE_NUMBER_INT);
  $meminfo_cached = filter_var($get_meminfo[2], FILTER_SANITIZE_NUMBER_INT);
  $meminfo_free = filter_var($get_meminfo[1], FILTER_SANITIZE_NUMBER_INT);

  $swapinfo_total = filter_var($get_meminfo[14], FILTER_SANITIZE_NUMBER_INT);
  $swapinfo_cached = filter_var($get_meminfo[5], FILTER_SANITIZE_NUMBER_INT);
  $swapinfo_free = filter_var($get_meminfo[15], FILTER_SANITIZE_NUMBER_INT);

  $meminfo_usage = ($meminfo_total - ($meminfo_free ));
  $swapinfo_usage = ($swapinfo_total - ($swapinfo_free ));

  if ($meminfo_total >= 10485760) {
    $mem_total = round(($meminfo_total / 1048576), 2);
    $mem_free = round(($meminfo_free / 1048576), 2);
  	$mem_cached = round(($meminfo_cached / 1048576), 2);

    $swap_total = round(($swapinfo_total / 1048576), 2);
    $swap_free = round(($swapinfo_free / 1048576), 2);
  	$swap_cached = round(($swapinfo_cached / 1048576), 2);

  	$mem_usage = round(($meminfo_usage / 1048576), 2);
    $swap_usage = round(($swapinfo_usage / 1048576), 2);

  	$mem_multiple = ' GB';
  } else {
    $mem_total = round(($meminfo_total / 1024), 2);
    $mem_free = round(($meminfo_free / 1024), 2);
  	$mem_cached = round(($meminfo_cached / 1024), 2);

    $swap_total = round(($swapinfo_total / 1024), 2);
    $swap_free = round(($swapinfo_free / 1024), 2);
  	$swap_cached = round(($swapinfo_cached / 1024), 2);

  	$mem_usage = round(($meminfo_usage / 1024), 2);
    $swap_usage = round(($swapinfo_usage / 1024), 2);
  	$mem_multiple = ' MB';
  }

  $mem = array(
    'total' => $mem_total.$mem_multiple,
    'free' => $mem_free.$mem_multiple,
  	'usage' => $mem_usage.$mem_multiple,
  	'cached' => $mem_cached.$mem_multiple,
    'swap_total' => $swap_total.$mem_multiple,
    'swap_free' => $swap_free.$mem_multiple,
  	'swap_usage' => $swap_usage.$mem_multiple,
  	'swap_cached' => $swap_cached.$mem_multiple,
    'muliple' => $mem_multiple
  );



  // disk usage
  $disk_space_total = disk_total_space('/');
  $disk_space_free = disk_free_space('/');
  $disk_space_usage = ($disk_space_total - $disk_space_free);

  if ($disk_space_total > 10737418240) {
    $disk_total = round(($disk_space_total / 1073741824), 2);
    $disk_free = round(($disk_space_free / 1073741824), 2);
  	$disk_usage = round(($disk_space_usage / 1073741824), 2);
  	$disk_multiple = ' GB';
  } else {
    $disk_total = round(($disk_space_total / 1048576), 2);
    $disk_free = round(($disk_space_free / 1048576), 2);
  	$disk_usage = round(($disk_space_usage / 1048576), 2);
  	$disk_multiple = ' MB';
  }

  $disk = array(
    'total' => $disk_total.$disk_multiple,
    'free' => $disk_free.$disk_multiple,
  	'usage' => $disk_usage.$disk_multiple,
    'muliple' => $disk_multiple
  );

  $info = array(
  	'CPU' => $cpu,
  	'MEMORY' => $mem,
  	'DISK' => $disk
  );
  return json_encode($info);
}

$traffics = json_decode(shell_exec('/usr/bin/vnstat --json'), true);
echo '
<table border="1" style="width:25%">
<caption>Bandwidth Monthly</caption>
 <tr>
   <th>Date</th>
   <th>Tx</th>
   <th>Rx</th>
 </tr>
';
foreach ($traffics as $key => $traffic) {
  $months = $traffic[0]['traffic']['months'];
  foreach ($months as $key => $month) {
    //print_r($months);
    echo '<tr>';
    echo '<td>'.date('M-Y', strtotime($month['date']['year'].'-'.$month['date']['month'])).'</td>';
    echo '<td>'.kbytes_to_string($month['tx']).'</td>';
    echo '<td>'.kbytes_to_string($month['rx']).'</td>';
    echo '</tr>';
  }

}
echo '
</table>
';

echo '
<table border="1" style="width:25%">
<caption>Bandwidth Daily</caption>
 <tr>
   <th>Date</th>
   <th>Tx</th>
   <th>Rx</th>
 </tr>
';
foreach ($traffics as $key => $traffic) {
  $days = $traffic[0]['traffic']['days'];
  foreach ($days as $key => $day) {
    //print_r($day);
    echo '<tr>';
    echo '<td>'.$day['date']['year'].'-'.$day['date']['month'].'-'.$day['date']['day'].'</td>';
    echo '<td>'.kbytes_to_string($day['tx']).'</td>';
    echo '<td>'.kbytes_to_string($day['rx']).'</td>';
    echo '</tr>';
  }

}
echo '
</table>
';





$info = json_decode(getSystemInfo(),true);

echo'
<table border="1" style="width:30%">
<tbody>
<caption>Memory</caption>
<tr>
<th>Total</th>
<th>Free</th>
<th>Usage</th>
<th>Cached</th>
</tr>
<tr>
<td>'.$info['MEMORY']['total'].'</td>
<td>'.$info['MEMORY']['free'].'</td>
<td>'.$info['MEMORY']['usage'].'</td>
<td>'.$info['MEMORY']['cached'].'</td>
</tr>
</tbody>
</table>
';

echo'
<table border="1" style="width:30%">
<tbody>
<caption>Swap</caption>
<tr>
<th>Total</th>
<th>Free</th>
<th>Usage</th>
<th>Cached</th>
</tr>
<tr>
<td>'.$info['MEMORY']['swap_total'].'</td>
<td>'.$info['MEMORY']['swap_free'].'</td>
<td>'.$info['MEMORY']['swap_usage'].'</td>
<td>'.$info['MEMORY']['swap_cached'].'</td>
</tr>
</tbody>
</table>
';

echo'
<table border="1" style="width:25%">
<tbody>
<caption>Disk</caption>
<tr>
<th>Total</th>
<th>Free</th>
<th>Usage</th>
</tr>
<tr>
<td>'.$info['DISK']['total'].'</td>
<td>'.$info['DISK']['free'].'</td>
<td>'.$info['DISK']['usage'].'</td>
</tr>
</tbody>
</table>
';

echo'
<table border="1" style="width:25%">
<tbody>
<caption>CPU Load</caption>

<tr>
<td>'.$info['CPU']['0'].'</td>
<td>'.$info['CPU']['1'].'</td>
<td>'.$info['CPU']['2'].'</td>
</tr>
</tbody>
</table>
';

//$ips = shell_exec("netstat -ntu | grep :80 | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | awk '{print $2}'");
$ips = shell_exec("netstat -ntup | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | grep -v ::1: | grep -v 'servers)' | grep -v Address");
echo'
<table border="1" style="width:50%">
<tbody>
<caption>Network</caption>
<tr>
<th>IP</th>
<th>Country</th>
<th>Region</th>
<th>City</th>
</tr>
';
$ipArray = explode("\n", $ips);
foreach ($ipArray as $key => $value) {
  $fields = explode(' ', $value);
  $ip = explode(':',$fields['7']);


  if (filter_var($ip['0'], FILTER_VALIDATE_IP)) {
    $location = file_get_contents("http://ip-api.com/json/$ip[0]?fields=status,country,regionName,city,isp");
    $location = json_decode($location,true);

    echo '
    <tr>
    <td>'.$fields['7'].'</td>
    <td>'.$location['country'].'</td>
    <td>'.$location['regionName'].'</td>
    <td>'.$location['city'].'</td>
    </tr>
    ';
  }

}
echo '
</tbody>
</table>
';
?>
