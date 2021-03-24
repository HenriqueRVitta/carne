<?php header ('Content-type: text/html; charset=ISO-8859-1');

	session_start();


	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	include ("../../includes/classes/paging.class.php");

  $conec = new conexao;
  $conec->conecta('MYSQL');

?>

<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- 
Para ISO-8859-1:
<html>
<head>
<title>Minha pagina</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

Para UTF-8:
<html>
<head>
<title>Minha pagina</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 -->


<title>Contrato de Ades�o</title>
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
$query = "SELECT a.nometitular, a.endereco, a.numero, a.bairro, a.cidade, a.uf, a.cep, a.identidade, a.cpf, b.descricao, b.formapagto, c.valor, d.datacontrato, d.diavencto, d.nrocontrato, d.plano ".
		 "FROM carne_titular a Join carne_contratos d ".
		  						"on d.idtitular = a.id ".
							  "Join carne_tipoplano b on b.id = d.plano ".
							  "Join carne_competenciaplano c on c.idplano = b.id ".
		"where a.id =".$_GET['cod']."";
		
		
$resultado = mysqli_query($conec->con,$query) or die("ERRO na Query ".$query);
$row = mysqli_fetch_array($resultado);

$dtcontrato = str_replace('/','',substr(converte_datacomhora($row['datacontrato']),0,10));
				
if(substr($row['formapagto'],0,3)=='CAR') {
$formapagto = "CARN�";
} else {
$formapagto = "BOLETO BANC�RIO";	
}


$LocalCidade = "";
$CepCidade = "30000-000";
$endereco = "";

?>
<p>&nbsp;</p>
<table width="800" border="0" cellspacing="0" cellpadding="0" BGCOLOR="#FFFFFF">
  <tr>
    <th scope="col"><img src="../../logo.png" width="350" height="100" alt="agenda" align="right"/></th>
	<th width="400" scope="col" align="left"><h2>CARN� DE DOA��O � SANTA CASA</h2>
    <p align="center" class="texto">Hospital S�o Gabriel</br>
	CNPJ: 18.039.461/0001-33 INSC. Estadual: Isenta</br>
	Fone: (37) 3335-1250 Fax: (37) 3335-1350 email: hospsgabriel@yahoo.com.br</br>
	Av. Eunice Leite, 122 - Centro Passa Tempo-MG CEP: 35537-000</p>	
	</th>
  </tr>
</table>
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td scope="col"><p>&nbsp;</p>
    <p align="justify" class="texto"></br></p>    
	<p align="center" class="texto"><strong><u>CL�USULAS</u>:</strong><br /><br />
    <p align="justify" class="texto"></br></p>
    	
      <p align="justify" class="texto">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. O carn� de doa��o � destinado a doadores volunt�rios, na forma das cl�usulas 03 e 04.</p>
    <p align="justify" class="texto">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Os servi�os oferecidos pela Santa Casa s�o os seguintes:</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a. Di�ria hospitalar em apartamentos com direito a acompanhante, com visitas permitidas de 09:00 �s 20:00 horas, diariamente.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b. Servi�o de enfermagem durante a perman�ncia hospitalar.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c. Acompanhamento m�dico dentro do quadro de profissionais credenciados pelo hospital.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d. Disponibiliza��o das salas cir�rgica e obst�trica.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;e. Medicamentos e materiais padronizados pelo hospital.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;f. Exames complementares de diagn�sticos necess�rios, dispon�veis no hospital e devidamente solicitados pelo m�dico assistente, </br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;durante a interna��o do paciente ou dependente.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>g. UMA CONSULTA POR M�S, NO HOR�RIO DAS 08:00 �S 18:00 HORAS.</strong></br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;h. Atendimento de urg�ncia e emerg�ncia, no hospital, PELO M�DICO DE PLANT�O, a qualquer hora, em qualquer dia</br> 
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;da semana; sendo que, a avalia��o de cada caso ser� feita pelo m�dico plantonista.</br> 
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Par�grafo �nico:</strong> As interna��es em acomoda��es especiais (apartamentos) obedecer�o � ordem de chegada dos pacientes</br> 
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;no hospital. Havendo lota��o completa de apartamentos, os pacientes aguardar�o</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;as vagas em enfermarias, sem preju�zo da aten��o m�dica hospitalar.</p>

    <p align="justify" class="texto">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. Os valores da doa��o ser�o os seguintes:</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a. Doa��o familiar: 10% (dez por cento) do sal�rio m�nimo vigente.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b. Doa��o individual: 6% (seis por cento) do sal�rio m�nimo vigente.</p>

    <p align="justify" class="texto">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. S�o considerados pertencentes ao grupo familiar: o doador, a esposa, filhas menores de 21 anos de idade, filhos menores de 18 anos</br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;de idade; em ambos os casos solteiros ou interditados judicialmente.</p>

    <p align="justify" class="texto">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5. Car�ncias:</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a. Interna��es cl�nicas: 03 (tr�s) meses de doa��o.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b. Cirurgias e urg�ncia/emerg�ncia oferecidas pelo hospital: 03 (tr�s) meses de doa��o.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c. Cirurgias programadas: 12 (doze) meses de doa��o.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d. Partos e ces�reas: 09 (nove) meses de doa��o.</p>
	    
    <p align="justify" class="texto">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6. Das exig�ncias do ato de interna��o:</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a. Mensalidades rigorosamente em dia.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b. Documentos de identidade ou equivalente.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c. Solicita��o de interna��o pelo M�dico Assistente.</p>

    <p align="justify" class="texto">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;7. N�o � de responsabilidade da Santa Casa:</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a. Remo��o de pacientes.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b. Pagamento de profissionais n�o credenciados.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c. Refei��es para mais de um acompanhante.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d. Guarda e responsabilidade de pertences de pacientes, inclusive ve�culos estacionados nas depend�ncias do hospital.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;e. Exames complementares fora dos estabelecimentos na al�nea �F� da cl�usula 2.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;f. Liga��es telef�nicas interurbanas.</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;g. Lavagem e passagem de roupas particulares de pacientes.</p>

    <p align="justify" class="texto">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8. Da anota��o dos dependentes:</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No ato da assinatura deste termo, o doador fornecer� os nomes dos dependentes, acompanhados do x�rox da certid�o de</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;nascimento, nos termos da Cl�usula 4, em formul�rio pr�prio, que far� parte integrante deste termo.</p>

    <p align="justify" class="texto">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9. Da desist�ncia:</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Em caso de desist�ncia, est� dever� ser comunicada � Santa Casa, por escrito, sendo que a cessa��o dos benef�cios se dar� ap�s</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;01 (um) m�s subseq�ente ao da comunica��o.</p>

    <p align="justify" class="texto">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;10. A falta de doa��o nos prazos estipulados na cl�usula 10 implica na suspens�o imediata dos direitos aqui avan�ados. Sendo a</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;suspens�o superior a 90 (noventa) dias, acarretar� nova ades�o sujeita �s car�ncias constantes da Cl�usula 5, sendo considerado</br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;novo doador, para todos os efeitos.</p>

    <p align="justify" class="texto"></br></p>
    <p align="justify" class="texto"></br></p>
    <p align="justify" class="texto"></br></p>

    <p align="justify" class="texto"></br></p>
    <p align="justify" class="texto">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E, por estarem cientes, as partes assinam o presente termo de doa��o.</p>
    
    <p align="justify" class="texto"></br></p>
	<p align="justify" class="texto">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Passa Tempo,________de_____________________de__________.</p>

    <p align="justify" class="texto"></br></p>

<table width="100%" border="0">
  <tr>
    <th scope="col"><p align="center" class="texto">Doador:__________________________________________</p></th>
    <th scope="col">&nbsp;</th>
    <th scope="col"><p align="center" class="texto">_________________________________________________</p></th>
  </tr>
  <tr>
    <th scope="col"><p align="center" class="texto"><?php echo $row['nometitular']?></p></th>
    <th scope="col">&nbsp;</th>
    <th scope="col"><p align="center" class="texto">SANTA CASA DE MISERIC�RDIA DE PASSA TEMPO</p></th>
  </tr>
  <tr>
    <th scope="col"><p align="center" class="texto">(Assinatura leg�vel, por extenso)</p></th>
    <th scope="col">&nbsp;</th>
    <th scope="col"><p align="center" class="texto">&nbsp;</p></th>
  </tr>
</table>

    <p align="justify" class="texto"></br></p>
    
<table width="90%" border="0">
  <tr>
    <td scope="col" width="10%"></tD>
    <td scope="col" width="10%">TITULAR:</tD>
    <td scope="col"><?php echo $row['nometitular']?></td>
  </tr>
  <tr>
    <td scope="col" width="10%"></tD>
    <td scope="col" width="10%">RG:</tD>
    <td scope="col"><?php echo $row['identidade']?></td>
    <td scope="col" width="10%">CPF:</tD>
    <td scope="col"><?php echo $row['cpf']?></td>
  </tr>
  <tr>
    <td scope="col" width="10%"></tD>
    <td scope="col" width="10%">ENDERE�O:</tD>
    <td scope="col"><?php echo trim($row['endereco']).",".$row['numero']?></td>
  </tr>
  <tr>
    <td scope="col" width="10%"></tD>
    <td scope="col" width="10%">BAIRRO:</tD>
    <td scope="col"><?php echo $row['bairro']?></td>
    <td scope="col" width="10%">CEP:</tD>
    <td scope="col"><?php echo $row['cep']?></td>
  </tr>
  <tr>
    <td scope="col" width="10%"></tD>
    <td scope="col" width="10%">CIDADE:</tD>
    <td scope="col"><?php echo $row['cidade']?></td>
    <td scope="col" width="10%">ESTADO:</tD>
    <td scope="col"><?php echo $row['uf']?></td>
  </tr>

</table>
 
     <p align="justify" class="texto"></br></p>
     <p align="justify" class="texto"></br></p>

<table width="100%" border="1">
  <tr>
    <th height="40" colspan="3" scope="col" align="left"><p align="center" class="texto">DEPENDENTES:</p></th>
  </tr>
  <tr>
    <th scope="col">Nome</th>
    <th scope="col">Parentesco</th>
    <th scope="col">Data Nascimento</th>
  </tr>

<?php 

	$query = "SELECT d.nome,t.descricao as parentesco,d.datanasc FROM carne_dependente d Join carne_titular c on c.id = d.idtitular ".
			 "left Join carne_tipodependente t on t.id = d.parentesco ".
			 "where d.idtitular = ".$_GET['cod']."";
			
	$resultado = mysqli_query($conec->con,$query) or die("ERRO na Query ".$query);

	while ($row = mysqli_fetch_array($resultado))
	{

		$date = new DateTime($row['datanasc']);
		
		
		print "<tr>";
		print "<th scope='col'>".$row['nome']."</th>";
		print "<th scope='col'>".$row['parentesco']."</th>";
		print "<th scope='col'>".$date->format('d-m-Y')."</th>";
		print "</tr>";
		
	}

?> 

</table>

     <p align="justify" class="texto"></br></p>
 
<table>
<tr>
<td>
<img src='../../includes/imgs/impressora.jpg' onclick='javascript:window.print();' title='Imprimir Contrato'>
</td>
</tr>
</table>

</body>
</html>