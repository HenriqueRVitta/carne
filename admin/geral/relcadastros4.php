<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 08/02/2015 09:55

		* M�dulo Carn� *

		Relat�rio dos Anal�tico do Cadastro de Titular

*/

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

ini_set('memory_limit', '-1');
	
date_default_timezone_set('America/Sao_Paulo');

include ("../../includes/classes/conecta.class.php");
include ("../../includes/classes/auth.class.php");
include ("../../includes/classes/dateOpers.class.php");
include ("../../includes/config.inc.php");
include ("../../includes/functions/funcoes.inc");

ob_clean();
ob_start();

$pcwhere = "";
$lcBorda = "";
$lcString = "";
$inativos = 0;

	$conec = new conexao;
	$conec->conecta('MYSQL');
	
	//$lnCompet = substr($_POST['mesano'],3,4).substr($_POST['mesano'],0,2);

	$dtinicial = '1900-01-01';	
	$dtfinal = date('Y-m-d');
	$situacao = 'ATIVOS/INATIVOS';

	if(!empty($_POST['datainicio'])) {
		$dtinicial = Fdate($_POST['datainicio']);
	}

	if(!empty($_POST['datafim'])) {
		$dtfinal = Fdate($_POST['datafim']);		
	}

	$plano = $_POST['plano'];
	$cidade	= $_POST['cidade'];


		if($plano<> -1 ) {
			$pcwhere.=" and p.plano =".$plano;
		}

		
		if($_POST['cidade'] <> -1) {
			$pcwhere.=" and c.cidade like '".trim($_POST['cidade'])."%'";
		}

		
		if($_POST['situacao']==2) {
			$pcwhere.=" and c.situacao = 'ATIVO'";
			$situacao = 'ATIVOS';
		}

		if($_POST['situacao']==3) {
			$pcwhere.=" and c.situacao = 'INATIVO'";
			$situacao = 'INATIVOS';
		}

		if($_POST['grupo']<>-1) {
			$pcwhere.=" and c.grupo =".$_POST['grupo']."";
		}
		
		if($_POST['tiporelatorio']==1) { $tiporel = "Analitico"; } else { $tiporel = "Sintetico"; } 


	// Inicio Dados Cabecalho	
	$lcBorda.="<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000000;'>";

	if(empty($_POST['datainicio'])) {
	
		$lcBorda.="<tr>
		<td align='right'>Data Inicial:</TD>
		<td align='left'>".$_POST['datainicio']."</TD>
		<td align='right'>Data Final:</TD>
		<td align='left'>".$_POST['datafim']."</TD>";
	
		if($_POST['cidade'] <> -1) {
		$lcBorda.="<td align='right'>Cidade:</TD>
		<td align='left'>".$_POST['cidade']."</TD>";
		}
		
		
		$lcBorda.="</tr>
		<tr>";
	}
		
	if(isset($_POST['plano'])) {

		$sql="SELECT descricao FROM carne_tipoplano where id = ".$_POST['plano']." ";
		$commit = mysqli_query($conec->con,$sql);
		$i=0;
			while($row = mysqli_fetch_array($commit)){

				$lcBorda.= "<td align='right'>Tipo de Plano:</TD>
				<td align='left'>".$row['descricao']."</TD>";
				
				$i++;
			}
		
	}
	
	
		$lcBorda.= "<td align='right'>Relat&oacute;rio:</TD>
		<td align='left'>".$tiporel."</TD>";
		
		$lcBorda.= "<td align='right'>Situacao Cadastral:</TD>
		<td align='left'>".$situacao."</TD>";

		if($_POST['grupo']<>-1) {
		$lcBorda.="<td align='right'>GRUPO:</TD>
		<td align='left'>".$_POST['grupo']."</TD>";
		}
		
	$lcBorda.= "</tr>
	</table>";
	// Fim Dados Cabecalho

	$pcordem = " order by c.cidade";
	
	$separacao = $_POST['separacao'];

	switch ( $separacao ){
	  case 1:
		$pcseparacao	= " group by c.cidade";
	    break;
	  case 2:
		$pcseparacao	= " group by c.cidade";
		$pcordem		= " order by c.cidade";
		break;
	  case 3:
		$pcseparacao	= " group by q.descricao";
		$pcordem		= " order by q.descricao";
	  	break;
  	  default:
		$pcseparacao	= " group by c.cidade";
	}
	

	// Come�a aqui a listar os registros
	$query = "SELECT c.cidade, q.descricao, count(*) as qtde FROM carne_titular c
	left Join carne_contratos p on p.idtitular = c.id
	left Join carne_tipoplano q on q.id = p.plano
	left Join carne_competenciaplano d on d.idplano = p.plano
	Where c.registro between '".$dtinicial."' and '".$dtfinal."' ".$pcwhere." ".$pcseparacao." ".$pcordem;
              
      //print_r($query);
      //print_r($_POST);
      //break;
      
	// Cabe�alho do regisrtos encontrados
	$lcString.= "<table style='font-family: serif; font-size: 9pt; color: #000088;' width='100%' border='1' cellspacing='1' cellpadding='1'>
	<tr>
	<th scope='col' align='center'>Descricao</th>
	<th scope='col' align='center'>Qtde</th>
	</tr>";
       
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$inativos = 0;
	$geral = 0;
	$vlrtotal = 0;
	
	
	while($row = mysqli_fetch_array($resultado)){
		
		$separa = $row['descricao'];
				
		$lcString.= "<tr>
		<td align='left'>".$separa."</TD>
		<td align='right'>".$row['qtde']."</TD>
		</tr>";
				
		$vlrtotal+=$row['qtde'];
		

		$i++;		
		
	}
	
	$geral= $i + $inativos;
	$lcString.= "</table>";
	
	//<p>&nbsp;</p>";
	
	// Resumo
	$lcString.= "<table width='100%' border='0'>
  	<tr>
    <th align='center'>RESUMO</th>
    </tr>
  	<tr>
    <td align='left'>Qtde Listados</td>
    <td align='right'>".$geral."</td>    
    </tr>
  	<tr>
    <td align='left'>Total Geral</td>
    <td align='right'>".number_format($vlrtotal,2,",",".")."</td>    
    </tr>
    
    </table>";

$date = date("d/m/Y H:i");

$header = "<table width='100%' style='border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;'><tr>
<td width='33%'>".$date."</span></td>
<td width='33%' align='center'><img src='../../logo.png' width='126px' /></td>
<td width='33%' style='text-align: right;'><span style='font-weight: bold;'>Pag. <span style='font-size:11pt;'>{PAGENO}</span></td>
</tr>
</table>
<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 14pt; color: #000000;'><tr>
<td width='33%' align='center'>Quantitativo Sint&eacute;tico dos Contribuintes ".$situacao."</td>
</tr>
</table>".$lcBorda."";

$headerE = "<table width='100%' style='border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;'><tr>
<td width='33%'>".$date."</span></td>
<td width='33%' align='center'><img src='../../logo.png' width='126px' /></td>
<td width='33%' style='text-align: right;'><span style='font-weight: bold;'>Pag. <span style='font-size:11pt;'>{PAGENO}</span></td>
</tr>
</table>
<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 14pt; color: #000000;'><tr>
<td width='33%' align='center'>Relat&oacute;rio dos Titulares de Carn&ecirc;</td>
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

// Se selecionado para Gerar EXCEL
if($_POST['gerarexecel'] == 2) {

	
$dadosXls = $header.$lcString.$footer;

// Definimos o nome do arquivo que ser� exportado  
$arquivo = "RelatorioCadastroCarne".$date.".xls";  
// Configura��es header para for�ar o download  
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$arquivo.'"');
header('Cache-Control: max-age=0');
// Se for o IE9, isso talvez seja necess�rio
header('Cache-Control: max-age=1');
       
// Envia o conte�do do arquivo  
echo $dadosXls;
	

} else {
	
$html = $header.$lcString.$footer;

//print_r($html);


include("../../includes/mpdf/vendor/autoload.php");

$mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
$mpdf->WriteHTML($html);
$mpdf->Output();

exit;

}

?>
