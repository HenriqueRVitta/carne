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
	
	print "<BR><B><font size=4>"."Gerar Boleto para Cobran&ccedil;a na rede Banc&aacute;ria"."</font></B><BR>";

	print "<FORM name='relgerarboletobancario' method='POST' action='../boleto/boleto_bancoob.php' target='_blank' onSubmit=\"return valida()\">";	
			
		if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	
	print "<TABLE border='0' align='left' ".$cellStyle."  width='80%' bgcolor='".BODY_COLOR."'>";

		print "<BR><b><font size=2 color='blue'>"."Op&ccedil;&otilde;es de Filtro"."</b></font><BR>";
		print "<TD></TD>";
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Vencto".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datainicio' class='text4' style='width:100px; text-align:center;' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario1' onBlur='return doDateVenc(this.id,this.value, 4)' value='".date("d/m/Y")."'></td>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' >"."Data Vencto Final".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datafim' class='text4' style='width:100px; text-align:center;' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario2' onBlur='return doDateVenc(this.id,this.value, 4)' value='".date("d/m/Y")."'></td>";
		
		print "</TR><TR>";		

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome do Cliente".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='titular' id='idtitular'>";  
		print "<option value=''>"."Todos"."</option>";
			$sql="Select id,nometitular from carne_titular where situacao = 'ATIVO' order by nometitular";
					$commit = mysql_query($sql);
					$i=0;
		
					while($row = mysql_fetch_array($commit)){

						print "<option value=".$row['id'].">".$row['nometitular']."</option>";
						$i++;
					}
				print "</select>";

				
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo Cliente".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><input type='text' class='text4' name='nrocarne' id='idnrocarne' value=''></td>";

		print "</TR></TD>";
		print "<TR>";
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Banco Emissor".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='bancoemissor' id='bancoemissor' >";
		print "<option value=''></option>";  
		print "<option value='Sicoob'>Sicoob</option>";  
		print "<option value='Bradesco'>Bradesco</option>";  
		print "</select>";
		print "</TR><TR>";
		
		
						
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' value='EMITIR BOLETO' name='submit' >";

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
