<?
$pattern=array("(\\\\bliptag[0-9]*)","(\\\\blipuid[^}]*})");
$replace=array("\\\\pict "," ");
$output=preg_replace($pattern,$replace,$input);
?>
