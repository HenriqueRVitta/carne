<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Informática LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 04/11/2019 10:38 GLPI 16792

		* Módulo Carnê *

		Opção de geração de NFSe conforme recebimentos bancario

*/
	session_start();

	
	if (extension_loaded(yaml)) {
		echo "Processando NFSe...";
	} else {
		echo "Pacote Yaml da NFSE nao instalado em PHP/ext...";
		exit;
	}
  		
  
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/classes/paging.class.php");

	
	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];


	$arr = $_POST['selecionado'];
	foreach ($arr as &$value) {
    
			$IdCliente = $value;

	}
	
print "</body>";
print "</html>";

?>
