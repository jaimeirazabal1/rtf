<?
/*insertrecord("(@@@@fonttbl.*@@@@info)","@@@@info")*/

$output=$input;

$pos=strpos($input,"\\fonttbl");
if (!($pos === false)) {
    
    $pos2=strpos($input,"\\info",$pos);
    
    if (!($pos === false)) $output=substr($input,0,$pos).substr($input,$pos2,strlen($input)-$pos2);
}    

/*insertrecord("(@@@@par(.*);})","@@@@par@@1<!--BACKSLASHSEMICOLON-->}")*/


$input=$output;

$pos=strpos($input,"\\par");

if (!($pos === false)) {

    $pos2=strpos($input,";}",$pos);
    
    if (!($pos === false)) $output=substr($input,0,$pos2)."<\02!--BACKSLASHSEMICOLON-->\02".substr($input,$pos2+1,strlen($input)-$pos2-1);
}    


?>