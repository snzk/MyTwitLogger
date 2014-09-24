<?PHP

  require_once("function.php");
  $textfile = getStringfromFile("server-info.txt");
  echo "$textfile[0]"."<br />";
  echo "$textfile[1]"."<br />";
  echo "$textfile[2]"."<br />";

?>
