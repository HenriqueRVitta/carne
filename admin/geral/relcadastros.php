<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 08/02/2015 09:32

		* M�dulo Carn� *

		Relat�rio dos Cadastros do Sistema

*/
	session_start();
	
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/classes/paging.class.php");
	include ("../../includes/calendario.php");

	$conec = new conexao;
	$conec->conecta('MYSQL');
	
	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];
	
	$dtAtual = date('d/m/Y');
	

	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	print "<BODY onLoad='document.relcadastros.calendario1.focus();' bgcolor='".BODY_COLOR."'>";

	
	print "<BR><B><font size=4>"."Relat&oacute;rios do Cadastro de Titular e Dependentes"."</font></B><BR>";

	print "<FORM name='relpagamentos' method='POST' action='relcadastros2.php' target='_blank'>";
	//print "<FORM name='relpagamentos' method='POST' action='relcadastros2.php' target='_blank' onSubmit=\"return valida()\">";
		
		if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	
	print "<TABLE border='0' align='left' ".$cellStyle."  width='80%' bgcolor='".BODY_COLOR."'>";


		print "<BR><b><font size=2 color='blue'>"."Filtros do Relat&oacute;rio"."</b></font><BR>";
		print "<TD></TD>";
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inicial Cadastro".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datainicio' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario1' value = '01/01/1900' onBlur='return doDateVenc(this.id,this.value, 4)'></td>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Final Cadastro".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datafim' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario2' value = '".$dtAtual."' onBlur='return doDateVenc(this.id,this.value, 4)'></td>";
		
		print "</TR><TR>";		
				
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Tipo de Plano".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='plano' id='idplano'>";  
		print "<option value=-1>"."Todos"."</option>";
			$sql="Select id,descricao from carne_tipoplano where status = 0 and unidade =".$_SESSION['s_local']." order by id";
					$commit = mysqli_query($conec->con,$sql);
					$i=0;
		
					while($row = mysqli_fetch_array($commit)){

						print "<option value=".$row['id'].">".$row['descricao']."</option>";
						
						$i++;
					}
				print "</select>";
				
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Munic&iacute;pio".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='cidade' id='idcidade'>";  
		print "<option value=-1>"."Todos"."</option>";
			$sql="SELECT distinct cidade FROM carne_titular order by cidade";
					$commit = mysqli_query($conec->con,$sql);
					$i=0;
		
					while($row = mysqli_fetch_array($commit)){

						print "<option value=".$row['cidade'].">".$row['cidade']."</option>";
						$i++;
					}
				print "</select>";

		print "</TR><TR>";		
				
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Tipo de Relat&oacute;rio".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='tiporelatorio' id='idtiporelatorio' onClick='return comboTiporel(this.id,this.value)'>";  
		print "<option value=1>"."Anal&iacute;tico"."</option>";
		print "<option value=2>"."Sint&eacute;tico"."</option>";
		print "<option value=3>"."Gr&aacute;fico"."</option>";
		print "</select>";
				
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Ordem Relat&oacute;rio".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='ordem' id='idordem'>";  
		print "<option value=1>"."Nro Registro"."</option>";
		print "<option value=2 selected>"."Nome Titular"."</option>";
		print "<option value=3>"."Data Contrato"."</option>";
		print "<option value=4>"."Cidade"."</option>";
		print "<option value=5>".""."</option>";
		print "</select>";

		print "</TR></TD><TR>";

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Tipo de Gr&aacute;fico".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='grafico' id='idgrafico' disabled='true'>";  
		print "<option value=1>"."Nenhum"."</option>";
		print "<option value=1>"."Pizza"."</option>";
		print "<option value=2>"."Barra"."</option>";
		print "</select>";
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Separa&ccedil;&atilde;o".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='separacao' id='idseparacao' onFocus='return comboTiporel2(this.id,this.value)'>";  
		print "<option value=1>"."Nenhum"."</option>";
		print "<option value=2>"."Cidade"."</option>";
		print "<option value=3>"."Tipo de Plano"."</option>";
		print "</select>";

		print "</TR><TR>";
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Situa&ccedil;&atilde;o Cadastral".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='situacao' id='idsituacao'>";  
		print "<option value='1'>"."Ambos"."</option>";
		print "<option value='2' selected>"."Ativos"."</option>";
		print "<option value='3'>"."Inativos"."</option>";
		print "</select>";

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Grupo".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='grupo' id='idgrupo'>";  
		print "<option value=-1>"."Todos"."</option>";
			$sql="Select id,descricao from carne_grupo order by id";
					$commit = mysqli_query($conec->con,$sql);
					$i=0;
		
					while($row = mysqli_fetch_array($commit)){
						
						print "<option value=".$row['id'].">".$row['descricao']."</option>";
						$i++;
					}
				print "</select>";
		
		print "</TR><TR>";

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Gerar Arquivo EXCEL".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='gerarexecel' id='idgerarexecel'>";  
		print "<option value='1' Selected>N&atilde;o</option>";
		print "<option value='2'>Sim</option>";
		print "</select>";

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Titular/Dependente".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='imprimirambos' id='imprimirambos'>";  
		print "<option value=1 Selected>Ambos</option>";
		print "<option value=2>Somente Titular</option>";
		print "<option value=3>Somente Dependentes</option>";
		print "</select>";

		print "</TR><TR>";		
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' value='PROCESSAR' name='submit' >";

		print "</TR></TR>";

	print "</table>";
	print "</form>";
	
?>

<script language="JavaScript">
/* Formata��o para qualquer mascara */

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
	
	// Relat�rio Anal�tico
	if(d.value == 1) {
		document.getElementById('idgrafico').value=1;
		document.getElementById('idseparacao').value=-1;
		document.getElementById('idseparacao').disabled=true;
		document.getElementById('idgrafico').disabled=true;
		document.getElementById('idordem').disabled=false;
		
	}
			
	// Relat�rio Sint�tico
	if(d.value == 2) {
		document.getElementById('idgrafico').value=1;
		document.getElementById('idseparacao').value=3;
		document.getElementById('idordem').value=5;
		document.getElementById('idordem').disabled=true;
		document.getElementById('idseparacao').disabled=false;
		document.getElementById('idgrafico').disabled=true;
		
	}

	// Relat�rio Grafico
	if(d.value == 3) {
		document.getElementById('idgrafico').value=2;
		document.getElementById('idgrafico').disabled=false;
	}
	
	
}

function comboTiporel2(Id, pStr) {

	d = document.getElementById(Id);
	
	// Relat�rio Anal�tico
	if(document.getElementById('idtiporelatorio').value == 1) {
		d.disabled=true;
	}
			
	// Relat�rio Sint�tico
	if(document.getElementById('idtiporelatorio').value == 2) {
		d.disabled=false;
	}
	
}

</script>

<?php 
print "</body>";
print "</html>";
