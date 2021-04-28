<?php 
/*      Copyright 2014 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 07/02/2019 14:29 GLPI 12987

*/


	session_start();
	
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	include ("../../includes/classes/paging.class.php");

	$conec = new conexao;
    $conec->conecta('MYSQL');
    
$banco = $_POST["banco"];

$query = "SELECT id, nome, bancoemissor, nroagencia, digitoagencia, nroconta, digitoconta, nrocontrato, infocliente1, infocliente2, infocliente3, instrucaocaixa1, instrucaocaixa2, instrucaocaixa3, dirarquivoretorno, dirarquivoremessa, carteiracobranca, idretornobanco, localpagto, codcedente FROM carne_bancos where nome = '".$banco."'";
$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);

$row = mysqli_fetch_array($resultado);
$linha=mysqli_num_rows($resultado);

if($linha > 0) {

		$agencia = $row['nroagencia'];
		$digitoagencia = $row['digitoagencia'];

        echo "<script language='javascript' type='text/javascript'>";
        echo "document.getElementById('idnroagencia').value = '".$row['nroagencia']."'";
        echo "</script>";
        
		echo "<script language='javascript' type='text/javascript'>";
        echo "document.getElementById('iddigitoagencia').value = '".$row['digitoagencia']."'";
        echo "</script>";
        
        echo "<script language='javascript' type='text/javascript'>";
        echo "document.getElementById('idnroconta').value = '".$row['nroconta']."'";
        echo "</script>";
        
		echo "<script language='javascript' type='text/javascript'>";
        echo "document.getElementById('iddigitoconta').value = '".$row['digitoconta']."'";
        echo "</script>";
        
		echo "<script language='javascript' type='text/javascript'>";
        echo "document.getElementById('idnrocontrato').value = '".$row['nrocontrato']."'";
        echo "</script>";

		echo "<script language='javascript' type='text/javascript'>";
        echo "document.getElementById('idcodcedente').value = '".$row['codcedente']."'";
        echo "</script>";

        echo "<script language='javascript' type='text/javascript'>";
        echo "document.getElementById('iddirarquivoremessa').value = '".$row['dirarquivoremessa']."'";
        echo "</script>";

        if($row['carteiracobranca'] == 'Com Registro') { $carteira = '1'; } else { $carteira = '2';}
         
        echo "<script language='javascript' type='text/javascript'>";
        echo "document.getElementById('idcarteiracobranca').value = '".$carteira."'";
        echo "</script>";
       
        
		echo "<script language='javascript' type='text/javascript'>";
        echo "document.getElementById('idinfocliente1').value = '".$row['infocliente1']."'";
        echo "</script>";
        
		echo "<script language='javascript' type='text/javascript'>";
        echo "document.getElementById('idinfocliente2').value = '".$row['infocliente2']."'";
        echo "</script>";

        echo "<script language='javascript' type='text/javascript'>";
        echo "document.getElementById('idinfocliente3').value = '".$row['infocliente3']."'";
        echo "</script>";

        echo "<script language='javascript' type='text/javascript'>";
        echo "document.getElementById('idinstrucaocaixa1').value = '".$row['instrucaocaixa1']."'";
        echo "</script>";
        
        echo "<script language='javascript' type='text/javascript'>";
        echo "document.getElementById('idinstrucaocaixa2').value = '".$row['instrucaocaixa2']."'";
        echo "</script>";

        echo "<script language='javascript' type='text/javascript'>";
        echo "document.getElementById('idinstrucaocaixa3').value = '".$row['instrucaocaixa3']."'";
        echo "</script>";

        echo "<script language='javascript' type='text/javascript'>";
        echo "document.getElementById('idretornobanco').value = '".$row['idretornobanco']."'";
        echo "</script>";
        
        echo "<script language='javascript' type='text/javascript'>";
        echo "document.getElementById('localpagto').value = '".$row['localpagto']."'";
        
}