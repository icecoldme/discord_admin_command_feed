<?php
//#####written by Doc-Ice-Elm April 2020####\\

$db['uname']='###' //user name

$db['pass']='###' //password

$db['name']='###' //database name

// adkat url
$adkat_url='';

// discord Webhook url
$url = '';

//DATABASE ACCESS
$link = mysqli_connect($db['host'], $db['uname'], $db['pass'],$db['name']);
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  

 $mapstats_result = mysqli_query($link,"SELECT  user_name FROM `adkats_users`  where user_role in (2,3) ORDER BY `adkats_users`.`user_id` ASC");
while($MS = mysqli_fetch_array($mapstats_result))
 {
$ms2[]="'".$MS['user_name']."'";
}
$admins=implode(',',$ms2);

list($Admin_feed,$record_id,$target_name,$source_name,$record_message,$command,$target_id,$battlelog_guid,$ServerName,$server_id) = mysqli_fetch_array(mysqli_query($link,"SELECT Adminfeed,record_id,target_name,source_name as Admin,record_message,ac.command_name,target_id,battlelog_guid,ServerName,arm.server_id FROM `adkats_records_main` arm
inner join `adkats_commands` ac on arm.command_type=ac.command_id
inner join `bfacp_settings_servers` bss on arm.server_id=bss.server_id
inner join `tbl_server` ts on arm.server_id=ts.ServerID
WHERE `source_name` IN (".$admins.") ORDER BY `record_time` DESC")); 

if 	($Admin_feed ==1) {
	echo 'no new Admin commands';
mysqli_close($link);
exit;
}else{	
$insertdata = mysqli_query($link,"UPDATE `".$db['name']."`.`adkats_records_main` SET Adminfeed=1 where record_id =".$record_id);

$discord_message = '**`'.$source_name.'`** issued `'.$command.'` command to ['.$target_name.']('.$adkat_url.'/players/'.$target_id.'/'.$target_name.') for '.$record_message.' on ';
}

$tlink = $adkat_url.'/players/'.$target_id.'/'.$target_name; 
$adkat_server=$adkat_url."/servers/live#!#id-".$server_id;
$data = json_encode(array(
// These 2 should usually be left out
// as it will overwrite whatever your
// users have set
    'username' => 'Admin Command Feed',
//'avatar_url' => '',
//'content' => '```css
//'.$ServerName.'```',
    'embeds' => array(
        array(
 'title' => $ServerName,
  'description' => $discord_message.'[server]('.$adkat_server.')',
 'url' => 'https://battlelog.battlefield.com/bf3/servers/show/pc/'.$battlelog_guid,
  'color' => 0xFFFFFF,
// 'timestamp' => $RT,
  'author' => array(
 //     'name' => 'Adkat Admin Link',
  //   'url' => $tlink ,
//	 "color" => '6AA84F'
     //'icon_url' => ''
  ),
 
        )
    )
));

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data)
));
 
echo curl_exec($ch);
mysqli_close($link);
