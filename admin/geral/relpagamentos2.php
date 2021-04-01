<?php


	$processado = false;

	if($processado==false ){
		// Impress�o do Analitico Inadimplentes
		if($_POST['tiporelatorio'] == 1 && $_POST['situacao'] == 2) {

			$processado = true;			
			$dataIni = str_replace("/", "-", $_POST["datainicio"]);
			$dataFim = str_replace("/", "-", $_POST["datafim"]);
			
			header("Location: relinadimplentes_ana2.php", TRUE, 307);
			
			/*
	    	if(date('m', strtotime($dataIni)) == date('m', strtotime($dataFim))) {
				// Redireciono com o metodo POST
				header("Location: relinadimplentes_ana.php", TRUE, 307);
	    	} Else {
				// Redireciono com o metodo POST
				header("Location: relinadimplentes_ana2.php", TRUE, 307);
	    	}
	    	*/
			
    	
		}
	}

	if($processado==false ){
		// Impressao do Analitico
		if(!empty($_POST['tiporelatorio']) && $_POST['tiporelatorio'] == 1) {
			$processado = true;
			
		$dataIni = str_replace("/", "-", $_POST["datainicio"]);
		$dataFim = str_replace("/", "-", $_POST["datafim"]);
    	if(date('m', strtotime($dataIni)) == date('m', strtotime($dataFim))) {

			// Redireciono com o metodo POST
			header("Location: relpagamentos3.php", TRUE, 307);
			
			
    	} else {
    		
			// Redireciono com o metodo POST
			header("Location: relpagamentos5.php", TRUE, 307);
    		
    	}
    			
		}
	}
	

	if($processado==false ){
		// Impress�o do Sintetico	
		if(!empty($_POST['tiporelatorio']) && $_POST['tiporelatorio'] == 2) {
			$processado = true;
			// Redireciono com o metodo POST
			header("Location: relpagamentos4.php", TRUE, 307);
		}
	}
	

	if($processado==false ){
		// Impress�o do Gr�fico Pizza
		if($_POST['tiporelatorio'] == 3 && $_POST['grafico'] == 2) {
			$processado = true;
			// Redireciono com o metodo POST
			header("Location: relpagamentosgraph.php", TRUE, 307);
		}
	}
	

	if($processado==false ){
		// Impress�o do Gr�fico Barra
		if($_POST['tiporelatorio'] == 3 && $_POST['grafico'] == 3) {
			$processado = true;
			// Redireciono com o metodo POST
			header("Location: relpagamentosgraph2.php", TRUE, 307);
		}
	}
	
?>
