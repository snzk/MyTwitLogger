<?PHP
  $num = strrpos($iconurl,'/');
  $iconname = substr($iconurl,$num+1);
  //そのままのサイズで画像を取得
  $data = file_get_contents($iconurl);
  //userのscreen_nameで画像をサーバーに保存する
  file_put_contents('/home/snzk/www/MyTwitLogger/images/icon/'.$iconname,$data);
  //ユーザー情報の登録
  //既に登録済みか調べる
  $sqlusr = "SELECT * FROM `tweets` WHERE userid = $userid";
  $rstusr = mysql_query($sqlusr,$con);
  $rstnum = mysql_num_rows($rstusr);
  echo "INSERT INTO `tweets`(`userid`, `screenname`, `name`, `icon`) VALUES ($userid,$sname,$name,'$iconname')<br />";
  If($rstnum == 0){
    //登録されていなければINSERT文を発行
    $sqlusr = "INSERT INTO `tweets`(`userid`, `screenname`, `name`, `icon`) VALUES ($userid,$sname,$name,'$iconname')";
    $rstusr = mysql_query($sqlusr,$con);
    If($sqlusr){
      echo $cname."の情報を登録"."<br />";
    }else{
      echo $cname."の情報登録失敗"."<br />";
    }
  }else{
  }
?>
