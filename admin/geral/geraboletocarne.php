<?php

	session_start();

	// ModeloCarne 1 = Modelo de Baependi
	
	if($_SESSION['modelocarne'] == 1) {
		
		// Impressão do Capa
		if(!empty($_POST['tipoimpressao']) && $_POST['tipoimpressao'] == 1) {
			// Redireciono com o metodo POST
			header("Location: geracapacarne.php", TRUE, 307);
		}
	
		// Impressão do carnê	
		if(!empty($_POST['tipoimpressao']) && $_POST['tipoimpressao'] == 2) {
	
			if($_POST['percvalor'] == 1) {
				// Impressão com Percentual
				// Redireciono com o metodo POST
				header("Location: geracarnecodbarraperc.php", TRUE, 307);
			} else {
				// Impressão com Valor
				// Redireciono com o metodo POST
				header("Location: geracarnecodbarra.php", TRUE, 307);
			}
		}
	}
	
	
	// ModeloCarne 2 = Modelo de Passa Tempo
	
	if($_SESSION['modelocarne'] == 2) {
		
		// Impressão do Capa
		if(!empty($_POST['tipoimpressao']) && $_POST['tipoimpressao'] == 1) {
			// Redireciono com o metodo POST
			header("Location: geracapacarne_2.php", TRUE, 307);
		}
	
		// Impressão do carnê	
		if(!empty($_POST['tipoimpressao']) && $_POST['tipoimpressao'] == 2) {
	
			if($_POST['percvalor'] == 1) {
				// Impressão com Percentual
				// Redireciono com o metodo POST
				header("Location: geracarnecodbarraperc_2.php", TRUE, 307);
			} else {
				// Impressão com Valor
				// Redireciono com o metodo POST
				header("Location: geracarnecodbarra_2.php", TRUE, 307);
			}
		}
	}
		
	// ModeloCarne 2 = Modelo de Promedico - Ipatinga
	
	if($_SESSION['modelocarne'] == 3) {
		
		// Impressão do Capa
		if(!empty($_POST['tipoimpressao']) && $_POST['tipoimpressao'] == 1) {
			// Redireciono com o metodo POST
			header("Location: geracapacarne_3.php", TRUE, 307);
		}
	
		// Impressão do carnê	
		if(!empty($_POST['tipoimpressao']) && $_POST['tipoimpressao'] == 2) {
	
				// Impressão com Valor
				// Redireciono com o metodo POST
				header("Location: geracarnecodbarra_3.php", TRUE, 307);
		}
	}

	// ModeloCarne 4 = Modelo de Itaguara
	
	if($_SESSION['modelocarne'] == 4) {
		
		// Impressão do Capa
		if(!empty($_POST['tipoimpressao']) && $_POST['tipoimpressao'] == 1) {
			// Redireciono com o metodo POST
			header("Location: geracapacarne_4.php", TRUE, 307);
		}
	
		// Impressão do carnê	
		if(!empty($_POST['tipoimpressao']) && $_POST['tipoimpressao'] == 2) {
	
				// Impressão com Valor
				// Redireciono com o metodo POST
				header("Location: geracarnecodbarra_4.php", TRUE, 307);
		}
	}

	// ModeloCarne 5 = Modelo de Ipatinga Saude e Harmonia
	
	if($_SESSION['modelocarne'] == 5) {
		
		// Impressão do Capa
		if(!empty($_POST['tipoimpressao']) && $_POST['tipoimpressao'] == 1) {
			// Redireciono com o metodo POST
			header("Location: geracapacarne_5.php", TRUE, 307);
		}
	
		// Impressão do carnê	
		if(!empty($_POST['tipoimpressao']) && $_POST['tipoimpressao'] == 2) {
	
				// Impressão com Valor
				// Redireciono com o metodo POST
				header("Location: geracarnecodbarra_5.php", TRUE, 307);
		}
	}
	
?>
