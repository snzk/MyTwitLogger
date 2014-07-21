<?PHP

$container = getStringfromFile('sample.txt');
foreach ($container as $sample)
{
  echo $sample;
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
