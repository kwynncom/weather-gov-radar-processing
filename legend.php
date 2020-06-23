<?php

set_include_path(get_include_path() . PATH_SEPARATOR . '/opt/kwynn');
require_once('kwutils.php');

class radar_colors {
    
    public function __construct() {
	$this->setColors();
    }
    
    public function c24todbz($c24) {
	if (  isset($this->c24todbz[$c24]))
	    return  $this->c24todbz[$c24];	
	kwas(0, 'color not found in legend');
    }
    
    public function ctodbz  ($r, $g, $b) {
	$c24 = self::rgbto24($r, $g, $b);
	return $this->c24todbz($c24);
    }
    
    public static function rgbto24($r, $g, $b) { return ($r << 16) + ($g << 8) + $b; }
    
    public function getc24todbza() { return $this->c24todbz; }
    
    public function getColorHuman($dbz) {
	if ($dbz < 5) return 'less than 5 dbz';
	kwas(isset($this->dbztoc[$dbz][3]), 'bad key to getColorHuman');
	return     $this->dbztoc[$dbz][3];    
    }
    
    public function getCSSRGB($dbz) {
	if (!isset($this->dbztoc[$dbz])) return "rgb(255,255,255);";
	$dbz = $this->dbztoc[$dbz];
	return					'rgb(' . $dbz[0] . ', ' . $dbz[1] . ',' . $dbz[2] .  ');';
    }
    
    private function setColors() {
    $this->dbztoc = [
        75 => [253, 253, 253, 'white'],
        70 => [152,  84, 198, 'purple dark'],
        65 => [248,   0, 253, 'purple light'],
        60 => [188,   0,   0, 'purple red'],
        55 => [212,   0,   0, 'red dark'],
        50 => [253,   0,   0, 'red'],
        47 => [253, 139,   0, 'orange to red worse'],
	45 => [253, 149,   0, 'orange to red better'],
	40 => [229, 188,   0, 'yellow dark'],
        35 => [253, 248,   2, 'yellow'],
        30 => [  0, 142,   0, 'green dark'],
        25 => [  1, 197,   1, 'green middle'],
        20 => [  2, 253,   2, 'green light'],
        15 => [  3,   0, 244, 'blue dark'],
        10 => [  1, 159, 244, 'blue middle'],
         5 => [  4, 233, 231, 'blue light'],
         0 => [100, 100, 100],
        -5 => [153, 153, 102],
       -10 => [204, 204, 153],
       -15 => [102,  51, 102],
       -20 => [153, 102, 153],
       -25 => [204, 153, 204],
       -30 => [204, 255, 255],
       -35 => [255, 255, 255],
    ];

    foreach($this->dbztoc as $dbz => $cs) {
	$r = $cs[0];
	$g = $cs[1];
	$b = $cs[2];
	$c24 = self::rgbto24($r, $g, $b);
	$this->c24todbz[$c24] = $dbz;
    }
}
}