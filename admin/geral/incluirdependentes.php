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

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	print "<BODY onLoad='document.dependente.idnome.focus();'bgcolor='".BODY_COLOR."'>";


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
	
	$query = "SELECT nometitular from carne_titular where id = ".$_GET['cod']."";
	$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$rowTitular = mysqli_fetch_array($resultado);
		
	print "<BR><B><font size=4>"."Inclus&atilde;o Dependente no Contrato de ".$rowTitular['nometitular']."</font></B><BR>";

	print "<FORM name='incluirdependentes' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	$PAGE = new paging("PRINCIPAL");
	$PAGE->setRegPerPage($_SESSION['s_page_size']);
	
	
	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='3' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='3' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

   		//$query = "SELECT * FROM carne_dependente order by nome";
		$query = "SELECT d.id,d.nome,d.datanasc,p.descricao as parentesco from carne_dependente d Left Join carne_tipodependente p on p.id = d.parentesco order by d.nome desc";
   		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
		$registros = mysqli_num_rows($resultado);

		if (isset($_GET['LIMIT']))
		$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));
		
		$PAGE->execSQL();

		print "<TR><TD class='line'>"."Digite o nome do Dependente"."</TD></TR>";
		print "<tr><td colspan='4'>".
			"<input type='text' class='text3' name='search' id='idSearch' value='".$search."'>&nbsp;";
			print "<input type='submit' name='BT_SEARCH' class='button' value='".TRANS('BT_FILTER')."'>".
		"</td>";
		print "<td colspan='4'></td>";
		print "<td colspan='4'></td>";
		print "<TD class='line'><a href='abas.php?cod=".$_GET['cod']."'><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD></TR>";
			
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
			print "<TR class='header'><td class='line' width='70%'>"."Nome Dependente"."</TD>"."<td class='line'>"."Parentesco"."</TD>"."<td class='line'>"."C&oacute;digo"."</TD>"."<td class='line'>"."Data Nasc"."</TD>".
				"</TD><td class='line' align='center'>".TRANS('COL_INC')."</TD></tr>";
			
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
				print "<td class='line'>".$row['parentesco']."</td>";
				print "<td class='line'>".$row['id']."</td>";
				$dtnasc = str_replace('/','',substr(converte_datacomhora($row['datanasc']),0,10));
				print "<td class='line'>".mask($dtnasc,'##/##/####')."</td>";
				print "<td class='line' align='center'><a onClick=\"confirmaAcao('".TRANS('MSG_INC_REG')."','abas.php', 'action=incluirdependente&coddep=".$row['id']."&cod=".$_GET['cod']."')\"><img height='16' width='16' src='".ICONS_PATH."ok.png' title='".TRANS('HNT_INC')."'></a></TD>";
				
				print "</TR>";
			}
			print "<tr><td colspan='4'>";
			$PAGE->showOutputPages();
			print "</td></tr>";
			
			
		}

print "</table>";
print "</body>";
print "</html>";
