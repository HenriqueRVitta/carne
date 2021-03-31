<?php
/*      Copyright 2014 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 06/02/2019 11:20:24 GLPI 12706

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
	print "<BODY onLoad='document.usuarios.idnome.focus();'bgcolor='".BODY_COLOR."'>";


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
	

	print "<BR><B><font size=4>"."Administra&ccedil;&atilde;o de Usu&aacute;rios do Sistema"."</font></B><BR>";

	print "<FORM name='usuarios' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	$PAGE = new paging_("PRINCIPAL");
	$PAGE->setRegPerPage($_SESSION['s_page_size']);
	
	
	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

       	$query = "SELECT max(codigo) as id FROM usuarios ";
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA EXECU��O DA QUERY DE MAX ID! '.$query);
       	$maxid = mysqli_fetch_array($resultado);
       	
       	$cond=0;
       	$query = "SELECT * FROM usuarios";
		if (isset($_GET['cod'])) {
			$query.= " WHERE codigo = ".$_GET['cod']." ";
			$cond=1;
		}

		if ((isset($_POST['search'])) && !empty($_POST['search'])) {
			$query.= " where lower(nome) like lower(('%".noHtml($_POST['search'])."%'))";
			$cond=2;
			
		}

		if($cond==0) {
			$query.=" Where unidade =".$_SESSION['s_local']." ORDER BY codigo desc";
		} else {
			$query.=" and unidade =".$_SESSION['s_local']." ORDER BY nome";
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

	    if(liberamenu('Incluir Grupo')=="N"){
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
			print "<TR class='header'><td class='line' width='30%'>"."Nome do Usu&aacute;rio"."</TD>"."<td class='line' width='30%' >"."Perfil"."</TD>".
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

				if($row['perfilcarne'] == 1) { $perfil = "Administrador"; } else { $perfil = "Vendedor"; }
				
				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
				print "<td class='line'>".$row['nome']."</td>";
				print "<td class='line'>".$perfil."</td>";
				
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['codigo']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
				
				if(liberamenu('Excluir Grupo')=="N"){
			    	print "<td disabled='disabled' class='line'><a onClick=''><img height='16' width='16' src='".ICONS_PATH."proibido.jpg' title='Exclus&atilde;o n&atilde;o permitida'></a></TD>";
		       } else {
					print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('MSG_DEL_REG')."','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['codigo']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
		       }
				
				
				print "</TR>";
			}
			print "<tr><td colspan='4'>";
			$PAGE->showOutputPages();
			print "</td></tr>";
			
			
		}

	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

		print "<BR><b><font size=2 color='blue'>"."Inclus&atilde;o de Usu&aacute;rios"."</b></font><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='text4' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($maxid['id']+1,6)."' readonly='true' ></td>";
		print "<TD class='line'><a href='usuarios.php'><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome do Usu&aacute;rio".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='nome' maxlength='45' id='idnome'></td>";

		print "</TR><TR>";

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Perfil do Usu&aacute;rio".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='perfilcarne' id='idperfilcarne'>";  
		print "<option value='1' selected>Administrador</option>";
		print "<option value='2'>Vendedor</option>";
		print "</select>";
		print "</TD></TR>";
		
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Senha".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='password' name='senha' class='text3' maxlength='45' id='idsenha' value=''></td>";
		print "</TR><TR>";
		
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Confirma Senha".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='password' class='text3' name='confirmasenha' maxlength='45' id='idconfirmasenha' onBlur='ValidaSenha();' value=''></td>";
		print "</TR><TR>";		

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Situa&ccedil;&atilde;o".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='situacao' id='idsituacao'>";  
		print "<option value='1' selected>Ativo</option>";
		print "<option value='0'>Inativo</option>";
		print "</select>";
		print "</TD></TR>";
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Vinculado ao Vendedor".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		
		print "<select class='select2' name='vendedor' id='idvendedor' onBlur='return Dados(this.value)'>";  
				print "<option value=-1>"."Selecione o Vendedor"."</option>";
					$sql="Select id,nome from carne_vendedor where ativo = 1 and unidade = ".$_SESSION['s_local'];
					$commit = mysqli_query($conec->con,$sql);
					$i=0;
					while($row = mysqli_fetch_array($commit)){
						print "<option value=".$row['id'].">".$row['nome']."</option>";
						$i++;
					}
				print "</select>";
		
		print "</TD></TR><TR>";
		
		
		print "<TR> <TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='".TRANS('BT_CAD')."' name='submit'>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";


	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysqli_fetch_array($resultado);

		print "<BR><b><font size=2 color='blue'>"."Edi&ccedil;&atilde;o Dados do Vendedor"."</b></font><BR>";		

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='text4' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($row['codigo'],6)."' readonly='true' ></td>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome do Usu&aacute;rio".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='nome' maxlength='45' id='idnome' value = '".$row['nome']."'></td>";

		print "</TR><TR>";

		if($row['perfilcarne'] == 1) { $adm = " selected"; } else { $adm = ""; }
		if($row['perfilcarne'] == 2) { $vendedor = " selected"; } else { $vendedor = ""; }
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Perfil do Usu&aacute;rio".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='perfilcarne' id='idperfilcarne'>";  
		print "<option value='1'".$adm.">Administrador</option>";
		print "<option value='2'".$vendedor.">Vendedor</option>";
		print "</select>";
		print "</TD></TR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nova Senha".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='password' name='senhaalterada' class='text' maxlength='45' id='idsenhaalterada' onBlur='ValidaSenhaAlterada();' value=''>".
		"<INPUT type='hidden' name='senhagravada' class='text' maxlength='45' id='idsenhagravada' value='".$row['senhaweb']."'></td>";
		print "</TR><TR>";
		
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Confirma Nova Senha".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='password' class='text' name='confirmasenhaalterada' maxlength='45' id='idconfirmasenhaalterada' onBlur='ValidaSenhaAlterada();' value=''></td>";
		print "</TR><TR>";		

		if($row['ativo'] == 1) { $ativo = " selected"; } else { $ativo = ""; }
		if($row['ativo'] == 0) { $inativo = " selected"; } else { $inativo = ""; }
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Situa&ccedil;&atilde;o".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='situacao' id='idsituacao'>";  
		print "<option value='1'".$ativo.">Ativo</option>";
		print "<option value='2'".$inativo.">Inativo</option>";
		print "</select>";
		print "</TD></TR>";
		
		print "</TR></TD>";
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Vinculado ao Vendedor".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";

		print "<select class='select2' name='vendedor' id='idvendedor'>";  
		$sql="select id,nome from carne_vendedor where id =".$row['vendedor']."";
		$commit = mysqli_query($conec->con,$sql) or die ('Erro na Query '.$sql);
		$rowR = mysqli_fetch_array($commit);		
		print "<option value=-1>"."Selecione o Vendedor"."</option>";
				$sql="select id, nome from carne_vendedor where unidade =".$_SESSION['s_local']." order by id";
				$commit = mysqli_query($conec->con,$sql) or die ('Erro na Query '.$sql);;
							while($rowB = mysqli_fetch_array($commit)){
						print "<option value=".$rowB["id"]."";
                        			if ($rowB['id'] == $rowR['id'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB['nome']."</option>";
					}
			print "</select>";
				
		print "</TR><TR>";	
		
	    if(liberamenu('Alterar Grupo')=="N"){
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
				
				if(empty($_POST['datainativo'])) {
					$inativo = Fdate('01/01/1900');
				} else {
					$inativo = Fdate($_POST['datainativo']);
				}
				$registro = date("Y-m-d H:i:s");
		}

	// Excluindo registro com Delete		
	if (isset($_GET['action']) && $_GET['action'] == "excluir"){
		

			// Exclui o grupo
			$query2 = "update usuarios set ativo = 0 WHERE codigo='".$_GET['cod']."'";
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

			print "<script>redirect('".$_SERVER['PHP_SELF']."');</script>";
		

	} else


	if(isset($_POST['perfilcarne'])) {

			$acesso1 = 'NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN';
			$acesso2 = 'NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN';
			$acesso3 = 'NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN';
			$acesso4 = 'NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN';
			$acesso5 = 'NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNSNNNNNNNNNNNNSSSSSNSSSNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN';
			$acesso6 = 'NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN';

			// Perfil Administrador
			if($_POST['perfilcarne'] == 1) {
				$acesso1 = 'NSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSNNNNNSSSSSSSSSSSSSSSSSSSSSSSSSSSNNNNNSNNNNSNNNNSNNNNSSNNNNNNNNNSNNNNSNNNNSNNNNSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSNSSSSSSSSSSSSNSSSSSSSSSNNSSSNNNNNNNNNNNSSSSSSSSSSSSNSSSSSSSSSNNNN';
				$acesso2 = 'SSSSNSSSSSSSSSSNSNSSSSSNSSSSSNSSSSSSNSNSSSSSSSNSNNNNNNNNNNSSSNSSSSSNNNNNNSSSNSSSSSSSSSSSSSSSSSNNNNNNNNNNNSNNSSSSSSSSSSSSSNSSSSNNSSNSSSSSSSSSSSSSNSSSSSSSSSSSSSSSSSNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNSSSSSSSSSSNSNNSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS';
				$acesso3 = 'SSSSSSNSSSSSSSSSSSSSSSSSSSSSSSNNSSSSNNSNNNNNSSSSSSSSSSNSNNNNNSSSSSNSNSNSSSSSSSSSSSSSSSNNNNNNNNNNNNSSNSSSSSSSSSSSSSSSSSSSSSSNNNSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSNSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSNSNNNNNNNNNNNNNNNNNNNNNSSSSSSSSSSSSSNNSSSNN';
				$acesso4 = 'SSSSSSSSSSSSSSSSSNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNSSSSSSSSSSNNSSSSSSSSSSSSSSSNNNNNNNNNNNNNNNNNNNSSSSSSSNNNNSSSSSSSSSSSSSNSSSSSSSSSSNNNS';
				$acesso5 = 'SNNNSSSSSSSNNNNNNNNNNNNNNNNNSSSSSSNNNNNNNNNNNSSSSSSNSSSSSSSSSSSNNNNNSSSSSSSSSSSSSNNNSSSNNNNNNNSSSSSSSSSSSSSSSSSSSNNNNSSSSSSSSSSSNNNNSSNNNNSSSSSSSSSSSNSSSSSSSSSSSSSSSSSNSSSNSSSNSSSSNSSSNSSSSSSSNSNSSSNSSSSNSSSNSSSSSSSSSSSSSNNSSSSSSSSSSSSSSSSSNNNNNNNNNS';
				$acesso6 = 'SNNSSSSSSNSSSSSSSSSSNNNNNSSSNSSSSNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN';
			}
		
	}	
			
	// Incluindo registro com INSERT
	if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_CAD')))) {	

		$erro=false;

		$qryl = "SELECT * FROM usuarios WHERE nome='".$_POST['nome']."'";
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
			
			$senhaweb = md5($_POST['senha']);
			$DataInc = date('Y-m-d h:i:s');
			
			$query = "INSERT INTO usuarios (codigo,nome,senha,senhaweb,ativo,unidade,ver_anamnes,data_inc,perfilcarne,acesso1,acesso2,acesso3,acesso4,acesso5,acesso6,vendedor)".
					" values (".$_POST['codigo'].",'".$lcnome."','".substr($senhaweb,0,9)."','".$senhaweb."',".$_POST['situacao'].",".$_SESSION['s_local'].",0,'".$DataInc."',".$_POST['perfilcarne'].",'".$acesso1."','".$acesso2."','".$acesso3."','".$acesso4."','".$acesso5."','".$acesso6."',".$_POST['vendedor'].")";
						
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

		$senhaweb = $_POST['senhagravada'];
			
		if(!empty($_POST['senhaalterada'])) {
			$senhaweb = md5($_POST['senhaalterada']);
		} 
			

		$query2 = "UPDATE usuarios SET nome='".$lcnome."', senhaweb = '".$senhaweb."', unidade=".$_SESSION['s_local'].", ativo=".$_POST['situacao'].", perfilcarne=".$_POST['perfilcarne'].", acesso1 = '".$acesso1."', acesso2 ='".$acesso2."', acesso3 ='".$acesso3."', acesso4 ='".$acesso4."', acesso5 ='".$acesso5."', acesso6 ='".$acesso6."', vendedor = ".$_POST['vendedor']." WHERE codigo=".$_POST['codigo']." ";		
		
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
		if (ok) var ok = validaForm('idnome','','Nome do Usuario',1);		
		if (ok) var ok = validaForm('idsenha','','Senha do Usuario',1);		
		if (ok) var ok = validaForm('idconfirmasenha','','Confirma Senha',1);
		
		return ok;
	}


	function ValidaSenha() {

	var senha = document.getElementById("idsenha").value;
	var confirmasenha = document.getElementById("idconfirmasenha").value;

	if(senha != confirmasenha) {

		alert('Senha incorreta...');
   		document.getElementById("idconfirmasenha").value = '';
		
	}
    	
	document.getElementById("idconfirmasenha").focus();
	
	}

	function ValidaSenhaAlterada() {

		var senha = document.getElementById("idsenhaalterada").value;
		var confirmasenha = document.getElementById("idconfirmasenhaalterada").value;

		if(senha != confirmasenha) {

			alert('Senha incorreta...');
	   		document.getElementById("idconfirmasenhaalterada").value = '';
			
		}
	    	
		document.getElementById("idconfirmasenhaalterada").focus();
		
		}
	
-->
</script>


<?php

		
print "</body>";
print "</html>";
