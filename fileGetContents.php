<?PHP

  $iconurl = 'https://pbs.twimg.com/profile_images/3002583649/72fa39ae5a72abfef42ea939f52765a9_400x400.jpeg';
  $num = strrpos($iconurl,'/');
  $iconname = substr($iconurl,$num+1);
  $data = file_get_contents($iconurl);
  echo $iconname."<br />";
  echo "Image Size = ".file_put_contents('images/icon/'.$iconname,$data)."byte<br />";

?>
