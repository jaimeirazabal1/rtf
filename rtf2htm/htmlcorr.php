<?
$pattern=array("(\[[ ]*\]|<!--NONE-->|<font[^>]*>\n</font>)","((<b>[ ]?[^<]*)<b>)","((<i>[ ]?[^<]*)<i>)","((<u>[ ]?[^<]*)<u>)","(<b>[ ]?(<[^>]*>)</b>)","(<i>[ ]?(<[^>]*>)</i>)","(<u>[ ]?(<[^>]*>)</u>)","(<b>[ ]*</b>)","(<i>[ ]*</i>)","(<u>[ ]*</u>)","(<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'></([uib])>)","(<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='3'></([uib])>)","(<i><font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'>)","(<b><font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'>)","(<u><font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'>)","(<i><font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='3'>)","(<b><font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='3'>)","(<u><font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='3'>)","(</font>[\n ]*<br><font[^>]*>)");
$replace=array("","\\1","\\1","\\1","\\1","\\1","\\1","","","","</\\1><font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'>","</\\1><font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='3'>","<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'><i>","<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'><b>","<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'><u>","<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='3'><i>","<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='3'><b>","<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='3'><u>","\n<br>");
$output=preg_replace($pattern,$replace,$input);
?>
<? do {
    $input=$output;
    $output=preg_replace("(<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'>([\n ]*)<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'>)","<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'>\\1",$input);
    } while ($input!=$output); ?>
<? do {
    $input=$output;
    $output=preg_replace("(<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='3'>([\n ]*)<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='3'>)","<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='3'>\\1",$input);
    } while ($input!=$output); ?>
<? do {
    $input=$output;
    $output=preg_replace("(<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='3'>([\n ]*)<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'>)","<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='3'>\\1",$input);
    } while ($input!=$output); ?>
<? do {
    $input=$output;
    $output=preg_replace("(<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='1'>([\n ]*)<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='1'>)","<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='1'>\\1",$input);
    } while ($input!=$output); ?>
<?
$pattern=array("(</li></ul><br><ul><li>|</li></ul>\n<br>\n<ul><li>)");
$replace=array("</li></ul>\n<ul><li>");
$output=preg_replace($pattern,$replace,$input);
?>
<? do {
    $input=$output;
    $output=preg_replace("(([a-zA-Z0-9])<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'>([a-zA-Z0-9]))","\\1\\2",$input);
    } while ($input!=$output); ?>
<? do {
    $input=$output;
    $output=preg_replace("(([a-zA-Z0-9])<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='3'>([a-zA-Z0-9]))","\\1\\2",$input);
    } while ($input!=$output); ?>
<? do {
    $input=$output;
    $output=preg_replace("(([a-zA-Z0-9])<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='1'>([a-zA-Z0-9]))","\\1\\2",$input);
    } while ($input!=$output); ?>
<?
$pattern=array("(<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'></font>)","(<font)","(<p>)","(<br>)","(<a>)","(</a>)","(<!--SPACE-->)");
$replace=array("","</font><font","<p>\015\012","<br>\015\012","\015\012<a>","</a>\015\012"," ");
$output=preg_replace($pattern,$replace,$input);
?>
