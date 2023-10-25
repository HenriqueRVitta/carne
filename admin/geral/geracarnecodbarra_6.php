<?php
/*      Copyright 2023 MTD

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 25/10/2023 11:03 GLPI 33238

		* Módulo Carnê *

		Emissao do Carnê

*/

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

	include ("../../includes/classes/conecta.class.php");
	include ("../../includes/classes/auth.class.php");
	include ("../../includes/classes/dateOpers.class.php");
	include ("../../includes/config.inc.php");
	include ("../../includes/functions/funcoes.inc");

	$conec = new conexao;
	$conec->conecta('MYSQL');

	date_default_timezone_set('America/Sao_Paulo');
	
	$dtinicial = Fdate($_POST['datainicio']);
	$dtfinal = Fdate($_POST['datafim']);
	$titular = $_POST['titular'];
	$datageracao = date('Y-m-d H:i:s');
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
    $query = "select a.razao,a.endereco,a.numero,a.ddd,a.fone,a.bairro,a.cep,b.cidade,b.uf from cadastro_unidades a left join municipios b on b.codibge = a.codcidade where a.codigo = '".$_SESSION['s_local']."'";
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$rowConfg = mysqli_fetch_array($resultado);
	$nomehosp = $rowConfg['razao'];
	$endereco = $rowConfg['endereco'].", ".$rowConfg['numero']." ".$rowConfg['bairro'];
	$cidade = $rowConfg['cidade']." ".$rowConfg['uf']." CEP:".mask($rowConfg['cep'],"#####-###")." Tel:(".$rowConfg['ddd'].") ".mask($rowConfg['fone'],"####-####");
	
	// Come�a aqui a listar os registros
       $query = "SELECT t.id, t.nrocarteira, t.nrocarne, t.nometitular, t.endereco, t.numero, t.bairro, t.cidade, t.uf, t.cpf, 
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
	$lcString= "<table style='width: 605px; height: 284px;' border='1' >";
	
       
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
	
	$lcString = "";

	while($row = mysqli_fetch_array($resultado)) {

	$lcString.= "<table style='width: 605px; height: 284px;' border='1' >";

	// Obtenho a quandidade de dependentes
    $querydependente = "select count(*) as qtdedependente from carne_dependente where situacao = 'ATIVO' and idtitular = '".$row['id']."'";
    $resultadodep = mysqli_query($conec->con,$querydependente) or die('ERRO NA QUERY !'.$querydependente);
	$rowDependente = mysqli_fetch_array($resultadodep);
	$qtdeDependente = $rowDependente['qtdedependente'];
		
	
	For ($x=$MesIni; $x<=$MesFim; $x++) {
		
		$nromes = $x;
		if($x <= 9) {
			$nromes = str_pad($x,2,'0', STR_PAD_LEFT);
		}

		$vencimento = $DiaIni."/".$x."/".$AnoIni;
		
		if($row['diavencto'] > 0) {
			$DiaIni = str_pad($row['diavencto'],2,'0', STR_PAD_LEFT);
			$vencimento = $DiaIni."/".$nromes."/".$AnoIni;
		}
        
        $dtVencto = $AnoIni."-".$nromes."-01";
        $UltimoDiaDoMes = date("t", strtotime($dtVencto));
        if($DiaIni > $UltimoDiaDoMes){
            $vencimento = $UltimoDiaDoMes."/".$nromes."/".$AnoIni;
        }

		$mesextenso = retorna_mes($x);
		$ano = $AnoIni;

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
		
		$codbarra = "9".strzero($row['nrocarteira'],5).strzero($x,2).$ano.$ValorImpCodB;

		$lcString.= "<tr style='height: 18px;'>
		<td style='height: 18px; width: 159px; text-align: center; font-family: serif; font-size: 15pt; color: #000000;' colspan='2'>Canhoto Recibo</td>
		<td style='height: 59px; width: 126px;' colspan='5' rowspan='3'><p><img src='imagens/logo.png' width='220' height='150' alt='image' /></p></td>
		<td style='height: 18x; width: 576px; text-align: center; font-weight: bold; font-family: serif; font-size: 20pt; color: #000000;' colspan='3'>CARN&Ecirc; DE MENSALIDADES</td>
		<td style='height: 18px; width: 250px; text-align: center; font-weight: bold; font-family: serif; font-size: 16pt; color: #000000;' colspan='4'>".str_pad($x,2,'0', STR_PAD_LEFT)."/".$row['nrocarteira']."</td>
		</tr>
		<tr style='height: 33px;'>
		<td style='height: 46px; width: 93px; font-size: 12pt;' colspan='2' rowspan='2'>Nome: ".$row['nometitular']."<br><br> M&ecirc;s/Ano: ".str_pad($x,2,'0', STR_PAD_LEFT)."/".$AnoIni."</td>
		<td style='height: 33px; width: 376px; text-align: center; font-weight: bold; font-family: serif; font-size: 12pt; color: #000000;' colspan='3'>".$nomehosp."</td>
		<td style='height: 33px; width: 40px; text-align: center; font-weight: bold; font-family: tahoma; font-size: 12pt; color: #000000;' colspan='4' rowspan='3'>Ajude a Apene a viver, ela vive por voc&ecirc;. <br> Sua contribui&ccedil;&atilde;o &eacute; necess&aacute;ria para o bom funcionamento da nossa Policlínica.</td>
		</tr>
		<tr style='height: 33px;'>
		<td style='height: 33px; width: 376px; text-align: left; font-family: serif; font-size: 11pt; color: #000000;' colspan='3'>".$endereco."s</td>
		</tr>
		<tr style='height: 33px;'>
		<td style='height: 33px; width: 93px; font-size: 18pt;' colspan='2'>Nro: ".$row['nrocarteira']."</td>
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

	
		$lcString.= "</table>";
		if(!empty($codigoinicio)) {
			$lcString.="<p style='page-break-before:always'></p>";
		}


		$i++;
		$registros+=$i;
		
	}

	
		if($registros == 0) {

			$lcString.= "<table style='width: 605px; height: 284px;' border='1' >";
			$lcString.= "</tr><tr>
			<td height='42' style='vertical-align: top; text-align: center; font-family: serif; font-size: 22pt; color: #000000;'>Nenhum registro encontrado<br>Verifique se esta ATIVO.</TD>
			</tr><tr>";
			$lcString.= "</table>";
		
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

include("../../includes/mpdf/vendor/autoload.php");

$mpdf = new \Mpdf\Mpdf(['orientation' => 'P']);
$mpdf->WriteHTML($lcString);
$mpdf->Output();


exit;

?>