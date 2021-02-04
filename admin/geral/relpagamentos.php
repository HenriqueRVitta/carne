<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 07/01/2015 17:44

		* M�dulo Carn� *

		Essa aplica��o tem como objetivo geral controlar os Titulares e dependentes 
		que fazem �contribui��o� mensal com a Unidade de Sa�de (Hospital) para obter 
		um desconto em realiza��o de atendimentos �Particular� ou at� mesmo algum 
		diferencial em caso de interna��o SUS

*/
	session_start();
	
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/classes/paging.class.php");
	include ("../../includes/calendario.php");

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	print "<BODY onLoad='document.relpagamentos.calendario1.focus();' bgcolor='".BODY_COLOR."'>";

	
	print "<BR><B><font size=4>"."Relat&oacute;rios de Pagamentos"."</font></B><BR>";

	print "<FORM name='relpagamentos' method='POST' action='relpagamentos2.php' target='_blank' onSubmit=\"return valida()\">";
	//print "<FORM name='relpagamentos' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";
		
		if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	
	print "<TABLE border='0' align='left' ".$cellStyle."  width='80%' bgcolor='".BODY_COLOR."'>";

		print "<BR><b><font size=2 color='blue'>"."Filtros do Relat&oacute;rio"."</b></font><BR>";
		print "<TD></TD>";
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inicial".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datainicio' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario1' onBlur='return doDateVenc(this.id,this.value, 4)'></td>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Final".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datafim' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario2' onBlur='return doDateVenc(this.id,this.value, 4)'></td>";
		
		print "</TR><TR>";		

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."M&ecirc;s e Ano".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='mesano' class='text4' onkeyup=\"maskIt(this,event,'##/####')\" id='idmesano' ></td>";
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome do Cliente:".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='titular' id='idtitular'>";  
		print "<option value=-1>"."Todos"."</option>";
			$sql="Select id,nometitular from carne_titular order by nometitular";
					$commit = mysql_query($sql);
					$i=0;
		
					while($row = mysql_fetch_array($commit)){

						print "<option value=".$row['id'].">".$row['nometitular']."</option>";
						$i++;
					}
				print "</select>";

		print "</TR></TD><TR>";
				
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Tipo de Plano".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='plano' id='idplano'>";  
		print "<option value=-1>"."Todos"."</option>";
			$sql="Select id,descricao from carne_tipoplano where status = 0 and unidade =".$_SESSION['s_local']." order by id";
					$commit = mysql_query($sql);
					$i=0;
		
					while($row = mysql_fetch_array($commit)){

						//if($row['id']==$rowA['plano']) { $selected = " selected"; } else { $selected = "";}
						//print "<option value=".$row['id'].$selected.">".$row['descricao']."</option>";
						
						print "<option value=".$row['id'].">".$row['descricao']."</option>";
						$i++;
					}
				print "</select>";

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

		print "</TR></TD><TR>";
				
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Tipo de Relat&oacute;rio".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='tiporelatorio' id='idtiporelatorio' onClick='return comboTiporel(this.id,this.value)'>";  
		print "<option value=1>"."Anal&iacute;tico"."</option>";
		print "<option value=2>"."Sint&eacute;tico"."</option>";
		print "<option value=3>"."Gr&aacute;fico"."</option>";
		print "</select>";

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Separa&ccedil;&atilde;o".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='separacao' id='idseparacao' onFocus='return comboTiporel2(this.id,this.value)'>";  
		print "<option value=-1>"."Nenhum"."</option>";
		print "<option value=1>"."Cidade"."</option>";
		print "<option value=2>"."M&ecirc;s/Ano"."</option>";
		print "<option value=3>"."Usu&aacute;rio"."</option>";
		print "<option value=4>"."Local Pagto"."</option>";
		print "</select>";
		
		print "</TR></TD><TR>";

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Tipo de Gr&aacute;fico".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='grafico' id='idgrafico' disabled='true'>";  
		print "<option value=1>"."Nenhum"."</option>";
		print "<option value=2>"."Pizza"."</option>";
		print "<option value=3>"."Barra"."</option>";
		print "</select>";
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Ordem Relat&oacute;rio".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='ordem' id='idordem'>";  
		print "<option value=1>"."Nome Titular"."</option>";
		print "<option value=2>"."Data Pagto"."</option>";
		print "<option value=3>"."Compet&ecirc;ncia"."</option>";
		print "<option value=4>"."Plano"."</option>";
		print "<option value=5>"."Local Pagto"."</option>";
		print "<option value=6 selected>"."Ordem Lancto"."</option>";
		print "<option value=7>"."Nro Carn&ecirc;"."</option>";
		print "</select>";

		print "</TR></TD><TR>";
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Usu&aacute;rio".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='usuario' id='idusuario'>";  
		print "<option value=-1>"."Todos"."</option>";
			$sql="SELECT codigo,nome FROM usuarios order by nome";
					$commit = mysql_query($sql);
					$i=0;
		
					while($row = mysql_fetch_array($commit)){

						print "<option value=".$row['codigo'].">".$row['nome']."</option>";
						$i++;
					}
				print "</select>";
						
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Munic&iacute;pio".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='cidade' id='idcidade'>";  
		print "<option value=-1>"."Todos"."</option>";
			$sql="SELECT distinct cidade FROM carne_titular order by cidade";
					$commit = mysql_query($sql);
					$i=0;
		
					while($row = mysql_fetch_array($commit)){

						print "<option value=".$row['cidade'].">".$row['cidade']."</option>";
						$i++;
					}
				print "</select>";

		print "</TR></TD><TR>";
						
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Situa&ccedil;&atilde;o Financeira".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='situacao' id='idsituacao' onChange='return comboInadimplente(this.id,this.value)'>";  
		print "<option value=1>"."Pagos"."</option>";
		print "<option value=2>"."Inadimplentes"."</option>";
		print "</select>";
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Outras Taxas".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='taxas' id='idtaxas' onChange='return ConsultaTaxas(this.value)'>";  
				print "<option value=-1>"."Selecione a Taxa"."</option>";
					$sql="Select id,descricao from carne_taxas where unidade = ".$_SESSION['s_local'];
					$commit = mysql_query($sql);
					$i=0;
					while($row = mysql_fetch_array($commit)){
						
						$selected = "";
						
						if(isset($_SESSION['idtaxas']) && $_SESSION['idtaxas'] == $row['id']) {
							$selected = " Selected";
						}
						print "<option value=".$row['id'].$selected.">".$row['descricao']."</option>";
						$i++;
					}
		print "</select>";
		print "<TR>";

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Inadimplentes a mais de".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='nromeses' id='idnromeses' disabled='true' onChange='return comboMeses(this.id,this.value)'>";  
		print "<option value='1' Selected>1 M&ecirc;s</option>";
		print "<option value='2'>2 Meses</option>";
		print "<option value='3'>3 Meses</option>";
		print "<option value='4'>4 Meses</option>";
		print "<option value='5'>5 Meses</option>";
		print "<option value='6'>6 Meses</option>";
		print "</select>";
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Gerar Arquivo EXCEL".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='gerarexecel' id='idgerarexecel'>";  
		print "<option value='1' Selected>N&atilde;o</option>";
		print "<option value='2'>Sim</option>";
		print "</select>";
		
		print "</TR><TR>";
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."INATIVAR".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='inativar' id='idinativar' disabled='true'>";  
		print "<option value=0 Selected></option>";
		print "<option value=1>Nao</option>";
		print "<option value=2>Sim</option>";
		print "</select>";
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Grupo".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='grupo' id='idgrupo'>";  
		print "<option value=-1>"."Todos"."</option>";
			$sql="Select id,descricao from carne_grupo order by id";
					$commit = mysql_query($sql);
					$i=0;
		
					while($row = mysql_fetch_array($commit)){
						
						print "<option value=".$row['id'].">".$row['descricao']."</option>";
						$i++;
					}
				print "</select>";
		
				
		print "</TD><TR>";

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
	}
			
	// Relat�rio Sint�tico
	if(d.value == 2) {
		document.getElementById('idgrafico').value=1;
		document.getElementById('idseparacao').value=4;
		document.getElementById('idseparacao').disabled=false;
		document.getElementById('idgrafico').disabled=true;	
		document.getElementById('idsituacao').value=1;
					
	}

	// Relat�rio Gr�fico
	if(d.value == 3) {
		document.getElementById('idseparacao').value=-1;
		document.getElementById('idseparacao').disabled=false;
		document.getElementById('idgrafico').disabled=false;		
		document.getElementById('idgrafico').value=2;
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

function comboInadimplente(Id, pStr) {

	d = document.getElementById('idnromeses');
	e = document.getElementById('idinativar');
	f = document.getElementById('idtiporelatorio');

	// Pagos
	if(document.getElementById('idsituacao').value == 1) {
		d.disabled=true;
		d.value=1;
		e.value=0;
	}
			
	// Inadimplentes
	if(document.getElementById('idsituacao').value == 2) {
		d.disabled=false;
		f.value=1;
	}
	
}

function comboMeses(Id, pStr) {

	d = document.getElementById('idinativar');

	// Pagos
	if(document.getElementById('idnromeses').value == 1) {
		d.disabled=true;
		d.value=0;
	}
			
	// Inadimplentes
	if(document.getElementById('idnromeses').value > 1) {
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
