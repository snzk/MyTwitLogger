<?php

  require_once("function.php");
  require_once("twitteroauth/twitteroauth.php");

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

  //漢字が文字化けするため事前に文字コードをUTF-8にする。そのうち直す
  $sql = "SET NAMES utf8";
  $rst = mysql_query($rst);

  //重複防止のため、table内に保存している最新tweetIDを取得
  $sqltweet = "SELECT tweetid FROM `tweets` ORDER BY tweetid DESC LIMIT 0,1";
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

  //twitteroauthライブラリを使ってOAuth認証を通す
  $oAuthKeys = getStringfromFile('key.txt');
  $consumerKey = $oAuthKeys[0];
  $consumerSecret = $oAuthKeys[1];
  $accessToken = $oAuthKeys[2];
  $accessTokenSecret = $oAuthKeys[3];
  $twObj = new TwitterOAuth($consumerKey,$consumerSecret,$accessToken,$accessTokenSecret);
  $req = $twObj->OAuthRequest("https://api.twitter.com/1.1/statuses/home_timeline.json","GET",array("count"=>"200"));
  //返ってきたJSONを格納して一つ一つのtweetをループしてSQLに登録する
  $tw_arr = json_decode($req);
  if (isset($tw_arr)) {
    foreach ($tw_arr as $key => $val) {

      //取得したtweet情報の編集
      $cpostdate = date('YmdHis',  strtotime($tw_arr[$key]->created_at));  //投稿日時
      $year = intval(substr($cpostdate,0,4));
      $month = intval(substr($cpostdate,4,2));
      $day = intval(substr($cpostdate,6,2));
      $hour = intval(substr($cpostdate,8,2));
      $minute = intval(substr($cpostdate,10,2));
      $second = intval(substr($cpostdate,12,2));

      $userid = "'".$tw_arr[$key]->user->id."'";  //ユーザーID
      $sname = "'".$tw_arr[$key]->user->screen_name."'";  //Screen Name
      $name = "'".$tw_arr[$key]->user->name."'";   //名前
      $iconurl = $tw_arr[$key]->user->profile_image_url;  //アイコンのURL
      $tweetid = $tw_arr[$key]->id_str;  //tweetのID
      $ctweet = "'".$tw_arr[$key]->text."'";  //tweet本文
      $client = $tw_arr[$key]->source;

      echo $year."/".$month."/".$day."  ".$hour.":".$minute.":".$second."  ID:".$tweetid."<br />";
      echo $sname."：".$userid."：".$ctweet."　(".$client."から)<br />";
      //取得したtweetIDがSQLに入っている値より大きかったら（＝新しかったら）SQLに保存する
      If($tweetid > $idmax) {
        //include('tw_GetUserInfo.php');
        $sqlist = "INSERT INTO `tweets`(`year`, `month`, `day`, `hour`, `minute`, `second`, `name`, `tweet`, `tweetid`) VALUES ($year,$month,$day,$hour,$minute,$second,$sname,$ctweet,$tweetid)";
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
      //取得したtweetIDがSQLに入っている値と同じかそれより小さかったらそのtweetと以降のtweetも既に取得済みと判断して処理終了
        {
          echo "→登録済み。プログラム終了";
          break;
        }
      }
  } else {
    echo 'つぶやきはありません。';
  }
  mysql_close($con);

?>
