<?php

	session_start();


	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	include ("../../includes/classes/paging.class.php");

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
body {
		background-color: #FFFFFF;
	}
</style>
</head>

<body>

<?php 
$query = "SELECT a.nometitular, b.descricao, b.formapagto, c.valor, d.datacontrato, d.diavencto, d.nrocontrato, d.plano ".
		 "FROM carne_titular a Join carne_contratos d ".
		  						"on d.idtitular = a.id ".
							  "Join carne_tipoplano b on b.id = d.plano ".
							  "Join carne_competenciaplano c on c.idplano = b.id ".
		"where a.id =".$_GET['cod']."";
		
		
$resultado = mysql_query($query) or die("ERRO na Query ".$query);
$row = mysql_fetch_array($resultado);

$dtcontrato = str_replace('/','',substr(converte_datacomhora($row['datacontrato']),0,10));
				
if(substr($row['formapagto'],0,3)=='CAR') {
$formapagto = "CARNÊ";
} else {
$formapagto = "BOLETO BANCÁRIO";	
}


$LocalCidade = "";
$CepCidade = "30000-000";
$endereco = "";

?>
<p>&nbsp;</p>
<table width="800" border="0" cellspacing="0" cellpadding="0" BGCOLOR="#FFFFFF">
  <tr>
    <th scope="col"><img src="../../logo.png" width="335" height="100" alt="agenda" align="left"/></th>
	<th width="379" scope="col" align="left"><h2>CONTRATO DE ADESÃO</h2></th>
  </tr>
</table>
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td scope="col"><p>&nbsp;</p>
      <p align="justify" class="texto">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pelo presente instrumento de prestação de serviços com valor diferenciado, a CONTRATADA, empresa Melo e Filhos Clínica Médica LTDA, com sede à rua Pentateuco, 185 - Canaã, Ipatinga/MG - Cep 35.164-200, Telefone (31) 3826-0311, inscrita no CNPJ sob nro 14.762.735/0001-67, 
      isenta de Inscrição Estadual, e do outro lado o ADERENTE, <?php echo $row['nometitular']?> , tendo certo e ajustado o presente contrato para prestação de serviço, mediante as cláusulas e condições seguintes:</p>
<p align="justify"></p>
<p align="justify" class="texto"><strong><u>Cláusula Primeira - DO OBJETO</u>:</strong><br /><br />
  1.1 - O presente contrato tem  como objeto adesão ao "Cartão Desconto". Descontos nos serviços prestados pela CONTRATADA, nas suas instalações e dependências, por seu quadro técnico de profissionais, ao ADERENTE e seus dependentes.<br /><br />
<p align="justify"></p>
<p align="justify" class="texto"><strong><u>Cláusula Segunda - DAS OBRIGAÇÔES DA CONTRATADA</u></strong> <br /><br />
  2.1 - São assegurados ao ADERENTE, convênios com empresa de prestação de serviços nas áreas de saúde (consulta médicas, exames clínicos, tratamentos odontológicos) a custo reduzido, intermediados pelo CARTÃO DESCONTO.</u></strong> <br /><br />
<p align="justify"></p>
<p align="justify" class="texto"><strong><u>Cláusula Terceira - DAS OBRIGAÇÔES DO ADERENTE</u></strong><br /><br />
  3.1 - O ADERENTE se comprometerá à estar em dias com suas contribuições, a portar documento de identificação tanto seu quanto de seus dependentes e à estar nas dependências da CONTRATADA em 15 minutos antes do horário da consulta. Em caso de cancelamento
  	de qualquer procedimento o ADERENTE devera avisar com, no mínimo, 6(seis) horas de antecedência.<br><br>
  3.2 - O ADERENTE remunerará a contratada pelos serviços prestados (consultas, procedimentos e exames) conforme tabelas vigentes.<br><br>
  3.3 - Os serviços prestados e a mensalidade serão revistos anualmente, em meados de março/abril, podendo ser ajustados conforme IGPM integral da FGV do ano anterior.<br><br>
  3.4 - O ADERENTE pagará o valor conforme opções abaixo, até atualização próxima.<br><br></strong></p>

<table width="100%" border="1">
  <tr>
    <th height="40" colspan="3" scope="col" align="left"><p align="justify" class="texto">Data de Vencimento do dia <?php echo $row['diavencto']?> de cada mês.</p></th>
  </tr>
  <tr>
    <th scope="col">OPÇÃO DE PAGAMENTO</th>
    <th scope="col">VALOR DA PARCELA</th>
    <th scope="col">FORMA DE PAGAMENTO</th>
  </tr>
  <tr>
    <th scope="col"><?php echo $row['descricao']?></th>
    <th scope="col"><?php echo $row['valor']?></th>
    <th scope="col"><?php echo $formapagto?></th>
  </tr>
</table>
  
<p align="justify" class="texto"></p>
<p align="justify" class="texto"><strong><u>Cláusula Quarta - DO PRAZO DE VIGÊNCIA</u></strong><br /><br />
  4.1 - O Presente contrato tem validade pelo prazo de 12 (doze) meses, sendo renovado automaticamente, caso não haja manifestação em contrário por umas das partes.<br><br>
  4.2 - O ADERENTE poderá rescindir o presente contrato no prazo de 7(sete) dias contados da data da sua assinatura, conforme o art. 49 da Lei 8078/90.<br><br>
  4.3 - As partes poderão rescindir o contrato unilateralmente sem qualquer ônus desde que tenha transcorrido 12 meses do contrato e comunicação prévia de 30(trinta) dias, por escrito. Caso contrário, será devida multa equivalente a 50% das parcelas restante para o término da vigência contratual.<br><br>
  4.4 - O ADERENTE se declara ciente e de acordo que os serviços oferecidos pelo CARTÃO DESCONTO (não caracteriza como Plano de Saúde e nem convênio hospitalar).<br><br>
  4.5 - As partes elegem o foro da comarca de Ipatinga/MG, com renúncia expressa de qualquer outro, por mais privilegiado que seja ou venha ser.<br></strong></p>

<p align="justify" class="texto">Ipatinga, <?php echo mask($dtcontrato,'##/##/####')?>.</p>

<table width="100%" border="0">
  <tr>
    <th scope="col"><p align="center" class="texto">_________________________________________________</p></th>
    <th scope="col">&nbsp;</th>
    <th scope="col"><p align="center" class="texto">_________________________________________________</p></th>
  </tr>
  <tr>
    <th scope="col"><p align="center" class="texto"><?php echo $row['nometitular']?></p></th>
    <th scope="col">&nbsp;</th>
    <th scope="col"><p align="center" class="texto">SAÚDE E HARMONIA CLÍNICA MÉDICA</p></th>
  </tr>
  <tr>
    <th scope="col"><p align="center" class="texto">ASSINATURA DO ADERENTE</p></th>
    <th scope="col">&nbsp;</th>
    <th scope="col"><p align="center" class="texto">&nbsp;</p></th>
  </tr>

</table>

<table>
<tr>
<td>
<img src='../../includes/imgs/impressora.jpg' onclick='javascript:window.print();' title='Imprimir Contrato'>
</td>
</tr>
</table>

</body>
</html>