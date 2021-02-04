<?php

	session_start();


	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	include ("../../includes/classes/paging.class.php");


$query = "SELECT a.*, b.descricao, b.formapagto, c.valor, d.datacontrato, d.diavencto, d.nrocontrato, d.plano ".
		 "FROM carne_titular a Join carne_contratos d ".
		  						"on d.idtitular = a.id ".
							  "Join carne_tipoplano b on b.id = d.plano ".
							  "Join carne_competenciaplano c on c.idplano = b.id ".
		"where a.id =".$_GET['cod']."";
		
		
$resultado = mysql_query($query) or die("ERRO na Query ".$query);
$row = mysql_fetch_array($resultado);
$dtcontrato = str_replace('/','',substr(converte_datacomhora($row['datacontrato']),0,10));
$dtnascimento = str_replace('/','',substr(converte_datacomhora($row['datanasc']),0,10));

if(substr($row['formapagto'],0,3)=='CAR') {
$formapagto = "CARNÊ";
} else {
$formapagto = "BOLETO BANCÁRIO";	
}

$fonecontato = '';

if(!empty($row['celular'])) { 
	$fonecontato = mask($row['celular'],'(##)####-#####'); 
} else {
					if(!empty($row['telefoneres'])) {
						$fonecontato = mask($row['telefoneres'],'(##)####-#####');										
					} else {

						if(!empty($row['telefonecom'])) {
						$fonecontato = mask($row['telefonecom'],'(##)####-#####');										
						
					}					
}
}

if(empty($fonecontato)) {
	$fonecontato = "N&atilde;o informado";
}


/*
$send = serialize($row); //trasnforma o array em string
$send = urlencode($send); //codifica a string para ser utilizada no link
*/

$LocalCidade = "";
$CepCidade = "30000-000";
$endereco = "";

$array_escola[1] = "N&atilde;o informado";
$array_escola[2] = "Analfabeto";
$array_escola[3] = "1&ordm; Grau Incompleto";  
$array_escola[4] = "1&ordm; Grau Completo";  
$array_escola[5] = "2&ordm; Grau Incompleto";  
$array_escola[6] = "2&ordm; Grau Completo";  
$array_escola[7] = "Superior Incompleto";  
$array_escola[8] = "Superior Completo";  
		

$array_estcivil[1] = "N&atilde;o informado";  
$array_estcivil[2] = "Solteiro";  
$array_estcivil[3] = "Casado";  
$array_estcivil[4] = "Divorciado";  
$array_estcivil[5] = "Separado";  
$array_estcivil[6] = "Vi&uacute;vo";  
$array_estcivil[7] = "Amasiado";  
$array_estcivil[8] = "Indefinido";  

?>

<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contrato de Adesão</title>
<style type="text/css">
.botaopdf {
background-image:url("../../includes/icons/pdf.png");
width:30px;
height:30px;
}
.titulo {
	font-family: Times New Roman, Times, serif;
	font-size:16;
}
.texto {
	font-family: Times New Roman, Times, serif;
	font-size:14;
}
.texto2 {
	font-family: Times New Roman, Times, serif;
	font-size:10;
}

body {
		background-color: #FFFFFF;
	}
</style>
</head>

<body>
<p>&nbsp;</p>


<table width="800" border="0" cellspacing="0" cellpadding="0" BGCOLOR="#FFFFFF">
  <tr>
    <th scope="col"><img src="../../logo.png" width="335" height="100" alt="agenda" align="left"/></th>
	<th width="379" scope="col" align="left"><h2>CARTÃO DESCONTO Nr. <?php echo $row['nrocontrato']?></h2></th>
  </tr>
</table>
<table width="800" border="0" cellspacing="0" cellpadding="0">

  <tr>

<table width="100%" border="0">
  <tr>
    <th height="40" colspan="3" scope="col" align="left"><p align="left" class="texto">Titular:<?php echo $row['nometitular']?></p></th>
  </tr>
  <tr>
    <th scope="col" align="left"><p align="left" class="texto">Rua / Av.: <?php echo $row['endereco']?></p></th>
    <th scope="col" align="left"><p align="left" class="texto">Nro: <?php echo $row['numero']?></p></th>
    <th scope="col" align="left"><p align="left" class="texto">CEP: <?php echo mask($row['cep'],'#####-###')?></p></th>
  </tr>
  <tr>
    <th scope="col" align="left"><p align="left" class="texto">Bairro: <?php echo $row['bairro']?></p></th>
    <th scope="col" align="left"><p align="left" class="texto">Cidade: <?php echo $row['cidade']?></p></th>
    <th scope="col" align="left"><p align="left" class="texto">UF: <?php echo $row['uf']?></p></th>
  </tr>
  <tr>
    <th scope="col" align="left"><p align="left" class="texto">Telefone: <?php echo $fonecontato?></p></th>
    <th scope="col" align="left"><p align="left" class="texto">Data Nascimento: <?php echo mask($dtnascimento,'##/##/####')?></p></th>
    <th scope="col" align="left"><p align="left" class="texto">Nro Filhos: <?php echo $row['qtdefilhos']?></p></th>
  </tr>
  <tr>
    <th scope="col" align="left"><p align="left" class="texto">Escolaridade: <?php echo $array_escola[$row['escolaridade']]?></p></th>
    <th scope="col" align="left"><p align="left" class="texto">Local Trabalho: <?php echo $row['localtrabalho']?></p></th>
    <th scope="col" align="left"><p align="left" class="texto">Profissão: <?php echo $row['profissao']?></p></th>
  </tr>
  <tr>
    <th scope="col" align="left"><p align="left" class="texto">Identidade: <?php echo $row['identidade']?></p></th>
    <th scope="col" align="left"><p align="left" class="texto">CPF: <?php echo $row['cpf']?></p></th>
    <th scope="col" align="left"><p align="left" class="texto">Estado Civil: <?php echo $array_estcivil[$row['estadocivil']]?></p></th>
  </tr>
  <tr>
    <th scope="col" align="left"><p align="left" class="texto">Filiação: </p></th>
    <th scope="col" align="left"><p align="left" class="texto">Pai: <?php echo $row['nomepai']?></p></th>
    <th scope="col" align="left"><p align="left" class="texto">Mãe: <?php echo $row['nomemae']?></p></th>
  </tr>

</table>

<p align="center" class="texto2">Em caso de cancelamento de consulta o beneficiário deverá avisar no prazo mínimo de 6(seis) horas de antecedência. O paciente deverá comparecer 15(quinze) minutos de antecedência do seu horário.</p>
<p align="center" class="texto2">Condição de Pagamento: Cobrança Bancária pagamento em 3 mensalidades</p>
<table width="100%" border="1">
  <tr>
    <th scope="col">Nome do Dependente</th>
    <th scope="col">Grau de Parentesco</th>
    <th scope="col">Data de Nascimento</th>
  </tr>
<?php 
		$query = "SELECT d.id,d.nome,d.datanasc,p.descricao as parentesco from carne_dependente d Left Join carne_tipodependente p on p.id = d.parentesco where d.idtitular=".$_GET['cod']." order by d.nome desc";
		$resultado = mysql_query($query) or die('ERRO NA QUERY !'.$query);
			while ($rowb = mysql_fetch_array($resultado))
			{

				  $dtnasc = str_replace('/','',substr(converte_datacomhora($rowb['datanasc']),0,10));
				  print "<tr>";
				  print "<th scope='col'><p align='center' class='texto'>".$rowb['nome']."</p></th>";
				  print "<th scope='col'><p align='center' class='texto'>".$rowb['parentesco']."</p></th>";
				  print "<th scope='col'><p align='center' class='texto'>".mask($dtnasc,'##/##/####')."</p></th>";
				  print "</tr>";
			}
		
?>
</table>
  
<p align="justify" class="texto"></p>
<p align="justify" class="texto">Titular:<?php echo $row['nometitular']?>  - Ass:_____________________________________</p>
<p align="justify" class="texto">Vendedor:____________________________________________________________________________________ Data: <?php echo mask($dtcontrato,'##/##/####')?>.</p>

</td>
  </tr>
</table>

<p align="center" class="texto">------------------------------------------------------------------------------------------------------------------------------------------------------------</p>

<table width="100%" border="1">
  <tr>
    <td>
<table width="100%" border="0">
  <tr>
    <th width="330" rowspan="4" scope="col"><img src="../../logo.png" width="330" height="130" alt="teste" /></th>
    <th width="498" scope="col"><h2>CARTÃO DESCONTO Nr. <?php echo $row['nrocontrato']?></h2></th>
  </tr>
  <tr>
    <th scope="col" class="texto2">Em caso de cancelamento de consulta o beneficiário deverá avisar no prazo mínimo de 6(seis) horas de antecedência. O paciente deverá comparecer 15(quinze) minutos de antecedência do seu horário.</th>
  </tr>
  <tr>
    <th scope="col" class="texto2">HORÁRIO DE ATENDIMENTO: 7:00 ÀS 17:00 DE SEGUNDA A SEXTA-FEIRA</th>
  </tr>
  <tr>
    <th scope="col" class="texto2">Condição de Pagamento: Cobrança Bancária pagamento em 3 mensalidades</th>
  </tr>
</table>

<p align="justify" class="texto"></p>
<p align="justify" class="texto">Titular:<?php echo $row['nometitular']?>  - Ass:_____________________________________</p>
<p align="justify" class="texto">Vendedor:____________________________________________________________________________________ Data: <?php echo mask($dtcontrato,'##/##/####')?>.</p>

<br/>
<table>
 
<tr>
<td>
<img src='../../includes/imgs/impressora.jpg' onclick='javascript:window.print();' title='Imprimir Contrato'>
</td>
<!--
<td>
<input type='submit' value='' class='botaopdf' onClick="redirect('contrato_pdf.php?send=<?php echo $send;?>&item=<?php echo $Item;?>')" title='Gerar Arquivo PDF' />
</td>
-->
</tr>
</table>

</body>
</html>