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
$cod = $_GET["cod"]; 

$contrato = 0;
$nometitu = 'Registro nao encontrado';

$sqlQuery = "select c.nrocontrato, t.nometitular from carne_contratos c Join carne_titular t on t.id = c.idtitular where c.nrocontrato ='".$cod."'";

$strquery=mysql_query($sqlQuery) or die('ERRO na query'.$sqlQuery);
$retorno=mysql_fetch_array($strquery);

if($retorno['nrocontrato'] > 0) {
$contrato = $retorno['nrocontrato'];
$nometitu = $retorno['nometitular'];
}
 // se existem dados 
echo "$contrato,$nometitu,00";

?>
