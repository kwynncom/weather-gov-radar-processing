<?php

require_once('get.php');

function degToLab($deg) {

  $label = array ("N","NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S","SSW","SW", "WSW", "W", "WNW", "NW", "NNW");
  $dir = $label[ fmod((($deg + 11) / 22.5),16) ];
  return($dir);
}