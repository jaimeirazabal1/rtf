<?
$pattern=array("(&AMP;)","(&GT;)","(&LT;)","(&NBSP;)");
$replace=array("&amp;","&gt;","&lt;","&nbsp;");
$output=preg_replace($pattern,$replace,$input);
?>
