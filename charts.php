<?php

#####################################################################
#Script written by Chrissyx                                         #
#You may use and edit this script, if you don't remove this comment!#
#http://www.chrissyx.de(.vu)/                                       #
#####################################################################

#
###---AB GEHT'S!---###
#
$action = (!$_POST['action']) ? $_GET['action'] : $_POST['action'];

#
###---VOTE---###
#
if ($_GET['id'])
{
 $charts = file("charts.dat");
 $size = count($charts);
 for ($i=0; $i<$size; $i++)
 {
  $charts2 = explode("#", $charts[$i]);
  if ($i == ($_GET['id']-1)) $charts2[0]++;
  $towrite .= implode("#", $charts2);
 }
 $temp = fopen("charts.dat", "w");
 fwrite($temp, $towrite);
 fclose($temp);
 die("<meta http-equiv=\"refresh\" content=\"0; url=" . $_SERVER['PHP_SELF'] . "\">");
}

#
###---ADMIN---###
#
switch ($action)
{
 case "admin":
 if (file_exists("charts.dat")) die("<b>ERROR:</b> \"charts.dat\" existiert bereits!");
 ?>

Zu votenes in der Box angeben. Jede Zeile steht für etwas votebars! <font color="red">Kein "#" verwenden!!!</font><br>
<form action="charts.php" method="post">
<textarea name="text" rows="10" cols="100"></textarea><br><br>
<input type="hidden" name="action" value="make">
<input type="submit" value="Einrichten">
</form>

 <?php
 break;

#
###---ERSTELLEN---###
#
 case "make":
 echo("Daten werden erstellt...<br>\n");
 $array = explode("\n", $_POST['text']);
 $size = count($array);
 for ($i=0; $i<$size; $i++) $towrite .= "0#" . $array[$i] . "\n";
 $temp = fopen("charts.dat", "w");
 fwrite($temp, $towrite);
 fclose($temp);
 ?>

Daten erstellt! <a href="<?=$_SERVER['PHP_SELF']?>">Weiterleiten...</a><br>
<meta http-equiv="refresh" content="2; url=<?=$_SERVER['PHP_SELF']?>">

 <?php
 break;

 default:
 if (!file_exists("charts.dat")) die("<b>ERROR:</b> Keine \"charts.dat\" gefunden! Bitte <a href=\"" . $_SERVER['PHP_SELF'] . "?action=admin\">hier</a> anlegen!");
 else
 ?>

<table border="1">
 <tr>
  <th>Platz</th><th>Titel</th><th>Votes</th><th>Voten</th>
 </tr>

 <?php
#
###---PLÄTZE CHECK---###
#
 $array = file("charts.dat");
 $size = count($array);
 for ($i=0; $i<$size; $i++)
 {
  $array2 = explode("#", $array[$i]);
  $davor = $array2[0];
  if (($array2[0] > $votes_davor) and (($votes_davor) or ($votes_davor == "0")))
  {
   $x = $i-1;
   $temp_array = file("charts.dat");
   for ($j=0; $j<$x; $j++)
   {
    $temp_array2 = explode("#", $temp_array[$j]);
    $temp_towrite .= implode("#", $temp_array2);
   }
   $temp_array2 = explode("#", $temp_array[$x+1]);
   $temp_towrite .= implode("#", $temp_array2);
   $temp_array2 = explode("#", $temp_array[$x]);
   $temp_towrite .= implode("#", $temp_array2);
   for ($j=$x+2; $j<count($temp_array); $j++)
   {
    $temp_array2 = explode("#", $temp_array[$j]);
    $temp_towrite .= implode("#", $temp_array2);
   }
   $temp_temp = fopen("charts.dat", "w");
   fwrite($temp_temp, $temp_towrite);
   fclose($temp_temp);
   $i = count($array);
  }
  $votes_davor = $davor;
 }
 unset($votes_davor);

#
###---LISTE ZEIGEN---###
#
 $charts = file("charts.dat");
 $size = count($charts);
 for ($i=0; $i<$size; $i++)
 {
  $charts2 = explode("#", $charts[$i]);
  echo("  <tr><td>" . ($i+1) . "</td><td>" . $charts2[1] . "</td><td>" . $charts2[0] . "</td><td><a href=\"" . $_SERVER['PHP_SELF'] . "?id=" . ($i+1) . "\">Vote!</a></tr>\n");
 }
 ?>

</table><br>

<!-- DO NOT REMOVE THIS COPYRIGHT!!! -->
<font size="1">&copy; 2004, 2005 by <a href="mailto:chrissyx(at)chrissyx.com">Chrissyx</a></font><br><br>
<!-- DO NOT REMOVE THIS COPYRIGHT!!! -->

 <?php
 break;
}
?>