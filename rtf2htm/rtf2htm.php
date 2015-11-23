#!/usr/local/bin/php -Cq
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
#    (C) Martin Mevald, 2002, 2003, 2004, 2005
#    martinmv@penguin.cz
#    http://www.penguin.cz/~martinmv/
*/


$par_is_p=0;
$no_small_footnotes=0;
$count=0;

if ($argc>1) {
if ($argv[1]=="-par") {
    array_shift ($argv);
    $par_is_p=1;
    $count++;
    } }
if ($argc>1) {
if ($argv[1]=="-nosmall") {
    array_shift ($argv);
    $no_small_footnotes=1;
    $count++;
    } }
if ($argc>1) {
if ($argv[1]=="-par") {
    array_shift ($argv);
    $par_is_p=1;
    $count++;
    } }

if (($argc-$count)!=3) { 
echo "Usage:   rtf2htm [-par] [-nosmall] input_file.rtf output_file.html\n";
echo "-par     translate all paragraphs as \"p\" html command\n";
echo "-nosmall footnotes: font size as normal text\n\n";
exit; 
} 


@$fd=fopen($argv[1],"r");

if ($fd==NULL) {
echo "File ".$argv[1]." not found.\n\n";
exit;

}

$input = fread ($fd,filesize($argv[1]));

fclose ($fd);

require("rtftohtm_prep.php");

$input = $output;

require("rtfimages_correc.php");

$input = $output;

require("rtfimages.php");

/*$output = $input;*/

require("version.php");


if (($version_ext<>0) and ($version_ext<>1)) {
	    echo  "version.php - unknown version";
	    exit;
	    }

if ($version_ext) {

require("rtfrem.php");

}

require("rtftohtm.php");

$input=$output;
require("htmlparser.php");

$input=$output;
require("small_meta.php");


@$fd=fopen($argv[2],"w");

if ($fd==NULL) {
echo "I can't write to file ".$argv[1].".\n\n";
exit;
}

$text="<!DOCTYPE html public \"-//w3c//dtd html 4.0 transitional//cs\">
<html>
<head>
<meta HTTP-Equiv=\"Content-Type\" CONTENT=\"text/html; charset=iso-8859-2\">
</head>
<body text=\"#000000\" bgcolor=\"#FFFFFF\">
<font face=\"Verdana, Helvetica CE, Arial CE, Helvetica, Arial\">
<font size=\"2\">

";

fwrite($fd,$text);
fwrite($fd,$output);

$text="
</body>
</html>";

fwrite($fd,$text);


fclose($fd);
?>
