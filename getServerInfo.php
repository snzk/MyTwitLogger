<?PHP

//サーバー情報の取得
$SVinfo = getStringfromFile('server-info.txt');

//DBに接続してリンクIDを受け取る
$con = @mysql_connect($SVinfo[0],$SVinfo[1],$SVinfo[2]);
if($con)
{
  echo "接続成功"."<br />";
  var_dump($con);
}
else {
  die('Failed connecting to DataBase' .mysql_error());
}


function getStringfromFile($filePath)
{
  $i = 0;
  foreach(@file($filePath) as $line)
  {
    $lines[$i] = str_replace(array("\r\n","\r","\n"),'',$line);
    $i = $i + 1;
  }
  return $lines;
}

?>
