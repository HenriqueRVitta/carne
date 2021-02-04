<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Informática LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 04/02/2015 14:13

		* Módulo Carnê *

		Opção de geração de Carnê/Boleto de cobrança

*/
	session_start();
	
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/classes/paging.class.php");
	include ("../../includes/calendario.php");

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	print "<BODY onLoad='document.relgerarboleto.calendario1.focus();' bgcolor='".BODY_COLOR."'>";
	
	print "<BR><B><font size=4>"."Auditor do Sistema"."</font></B><BR>";

	print "<FORM name='relauditoria' method='POST' action='relauditoria01.php' target='_blank' onSubmit=\"return valida()\">";	
			
		if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	
	print "<TABLE border='0' align='left' ".$cellStyle."  width='80%' bgcolor='".BODY_COLOR."'>";

		print "<BR><b><font size=2 color='blue'>"."Op&ccedil;&otilde;es de Filtro"."</b></font><BR>";
		print "<TD></TD>";
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inicial".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datainicio' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario1' onBlur='return doDateVenc(this.id,this.value, 4)'></td>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Final".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datafim' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario2' onBlur='return doDateVenc(this.id,this.value, 4)'></td>";
		
		print "</TR><TR>";		

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Usu&aacute;rio".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='usuario' id='idusuario'>";  
		print "<option value=-1>"."Todos"."</option>";
			$sql="Select codigo,nome from usuarios order by nome";
					$commit = mysql_query($sql);
					$i=0;
		
					while($row = mysql_fetch_array($commit)){

						print "<option value=".$row['codigo'].">".$row['nome']."</option>";
						$i++;
					}
				print "</select>";

				
		print "</TR></TD><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Refer&ecirc;ncia".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><input type='text' class='text3' name='referencia' id='idreferencia' value='' placeholder='informe nro documento ou nome'></td>";
		print "</TR>";
				
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
		var ok = validaForm('calendario1','','Data Inicial',1);
		var ok = validaForm('calendario2','','Data Final',1);
		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
