<?php
/*      Copyright 2019 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 31/07/2020 17:23 GLPI 19825

		* M�dulo Carn� *

		Impressao Carne com codigo de barras

*/
	
	// Impress�o do Capa
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

	$conec = new conexao;
	$conec->conecta('MYSQL');

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
             15,    // margin_left
             5,    // margin right
             5,     // margin top
             0,    // margin bottom
             6,     // margin header
             0,     // margin footer
             'L');  // L - landscape, P - portrait

             
//$mpdf->SetDisplayMode('fullpage');

// Comentado para que todas as paginas fiquem com as mesmas formatacoes
//$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins

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

	// Come�a aqui a listar os registros
    $query = "select a.razao,a.endereco,a.numero,a.ddd,a.fone,a.bairro,a.cep,b.cidade,b.uf from cadastro_unidades a left join municipios b on b.codibge = a.codcidade where a.codigo = '".$_SESSION['s_local']."'";
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$rowConfg = mysqli_fetch_array($resultado);
	$nomehosp = $rowConfg['razao'];
	$endereco = $rowConfg['endereco'].", ".$rowConfg['numero']." ".$rowConfg['bairro'];
	$cidade = $rowConfg['cidade']." ".$rowConfg['uf']." CEP:".mask($rowConfg['cep'],"#####-###")." Tel:(".$rowConfg['ddd'].") ".mask($rowConfg['fone'],"####-####");
	
	// Come�a aqui a listar os registros
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
      
	// Cabe�alho do regisrtos encontrados
	$lcString= "<table style='width: 100%; border-collapse: collapse;' cellspacing='1'>";
	
       
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
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
	
	while($row = mysqli_fetch_array($resultado)){

	// Obtenho a quandidade de dependentes
    $querydependente = "select count(*) as qtdedependente from carne_dependente where situacao = 'ATIVO' and idtitular = '".$row['id']."'";
    $resultadodep = mysqli_query($conec->con,$querydependente) or die('ERRO NA QUERY !'.$querydependente);
	$rowDependente = mysqli_fetch_array($resultadodep);
	$qtdeDependente = $rowDependente['qtdedependente'];
		
	
	For ($x=$MesIni; $x<=$MesFim; $x++) {
		
		//$codbarra = strzero($row['id'],7).$row['compet_ini'];
		$nromes = $x;
		if($x <= 9) {
			$nromes = str_pad($x,2,'0', STR_PAD_LEFT);
		}
		//$dtpagto = str_replace('/','',substr(converte_datacomhora($row['databaixa']),0,10));
		$vencimento = $DiaIni."/".$nromes."/".$AnoIni;
		
		if($row['diavencto'] > 0) {
			$DiaIni = str_pad($row['diavencto'],2,'0', STR_PAD_LEFT);
			$vencimento = $DiaIni."/".$nromes."/".$AnoIni;
		}
		

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

		//$lcString.= "<tr style='height: 23px;'>
		
		  $lcString.= "
			<tr>
			<td colspan='5'></td>
			</tr>
		  <tr>
		    <td style='width: 100px;' rowspan='2'><img src='imagens/logo.png' width='180' height='80' alt='image' /></td>
		    <td style='width: 39px; text-align: center; font-family: serif; font-size: 8pt; color: #000000;'>M&ecirc;s Ref:".$nromes."/".$AnoIni."</td>
		    <td style='width: 10px; border-right:1px dotted;' rowspan='2'></td>
		    <td style='width: 210px; padding-left: 30px;' rowspan='2'><img src='imagens/logo.png' width='180' height='80' alt='image' align='left'/></td>
		    <td style='text-align: center; font-family: serif; font-size: 12pt; color: #000000;'>M&ecirc;s Ref: ".$nromes."/".$AnoIni."</td>
		  </tr>
		  <tr>
		    <td style='text-align: center; font-family: serif; font-size: 8pt; color: #000000;' >Nro Contrato: ".$row['id']."</td>
		    <td style='text-align: center; font-family: serif; font-size: 12pt; color: #000000;'>Nro Contrato: ".$row['id']."</td>
		  </tr>
		  <tr>
		    <td colspan='2'>Nome: ".substr($row['nometitular'],0,20)."</td>
		    <td style='width: 10px; border-right:1px dotted;'></td>
		    <td style='height: 23px; width: 159px; text-align: left; font-family: serif; font-size: 11pt; color: #000000;' colspan='2'>&nbsp;&nbsp;Nome: ".substr($row['nometitular'],0,40)."</td>
		  </tr>
		  <tr>
		    <td colspan='2'>Valor: R$ ".$ValorImpreso."             Venc:   ".$vencimento."</td>
		    <td style='width: 10px; border-right:1px dotted;'></td>
		    <td>&nbsp;&nbsp;Valor: R$ ".$ValorImpreso."</td>
		    <td>    Venc: ".$vencimento."</td>
		  </tr>
		  <tr>
		    <td style='height: 43px;' colspan='2'>Ass:___________ Dt Pagto: ___/___/___</td>
		    <td style='width: 10px; border-right:1px dotted;'></td>
		    <td style='height: 43px;' colspan='2'>&nbsp;&nbsp;Ass:_______________________________ Dt Pagto: ___/___/___</td>
		    
		  </tr>
		<tr>
		<td style='border-bottom:1px dotted;' colspan='5'></td>
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
			// Os Carnes que est�o sendo gerados para o Contribuinte
			$query = "INSERT INTO carne_carnesgerados (idtitular,datainicio,datafim,usuario,datagerou,valor)".
					" values ('".$titular."','".$dtinicial."','".$dtfinal."',".$_SESSION['s_uid'].",'".$datageracao."',".$Valorcarne.")";
			$resultado = mysqli_query($conec->con,$query) or die('Erro no Insert '.$query);
					
		
			}
	
		}
	
	$lcString.= "</table>";

$mpdf->ignore_invalid_utf8 = true;
$mpdf->WriteHTML($lcString);

$mpdf->Output();
exit;

?>