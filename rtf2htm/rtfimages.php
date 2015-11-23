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
#    (C) Martin Mevald, 2002
#    martinmv@penguin.cz
#    http://www.penguin.cz/~martinmv/
*/
$rtfimages=0;


$xmaxrtf=strlen($input)+1000;

function rtfpicturesearch() {
global $input,$xmaxrtf;

$rtfimage1=strpos($input,"\\pict ");
$rtfimage2=strpos($input,"\\pict\\");
$rtfimage3=strpos($input,"\\pict{");



if ($rtfimage1==0) $rtfimage1=$xmaxrtf;
if ($rtfimage2==0) $rtfimage2=$xmaxrtf;
if ($rtfimage3==0) $rtfimage3=$xmaxrtf;

$r=min($rtfimage1,$rtfimage2,$rtfimage3);

if ($r==$xmaxrtf) $r=0;
 
return $r;

}



$rtfimage=rtfpicturesearch();

while ($rtfimage>0) {

$rtfimages++;

$rtfbin=strpos($input,"\\bin",$rtfimage);
$rtfbrace=strpos($input,"}",$rtfimage);


if ($rtfbrace==0) $rtfbrace=$rtfbin+1;
if ($rtfbin==0) $rtfbin=$rtfbrace+1;

if ($rtfbin<$rtfbrace) {

$rtfbinspace=strpos($input," ",$rtfbin);
$rtfbinlength=substr($input,$rtfbin+4,$rtfbinspace-$rtfbin-4);

$input=substr($input,0,$rtfimage-1).substr($input,$rtfbinspace+$rtfbinlength+1);

} else {

$input=substr($input,0,$rtfimage-1).substr($input,$rtfbrace);


}

$rtfimage=rtfpicturesearch();
}

?>
