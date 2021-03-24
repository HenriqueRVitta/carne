<?php 

	if (is_file ("../../includes/classes/conecta.class.php"))
		require_once ("../../includes/classes/conecta.class.php"); else
	if (is_file ("../conecta.class.php"))
		require_once ("../conecta.class.php"); else
	if (is_file ("./includes/classes/conecta.class.php"))
		require_once ("./includes/classes/conecta.class.php");


	$conec = new conexao;
	$conec->conecta('MYSQL');

	$qryConf = "SELECT * FROM config";
	$execConf = mysqli_query($conec->con,$qryConf);
	$rowConf = mysqli_fetch_array($execConf);

	define ( "LANG", $rowConf['conf_language']);

	$conec->desconecta('MYSQL');
?>