<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Informática LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 04/11/2019 10:38 GLPI 16792

		* Módulo Carnê *

		Opção de geração de NFSe conforme recebimentos bancario

*/
	session_start();
	
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/classes/paging.class.php");
	include ("../../includes/calendario.php");

	
	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	print "<BODY onLoad='document.relgerarboleto.calendario1.focus();' bgcolor='".BODY_COLOR."'>";
	
	print "<BR><B><font size=4>"."Gerar NFSe das Cobran&ccedil;a na rede Bancaria"."</font></B><BR>";

	print "<FORM name='nfsegerar' method='POST' action='nfsegerar.php' onSubmit=\"return valida()\">";
			
		if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";

		$dtinicial = date("d/m/Y"); 
		$dtfinal = date("d/m/Y");
		
		//$dtinicial = '28/10/2019';
		//$dtfinal = '28/10/2019';
		
		
	if(isset($_POST['datainicio'])) {

		$dtinicial = $_POST['datainicio'];
		$dtfinal = $_POST['datafim'];
		
	}

	print "<TABLE border='0' align='left' ".$cellStyle."  width='80%' bgcolor='".BODY_COLOR."'>";

		print "<BR><b><font size=2 color='blue'>"."Op&ccedil;&otilde;es de Filtro"."</b></font><BR>";
		print "<TD></TD>";
		print "<TR>";
		print "<TD width='10%' align='left' bgcolor='".TD_COLOR."'>"."Data Inicial".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datainicio' class='text4' style='width:100px; text-align:center;' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario1' onBlur='return doDateVenc(this.id,this.value, 4)' value='".$dtinicial."'></td>";
		print "<TD width='10%' align='left' bgcolor='".TD_COLOR."' >"."Data Final".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datafim' class='text4' style='width:100px; text-align:center;' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario2' onBlur='return doDateVenc(this.id,this.value, 4)' value='".$dtfinal."'></td>";
		print "<TR>";
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."NFSe/RPS".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='situacaorps' id='situacaorps' onChange='return OcultaEnviar(this.id,this.value)'>";
		print "<option value='0'></option>";  
		print "<option value='1'>RPS Enviados</option>";  
		print "<option value='2'>RPS Pendentes</option>";  
		print "</select>";
		
		print "</TR><TR>";		

		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' value='BUSCAR PAGAMENTOS' name='submit' id='buscarpagamentos'>";

		print "</TR></TR>";

	print "</table>";
	print "</form>";

	
if(isset($_POST['datainicio'])) {
	
	
//print "<FORM name='nfsegerar' method='POST' action='nfsenew/examples/Rps.php' onSubmit=\"return valida()\">";
print "<FORM name='nfsegerar' method='POST' action='nfsenew/examples/RecepcionarLoteRps.php' onSubmit=\"return verificaChecks(); return false;\">";


$dtinicial = Fdate($_POST['datainicio']);
$dtfinal = Fdate($_POST['datafim']);


	print "<INPUT type='text' name='inicio' class='text4' value='".$dtinicial."' hidden='hidden'>";
	print "<INPUT type='text' name='fim' class='text4' value='".$dtfinal."' hidden='hidden'>";

		
	$nValorMax = 0;
	if($_SESSION['percentualfiltro'] > 0){

		$sqlQuery = "SELECT sum(valor) as total FROM retornobanco
		where datapagto between '".$dtinicial."' and '".$dtfinal."' and nfse = 1";
		$commit=mysql_query($sqlQuery) or die('ERRO na query'.$sqlQuery);
		
		if (mysql_num_rows($commit) > 0){
			
			while($row = mysql_fetch_array($commit)){
				$nValorMax = (($row['total'] * $_SESSION['percentualfiltro']) / 100);
			}
		}
	}			

	$situacao = '';
	if(isset($_POST['situacaorps'])) {
		
		// RPS Enviados
		if($_POST['situacaorps'] == 1) {
			$situacao = " and notafiscal > ''";
		}
		
		// RPS Pendentes
		if($_POST['situacaorps'] == 2) {
			$situacao = " and notafiscal = ''";
		}
		
	} 
	
	$sqlQuery = "SELECT c.nometitular,g.cpfcnpj,g.datapagto,sum(g.valor) as valor,g.historico,g.notafiscal,g.codverificacao,g.nrotitulobanco,g.lote,g.protocolonfse,g.idretorno,l.dtemissao FROM retornobanco g
	join carne_titular c on c.id = g.idcliente
	left join carne_loterps l on l.id = g.lote
	where g.datapagto between '".$dtinicial."' and '".$dtfinal."' and nfse = 1 ".$situacao." group by g.cpfcnpj order by g.datapagto,c.nometitular";
	
	$commit=mysql_query($sqlQuery) or die('ERRO na query'.$sqlQuery);
	
	if (mysql_num_rows($commit) == 0){
	
		print "<div class='alert alert-danger'>Nenhum Registro encontrato..</div>";
	
	} else {
		
		if (!isset($_GET['cellStyle'])) {
			$cellStyle = "cellpadding='5' cellspacing='0'";
		} else
			$cellStyle = "cellpadding='5' cellspacing='1'";

		$Print = "<table width='100%'><tr><td>";
		$Print.= "<input type='submit' class='btn-primary' value='ENVIAR NFSe/RPS' name='submit' id='idsubmit'>";
		$Print.= "</td></tr></table>";
			
		$Print.= "<table id='pagtos' name='pagtos' border='0' align='left' ".$cellStyle."  width='100%' bgcolor='#87CEFA'>";

		
		$Print.="<TR class='header'><td class='line'>"."Sel."."</TD>"."<td class='line' width='10%'>"."CPF/CNPJ"."</TD>"."<td class='line' width='10%'>"."Contribuinte"."</TD>"."<td class='line'>"."NFSe/RPS"."</TD>"."<td class='line'>"."Nro Lote"."</TD>"."<td class='line'>"."Gerado em"."</TD>"."<td class='line'>"."Titulo Banco"."</TD>"."<td class='line'>"."Data Pagto"."</TD>"."<td class='line'>"."Vlr Pago"."</TD></tr>";
		
		$j=2;
		$nContador = 0;
		$nTotalGeral = 0;
		doubleval($nTotalGeral);
		
		
		while($row = mysql_fetch_array($commit)){
	
			if ($j % 2)
			{
				$trClass = "lin_par";
			}
			else
			{
				$trClass = "lin_impar";
			}
			$j++;
			$nContador++;
			$nTotalGeral+=$row['valor'];
			
			$datapagto = date('d/m/Y', strtotime($row['datapagto']));
			$dataRps = date('d-m-Y', strtotime($row['dtemissao']));
			if($dataRps <= '31-12-2000'){
				$dataRps = '';
			}
			
			$Checked = "checked='checked'";
			if($dataRps > '') {
				$Checked='';
			}
			$Print.="<tr class='".$trClass."' id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
			//$Print.="<td class='line' width='5%'><input type='checkbox' name='selecionado[]' ".$Checked." value='".$row['idretorno']."' id='selecionado'> </td>";
			$Print.="<td class='line' width='5%'><input type='checkbox' name='selecionado[]' value='".$row['idretorno']."' </td>";
			$Print.="<td class='line' width='10%'>".$row['cpfcnpj']."</td>";
			$Print.="<td class='line' width='50%'>".$row['nometitular']."</td>";
			$Print.="<td class='line' width='10%'>".$row['notafiscal']."</td>";
			$Print.="<td class='line' width='10%'>".$row['lote']."</td>";			
			$Print.="<td class='line' width='10%'>".$dataRps."</td>";			
			$Print.="<td class='line' width='10%'>".$row['nrotitulobanco']."</td>";			
			$Print.="<td class='line' width='10%'>".$datapagto."</td>";
			$Print.="<td class='line' width='10%'>".$row['valor']."</td>";
			$Print.="</tr>";
		
			if($nValorMax > 0){
				if($nTotalGeral >= $nValorMax){
					break;
				}
			}
		}

		$Print.="<tfoot>";
		$Print.="<tr>";
		$Print.="<td></td>";
		$Print.="<td>Qtde: ".$nContador."</td>";
		$Print.="<td></td>";
		$Print.="<td></td>";
		$Print.="<td></td>";
		$Print.="<td></td>";
		$Print.="<td></td>";
		$Print.="<td></td>";
		$Print.="<td>".$nTotalGeral."</td>";
		$Print.="</tr>";
		$Print.="</tfoot>";
  		
		$Print.="</Table>";
		
		print $Print;
	
	
	}

print "</form>";
	
}	
	

?>

<script language="JavaScript">
/* Formatação para qualquer mascara */

// Verifica se marcou algum CheckBox
function verificaChecks() {

	
    var aChk = document.getElementsByName("selecionado[]"); 

    var Selecionado = false;
    
    for (var i=0;i<aChk.length;i++){ 

        if (aChk[i].checked == true){ 

        	Selecionado = true;
        	exit;
        	
        	
        }  else {

        	Selecionado = false;

        }

    }
    
	if(Selecionado == false){
    	alert('Nenhum registro foi selecionado.');
    	return false;
	
	}
		
}


// Ocultar/desabilitar botao Enviar NFSe/RPS
function OcultaEnviar(Id, pStr) {

	d = document.getElementById(Id);	
	document.getElementById('idsubmit').style.display="none";
	//document.getElementById('buscarpagamentos').click();
	
}

function formatar(src, mask) 
{
var i = src.value.length;
var saida = mask.substring(0,1);
var texto = mask.substring(i)
if (texto.substring(0,1) != saida) 
{
src.value += texto.substring(0,1);
}
}

/* Valida Data */

var reDate4 = /^((0?[1-9]|[12]\d)\/(0?[1-9]|1[0-2])|30\/(0?[13-9]|1[0-2])|31\/(0?[13578]|1[02]))\/(19|20)?\d{2}$/;
var reDate = reDate4;

function doDateVenc(Id, pStr, pFmt){
d = document.getElementById(Id);
if (d.value != ""){ 
if (d.value.length < 10){
alert("Data incorreta!\nDigite corretamente a data: dd/mm/aaaa !");
d.value="";
d.focus(); 
return false;
}else{

eval("reDate = reDate" + pFmt);
if (reDate.test(pStr)) {
return false;
} else if (pStr != null && pStr != "") {
alert("ALERTA DE ERRO!!\n\n" + pStr + " Data incorreta, favor corrigir.");
d.value="";
d.focus(); 
return false;
}
}	
}else{
return false;
}
}
</script>

<script type="text/javascript">
<!--
	function valida(){
	
		var ok = validaForm('calendario1','','Data Inicial',1);
		var ok = validaForm('calendario2','','Data Final',1);
		var ok = validaForm('idtitular','','Nome do Cliente',1);

		var bancoemissor = document.getElementById("bancoemissor").value;
		if(bancoemissor == ''){
			alert('Banco Emissor deve ser informado.');
			ok = false;
		}

		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
