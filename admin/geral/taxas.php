<?php
/*      Copyright 2018 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 05/07/2018 13:32

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
	print "<BODY onLoad='document.taxas.iddescricao.focus();'bgcolor='".BODY_COLOR."'>";


	$fecha = "";
	if (isset($_GET['popup'])) {
		$fecha = "window.close()";
	} else {
		$fecha = "history.back()";
	}

	if (isset($_POST['search'])){
		$search = $_POST['search'];
	} else
		$search = "";
	

	print "<BR><B><font size=4>"."Administra&ccedil;&atilde;o de Taxas"."</font></B><BR>";

	print "<FORM name='taxas' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	$PAGE = new paging_("PRINCIPAL");
	$PAGE->setRegPerPage($_SESSION['s_page_size']);
	
	
	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

       	$query = "SELECT max(id) as id FROM carne_taxas ";
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA EXECU��O DA QUERY DE MAX ID! '.$query);
       	$maxid = mysqli_fetch_array($resultado);
       	
       	$cond=0;
       	$query = "SELECT * FROM carne_taxas ";
		if (isset($_GET['cod'])) {
			$query.= " WHERE id = ".$_GET['cod']." ";
			$cond=1;
		}

		if ((isset($_POST['search'])) && !empty($_POST['search'])) {
			$query.= " where lower(descricao) like lower(('%".noHtml($_POST['search'])."%'))";
			$cond=2;
			
		}

		if($cond==0) {
			$query.=" Where unidade =".$_SESSION['s_local']." ORDER BY id desc";
		} else {
			$query.=" and unidade =".$_SESSION['s_local']." ORDER BY descricao";
		}

		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
		$registros = mysqli_num_rows($resultado);

	    $disabled = '';
	    $clasbutton = " class='button'";
		
		if (isset($_GET['LIMIT']))
		$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));
		
	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		$PAGE->execSQL();

	    if(liberamenu('Incluir Taxas')=="N"){
	    	$disabled = " disabled='disabled'";
	    	$clasbutton = " class='buttonDisabled'";
	    }
		
		print "<TR><TD><input type='button' $clasbutton id='idBtIncluir' $disabled value='".TRANS('BT_CAD')."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\"></TD></TR>";
		print "<tr><td colspan='4'>".
			"<input type='text' class='text3' name='search' id='idSearch' value='".$search."'>&nbsp;";
			print "<input type='submit' name='BT_SEARCH' class='button' value='".TRANS('BT_FILTER')."'>".
		"</td></tr>";
		
		if (mysqli_num_rows($resultado) == 0)
		{
			echo "<tr><td colspan='4'>".mensagem(TRANS('MSG_NOT_REG_CAD'))."</td></tr>";
		}
		else
		{

			print "<tr><td colspan='4'>";
			print "<B>".TRANS('FOUND')." <font color=red>".$PAGE->NUMBER_REGS."</font> ".TRANS('RECORDS_IN_SYSTEM').". ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</B></TD>";
			print "</tr>";
			//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
			print "<TR class='header'><td class='line' width='40%'>"."Nome da Taxa"."</TD>"."<td class='line' width='10%' >"."Valor Taxa"."</TD>"."<td class='line' width='20%' >"."Data Inclus&atilde;o"."</TD>"."<td class='line'>"."C&oacute;digo"."</TD>"."<td class='line'>"."Status"."</td>"."<td class='line' width='20%'>"."Data Inativo"."</td>".
				"<td class='line'>".TRANS('COL_EDIT')."</TD><td class='line'>".TRANS('COL_DEL')."</TD></tr>";
			
			$j=2;
			while ($row = mysqli_fetch_array($PAGE->RESULT_SQL))
			{
				if ($j % 2)
				{
					$trClass = "lin_par";
				}
				else
				{
					$trClass = "lin_impar";
				}
				$j++;

				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
				print "<td class='line'>".$row['descricao']."</td>";
				print "<td class='line'>".$row['valor']."</td>";
				$dtregistro = str_replace('/','',substr(converte_datacomhora($row['registro']),0,10));
				print "<td class='line'>".mask($dtregistro,'##/##/####')."</td>";
				print "<td class='line'>".$row['id']."</td>";
				print "<td class='line'>";
				if($row['status']==0) 
				{ echo "ATIVO"; 
				} else { echo "INATIVO"; 
				}
				print "<td class='line'>".databranco($row['inativo'])."</td>";
				print "</td>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['id']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";

			    if(liberamenu('Excluir Taxas')=="N"){
			    	print "<td disabled='disabled' class='line'><a onClick=''><img height='16' width='16' src='".ICONS_PATH."proibido.jpg' title='Exclus&atilde;o n&atilde;o permitida'></a></TD>";
		       } else {
					print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('MSG_DEL_REG')."','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['id']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
		       }
				
				
				print "</TR>";
			}
			print "<tr><td colspan='4'>";
			$PAGE->showOutputPages();
			print "</td></tr>";
			
			
		}

	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

		print "<BR><b><font size=2 color='blue'>"."Inclus&atilde;o de Taxas"."</b></font><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='text4' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($maxid['id']+1,6)."' readonly='true' ></td>";
		print "<TD class='line'><a href='taxas.php'><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome da Taxa".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='nome' maxlength='45' id='idnome'></td>";
		print "</TR><TR>";		
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Valor da Taxa".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='valor' maxlength='45' id='idvalor' value='0' onkeypress=\"return formatar_moeda(this,',','.',event);\"</td>";
		print "</TR><TR>";
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Status".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='status' id='idstatus' onBlur='javascript:proximofocus(this.value,document.plano);'>";  
		print "<option value='0'>Ativo</option>";  
		print "<option value='1'>Inativo</option>";  
		print "</select>";
  
		print "</TR></TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inativo".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datainativo' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddatainativo' onBlur='return doDateVenc(this.id,this.value, 4)'></td>";
		print "</TR><TR>";
		
		print "<TR> <TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='".TRANS('BT_CAD')."' name='submit'>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";


	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysqli_fetch_array($resultado);

		print "<BR><b><font size=2 color='blue'>"."Edi&ccedil;&atilde;o Dados da Taxa"."</b></font><BR>";		

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='text4' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($row['id'],6)."' readonly='true' ></td>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome da Taxa".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='nome' maxlength='45' id='idnome' value='".$row['descricao']."'></td>";
		print "</TR><TR>";		

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Valor da Taxa".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='valor' maxlength='45' id='idvalor' value='".$row['valor']."' onkeypress=\"return formatar_moeda(this,',','.',event);\"</td>";
		print "</TR><TR>";
		
		if($row['status']=='0'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($row['status']=='1'){ $selected2 = " selected"; } else { $selected2 = "";}
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Status".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='status' id='idstatus' onBlur='javascript:proximofocus(this.value,document.plano);'>";  
		print "<option value='0'".$selected1.">Ativo</option>";  
		print "<option value='1'".$selected2.">Inativo</option>";  
		print "</select>";  
		print "</TR></TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inativo".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datainativo' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddatainativo' onBlur='return doDateVenc(this.id,this.value, 4)' value='".databranco($row['inativo'])."'></td>";
		print "</TR><TR>";

	    if(liberamenu('Alterar Taxas')=="N"){
	    	$disabled = " disabled='disabled'";
	    	$clasbutton = " class='buttonDisabled'";
	    }
		
		
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' $clasbutton value='".TRANS('BT_ALTER')."' name='submit' $disabled>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";
		print "<TR>
		<td> </td>";

	} else

		// Vari�veis convertidas
		if(isset($_POST['codigo'])) {
				
				if(empty($_POST['datainativo'])) {
					$inativo = Fdate('01/01/1900');
				} else {
					$inativo = Fdate($_POST['datainativo']);
				}
				$registro = date("Y-m-d H:i:s");
		}

	// Excluindo registro com Delete		
	if (isset($_GET['action']) && $_GET['action'] == "excluir"){
		
			$resultado2 = 0;
			$erro=false;
			$qryl = "SELECT idtaxas FROM carne_pagamentos WHERE idtaxas='".$_GET['cod']."'";
			$resultado = mysqli_query($conec->con,$qryl) or die('Erro na Query :'.$qryl);
			$linhas = mysqli_num_rows($resultado);
	
			if ($linhas > 0)
			{
					$aviso = TRANS('ERR_DEL');
					$erro = true;;
			}
				
			if (!$erro) {

				// Exclui o Plano
				$query2 = "DELETE FROM carne_taxas WHERE id='".$_GET['cod']."'";
				$resultado2 = mysqli_query($conec->con,$query2) or die('Erro na exclus�o '.$query2);
				
			}

			if ($resultado2 == 0)
			{
					$aviso = TRANS('ERR_DEL');
			}
			else
			{
					$aviso = TRANS('OK_DEL');
			}
			
			if($aviso != TRANS('OK_DEL')) {
				print "<script>mensagem('".$aviso."');</script>";
			}

			print "<script>redirect('".$_SERVER['PHP_SELF']."');</script>";
		

	} else


	// Incluindo registro com INSERT
	if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_CAD')))) {	

		$erro=false;

		$qryl = "SELECT * FROM carne_taxas WHERE descricao='".$_POST['nome']."' and status = 0";
		$resultado = mysqli_query($conec->con,$qryl) or die('Erro na Query :'.$qryl);
		$linhas = mysqli_num_rows($resultado);

		if ($linhas > 0)
		{
				$aviso = TRANS('MSG_EXIST_REG_CAD_SYSTEM');
				$erro = true;;
		}

		
		if (!$erro)
		{
			
			$lcnome = retira_acentos_UTF8($_POST['nome']);
			$lcnome = strtoupper($lcnome);
			
			$query = "INSERT INTO carne_taxas (descricao,valor,status,inativo,registro,unidade)".
					" values ('".$lcnome."','".$_POST['valor']."','".$_POST['status']."','".$inativo."','".$registro."',".$_SESSION['s_local'].")";
						
			$resultado = mysqli_query($conec->con,$query) or die('Erro no Insert '.$query);
			if ($resultado == 0)
			{
				$aviso = TRANS('ERR_INSERT');
			}
			else
			{
				$aviso = TRANS('OK_INSERT');
			}
		}

		if($aviso != TRANS('OK_INSERT')) {
			echo "<script>mensagem('".$aviso."');</script>";
		} 
		
		echo "<script>redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else

	// Alterando o registro com UPDATE
	if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_ALTER')))) {	
					
		$lcnome = retira_acentos_UTF8($_POST['nome']);
		$lcnome = strtoupper($lcnome);
		
		$query2 = "UPDATE carne_taxas SET descricao='".$lcnome."',valor='".$_POST['valor']."',status='".$_POST['status']."', inativo='".$inativo."', registro='".$registro."', unidade=".$_SESSION['s_local']." WHERE id=".$_POST['codigo']." ";		
		
		$resultado2 = mysqli_query($conec->con,$query2) or die('Erro na query: '.$query2);
		
		if ($resultado2 == 0)
		{
			$aviso = TRANS('ERR_EDIT');
		}
		else
		{
			$aviso =  TRANS('OK_EDIT');
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
		if (ok) var ok = validaForm('idnome','','Nome do Dependente',1);		

		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
