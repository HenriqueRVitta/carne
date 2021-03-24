<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 20/02/2019 09:57 GLPI 13188

		* M�dulo Carn� *

		Emissao da Capa do Carne

*/

	session_start();
	
	// Impress�o do Capa
	if(!empty($_POST['tipoimpressao']) && $_POST['tipoimpressao'] == 1) {
 	     echo "<script>redirect('geracapacarne.php');</script>";		
	}


	include("../../includes/mpdf54/mpdf.php");	
	include ("../../includes/include_geral_III.php");

	$conec = new conexao;
	$conec->conecta('MYSQL');
	
	$dtinicial = Fdate($_POST['datainicio']);
	$dtfinal = Fdate($_POST['datafim']);
	$titular = $_POST['titular'];
	//$nrocarne = $_POST['nrocarne'];
    $pcwhere = "";
    
		if($titular<> -1 ) {
			$pcwhere.=" and t.id =".$titular;
		}

		/*
		if(!empty($_POST['nrocarne'])) {
			$pcwhere.=" and t.nrocarne =".$nrocarne;
		}
		*/
		
		
//$mpdf=new mPDF('en-x','A4','','',32,25,47,47,10,10); 
$mpdf=new mPDF('en-x','A4','','',12,12,10,30,5,5);

$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins

$date = date("d/m/Y g:i a");

$header = '
<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;"><tr>
<td width="33%">Left header p <span style="font-size:14pt;">{PAGENO}</span></td>
<td width="33%" align="center"><img src="sunset.jpg" width="126px" /></td>
<td width="33%" style="text-align: right;"><span style="font-weight: bold;">Right header</span></td>
</tr></table>
';
$headerE = '
<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;"><tr>
<td width="33%"><span style="font-weight: bold;">Outer header</span></td>
<td width="33%" align="center"><img src="sunset.jpg" width="126px" /></td>
<td width="33%" style="text-align: right;">Inner header p <span style="font-size:14pt;">{PAGENO}</span></td>
</tr></table>
';

$footer = '<div align="center">See <a href="http://mpdf1.com/manual/index.php">documentation manual</a></div>';
$footerE = '<div align="center">See <a href="http://mpdf1.com/manual/index.php">documentation manual</a></div>';

$header = "";
$headerE = "";
$footer = "";
$footerE = "";


$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
$mpdf->SetHTMLFooter($footer);
$mpdf->SetHTMLFooter($footerE,'E');

	// Come�a aqui a listar os registros
    $query = "select nome_hosp, end_hosp, num_hosp, bair_hosp, cid_hosp, uf_hosp, cep_hosp, cgc_hosp, ddd1_hosp, fone_hosp from configuracao limit 1";
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$rowConfg = mysqli_fetch_array($resultado);
	$nomehosp = $rowConfg['nome_hosp'];

	// Come�a aqui a listar os registros
       $query = "SELECT t.id, t.nrocarne, t.nometitular, t.endereco, t.numero, t.cep, t.bairro, t.cidade, t.uf, c.nrocontrato, c.plano, c.diavencto, p.descricao, p.formapagto, p.percdesc, cp.compet_ini, cp.compet_fim, cp.valor
				 FROM carne_titular t Join carne_contratos c
				 on c.idtitular = t.id
				 Join carne_tipoplano p
				 on p.id = c.plano
				 Join carne_competenciaplano cp
				 on cp.idplano = c.plano 
				 Where t.situacao = 'ATIVO' ".$pcwhere."";
      
	// Cabe�alho do regisrtos encontrados
    $lcString= "<table width='800' border='1' cellspacing='1' cellpadding='1'>
    <tr><td>";
    
    $lcString.= "<table width='800' border='0' cellspacing='1' cellpadding='1'>";
	
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$lntotalpg = 0.00;
	$registros = 0;
	
	
	$MesIni = date( 'm', strtotime($dtinicial));
	$MesFim = date( 'm', strtotime($dtfinal));
		
	while($row = mysqli_fetch_array($resultado)){
		
		$lcString.="<tr>
		    <td width='182' rowspan='5'><p><img src='imagens/logo.png' width='190' height='180' alt='image' /></p>
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
		    <td align='center' colspan='2'><div style='font-weight: bold; text-align: center; font-family: serif; font-size: 26pt; color: #000000;'>CARN&Ecirc; DE DOA&Ccedil;&Atilde;O MENSAL</div></td>
		  </tr>
		  <tr>
		    <td align='left' height='48' width='120'><div style='text-align: left; font-family: serif; font-size: 20pt; color: #000000;'>Nro: ".$row['id']."</div></td>
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
		</tr></td></table>

		<table width='800' border='0'>
		<tr>
			<td>&nbsp;</td>
		</tr>
		</table>
		
		<table width='800' border='1' cellspacing='1' cellpadding='1'>
		<tr>
		<td>
		<table width='800' border='0'>
		<tr>
		    <th width='450' align='left' scope='col'>Nome: ".$row['nometitular']."</th>
		    <th width='300' align='right' scope='col'>Nro :".$row['id']."</th>
		  </tr>";
		

	// Seleciono aqui os dependentes do titular
    $queryDep = "select nome from carne_dependente where idtitular = ".$row['id']."";
    $resuldep = mysqli_query($conec->con,$queryDep) or die('ERRO NA QUERY !'.$query);
	$x=0;
	while($rowDep = mysqli_fetch_array($resuldep)) {

	  $lcString.="<tr>
		    <td>".$rowDep['nome']."</td>
		    <td>&nbsp;</td>
		  </tr>";

	  $x++;
	  
	}
		
		$i++;
		$registros+=$i;
		
	}

		if($registros == 0) {

		$lcString.= "</tr><tr>
		<td height='42' style='vertical-align: top; text-align: center; font-family: serif; font-size: 22pt; color: #000000;'>Nenhum registro encontrado<br>Verifique se esta ATIVO.</TD>
		</tr><tr>";
		
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
	
$mpdf->WriteHTML($lcString);

$mpdf->Output();
exit;

?>