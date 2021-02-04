<?php

	$processado = false;

	if($processado==false ){
		// Impressão do Analitico do Cadastro de Titular
		if(!empty($_POST['tiporelatorio']) && $_POST['tiporelatorio'] == 1) {
			$processado = true;
			// Redireciono com o metodo POST
			header("Location: relcadastros3.php", TRUE, 307);
		}
	}
	

	if($processado==false ){
		// Impressão do Sintetico do cadastro de Titular	
		if(!empty($_POST['tiporelatorio']) && $_POST['tiporelatorio'] == 2) {
			$processado = true;
			// Redireciono com o metodo POST
			header("Location: relcadastros4.php", TRUE, 307);
		}
	}
	
/*
	if($processado==false ){
		// Impressão do Gráfico Pizza
		if($_POST['separacao'] <> -1 && $_POST['tiporelatorio'] == 3) {
			$processado = true;
			// Redireciono com o metodo POST
			header("Location: relpagamentosgraph.php", TRUE, 307);
		}
	}
	

	if($processado==false ){
		// Impressão do Gráfico Barra
		if($_POST['separacao'] <> -1 && $_POST['tiporelatorio'] == 4) {
			$processado = true;
			// Redireciono com o metodo POST
			header("Location: relpagamentosgraph2.php", TRUE, 307);
		}
	}
*/

?>
