<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Informática LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 08/02/2015 09:32

		* Módulo Carnê *

		Relatório dos Cadastros do Sistema
*/
	session_start();
	
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/classes/paging.class.php");
	include ("../../includes/calendario.php");

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	print "<BODY onLoad='document.relpagamentogrupo.calendario1.focus();' bgcolor='".BODY_COLOR."'>";

	
	print "<BR><B><font size=4>"."Registro de Pagamentos por GRUPO de Titular"."</font></B><BR>";

	print "<FORM name='relpagamentos' method='POST' action='relpagamentogrupo.php' target='_blank' onSubmit=\"return valida()\">";
		
		if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	
	print "<TABLE border='0' align='left' ".$cellStyle."  width='80%' bgcolor='".BODY_COLOR."'>";

		print "<BR><b><font size=2 color='blue'>"."Filtros do Relat&oacute;rio"."</b></font><BR>";
		print "<TD></TD>";
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Pagamento".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datapagto' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario1' onBlur='return doDateVenc(this.id,this.value, 4)' value=''></td>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."M&ecirc;s e Ano".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='mesano' class='text4' onkeyup=\"maskIt(this,event,'##/####')\" id='idmesano' onBlur='return MesAno(this.value,this.id)' value=''></td>";
		
		print "</TR><TR>";		
				
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Local de Pagamento".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='localpagto' id='idlocalpagto' onBlur='return Dados(this.value)'>";  
				print "<option value=-1>"."Selecione Local Pagamento"."</option>";
					$sql="Select id,descricao from carne_localpagto where unidade = ".$_SESSION['s_local'];
					$commit = mysql_query($sql);
					$i=0;
					while($row = mysql_fetch_array($commit)){
						
						$selected = "";
						
						if(isset($_SESSION['localpagto']) && $_SESSION['localpagto'] == $row['id']) {
							$selected = " Selected";
						}
						print "<option value=".$row['id'].$selected.">".$row['descricao']."</option>";
						$i++;
					}
				print "</select>";
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Grupo".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='grupo' id='idgrupo'>";  
		print "<option value=-1>"."Todos"."</option>";
			$sql="Select id,descricao from carne_grupo order by id";
					$commit = mysql_query($sql);
					$i=0;
		
					while($row = mysql_fetch_array($commit)){
						
						print "<option value=".$row['id'].$selected.">".$row['descricao']."</option>";
						$i++;
					}
				print "</select>";
				
		print "</TR><TR>";

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Excluir Lanctos".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='excluir' id='idexcluir'>";  
		print "<option value='1' selected>"."Nao"."</option>";
		print "<option value='2'>"."Sim"."</option>";
		print "</select>";
				
		print "</TR><TR>";
		
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


</script>

<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('calendario1','','Data Pagto',1);
		var ok = validaForm('idmesano','','Mes e Ano',1);
		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
