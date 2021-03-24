<?php

@session_start();


// Este primeiro header, corrigi o problema de acentua��o dos caracteres.
header('Content-Type: text/html; charset=iso-8859-1');
// Os dois headers seguintes, evitam que a p�gina seja armazenada em cache no navegador.
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past


//Conectando com o Banco
include ("/carne/includes/config.inc.php");
include ("/carne/includes/classes/conecta.class.php");
$conec = new conexao;
$conec->conecta('MYSQL') ;

//recebendo o id do carne_tipodependente
$cod=$_GET["cod"];

$sqlQuery = "select idademaxima from carne_tipodependente where id ='".$cod."'";

$slProduto=mysqli_query($conec->con,$sqlQuery) or die('ERRO na query'.$sqlQuery);
$rest=mysqli_fetch_array($slProduto);

echo "$rest[idademaxima],00";

?>
