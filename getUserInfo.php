<?PHP

  $num = strrpos($iconurl,'/');
  $iconname = substr($iconurl,$num+1);
  $data = file_get_contents($iconurl);
  file_put_contents('images/icon/'.$iconname,$data);

  $sqlusr = "SELECT * FROM `users` WHERE userid = $userid";
  $rstusr = mysql_query($sqlusr,$con);
  $rstnum = mysql_num_rows($rstusr);

  echo "INSERT INTO `users`(`userid`, `screenname`, `name`, `icon`) VALUES ($userid,$sname,$name,'$iconname')<br />";

  If($rstnum == 0){
    $sqlusr = "INSERT INTO `users`(`userid`, `screenname`, `name`, `icon`) VALUES ($userid,$sname,$name,'$iconname')";
    $rstusr = mysql_query($sqlusr,$con);
    If($sqlusr){
      echo $cname."の情報を登録"."<br />";
    }else{
      echo $cname."の情報登録失敗"."<br />";
    }
  }else{
  }
?>
