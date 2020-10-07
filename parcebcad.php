<?php
include_once('attributes.php');
  if(!isset($bikecadfile)) return
  $xml = XMLReader::open($bikecadfile);
  $values = array();
  while($xml->read()){
    $key = $xml->getAttribute( 'key' );
    if(strlen($key)){
      $value = $xml->readString();
      if(strlen($value))
        $values[$key] = $value;
    }
  }

  $tubes['st']  =$values['Seat tube length'];
  $tubes['ht']  =$values['Head tube length textfield'];
  $tubes['cs']  =$values['CS textfield'];
  $tubes['bb']  =$values[$bcadformat['bb']];
  $csm          =$values['CS measure style'];
  //$angles['csc']=$values['Chain stay bend angle'];
  // $bbh          =$values[''];
  $bbho         =$values['BBheight offset'];
  $angles['st'] =$values['Seat angle'];
  $angles['ht'] =$values['Head angle'];
  $angles['tt'] =$values['Top tube angle textfield'];
  $fo           =$values['Fork offset'];
  $fl           =$values['Fork length'];
  $offset['fo'] =$fo;
  $fl           =$values['Fork length'];
  $offset['fl'] =$fl;
  $offset['bbcs'] =$values[$bcadformat['bbcs']];
  $fcd          =$values['FCD textfield'];
  $fwd          =$values['Wheel diameter front'];
  $rwd          =$values['Wheel diameter rear'];
  $rww          =$values['Dropout spacing'];
  $dia['fw']      =$fwd;
  $dia['rw']      =$rwd;
  $dia['bb']      =$values['BB diameter'];
  $ext['htl']   =$values['Head tube lower extension'];
  $ext['htu']   =$values['Head tube upper extension'];
  $ext['stu']   =$values['Seat tube extension'];
  $offset['stss'] =$values['Seat stay junction'];
  $ext['ss']    = $values['Seat stay junction'];
  $dia['tt'] =  $values['Head tube diameter'];
  $dia['st'] =  $values['Seat tube diameter textfield'];
  $dia['dt'] =  $values['Down tube diameter'];
  $dia['ht'] =  $values['Head tube diameter'];
  $dia['cs'] =  $values['Chain stay vertical diameter'];
  $dia['ss'] =  $values['Seat stay main diameter'];
  $dia['csd'] =  $values['Chain stay back diameter'];
  $dia['ssd'] =  $values['Seat stay bottom diameter'];
  $offset['htb'] =  $values['Lower stack height'];


  if($csm == 1){
    $tirecap = $tubes['cs'];
    $gamma = 10;
    $i = 3;
    while($i>0){
      $tubes['cs'] = ($rwd/2 + $tirecap + $dia['st']/2)/dcos(90 - $angles['st'] + $gamma);
      $gamma = dasin(($rwd/2 - $bbh)/$tubes['cs']);
      $i--;
    }
  }
  $st=$tubes['st'];
  $ht=$tubes['ht'];
  $fcd=$fcd;
  $bbh=($rwd + $fwd)/4 + 2*$bbho;
  $wg=1;
  $sta=$angles['st'];//$sta;
  $hta=$angles['ht'];//$hta;

?>
