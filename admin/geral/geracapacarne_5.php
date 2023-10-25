<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 31/07/2020 17:23 GLPI 19825

		* M�dulo Carn� *

		Emissao da Capa do Carne

*/

	session_start();
	
	// Impress�o do Capa
	if(!empty($_POST['tipoimpressao']) && $_POST['tipoimpressao'] == 1) {
 	     echo "<script>redirect('geracapacarne.php');</script>";		
	}


	//include("../../includes/mpdf54/mpdf.php");	
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
    $lcString= "<table style='width: 780px; height: 340px;' border='1' cellspacing='1' cellpadding='1'>
    <tr><td>";
    
    $lcString.= "<table style='width: 780px; height: 340px;' border='0' cellspacing='1' cellpadding='1'>";
	
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$lntotalpg = 0.00;
	$registros = 0;
	
	
	$MesIni = date( 'm', strtotime($dtinicial));
	$MesFim = date( 'm', strtotime($dtfinal));
		
	while($row = mysqli_fetch_array($resultado)){
		
		$lcString.="<tr>
  	     <td align='center' colspan='2'><img src='imagens/logo.png' width='220' height='100' alt='image' /></td>
		  </tr>
		<tr>
			<td><br></td>
		</tr>
		  <tr>
		    <td  style='text-align: center; font-family: serif; font-size: 13pt; color: #0000FF; font-weight: bold;'>Rua Pentateuco, 185 - Cana&auml; - Cep 35.164-200 - Ipatinga - MG</td>
		  </tr>
		  <tr>
		    <td  style='text-align: center; font-family: serif; font-size: 13pt; color: #0000FF; font-weight: bold;'>Telefax: (31) 3826-0311 - 3826-4282 / E-mail: clinicasaudeeharmonia@hotmail.com</td>
		  </tr>
		  </table>
		</tr></td><br></table>

		<table style='width: 680px; height: 340px; text-align: center;' border='0'>
		<tr>
			<td>--recorte aqui-------------------------------------------------------------------------------------------------------------------------</td>
		</tr>
		</table>
		
		<table style='width: 680px; height: 340px;' border='1' cellspacing='1' cellpadding='1'>
		<tr>
		<td>
		<table style='width: 680px; height: 340px;' border='0'>
		<tr>
		    <th width='450' align='left' scope='col'>Nome: ".$row['nometitular']."</th>
		    <th width='300' align='right' scope='col'>Nro :".$row['id']."</th>
		  </tr>";
		

	// Seleciono aqui os dependentes do titular
    $queryDep = "select a.nome,b.descricao from carne_dependente a left join carne_tipodependente b on b.id = a.parentesco where a.idtitular = ".$row['id']."";
    $resuldep = mysqli_query($conec->con,$queryDep) or die('ERRO NA QUERY !'.$query);
	$x=0;
	while($rowDep = mysqli_fetch_array($resuldep)) {

	  $lcString.="<tr>
		    <td>".retira_acentos_UTF8($rowDep['nome'])." - ".$rowDep['descricao']."</td>
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
		  		</tr>";		  		
	
		
	$lcString.= "</table>
	</tr></td></table>	";
	
	include("../../includes/mpdf/vendor/autoload.php");

	$mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
	$mpdf->WriteHTML($lcString);
	$mpdf->Output();

exit;

?>