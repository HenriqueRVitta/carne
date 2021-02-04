<?php
/*      Copyright 2014 MCJ Assessoria Hospitalar e Informática LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 27/03/2014 12:00

		* Módulo Carnê *

		Essa aplicação tem como objetivo geral controlar os Titulares e dependentes 
		que fazem “contribuição” mensal com a Unidade de Saúde (Hospital) para obter 
		um desconto em realização de atendimentos “Particular” ou até mesmo algum 
		diferencial em caso de internação SUS

*/
	session_start();
	
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	include ("../../includes/classes/paging.class.php");
	
	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	print "<BODY onLoad='document.competenciaplano.compet_ini.focus();'bgcolor='".BODY_COLOR."'>";


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
	

	print "<BR><B><font size=4>"."Administra&ccedil;&atilde;o das Compet&ecirc;ncias do Plano"."</font></B><BR>";

	print "<FORM name='competenciaplano' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	$PAGE = new paging("PRINCIPAL");
	$PAGE->setRegPerPage($_SESSION['s_page_size']);
	
	
	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

	$query = "";

	if(isset($_GET['cod']) or isset($_GET['idcomp'])) {
		
		$query = "SELECT max(id) as id FROM carne_competenciaplano ";
		$resultado = mysql_query($query) or die('ERRO NA EXECUÇÂO DA QUERY DE MAX ID! '.$query);
       	$maxid = mysql_fetch_array($resultado);
       	
       	$queryplano = "SELECT * from carne_tipoplano where id = ".$_GET['cod']."";
       	$plano = mysql_query($queryplano) or die('ERRO NA QUERY !'.$queryplano);
       	
       	$cond=0;
       	$query = "SELECT * FROM carne_competenciaplano";

       	if (isset($_GET['idcomp'])) {
			$query.= " WHERE id = ".$_GET['idcomp']." ";
			$cond=1;
		} else {
			
		       	if (isset($_GET['cod'])) {
					$query.= " WHERE id = ".$_GET['cod']." ";
					$cond=1;
				}

		}
		
		if ((isset($_POST['search'])) && !empty($_POST['search'])) {
			$query.= " where lower(descricao) like lower(('%".noHtml($_POST['search'])."%'))";
			$cond=2;
			
		}

		if($cond==0) {
			$query.=" Where unidade =".$_SESSION['s_local']." ORDER BY id desc";
		} else {
			$query.=" and unidade =".$_SESSION['s_local']." ORDER BY compet_ini";
		}

		$resultado = mysql_query($query) or die('ERRO NA QUERY !'.$query);
		$registros = mysql_num_rows($resultado);

	}

	
	if(empty($resultado) && empty($_POST)) {
		echo "<script>redirect('plano.php');</script>";
	}
		if (isset($_GET['LIMIT']))
		$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));

    $disabled = '';
    $clasbutton = " class='button'";
		
	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		$PAGE->execSQL();

	    if(liberamenu('Incluir Tipo Plano')=="N"){
	    	$disabled = " disabled='disabled'";
	    	$clasbutton = " class='buttonDisabled'";
	    }
		
		print "<TR><TD><input type='button' $clasbutton id='idBtIncluir' $disabled value='".TRANS('BT_CAD')."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\"></TD></TR>";
		print "<tr><td colspan='4'>".
			"<input type='text' class='text3' name='search' id='idSearch' value='".$search."'>&nbsp;";
			print "<input type='submit' name='BT_SEARCH' class='button' value='".TRANS('BT_FILTER')."'>".
		"</td></tr>";
		
		if (mysql_num_rows($resultado) == 0)
		{
			echo "<tr><td colspan='4'>".mensagem(TRANS('MSG_NOT_REG_CAD'))."</td></tr>";
		}
		else
		{

			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr><td colspan='4'>";
			print "<B>".TRANS('FOUND')." <font color=red>".$PAGE->NUMBER_REGS."</font> ".TRANS('RECORDS_IN_SYSTEM').". ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</B></TD>";
			print "</tr>";
			//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
			print "<TR class='header'><td class='line' width='60%'>"."Nome do Plano"."</TD>"."<td class='line' width='20%' >"."Data Inclus&atilde;o"."</TD>"."<td class='line'>"."C&oacute;digo"."</TD>"."<td class='line'>"."Status"."</td>"."<td class='line' width='20%'>"."Data Inativo"."</td>".
				"<td class='line'>".TRANS('COL_EDIT')."</TD><td class='line'>".TRANS('COL_DEL')."</TD></tr>";
			
			$j=2;
			while ($row = mysql_fetch_array($PAGE->RESULT_SQL))
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

				if(liberamenu('Excluir Tipo Plano')=="N"){
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

		$rowPlano = mysql_fetch_array($plano);
		
		print "<BR><b><font size=2 color='blue'>"."Inclus&atilde;o Compet&ecirc;ncias do Plano"."</b></font><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='text4' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($rowPlano['id'],6)."' disabled='true' >";
		print "<INPUT type='text' name='idplano' class='text4' id='idPlano' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($rowPlano['id'],6)."' hidden='true' ></td>";
		print "<TD class='line'><a href=\"javascript:".$fecha."\"><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome do Plano".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='nome' maxlength='45' id='idnome' value='".$rowPlano['descricao']."' disabled='true'></td>";
		print "</TR><TR>";		
		if($rowPlano['status']=='0'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($rowPlano['status']=='1'){ $selected2 = " selected"; } else { $selected2 = "";}
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Status".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='status' id='idstatus' onBlur='javascript:proximofocus(this.value,document.plano);' disabled='true'>";  
		print "<option value='0'".$selected1.">Ativo</option>";  
		print "<option value='1'".$selected2.">Inativo</option>";  
		print "</select>";  
		print "</TR></TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inativo".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datainativo' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddatainativo' onBlur='return doDateVenc(this.id,this.value, 4)' value='".databranco($rowPlano['inativo'])."' disabled='true'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Compet&ecirc;ncia Inicial".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='compet_ini' maxlength='7' id='idcompet_ini' onkeyup=\"maskIt(this,event,'##/####')\"</td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Compet&ecirc;ncia Final".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='compet_fim' maxlength='45' id='idcompet_fim' onkeyup=\"maskIt(this,event,'##/####')\"</td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Valor do Titular".":</TD>";

		$nPercentual = (($_SESSION['vlrbasecarne'] * $_GET['percentual']) / 100);
		
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='valor' maxlength='45' id='idvalor' value='".$nPercentual."' onkeypress=\"return formatar_moeda(this,',','.',event);\"</td>";
		print "</TR><TR>";

		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Valor do Dependente".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='valor_dependente' maxlength='45' id='valor_dependente' value='0' onkeypress=\"return formatar_moeda(this,',','.',event);\"</td>";
		print "</TR><TR>";
		
		print "</TR><TR>";		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Cobrar no Plano".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select3' name='vlrfixonegociado' id='vlrfixonegociado' >";  
		print "<option value='1' selected".">Valor do Titular e Dependente</option>";  
		print "<option value='2'>Valor Negociado</option>";  
		print "</select>";  
		print "</TR><TR>";		
		
		print "<TR> <TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='".TRANS('BT_CAD')."' name='submit'>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";


	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

	    $queryplano = "SELECT * from carne_tipoplano where id = ".$_GET['cod']."";
	    $plano = mysql_query($queryplano) or die('ERRO NA QUERY !'.$queryplano);

		$querycomp = "SELECT * from carne_competenciaplano where id = ".$_GET['idcomp']."";
	    $dadoscomp = mysql_query($querycomp) or die('ERRO NA QUERY !'.$queryplano);	    
		
		$rowPlano = mysql_fetch_array($plano);
		$rowcomp  = mysql_fetch_array($dadoscomp);
		
		print "<BR><b><font size=2 color='blue'>"."Edi&ccedil;&atilde;o Compet&ecirc;ncia do Plano"."</b></font><BR>";		

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='text4' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($rowPlano['id'],6)."' disabled='true' ></td>";
		print "<INPUT type='text' name='idplano' class='text4' id='idPlano' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($rowPlano['id'],6)."' hidden='true' ></td>";
		print "<INPUT type='text' name='idplanocomp' class='text4' id='idplanocomp' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($rowcomp['id'],6)."' hidden='true' ></td>";
		print "<TD class='line'><a href=\"javascript:".$fecha."\"><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome do Plano".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='nome' maxlength='45' id='idnome' value='".$rowPlano['descricao']."' disabled='true'></td>";
		print "</TR><TR>";		
		if($rowPlano['status']=='0'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($rowPlano['status']=='1'){ $selected2 = " selected"; } else { $selected2 = "";}
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Status".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='status' id='idstatus' onBlur='javascript:proximofocus(this.value,document.plano);' disabled='true'>";  
		print "<option value='0'".$selected1.">Ativo</option>";  
		print "<option value='1'".$selected2.">Inativo</option>";  
		print "</select>";  
		print "</TR></TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inativo".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datainativo' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddatainativo' onBlur='return doDateVenc(this.id,this.value, 4)' value='".databranco($rowPlano['inativo'])."' disabled='true'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Compet&ecirc;ncia Inicial".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='compet_ini' maxlength='7' id='idcompet_ini' onkeyup=\"maskIt(this,event,'##/####')\" value='".invertecomp($rowcomp['compet_ini'],1)."'</td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Compet&ecirc;ncia Final".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='compet_fim' maxlength='45' id='idcompet_fim' onkeyup=\"maskIt(this,event,'##/####')\" value='".invertecomp($rowcomp['compet_fim'],1)."'</td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Valor do Titular".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='valor' maxlength='45' id='idvalor' onkeypress=\"return formatar_moeda(this,',','.',event);\" value='".$rowcomp['valor']."'</td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Valor do Dependente".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='valor_dependente' maxlength='45' id='valor_dependente' onkeypress=\"return formatar_moeda(this,',','.',event);\" value='".$rowcomp['valor_dependente']."'</td>";
		print "</TR><TR>";

		print "</TR><TR>";		
		if($rowcomp['vlrfixonegociado']=='1'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($rowcomp['vlrfixonegociado']=='2'){ $selected2 = " selected"; } else { $selected2 = "";}
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Cobrar no Plano".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select3' name='vlrfixonegociado' id='vlrfixonegociado'>";  
		print "<option value='1'".$selected1.">Valor do Titular e Dependente</option>";  
		print "<option value='2'".$selected2.">Valor Negociado</option>";  
		print "</select>";  
		print "</TR><TR>";		

	    if(liberamenu('Alterar Tipo Plano')=="N"){
	    	$disabled = " disabled='disabled'";
	    	$clasbutton = " class='buttonDisabled'";
	    }
		
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' $clasbutton value='".TRANS('BT_ALTER')."' name='submit' $disabled>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";
		print "</TR>";

	} else

		// Variáveis convertidas
		if(isset($_POST['compet_ini'])) {
				
				$compet_ini = substr($_POST['compet_ini'],3,4).substr($_POST['compet_ini'],0,2);
				$compet_fim = substr($_POST['compet_fim'],3,4).substr($_POST['compet_fim'],0,2);
				$registro = date("Y-m-d H:i:s");
		}

	// Excluindo registro com Delete		
	if (isset($_GET['action']) && $_GET['action'] == "excluir"){
			$query2 = "DELETE FROM carne_competenciaplano WHERE id='".$_GET['cod']."'";
			$resultado2 = mysql_query($query2) or die('Erro na exclusão '.$query2);

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

		$qryl = "SELECT * FROM carne_competenciaplano WHERE compet_ini='".$compet_ini."' and compet_fim ='".$compet_fim."' and idplano=".$_POST['idplano']."";
		$resultado = mysql_query($qryl) or die('Erro na Query :'.$qryl);
		$linhas = mysql_num_rows($resultado);

		if ($linhas > 0)
		{
				$aviso = TRANS('MSG_EXIST_REG_CAD_SYSTEM');
				$erro = true;;
		}

		
		if (!$erro)
		{

						
			$query = "INSERT INTO carne_competenciaplano (idplano,compet_ini,compet_fim,valor,valor_dependente,unidade,registro,vlrfixonegociado)".
					" values ('".$_POST['idplano']."','".$compet_ini."','".$compet_fim."',".$_POST['valor'].",".$_POST['valor_dependente'].",".$_SESSION['s_local'].",'".$registro."',".$_POST['vlrfixonegociado'].")";
						
			$resultado = mysql_query($query) or die('Erro no Insert '.$query);
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
			echo "<script>mensagem('Já existe cadastro com essas informações');</script>";
		} 
		
		echo "<script>redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else

	// Alterando o registro com UPDATE
	if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_ALTER')))) {	

		
		$query2 = "UPDATE carne_competenciaplano SET idplano='".$_POST['idplano']."', compet_ini='".$compet_ini."', compet_fim='".$compet_fim."', valor=".$_POST['valor'].", valor_dependente=".$_POST['valor_dependente'].", unidade=".$_SESSION['s_local']." WHERE id=".$_POST['idplanocomp']." ";		
		
		$resultado2 = mysql_query($query2) or die('Erro na query: '.$query2);
		
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
		var ok = validaForm('idcodigo','','Cï¿½digo',1);
		if (ok) var ok = validaForm('idcompet_ini','','Competência Inicial',1);		
		if (ok) var ok = validaForm('idcompet_fim','','Competência Fim',1);
		if (ok) var ok = validaForm('idvalor','','Valor do Plano',1);
		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
