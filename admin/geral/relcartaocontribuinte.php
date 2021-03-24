<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 27/10/2020 14:43 GLPI 20676

		* M�dulo Carn� *

		Relat�rio dos Cadastros do Sistema

*/
	session_start();
	
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/classes/paging.class.php");
	include ("../../includes/calendario.php");
	$conec = new conexao;
	$conec->conecta('MYSQL');
	
    $query = "SELECT prox_cartao_desc, prox_contrato, dtvencontrato, qtdepagtocaren, nromaxdepend, modelomenu, vlrbasecarne, contrato, ctacaixa, ctacontabil, centrocusto, historicopadrao, codcliente, modelocarne, modelocontrato, dtnascdepobriga, mesesematraso FROM config";
	$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$config = mysqli_fetch_array($resultado);
	
	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];
	
	$dtAtualIni = '01/'.date('m/Y');
	$dtAtual = date('d/m/Y');
	
	if($config['modelocontrato']=='1'){ $selected_1 = " selected"; } else { $selected_1 = "";}
	if($config['modelocontrato']=='2'){ $selected_2 = " selected"; } else { $selected_2 = "";}
	if($config['modelocontrato']=='3'){ $selected_3 = " selected"; } else { $selected_3 = "";}
	
	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	print "<BODY onLoad='document.relcadastros.calendario1.focus();' bgcolor='".BODY_COLOR."'>";

	
	print "<BR><B><font size=4>"."Relat&oacute;rio do Cadastro para Emiss&atilde;o Cart&atilde;o do Contribuinte"."</font></B><BR>";

	print "<FORM name='relcartaocontribuinte' method='POST' action='relcartaocontribuinte.php'>";
		
		if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	
	print "<TABLE border='0' align='left' ".$cellStyle."  width='80%' bgcolor='".BODY_COLOR."'>";


		print "<BR><b><font size=2 color='blue'>"."Filtros do Relat&oacute;rio"."</b></font><BR>";
		print "<TD></TD>";
		print "<TR>";
		print "<TD width='25%' align='left' bgcolor='".TD_COLOR."'>"."Data Inicial Contrato".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datainicio' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario1' value = '".$dtAtualIni."' onBlur='return doDateVenc(this.id,this.value, 4)'></td>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Final Contrato".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datafim' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario2' value = '".$dtAtual."' onBlur='return doDateVenc(this.id,this.value, 4)'></td>";
		
		print "</TR><TR>";		
		
		print "<TD width='25%' align='left' bgcolor='".TD_COLOR."'>"."Situa&ccedil;&atilde;o Cadastral".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='situacao' id='idsituacao' disabled='disabled' title='Somente os Contribuintes com Status ATIVO'>";  
		print "<option value='1'>"."Ambos"."</option>";
		print "<option value='2' selected>"."Ativos"."</option>";
		print "<option value='3'>"."Inativos"."</option>";
		print "</select>";

		print "<TD width='25%' align='left' bgcolor='".TD_COLOR."'>"."Modelo Contrato/Cart&atilde;o".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='modelocontrato' id='idmodelocontrato' title='Modelo definido nas configura&ccedil;&otilde;es'>";  
		print "<option value='1'".$selected_1.">Modelo 01</option>";  
		print "<option value='2'".$selected_2.">Modelo 02</option>";  
		print "<option value='3'".$selected_3.">Modelo 03</option>";  
		print "</select>";

		print "</TR><TR>";
		
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' value='CARREGAR LISTA' name='submit' >";

		print "</TR></TR>";

	print "</table>";
	print "</form>";

	
if(isset($_POST['datainicio'])) {
	
	$action = "";
	
	if($_POST['modelocontrato'] == 1) {
		$action = "action='visualizacartao3.php target='_blank'";
	}

	if($_POST['modelocontrato'] == 2) {
		//$action = "action='visualizacartao??????.php target='_blank'";
	}

	// Modelo 03 - Itaguara
	if($_POST['modelocontrato'] == 3) {
		$action = "action='visualizacartao_3.php' target='_blank'";
	}
	
	
print "<FORM name='relcartaocontribuinte' method='POST' ".$action." onSubmit=\"return verificaChecks(); return false;\">";

	$dtinicial = Fdate($_POST['datainicio']);
	$dtfinal = Fdate($_POST['datafim']);

	print "<INPUT type='text' name='inicio' class='text4' value='".$dtinicial."' hidden='hidden'>";
	print "<INPUT type='text' name='fim' class='text4' value='".$dtfinal."' hidden='hidden'>";
	
	$sqlQuery = "select a.id, a.cpf, a.nometitular, a.cartaoemitido, a.cartaoemitidopor, b.datacontrato, b.diavencto,
				c.descricao, d.valor, d.valor_dependente
				from carne_titular a
				left Join carne_contratos b on b.idtitular = a.id
				left Join carne_tipoplano c on c.id = b.plano
				left Join carne_competenciaplano d on d.idplano = b.plano
				where b.datacontrato between '".$dtinicial."' and '".$dtfinal."' and a.situacao = 'ATIVO' order by a.nometitular";
	
	$commit=mysqli_query($conec->con,$sqlQuery) or die('ERRO na query'.$sqlQuery);
	
	if (mysqli_num_rows($commit) == 0){
	
		print "<div class='alert alert-danger'>Nenhum Registro encontrato..</div>";
	
	} else {
		
		if (!isset($_GET['cellStyle'])) {
			$cellStyle = "cellpadding='5' cellspacing='0'";
		} else
			$cellStyle = "cellpadding='5' cellspacing='1'";

		$Print = "<table width='100%'><tr><td>";
		$Print.= "<input type='submit' style='background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center;' value='Imprimir os Cart&otilde;es selecionados' name='submit' id='idsubmit'>";
		$Print.= "</td></tr></table>";
			
		$Print.= "<table id='pagtos' name='pagtos' border='0' align='left' ".$cellStyle."  width='100%' bgcolor='#87CEFA' style='font-size:12'>";

		
		$Print.="<TR class='header'><td class='line'>"."Sel."."</TD>"."</TD>"."<td class='line' width='30%'>"."Contribuinte"."</TD>"."</TD>"."<td class='line' width='5%'>"."Dia Vencto"."</TD>"."<td class='line' width='20%'>"."Plano"."</TD>"."<td class='line' width='15%'>"."In&iacute;cio Contrato"."</TD>"."<td class='line' width='15%'>"."Cart&atilde;o Emitido:"."</TD>"."<td class='line' width='15%'>Emitido Por:</TD>"."</tr>";
		
		$j=2;
		$nContador = 0;
		$nTotalGeral = 0;
		doubleval($nTotalGeral);
		
		
		while($row = mysqli_fetch_array($commit)){
	
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
			
			$datacontrato = date('d/m/Y', strtotime($row['datacontrato']));
			$cartaoemitido = date('d-m-Y', strtotime($row['cartaoemitido']));
			$cartaoemitidopor = $row['cartaoemitidopor'];
			
			if($cartaoemitido < '1990-01-01 00:00:00') {
				$cartaoemitido = "";				
			}
			
			$Checked = "checked='checked'";
			if(!empty($cartaoemitido)) {
				$Checked='';
			}
			$Print.="<tr class='".$trClass."' id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
			$Print.="<td class='line' width='5%'><input type='checkbox' name='selecionado[]' ".$Checked." value='".$row['id']."' </td>";
			$Print.="<td class='line' width='30%'>".$row['nometitular']."</td>";
			$Print.="<td class='line' width='5'>".$row['diavencto']."</td>";			
			$Print.="<td class='line' width='20%'>".$row['descricao']."</td>";			
			$Print.="<td class='line' width='15%'>".$datacontrato."</td>";			
			$Print.="<td class='line' width='15%'>".$cartaoemitido."</td>";
			$Print.="<td class='line' width='15%'>".$cartaoemitidopor."</td>";
			$Print.="</tr>";
		
		}

		$Print.="<tfoot>";
		$Print.="<tr>";
		$Print.="<td></td>";
		$Print.="<td>Qtde: ".$nContador."</td>";
		$Print.="<td></td>";
		$Print.="<td></td>";
		$Print.="<td></td>";
		$Print.="<td></td>";
		$Print.="</tr>";
		$Print.="</tr>";
		$Print.="</tfoot>";
  		
		$Print.="</Table>";
		
		print $Print;
	
	
	}

print "</form>";
	
}	
	
?>

<script language="JavaScript">
/* Formata��o para qualquer mascara */

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
    	alert('Nenhum Contribuinte foi selecionado.');
    	return false;
	
	}
		
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
		var ok = validaForm('calendario1111','','Data Inicial',1);
		var ok = validaForm('calendario2222','','Data Final',1);
		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
