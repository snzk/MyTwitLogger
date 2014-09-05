<?PHP

  echo 'SampleFile.'."<br />";
  $svInfo = getStringfromFile('server-info.txt');

  $con = @mysql_connect($svInfo[0],$svInfo[1],$svInfo[2]);
  if($con)
  {
    echo "接続成功"."<br />";
    var_dump($con);
  }
  else
  {
    die('Failed connecting to DataBase' .mysql_error());
  }

  $dbInfo = getStringfromFile('database-info.txt');

  $link = $mysql_select_db($dbInfo[0],$con)
  if (! $link)
  {
    die('Failed Selecting to DataBase' .mysql_error());
  }
  else
  {
    echo "DB選択成功"."<br />";
  }

  mysql_close($link)

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
