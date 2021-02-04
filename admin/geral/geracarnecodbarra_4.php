<?php
/*      Copyright 2019 MCJ Assessoria Hospitalar e Informática LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 08/03/2019 14:18 GLPI 13397

		* Módulo Carnê *

		Impressao Carne com codigo de barras

*/
	
	// Impressão do Capa
	if(!empty($_POST['tipoimpressao']) && $_POST['tipoimpressao'] == 1) {
 	     echo "<script>redirect('geracapacarne.php');</script>";		
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

	date_default_timezone_set('America/Sao_Paulo');
	
	$dtinicial = Fdate($_POST['datainicio']);
	$dtfinal = Fdate($_POST['datafim']);
	$titular = $_POST['titular'];
	$datageracao = date('Y-m-d H:i:s');
	
	//$nrocarne = $_POST['nrocarne'];
	$pcwhere = "";
	
		if($titular<> -1 ) {
			$pcwhere.=" and t.id =".$titular;
		}

$mpdf = new mPDF(
             '',    // mode - default ''
             '',    // format - A4, for example, default ''
             0,     // font size - default 0
             '',    // default font family
             5,    // margin_left
             5,    // margin right
             5,     // margin top
             0,    // margin bottom
             6,     // margin header
             0,     // margin footer
             'L');  // L - landscape, P - portrait

//$mpdf->SetDisplayMode('fullpage');

$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins
             
$date = date("d/m/Y g:i a");


$header = "<table style='width: 680px; height: 340px;' border='1' cellspacing='1' cellpadding='1'><tbody><tr>";
$headerE = "<table style='width: 680px; height: 340px;' border='1' cellspacing='1' cellpadding='1'><tbody><tr>";

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
    $query = "select a.razao,a.endereco,a.numero,a.ddd,a.fone,a.bairro,a.cep,b.cidade,b.uf from cadastro_unidades a left join municipios b on b.codibge = a.codcidade where a.codigo = '".$_SESSION['s_local']."'";
    $resultado = mysql_query($query) or die('ERRO NA QUERY !'.$query);
	$rowConfg = mysql_fetch_array($resultado);
	$nomehosp = $rowConfg['razao'];
	$endereco = $rowConfg['endereco'].", ".$rowConfg['numero']." ".$rowConfg['bairro'];
	$cidade = $rowConfg['cidade']." ".$rowConfg['uf']." CEP:".mask($rowConfg['cep'],"#####-###")." Tel:(".$rowConfg['ddd'].") ".mask($rowConfg['fone'],"####-####");
	
	// Começa aqui a listar os registros
       $query = "SELECT t.id, t.nrocarne, t.nometitular, t.endereco, t.numero, t.bairro, t.cidade, t.uf, t.cpf, 
       			 t.telefoneres, t.celular, t.datainicio, c.nrocontrato, c.plano, c.diavencto, p.descricao, p.formapagto, p.percdesc, 
       			 cp.compet_ini, cp.compet_fim, cp.valor, cp.valor_dependente, t.valorplano, cp.vlrfixonegociado
				 FROM carne_titular t Join carne_contratos c
				 on c.idtitular = t.id
				 Join carne_tipoplano p
				 on p.id = c.plano
				 Join carne_competenciaplano cp
				 on cp.idplano = c.plano 
				 where t.situacao = 'ATIVO' ".$pcwhere."";
      
	// Cabeçalho do regisrtos encontrados
	$lcString= "<table style='width: 680px; height: 340px;' border='1' >";
	
       
    $resultado = mysql_query($query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$lntotalpg = 0.00;

	$registros = 0;
	
	$DiaIni = date( 'd', strtotime($dtinicial));
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
	
	while($row = mysql_fetch_array($resultado)){

	// Obtenho a quandidade de dependentes
    $querydependente = "select count(*) as qtdedependente from carne_dependente where situacao = 'ATIVO' and idtitular = '".$row['id']."'";
    $resultadodep = mysql_query($querydependente) or die('ERRO NA QUERY !'.$querydependente);
	$rowDependente = mysql_fetch_array($resultadodep);
	$qtdeDependente = $rowDependente['qtdedependente'];
		
	
	For ($x=$MesIni; $x<=$MesFim; $x++) {
		
		//$codbarra = strzero($row['id'],7).$row['compet_ini'];
		
		//$dtpagto = str_replace('/','',substr(converte_datacomhora($row['databaixa']),0,10));
		$vencimento = $DiaIni."/".$x."/".$AnoIni;
		

		//$mesextenso = retorna_mes(substr($row['compet_ini'],5,2));
		$mesextenso = retorna_mes($x);
		$ano = $AnoIni; //substr($row['compet_ini'],0,4);

		//$Valorcarne = (($VlrBaseCarne * $row['percdesc']) / 100);
		if($row['percdesc'] > 0){
			$Valorcarne = (($VlrBaseCarne * $row['percdesc']) / 100);
		} else {
			$Valorcarne = $VlrBaseCarne;
		}
		
		
		if($row['valor'] > 0){
			
			$Valorcarne = $row['valor'];
			
			if($qtdeDependente > 0 && $row['valor_dependente'] > 0) {
				$Valorcarne = number_format($row['valor'] + ($row['valor_dependente'] * $qtdeDependente),2,'.','');
			}
			
		}

		// Valor Negociado com o Cliente
		if($row['vlrfixonegociado'] == 2 && $row['valorplano'] > 0){
			$Valorcarne = $row['valorplano'];
		}
		
		$ValorImpreso = $Valorcarne;
		$ValorImpCodB = Round($Valorcarne);
		$telefone = mask($row['telefoneres'],"####-####");
		if(!empty($row['celular'])){
			$telefone = mask($row['celular'],"(##)#####-####");
		}
		
		$dataInicio = date( 'm', strtotime($row['datainicio']))."/".date( 'Y', strtotime($row['datainicio']));
		
		$codbarra = "9".strzero($row['id'],5).strzero($x,2).$ano.$ValorImpCodB;

		$lcString.= "<tr style='height: 23px;'>
		<td style='height: 23px; width: 159px; text-align: center; font-family: serif; font-size: 15pt; color: #000000;' colspan='2'>Canhoto Recibo</td>
		<td style='height: 69px; width: 126px;' colspan='5' rowspan='3'><p><img src='imagens/logo.png' width='220' height='150' alt='image' /></p></td>
		<td style='height: 23px; width: 576px; text-align: center; font-weight: bold; font-family: serif; font-size: 22pt; color: #000000;' colspan='3'>CARN&Ecirc; DE DOA&Ccedil;&Atilde;O No:</td>
		<td style='height: 23px; width: 250px; text-align: center; font-weight: bold; font-family: serif; font-size: 18pt; color: #000000;' colspan='4'>".$x."/".$row['id']."</td>
		</tr>
		<tr style='height: 33px;'>
		<td style='height: 46px; width: 93px; font-size: 12pt;' colspan='2' rowspan='2'>Nome: ".$row['nometitular']."<br><br> M&ecirc;s/Ano: ".$x."/".$AnoIni."</td>
		<td style='height: 33px; width: 376px; text-align: left; font-weight: bold; font-family: serif; font-size: 14pt; color: #000000;' colspan='3'>".$nomehosp."</td>
		<td style='height: 33px; width: 40px; text-align: center; font-weight: bold; font-family: tahoma; font-size: 14pt; color: #000000;' colspan='4' rowspan='3'>Ajude a Santa Casa a viver, ela vive por voc&ecirc;. <br> Sua contribui&ccedil;&atilde;o &eacute; necess&aacute;ria para o bom funcionamento do nosso hospital.</td>
		</tr>
		<tr style='height: 33px;'>
		<td style='height: 33px; width: 376px; text-align: left; font-family: serif; font-size: 11pt; color: #000000;' colspan='3'>".$endereco."s</td>
		</tr>
		<tr style='height: 33px;'>
		<td style='height: 33px; width: 93px; font-size: 18pt;' colspan='2'>Nro: ".$row['id']."</td>
		<td style='height: 33px; width: 226px; font-size: 12pt;' colspan='5'></td>
		<td style='height: 33px; width: 376px; font-size: 12pt;' colspan='3'>".$cidade."</td>
		</tr>
		<tr style='height: 33px;'>
		<td style='height: 33px; width: 159px; font-size: 12pt;' colspan='2'>Data:</td>
		<td style='height: 33px; width: 207px; font-size: 12pt;' colspan='7'>Vencimento: ".$vencimento."</td>
		<td style='height: 33px; width: 74px; font-size: 18pt;' colspan='5'>Valor: ".$ValorImpreso."</td>
		</tr>
		<tr style='height: 33px;'>
		<td style='height: 46px; width: 159px; font-size: 12pt;' colspan='2' rowspan='2'>Valor: ".$ValorImpreso."</td>
		<td style='height: 33px; width: 197px; font-size: 12pt;' colspan='12'>Nome: ".$row['nometitular']."</td>
		</tr>
		<tr style='height: 33px;'>
		<td style='height: 33px; width: 197px; font-size: 12pt;' colspan='7'>Endere&ccedil;o: ".$row['endereco']."</td>
		<td style='height: 33px; width: 74px; font-size: 12pt;' colspan='5'>Nro: ".$row['numero']."</td>
		</tr>
		<tr style='height: 33px;'>
		<td style='height: 46px; width: 159px; font-size: 12pt;' colspan='2' rowspan='2'>Assinatura:</td>
		<td style='height: 33px; width: 197px; font-size: 12pt;' colspan='7'>Bairro: ".$row['bairro']."</td>
		<td style='height: 33px; width: 74px; font-size: 12pt;' colspan='5'>Cidade: ".$row['cidade']."-".$row['uf']."</td>
		</tr>
		<tr style='height: 33px;'>
		<td style='height: 33px; width: 197px; font-size: 12pt;' colspan='7'>CPF: ".$row['cpf']."</td>
		<td style='height: 33px; width: 74px; font-size: 12pt;' colspan='5'>Telefone: ".$telefone."</td>
		</tr>
		<tr style='height: 33px;'>
		<td style='height: 33px; width: 159px; font-size: 12pt;' colspan='2'>INICIO: ".$dataInicio."</td>
		<td style='height: 33px; width: 197px; font-size: 12pt;' colspan='7'>Data:&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td style='height: 33px; width: 74px; font-size: 12pt;' colspan='5'>Ass:</td>
		</tr>
		<tr>
		<td colspan='1'></td>
		</tr>";
				
		if($AnoIni <> $AnoFim && $x==12) {
			$x = 0;
			$MesIni = 1;
			$MesFim = $MesFimPos;
			$AnoIni = $AnoFim;
		}
	}
		
		$i++;
		$registros+=$i;
		
	}

	
		if($registros == 0) {

		$lcString.= "</tr><tr>
		<td height='42' style='vertical-align: top; text-align: center; font-family: serif; font-size: 22pt; color: #000000;'>Nenhum registro encontrado<br>Verifique se esta ATIVO.</TD>
		</tr><tr>";
		
		} else {

			if($titular<> -1 ) {

			// Henrique 24/10/2019 13:23 GLPI 16571
			// Inserindo na Tabela carne_carnesgerados
			// Os Carnes que estão sendo gerados para o Contribuinte
			$query = "INSERT INTO carne_carnesgerados (idtitular,datainicio,datafim,usuario,datagerou,valor)".
					" values ('".$titular."','".$dtinicial."','".$dtfinal."',".$_SESSION['s_uid'].",'".$datageracao."',".$Valorcarne.")";
			$resultado = mysql_query($query) or die('Erro no Insert '.$query);
					
		
			}
	
		}
	
	$lcString.= "</table>";

		
$mpdf->ignore_invalid_utf8 = true;	
$mpdf->WriteHTML($lcString);

$mpdf->Output();
exit;

?>