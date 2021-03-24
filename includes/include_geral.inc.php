<?php 
##INCLUDES GERAIS

if(!isset($_SESSION['s_usuario'])) {
	header('Location: ../../index.php');
}

	include ("../../includes/classes/headers.class.php");
	include ("../../includes/classes/conecta.class.php");
	include ("../../includes/classes/auth.class.php");
	include ("../../includes/classes/dateOpers.class.php");
 	include ("../../includes/config.inc.php");
	include ("../../includes/versao.php");

 	include ("../../includes/languages/".LANGUAGE.""); //TEMPORARIAMENTE
 	include ("../../includes/menu/menu.php");
	include ("../../includes/functions/funcoes.inc");
	include ("../../includes/javascript/funcoes.js");

	 	
 	//include ("../../includes/queries/queries.php");

	print "<style>"; //type='text/css'
	?>
	<!--
		@import url('../../includes/css/estilos.css.php');
	//-->
	<?php 
	print "</style>";


	print "<link rel='shortcut icon' href='../../includes/icons/favicon.ico'>";

	$conec = new conexao;
	$conec->conecta('MYSQL');


	if (isset($_SESSION['s_uid'])) {
		$qry = "SELECT * FROM temas t, uthemes u  WHERE u.uth_uid = ".$_SESSION['s_uid']." and t.tm_id = u.uth_thid";
		$exec = mysqli_query($conec->con,$qry) or die('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÃ‡Ã•ES DO TEMA!<BR>'.$qry);
		$row = mysqli_fetch_array($exec);
		$regs = mysqli_num_rows($exec);
		if ($regs==0){ //SE NÃƒO ENCONTROU TEMA ESPECÃ�FICO PARA O USUÃ�RIO
			$qry = "SELECT * FROM styles";
			$exec = mysqli_query($conec->con,$qry);
			$row = mysqli_fetch_array($exec);
		}
	} else {
		$qry = "SELECT * FROM styles";
		$exec = mysqli_query($conec->con,$qry);
		$row = mysqli_fetch_array($exec);
	}


	define ( "BODY_COLOR", $row['tm_color_body']);
	define ( "TD_COLOR", $row['tm_color_td']);

?>