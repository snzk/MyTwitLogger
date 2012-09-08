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
	<!-- CSS デザイン部分	 -->
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
/*
		#paragraph-1:after{
			content: ".";
			height: 0;
			display: block;
			clear: both;
			visibility: hidden;
		}
*/
		sname {
			font-size: 10px;
			color: gray;
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
			font-size: 12px; 
			color: white
		}
	</style>
</head>
<body>
	<!-- ここからMySQLに格納したtweetをLOOPで回す -->
	<?PHP While($col = mysql_fetch_assoc($rst)): ?>
		<?PHP
			//DBから読み込んだアイコンのファイル名をテキストファイルに上書き保存する
			$fp = fopen('tw_iconpath.txt',"w");
			$imgname = @fwrite($fp,$col['icon'],strlen($col['icon']));
			fclose($fp);
		?>
		<sname><?=$col['screenname']?></sname>
		<time1><?=$col['year']."/".sprintf("%02d", $col['month'])."/".sprintf("%02d", $col['day']) ?></time1>
		<time2><?="　".$col['hour'].":".sprintf("%02d", $col['minute']).":".sprintf("%02d", $col['second'])."　" ?></time2>
		<bunsho><?=$col['tweet']."<br />" ?></bunsho>
		<tweet id="paragraph-1">
		</tweet>
	<?PHP endwhile; ?>
	<?PHP 	mysql_close($con); ?>
</body>
</html>