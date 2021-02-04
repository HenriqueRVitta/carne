<?php

session_start();


// Este primeiro header, corrigi o problema de acentua��o dos caracteres.
header('Content-Type: text/html; charset=iso-8859-1');
// Os dois headers seguintes, evitam que a p�gina seja armazenada em cache no navegador.
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past


//Conectando com o Banco
include ("../../includes/config.inc.php");
include ("../../includes/classes/paging.class.php");
include ("../../includes/classes/conecta.class.php");

$conec = new conexao;
$conec->conecta('MYSQL') ;

//recebendo o id do carne_tipodependente
$cod=$_POST["idCliente"];

$sqlQuery = "SELECT c.nometitular,g.datainicio,g.datafim,g.datagerou,g.valor,u.nome FROM carne_carnesgerados g
join carne_titular c on c.id = g.idtitular
join usuarios u on u.codigo = g.usuario
where idtitular ='".$cod."' order by g.datagerou asc limit 12";

$commit=mysql_query($sqlQuery) or die('ERRO na query'.$sqlQuery);

if (mysql_num_rows($commit) == 0){

	print "";

} else {
	
	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	$Print = "<table id='pagtos' name='pagtos' border='0' align='left' ".$cellStyle."  width='100%' bgcolor='#87CEFA'>";
	$Print.="<tr><td colspan='4'>";
	$Print.="<B>&Uacute;ltimos carn&ecirc;s gerados...</B></TD>";
	$Print.="</tr>";
	$Print.="<TR class='header'><td class='line'>"."Cliente"."</TD>"."<td class='line' width='10%'>"."Gerado em:"."</TD>"."<td class='line' width='10%'>"."Usu&aacute;rio"."</TD>"."<td class='line'>"."Per&iacute;odo Inicial"."</TD>"."<td class='line'>"."Per&iacute;odo Final"."</TD>"."<td class='line'>"."Vlr Gerado"."</TD></tr>";
	
	$j=2;
	
	while($row = mysql_fetch_array($commit)){

		if ($j % 2)
		{
			$trClass = "lin_par";
		}
		else
		{
			$trClass = "lin_impar";
		}
		$j++;
		
		$datagerou = date('d/m/Y H:i:s', strtotime($row['datagerou']));
		$datainicio = substr(date('d/m/Y H:i:s', strtotime($row['datainicio'])),0,10);
		$datafim = substr(date('d/m/Y H:i:s', strtotime($row['datafim'])),0,10);
		
		$Print.="<tr class='".$trClass."' id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
		$Print.="<td class='line'>".$row['nometitular']."</td>";
		$Print.="<td class='line'>".$datagerou."</td>";
		$Print.="<td class='line'>".$row['nome']."</td>";
		$Print.="<td class='line'>".$datainicio."</td>";
		$Print.="<td class='line'>".$datafim."</td>";
		$Print.="<td class='line'>".$row['valor']."</td>";
		$Print.="</tr>";
		
	
		
	}

	$Print.="</Table>";

	print $Print;
	
}
	
?>
