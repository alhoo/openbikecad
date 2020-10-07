<?php header ("content-type: text/xml"); echo "<?xml version='1.0'?>\n"; ?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body>
<?php
include_once("plotter.php");
include_once("data_loader.php");
?>
<div style="border:3px solid; width: 500px">
<?php
if(isset($_GET['tili'])){
    $d = new data_loader("tilitiedot.txt", 500, "x\td\ty\td");
    $d->sort_data_by_date();
    $d->finnish_account_value_to_float();
    $d->scalexy(1.0/40);
    $d->integrate();
    $t = new plotter(($d->get()));
    echo $t;
}
else{
    $d = new data_loader("gas_consumption/gas_consumption.data", 100, "x\ty");
    $d->sort_data_by_date();
    $d->scalexy(1.0/4);
    $t = new plotter(($d->get()));
    echo $t;
}
?>
</div>
</body>

</html>
