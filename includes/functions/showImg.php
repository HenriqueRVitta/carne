<?php 
session_start();
  	include ("../../includes/config.inc.php");
  	include ("../../includes/classes/conecta.class.php");
		
	$conec = new conexao;
	$conec->conecta('MYSQL');

	// Secure the user data by escaping characters 
	// and shortening the input string
	function clean($input, $maxlength) {
		$input = substr($input, 0, $maxlength);
		$input = EscapeShellCmd($input);
		return ($input);
	}

	$file = "";
	$file = clean($_GET['file'], 4);

	if (empty($file))
	exit;

	//$query = "SELECT * FROM imagens WHERE img_oco = ".$_GET['file']." and img_cod=".$_GET['cod']."";
	$query = "SELECT * FROM imagens WHERE  img_cod=".$_GET['cod']."";
	
	$result = mysqli_query($conec->con,$query) or die("ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DA IMAGEM");
	
	$data = @ mysqli_fetch_array($result);

	if (!empty($data["img_bin"])) {
		// Sa�da MIME header
		header("Content-Type: {$data["img_tipo"]}");
		// Sa�da da imagen
		echo $data["img_bin"];
	}
?>