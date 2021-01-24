<?php

require_once('../OIDR/OpenIDReader.php');
require_once('../../Samples/PHP/Samples.php');

$OpenIDReader = new OpenIDReader();
$OpenIDReader->Debug(true);
$OpenIDReader->SetScanString($GBRUK);

$OpenIDReader->SetReadType('passport');
var_dump($OpenIDReader->Read());