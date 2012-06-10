<?php

//自分のつぶやきをmySQLに記録する//

//サーバー情報の取得
$contents = @file('SVinfo.txt');
$i = 0;
foreach($contents as $line)
{
	$SVinfo[$i] = str_replace(array("\r\n","\r","\n"),'',$line);
	$i = $i + 1;
}

//DBに接続してリンクIDを受け取る
$con = @mysql_connect($SVinfo[0],$SVinfo[1],$SVinfo[2]);
if(!$con)
{
	die('Failed connecting to DataBase' .mysql_error());
}

//TXTファイルからDB情報を取得
$contents = @file('twDBinfo.txt');
$i = 0;
foreach($contents as $line)
{
	$DBinfo[$i] = str_replace(array("\r\n","\r","\n"),'',$line);
	$i = $i + 1;
}
mysql_select_db($DBinfo[0],$con);	//DBに移動

//漢字が文字化けするため事前に文字コードをUTF-8にする。そのうち直す
$sql = "SET NAMES utf8";
$rst = mysql_query($rst);

//重複防止のため、table内に保存している最新tweetIDを取得
$sqltweet = "SELECT tweetid FROM `tbl_tweets` ORDER BY tweetid DESC LIMIT 0,1";
$rsttweet = mysql_query($sqltweet,$con);
If($rsttweet)
{
	While($col = mysql_fetch_assoc($rsttweet))
	{
		$idmax = $col['tweetid'];
		echo "最新TweetIDは：".$idmax."<br />";
	}
}
else
{
	echo "DB上の最新TweetのID取得失敗。"."<br />";
}

include 'tw_OAuth.php';	//OAuth認証を通す

$param["screen_name"] = 'SNZK_Wa';//SecretKeyをtw_OAuthで呼び出しているのでこの名前はBlankでも動く
$api_url = "http://api.twitter.com/1/statuses/home_timeline.xml?count=200";	//XML形式でhome_timelineを取得する
$result = $consumer->sendRequest($api_url,$param, "GET");
$xml = new SimpleXMLElement($result->getBody());

//2.ここから取得できる分だけLoop回す
for ($i = 0; $i < count($xml->status); $i++)
{
	//2-1,取得したtweet情報の編集
	$cpostdate = date('YmdHis',	strtotime($xml->status[$i]->created_at));
	$year = intval(substr($cpostdate,0,4));
	$month = intval(substr($cpostdate,4,2));
	$day = intval(substr($cpostdate,6,2));
	$hour = intval(substr($cpostdate,8,2));
	$minute = intval(substr($cpostdate,10,2));
	$second = intval(substr($cpostdate,12,2));

	$cname = "'".$xml->status[$i]->user->screen_name."'";	//Screen Name
	$userid = $xml->status[$i]->user->id;	//ユーザーID
	$iconurl = $xml->status[$i]->user->profile_image_url;	//アイコンのURL
	$tweetid = $xml->status[$i]->id;	//tweetのID
	$ctweet = "'".$xml->status[$i]->text."'";	//tweet本文
	$client = $xml->status[$i]->source;
	
	echo $year."/".$month."/".$day."  ".$hour.":".$minute.":".$second."  ID:".$tweetid."<br />";
	echo $cname."：".$ctweet."　(".$client."から)<br />";
	If($tweetid > $idmax)
	{
		include('tw_GetUserInfo.php');
		$sqlist = "INSERT INTO `tbl_tweets`(`year`, `month`, `day`, `hour`, `minute`, `second`, `name`, `tweet`, `tweetid`) VALUES ($year,$month,$day,$hour,$minute,$second,$cname,$ctweet,$tweetid)";
		$rstist = mysql_query($sqlist,$con);
		If(!$rstist)
		{
			echo "Tweet登録失敗"."<br />";
		}
		else
		{
			echo " → 登録。"."<br />";
		}
	}
	else
	{
		echo "→登録済み。プログラム終了";
		break;
	}
}
mysql_close($con);

?>