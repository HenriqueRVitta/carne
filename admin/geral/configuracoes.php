<?php 
header ('Content-type: text/html; charset=ISO-8859-1');
/*      Copyright 2014 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 27/03/2014 12:00

		* M�dulo Carn� *

		Essa aplica��o tem como objetivo geral controlar os Titulares e dependentes 
		que fazem �contribui��o� mensal com a Unidade de Sa�de (Hospital) para obter 
		um desconto em realiza��o de atendimentos �Particular� ou at� mesmo algum 
		diferencial em caso de interna��o SUS

*/
	session_start();
	
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	include ("../../includes/classes/paging.class.php");
	
	$conec = new conexao;
	$conec->conecta('MYSQL');
	
	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	
	print "<BODY onLoad='document.dependente.idnome.focus();'bgcolor='".BODY_COLOR."'>";


	$fecha = "";
	if (isset($_GET['popup'])) {
		$fecha = "window.close()";
	} else {
		$fecha = "history.back()";
	}

	
    $disabled = '';
    $clasbutton = " class='button'";
	
	print "<BR><B><font size=4>"."Administra&ccedil;&atilde;o Configura&ccedil;&otilde;es do Sistema"."</font></B><BR>";

	print "<FORM name='configuracao' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";

		print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

       	$query = "SELECT tm_color_topo, tm_color_td, tm_color_body from styles";
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA EXECU��O DA QUERY DE MAX ID!');
       	$style = mysqli_fetch_array($resultado);
       	
       	$query = "SELECT prox_cartao_desc, prox_contrato, dtvencontrato, qtdepagtocaren, nromaxdepend, modelomenu, vlrbasecarne, contrato, ctacaixa, ctacontabil, centrocusto, historicopadrao, codcliente, modelocarne, modelocontrato, dtnascdepobriga, mesesematraso FROM config";
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
		$config = mysqli_fetch_array($resultado);
		
		if(empty($config['prox_cartao_desc'])) { $ready1 = ""; } else { $ready1 = "readonly=\'true\'"; } 
		if(empty($config['prox_contrato'])) { $ready2 = ""; } else { $ready2 = "readonly=true"; } 

		
		print "<BR><b><font size=2 color='blue'>"."Edi&ccedil;&atilde;o Par&acirc;metros de Configura&ccedil;&atilde;o"."</b></font><BR>";		
		print "<TR>";
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."Pr&oacute;ximo Nro Cart&atilde;o Desconto".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='prox_cartao_desc' maxlength='6' class='text4' id='idprox_cartao_desc' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($config['prox_cartao_desc'],6)."'".$ready1."></td>";
		print "</TR><TR>";		
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."Pr&oacute;ximo Nro Contrato/Carn&ecirc;".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='prox_contrato' maxlength='6' id='idprox_contrato' value='".strzero($config['prox_contrato'],6)."'".$ready2."></td>";
		print "</TR><TR>";		
		$dtcontrato = str_replace('/','',substr(converte_datacomhora($config['dtvencontrato']),0,10));
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."Data Padrao Vencto Contrato/Carn&ecirc;".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtvencontrato' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddtvencontrato' onBlur='return doDateVenc(this.id,this.value, 4)' value='".mask($dtcontrato,'##/##/####')."'></td>";
		print "</TR><TR>";
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."Qtde Pagto para Car&ecirc;ncia Contrato/Carn&ecirc;".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='qtdepagtocaren' maxlength='3' class='text4' id='idqtdepagtocaren' value='".$config['qtdepagtocaren']."'></td>";
		print "</TR><TR>";
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."Valor base de c&aacute;lculo do Carn&ecirc;".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='vlrbasecarne' maxlength='12' class='text4' id='idvlrbasecarne' value='".$config['vlrbasecarne']."' onkeypress=\"return formatar_moeda(this,',','.',event);\"</td>";
		print "</TR><TR>";
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."Nro m&aacute;ximo dependentes por Titular".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nromaxdepend' maxlength='3' class='text4' id='idnromaxdepend' value='".$config['nromaxdepend']."'></td>";
		print "</TR><TR>";
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."Cor de Fundo do Cabecalho".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='tm_color_topo' maxlength='45' class='text4' id='idtm_color_topo' value='".$style['tm_color_topo']."'></td>";
		print "</TR><TR>";
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."Cor de Fundo Nome dos Campos".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='tm_color_td' maxlength='45' class='text4' id='idtm_color_td' value='".$style['tm_color_td']."'></td>";
		print "</TR><TR>";
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."Cor de Fundo Corpo da Tela".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='tm_color_body' maxlength='45' class='text4' id='idtm_color_body' value='".$style['tm_color_body']."'></td>";
		print "</TR><TR>";
		
		if($config['modelomenu']=='1'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($config['modelomenu']=='2'){ $selected2 = " selected"; } else { $selected2 = "";}
		if($config['modelomenu']=='3'){ $selected3 = " selected"; } else { $selected3 = "";}
		if($config['modelomenu']=='4'){ $selected4 = " selected"; } else { $selected4 = "";}
		if($config['modelomenu']=='5'){ $selected5 = " selected"; } else { $selected5 = "";}
						
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Modelo de Menus".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='modelomenu' id='idmodelomenu'>";  
		print "<option value='1'".$selected1.">Modelo Padrao</option>";  
		print "<option value='2'".$selected2.">Modelo TreeView 1</option>";  
		print "<option value='3'".$selected3.">Modelo TreeView 2</option>";
		print "<option value='4'".$selected4.">Modelo TreeView 3</option>";
		print "<option value='5'".$selected5.">Modelo TreeView 4</option>";
		print "</select>";
		print "</TR><TR>";
		
		if($config['contrato']=='Sim'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($config['contrato']=='Nao'){ $selected2 = " selected"; } else { $selected2 = "";}
						
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Utiliza Contrato no controle de carn&ecirc;".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='contrato' id='idcontrato'>";  
		print "<option value='Sim'".$selected1.">Sim</option>";  
		print "<option value='Nao'".$selected2.">N&atilde;o</option>";  
		print "</select>";
		print "</TR><TR>";

		if($config['modelocontrato']=='1'){ $selected_1 = " selected"; } else { $selected_1 = "";}
		if($config['modelocontrato']=='2'){ $selected_2 = " selected"; } else { $selected_2 = "";}
		if($config['modelocontrato']=='3'){ $selected_3 = " selected"; } else { $selected_3 = "";}
		
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Modelo do Contrato".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='modelocontrato' id='idmodelocontrato'>";  
		print "<option value='1'".$selected_1.">Modelo 01</option>";  
		print "<option value='2'".$selected_2.">Modelo 02</option>";  
		print "<option value='3'".$selected_3.">Modelo 03</option>";  
		print "</select>";
		print "</TR><TR>";
		
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."Conta CAIXA do FINANCEIRO".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='ctacaixa' maxlength='45' class='text4' id='idctacaixa' value='".$config['ctacaixa']."'></td>";
		print "</TR><TR>";
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."Conta Cont&aacute;bil do FINANCEIRO".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='ctacontabil' maxlength='45' class='text4' id='idctacontabil' value='".$config['ctacontabil']."'></td>";
		print "</TR><TR>";
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."Centro de Custos do FINANCEIRO".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='centrocusto' maxlength='45' class='text4' id='idcentrocusto' value='".$config['centrocusto']."'></td>";
		print "</TR><TR>";
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."Hist&oacute;rico Padr&atilde;o do FINANCEIRO".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='historicopadrao' maxlength='45' class='text4' id='idhistoricopadrao' value='".$config['historicopadrao']."'></td>";
		print "</TR><TR>";
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."Codigo Cliente Padr&atilde;o do FINANCEIRO".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codcliente' maxlength='45' class='text4' id='idhistoricopadrao' value='".$config['codcliente']."'></td>";
		print "</TR><TR>";

		if($config['modelocarne']=='1'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($config['modelocarne']=='2'){ $selected2 = " selected"; } else { $selected2 = "";}
	    if($config['modelocarne']=='3'){ $selected3 = " selected"; } else { $selected3 = "";}
		if($config['modelocarne']=='4'){ $selected4 = " selected"; } else { $selected4 = "";}
		if($config['modelocarne']=='5'){ $selected5 = " selected"; } else { $selected5 = "";}
		if($config['modelocarne']=='6'){ $selected6 = " selected"; } else { $selected6 = "";}

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Modelo do Carn&ecirc".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='modelocarne' id='idmodelocarne'>";  
		print "<option value='1'".$selected1.">Modelo 01</option>";  
		print "<option value='2'".$selected2.">Modelo 02</option>";  
		print "<option value='3'".$selected3.">Modelo 03</option>";
		print "<option value='4'".$selected4.">Modelo 04</option>";
		print "<option value='5'".$selected5.">Modelo 05</option>";		
		print "<option value='6'".$selected6.">Modelo 06 - Apene</option>";
		print "</select>";
		print "</TR><TR>";

		if($config['dtnascdepobriga']=='1'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($config['dtnascdepobriga']=='2'){ $selected2 = " selected"; } else { $selected2 = "";}
						
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Data Nascimento do Depentende Obrigat&oacute;ria".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='dtnascdepobriga' id='iddtnascdepobriga'>";  
		print "<option value='1'".$selected1.">Sim</option>";  
		print "<option value='2'".$selected2.">Nao</option>";  
		print "</select>";
		print "</TR><TR>";

		if($config['mesesematraso']=='1'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($config['mesesematraso']=='2'){ $selected2 = " selected"; } else { $selected2 = "";}
		if($config['mesesematraso']=='3'){ $selected3 = " selected"; } else { $selected3 = "";}
		if($config['mesesematraso']=='4'){ $selected4 = " selected"; } else { $selected4 = "";}
		if($config['mesesematraso']=='5'){ $selected5 = " selected"; } else { $selected5 = "";}
		if($config['mesesematraso']=='6'){ $selected6 = " selected"; } else { $selected6 = "";}
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Qtde Meses Atraso para mostrar em Registro Pagamento".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='mesesematraso' id='idmesesematraso'>";  
		print "<option value='1'".$selected1.">01</option>";  
		print "<option value='2'".$selected2.">02</option>";  
		print "<option value='3'".$selected3.">03</option>";
		print "<option value='4'".$selected4.">04</option>";
		print "<option value='5'".$selected5.">05</option>";
		print "<option value='6'".$selected6.">06</option>";								
		print "</select>";
		print "</TR><TR>";

		if(liberamenu('Alterar Configuracoes')=="N"){
	    	$disabled = " disabled='disabled'";
	    	$clasbutton = " class='buttonDisabled'";
	    }
				
		print "<TD align='left' width='30%' bgcolor='".BODY_COLOR."'><input type='submit' $clasbutton id='idAlterar' $disabled value='".TRANS('BT_ALTER')."' name='submit'></TD>";
		print "<TD align='left' width='40%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		echo "<tr><td colspan='4' align='lefth'><br><a style='font-family:Tahoma; color:#1E90FF; font-weight:bold; text-decoration:underline; font-size:10pt;' onclick='redirect(\"cores.php\")'>Tabela de Cores</td></tr>";
		
		print "</TR>";

		$dtvencto = date("Y-m-d H:i:s");
	
	// Alterando o registro com UPDATE
	if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_ALTER')))) {	
					
		$query2 = "UPDATE styles SET tm_color_topo='".$_POST['tm_color_topo']."', tm_color_td='".$_POST['tm_color_td']."', tm_color_body='".$_POST['tm_color_body']."'";
		
		$resultado2 = mysqli_query($conec->con,$query2) or die('Erro na query: '.$query2);

		if ($resultado2 == 0)
		{
			$aviso = TRANS('ERR_EDIT');
		}
		else
		{
			$aviso =  TRANS('OK_EDIT');
			
			$query2 	= "UPDATE config SET prox_cartao_desc='".$_POST['prox_cartao_desc']."', prox_contrato='".$_POST['prox_contrato']."', dtvencontrato='".$dtvencto."', qtdepagtocaren=".$_POST['qtdepagtocaren'].", nromaxdepend='".$_POST['nromaxdepend']."', modelomenu='".$_POST['modelomenu']."', vlrbasecarne=".$_POST['vlrbasecarne'].", contrato='".$_POST['contrato']."', ctacaixa=".$_POST['ctacaixa'].", ctacontabil=".$_POST['ctacontabil'].", centrocusto=".$_POST['centrocusto'].", historicopadrao=".$_POST['historicopadrao'].", codcliente=".$_POST['codcliente'].", modelocarne=".$_POST['modelocarne'].", modelocontrato =".$_POST['modelocontrato'].", dtnascdepobriga=".$_POST['dtnascdepobriga'].", mesesematraso=".$_POST['mesesematraso']."";
			$resultado2 = mysqli_query($conec->con,$query2) or die('Erro na query: '.$query2);
			$_SESSION['s_modelomenu'] = $_POST['modelomenu'];
			$_SESSION['ctacaixa'] = $_POST['ctacaixa'];
			$_SESSION['ctacontabil'] = $_POST['ctacontabil'];
			$_SESSION['centrocusto'] = $_POST['centrocusto'];
			$_SESSION['historicopadrao'] = $_POST['historicopadrao'];
			$_SESSION['codcliente'] = $_POST['codcliente'];
			$_SESSION['modelocarne'] = $_POST['modelocarne'];
			$_SESSION['modelocontrato'] = $_POST['modelocontrato'];
			
		}

			if($aviso !=  TRANS('OK_EDIT')) {
				echo "<script>mensagem('".$aviso."');</script>";
				
			}


			
			echo "<script>redirect('".$_SERVER['PHP_SELF']."');</script>";
						
	}

	print "</table>";

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
</script>


<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idcodigo','','C�digo',1);
		if (ok) var ok = validaForm('iddtvencontrato','','Data Vento Contrato',1);
		if (ok) var ok = validaForm('idnromaxdepend','','Nro m�ximo dependentes',1);		

		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
