<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Informática LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 07/01/2015 17:44

		* Módulo Carnê *

		Essa aplicação tem como objetivo geral controlar os Titulares e dependentes 
		que fazem “contribuição” mensal com a Unidade de Saúde (Hospital) para obter 
		um desconto em realização de atendimentos “Particular” ou até mesmo algum 
		diferencial em caso de internação SUS

*/
	session_start();
	
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/classes/paging.class.php");
	include ("../../includes/calendario.php");

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	print "<BODY onLoad='document.exportafinanceiro.calendario1.focus();' bgcolor='".BODY_COLOR."'>";

	
	print "<BR><B><font size=4>"."Exporta Pagamentos para o Financeiro"."</font></B><BR>";

	print "<FORM name='exportafinanceiro' method='POST' action='relexportafinanceiro.php' target='_blank' onSubmit=\"return valida()\">";
		
		if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	
	print "<TABLE border='0' align='left' ".$cellStyle."  width='80%' bgcolor='".BODY_COLOR."'>";


		print "<BR><b><font size=2 color='blue'>"."Filtros da Exporta&ccedil;&atilde;o"."</b></font><BR>";
		print "<TD></TD>";
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inicial".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datainicio' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario1' onBlur='return doDateVenc(this.id,this.value, 4)'></td>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Final".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datafim' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario2' onBlur='return doDateVenc(this.id,this.value, 4)'></td>";
		
		print "</TR><TR>";		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Local de Pagamento".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='localpagto' id='idlocalpagto' onBlur='return Dados(this.value)'>";  
				print "<option value=-1>"."Todos"."</option>";
					$sql="Select id,descricao from carne_localpagto where unidade = ".$_SESSION['s_local'];
					$commit = mysql_query($sql);
					$i=0;
					while($row = mysql_fetch_array($commit)){
						print "<option value=".$row['id'].">".$row['descricao']."</option>";
						$i++;
					}
				print "</select>";

		print "</TD>";
				
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Emitir para".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='processarpara' id='processarpara' onBlur='return Dados(this.value)'>";  
		print "<option value=1>Confer&ecirc;ncia</option>";
		print "<option value=2>Exportar para o Financeiro</option>";
		print "</select>";

		print "</TR></TD><TR>";
		
		
		print "</TR></TD><TR><TD></TD><TR>";

		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' value='PROCESSAR' name='submit' >";

		print "</TR></TR>";

	print "</table>";
	print "</form>";
	
?>

<script language="JavaScript">
/* Formatação para qualquer mascara */

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

function comboTiporel(Id, pStr) {

	d = document.getElementById(Id);
	
	// Relatório Analítico
	if(d.value == 1) {
		document.getElementById('idgrafico').value=1;
		document.getElementById('idseparacao').value=-1;
		document.getElementById('idseparacao').disabled=true;
		document.getElementById('idgrafico').disabled=true;
	}
			
	// Relatório Sintético
	if(d.value == 2) {
		document.getElementById('idgrafico').value=1;
		document.getElementById('idseparacao').value=4;
		document.getElementById('idseparacao').disabled=false;
		document.getElementById('idgrafico').disabled=true;		
	}

	// Relatório Gráfico
	if(d.value == 3) {
		document.getElementById('idseparacao').value=-1;
		document.getElementById('idseparacao').disabled=false;
		document.getElementById('idgrafico').disabled=false;		
		document.getElementById('idgrafico').value=2;
	}
	
}

function comboTiporel2(Id, pStr) {

	d = document.getElementById(Id);
	
	// Relatório Analítico
	if(document.getElementById('idtiporelatorio').value == 1) {
		d.disabled=true;
	}
			
	// Relatório Sintético
	if(document.getElementById('idtiporelatorio').value == 2) {
		d.disabled=false;
	}
	
}

</script>

<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('calendario1','','Data Inicial',1);
		var ok = validaForm('calendario2','','Data Final',1);
		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
