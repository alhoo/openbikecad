<?php

include_once('calculate.php');

  echo "<h3>Tube lengths:</h3><table>\n";
  echo "<tr><td>Tube name</td><td>length (mm)</td></tr>\n";
  foreach($tubes as $name=>$length){
    echo "<tr><td>$name </td><td>".round($length,1)."</td></tr>\n";
  }
  echo "</table><hr /><h3>Frame angles:</h3><br/>\n<table>";
  echo "<tr><td>Tube name</td><td>angle &deg;</td></tr>\n";
  foreach($angles as $name=>$angle){
    echo "<tr><td>$name </td><td>".round($angle,1)."&deg;</td></tr>\n";
  }
  echo "</table>";

  /*echo "<h3>Tube lengths:</h3><ul>\n";
  foreach($tubes as $name=>$length){
    echo "<li>$name ".round($length,1)." mm</li>\n";
  }
  echo "</ul><hr /><h3>Frame angles:</h3><br/>\n<ul>";
  foreach($angles as $name=>$angle){
    echo "<li>$name ".round($angle,1)."&deg;</li>\n";
  }
  echo "</ul>";*/
?>
