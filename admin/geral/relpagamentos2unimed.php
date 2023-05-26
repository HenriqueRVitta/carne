<?php


	$processado = false;

	if($processado==false ){

		// Impressao do Analitico
		if($_POST['tiporelatorio'] == 1) {
		
        $processado = true;
		// Inadimplentes

		if(isset($_POST['situacao']) && $_POST['situacao'] == 1){

			// Pagos
			// Redireciono com o metodo POST
			header("Location: relpagamentos3unimed.php", TRUE, 307);
		
		} else {

			// Inadimplentes
			// Redireciono com o metodo POST
			header("Location: relinadimplentes_unimed.php", TRUE, 307);
			
		}

    			
		}
	}
	

	if($processado==false ){
		// Impress�o do Sintetico	
		if($_POST['tiporelatorio'] == 2) {
			$processado = true;
			// Redireciono com o metodo POST
			header("Location: relpagamentos4unimed.php", TRUE, 307);
		}
	}
	

?>
