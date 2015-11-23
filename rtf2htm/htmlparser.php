<?
/*
#    This program is free software; you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation; either version 2 of the License, or
#    (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.
#
#    You should have received a copy of the GNU General Public License
#    along with this program; if not, write to the Free Software
#    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#
#    (C) Martin Mevald, 2003, 2004, 2005
#    martinmv@penguin.cz
#    http://www.penguin.cz/~martinmv/
*/

$table_special=0;

$par_html="</font>\n";

$flag_li=0;
$flag_ul=0;

setparbr(1);

$fontcenter="<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='3'>\n";
$fontright="<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'>\n";
$fontleft="<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'>\n";
$fontjustify="<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'>\n";

$footnotecolor1="<font color='#000000'>";
$footnotecolor2="</font>";

$footnote_txt="";
$footnote_count=0;
 
$stack_count=0;

function setparbr($x) {
global $ptext,$parbr,$par_is_p;

if ($par_is_p) $x=0;
$parbr=$x;
if ($parbr) $ptext="<br>"; else $ptext="<p>";
}

function newpar($text) {
global $struct,$output,$par_html;
global $flag_b,$flag_i,$flag_u,$ptext;
global $flag_li,$flag_ul,$parbr;

if ($flag_b) $output.="</b>";
if ($flag_i) $output.="</i>";
if ($flag_u) $output.="</u>";
if ($struct["SUP"]) $output.="</sup>";

if ($flag_li) $output.="</li>";
if ($flag_ul) $output.="</ul>";

$flag_li=0;
$flag_ul=0;

$output.=$par_html;


if (!$parbr) $output.="</p>";    
else if ($struct["QR"] or $struct["QC"]) $output.="</p>";


$output.="$text";

if ($flag_b) $output.="<b>";
if ($flag_i) $output.="<i>";
if ($flag_u) $output.="<u>";
if ($struct["SUP"]) $output.="<sup>";

}

function spush() {
global $stack,$stack_count,$struct,$flag_caps;
global $flag_b,$flag_i,$flag_u;

$struct["CAPS"]=$flag_caps;
$struct["B"]=$flag_b;
$struct["I"]=$flag_i;
$struct["U"]=$flag_u;

$stack[$stack_count++]=$struct;

}

function spop() {
global $stack,$stack_count,$struct,$flag_caps;
global $flag_b,$flag_i,$flag_u;



if ($stack_count==0) return ;
    
$struct=$stack[--$stack_count];

$flag_caps=$struct["CAPS"];
$flag_b=$struct["B"];
$flag_i=$struct["I"];
$flag_u=$struct["U"]; 
    
setparbr(!$struct["SAB"]);
}

function printflag($command,$state) {
global $output;

if ($state) $output.="<$command>";
else $output.="</$command>";
}

function scmp() {
global $stack,$stack_count,$struct,$flag_caps;
global $flag_b,$flag_i,$flag_u;
global $fontleft,$fontright,$fontcenter,$fontjustify;
global $output,$par_html,$ptext;
global $footnote_stack,$footnote_count,$footnote_txt;
                
if ($stack_count==0) return;

$x=$stack[$stack_count-1];

if ($x["FOOTNOTE"]<>$struct["FOOTNOTE"]) {



    $footnote_stack[$footnote_count-1]["TEXT"]=$output;
    
    $struct["CAPS"]=$flag_caps;
    $struct["B"]=$flag_b;
    $struct["I"]=$flag_i;
    $struct["U"]=$flag_u;
    $struct["FOOTNOTE"]=0;

    $footnote_stack[$footnote_count-1]["STRUCT_END"]=$struct;
    
    $output=$footnote_txt;
    $footnote_txt="";
    spop();
    $x=$stack[$stack_count-1];
    $struct["FOOTNOTE"]=0;
    
    }

if ($flag_b<>$x["B"]) printflag("b",$x["B"]);
if ($flag_i<>$x["I"]) printflag("i",$x["I"]);
if ($flag_u<>$x["U"]) printflag("u",$x["U"]);

if ($x["SUP"]<>$struct["SUP"]) printflag("sup",$x["SUP"]);

if (($x["QC"]<>$struct["QC"]) or ($struct["QR"]<>$x["QR"])) { /* change state */
    if ($x["QC"]) newpar("<p align=\"center\">".$fontcenter);
    else if ($x["QR"]) newpar("<p align=\"right\">".$fontright);
    else newpar("$ptext".$fontleft); 
	
    }
    
    
}

if (substr_count($input,"<table")!=substr_count($input,"</table>")) $table_special=1;

$flag_b=0;
$flag_i=0;
$flag_u=0;

$flag_b_r=0;
$flag_i_r=0;
$flag_u_r=0;


$flag_font=0;
$flag_table=0;
$table_text=0;
$flag_td=0;
$flag_caps=0;
$output="";

/* $fontleft="<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'>\n"; */
$fontleft0=strtolower("font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='2'");
$fontfootnote="<font face='Verdana, Helvetica CE, Arial CE, Helvetica, Arial' size='1'>\n";

/*$special_footnotes=1;*/

require("version.php");

if (($version_ext<>0) and ($version_ext<>1)) {
	    echo  "<p><H1>version.php - unknown version</H1>";
	    exit;
	    }

$special_footnotes=$version_ext;


$special_footnotes_flag=0;

$flag_image=0;
$flag_image_doc=substr_count($input,"##");

function htmlparser_text($htmltext) {
global $output,$fontleft;
global $flag_b,$flag_i,$flag_ul,$flag_font;
global $flag_table,$table_text,$flag_td;
global $table_special,$flag_u;
global $flag_b_r,$flag_i_r,$flag_u_r;
global $flag_caps,$ptext;

global $special_footnotes,$flag_image,$flag_image_doc;

if ($special_footnotes && !$flag_table && $flag_image_doc ) { /* ##1 images */

    $flgs=$fontleft;
    
    if ($flag_b) $flgs.="<b>";
    if ($flag_i) $flgs.="<i>";
    if ($flag_u) $flgs.="<u>";




    if ($flag_image) $htmltext=preg_replace("((##[0-9]*))","</td><tr><td>".$flgs."\\1",$htmltext,1);
    else {
	    $htmltext0=$htmltext;
	    $htmltext=preg_replace("((##[0-9]*))","<table><td>".$flgs."\\1",$htmltext,1);
	    if (strcmp($htmltext,$htmltext0)) $flag_image=1;
	    }
	    
}

/* special: end table */

if ($flag_caps) {
	$xinput=$htmltext;
	require("xcsupper.php");
	$htmltext=$xoutput;
	}


if ($table_special && $flag_table) { 



if (strlen(trim($htmltext))>40) {



if ($flag_font) {$output.="</font>";$flag_font=0;$flag_caps=0;}

if ($flag_b) {$output.="</b>";$flag_b=0;$flag_b_r=0;}
if ($flag_i) {$output.="</i>";$flag_i=0;$flag_i_r=0;}
if ($flag_u) {$output.="</u>";$flag_u=0;$flag_u_r=0;}

if ($flag_td) {$output.="</td>";$flag_td=0;}

$output.="</table>";
$flag_table=0;

$output.="</p>$ptext$fontleft\n";


$output.="$htmltext";

return;

} }

/* table without <td>*/
if (!$flag_td && $flag_table) { $output.="<td>$fontleft\n";$flag_td=1;
				$flag_font=1;
/*				if ($flag_b_r && !$flag_b) {$output.="<b>";$flag_b=1;}
				if ($flag_i_r && !$flag_i) {$output.="<i>";$flag_i=1;}
				if ($flag_u_r && !$flag_u) {$output.="<u>";$flag_u=1;}
*/				
}

/* without font */

if (!$flag_font) {$output.="$fontleft\n";$flag_font=1;$flag_caps=0;}

/* td in the table */

if ($flag_td)	{



				if ($flag_b_r && !$flag_b) {$output.="<b>";$flag_b=1;}
				if ($flag_i_r && !$flag_i) {$output.="<i>";$flag_i=1;}
        			if ($flag_u_r && !$flag_u) {$output.="<u>";$flag_u=1;}


		 
		}



$output.="$htmltext";

}

function htmlparser_comment($htmltext) {
global $output,$struct,$par_html;
global $flag_caps,$ptext;
global $fontleft,$fontright,$fontcenter,$fontjustify;
global $footnote_stack,$footnote_count,$footnote_txt;


$htmltext=strtoupper($htmltext);


if ((!strncmp($htmltext,"SA ",3)) or (!strncmp($htmltext,"SB ",3))) {
    strtok($htmltext," ");
    $x=strtok(" ");
    $struct["SAB"]=$x;
    setparbr(!$x);
    return;
    }

switch ($htmltext) {

    case "CAPS": $flag_caps=1; break;
    case "ENDCAPS": $flag_caps=0; break;
    case "GROUP": spush(); break;
    case "UNGROUP": scmp();spop();break;
    
    case "PARD": /* reset */
    
    if (($struct["QC"]) or ($struct["QR"])) newpar("$ptext".$fontleft);
    
    $struct["QC"]=0;
    $struct["QR"]=0;
    
    $struct["SAB"]=0;
    
    setparbr(1);

    
    break;
    
    
    case "PAR":

    if ($struct["QC"]) newpar("<p align=\"center\">".$fontcenter);
    else if ($struct["QR"]) newpar("<p align=\"right\">".$fontright);
    else  newpar("$ptext".$fontleft); 
    
    break;
    
    case "QC": 
    
    if (!$struct["QC"]) {
    
    newpar("<p align=\"center\">".$fontcenter);

    $struct["QC"]=1; 
    $struct["QR"]=0;
    
    }


    break;
    
    case "QR":
    
    if (!$struct["QR"]) {

    newpar("<p align=\"right\">".$fontright);

    $struct["QC"]=0;
    $struct["QR"]=1;
    
    }

    break;
    
    case "QL":
    case "QJ":
    
    if ($struct["QC"] or $struct["QR"]) {
    newpar("$ptext".$fontleft);    
    
    $struct["QC"]=0;
    $struct["QR"]=0;
    
    }
    
    break;
    
    case "FOOTNOTE":
    

    
    if (!$struct["FOOTNOTE"]) {

    spush();
    $footnote_stack[$footnote_count++]["STRUCT"]=$struct;
    
    $struct["FOOTNOTE"]=1;
    $footnote_txt=$output;
    $output="";
    
    }
    break;
    
    case "CHFTN":
    
    if ($struct["FOOTNOTE"]) { 

    $fname="<a name=\"$footnote_count\"><a href=\"#xx$footnote_count\">".$footnotecolor1."[$footnote_count]".$footnotecolor2."</a>";
    $output.=" ".$fname." ";
    } else {
    $footnote_count++;

    $ftarget="<a name=\"xx$footnote_count\">&nbsp;<a href=\"#$footnote_count\">".$footnotecolor1."[$footnote_count]".$footnotecolor2."</a>";
    $output.=" ".$ftarget." ";
    $footnote_count--;
    }
    
    break;
    
    default: 
    $output.="<!--$htmltext-->";
    
    }
}

function htmlparser_command($htmltext){
global $output;
global $flag_b,$flag_i,$flag_ul,$flag_font;
global $flag_table,$table_text,$flag_td;
global $p_count;
global $table_special,$flag_u;
global $flag_b_r,$flag_i_r,$flag_u_r;
global $flag_caps,$ptext;
global $special_footnotes,$special_footnotes_flag,$fontleft,$fontleft0,$fontfootnote;
global $struct;
global $flag_li,$flag_ul;
global $no_small_footnotes;

global $flag_image;


$htmltext0=strtolower($htmltext);

switch($htmltext0) {

case "li":
$flag_li=1;
$output.="<li>";
break;

case "/li":
$flag_li=0;
$output.="</li>";
break;

case "ul":
$flag_ul=1;
$output.="<ul>";
break;

case "/ul":
$flag_ul=0;
$output.="</ul>";
break;

case "sup":
$output.="<sup>";
$struct["SUP"]=1;
break;

case "/sup":
$output.="</sup>";
$struct["SUP"]=0;
break;


case "/font":
$output.="</font>";
$flag_font=0;
$flag_caps=0; 
break;

case "b":
if (!$flag_b) {$output.="<b>"; $flag_b=1; $flag_b_r=1;} ;  break;


case "i":
if (!$flag_i) {$output.="<i>"; $flag_i=1; $flag_i_r=1;} ;    break;


case "u":
if (!$flag_u) {$output.="<u>"; $flag_u=1; $flag_u_r=1;} ; break;

case "/b":
$flag_b_r=0;
if ($flag_b) {$output.="</b>"; $flag_b=0; $flag_b_r=0; } ; break;


case "/i":
$flag_i_r=0;
if ($flag_i) {$output.="</i>"; $flag_i=0; $flag_i_r=0; } ; break;

case "/u":
$flag_u_r=0;
if ($flag_u) {$output.="</u>"; $flag_u=0; $flag_u_r=0 ; } ; break;


default:

if ($special_footnotes && !$special_footnotes_flag && !$no_small_footnotes) { 

    if (!strncmp($htmltext0,"a href=\"#xx",11)) {
	$special_footnotes_flag=1;
	$fontleft=$fontfootnote;

	$output.="<$htmltext>"; 
	return; }
} 
	


if (!strncmp($htmltext0,"font",4)) {
    if ($special_footnotes_flag) {
    	
	    $output.=$fontleft;
	    $flag_font=1; $flag_caps=0; return;
}	    

$flag_font=1; $flag_caps=0; $output.="<$htmltext>"; return; 

} 

elseif (!strncmp($htmltext0,"table",5)) {
	    $table_text=0;
	    if ($flag_table && $table_special) $output.="<tr>";
	    else {
		
		if ($flag_image) {
				    $output.="</b></i></u></td></table>";
				    if ($flag_b) $output.="<b>";
				    if ($flag_i) $output.="<i>";
				    if ($flag_u) $output.="<u>";

				    $flag_image=0;
				}
				
		spush(); $output.="<$htmltext>"; $flag_table=1;
		}
	    return;
	    } 
	    
             
elseif  (!strncmp($htmltext0,"/table",6)) {
     $table_text=0;
     if (!$table_special) {spop(); $output.="<$htmltext>"; $flag_table=0;}
     elseif ($flag_table)  {$output.="<tr>"; }
     return;
     } 
    
elseif (!strncmp($htmltext0,"td",2)) { 
$p_count=0;
$table_text=0;
if ($flag_td) { if ($flag_font) $output.="</font>"; $output.="</td>";}
$flag_td=1; $flag_font=0; 

$flag_b=0;
$flag_i=0;
$flag_u=0;

$output.="<$htmltext>";
return;}
elseif (!strncmp($htmltext0,"/td",3)) { 
$p_count=0;
if ($flag_b) {$output.="</b>";$flag_b=0;}
if ($flag_i) {$output.="</i>";$flag_i=0;}
if ($flag_u) {$output.="</u>";$flag_u=0;}
if ($flag_font) $output.="</font>";
$table_text=0;
$flag_td=0; $flag_font=0; 
$output.="<$htmltext>"; 
return;}
	
$output.="<$htmltext>";

}



}

function htmlparser($i)
{
$off=0;
$lng=strlen($i);
$maxx=$lng+1;

$comm_pos=-1;
$cmd_pos=-1;

do {

if ($off>=$maxx)  return;

if ($off>$comm_pos) {
    $comm_pos=strpos($i,"<!--",$off);
    if ($comm_pos === false) $comm_pos=$maxx;
    }
    
if ($off>$cmd_pos)  {
    $cmd_pos=strpos($i,"<",$off);
    if ($cmd_pos === false) $cmd_pos=$maxx;
    }

$off++; 

if (($cmd_pos>$off) && ($comm_pos>$off)) {


    $end_text=min($cmd_pos,$comm_pos); /*strpos($i,"<",$off);*/
    /*if ($end_text === false) $end_text=$maxx;*/
    
    $htmltext=substr($i,$off,$end_text-$off);
    if ($htmltext)   htmlparser_text($htmltext);
    $off=$end_text;

    } elseif ($comm_pos<=$off) {
    
    $end_text=strpos($i,"-->",$off);
    if ($end_text === false) $end_text=$maxx;    

    $htmltext=substr($i,$comm_pos+4,$end_text-$comm_pos-4);
    $htmltext=trim($htmltext);
	    
    if ($htmltext)  htmlparser_comment($htmltext); 
    $off=$end_text+2;		

    } elseif ($cmd_pos<=$off) {
    
    $end_text=strpos($i,">",$off);
    if ($end_text === false) $end_text=$maxx;

    $htmltext=substr($i,$cmd_pos+1,$end_text-$cmd_pos-1);
    $htmltext=trim($htmltext);
    if ($htmltext)  htmlparser_command($htmltext);
    $off=$end_text;
			
    
    } 

   


 } while (1);

}

function dump($variable,$rec) {
foreach ($variable as $key => $x1) {
    
    if (is_array($x1)) dump($x1,$rec."[\"$key\"] ");
    else echo $rec."[\"$key\"]= |$x1|\n";
    
}  

}


htmlparser($input);

/* footnotes */

$count=0;



/*dump($footnote_stack,"\$footnote_stack ");*/

while ($count<$footnote_count) {
    newpar("$ptext".$fontfootnote);
    $stack[$stack_count++]=$footnote_stack[$count]["STRUCT"];
    $struct["FOOTNOTE"]=0;
    scmp();
    spop();
    $x=$footnote_stack[$count]["TEXT"];
    
    if (!$no_small_footnotes) {
    $x=str_replace("' size='3'>","' size='1'>",$x);
    $x=str_replace("' size='2'>","' size='1'>",$x);
    }
    
    $output.=$x;
    $struct=$footnote_stack[$count]["STRUCT_END"];
    $count++;
}


if ($flag_image) {
				    $output.="</b></i></u></td></table>";
				    if ($flag_b) $output.="<b>";
				    if ($flag_i) $output.="<i>";
				    if ($flag_u) $output.="<u>";
				    
}

$input=$output;
$output="";


require("htmlcorr.php");


?>