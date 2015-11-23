<?php


function leef($fichero)
{
	$texto= file($fichero);
	$tamleef = sizeof($texto);
	$todo='';
	for ($n=0; $n<$tamleef;$n++)
		{ $todo = $todo.$texto[$n];}
	return $todo;
}

function rtf( $plantilla, $fsalida, $matequivalencias)
{
	$pre=time();
	$fsalida=$pre.$fsalida;
	//$link=mysqli_connect('localhost','root','','tu_db');

	$txtplantilla=leef($plantilla);

	$matriz=explode("sectd",$txtplantilla);
	$cabecera=$matriz[0]."sectd";
	$inicio=strlen($cabecera);
	$final=strrpos($txtplantilla,"}");
	$largo=$final-$inicio;
	$cuerpo=substr($txtplantilla,$inicio,$largo);

	$punt = fopen($fsalida ,"w");
	fputs($punt,$cabecera);

	//$result=$link->query($sql);
	//while($row=$result->fetch_array()){
		$despues=$cuerpo;

		foreach($matequivalencias as $dato){
			$datosql=$_POST[$dato[1]];
			$datosql=stripslashes($datosql);
			$datortf=$dato[0];
			$despues=str_replace($datortf,$datosql,$despues);
		}
		fputs($punt,$despues);
		$saltopag="\n";
		fputs($punt,$saltopag);
	//}
	fputs($punt,"}");
	fclose($punt);
	return $fsalida;
}



$plantilla="plantilla.rtf";
//$sql = "SELECT nombre,apellido,direccion,profesion FROM usuarios";
$equivalencia[0][0]="#*NOMBRE*#";
$equivalencia[0][1]="nombre";

$equivalencia[1][0]="#*APELLIDO*#";
$equivalencia[1][1]="apellido";

$equivalencia[2][0]="#*DIRECCION*#";
$equivalencia[2][1]="direccion";

$equivalencia[3][0]="#*PROFESION*#";
$equivalencia[3][1]="profesion";
if (isset($_POST['form'])) {
	# code...
	$salida=rtf($plantilla,"certificado.rtf",$equivalencia);
	$salida="<A href='$salida'>Obtener</A>&nbsp;<A href='?pdf=$salida'>Pdf</A>";
	echo("<p>$salida</p>");
}
if (isset($_GET['pdf'])) {
	leerdoc($_GET['pdf']);
}
?>
<p>Ingresa los Datos:</p>
<form action="" method="POST">
	<label>Nombre</label><br>
	<input type="text" name="nombre"><br>
	<label>apellido</label><br>
	<input type="text" name="apellido"><br>
	<label>direccion</label><br>
	<input type="text" name="direccion"><br>
	<label>profesion</label><br>
	<input type="text" name="profesion"><br>
	<input type="submit" name="form" value="Enviar">
</form>
<a href="plantilla.rtf">Ver Plantilla</a>
<?php 

function leerdoc($documento)
{
	//require('fpdf/fpdf.php');
	require_once('rtf2html.php');
	require_once('aux_pdf.php');
	$reader = new RtfReader();
	$rtf = file_get_contents("$documento"); // or use a string
	$reader->Parse($rtf);

	$formatter = new RtfHtml();
	
   	$pdf=new PDF_HTML();
    $pdf->SetFont('Arial','',12);
    $pdf->AddPage();
    $text=$formatter->Format($reader->root);
    if(ini_get('magic_quotes_gpc')=='1')
        $text=stripslashes($text);
    $pdf->WriteHTML($text);
    $pdf->Output();
    exit;
}  
 ?>