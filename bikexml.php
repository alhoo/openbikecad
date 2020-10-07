<?php header ("content-type: text/xml"); echo "<?xml version='1.0'?>\n"; ?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style type="text/css">
text {display:none;}
g:hover > g > text {display:inline;}
line { stroke: #aaf; stroke-width: 7px; }
rect { stroke: black; stroke-width: 2px; fill-opacity: 0.6;}
</style>
</head>
<body>
<?php
echo fread(fopen("bike.svg",'r'),filesize("bike.svg"));
?>
</body>
</html>
