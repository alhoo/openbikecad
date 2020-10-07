<?php

/*
 * This file calculates tubelengths and angles, when some enough information is known
 * */
function pow2($a){ return pow($a,2); }
function dsin($a){ return sin(M_PI*$a/180); }
function dasin($a){ return 180/M_PI*asin($a); }
function dcos($a){ return cos(M_PI*$a/180); }
function dacos($a){ return 180/M_PI*acos($a); }
function dtan($a){ return tan(M_PI*$a/180); }
function datan($a){ return 180/M_PI*atan($a); }

  $tubes['tt']=600;      //Toptube length
  $tubes['dt']=600;      //Downtube length 
  $tubes['st']=600.0;    //Seattube length
  $tubes['ht']=123.7;    //headtube length
  $tubes['cs']=388.0;    //Chainstay length
  $tubes['ss']=0;        //Seatstay length 
  $tubes['bb']=80;        //
  $angles['csc']=10.0;   //Chainstay center angle
  $bbh=285.0;   //Bottom braket heigth
  $angles['st']=75.0;    //Seattube angle
  $angles['ht']=77.0;    //Headtube angle
  $angles['tt']=-0.7;    //Toptube angle
  $fo=40.0;     //Fork offset
  $fl=358.0;    //Fork length
  $fcd=570.0;   //Front center distance
  $csm = 0;
  $dia['fw']=674.0;   //Frontwheel diameter
  $dia['rw']=674.0;   //Rearwheed diameter
  $offset['stss'] = 4.0;
  $offset['sttt'] = 3.0;
  $offset['htdt'] = 5.0;
  $offset['httt'] = 4.0;

  $bikecadfile='default.bcad';
  include_once('parcebcad.php');
  include_once('attributes.php');

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
  $fl = $offset['fl'];  //Why?
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
  $fo = $offset['fo'];  //Why?
  if(isset($_INPUT['wg'])){
    $tubes['cs'] = $_INPUT['wg'];
    $csm=1;
  }



  if($csm == 1){
    $tirecap = $tubes['cs'];
    $i = 5;
    while($i>0){
      $tubes['csp'] = ($dia['rw']/2 + $tirecap + $dia['st']/2)/dcos(90 - $angles['st'] + $gamma);
      $gamma = dasin(($dia['rw']/2 - $bbh)/$tubes['csp']);
      $i--;
    }
    $tubes['cs'] = sqrt(pow2($tubes['csp']) + pow2($rww/2 - ($tubes['bb']/2 - $offset['bbcs'])));
  }
  $angles['csc'] = $gamma;

  //$offset['sttt'] =$values[''];
  //$offset['htdt'] =$values[''];
  //$offset['httt'] =$values[''];
  //  
  // Seat stay offset
  //  Seat tube extension
  //  Seat tube leading edge textfield
  //  Seat tube chord length textfield



  //Rear triangle dimensions
  $angles['csst']       = $angles['st'] - $angles['csc'];
  $tubes['ss']          = sqrt(pow2($tubes['st'] - $offset['stss']) + pow2($tubes['cs']) - 2 * $tubes['cs'] * ($tubes['st'] - $offset['stss']) * dcos($angles['csst'])); //c² = a² + b² - 2abcos(gamma);
  $angles['ssst']       = dacos((pow2($tubes['ss']) + pow2($tubes['st'] - $offset['stss']) - pow2($tubes['cs']))/(2*($tubes['st'] - $offset['stss'])*$tubes['ss']));
  $angles['csss']       = 180 - $angles['ssst'] - $angles['csst'];
  $angles['ss']       = $angles['csss']-$angles['csc']; 

  $tubes['ssp'] = sqrt(pow2($tubes['ss']) - pow2($rww/2 - $dia['st']/2));       //FIXME ACCURACY


  //Helper variables
  $hf = $dia['fw']/2 - $fo*dcos($angles['ht']);
  $fcd2 = sqrt( pow2($bbh - $hf) + pow2( sqrt( pow2($fcd) - pow2($bbh - $dia['fw']/2)) - $fo*dsin($angles['ht'])) );

  //Front dimensions
//  $c1                   = sqrt(pow2($fo) - pow2($fo*dcos($angles['ht'] - $angles['st'])));
  $bbfwca               = dasin(($dia['fw']/2 - $bbh)/$fcd);
  $bbfwc2a              = dasin(($hf - $bbh)/$fcd2);
  $alpha=       60;
  $b = $fl;
  //We need to iterate downtube values
  $i = 5;
  while($i > 0){
    $a  = $fcd2;
    $b  = $fl + $ext['htl'] + $offset['htb'] + 0.5*$dia['dt']/dsin($alpha) + 0.5*$dia['ht']/dtan($alpha);
    $tubes['dt']  = sqrt(pow2($a) + pow2($b) - 2*$a*$b*dcos($angles['ht'] + $bbfwc2a));
    $c = $tubes['dt'];
    $alpha         = dacos((pow2($b) - pow2($a) + pow2($c))/(2*$b*$c));
    $i--;
  }
  $angles['dtht'] = $alpha;
  $angles['dt'] = 180 - $alpha  - $angles['ht']; //??
//  $angles['dt'] = dacos((pow2($a) - pow2($b) + pow2($c))/(2*$a*$c));



  $head_projectoin_on_wheel_center_ground       = ($fl + $tubes['ht'] + $ext['htl'] - $ext['htu'] - $fo * dtan($bbfwca))*dcos($angles['ht']) + $fo/dcos($bbfwca);
  $seattube_projection_on_bottombraket_center_ground = ($tubes['st'] - $offset['sttt'])*dcos($angles['st']);
  $c                    = sqrt(pow2($fcd) - pow2($dia['fw']/2 - $bbh))  - $head_projectoin_on_wheel_center_ground + $seattube_projection_on_bottombraket_center_ground;



  $angles['tt']         = 0;
  $angles['sttt']       = 72;
  $angles['httt']       = 118;
  $seatheight = $tubes['st'];
  $i = 5;
  while($i > 0){
    $seatheight_bb_to_tt        = ($tubes['st'] - ($ext['stu'] + $dia['tt']/(2*dsin($angles['sttt'])) - $dia['st']/(2*dtan($angles['sttt']))));
    $seattube_projection_on_bottombraket_center_ground = ($seatheight_bb_to_tt)*dcos($angles['st']);
    $seatheight           = $bbh + $seatheight_bb_to_tt*dsin($angles['st']);

    $headheight_hf_to_tt        = ($tubes['ht'] + $fl + $ext['htl'] - ($ext['htu'] + 0.5*$dia['tt']/dsin($angles['httt']) - 0.5*$dia['ht']/dtan($angles['httt'])));
    $headheight           = $hf + $headheight_hf_to_tt*dsin($angles['ht']);
    $head_projectoin_on_hf_center_ground       = ($headheight_hf_to_tt)*dcos($angles['ht']);
    
    $c                    = sqrt(pow2($fcd2) - pow2($hf - $bbh))  - $head_projectoin_on_hf_center_ground + $seattube_projection_on_bottombraket_center_ground;

    $tubes['tt']          = sqrt(pow2($seatheight - $headheight) + pow2($c));
    $angles['tt']         = datan(($seatheight - $headheight) / $c);
    $angles['sttt']       = $angles['st'] - $angles['tt'];
    $angles['httt']       = 180 - $angles['sttt'] - $angles['ht'] + $angles['st'];
    $i--;
  }
  

  $angles['tt']         = datan(($headheight - $seatheight) / $c);
  $tubes['tt']          = sqrt(pow2($seatheight - $headheight) + pow2($c));

  $angles['stdt']       = 360 - $angles['sttt'] - $angles['httt'] - (180 - $angles['dtht']);
  //$angles['dt'] = 90 - $angles['dtht'] - $angles['ht'];

?>
