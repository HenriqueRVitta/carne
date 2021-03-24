<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 03/02/2015 13:00

		* M�dulo Carn� *

		Relat�rio dos pagamentos registrados

*/

	session_start();

	include("../../includes/mpdf54/mpdf.php");	
	include ("../../includes/include_geral_III.php");
	$conec = new conexao;
	$conec->conecta('MYSQL');

	$lnCompet = substr($_POST['mesano'],3,4).substr($_POST['mesano'],0,2);
	$dtinicial = Fdate($_POST['datainicio']);
	$dtfinal = Fdate($_POST['datafim']);
	$titular = $_POST['titular'];
	$plano = $_POST['plano'];
	$localpagto = $_POST['localpagto'];
	$cidade		= $_POST['cidade'];
	$pcwhere	= "";

		if($titular<> -1 ) {
			$pcwhere.=" and c.id =".$titular;
		}

	if($_POST['tiporelatorio']==1) { $tiporel = "Analitico"; } else { $tiporel = "Sintetico"; } 


	// Inicio Dados Cabecalho	
	$lcBorda.="<table width='500' style='vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000000;'>
	<tr>
	<td align='right'>Data Inicial:</TD>
	<td align='left'>".$_POST['datainicio']."</TD>
	<td align='right'>Data Final:</TD>
	<td align='left'>".$_POST['datafim']."</TD></tr>";
	$lcBorda.= "<tr>";
	
	// Nome do Paciente
	if($_POST['titular'] <> -1 ) {
		
		$sql="SELECT nometitular FROM carne_titular where id = ".$_POST['titular']." ";
		$commit = mysqli_query($conec->con,$sql);
		$i=0;
			while($row = mysqli_fetch_array($commit)){

				$lcBorda.= "<td align='right'>Cliente:</TD>
				<td align='left'>".$row['nometitular']."</TD>";
				
				$i++;
			}
	}
	
		$lcBorda.= "<td align='right'>Relat&oacute;rio:</TD>
		<td align='left'>".$tiporel."</TD>";

	$lcBorda.= "</tr></table>";	
	// Fim Dados Cabecalho
		

	$nordem = $_POST['ordem'];

	switch ( $nordem ){
	  case 1:
		$pcordem	= " order by c.nometitular";
	    break;
	  case 2:
		$pcordem	= " order by k.databaixa";
		break;
	  case 3:
		$pcordem	= " order by k.mesano";
	  	break;
	  case 4:
		$pcordem	= " order by q.descricao";
	    break;
	  case 5:
		$pcordem	= " order by l.descricao";
	    break;
	  case 7:
		$pcordem	= " order by c.nrocarne";
	    break;
	    default:
		$pcordem	= " order by c.nometitular";
	}

	$lcgroup = "";
	
	if($_POST['separacao'] <> -1 ) {

	
	
		switch ( $_POST['separacao'] ){
		  case 1:
			$lcgroup = " group by c.cidade";
		  	break;
		  case 2:
			$lcgroup = " group by k.mesano";
		  	break;
		  case 3:
			$lcgroup = " group by u.nome";
		  	break;
		  case 4:
			$lcgroup = " group by l.descricao";
		  	break;
		  default:
			$lcgroup = " group by c.nometitular";
		}

		$lcBorda.= "</table>";
			
	}
	
	// Come�a aqui a listar os registros
       $query = "SELECT c.id, c.nometitular, c.registro, c.nrocarne, c.cidade, p.nrocontrato, p.plano, p.diavencto, p.datacontrato, q.descricao, q.percdesc, d.valor, d.compet_ini, d.compet_fim,
		space(1) as desclocal, space(1) as localpagto, space(10) as databaixa, 0.00 as vlrpago, space(1) as nome FROM carne_titular c
		Join carne_contratos p on p.idtitular = c.id
		Join carne_tipoplano q on q.id = p.plano
		Join carne_competenciaplano d on d.idplano = p.plano
		Where c.situacao = 'ATIVO' ".$pcwhere.$pcordem." limit 4000";
              
      //print_r($query);
      //break;
      
	// Cabe�alho do regisrtos encontrados
	$lcString.= "<table width='800' border='1' cellspacing='1' cellpadding='1'>
	<tr>
	<th scope='col' align='center'>Nome do Cliente</th>
	<th scope='col' align='center'>Desde</th>	
	<th scope='col' align='center'>Nro Carn&ecirc;</th>
	<th scope='col' align='center'>Taxa %</th>
	<th scope='col' align='center'>Vlr Plano</th>
	<th scope='col' align='center'>Ult.Pagto</th>
	</tr>";
       
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$qtdeIna = 0;
	
	$lntotalpg = 0.00;

	$dtini = explode('/',$_POST['datainicio']);
	$dtfim = explode('/',$_POST['datafim']);

	while($row = mysqli_fetch_array($resultado)){
		
		//$dtpagto = str_replace('/','',substr(converte_datacomhora($row['databaixa']),0,10));

		for ($x=$dtini[1]; $x <= $dtfim[1]; $x++) {
			
	       $queryIna = "SELECT mesano from carne_pagamentos Where databaixa between '".$dtinicial."' and '".$dtfinal."' and idcliente = ".$row['id'];
    	   $resultadoIna = mysqli_query($conec->con,$queryIna) or die('ERRO NA QUERY !'.$queryIna);
    		if(mysqli_num_rows($resultadoIna) == 0) {
    			
    			$UltPagto = "";
	       		$queryMaxPag = "SELECT max(mesano) as mesano from carne_pagamentos where idcliente = ".$row['id'];
    	   		$resultadoMax = mysqli_query($conec->con,$queryMaxPag) or die('ERRO NA QUERY !'.$queryMaxPag);
    	   		$rowMaxpag = mysqli_fetch_array($resultadoMax);
    	   		if(mysqli_num_rows($resultadoMax) > 0) {
    				$UltPagto = $rowMaxpag['mesano'];
    	   		}
    	   		
    	   		$dtregistro = str_replace('/','',substr(converte_datacomhora($row['registro']),0,10));
    	   				
				$lcString.= "<tr>
				<td align='left'>".retira_acentos_UTF8($row['nometitular'])."</TD>
				<td align='center'>".mask($dtregistro,'##/##/####')."</TD>
				<td align='center'>".$row['nrocarne']."</TD>
				<td align='left'>".$row['percdesc']."</TD>
				<td align='right'>".number_format($row['valor'],2,",",".")."</TD>
				<td align='center'>".invertecomp($UltPagto,1)."</TD>
				</tr>";

				//<td align='center'>".invertecomp($dtini[2].strzero($x,2),1)."</TD>
								
				$lntotalpg+=$row['valor'];
				$qtdeIna++;
				
    		}
    	   
		}
		
				
		$i++;
		
	}
	
	$lcString.= "</table>";
	
	//<p>&nbsp;</p>";
	
	// Resumo
	$lcString.= "<table width='100%' border='0'>
  	<tr>
    <th align='center'>RESUMO</th>
    </tr>
  	<tr>
    <td align='left'>Total do Valor dos Planos a Receber</th>
    <td align='right'>".number_format($lntotalpg,2,",",".")."</th>    
    </tr>
  	<tr>
    <td align='left'>Total de Inadimplentes listados</th>
    <td align='right'>".$qtdeIna."</th>    
    </tr>
	</table>
    </table>";


//echo $lcString;

	
//$mpdf=new mPDF('en-x','A4','','',32,25,47,47,10,10); 
$mpdf=new mPDF('en-x','A4','','',12,12,40,45,1,5);

$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins
$mpdf->useSubstitutions = false; 

$date = date("d/m/Y g:i a");


$header = "<table width='100%' style='border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;'><tr>
<td width='33%'>".$date."</span></td>
<td width='33%' align='center'><img src='../../logo.png' width='126px' /></td>
<td width='33%' style='text-align: right;'><span style='font-weight: bold;'>Pag. <span style='font-size:11pt;'>{PAGENO}</span></td>
</tr>
</table>
<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 14pt; color: #000000;'><tr>
<td width='33%' align='center'>Relat&oacute;rio de Inadimplentes no Carn&ecirc;</td>
</tr>
</table>".$lcBorda."";

$headerE = "<table width='100%' style='border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;'><tr>
<td width='33%'>".$date."</span></td>
<td width='33%' align='center'><img src='../../logo.png' width='126px' /></td>
<td width='33%' style='text-align: right;'><span style='font-weight: bold;'>Pag. <span style='font-size:11pt;'>{PAGENO}</span></td>
</tr>
</table>
<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 14pt; color: #000000;'><tr>
<td width='33%' align='center'>Relat&oacute;rio Recebimentos de Carn&ecirc;</td>
</tr>
</table>".$lcBorda."";


$footer = "<table width='100%' style='border-top: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000000;'><tr>
<td width='33%' align='center'>
<div align='center'><span style='font-size:9pt;'>MCJ - Assessoria Hosp. & Inf. LTDA  Rua da Bahia, 570 - Conj. 902 - Centro - 30.160-010  Belo Horizonte-MG  Fone (31)3214-0600</a></span></div>
</td>
</table>";


$footerE = "<table width='100%' style='border-top: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000000;'><tr>
<td width='33%' align='center'>
<div align='center'><span style='font-size:9pt;'>MCJ - Assessoria Hosp. & Inf. LTDA  Rua da Bahia, 570 - Conj. 902 - Centro - 30.160-010  Belo Horizonte-MG  Fone (31)3214-0600</a></span></div>
</td>
</table>";


$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
$mpdf->SetHTMLFooter($footer);
$mpdf->SetHTMLFooter($footerE,'E');


$html = '
<h1>mPDF</h1>
<h2>Headers & Footers Method 2</h2>
<h3>Odd / Right page</h3>
<p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>
<pagebreak />
<h3>Even / Left page</h3>
<p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>
';

$mpdf->WriteHTML($lcString);

$mpdf->Output();
exit;
    
?>
