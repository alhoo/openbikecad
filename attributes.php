<?php
$primary= array('st', 'ht', 'fl', 'fcd', 'bbh', 'wg', 'sta', 'hta');
$diameters  = array('std', 'htd', 'ttd', 'dtd', 'csd', 'ssd', 'csd1', 'ssd1', 'fwd', 'rwd', 'bbd');
$offsets  = array('ssj', 'stu', 'htu', 'htl', 'rww', 'fo', 'bbcs');
$minor = $diameters;
foreach($offsets as $a)
  array_push($minor, $a);

$definitions=array(
    'st' => 'seattube length', 'ht' => 'headtube length',
    'fl' => 'fork length', 'fcd' => 'fork-center dist',
    'bbh' => 'BB height', 'wg' => 'wheel gap', 'sta' =>
    'seattube angle', 'hta' => 'headtube angle',
    'std' => 'seattube', 'htd' => 'headtube', 'ttd' => 
    'toptube', 'dtd' => 'downtube', 'csd' => 'chainstay', 
    'ssd' => 'seatstay', 'fwd' => 'frontwheel', 'rwd' => 
    'rearwheel', 'bbd' => 'bottom braket', 'ssj' => 'seatstay'
    , 'stu' => 'st above tt', 'htu' => 'ht above tt', 'htl' => 
    'ht under dt', 'fo' => 'fork', 'csd1' => 'cs wheelside', 
    'ssd1' => 'seatstay wheelside', 'rww' => 'Rear wheel width',
    'bbcs' => 'cs from outerface of bb', 'bb' => 'BB length'
  );

$bcadformat=array(
  'st' => 'Seat tube length',
  'ht' => 'Head tube length textfield',
  'cs' => 'CS textfield',
  'bb' => 'BB length',
  'csm' => 'CS measure style',
  'bbho' => 'BBheight offset',
  'sta' => 'Seat angle',
  'hta' => 'Head angle',
  'tta' => 'Top tube angle textfield',
  'fo' => 'Fork offset',
  'fl' => 'Fork length',
  'fcd' => 'FCD textfield',
  'fwd' => 'Wheel diameter front',
  'rwd' => 'Wheel diameter rear',
  'rww' => 'Dropout spacing',
  'bbd' => 'BB diameter',
  'htl' => 'Head tube lower extension',
  'htu' => 'Head tube upper extension',
  'stu' => 'Seat tube extension',
  'stss' => 'Seat stay junction',
  'ssj' => 'Seat stay junction',
  'ttd' => 'Head tube diameter',
  'std' => 'Seat tube diameter textfield',
  'dtd' => 'Down tube diameter',
  'htd' => 'Head tube diameter',
  'csd' => 'Chain stay vertical diameter',
  'ssd' => 'Seat stay main diameter',
  'csd1' => 'Chain stay back diameter',
  'ssd1' => 'Seat stay bottom diameter',
  'htb' => 'Lower stack height',
  'bbcs' => 'Chain stay position on BB'
);
