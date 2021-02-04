<?php

session_start();


	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	include ("../../includes/classes/paging.class.php");

?>

<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

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
$query = "SELECT a.nometitular, a.profissao, a.estadocivil, a.endereco, a.numero, a.bairro, a.cidade, a.cep, a.sexo, b.descricao, b.formapagto, c.valor, d.datacontrato, d.diavencto, d.nrocontrato, d.plano ".
		 "FROM carne_titular a Join carne_contratos d ".
		  						"on d.idtitular = a.id ".
							  "Join carne_tipoplano b on b.id = d.plano ".
							  "Join carne_competenciaplano c on c.idplano = b.id ".
		"where a.id =".$_GET['cod']."";
		
		
$resultado = mysql_query($query) or die("ERRO na Query ".$query);
$row = mysql_fetch_array($resultado);

$dtcontrato = str_replace('/','',substr(converte_datacomhora($row['datacontrato']),0,10));
				
if(substr($row['formapagto'],0,3)=='CAR') {
$formapagto = "CARNÊS";
} else {
$formapagto = "BOLETO BANCÁRIO";	
}

$Nacionalidade = "brasileiro";
if($row['datacontrato']=='F'){
	$Nacionalidade = "brasileira";
}

$profissao = "PROFISSÂO NÂO INFORMADO";
if(!empty($row['profissao'])) {
	$profissao = $row['profissao'];
}

$n = $row['estadocivil'];
switch ($n) {
    case "1":
		$EstadoCivil = "ESTADO CIVIL NÂO INFORMADO";
        break;
    case "2":
		$EstadoCivil = "solteiro";
        break;
    case "3":
		$EstadoCivil = "casado";
        break;
    case "4":
		$EstadoCivil = "divordiado";
        break;
    case "5":
		$EstadoCivil = "separado";
        break;
    case "6":
		$EstadoCivil = "viúvo(a)";
        break;
    case "7":
		$EstadoCivil = "amasiado";
        break;
    case "8":
		$EstadoCivil = "indefinido";
        break;
    default:
    		$EstadoCivil = "ESTADO CIVIL NÂO INFORMADO";
    	
}

		
$LocalCidade = "";
$CepCidade = "30000-000";
$endereco = $row['endereco'].",".$row['numero'].", ".$row['bairro'].", ".$row['cidade'].", ".$row['cep']

?>
<table width="80%" border="0" cellspacing="0" cellpadding="0" BGCOLOR="#FFFFFF" style="margin-left: 60px;">
    <th scope="col"><p align="center" class="texto"><h2>CONTRATO DE ADESÃO</h2></p></th>
</table>
<table width="80%" border="0" cellspacing="0" cellpadding="0" style="margin-left: 60px;">
  <tr>
    <td scope="col"><p>&nbsp;</p>
      <p align="justify" class="texto">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pelo presente instrumento particular que entre si fazem, na melhor forma de direito, de um lado SANTA CASA DE MISERICÓRDIA DE ITAGUARA, inscrita no CNPJ sob o número 20.878.294/0001-66, com sede na rua Antônio Pereira Rezende, nº 19, bairro Nogueiras, CEP 35.488-000, Itaguara/MG, inscrita no CNPJ nº 20.878.294/0001-66, representada neste ato por sua provedora, doravante denominada simplesmente CONTRATADA de outro lado
      <?php echo $row['nometitular']?>, <?php echo $Nacionalidade; ?>, <?php echo $profissao;?>, <?php echo $EstadoCivil; ?>, residente e domiciliado na <?php echo $endereco; ?> doravante denominado simplesmente ADERENTE, os quais livremente firmam o presente contrato que reger-se-á pelas seguintes cláusulas e condições:</p><p align="justify"></p>
<p align="justify" class="texto"><strong><u>CLÁUSULA PRIMEIRA - DO OBJETO</u>:</strong><br /><br />
  O presente contrato tem como objeto adesão ao "Carnê de Doação", sendo estes descontos nos serviços prestados pela CONTRATADA, nas suas instalações e dependências, por seu quadro técnico de profissionais, bem como exames, ao ADERENTE e seus dependentes, quando for o caso.<br />
<p align="justify"></p>
<p align="justify" class="texto"><strong><u>CLÁUSULA SEGUNDA – DO VALOR DA CONTRIBUIÇÃO</u></strong> <br /><br />
  O ADERENTE contribuirá com uma taxa mensal de acordo com o que for fixado anualmente pela Diretoria da CONTRATADA.

Parágrafo Único - O atraso no pagamento das prestações pelo beneficiário superior a 15 (quinze) dias, implicará na interrupção dos benefícios ora pactuados.</u></strong> <br /><br />
<p align="justify"></p>
<p align="justify" class="texto"><strong><u>CLÁUSULA TERCEIRA– DOS BENEFÍCIOS:</u></strong><br /><br />
O ADERENTE e seus dependentes, quando for o caso, terão os seguintes benefícios:<br><br>
    I. Internações gratuitas em acomodações especiais, em apartamento individual ou com dois leitos de acordo com o número de apartamentos e de leitos disponíveis, levando em consideração a ordem de chegada dos pacientes. Não havendo apartamentos disponíveis, os pacientes aguardarão na enfermaria até que haja a liberação de algum apartamento. No caso dessas internações, haverá amplos horários de visitas;<br><br> 
    II. Consultas de urgência ilimitadas, atendidas pelo médico plantonista da CONTRATADA, com serviços de apoio gratuitos (Raio X, ECG, exames de sangue, urina, fezes, escarros, etc), realizado na CONTRATADA;<br><br>
    III. Medicamentos e materiais hospitalares, padronizados gratuitos, utilizados durante a internação;<br><br> 
    IV. Desconto nos medicamentos que não são comercializados nas farmácias locais.<br><br>
    V. Transferência graciosa para outros hospitais, desde que por recomendação do médico assistente da CONTRATADA;<br><br>
    VI. Isenção da taxa hospitalar em cirurgias programadas, inclusive partos, ligaduras e vasectomias;<br><br>
    VII. Desconto de 20% (vinte por cento) na equipe médica de cirurgia plástica e 50% (cinquenta reais) na taxa hospitalar;<br><br>
    VIII. Descontos em consultas com médicos especialistas em atendimento nas instalações da CONTRATADA;<br><br>
    IX. Descontos em exames laboratoriais, referente aos laboratórios Hebert Lima Vilela e Italab;<br><br>
    X. Descontos de 40% (quarenta por cento) em exames de Raio X, solicitados por médicos não credenciados na CONTRATADA;<br><br> 
    XI. Isenção nos exames de ultrassom, referente a parte do hospital;<br><br></strong></p>

<p align="justify" class="texto"></p>
<p align="justify" class="texto"><strong><u>CLÁUSULA QUARTA – DA CARÊNCIA:</u></strong><br /><br />
	Os períodos de carências passarão a contar a partir da data de inclusão do beneficiário e deverão ser observados da seguinte forma:<br><br>
    I- 03 (três) meses para os descontos em consultas e exames<br><br>
    II- 06 (seis) meses para internação;<br><br>
    III- 12 (doze) meses para cirurgias;<br></strong></p>

<p align="justify" class="texto"><strong><u>CLÁUSULA QUINTA – DAS OBRIGAÇÕES DAS PARTES</u></strong><br />
<p align="justify" class="texto"><strong><u>5.1 DO ADERENTE:</u></strong><br /><br />
    I. Contribuir mensalmente com a taxa estipulada pela CONTRATANTE;<br><br>
    II. Manter-se em dia com as contribuições;<br><br> 
    III. Cumprir rigorosamente as cláusulas do presente contrato;<br></strong></p>

<p align="justify" class="texto"><strong><u>5.2 DA CONTRATADA:</u></strong><br /><br />
    I. Assegurar ao ADERENTE a de prestação de serviços nas áreas de saúde a custo reduzido, intermediados pelo carnê de doação.<br><br> 
    II. Realizar os atendimentos, dentro dos limites contratados, de forma a atender às necessidades dos beneficiários, privilegiando os casos de emergência ou urgência;<br><br>
    III. Fornecer meios para que os serviços ora contratados possam ser executados com diligência e perfeição, dentro das normas impostas;<br><br>
    IV. Garantir o acesso a informações que se fizerem necessárias para que os serviços possam transcorrer normalmente, sem interrupções;<br><br>
    V. Cumprir rigorosamente as cláusulas do presente contrato;<br></strong></p>

<p align="justify" class="texto"><strong><u>CLÁUSULA SEXTA- DA VIGÊNCIA</u></strong><br /><br />
	O presente contrato tem validade pelo prazo de 12 (doze) meses, sendo renovado automaticamente, caso não haja manifestação em contrário por umas das partes.<br></strong></p>

<p align="justify" class="texto"><strong><u>CLÁUSULA SÉTIMA– DA RESCISÃO</u></strong><br /><br />
	Este contrato poderá ser rescindido a qualquer tempo, por iniciativa de qualquer das partes, sem nenhum ônus, mediante comunicação por escrito, com antecedência mínima de 30 (trinta) dias.<br></strong></p>


<p align="justify" class="texto"><strong><u>CLÁUSULA OITAVA - DAS DISPOSIÇÕES GERAIS</u></strong><br /><br />
	8.1 - O presente contrato é de adesão, bilateral, gerando direitos e obrigações para as partes, conforme dispõe o Código Civil Brasileiro, estando também sujeito às disposições do Código de Defesa do Consumidor.<br><br>
	8.2 - O ADERENTE se declara ciente e de acordo que os serviços oferecidos pelo carnê de doação não caracterizam como Plano de Saúde e nem convênio hospitalar.<br></strong></p>


<p align="justify" class="texto"><strong><u>CLÁUSULA NONA - DO FORO</u></strong><br /><br />
	Os contratantes elegem o foro da comarca de Itaguara/MG, com renúncia de qualquer outro, por mais privilegiado que seja, para dirimir dúvidas de interpretação e aplicação deste contrato, bem como para execução.<br><br>
	Por estarem justos e acertados, firmam o presente contrato em duas vias, de igual teor e forma, se obrigando a cumprir o que nele está avençado, na presença de duas testemunhas, que abaixo também subscrevem, para os fins pretendidos.<br></strong></p>

<p align="justify" class="texto">Itaguara/MG, <?php echo mask($dtcontrato,'##/##/####')?>.</p><br>

<p align="center" class="texto">_________________________________________________________<br>
SANTA CASA DE MISERICÓRDIA DE ITAGUARA<br>EDVAR APARECIDO MAMEDE ALVES<br>PROVEDOR</p><br><br>

<p align="center" class="texto">_________________________________________________________<br>
<?php echo $row['nometitular']?><br>ASSINATURA ADERENTE</p><br><br>

<p align="justify" class="texto">Testemunhas:</p><br>

<table width="100%" border="0">
  <tr>
    <th scope="col"><p align="justify" class="texto">_________________________________________________</p></th>
    <th scope="col">&nbsp;</th>
    <th scope="col"><p align="justify" class="texto">_________________________________________________</p></th>
  </tr>
  <tr>
    <th scope="col"><p align="justify" class="texto">NOME:</p></th>
    <th scope="col">&nbsp;</th>
    <th scope="col"><p align="justify" class="texto">NOME:</p></th>
  </tr>
  <tr>
    <th scope="col"><p align="justify" class="texto">RG:</p></th>
    <th scope="col">&nbsp;</th>
    <th scope="col"><p align="justify" class="texto">RG:</p></th>
  </tr>
  <tr>
    <th scope="col"><p align="justify" class="texto">CPF:</p></th>
    <th scope="col">&nbsp;</th>
    <th scope="col"><p align="justify" class="texto">CPF:</p></th>
  </tr>

</table>

<table>
<tr>
<td>
<br>
<img src='../../includes/imgs/impressora.jpg' onclick='javascript:window.print();' title='Imprimir Contrato'>
</td>
</tr>
</table>

</body>
</html>