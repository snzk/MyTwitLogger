<?php

  require_once("function.php");
  $svInfo = getStringfromFile('server-info.txt');

  $con = @mysql_connect($svInfo[0],$svInfo[1],$svInfo[2]);
  if($con)
  {
    echo "接続成功"."<br />";
  }
  else
  {
    die('Failed connecting to DataBase' .mysql_error());
  }

  $dbInfo = getStringfromFile('database-info.txt');
  $db_selected = mysql_select_db($dbInfo[0],$con);
  if (!$db_selected)
  {
    die('Can not use'.$dbInfo[0] .mysql_error());
  }
  else
  {
    echo '$db_selected'. "= TRUE.  Succeeded select a DB"."<br />";
  }

  $sql = "SET NAMES utf8";
  $rst = mysql_query($rst);
  mysql_set_charset('utf8');
  mysql_select_db($dbInfo[0],$con);
  $Year = date('Y');
  $Month = date('m');
  $Day = date('d');
  $sql = "SELECT *
    FROM  `tweets`
    WHERE year = $Year
    AND month = $Month
    AND day = $Day
    ORDER BY tweetid DESC ";
  $rst = mysql_query($sql,$con);

?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- CSS デザイン部分  開始 -->
  <style type="text/css">
    body {
      margin: 0 0 0 0;
      background-color: #424242;
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
      //文字列編集  tweet内にリンクが含まれるとき、aタグで囲みクリックで飛べるようにする
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
  <?PHP   mysql_close($con); ?>
</body>
</html>
