<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Informática LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 03/02/2015 13:00

		* Módulo Carnê *

		Relatório dos pagamentos registrados

*/
	 
	// Impressão do Capa
	if(!empty($_POST['tipoimpressao']) && $_POST['tipoimpressao'] == 1) {
 	     echo "<script>redirect('geracapacarneparcelamento.php');</script>";		
	}

$codBarra1 = "";
$codBarra2 = "";

function CodigoBarra($numero){
	
		$fino = 1;
		$largo = 3;
		$altura = 30;
		
		$barcodes[0] = '00110';
		$barcodes[1] = '10001';
		$barcodes[2] = '01001';
		$barcodes[3] = '11000';
		$barcodes[4] = '00101';
		$barcodes[5] = '10100';
		$barcodes[6] = '01100';
		$barcodes[7] = '00011';
		$barcodes[8] = '10010';
		$barcodes[9] = '01010';
		
		for($f1 = 9; $f1 >= 0; $f1--){
			for($f2 = 9; $f2 >= 0; $f2--){
				$f = ($f1*10)+$f2;
				$texto = '';
				for($i = 1; $i < 6; $i++){
					$texto .= substr($barcodes[$f1], ($i-1), 1).substr($barcodes[$f2] ,($i-1), 1);
				}
				$barcodes[$f] = $texto;
			}
		}
		
		$codBarra1 = '<img src="imagens/p.gif" width="'.$fino.'" height="'.$altura.'" border="0" />'.
		'<img src="imagens/b.gif" width="'.$fino.'" height="'.$altura.'" border="0" />'.
		'<img src="imagens/p.gif" width="'.$fino.'" height="'.$altura.'" border="0" />'.
		'<img src="imagens/b.gif" width="'.$fino.'" height="'.$altura.'" border="0" />'.
		'<img ';
				
		$texto = $numero;
		
		if((strlen($texto) % 2) <> 0){
			$texto = '0'.$texto;
		}
		
		while(strlen($texto) > 0){
			$i = round(substr($texto, 0, 2));
			$texto = substr($texto, strlen($texto)-(strlen($texto)-2), (strlen($texto)-2));
			
			if(isset($barcodes[$i])){
				$f = $barcodes[$i];
			}
			
			for($i = 1; $i < 11; $i+=2){
				if(substr($f, ($i-1), 1) == '0'){
  					$f1 = $fino ;
  				}else{
  					$f1 = $largo ;
  				}
  				
  				$codBarra1.='src="imagens/p.gif" width="'.$f1.'" height="'.$altura.'" border="0">'.
  				'<img ';
  				
  				if(substr($f, $i, 1) == '0'){
					$f2 = $fino ;
				}else{
					$f2 = $largo ;
				}
				
				$codBarra1.='src="imagens/b.gif" width="'.$f2.'" height="'.$altura.'" border="0">'.
				'<img ';
			}
		}
		$codBarra1.='src="imagens/p.gif" width="'.$largo.'" height="'.$altura.'" border="0" />'.
		'<img src="imagens/b.gif" width="'.$fino.'" height="'.$altura.'" border="0" />'.
		'<img src="imagens/p.gif" width="1" height="'.$altura.'" border="0" />';
		
		return $codBarra1;
	}
	
	session_start();

	include("../../includes/mpdf54/mpdf.php");	
	include ("../../includes/include_geral_III.php");

	$dtinicial = Fdate($_POST['datainicio']);
	$dtfinal = Fdate($_POST['datafim']);
	$nrocarne = $_POST['nrocarne'];
	$pcwhere = "";
	
		if(!empty($_POST['nrocarne'])) {
			$pcwhere.=" and t.nrocarne =".$nrocarne;
		}
		
//$mpdf=new mPDF('en-x','A4','','',32,25,47,47,10,10); 
$mpdf=new mPDF('en-x','A4','','',7,5,10,22,5,5);

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

$lcString  = '
<h1>mPDF</h1>
<h2>Headers & Footers Method 2</h2>
<h3>Odd / Right page</h3>
<p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>
<pagebreak />
<h3>Even / Left page</h3>
<p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>
';

	// Começa aqui a listar os registros
    $query = "select nome_hosp from configuracao limit 1";
    $resultado = mysql_query($query) or die('ERRO NA QUERY !'.$query);
	$rowConfg = mysql_fetch_array($resultado);
	$nomehosp = $rowConfg['nome_hosp'];

	// Começa aqui a listar os registros
       $query = "SELECT t.id, t.nrocarne, t.nometitular, c.nrocontrato, c.plano, c.diavencto, p.descricao, p.formapagto, p.percdesc, cp.compet_ini, cp.compet_fim, cp.valor
				 FROM carne_titular t Join carne_contratos c
				 on c.idtitular = t.id
				 Join carne_tipoplano p
				 on p.id = c.plano
				 Join carne_competenciaplano cp
				 on cp.idplano = c.plano 
				 where t.situacao in('ATIVO') ".$pcwhere."";
      
	// Cabeçalho do regisrtos encontrados
	$lcString= "<table width='800' border='0' cellspacing='1' cellpadding='1'>";
	
       
    $resultado = mysql_query($query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$lntotalpg = 0.00;

	$registros = 0;
	
	$MesIni = date( 'm', strtotime($dtinicial));
	$MesFim = date( 'm', strtotime($dtfinal));
	$MesFimPos = date( 'm', strtotime($dtfinal));
	$AnoIni = date( 'Y', strtotime($dtinicial));
	$AnoFim = date( 'Y', strtotime($dtfinal));
	$outroano = false;
	
	if($AnoIni <> $AnoFim && $MesFim <= $MesIni) {
		$MesFim = 12;
		$outroano = true;
	}
	
	$VlrBaseCarne=$_SESSION['vlrbasecarne'];
	
	$parcela = 1;

	$queryParcela = "delete from carne_parcelamento where nrocarne = ".$_POST['nrocarne']." and databaixa = '1900-01-01 00:00:00' and unidade = ".$_SESSION['s_local']."";
	$resultadoParcela = mysql_query($queryParcela) or die('ERRO NA EXCLUSAO CARNE_CANCELAMENTO!'.$queryParcela);
	
	while($row = mysql_fetch_array($resultado)){

	For ($x=$MesIni; $x<=$MesFim; $x++) {
		

		$mesextenso = retorna_mes($x);
		$ano = $AnoIni; //substr($row['compet_ini'],0,4);

		$Valorcarne = (($VlrBaseCarne * $row['percdesc']) / 100);
		$ValorImpreso = Round($Valorcarne).",00";
		$ValorImpCodB = Round($Valorcarne);
		
		if($parcela <= $_POST['parcelas']) {

			$ValorImpreso2 = Round($Valorcarne).".00";
			$ValorImpreso = Round($Valorcarne + $_POST['vlrpago']).",00";
			$ValorImpCodB = Round($Valorcarne + $_POST['vlrpago']);
			$registro = date("Y-m-d H:i:s");

			$mesanoparcela = $ano.strzero($x,2);
	       	$queryParcela = "insert into carne_parcelamento (idcliente,nrocarne,mesano,databaixa,localpagto,vlrmensal,vlrparcelado,taxa,unidade,usuario,dtregistro) ".
	       	" values (".$row['id'].",".$row['nrocarne'].",".$mesanoparcela.",'1900-01-01 00:00:00',0,".$ValorImpreso2.",".$_POST['vlrpago'].",".$row['percdesc'].",".$_SESSION['s_local'].",".$_SESSION['s_uid'].",'".$registro."')";
			$resultadoParcela = mysql_query($queryParcela) or die('ERRO NA QUERY CARNE_CANCELAMENTO!'.$queryParcela);
			
		}
		
		$codbarra = strzero($row['nrocarne'],4).strzero($x,2).$ano.$ValorImpCodB.'000999';
		
		$lcString.= "<tr>
		<td style='text-align: center; font-family: serif; font-size: 8pt; color: #000000;'>".$nomehosp."</TD>
		<td style='text-align: center; font-family: serif; font-size: 8pt; color: #000000;'>".$nomehosp."</TD>
		<td style='text-align: center; font-family: serif; font-size: 8pt; color: #000000;'>".$nomehosp."</TD>
		</tr><tr>
		<td height='50' style='text-align: center; font-family: serif; font-size: 12pt; color: #000000;'>CARN&Ecirc; DE DOA&Ccedil;&Atilde;O<BR>PARCELAMENTO</TD>
		<td height='50' style='text-align: center; font-family: serif; font-size: 12pt; color: #000000;'>CARN&Ecirc; DE DOA&Ccedil;&Atilde;O<BR>PARCELAMENTO</TD>
		<td height='50' style='text-align: center; font-family: serif; font-size: 12pt; color: #000000;'>CARN&Ecirc; DE DOA&Ccedil;&Atilde;O<BR>PARCELAMENTO</TD>
		</tr><tr>
		<td height='25' align='center'>M&ecirc;s:".$mesextenso."/".$ano."</TD>
		<td height='25' align='center'>M&ecirc;s:".$mesextenso."/".$ano."</TD>
		<td height='25' align='center'>M&ecirc;s:".$mesextenso."/".$ano."</TD>
		</tr><tr>
		<td height='25' align='center' style='text-align: center; font-family: serif; font-size: 13pt; color: #000000;'>Taxa:".number_format($row['percdesc'],0,",",".")."% Valor: ".$ValorImpreso."</TD>
		<td height='25' align='center' style='text-align: center; font-family: serif; font-size: 13pt; color: #000000;'>Taxa:".number_format($row['percdesc'],0,",",".")."% Valor: ".$ValorImpreso."</TD>
		<td height='25' align='center' style='text-align: center; font-family: serif; font-size: 13pt; color: #000000;'>Taxa:".number_format($row['percdesc'],0,",",".")."% Valor: ".$ValorImpreso."</TD>
		</tr><tr>
		<td height='25' style='text-align: center; font-weight: bold; font-family: serif; font-size: 12pt; color: #000000;'>Nro:".$row['nrocarne']."</TD>
		<td height='25' style='text-align: center; font-weight: bold; font-family: serif; font-size: 12pt; color: #000000;'>Nro:".$row['nrocarne']."</TD>
		<td height='25' style='text-align: center; font-weight: bold; font-family: serif; font-size: 12pt; color: #000000;'>Nro:".$row['nrocarne']."</TD>
		</tr><tr>		
		<td style='text-align: center; font-weight: bold; font-family: serif; font-size: 14pt; color: #000000;'>Doador</TD>
		<td style='text-align: center; font-weight: bold; font-family: serif; font-size: 14pt; color: #000000;'>Hospital</TD>
		<td style='text-align: center; font-weight: bold; font-family: serif; font-size: 14pt; color: #000000;'>Banco</TD>
		</tr><tr>
		<td align='center'>".CodigoBarra($codbarra)."</TD>
		<td align='center'>".CodigoBarra($codbarra)."</TD>
		<td align='center'>".CodigoBarra($codbarra)."</TD>
		</tr><tr>
		<td height='50' style='vertical-align: top; text-align: center; font-family: serif; font-size: 9pt; color: #000000;'>".$codbarra."</TD>
		<td height='50' style='vertical-align: top; text-align: center; font-family: serif; font-size: 9pt; color: #000000;'>".$codbarra."</TD>
		<td height='50' style='vertical-align: top; text-align: center; font-family: serif; font-size: 9pt; color: #000000;'>".$codbarra."</TD>
		</tr><tr>
		
		</tr>";
		
		if($AnoIni <> $AnoFim && $x==12) {
			$x = 0;
			$MesIni = 1;
			$MesFim = $MesFimPos;
			$AnoIni = $AnoFim;
		}

		$parcela++;
		
	}
		
		$i++;
		$registros+=$i;
		
		
	}

	
		if($registros == 0) {

		$lcString.= "</tr><tr>
		<td height='42' style='vertical-align: top; text-align: center; font-family: serif; font-size: 22pt; color: #000000;'>Nenhum registro encontrado<br>Verifique se esta ATIVO.</TD>
		</tr><tr>";
		
		}
	
	$lcString.= "</table>";

$mpdf->ignore_invalid_utf8 = true;	
$mpdf->WriteHTML($lcString);

$mpdf->Output();
exit;

?>