<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

//session_start();

date_default_timezone_set('America/Sao_Paulo');

include ("../../includes/classes/conecta.class.php");
include ("../../includes/classes/auth.class.php");
include ("../../includes/classes/dateOpers.class.php");
include ("../../includes/config.inc.php");
include ("../../includes/functions/funcoes.inc");

//ini_set('memory_limit', '-1');
	
	$conec = new conexao;
	$conec->conecta('MYSQL');
	
    $sql="SELECT a.data,a.banco,a.arquivo,b.nome FROM carne_lote a join usuarios b on b.codigo = a.usuario  where a.id = ".$_GET['lote']." ";
    $commit = mysqli_query($conec->con,$sql);
    $rowlote = mysqli_fetch_array($commit);
	$lcBorda = "";
	$lcString = '';

	// Inicio Dados Cabecalho	
	$lcBorda.="<table>";

		$lcBorda.="<tr>
		<td align='right'>Data/Hora:</TD>
		<td align='left'>".$rowlote['data']."</TD>
		<td align='right'>Banco:</TD>
		<td align='left'>".$rowlote['banco']."</TD>";

        $lcBorda.="<td align='right'>Usuário:</TD>
		<td align='left'>".$rowlote['nome']."</TD>";
		
		$lcBorda.="</tr>
		<tr>";
	
		$lcBorda.= "<td align='right'>Arquivo:</TD>
		<td align='left'>".$rowlote['arquivo']."</TD>";
		
		$lcBorda.= "<td align='right'>:</TD>
		<td align='left'></TD>";

		$lcBorda.="<td align='right'>:</TD>
		<td align='left'></TD>";
		
	$lcBorda.= "</tr>
	</table>";
	// Fim Dados Cabecalho

	$query = "SELECT a.idtitular, a.sacado, a.data_vencimento, a.valor_boleto, b.datacontrato
    FROM carne_remessaboleto a Join carne_contratos b on b.idtitular = a.idtitular where a.lote ='".$_GET['lote']."'";
              
      //print_r($query);
      //print_r($_POST);
      //break;
      
	// Cabeçaalho do regisrtos encontrados
	$lcString.= "<table style='font-family: serif; font-size: 9pt; color: #000088;' width='800' border='1' cellspacing='1' cellpadding='1'>
	<tr>
	<th scope='col' align='center'>Id Cliente</th>
	<th scope='col' align='center'>Nome do Cliente</th>
	<th scope='col' align='center'>Dt.Vencto</th>
	<th scope='col' align='center'>Valor</th>	
	<th scope='col' align='center'>Venc.Contrato</th>	
	</tr>";
       
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$geral = 0;
	$vlrtotal = 0;
	
	
	while($row = mysqli_fetch_array($resultado)){
		
		$dtvencto = str_replace('/','',substr(converte_datacomhora($row['data_vencimento']),0,10));
        $dtcontrato = str_replace('/','',substr(converte_datacomhora($row['datacontrato']),0,10));
		
		$nroreg = $row['idtitular'];
		
		$lcString.= "<tr>
		<td align='center'>".$nroreg."</TD>
		<td align='left'>".retira_acentos_UTF8($row['sacado'])."</TD>
		<td align='center'>".mask($dtvencto,'##/##/####')."</TD>
		<td align='center'>".number_format($row['valor_boleto'],2,",",".")."</TD>
		<td align='left'>".mask($dtcontrato,'##/##/####')."</TD>
		</tr>";
				
		$vlrtotal+=$row['valor_boleto'];
		

		$i++;		
		
	}
	
	$geral= $i;
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
    <td align='left'>Total Geral Valor Plano</td>
    <td align='right'>".number_format($vlrtotal,2,",",".")."</td>    
    </tr>
    
    </table>";
	
$date = date("d/m/Y g:i a");

$header = "<table width='100%' style='border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;'><tr>
<td width='33%'>".$date."</span></td>
<td width='33%' align='center'><img src='../../logo.png' width='126px' /></td>
<td width='33%' style='text-align: right;'><span style='font-weight: bold;'>Pag. <span style='font-size:11pt;'>{PAGENO}</span></td>
</tr>
</table>
<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 14pt; color: #000000;'><tr>
<td width='33%' align='center'>Relat&oacute;rio de Remessa Banco SICOOB</td>
</tr></table>";

//.$lcBorda."";

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
if(isset($_POST['gerarexecel']) && $_POST['gerarexecel'] == 2) {

	
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

	include("../../includes/mpdf/vendor/autoload.php");
	
	//$mpdf = new \Mpdf\Mpdf();
	$mpdf = new \Mpdf\Mpdf(['debug' => true]);
	$mpdf->WriteHTML($html);
	$mpdf->Output();
	exit;

}

?>
