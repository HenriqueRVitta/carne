<?php

	session_start();


	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	include ("../../includes/classes/paging.class.php");

	$conec = new conexao;
	$conec->conecta('MYSQL');

	$idTitular = 0;

if(isset($_GET['cod'])) {
	$arr = array(0 => $_GET['cod']);
}

if(isset($_POST['selecionado'])) {
	$arr = $_POST['selecionado'];
}

?>

<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cart&atilde;o Irm&atilde;o Contribuinte</title>
<style type="text/css">

body {
		background-color: #FFFFFF;
		margin-left: 2px;
	}
</style>
</head>

<body>
<p>&nbsp;</p>

<!--
style="width:831px; height:264px;"
 -->
<?php 
$page = 0;
foreach ($arr as &$value) {
    $page++;
    
	$idTitular=trim($value);
	
	// Gravando data da emissao do Cartao
	$queryloteRps = "Update carne_titular set cartaoemitido = '".date('Y-m-d h:i:s')."', cartaoemitidopor = '".$_SESSION['s_usuario']."' where id = ".$idTitular."";
	$resultadoLoteRps = mysqli_query($conec->con,$queryloteRps) or die('ERRO NA QUERY !'.$queryloteRps);
	
	$query = "SELECT a.*, b.descricao, b.formapagto, c.valor, d.datacontrato, d.diavencto, d.nrocontrato, d.plano ".
			 "FROM carne_titular a Join carne_contratos d ".
			  						"on d.idtitular = a.id ".
								  "Join carne_tipoplano b on b.id = d.plano ".
								  "Join carne_competenciaplano c on c.idplano = b.id ".
			"where a.id =".$idTitular."";
			
			
	$resultado = mysqli_query($conec->con,$query) or die("ERRO na Query ".$query);
	$row = mysqli_fetch_array($resultado);
	$dtcontrato = str_replace('/','',substr(converte_datacomhora($row['datacontrato']),0,10));
	$dtnascimento = str_replace('/','',substr(converte_datacomhora($row['datanasc']),0,10));
?>	


<table width="98%"  border="1" cellspacing="0" cellpadding="0" BGCOLOR="#FFFFFF">
  <tr>
  
	  <td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" BGCOLOR="#FFFFFF">
		<tr>
		<td align="center"><img src="imagens/logo.png" width="300" height="150" alt="agenda"/></td>
		</tr>
	
		<tr>
		<td><p><br><br></p></td>
		</tr>
	
		<tr>
		<td align="center"><h3>CART&Atilde;O IRM&Atilde;O CONTRIBUINTE</h3></td>
		</tr>
		</table>
	  </td>
  
	  <td>
	  
		<table width="100%" border="0" cellspacing="2" cellpadding="2" BGCOLOR="#FFFFFF">
		<tr>
		<td align="center"><h3>CART&Atilde;O IRM&Atilde;O CONTRIBUINTE</h3></td>
		</tr>
		<tr>
		<td align="center"><p><b><?php echo $row['nometitular'];?></p></b></td>
		</tr>
		<tr>
		<!-- <td align="center"><p>Data Nasc.: <?php echo mask($dtnascimento,'##/##/####');?> Sexo: <?php echo $row['sexo'];?></p></td>  -->
		<td align="center"><p>Data Nasc.: <?php echo mask($dtnascimento,'##/##/####');?>   /  <b>NRO CARN&Ecirc;:  <?php echo $row['id'];?></b></p></td>
		</tr>

<?php 

	// Seleciono aqui os dependentes do titular
    $queryDep = "select a.nome,b.descricao from carne_dependente a left join carne_tipodependente b on b.id = a.parentesco where a.idtitular = ".$idTitular."";
    $resuldep = mysqli_query($conec->con,$queryDep) or die('ERRO NA QUERY !'.$query);
	$x=0;
	while($rowDep = mysqli_fetch_array($resuldep)) {

	?>
	
	  <tr>
	  <td><p><?php echo $rowDep['nome']." - ".$rowDep['descricao'];?></p></td>
	  </tr>
	  
	  <?php 

	  $x++;
	  
		  if($x > 3){
		  	break;
		  }
	  
	}

?>

		<tr>
		<td><p> </p></td>
		</tr>
	
		<tr>
		<td><p> </p></td>
		</tr>
	
		<tr>
		<td><p> </p></td>
		</tr>
	
		<tr>
		<!-- <td align="center"><h2>NRO CARN&Ecirc;:  <?php echo $row['id'];?></h2></td>-->
		</tr>
	
		</table>
		</td>  
  </tr>
</table>
<p><br></p>
<?php 

	if($page==4){
		echo "<div style='page-break-after: always'></div>";
		echo "<p>&nbsp;</p>";
		$page=0;
	}
}
?>
	<br><p align="center"><img src='../../includes/imgs/impressora.jpg' onclick='javascript:window.print();' title='Imprimir Cartão'></p>

</body>
</html>