<?php
// header ('Content-type: text/html; charset=ISO-8859-1');
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
	print "<BODY onLoad='document.contratos.idnome.focus();'bgcolor='".BODY_COLOR."'>";


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
	

	print "<BR><B><font size=4>"."Administra&ccedil;&atilde;o de Contratos"."</font></B><BR>";

	print "<FORM name='contratos' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	$PAGE = new paging_("PRINCIPAL");
	$PAGE->setRegPerPage($_SESSION['s_page_size']);
	
	
	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='4' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='4' cellspacing='1'";
		
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

       	$query = "SELECT max(id) as id FROM carne_titular ";
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA EXECU��O DA QUERY DE MAX ID!');
       	$maxid = mysqli_fetch_array($resultado);
       	
       	$cond=0;
       	$query = "SELECT * FROM carne_titular ";
		if (isset($_GET['cod'])) {
			$query.= " WHERE id = ".$_GET['cod']." ";
			$cond=1;
		}

		if ((isset($_POST['search'])) && !empty($_POST['search'])) {
			$query.= " where lower(nometitular) like lower(('%".noHtml($_POST['search'])."%')) or lower(cidade) like lower(('%".noHtml($_POST['search'])."%'))";
			$cond=2;
			
		} else {
			$query.=" Where unidade =".$_SESSION['s_local']." ORDER BY id desc";
		}

		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA EXECUÇÂO DA QUERY DE CONSULTA 1!');
		$registros = mysqli_num_rows($resultado);

	// vari�vel que controla permiss�es dos bot�es para incluir, editar e excluir  do usu�rio
	$disabled = '';
	$clasbutton = " class='button'";
		

		if (isset($_GET['LIMIT']))
		$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));
		
	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		$PAGE->execSQL();
		
	    if(liberamenu('Excluir Contrato')=="N"){
	    	$disabled = " disabled='disabled'";
	    	$clasbutton = " class='buttonDisabled'";
	    }
		
		
		print "<TR><TD class='line'>"."Digite o nome do Titular"."</TD></TR>";
		print "<tr><td colspan='4'>".
			"<input type='text' class='text3' name='search' id='idSearch' value='".$search."' placeholder='Digite o nome do cliente'>&nbsp;";
			print "<input type='submit' name='BT_SEARCH' class='button' value='".TRANS('BT_FILTER')."'>".
			"</td></tr>";
		
		if (mysqli_num_rows($resultado) == 0)
		{
			echo "<tr><td colspan='4'>".mensagem(TRANS('MSG_NOT_REG_CAD'))."</td></tr>";
			echo "<tr><td colspan='4'>".mensagem('Cadastre o Titular para ter acesso ao contrato.')."</td></tr>";
			
		}
		else
		{

			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr><td colspan='4'>";
			print "<B>".TRANS('FOUND')." <font color=red>".$PAGE->NUMBER_REGS."</font> ".TRANS('RECORDS_IN_SYSTEM').". ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</B></TD>";
			print "</tr>";
			//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
			print "<TR class='header'><td class='line'>"."Cliente"."</TD>"."<td class='line'>"."C&oacute;digo"."</TD>"."<td class='line'>"."Fone Contato"."</TD>"."<td class='line'>"."Nro Contrato"."</TD>".
				"<td class='line' align='center'><h3>"." Contrato "."</h3></TD></tr>";
			
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
				print "<td class='line'>".$row['nometitular']."</td>";
				print "<td class='line'>".$row['id']."</td>";
				if(!empty($row['celular'])) { 
					print "<td class='line'>".mask($row['celular'],'(##)####-#####')." Cel</td>"; 
				} else {
									if(!empty($row['telefoneres'])) {
										print "<td class='line'>".mask($row['telefoneres'],'(##)####-#####')." Res</td>";										
									} else {

										if(!empty($row['telefonecom'])) {
										print "<td class='line'>".mask($row['telefonecom'],'(##)####-#####')." Com</td>";										
										
									} else {
										print "<td class='line'>"." N&acirc;o informado"."</td>";										
									}					
				}
				}
				print "<td class='line'>".$row['nrocontrato']."</td>";
				
				print "<td class='line' align='center'><a onClick=\"redirect('abas.php?action=dependentes&cod=".$row['id']."&cellStyle=true')\"><img height='18' width='18' src='".ICONS_PATH."contents.png' title='"."Acessar dados do Contrato"."'></a></td>";
				print "<td class='line'><a href=\"redirect('abas.php?action=excluir&cod=".$row['id']."')\"></a></TD>";
				
				print "</TR>";
			}
			print "<tr><td colspan='4'>";
			$PAGE->showOutputPages();
			print "</td></tr>";
			
			
		}

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
		if (ok) var ok = validaForm('idnome','','Nome do Cliente',1);
		if (ok) var ok = validaForm('idcidade','','Cidade',1);		
		if (ok) var ok = validaForm('iddtnasc','','Data de Nascimento',1);		
		if (ok) var ok = validaForm('idcpf','','CPF',1);		

		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
