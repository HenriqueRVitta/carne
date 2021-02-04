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
	print "<BODY onLoad='document.plano.idnome.focus();'bgcolor='".BODY_COLOR."'>";


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
	

	print "<BR><B><font size=4>"."Administra&ccedil;&atilde;o de Planos"."</font></B><BR>";

	print "<FORM name='plano' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	$PAGE = new paging("PRINCIPAL");
	$PAGE->setRegPerPage($_SESSION['s_page_size']);
	
	
	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

       	$query = "SELECT max(id) as id FROM carne_tipoplano ";
		$resultado = mysql_query($query) or die('ERRO NA EXECUÇÂO DA QUERY DE MAX ID! '.$query);
       	$maxid = mysql_fetch_array($resultado);
       	
       	$cond=0;
       	$query = "SELECT p.*,c.valor,c.valor_dependente FROM carne_tipoplano p left join carne_competenciaplano c on c.idplano = p.id";
		if (isset($_GET['cod'])) {
			$query.= " WHERE p.id = ".$_GET['cod']." ";
			$cond=1;
		}

		if ((isset($_POST['search'])) && !empty($_POST['search'])) {
			$query.= " where lower(p.descricao) like lower(('%".noHtml($_POST['search'])."%'))";
			$cond=2;
			
		}

		if($cond==0) {
			$query.=" Where p.unidade =".$_SESSION['s_local']." ORDER BY p.id desc";
		} else {
			$query.=" and p.unidade =".$_SESSION['s_local']." ORDER BY p.descricao";
		}

		$resultado = mysql_query($query) or die('ERRO NA QUERY !'.$query);
		$registros = mysql_num_rows($resultado);

	    $disabled = '';
	    $clasbutton = " class='button'";
		
		if (isset($_GET['LIMIT']))
		$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));
		
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

			print "<tr><td colspan='4'>";
			print "<B>".TRANS('FOUND')." <font color=red>".$PAGE->NUMBER_REGS."</font> ".TRANS('RECORDS_IN_SYSTEM').". ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</B></TD>";
			print "</tr>";
			//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
			print "<TR class='header'><td class='line' width='40%'>"."Nome do Plano"."</TD>"."<td class='line' width='30%' >"."Forma Pagto"."</TD>"."<td class='line' width='10%' >"."% Desconto"."</TD>"."<td class='line' width='10%' >"."Valor Plano"."</TD>"."<td class='line' width='20%' >"."Data Inclus&atilde;o"."</TD>"."<td class='line'>"."C&oacute;digo"."</TD>"."<td class='line'>"."Status"."</td>"."<td class='line' width='20%'>"."Data Inativo"."</td>".
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
				print "<td class='line'>".$row['formapagto']."</td>";
				print "<td class='line' align='right'>".$row['percdesc']."</td>";
				print "<td class='line' align='right'>".$row['valor']."</td>";
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

		print "<BR><b><font size=2 color='blue'>"."Inclus&atilde;o do Plano"."</b></font><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='text4' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($maxid['id']+1,6)."' readonly='true'></td>";
		print "<TD class='line'><a href='plano.php'><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome do Plano".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='nome' maxlength='45' id='idnome'></td>";
		print "</TR><TR>";		
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Forma de Pagamento".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='formapagto' id='idformapagto' onBlur='javascript:proximofocus(this.value,document.carenciainternacao);' >";  
		print "<option value='CARNE'>Carn&ecirc;</option>";  
		print "<option value='BOLETO BANCARIO'>Boleto Banc&aacute;rio</option>";  
		print "</select>";
  

		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Qtde Dias Car&ecirc;ncia Interna&ccedil;&atilde;o".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='carenciainternacao' maxlength='4' id='idcarenciainternacao' ><input type='checkbox' id='travainternacao' name='travainternacao'>
  		<label for='travainternacao'>Travar no Atendimento Interna&ccedil;&atilde;o</label></td>";
		
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Qtde Dias Car&ecirc;ncia Cirurgias".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='carenciacirurgia' maxlength='4' id='idcarenciacirurgia'><input type='checkbox' id='travacirurgia' name='travacirurgia'>
  		<label for='travacirurgia'>Travar quando procedimento Cirurgia</label></td>";
		
		print "</TR><TR>";

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Percentual de Desconto".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='percdesc' id='idpercdesc' onBlur='javascript:proximofocus(this.value,document.status);'>";  

		For ($i=0; $i<=100; $i++)
		{
			print "<option value='".$i."'>".$i."</option>";  
		}
		
		print "</select>";
		
		print "</TR><TR>";
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Status".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='status' id='idstatus' onBlur='javascript:proximofocus(this.value,document.datainativo);'>";  
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

	// Alteracao
	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<BR><b><font size=2 color='blue'>"."Edi&ccedil;&atilde;o Dados do Plano"."</b></font><BR>";		

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='text4' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($row['id'],6)."' readonly='true' ></td>";
		//print "<TD class='line'><a href='plano.php'><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome do Plano".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='nome' maxlength='45' id='idnome' value='".$row['descricao']."'></td>";
		print "</TR><TR>";		

		if($row['formapagto']=='CARNE'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($row['formapagto']=='BOLETO BANCARIO'){ $selected2 = " selected"; } else { $selected2 = "";}
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Forma de Pagamento".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='formapagto' id='idformapagto' onBlur='javascript:proximofocus(this.value,document.carenciainternacao);'>";  
		print "<option value='CARNE'".$selected1.">Carn&ecirc;</option>";  
		print "<option value='BOLETO BANCARIO'".$selected2.">Boleto Banc&aacute;rio</option>";  
		print "</select>";
  
		print "</TR></TR>";		


				$travainternacaoCheCked = '';
				$travacirurgiaCheCked = '';
				
				if($row['travainternacao'] == 1){
					$travainternacaoCheCked = ' checked';
				}
				
				if($row['travacirurgia'] == 1){
					$travacirurgiaCheCked = ' checked';
				}
		
				
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Qtde Dias Car&ecirc;ncia Interna&ccedil;&atilde;o".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='carenciainternacao' maxlength='4' id='idcarenciainternacao' value='".$row['carenciainternacao']."'><input type='checkbox' id='travainternacao' name='travainternacao'.".$travainternacaoCheCked.">
  		<label for='travainternacao'>Travar no Atendimento Interna&ccedil;&atilde;o</label></td>";
		
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Qtde Dias Car&ecirc;ncia Cirurgias".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='carenciacirurgia' maxlength='4' id='idcarenciacirurgia' value='".$row['carenciacirurgia']."'><input type='checkbox' id='travacirurgia' name='travacirurgia'.".$travacirurgiaCheCked.">
  		<label for='travacirurgia'>Travar quando procedimento Cirurgia</label></td>";
		
		print "</TR><TR>";
		
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Percentual de Desconto".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='percdesc' id='idpercdesc' onBlur='javascript:proximofocus(this.value,document.status);'>";  

		For ($i=0; $i<=100; $i++)
		{

			if($row['percdesc']==$i){ $selected1 = " selected"; } else { $selected1 = "";}
			print "<option value='".$i."' $selected1>".$i."</option>";
			  
		}
		
		print "</select>";
		
		print "</TR><TR>";
		
		if($row['status']=='0'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($row['status']=='1'){ $selected2 = " selected"; } else { $selected2 = "";}
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Status".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='status' id='idstatus' onBlur='javascript:proximofocus(this.value,document.datainativo);'>";  
		print "<option value='0'".$selected1.">Ativo</option>";  
		print "<option value='1'".$selected2.">Inativo</option>";  
		print "</select>";  
		print "</TR></TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inativo".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datainativo' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddatainativo' onBlur='return doDateVenc(this.id,this.value, 4)' value='".databranco($row['inativo'])."'></td>";
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
		print "<TR>";
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><a style='font-family:Tahoma; color:#1E90FF; font-weight:bold; text-decoration:underline; font-size:10pt;' href='competenciaplano.php?action=incluir&cellStyle=true&cod=".$row['id']."&percentual=".$row['percdesc']."');'>Cadastrar Compet&ecirc;ncias</TD>";
		print "</TR>";		
		print "<TR><td> </td>";

		// Começa aqui a listar as competências do plano
		$querycomp = "select * from carne_competenciaplano where idplano =".$row['id']."";
		$resulcomp = mysql_query($querycomp) or die('ERRO NA QUERY !'.$querycomp);
		
		if (isset($_GET['LIMIT']))
		$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($querycomp,(isset($_GET['FULL'])?$_GET['FULL']:0));
		$PAGE->execSQL();
		
		if (mysql_num_rows($resulcomp) == 0)
		{
			echo "<tr><td colspan='4'>".mensagem('Nenhuma compet&ecirc;ncia cadastrada')."</td></tr>";
		}
		else
		{

			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr><td colspan='4'>";
			print "<B>".TRANS('FOUND')." <font color=red>".$PAGE->NUMBER_REGS."</font> ".TRANS('RECORDS_IN_SYSTEM').". ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</B></TD>";
			print "</tr>";
			//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
			print "<TR class='header'><td class='line' width='35%'>"." Compet&ecirc;ncia Inicial"."</TD>"."<td class='line' width='35%'>"."Compet&ecirc;ncia Final"."</TD>"."<td class='line' width='25%'>"."Valor Titular"."</TD>"."<td class='line' width='25%'>"."Valor Dependente"."</TD>".
				"<td class='line'>".TRANS('COL_EDIT')."</TD><td class='line'>".TRANS('COL_DEL')."</TD></tr>";
			
			$j=2;
			while ($rowcomp = mysql_fetch_array($PAGE->RESULT_SQL))
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

				$compet_ini = substr($rowcomp['compet_ini'],4,2).substr($rowcomp['compet_ini'],0,4);
				$compet_fim = substr($rowcomp['compet_fim'],4,3).substr($rowcomp['compet_fim'],0,4);
				
				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
				print "<td class='line'>".mask($compet_ini,'##/####')."</td>";
				print "<td class='line'>".mask($compet_fim,'##/####')."</td>";
				print "<td class='line'>".$rowcomp['valor']."</td>";
				print "<td class='line'>".$rowcomp['valor_dependente']."</td>";
				print "<td class='line'><a onClick=\"redirect('competenciaplano.php?action=alter&cod=".$row['id']."&cellStyle=true&idcomp=".$rowcomp['id']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";

				if(liberamenu('Excluir Tipo Plano')=='N'){
			    	print "<td disabled='disabled' class='line'><a onClick=''><img height='16' width='16' src='".ICONS_PATH."proibido.jpg' title='Exclus&atilde;o n&atilde;o permitida'></a></TD>";
			       } else {
					print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('MSG_DEL_REG')."','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$rowcomp['id']."&competencia=excluir')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
			       }
				
				print "</TR>";
			}
			print "<tr><td colspan='4'>";
			$PAGE->showOutputPages();
			print "</td></tr>";
			
			
		}		
	} else

		// Variáveis convertidas
		if(isset($_POST['codigo'])) {
				
				if(empty($_POST['datainativo'])) {
					$inativo = Fdate('01/01/1900');
				} else {
					$inativo = Fdate($_POST['datainativo']);
				}
				$registro = date("Y-m-d H:i:s");
				
				$carenciainternacao = 0;
				$carenciacirurgia = 0;
				
				if(!empty($_POST['carenciainternacao'])) {
					$carenciainternacao = $_POST['carenciainternacao'];
				}
				
				if(!empty($_POST['carenciacirurgia'])) {
					$carenciacirurgia = $_POST['carenciacirurgia'];
				}
				

			$travainternacao = 0;
			$travacirurgia = 0;
			
			if(isset($_POST['travainternacao'])) {
				if($_POST['travainternacao'] == 'on'){
					$travainternacao = 1;
				}
			}
	
			if(isset($_POST['travacirurgia'])) {
				if($_POST['travacirurgia'] == 'on'){
					$travacirurgia = 1;
				}
			}
							
								
		}

	// Excluindo registro com Delete		
	if (isset($_GET['action']) && $_GET['action'] == "excluir"){
		
			if(isset($_GET['competencia']) && $_GET['competencia']=='excluir') {

				$query2 = "DELETE FROM carne_competenciaplano WHERE id='".$_GET['cod']."'";
				$resultado2 = mysql_query($query2) or die('Erro na exclusão '.$query2);
				
			} else {

				// Exclui o Plano
				$query2 = "DELETE FROM carne_tipoplano WHERE id='".$_GET['cod']."'";
				$resultado2 = mysql_query($query2) or die('Erro na exclusão '.$query2);

				// Exclui todas competências do Plano
				if($resultado2>0) {
					$query2 = "DELETE FROM carne_competenciaplano WHERE idplano='".$_GET['cod']."'";
					$resultado2 = mysql_query($query2) or die('Erro na exclusão '.$query2);
				}
				
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

		$qryl = "SELECT * FROM carne_tipoplano WHERE descricao='".$_POST['nome']."' and status = 0";
		$resultado = mysql_query($qryl) or die('Erro na Query :'.$qryl);
		$linhas = mysql_num_rows($resultado);

		if ($linhas > 0)
		{
				$aviso = TRANS('MSG_EXIST_REG_CAD_SYSTEM');
				$erro = true;;
		}

		
		if (!$erro)
		{
			
			$lcnome = retira_acentos_UTF8($_POST['nome']);
			$lcnome = strtoupper($lcnome);

			$travainternacao = 0;
			$travacirurgia = 0;
			
			if(isset($_POST['travainternacao'])) {
				if($_POST['travainternacao'] == 'on'){
					$travainternacao = 1;
				}
			}
	
			if(isset($_POST['travacirurgia'])) {
				if($_POST['travacirurgia'] == 'on'){
					$travacirurgia = 1;
				}
			}
			
			
			$query = "INSERT INTO carne_tipoplano (descricao,formapagto,status,inativo,registro,unidade,percdesc,carenciainternacao,carenciacirurgia,travainternacao,travacirurgia)".
					" values ('".$lcnome."','".$_POST['formapagto']."','".$_POST['status']."','".$inativo."','".$registro."',".$_SESSION['s_local'].",".$_POST['percdesc'].",".$carenciainternacao.",".$carenciacirurgia.",".$travainternacao.",".$travacirurgia.")";
						
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
			echo "<script>mensagem('".$aviso."');</script>";
		} 
		
		echo "<script>redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else

	// Alterando o registro com UPDATE
	if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_ALTER')))) {	
					
		$lcnome = retira_acentos_UTF8($_POST['nome']);
		$lcnome = strtoupper($lcnome);
		
		$query2 = "UPDATE carne_tipoplano SET descricao='".$lcnome."',formapagto='".$_POST['formapagto']."',status='".$_POST['status']."', inativo='".$inativo."', registro='".$registro."', unidade=".$_SESSION['s_local'].", percdesc=".$_POST['percdesc'].", carenciainternacao=".$carenciainternacao.", carenciacirurgia=".$carenciacirurgia.", travainternacao=".$travainternacao.", travacirurgia=".$travacirurgia." WHERE id=".$_POST['codigo']." ";		
		
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
		if (ok) var ok = validaForm('idnome','','Nome do Dependente',1);		

		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
