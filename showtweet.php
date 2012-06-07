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
$sql = "SELECT * FROM `tbl_tweets` ORDER BY tweetid DESC";
$rst = mysql_query($sql,$con);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style type="text/css">
		body {background-color: black}
		th {font-size: 14px; color: rgba(225,236,236,0.87)}
		td {font-size: 12px; color: rgba(225,236,236,0.87)}
	</style>
</head>
<body>
	<div>
	<table>	
		<tr>
		    <th>日付</th>
		    <th>ユーザー</th>
		    <th>つぶやき</th>
		</tr>
		<?PHP While($col = mysql_fetch_assoc($rst)): ?>
			<tr>
				<td> <?=$col['year']."/".$col['month']."/".$col['day'] ?></td>
				<td> <?="　".$col['hour'].":".$col['minute'].":".$col['second']/* ."<br />" */ ?></td>
				<td> <?=$col['name']." : ".$col['tweet']/* ."<br /><br />" */ ?></td>
			</tr>
		<?PHP endwhile; ?>
	</table>
	<?PHP 	mysql_close($con); ?>
	</div>
</body>
</html>