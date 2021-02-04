<?php

@session_start();

// Este primeiro header, corrigi o problema de acentua��o dos caracteres.
header('Content-Type: text/html; charset=iso-8859-1');
// Os dois headers seguintes, evitam que a p�gina seja armazenada em cache no navegador.
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

//Conectando com o Banco
include ("../../includes/config.inc.php");
include ("../../includes/classes/conecta.class.php");
$conec = new conexao;
$conec->conecta('MYSQL') ;

//recebendo o COD
$cod=$_GET["cod"];

$sqlQuery = "select id, nometitular, nrocarne, prontuario, plano from carne_titular where nrocarne='".$cod."'"; 

$slProduto=mysql_query($sqlQuery) or die('ERRO NA EXECU��O DA QUERY DE CONSULTA!');
	
$rest=mysql_fetch_array($slProduto);
 	 
echo"$rest[id],$rest[nometitular],$rest[nrocarne],$rest[prontuario],$rest[plano],00";


?>
