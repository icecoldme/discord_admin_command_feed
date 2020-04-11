# discord_ban_feed
by **Doc-Ice-Elm**

![enter image description here](https://pl-platoon.co.uk/homebrew/logo2.png)


**Run this query in SQL console:-**

 ALTER TABLE `adkats_records_main` ADD `Adminfeed` TINYINT(1) NOT NULL DEFAULT '0'  AFTER `adkats_web`;

**setup a discord channel & create a webhook**

**edit the ### in the PHP file with your own database details and url's :-**

$db['host']='localhost'

$db['uname']='###' //user name

$db['pass']='###' //password

$db['name']='###' //database name

adkat link
$adkat_url='###';

Discord webhook
$url = '###';

**last but not least set up cron job to run the php script every 5 mins.**

## use this at your own risk !

## always make backups first !
