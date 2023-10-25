<?php

/*      Copyright 2023 MTD

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 25/10/2023 11:03 GLPI 33238

		* Módulo Carnê *

		Emissao da Capa do Carne

*/

	session_start();

	include ("../../includes/classes/conecta.class.php");
	include ("../../includes/classes/auth.class.php");
	include ("../../includes/classes/dateOpers.class.php");
	include ("../../includes/config.inc.php");
	include ("../../includes/functions/funcoes.inc");

	$conec = new conexao;
	$conec->conecta('MYSQL');
	
	$dtinicial = Fdate($_POST['datainicio']);
	$dtfinal = Fdate($_POST['datafim']);
	$titular = $_POST['titular'];
	$pcwhere = "";
    $codigoinicio = $_POST['codigoinicio'];
	$codigofim = $_POST['codigofim'];

	if($titular<> -1 ) {
		$pcwhere.=" and t.id =".$titular;
		$codigoinicio = "";
		$codigofim = "";
	}

	if(isset($_POST['codigoinicio']) && (!empty($_POST['codigoinicio']) && !empty($codigofim))){
		$pcwhere.=" and t.id between '".$codigoinicio."' and '".$codigofim."'";
	}

		
		

$date = date("d/m/Y g:i a");

	// Come�a aqui a listar os registros
    $query = "select nome_hosp, end_hosp, num_hosp, bair_hosp, cid_hosp, uf_hosp, cep_hosp, cgc_hosp, ddd1_hosp, fone_hosp from configuracao limit 1";
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$rowConfg = mysqli_fetch_array($resultado);
	$nomehosp = $rowConfg['nome_hosp'];

	// Come�a aqui a listar os registros
       $query = "SELECT t.id, t.nrocarne, t.nrocarteira, t.nometitular, t.endereco, t.numero, t.cep, t.bairro, t.cidade, t.uf, c.nrocontrato, c.plano, c.diavencto, p.descricao, p.formapagto, p.percdesc, cp.compet_ini, cp.compet_fim, cp.valor
				 FROM carne_titular t Join carne_contratos c
				 on c.idtitular = t.id
				 Join carne_tipoplano p
				 on p.id = c.plano
				 Join carne_competenciaplano cp
				 on cp.idplano = c.plano 
				 Where t.situacao = 'ATIVO' ".$pcwhere."";
      
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$lntotalpg = 0.00;
	$registros = 0;
	
	
	$MesIni = date( 'm', strtotime($dtinicial));
	$MesFim = date( 'm', strtotime($dtfinal));
	$lcString="";
	while($row = mysqli_fetch_array($resultado)){

	// Cabe�alho do regisrtos encontrados
    $lcString.= "<table style='width: 605px; height: 284px;' border='1' cellspacing='1' cellpadding='1'>
    <tr><td>";
    
    $lcString.= "<table style='width: 605px; height: 284px;' border='0' cellspacing='1' cellpadding='1'>";

	
		$lcString.="<tr>
		    <td width='182' rowspan='5'><p><img src='imagens/logo.png' width='180' height='180' alt='image' /></p>
		    <p>&nbsp;</p></td>
		    <td align='center' colspan='2'><div style='text-align: center; font-family: serif; font-size: 20pt; color: #000000;'>".$nomehosp."</div></td>
		  </tr>
		  <tr>
		    <td align='center' colspan='2'><div style='text-align: center; font-family: serif; font-size: 15pt; color: #000000;'>".mask($rowConfg['cep_hosp'],'#####-###')." - ".$rowConfg['cid_hosp']." ".$rowConfg['uf_hosp']."</div></td>
		  </tr>
		  <tr>
		    <td align='center' colspan='2'><div style='text-align: center; font-family: serif; font-size: 15pt; color: #000000;'>TEL/FAX: ".$rowConfg['ddd1_hosp']." ".mask($rowConfg['fone_hosp'],'####-####')."</div></td>
		  </tr>
		  <tr>
		    <td align='center' colspan='2'><div style='font-weight: bold; text-align: center; font-family: serif; font-size: 26pt; color: #000000;'>CARN&Ecirc; DE MENSALIDADE</div></td>
		  </tr>
		  <tr>
		    <td align='left' height='48' width='120'><div style='text-align: left; font-family: serif; font-size: 20pt; color: #000000;'>Nro: ".$row['nrocarteira']."</div></td>
		    <td align='left' height='48' width='500'><div style='text-align: left; font-family: serif; font-size: 20pt; color: #000000;'>Nome: ".$row['nometitular']."</div></td>
		  </tr>
		  <tr>
		    <td>&nbsp;</td>
		    <td width='120'>&nbsp;</td>
		  </tr>
		  <tr>
		    <td rowspan='2' style='text-align: right; font-family: serif; font-size: 15pt; color: #000000;'>Endere&ccedil;o:</td>
		    <td colspan='2'>".$row['endereco']." ".$row['numero']." - ".$row['bairro']." / ".$row['cep']." - ".$row['cidade']." - ".$row['uf']."</td>
		  </tr>
		  </table>
		</tr></td><br></table>

		<table style='width: 605px; height: 284px; text-align: center;' border='0'>
		<tr>
			<td><br></td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
		<tr>
			<td>--recorte aqui-----------------------------------------------------------------------------------------------------------------</td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
		</table>
		
		<table style='width: 605px; height: 284px;' border='1' cellspacing='1' cellpadding='1'>
		<tr>
		<td>
		<table style='width: 605px; height: 284px;' border='0'>
		<tr>
		    <th width='450' align='left' scope='col'>Nome: ".$row['nometitular']."</th>
		    <th width='300' align='right' scope='col'>Nro :".$row['nrocarteira']."</th>
		  </tr>";
		

	// Seleciono aqui os dependentes do titular
    $queryDep = "select a.nome,b.descricao from carne_dependente a left join carne_tipodependente b on b.id = a.parentesco where a.idtitular = ".$row['nrocarteira']."";
    $resuldep = mysqli_query($conec->con,$queryDep) or die('ERRO NA QUERY !'.$query);
	$x=0;
	while($rowDep = mysqli_fetch_array($resuldep)) {

	  $lcString.="<tr>
		    <td>".$rowDep['nome']." - ".$rowDep['descricao']."</td>
		    <td>&nbsp;</td>
		  </tr>";

	  $x++;
	  
	}
	
	
	$lcString.="<tr>
		    	<td>&nbsp;</td>
		  		</tr>
				<tr>
		    	<td>&nbsp;</td>
		  		</tr>
				<tr>
		    	<td>&nbsp;</td>
		  		</tr>
				<tr>
		    	<td>&nbsp;</td>
		  		</tr>
		  		<tr>
		    	<td>&nbsp;</td>
		  		</tr>
				<tr>
		    	<td>&nbsp;</td>
		  		</tr>";		  		
	
		
	$lcString.= "</table>
	</tr></td></table>	";
		/*
		$lcString.= "</ br></ br>
			<p align='justify' class='texto'><strong><u>Comunicado,</u></strong><br />
			  Irm&atilde;o Contribuinte, caso esteja acomodado em apartamento e faltar leito para conv&ecirc;nios e atendimentos particulares, o paciente ser&aacute; reacomodado, provisoriamente, em um conjugado, com o mesmo atendimento, 
			  at&eacute; que libere a vaga. Agradecemos sua doa&ccedil;&atilde;o e contamos com sua compreens&atilde;o.<br />
			<p align='center'>Santa Casa de Itaguara.</p>";
		*/

		if(!empty($codigoinicio)) {
			$lcString.="<p style='page-break-before:always'></p>";
		}
			

		$i++;
		$registros+=$i;
		
	}

		if($registros == 0) {

		$lcString.= "</tr><tr>
		<td height='42' style='vertical-align: top; text-align: center; font-family: serif; font-size: 22pt; color: #000000;'>Nenhum registro encontrado<br>Verifique se esta ATIVO.</TD>
		</tr><tr>";
		
		}

include("../../includes/mpdf/vendor/autoload.php");

//$mpdf = new \Mpdf\Mpdf(['debug' => true]);
$mpdf = new \Mpdf\Mpdf(['orientation' => 'P']);
$mpdf->WriteHTML($lcString);
$mpdf->Output();


exit;

?>