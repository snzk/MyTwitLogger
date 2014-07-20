<?PHP
$contents = @file('SVinfo.txt');
$i = 0;
foreach($contents as $line)
{
  $SVinfo[$i] = str_replace(array("\r\n","\r","\n"),'',$line);
  $i = $i + 1;
}
?>
