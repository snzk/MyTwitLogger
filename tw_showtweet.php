<?php

//MySQLに保存した自分のTLを表示する//

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
	//echo $DBinfo[$i]."<br />";
	$i = $i + 1;
}
mysql_select_db($DBinfo[0],$con);	//DBに移動
$Month = date('m');
$Day = date('d');
$sql = "SELECT * 
		FROM  `tbl_tweets` 
		INNER JOIN  `tbl_twUser` ON tbl_tweets.name = tbl_twUser.screenname
		WHERE MONTH = $Month
		AND DAY = $Day
		ORDER BY tweetid DESC ";
$rst = mysql_query($sql,$con);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- CSS デザイン部分	開始 -->
	<style type="text/css">
		body {
			margin: 0 0 0 0;
			background-color: black;
			color: white
			}
		tweet{
			background: aqua;
			border: 7px solid #6699cc;
		}
		sname {
			font-size: 10px;
			color: #c0c1f4;
		}
		time1 {
			font-size: 10px;
			color: red
		}
		time2 {
			font-size: 10px;
			color: lime
		}
		bunsho {
			font-size: 11px; 
			color: white
		}
	</style>
	<!-- CSS デザイン部分 終了 -->
</head>
<body>
	<!-- ここからMySQLに格納したtweetをLOOPで回す -->
	<?PHP While($col = mysql_fetch_assoc($rst)): ?>
		<?PHP
			//文字列編集	tweet内にリンクが含まれるとき、aタグで囲みクリックで飛べるようにする
			//httpで始まるリンクの開始位置、終了位置を検索してURLのみを抽出する
			$urlstrt = strpos($col['tweet'],'http');
			if($urlstrt != FALSE){
				$urlend = strpos($col['tweet'],' ',$urlstrt);
				if($urlend == FALSE){
					$url = substr($col['tweet'], $urlstrt);
				}else{
					$url = substr($col['tweet'], $urlstrt, $urlend - $urlstrt);
				}
				//URLをaタグで囲み、元のtweetのURL部分と差し替える
				$linkurl = "<a href=\"".$url."\">".$url."</a>";
				$linktweet = str_replace($url,$linkurl,$col['tweet']);
			}else{
				$linktweet = $col['tweet'];
			}
		?>
		<time1><?=$col['year']."/".sprintf("%02d", $col['month'])."/".sprintf("%02d", $col['day']) ?></time1>
		<time2><?="<a href=\"".'http://www.twitter.com/'.$col['screenname'].'/status/'.$col['tweetid']."\">".
		$col['hour'].":".sprintf("%02d", $col['minute']).":".sprintf("%02d", $col['second'])."</a>"." " ?></time2>
		<sname><?=$col['screenname']?></sname>
		<bunsho><?=$linktweet."<br />" ?></bunsho>
	<?PHP endwhile; ?>
	<?PHP 	mysql_close($con); ?>
</body>
</html>