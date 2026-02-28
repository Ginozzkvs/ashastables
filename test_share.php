<?php
$ip='192.168.0.137';
$path='\\\\'.$ip.'\\Receipt';
$h=@fopen($path,'w');
echo $h ? 'opened' : 'failed';
if ($h) fclose($h);
