<?php 
/*      Copyright 2012 MCJ Assessoria Hospitalar e Informï¿½tica LTDA

         Desenvolvedor: Carlos Henrique R Vitta

         Este software, tem como finalidade proporcionar aos colaboradores da MCJ
         (Gerentes,Tï¿½cnicos e desenvolvedores) o registro das tarefas executadas
         nos projetos desenvolvidos pela empresa
*/
	session_start();


	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	include ("../../includes/classes/paging.class.php");
	
	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	print "<BODY onLoad='document.consultapacientes.idSearch.focus();'bgcolor='".BODY_COLOR."'>";


	$fecha = "";
	if (isset($_GET['popup'])) {
		$fecha = "window.close()";
	} else {
		$fecha = "history.back()";
	}

    $query = "SELECT razao FROM cadastro_unidades where codigo=".$_SESSION['s_local'];
	$resultado = mysql_query($query) or die('ERRO NA Ececucao da Query! '.$query);
    $row = mysql_fetch_array($resultado);
	
	print "<TABLE border='0' align='left' cellpadding='5' cellspacing='2' width='100%' bgcolor='".BODY_COLOR."'>";
	print "<TR><TD><h2 align='center'><B><font size='4'>".$row['razao']."</font></B></h2></TD></TR>";
	print "<TR><TD><h3 align='center'><B><font size='4'>Consulta Pacientes</font></B></h3></TD></TR>";
	print "<TR><TD align='right'><input type='button' class='button' value='Fechar' onclick='self.close();'></TD></TR>";
	print "</TABLE>";	

	print "<FORM name='consultapacientes' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	$PAGE = new paging("PRINCIPAL");
	$PAGE->setRegPerPage($_SESSION['s_page_size']);
	
	
	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

       	$query = "SELECT max(prontuario) as id FROM pacientes  ";
		$resultado = mysql_query($query) or die('ERRO NA Ececucao da Query MAX Prontuario!');
       	$maxid = mysql_fetch_array($resultado);

       	if($maxid['id']==0) { $maxid['id'] = 1; }

       	$nome="";
       	$cond=0;
       	$query = "SELECT prontuario,nome,dtnasc,mae_nome FROM pacientes ";
		if (isset($_GET['nome'])) {
			$query.= " WHERE nome like '%".noHtml($_GET['nome'])."%'";
			$nome=$_GET['nome'];
			$cond=1;
		}

		if ((isset($_POST['search'])) && !empty($_POST['search'])) {
			$query.= " where nome like '%".noHtml($_POST['search'])."%'";
			$cond=2;
		}

		$query.=" order by nome";
		
		$resultado = mysql_query($query) or die('ERRO NA EXECUÇÂO DA QUERY CONSULTA!');
		$registros = mysql_num_rows($resultado);

		if (isset($_GET['LIMIT']))
		$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));
		
	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

	
		$PAGE->execSQL();
		print "<tr><td colspan='4'>".
			"<input type='text' class='text3' name='search' id='idSearch' value='".$nome."'>&nbsp;";
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
			print "<TR class='header'><td class='line'>"."Paciente"."</TD>"."<td class='line'>"."Registro"."</TD>"."<td class='line'>"."Sele&ccedil;&atilde;o"."</TD></tr>";
			
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
				print "<td class='line'>".$row['nome']."</td>";
				print "<td class='line'>".$row['prontuario']."</td>";
				print "<td class='line'><a onClick=\"javascript:retorna('".$row['prontuario']."')\"><img height='16' width='16' src='".ICONS_PATH."ok.png' title='"."Click para selecionar o registro"."'></a></td>";
				
				print "</TR>";
			}
			print "<tr><td colspan='8'>";
			$PAGE->showOutputPages();
			print "</td></tr>";
			
		}

	} 
	
	print "</table>";

?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idnome','','Nome do Cliente',1);
		if (ok) var ok = validaForm('ididcidade','','Cidade',1);
		return ok;
	}

function retorna(dataatual) { 
	//alert(dataatual);
	window.opener.document.abastitular.prontuario.value = dataatual;
	window.close(); 
}
-->
</script>


<?php 
print "</body>";
print "</html>";
?>