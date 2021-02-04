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
  
$sqlQuery = "select nromaxdepend from config";
$executa=mysql_query($sqlQuery) or die('ERRO na query'.$sqlQuery);
$config=mysql_fetch_array($executa);

$sqlQuery = "SELECT count(*) as qtde FROM carne_dependente where idtitular ='".$cod."'";
$executa=mysql_query($sqlQuery) or die('ERRO na query'.$sqlQuery);
$depend=mysql_fetch_array($executa);

$retorno = 'true';

if($depend['qtde'] >= $config['nromaxdepend']){
	$retorno = 'false';
}

 // se existem dados 
echo "$retorno,$config[nromaxdepend],00";

?>
