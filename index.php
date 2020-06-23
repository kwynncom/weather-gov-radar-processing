<?php 
// require_once('lightning/getHT.php');

$hthead = file_get_contents(__DIR__ . '/hthead.html');
if (PHP_SAPI !== 'cli') { echo $hthead; }
file_put_contents('/tmp/myrad.html', $hthead);
require_once('table.php');

// $ht .= getLHT();

file_put_contents('/tmp/myrad.html', $ht, FILE_APPEND);
if (PHP_SAPI !== 'cli') { echo $ht; }

$httail = "</body>\n</html>\n";
if (PHP_SAPI !== 'cli') echo $httail;
file_put_contents('/tmp/myrad.html', $httail, FILE_APPEND);
    
