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
#    (C) Martin Mevald, 2005
#    martinmv@penguin.cz
#    http://www.penguin.cz/~martinmv/
*/

function findtext($p) {
global $input,$maxrtf;


while ($p<=$maxrtf) {

$c=$input[$p];



if (($c=="{") or ($c=="}")) {$p++; continue ; }
elseif ($c=="\\")  {
		    $p++;
		    while (($p<=$maxrtf) and ($input[$p]!=' ')) $p++;
		    $p++;
		    
		    return ($p);
		    }
else return ($p);

$p++;
}
return -1;
}

$maxrtf=strlen($input);

$p=strpos($input,"^");

if ($p!=0) {

while (($p<$maxrtf) and ($p!=0)) {



    $p2=findtext($p+1);
    if ($p<0) return ;
    
    $input=substr($input,0,$p).substr($input,$p+1,$p2-$p-1)."^".substr($input,$p2,$maxrtf-$p2);
    
    
    
    $p=strpos($input,"^",$p2+1);
    
    
    
    }

}

/* znaky ## */

$p=strpos($input,"#");

if ($p==0) return ;

while (($p<$maxrtf) and ($p!=0)) {



    $p2=findtext($p+1);
    if ($p<0) return ;
    
    $input=substr($input,0,$p).substr($input,$p+1,$p2-$p-1)."#".substr($input,$p2,$maxrtf-$p2);
    
    
    
    $p=strpos($input,"#",$p2+1);
    
    
    
    }



    
?>