<?php
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

?>

<!--
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script> 
 -->
  
   	<!-- Utilize isso para evitar conflito entre scripts
	veja exemplo: http://blog.sahb.com.br/2009/04/conflito-entre-prototypejs-e-jqueryjs/ -->
	<script type="text/javascript" src="/carne/admin/geral/scripts/lib/jquery.js"></script>
    <script type="text/javascript">
        jQuery.noConflict();
    </script>

	<!-- Traz os dados referente ao CPF do paciente -->
	<script type="text/javascript" src="/carne/admin/geral/scripts/lib/idademaxima.js"></script>

<?php 
	
	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	print "<BODY onLoad='document.dependente.idnome.focus();'bgcolor='".BODY_COLOR."'>";
	//print "<BODY onLoad='Dados(document.dependente.ididade.value);'bgcolor='".BODY_COLOR."'>";

	
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
	

		$queryRR = "SELECT dtnascdepobriga FROM config";
		$resultadoRR = mysqli_query($conec->con,$queryRR) or die('ERRO NA EXECU��O DA QUERY DE MAX ID!');
       	$configuraRR = mysqli_fetch_array($resultadoRR);
		$dtnascdepobriga = $configuraRR['dtnascdepobriga'];
		
	print "<BR><B><font size=4>"."Administra&ccedil;&atilde;o de Dependente"."</font></B><BR>";

	print "<FORM name='dependente' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	$PAGE = new paging("PRINCIPAL");
	$PAGE->setRegPerPage($_SESSION['s_page_size']);
	
	
	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

       	$query = "SELECT max(id) as id FROM carne_dependente ";
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA EXECU��O DA QUERY DE MAX ID!');
       	$maxid = mysqli_fetch_array($resultado);
       	
       	$cond=0;
       	$query = "SELECT d.*, t.nometitular FROM carne_dependente d left Join carne_titular t on t.id = d.idtitular";
		if (isset($_GET['cod'])) {
			$query.= " WHERE d.id = ".$_GET['cod']." ";
			$cond=1;
		}

		if ((isset($_POST['search'])) && !empty($_POST['search'])) {
			$query.= " where lower(d.nome) like lower(('%".noHtml($_POST['search'])."%'))";
			$cond=2;
			
		}

		
		if($cond==0) {
			$query.=" order by d.nome";
		}
		
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
		$registros = mysqli_num_rows($resultado);
		
		if($registros==0) {
       		$query = "SELECT * FROM carne_dependente";
			$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
			$registros = mysqli_num_rows($resultado);
		}

		if (isset($_GET['LIMIT']))
		$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));

	// vari�vel que controla permiss�es dos bot�es para incluir, editar e excluir  do usu�rio
	$disabled = '';
	$clasbutton = " class='button'";
		
	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		$PAGE->execSQL();

	    if(liberamenu('Incluir Dependente')=="N"){
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

			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr><td colspan='4'>";
			print "<B>".TRANS('FOUND')." <font color=red>".$PAGE->NUMBER_REGS."</font> ".TRANS('RECORDS_IN_SYSTEM').". ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</B></TD>";
			print "</tr>";
			//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
			print "<TR class='header'><td class='line' width='50%'>"."Nome Dependente"."</TD>"."<td class='line' width='40%'>"."TITULAR"."</TD>"."<td class='line'>"."C&oacute;digo"."</TD>"."<td class='line'>"."Data Nasc"."</TD>".
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
				print "<td class='line'>".$row['nome']."</td>";
				print "<td class='line'>".$row['nometitular']."</td>";
				print "<td class='line'>".$row['id']."</td>";
				$dtnasc = str_replace('/','',substr(converte_datacomhora($row['datanasc']),0,10));
				print "<td class='line'>".mask($dtnasc,'##/##/####')."</td>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['id']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";

				if(liberamenu('Excluir Dependentes')=="N"){
					print "<td class='line'><a onClick=''><img height='16' width='16' src='".ICONS_PATH."proibido.jpg' title='Exclus&atilde;o n&atilde;o permitida'></a></TD>";
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

		print "<BR><b><font size=2 color='blue'>"."Inclus&atilde;o do Cliente"."</b></font><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='text4' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($maxid['id']+1,6)."' readonly='true' ></td>";
		print "<TD class='line'><a href='dependente.php'><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome Dependente".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='nome' maxlength='45' id='idnome'></td>";
		print "</TR><TR>";		

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro da Carteira".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nrocarteira' maxlength='45' class='text2' id='idnrocarteira' value=''></td>";
		print "</TR></TD>";
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Sexo".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='sexo' id='idsexo'>";  
		print "<option value='M'>Masculino</option>";  
		print "<option value='F'>Feminino</option>";  
		print "</select>";  
		print "</TR></TR>";		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Parentesco".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		
		print "<select class='select2' name='parentesco' id='idparentesco' onBlur='return Dados(this.value)'>";  
				print "<option value=-1>"."Selecione Tipo Dependente"."</option>";
					$sql="Select id,descricao from carne_tipodependente where unidade = ".$_SESSION['s_local'];
					$commit = mysqli_query($conec->con,$sql);
					$i=0;
					while($row = mysqli_fetch_array($commit)){
						print "<option value=".$row['id'].">".$row['descricao']."</option>";
						$i++;
					}
				print "</select>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Nascimento".":</TD>";
		if($dtnascdepobriga==1){
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtnasc' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddtnasc' onBlur='return doDateVenc(this.id,this.value, 4)'>";
		} else {
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtnasc' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddtnasc2' onBlur='return doDateVenc(this.id,this.value, 4)'>";
		}
		print "<INPUT type='text' class='text4' name='idade'  id='ididade' hidden='true'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Prontu&aacute;rio".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='prontuario' maxlength='7' class='text4' onkeyup=\"maskIt(this,event,'#######')\" id='idprontuario' value='0'>";
		print "<input type='button' value='Consulta' name='tipo1' class='minibutton2' onClick=\"javascript:popup_consulta('consultadependentes.php?popup=true&nome='+document.getElementById('idnome').value)\"></td>";
		print "</TR><TR>";

		
			print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Situa&ccedil;&atilde;o".":</TD>";
			print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select2' name='situacao' id='idsituacao'>";  
			print "<option value='ATIVO'>Ativo</option>";
			print "<option value='INATIVO'>Inativo</option>";
			print "</select>";
			print "</TD></TR>";
							
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inativo".":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtinativo' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddtinativo' onBlur='return doDateVenc(this.id,this.value, 4)' value=''></td>";
			print "</TR><TR>";
	
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."OBS".":</TD>";
			print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='obs' maxlength='200' id='idobs' value=''></td>";
			print "</TR><TR>";		
		
			
		print "<TR> <TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='".TRANS('BT_CAD')."' name='submit'>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";


	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysqli_fetch_array($resultado);

		print "<BR><b><font size=2 color='blue'>"."Edi&ccedil;&atilde;o Dados do Dependente"."</b></font><BR>";		

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='text4' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($row['id'],6)."' readonly='true'  ></td>";
		print "<TD class='line'><a href='dependente.php'><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome Dependente".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='nome' maxlength='45' id='idnome' value='".$row['nome']."'></td>";
		print "</TR><TR>";		

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro da Carteira".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nrocarteira' maxlength='45' class='text2' id='idnrocarteira' value='".$row['nrocarteira']."'></td>";
		print "</TR></TD>";
		
		if($row['sexo']=='M'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($row['sexo']=='F'){ $selected2 = " selected"; } else { $selected2 = "";}
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Sexo".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='sexo' id='idsexo'>";  
		print "<option value='M'".$selected1.">Masculino</option>";  
		print "<option value='F'".$selected2.">Feminino</option>";  
		print "</select>";  
		print "</TR></TR>";		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Parentesco".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		
		print "<select class='select2' name='parentesco' id='parentesco'>";  
		$sql="select id,nome,parentesco from carne_dependente where id =".$_GET['cod']."";
		$commit = mysqli_query($conec->con,$sql) or die ('Erro na Query '.$sql);
		$rowR = mysqli_fetch_array($commit);		
		print "<option value=-1>"."Selecione Tipo Dependente"."</option>";
				$sql="select id, descricao from carne_tipodependente where unidade =".$_SESSION['s_local']." order by id";
				$commit = mysqli_query($conec->con,$sql) or die ('Erro na Query '.$sql);;
							while($rowB = mysqli_fetch_array($commit)){
						print "<option value=".$rowB["id"]."";
                        			if ($rowB['id'] == $rowR['parentesco'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB['descricao']."</option>";
					}
			print "</select>";
		print "</TR><TR>";
		$dtnasc = str_replace('/','',substr(converte_datacomhora($row['datanasc']),0,10));
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Nascimento".":</TD>";
		if($dtnascdepobriga==1){
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtnasc' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddtnasc' onBlur='return doDateVenc(this.id,this.value, 4)' value='".mask($dtnasc,'##/##/####')."'></td>";
		} else {
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtnasc' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddtnasc2' onBlur='return doDateVenc(this.id,this.value, 4)' value='".mask($dtnasc,'##/##/####')."'></td>";
		}
		print "<INPUT type='text' class='text4' name='idade'  id='ididade' hidden='true'></td>";		
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Prontu&aacute;rio".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='prontuario' maxlength='7' class='text4' onkeyup=\"maskIt(this,event,'#######')\" id='idprontuario' value='".$row['prontuario']."'>";
		print "<input type='button' value='Consulta' name='tipo1' class='minibutton2' onClick=\"javascript:popup_consulta('consultadependentes.php?popup=true&nome='+document.getElementById('idnome').value)\"></td>";
		print "</TR><TR>";

		
		if($row['situacao'] == 'ATIVO') { $ativo = " selected"; } else { $ativo = ""; }
		if($row['situacao'] == 'INATIVO') { $inativo = " selected"; } else { $inativo = ""; }
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Situa&ccedil;&atilde;o".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='situacao' id='idsituacao'>";  
		print "<option value='ATIVO'".$ativo.">Ativo</option>";
		print "<option value='INATIVO'".$inativo.">Inativo</option>";
		print "</select>";
		print "</TD></TR>";
		
		print "<TR>";
		
		if($row['dtinativo']=='1900-01-01 00:00:00') {
			$dtinativo = '';
		} else { 
			$dtinativo = str_replace('/','',substr(converte_datacomhora($row['dtinativo']),0,10));
		}
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inativo".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtinativo' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddtinativo' onBlur='return doDateVenc(this.id,this.value, 4)' value='".mask($dtinativo,'##/##/####')."'></td>";
		print "</TR><TR>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."OBS".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='obs' maxlength='200' id='idobs' value='".$row['obs']."'></td>";
		print "</TR><TR>";
				
	    if(liberamenu('Alterar Dependentes')=="N"){
	    	$disabled = " disabled='disabled'";
	    	$clasbutton = " class='buttonDisabled'";
	    }
		
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' $clasbutton value='".TRANS('BT_ALTER')."' name='submit' $disabled>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";


	} else

		// Vari�veis convertidas
		if(isset($_POST['codigo'])) {
				
				$nascimento = Fdate($_POST['dtnasc']);
				$registro = date("Y-m-d H:i:s");
		}

	// Excluindo registro com Delete		
	if (isset($_GET['action']) && $_GET['action'] == "excluir"){
			$query2 = "DELETE FROM carne_dependente WHERE id='".$_GET['cod']."'";
			$resultado2 = mysqli_query($conec->con,$query2) or die('Erro na exclus�o '.$query2);

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

	       	 $Log1 = liberamenu('Excluir Dependentes');
	    	 $Log2 = $_GET['cod'];
	    	 $log3 = '';
	    	 $log4 = false;
	    	 $log5 = "";
	 	     grava_log($_SESSION['s_uid'],$_SESSION['s_codoperacao'],$Log2,$log3,$log4,$log4,$log4,$log5);
			
	 	     
			print "<script>redirect('".$_SERVER['PHP_SELF']."');</script>";
		

	} else


	// Incluindo registro com INSERT
	if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_CAD')))) {	

		$erro=false;

		$qryl = "SELECT * FROM carne_dependente WHERE nome='".$_POST['nome']."'";
		$resultado = mysqli_query($conec->con,$qryl) or die('Erro na Query :'.$qryl);
		$linhas = mysqli_num_rows($resultado);

		if ($linhas > 0)
		{
				$aviso = TRANS('MSG_EXIST_REG_CAD_SYSTEM');
				$erro = true;;
		}

		
		if (!$erro)
		{

			$situacao = $_POST['situacao'];
			if(empty($_POST['dtinativo'])) { $dtinativo = '1900-01-01 00:00:00'; } else $dtinativo = FDate($_POST['dtinativo']);
			$obs = $_POST['obs'];
			
			$query = "INSERT INTO carne_dependente (nome,parentesco,sexo,datanasc,registro,prontuario,nrocarteira,situacao,dtinativo,obs)".
					" values ('".strtoupper($_POST['nome'])."','".$_POST['parentesco']."','".$_POST['sexo']."','".$nascimento."','".$registro."',".$_POST['prontuario'].",'".$_POST['nrocarteira']."','".$situacao."','".$dtinativo."','".$obs."')";
						
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

		
	       	 $Log1 = liberamenu('Incluir Dependente');
	    	 $Log2 = 0;
	    	 $log3 = strtoupper($_POST['nome']);
	    	 $log4 = false;
	    	 $log5 = "";
	 	     grava_log($_SESSION['s_uid'],$_SESSION['s_codoperacao'],$Log2,$log3,$log4,$log4,$log4,$log5);
		
	 	     
		echo "<script>redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else

	// Alterando o registro com UPDATE
	if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_ALTER')))) {	

		$situacao = $_POST['situacao'];
		$dtinativo = Fdate($_POST['dtinativo']);
		$obs = $_POST['obs'];
		
		$query2 = "UPDATE carne_dependente SET nome='".strtoupper($_POST['nome'])."',parentesco='".$_POST['parentesco']."', sexo='".$_POST['sexo']."', datanasc='".$nascimento."', prontuario=".$_POST['prontuario'].", nrocarteira = '".$_POST['nrocarteira']."', situacao = '".$situacao."', dtinativo = '".$dtinativo."', obs = '".$obs."' WHERE id=".$_POST['codigo']." ";		
		
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

	       	 $Log1 = liberamenu('Alterar Dependentes');
	    	 $Log2 = $_POST['codigo'];
	    	 $log3 = strtoupper($_POST['nome']);
	    	 $log4 = false;
	    	 $log5 = "";
	 	     grava_log($_SESSION['s_uid'],$_SESSION['s_codoperacao'],$Log2,$log3,$log4,$log4,$log4,$log5);
			
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

	var idade = document.dependente.parentesco.value;

	if(idade > 0) {
		Dados(idade);
		var nidade = gE('ididade');
		var ndatanasc = gE('iddtnasc');
	}

	idadeatual = calcular_idade(pStr);

	if(nidade.value > 0) {
		if(idadeatual > nidade.value) {
			alert('Idade m�xima para esse parentesco � de '+nidade.value+' anos. A idade atual � '+idadeatual);
			ndatanasc.value = '';
		}
	}
	
	

		
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

/////////////////////////////////////////////////////////
//////////////////////////////////

function Dados(parentesco) { 

	//verifica se o browser tem suporte a ajax 
	 try { 
	  ajax = new ActiveXObject("Microsoft.XMLHTTP"); 
	 }  
	 catch(e) { 
	  try { 
	   ajax = new ActiveXObject("Msxml2.XMLHTTP"); 
	  } 
	  catch(ex) { 
	   try { 
	    ajax = new XMLHttpRequest(); 
	   } 
	   catch(exc) { 
	    alert("Esse browser n�o tem recursos para uso do Ajax"); 
	    ajax = null; 
	   } 
	  } 
	 } 
	 //se tiver suporte ajax 
	 if(ajax) {

	  ajax.open("GET", "idademaxima.php?cod="+parentesco, true); 
	  ajax.onreadystatechange = function() { 
	  //enquanto estiver processando...emite a msg de carregando 
	  if(ajax.readyState == 1) {
	   mensagem( "Carregando...!" );  
	  } 

	  var parentesco=gE('idparentesco');
	  var idade=gE('ididade');
	  
	  //alert(ajax.readyState+' '+ajax.responseText+' '+ajax.status);
	  
	  //ap�s ser processado - chama fun��o processXML que vai varrer os dados 
	 if(ajax.readyState == 4 ) { 
		if(ajax.status==200){

			r=ajax.responseText;
			rparentesco = r.substring(0, (i = r.indexOf(',')));

				idade.value=rparentesco;
			    //document.getElementById('ididade').value=rprontuario; 
			
			}
		  else { 
			   //caso n�o seja um arquivo XML emite a mensagem abaixo 
			   mensagem( "Erro ao carregar" ); 
			  } 
	 } 
	} 
	 ajax.send(null); 
	} else
	{

		 alert( "Sem Suporte a AJAX." ); 
		
	}
	}//end function Dados 

	//fun��o respons�vel pelo nome dos elementos do form
	function gE(ID) {
	return document.getElementById(ID);
	}

	function mensagem(msg){ 
		  document.getElementById('mensagem').innerHTML=msg; 
		}//end function mensagem 

</script>


<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idcodigo','','C�digo',1);
		if (ok) var ok = validaForm('idnome','','Nome do Dependente',1);
		if (ok) var ok = validaForm('iddtnasc','','Data de Nascimento',1);		

		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
