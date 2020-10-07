<?php header ("Content-type: image/svg+xml"); ?>
<svg xmlns="http://www.w3.org/2000/svg" width="1000" height="1000" id="circles">
<?php
function pow2($a){ return $a*$a;}
function circle_intersections($r0,$r1 = array(0,0,1)){
    $x = array(array(0,0),array(0,0));
    $x0 = $r0[0];
    $x1 = $r1[0];
    $y0 = $r0[1];
    $y1 = $r1[1];
    $R0 = $r0[2];
    $R1 = $r1[2];
    $d2 = (pow2($x0 - $x1) + pow2($y0 - $y1));
    $d = sqrt($d2);
    $a = ($d2 - pow2($R1) + pow2($R0))/(2*($d));
    $h = sqrt(pow2($R0) - pow2($a));
    $x2 = $x0 + $a*($x1 - $x0)/$d;
    $y2 = $y0 + $a*($y1 - $y0)/$d;
    $x[0][0] = $x2 + $h*( $y1 - $y0 )/$d;
    $x[0][1] = $y2 - $h*( $x1 - $x0 )/$d;
    $x[1][0] = $x2 - $h*( $y1 - $y0 )/$d;
    $x[1][1] = $y2 + $h*( $x1 - $x0 )/$d;
    return $x;
}
function plot_circle($r){
    echo "<circle cx='$r[0]' cy='$r[1]' r='$r[2]' fill='none' style='stroke: black'/>\n"; 
}

$r1 = array(rand(100,200),rand(100,200),rand(70,120));
$r2 = array(rand(200,300),rand(200,300),rand(70,120));

plot_circle($r1);
plot_circle($r2);
$x = circle_intersections($r1,$r2);
plot_circle(array($x[0][0], $x[0][1], 5));
plot_circle(array($x[1][0], $x[1][1], 5));

?>
</svg>
