<?php header ("Content-type: image/svg+xml"); ?>
<!-- Created with openBikeCad -->
<?php
include_once('parcebcad.php');
include_once('attributes.php');
function pow2($a){ return pow($a,2); }
function dsin($a){ return sin(M_PI*$a/180); }
function dasin($a){ return 180/M_PI*asin($a); }
function dcos($a){ return cos(M_PI*$a/180); }
function dacos($a){ return 180/M_PI*acos($a); }
function dtan($a){ return tan(M_PI*$a/180); }
function datan($a){ return 180/M_PI*atan($a); }
class bike{
    private $w;
    private $h;
    private $x1;
    private $y1;
    private $x2;
    private $y2;
    private $absangles;
    private $dimensions;
    private $bikecadfile;
    private $bikeheight;
    private $picratio;
    public function __construct($w, $h, $data = array()){
        $this->dimensions['tubes']  = 
            array('tt' => 600, 'dt' => 600, 'st' => 600, 'ht' => 125, 
            'cs' => 388, 'ss' => -1, 'bb' => 80, 'fork' => 360);
        $this->dimensions['angles'] = 
            array('csc' => 10, 'ct' => 75, 'ht' => 77, 'tt' => -0.7, 'st'=>0);
        $this->dimensions['offset'] = 
            array('fork' => 40, 'stss' => 4, 'sttt' => 3, 'htdt' => 5, 
            'httt' => 4,'bbcs'=> 20);
        $this->dimensions['dia']    = 
            array('fw' => 674, 'rw' => 674, 'tt' => 28.6, 'dt' => 31.7, 
            'st' => 28.6, 'ss' => 18, 'cs' => 22, 'cs2' => 15, 
            'ss2' => 14, 'bb' => 40);
        $this->dimensions['bbh']    = 285.0;   //Bottom braket heigth
        $this->dimensions['fcd']    = 570.0;   //Front center distance
        $this->dimensions['csm']    = 0;     //Chainstay calculation method (weel gab or chainstay length
        $this->dimensions['rww']    = 135;
        $this->dimensions['fww']    = 110;
        foreach($data as $i=>$d){
            if(is_array($d)){
                foreach($d as $j=>$v){
                    $this->dimensions[$i][$j] = $v;
                }
            }
            else $this->dimensions[$i] = $v;
        }
        $csm = $this->dimensions['csm'];
        $cs  = $this->dimensions['tubes']['cs'];
        $bb  = $this->dimensions['tubes']['bb'];
        $bbcs= $this->dimensions['offset']['bbcs'];
        $csp = 0; //FIXME
        $rwd = $this->dimensions['dia']['rw']; //Should this be rww
        $std = $this->dimensions['dia']['st'];
        $sta = $this->dimensions['angles']['st'];
        $gamma = 0;
        if($csm == 1){
          $tirecap = $cs;
          $i = 5;
          while($i>0){
            $csp = ($rwd/2 + $tirecap + $std/2)/dcos(90 - $sta + $gamma);
            $gamma = dasin(($dia['rw']/2 - $bbh)/$csp);
            $i--;
          }
          $cs = sqrt(pow2($csp) + pow2($rwd/2 - ($bb/2 - $bbcs)));
        }
        $this->dimensions['angles']['csc'] = $gamma;

        $this->bikecadfile      = "default.bcad";
        if($w>9000)
            $w = 8192;
        if($w>5000)
            $w = 4096;
        $this->w = $w;
        $this->h = $h;

        $this->rear_triangle_dimensions();
        $this->front_triangle_dimensions();
        $this->picture_ratio();
        $this->xy();
    }
    private function picture_ratio(){
        $angles = $this->dimensions['angles'];
        $tubes = $this->dimensions['tubes'];
        $dia = $this->dimensions['dia'];
        $rww = $this->dimensions['rww'];
        $bbh = $this->dimensions['bbh'];
        if($angles['tt'] < 0){
          //seat tube top is the top of the image
          if($bbh < max($dia['rw']/2, $dia['fw']/2)){
            $this->bikeheight = $tubes['st']*dsin($angles['st']) + $dia['rw']/2;
          }
          else{
            $this->bikeheight = $tubes['st']*dsin($angles['st']) + $bbh;
          }
        }
        else{
          //Headtube top is the top of the image
          if($bbh < ($dia['rw'] + $dia['fw'])/4){
            $this->bikeheight = $tubes['st']*dsin($angles['st']) + $tubes['tt']*dsin($angles['tt']) + $dia['rw']/2;
          }
        }
        $this->picratio = $this->h/$this->bikeheight;
    }
    private function xy(){
        $angles = $this->dimensions['angles'];
        $tubes = $this->dimensions['tubes'];
        $dia = $this->dimensions['dia'];
        $rww = $this->dimensions['rww'];
        $bbh = $this->dimensions['bbh'];
        $bikeheight = $this->bikeheight;
        $picratio = $this->picratio;
        $w = $this->w;
        $y1['ground'] = ($bikeheight)*$picratio - 1;
        $x1['ground'] = 0;
        $y2['ground'] = $y1['ground'];
        $x2['ground'] = $w;
      
        $y1['cs'] = $y1['ground'] - $dia['rw']/2*$picratio;
        $x1['cs'] = $dia['rw']/2*$picratio;
        $y2['cs'] = $y1['ground'] - $bbh*$picratio;
        $x2['cs'] = $x1['cs'] + ($tubes['csp']*dcos($angles['csc']))*$picratio;
      
  $y1['st'] = $y2['cs'];
  $x1['st'] = $x2['cs'];
  $y2['st'] = $y2['cs'] - $tubes['st']*dsin($angles['st'])*$picratio;
  $x2['st'] = $x2['cs'] - $tubes['st']*dcos($angles['st'])*$picratio;


  $y1['ss'] = $y1['cs'];
  $x1['ss'] = $x1['cs'];
  $y2['ss'] = $y1['cs'] - $tubes['ssp']*dsin($angles['ss'])*$picratio;
  $x2['ss'] = $x1['cs'] + $tubes['ssp']*dcos($angles['ss'])*$picratio;


  $y1['tt'] = $y2['ground'] - $seatheight*$picratio;
  $x1['tt'] = $x2['st'] - ($y2['st'] - $y1['tt'])/dtan($angles['st']);
  $y2['tt'] = $y1['tt'] - $tubes['tt']*dsin($angles['tt'])*$picratio;
  $x2['tt'] = $x1['tt'] + $tubes['tt']*dcos($angles['tt'])*$picratio;



  $x2['fork'] = $x2['cs'] + (sqrt(pow2($fcd) - pow2($dia['fw']/2 - $bbh)))*$picratio;
  $y2['fork'] = $y1['cs'] + ($dia['rw']/2 - $dia['fw']/2)*$picratio;
  $y1['fork'] = $y2['fork'] - ($fl*dsin($angles['ht']) - $fo*dcos($angles['ht']))*$picratio;
  $x1['fork'] = $x2['fork'] - ($fl*dcos($angles['ht']) + $fo*dsin($angles['ht']))*$picratio;
  
  $y1['dt'] = $y2['cs'];
  $x1['dt'] = $x2['cs'];
  $y2['dt'] = $y2['cs'] - $tubes['dt']*dsin($angles['dt'])*$picratio;
  $x2['dt'] = $x2['cs'] + $tubes['dt']*dcos($angles['dt'])*$picratio;
  
  $y1['ht'] = $y1['fork'] - ($offset['htb'])*dsin($angles['ht'])*$picratio;
  $x1['ht'] = $x1['fork'] - ($offset['htb'])*dcos($angles['ht'])*$picratio;
  $y2['ht'] = $y1['ht'] - $tubes['ht']*dsin($angles['ht'])*$picratio;
  $x2['ht'] = $x1['ht'] - $tubes['ht']*dcos($angles['ht'])*$picratio;

  $absangles['ht']=180-$angles['ht'];
  $absangles['st']=180-$angles['st'];
  $absangles['dt']=abs($angles['dt']);
  $absangles['tt']=abs($angles['tt']);
  $absangles['cs']=abs($angles['csc']);
  $absangles['ss']=abs($angles['ss']);
        $this->absangles = $absangles;
        $this->x1 = $x1;
        $this->y1 = $y1;
        $this->x2 = $x2;
        $this->y2 = $y2;
    }
    private function front_triangle_dimensions(){
        $angles = $this->dimensions['angles'];
        $tubes = $this->dimensions['tubes'];
        $offset = $this->dimensions['offset'];
        $dia = $this->dimensions['dia'];
        $bbh = $this->dimensions['bbh'];
        $fcd = $this->dimensions['fcd'];
        $fl = $this->dimensions['tubes']['fork'];
        $rww = $this->dimensions['rww'];

        //Helper variables
        $hf       = $dia['fw']/2 - $offset['fork']*dcos($angles['ht']);
        $fcd2     = sqrt( pow2($bbh - $hf) + pow2( sqrt( pow2($fcd) 
                - pow2($bbh - $dia['fw']/2)) - $offset['fork']*dsin($angles['ht'])) );
      
        //Front dimensions
        $bbfwca   = dasin(($dia['fw']/2 - $bbh)/$fcd);
        $bbfwc2a  = dasin(($hf - $bbh)/$fcd2);
        $alphai   = 60;
        $b        = $fl;
        //We need to iterate downtube values
        $i        = 5;
        while($i > 0){
          $a            = $fcd2;
          $b            = $fl + $ext['htl'] + $offset['htb'] 
                + 0.5*$dia['dt']/dsin($alpha) + 0.5*$dia['ht']/dtan($alpha);
          $tubes['dt']  = sqrt(pow2($a) + pow2($b) - 2*$a*$b*dcos($angles['ht'] 
                + $bbfwc2a));
          $c            = $tubes['dt'];
          $alpha        = dacos((pow2($b) - pow2($a) + pow2($c))/(2*$b*$c));
          $i--;
        }
        $angles['dtht'] = $alpha;
        $angles['dt']   = 180 - $alpha  - $angles['ht']; //??
      //  $angles['dt'] = dacos((pow2($a) - pow2($b) + pow2($c))/(2*$a*$c));
      
      
      
        $head_projectoin_on_wheel_center_ground = 
            ($fl + $tubes['ht'] + $ext['htl'] - $ext['htu'] 
            - $offset['fork'] * dtan($bbfwca))*dcos($angles['ht']) + $offset['fork']/dcos($bbfwca);
        $seattube_projection_on_bottombraket_center_ground = ($tubes['st'] 
              - $offset['sttt'])*dcos($angles['st']);
        $c    = sqrt(pow2($fcd) - pow2($dia['fw']/2 - $bbh)) 
              - $head_projectoin_on_wheel_center_ground 
              + $seattube_projection_on_bottombraket_center_ground;
      
        $angles['tt']     = 0;
        $angles['sttt']   = 72;
        $angles['httt']   = 118;
        $seatheight       = $tubes['st'];
        $i                = 5;
        while($i > 0){
            $seatheight_bb_to_tt  = ($tubes['st'] - ($ext['stu'] 
                    + $dia['tt']/(2*dsin($angles['sttt'])) 
                    - $dia['st']/(2*dtan($angles['sttt']))));
            $seattube_projection_on_bottombraket_center_ground = 
                    ($seatheight_bb_to_tt)*dcos($angles['st']);
            $seatheight           = 
                    $bbh + $seatheight_bb_to_tt*dsin($angles['st']);
        
            $headheight_hf_to_tt  = ($tubes['ht'] + $fl + $ext['htl']
                    - ($ext['htu'] + 0.5*$dia['tt']/dsin($angles['httt']) 
                    - 0.5*$dia['ht']/dtan($angles['httt'])));
            $headheight    = $hf + $headheight_hf_to_tt*dsin($angles['ht']);
            $head_projectoin_on_hf_center_ground = 
                    ($headheight_hf_to_tt)*dcos($angles['ht']);
            
            $c              = sqrt(pow2($fcd2) - pow2($hf - $bbh))  
                    - $head_projectoin_on_hf_center_ground 
                    + $seattube_projection_on_bottombraket_center_ground;
        
            $tubes['tt']    = sqrt(pow2($seatheight - $headheight) + pow2($c));
            $angles['tt']   = datan(($seatheight - $headheight) / $c);
            $angles['sttt'] = $angles['st'] - $angles['tt'];
            $angles['httt'] = 180 - $angles['sttt'] - $angles['ht'] 
                            + $angles['st'];
            $i--;
        }
  

        $angles['tt']     = datan(($headheight - $seatheight) / $c);
        $tubes['tt']      = sqrt(pow2($seatheight - $headheight) + pow2($c));
      
        $angles['stdt']   = 360 - $angles['sttt'] - $angles['httt'] 
                  - (180 - $angles['dtht']);
        //$angles['dt'] = 90 - $angles['dtht'] - $angles['ht'];

        $this->dimensions['angles'] = $angles;
        $this->dimensions['tubes'] = $tubes;
        $this->dimensions['dia'] = $dia;
        $this->dimensions['rww'] = $rww;
    }
    private function rear_triangle_dimensions(){
        $angles = $this->dimensions['angles'];
        $tubes = $this->dimensions['tubes'];
        $offset = $this->dimensions['offset'];
        $dia = $this->dimensions['dia'];
        $rww = $this->dimensions['rww'];
        $angles['csst']       = $angles['st'] - $angles['csc'];
        $tubes['ss']          = sqrt(pow2($tubes['st'] - $offset['stss']) 
                + pow2($tubes['cs']) - 2 * $tubes['cs'] * ($tubes['st'] 
                - $offset['stss']) * dcos($angles['csst']));
        $angles['ssst']       = dacos((pow2($tubes['ss']) + pow2($tubes['st']
                - $offset['stss']) - pow2($tubes['cs']))/(2*($tubes['st'] 
                - $offset['stss'])*$tubes['ss']));
        $angles['csss']       = 180 - $angles['ssst'] - $angles['csst'];
        $angles['ss']       = $angles['csss']-$angles['csc']; 

         //FIXME ACCURACY
        $tubes['ssp'] = sqrt(pow2($tubes['ss']) - pow2($rww/2 - $dia['st']/2));
        $this->dimensions['angles'] = $angles;
        $this->dimensions['tubes'] = $tubes;
        $this->dimensions['dia'] = $dia;
        $this->dimensions['rww'] = $rww;
    }

    private function drawLine3d($name, $x1, $y1, $z1, $x2, $y2, $z2, $color = 'black', $dotted = 0){
      return "<line id='$name$z1' x1='".round($x1,1)."' y1='".round($y1)."' z1='$z1' x2='".round($x2)."' y2='".round($y2)."' z2='$z2' style='stroke:$color;stroke-width:1".($dotted?";stroke-dasharray:5,5":"")."'/>";
    }
  
    private function drawLine($name, $x1, $y1, $x2, $y2, $color = 'black', $dotted = 0){
      return "<line id='$name' x1='".round($x1,1)."' y1='".round($y1)."' x2='".round($x2)."' y2='".round($y2)."' style='stroke:$color;stroke-width:1".($dotted?";stroke-dasharray:5,5":"")."'/>";
    }
    private function drawCircle($name, $x, $y, $r, $color, $fill = 0){
      return "<circle r='".round($r)."' cx='".round($x)."' cy='".round($y)."' fill='".($fill?"white":"none")."' stroke='$color'/>";
    }
    private function drawString($x1, $y1, $x2, $y2, $string, $color){
      return "";
    }

    public function __toString(){
        $ret = "<svg xmlns=\"http://www.w3.org/2000/svg\" id=\"bikeplan_svg\" width=\"$this->w\px\" height=\"$this->h\px\" >";
        $ret .= "<g id='bike'>";
        $angles = $this->dimensions['angles'];
        $tubes = $this->dimensions['tubes'];
        $dia = $this->dimensions['dia'];
        $rww = $this->dimensions['rww'];
        $absangles = $this->absangles;
        $x1 = $this->x1;
        $y1 = $this->y1;
        $x2 = $this->x2;
        $y2 = $this->y2;
        $picratio = $this->picratio;
    $ret .= $this->drawCircle( 'rearwheel'  , $x1['cs']  ,  $y1['cs']  , $dia['rw']/2*$picratio  , '#ccc' );
    $ret .= $this->drawCircle( 'frontwheel'  , $x2['fork']  ,  $y2['fork']  , $dia['fw']/2*$picratio  ,  '#ccc' );
    foreach($x1 as $key=>$value){
      $ret .= $this->drawLine ($key, $x1[$key],$y1[$key],$x2[$key],$y2[$key],'gray',1);
      if(isset($tubes[$key])){
        if(isset($dia[($key."d")])){
          $z1 = 60;
          if($key == 'cs')
            $z2 = round($tubes['bb']/2 - $offset['bbcs'], 1);
          else
            $z2 = round($dia['st']/2,1);
          $ret .= $this->drawLine3d ($key."_l1", 
            $x1[$key] - ($dia[$key."d"]/2)*dsin($absangles[$key])*$picratio, 
            $y1[$key] - ($dia[$key."d"]/2)*dcos($absangles[$key])*$picratio, 
            $z1,
            $x2[$key] - ($dia[$key]/2)*dsin($absangles[$key])*$picratio,
            $y2[$key] - ($dia[$key]/2)*dcos($absangles[$key])*$picratio, 
            $z2
          );
          $ret .= $this->drawLine3d ($key."_r1", 
            $x1[$key] + ($dia[$key."d"]/2)*dsin($absangles[$key])*$picratio, 
            $y1[$key] + ($dia[$key."d"]/2)*dcos($absangles[$key])*$picratio, 
            $z1,
            $x2[$key] + ($dia[$key]/2)*dsin($absangles[$key])*$picratio,
            $y2[$key] + ($dia[$key]/2)*dcos($absangles[$key])*$picratio, 
            $z2);
          $ret .= $this->drawLine3d ($key."_l2", 
            $x1[$key] - ($dia[$key."d"]/2)*dsin($absangles[$key])*$picratio, 
            $y1[$key] - ($dia[$key."d"]/2)*dcos($absangles[$key])*$picratio, 
            -$z1,
            $x2[$key] - ($dia[$key]/2)*dsin($absangles[$key])*$picratio,
            $y2[$key] - ($dia[$key]/2)*dcos($absangles[$key])*$picratio, 
            -$z2);
          $ret .= $this->drawLine3d ($key."_r2", 
            $x1[$key] + ($dia[$key."d"]/2)*dsin($absangles[$key])*$picratio, 
            $y1[$key] + ($dia[$key."d"]/2)*dcos($absangles[$key])*$picratio, 
            -$z1,
            $x2[$key] + ($dia[$key]/2)*dsin($absangles[$key])*$picratio,
            $y2[$key] + ($dia[$key]/2)*dcos($absangles[$key])*$picratio, 
            -$z2);
        }
        else {
          $ret .= $this->drawLine (
            $key."_l", 
            $x1[$key] - ($dia[$key]/2)*dsin($absangles[$key])*$picratio,
            $y1[$key] - ($dia[$key]/2)*dcos($absangles[$key])*$picratio,
            $x2[$key] - ($dia[$key]/2)*dsin($absangles[$key])*$picratio,
            $y2[$key] - ($dia[$key]/2)*dcos($absangles[$key])*$picratio
          );
          $ret .= $this->drawLine ($key."_r", 
            $x1[$key] + ($dia[$key]/2)*dsin($absangles[$key])*$picratio,
            $y1[$key] + ($dia[$key]/2)*dcos($absangles[$key])*$picratio,
            $x2[$key] + ($dia[$key]/2)*dsin($absangles[$key])*$picratio,
            $y2[$key] + ($dia[$key]/2)*dcos($absangles[$key])*$picratio
          );
        }
        //$this->drawString  ( 4  , ($x1[$key] + $x2[$key])/2 + pow(($y1[$key] - $y2[$key]),3)/5000000 , ($y1[$key] + $y2[$key])/2 - pow(($x1[$key] - $x2[$key]),3)/5000000 , round($tubes[$key],1)  , 'gray' );
      }
    }
    $ret .= $this->drawCircle('bb', $x2['cs']  ,  $y2['cs']  , $dia['bb']/2*$picratio, 'black', 1);
    $ret .= "</g></svg>";
        return $ret;
    }

}
?>
<?php
  if(isset($_GET['imw']))
    $w = $_GET['imw'];
  else
    $w = 1024;
  if(isset($_GET['h']))
    $h = $_GET['h'];
  else
    $h = $w*0.5;

  $_INPUT=$_GET + $_POST;

  foreach($primary as $attr){
    if(isset($_INPUT[$attr])){
      if(isset($tubes[$attr]))
        $tubes[$attr] = $_INPUT[$attr];
      elseif(isset($dia[substr($attr, 0, -1)]) && substr($attr, -1) == 'd')
        $dia[substr($attr,0,-1)] = $_INPUT[$attr];
      elseif(isset($ext[$attr]))
        $ext[$attr] = $_INPUT[$attr];
      elseif(isset($offset[$attr]))
        $offset[$attr] = $_INPUT[$attr];
      elseif(isset($angles[substr($attr, 0, -1)]) && substr($attr, -1) == 'a')
        $angles[substr($attr,0,-1)] = $_INPUT[$attr];
      else{
        $$attr = $_INPUT[$attr];
      }
    }
  }
//  $fl = $offset['fl'];
  foreach($diameters as $attr){
    if(isset($_INPUT[$attr])){
      if(isset($dia[substr($attr, 0, -1)]))
        $dia[substr($attr, 0, -1)] = $_INPUT[$attr];
      else
        $$attr = $_INPUT[$attr];
    }
  }
  foreach($offsets as $attr){
    if(isset($_INPUT[$attr])){
      if(isset($ext[$attr]))
        $ext[$attr] = $_INPUT[$attr];
      elseif(isset($offset[$attr]))
        $offset[$attr] = $_INPUT[$attr];
      else
        $$attr = $_INPUT[$attr];
    }
  }
//  $fo = $offset['fo'];
  if(isset($_INPUT['wg'])){
    $tubes['cs'] = $_INPUT['wg'];
    $csm=1;
  }
$bike = new bike(900,400);
echo $bike;

?>
