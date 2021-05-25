<?php

session_start();

if($_POST['layout'] == "CNAB400") {
	// Redireciono com o metodo POST
	header("Location: retornosicoob400.php", TRUE, 307);
}

if($_POST['layout'] == "CNAB240") {
	// Redireciono com o metodo POST
	echo '<p>Rotina do arquivo Retorno CNAB240 ainda em desenolvimento...</p>';
	//header("Location: retornosicoob240.php", TRUE, 307);
}
exit;
   
?>
