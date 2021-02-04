<?php header ('Content-type: text/html; charset=ISO-8859-1'); 
/*      Copyright 2014 MCJ Assessoria Hospitalar e Informática LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 04/04/2014 12:00

		* Módulo Carnê *

		Essa aplicação tem como objetivo geral controlar os Titulares e dependentes 
		que fazem “contribuição” mensal com a Unidade de Saúde (Hospital) para obter 
		um desconto em realização de atendimentos “Particular” ou até mesmo algum 
		diferencial em caso de internação SUS

*/

	session_start();
	
	include ('../../includes/include_geral.inc.php');
	include ('../../includes/include_geral_II.inc.php');
	include ('../../includes/classes/paging.class.php');
	
	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	
?>

<head> 
<title> Administração de Contratos </title> 
<style>

body, table { 
 font-family: Verdana, Arial, Helvetica, sans-serif; 
 font-size: 10px; 
 color: #000000; 
}


.menu { 
 font-family: Verdana, Arial, Helvetica, sans-serif; 
 font-size: 12px; 
 font-weight: normal; 
 color: #000033; 
 background-color: #FFFFFF; 
 border-right: 1px solid #000000; 
 border-top: 1px solid #000000; 
 border-bottom: 1px solid #000000; 
 padding: 5px; 
 cursor: pointer; 
}


.menu-sel { 
 font-family: Verdana, Arial, Helvetica, sans-serif; 
 font-size: 10px; 
 font-weight: bold; 
 color: #000033; 
 background-color: #CCCCCC; 
 border-right: 1px solid #000000; 
 border-top: 1px solid #000000; 
 padding: 5px; 
 cursor: default; 
}
 

.tb-conteudo { 
 border-right: 2px solid #000000; 
 border-bottom: 2px solid #000000; 
} 

.conteudo { 
 position: relative;
 font-family: Verdana, Arial, Helvetica, sans-serif; 
 font-size: 10px; 
 font-weight: normal; 
 color: #000033; 
 background-color: #F6F6F6; 
 padding: 5px; 
 width: 100%; 
 height: 768px; 
}

</style>

 
<script language="JavaScript"> 

function Limitedep(cod) { 

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
	    alert("Esse browser não tem recursos para uso do Ajax"); 
	    ajax = null; 
	   } 
	  } 
	 } 
	 //se tiver suporte ajax 
	 if(ajax) {
	  ajax.open("GET", "limitedependente.php?cod="+cod, true); 
	  ajax.onreadystatechange = function() { 
	  //enquanto estiver processando...emite a msg de carregando 
	  if(ajax.readyState == 1) {
	   mensagem( "Carregando...!" );  
	  } 

	  //var parentesco=gE('idparentesco');
	  //var idade=gE('ididade');
	  
	  //alert(ajax.readyState+' '+ajax.responseText+' '+ajax.status);
	  
	  //após ser processado - chama função processXML que vai varrer os dados 
	 if(ajax.readyState == 4 ) { 
		if(ajax.status==200){

			r=ajax.responseText;
			cretorno = r.substring(0, (i = r.indexOf(',')));
			r = r.substring(++i);
			npermitido = r.substring(0, (i = r.indexOf(',')));

			if(cretorno=='true') {
				redirect("incluirdependentes.php?action=incluir&cellStyle=true&cod="+cod);				
			} else {
				
					alert('Quantidade máxima de '+npermitido+' Dependentes por Titular atingido.');
								
				}
				
				

		}	
		  else { 
			   //caso não seja um arquivo XML emite a mensagem abaixo 
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

	//função responsável pelo nome dos elementos do form
	function gE(ID) {
	return document.getElementById(ID);
	}

	
function stAba(menu,conteudo) 
 { 
  this.menu = menu; 
  this.conteudo = conteudo; 
 }

var arAbas = new Array(); 
 arAbas[0] = new stAba('td_cadastro','div_cadastro'); 
 arAbas[1] = new stAba('td_consulta','div_consulta'); 
// arAbas[2] = new stAba('td_manutencao','div_manutencao'); 

function AlternarAbas(menu,conteudo) 
 { 
  for (i=0;i<arAbas.length;i++) 
  { 
   m = document.getElementById(arAbas[i].menu); 
   m.className = 'menu'; 
   c = document.getElementById(arAbas[i].conteudo); 
   c.style.display = 'none'; 
  } 
  m = document.getElementById(menu);
  m.className = 'menu-sel'; 
  c = document.getElementById(conteudo); 
  c.style.display = ''; 
 } 

</script>
<?php 

print "</head>";
 

print "<body onLoad=\"AlternarAbas('td_cadastro','div_cadastro')\">"; 

print "<h2 align='left'><B><font size='4'>Administração de Contratos</font></B></h2>";
print "<input type='text' name='titular' id='idtitular' value='".$_GET['cod']."' hidden='true'>";

print "<table width='100%' height='480' cellspacing='0' cellpadding='0' 
border='0' style='border-left: 1px solid #000000;'> ";
print "<tr>"; 
print "<td height='20' width='200' class='menu' id='td_cadastro' align='center'
  onClick=\"AlternarAbas('td_cadastro','div_cadastro')\"> 
   Dependente(s) 
  </td>";
print "  <td height='20' width='200' class='menu' id='td_consulta' align='center'
  onClick=\"AlternarAbas('td_consulta','div_consulta')\"> 
   Dados do Contrato 
  </td>"; 
//print "  <td height='20' width='200' class='menu' id='td_manutencao' align='center'
//  onClick=\"AlternarAbas('td_manutencao','div_manutencao')\"> 
//   Visualizar Contrato 
//  </td>"; 
print "<td width='460' style='border-bottom: 1px solid #000000'> 
   &nbsp; 
  <td>"; 
print "</tr>"; 
print "<tr>"; 
print "<td height='460' class='tb-conteudo' colspan='4'>";

// Aba para listar os dependentes
print "<div id='div_cadastro' class='conteudo' style='display: none'>&nbsp;&nbsp;NOME TITULAR<br>";


		// Incluindo dependentes do contrato
		if ((isset($_GET['action'])) && $_GET['action']=='incluirdependente') {

			$query2 = "Update carne_dependente set idtitular=".$_GET['cod']." WHERE id='".$_GET['coddep']."'";
			$resultado2 = mysql_query($query2) or die('Erro ... '.$query2);

			if ($resultado2 == 0)
			{
					$aviso = TRANS('ERR_DEL');
			}
			else
			{
					$aviso = TRANS('OK_DEL');
			}
			
		
		}

		// Apagando dependentes do contrato
		// Update no campo idtitular=0
		if ((isset($_GET['action'])) && $_GET['action']=='excluir') {

			$query2 = "Update carne_dependente set idtitular=0 WHERE id='".$_GET['cod']."'";
			$resultado2 = mysql_query($query2) or die('Erro ... '.$query2);

			if ($resultado2 == 0)
			{
					$aviso = TRANS('ERR_DEL');
			}
			else
			{
					$aviso = TRANS('OK_DEL');
			}
			
		
		}

		$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];
		
		$PAGE = new paging("PRINCIPAL");
		$PAGE->setRegPerPage($_SESSION['s_page_size']);
		$search = "";
		//$cellStyle = "cellpadding='5' cellspacing='0'";
		$cellStyle = "cellpadding='5' cellspacing='1'";
		
		print "<TABLE style='font-size:15px;' border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

		$query = "SELECT nometitular from carne_titular where id = ".$_GET['cod']."";
		$resultado = mysql_query($query) or die('ERRO NA QUERY !'.$query);
		$rowTitular = mysql_fetch_array($resultado);
		
		
		$query = "SELECT d.id,d.nome,d.datanasc,p.descricao as parentesco from carne_dependente d Left Join carne_tipodependente p on p.id = d.parentesco where d.idtitular=".$_GET['cod']." order by d.nome desc";
		$resultado = mysql_query($query) or die('ERRO NA QUERY !'.$query);
		$registros = mysql_num_rows($resultado);

		if (isset($_GET['LIMIT']))
		$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));
		
		$PAGE->execSQL();

		// variável que controla permissões dos botões para incluir, editar e excluir  do usuário
		$disabled = '';
		$clasbutton = " class='button'";
		
		print "<TR><TD><input type='text' style='font-family:tahoma; width:300; font-size: 15px; font-weight:bold; background-image:url('./aqua.png'); padding:1px;' value='".$rowTitular['nometitular']."' readonly='true'></TD>";
		print "<td colspan='4'></td>";
		print "<td colspan='4'></td>";
		print "<TD class='line'><a href='contratos.php'><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD></TR>";
				
		if (mysql_num_rows($resultado) == 0)
		{
			echo "<tr><td colspan='4'>".mensagem(TRANS('MSG_NOT_REG_CAD'))."</td></tr>";
			echo "<tr><td colspan='4' align='lefth'><a style='font-family:Tahoma; color:#1E90FF; font-weight:bold; text-decoration:underline; font-size:10pt;' onclick='Limitedep(".$_GET['cod'].")'>Incluir dependentes</td></tr>";
		}
		else
		{

			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr><td colspan='4'>";
			print "<B><br>".TRANS('FOUND')." <font color=red>".$PAGE->NUMBER_REGS."</font> ".TRANS('RECORDS_IN_SYSTEM').". ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</B></TD>";
			print "</tr>";
			//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
			print "<TR class='header'><td class='line' width='70%'>"."Nome Dependente"."</TD>"."<td class='line'>"."Parentesco"."<td class='line'>"."C&oacute;digo"."</TD>"."<td class='line'>"."Data Nasc"."</TD>".
				"</TD><td class='line'>".TRANS('COL_DEL')."</TD></tr>";
			
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
				print "<td class='line'>".$row['parentesco']."</td>";
				print "<td class='line'>".$row['id']."</td>";
				$dtnasc = str_replace('/','',substr(converte_datacomhora($row['datanasc']),0,10));
				print "<td class='line'>".mask($dtnasc,'##/##/####')."</td>";

				if(liberamenu('Excluir Contrato')=="N"){
					print "<td class='line'><a onClick=><img height='16' width='16' src='".ICONS_PATH."proibido.jpg' title='Exclus&atilde;o n&atilde;o permitida'></a></TD>";
				} else {
					print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('MSG_DEL_REG')."','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['id']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
				}
				
				
				print "</TR>";
			}
			print "<tr><td colspan='4'>";
			$PAGE->showOutputPages();
			print "</td></tr>";

		$hidden = '';
	    if(liberamenu('Incluir Contrato')=="N"){
	    	$hidden = " hidden='hidden'";
	    }
			
			echo "<tr><td colspan='4' align='lefth'><a style='font-family:Tahoma; color:#1E90FF; font-weight:bold; text-decoration:underline; font-size:10pt;' onclick='Limitedep(".$_GET['cod'].")' $hidden>Incluir dependentes</td></tr>";			
			
		}
	print "</table>";		


print "</div>";


// Aba para listar dados do Contrato
print "<div id='div_consulta' class='conteudo' style='display: none'>&nbsp;&nbsp;NOME TITULAR<br>";


		// Excluindo Contrato
		if ((isset($_GET['action'])) && $_GET['action']=='excluircontrato') {

			$query2 = "delete from carne_contratos where id ='".$_GET['idcontrato']."'";
			$resultado2 = mysql_query($query2) or die('Erro ... '.$query2);

			if ($resultado2 == 0)
			{
					$aviso = TRANS('ERR_DEL');
			}
			else
			{
					$aviso = TRANS('OK_DEL');
			}
			
		
		}

		$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];
		
		$PAGE = new paging("PRINCIPAL");
		$PAGE->setRegPerPage($_SESSION['s_page_size']);
		$search = "";
		//$cellStyle = "cellpadding='5' cellspacing='0'";
		$cellStyle = "cellpadding='5' cellspacing='1'";
		
		print "<TABLE style='font-size:15px;' border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

		$query = "SELECT nometitular from carne_titular where id = ".$_GET['cod']."";
		$resultado = mysql_query($query) or die('ERRO NA QUERY !'.$query);
		$rowTitular = mysql_fetch_array($resultado);
		
		$query = "SELECT c.id, c.nrocontrato, c.idtitular, c.datacontrato, c.plano, c.diavencto, c.status, c.registro, p.descricao from carne_contratos c Join carne_tipoplano p on p.id = c.plano where c.idtitular=".$_GET['cod']." order by p.descricao desc";
		$resultado = mysql_query($query) or die('ERRO NA QUERY !'.$query);
		$registros = mysql_num_rows($resultado);

		if (isset($_GET['LIMIT']))
		$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));
		
		$PAGE->execSQL();

		print "<TR><TD><input type='text' style='font-family:tahoma; width:300; font-size: 15px; font-weight:bold; background-image:url('./aqua.png'); padding:1px;' value='".$rowTitular['nometitular']."' readonly='true'></TD>";
		print "<td colspan='4'></td>";
		print "<td colspan='4'></td>";
		print "<TD class='line'><a href='contratos.php'><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD></TR>";

		// Nenhum registro encontrado
		if (mysql_num_rows($resultado) == 0)
		{
			echo "<tr><td colspan='4'>".mensagem(TRANS('MSG_NOT_REG_CAD'))."</td></tr>";
			echo "<tr><td colspan='4' align='lefth'><a style='font-family:Tahoma; color:#1E90FF; font-weight:bold; text-decoration:underline; font-size:10pt;' href='incluircontrato.php?action=incluir&cellStyle=true&cod=".$_GET['cod']."');'>Incluir Contrato</td></tr>";			
		}
		else
		{

			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr><td colspan='4'>";
			print "<B><br>".TRANS('FOUND')." <font color=red>".$PAGE->NUMBER_REGS."</font> ".TRANS('RECORDS_IN_SYSTEM').". ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</B></TD>";
			print "</tr>";
			//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
			print "<TR class='header'><td class='line' width='10%'>"."Nro Contrato"."</TD>"."<td class='line' width='20%'>"."Data Contrato"."<td class='line' width='50%'>"."Plano"."</TD>"."<td class='line' width='10%'>"."Dia Vencto"."</TD>".
				"<td class='line'>".TRANS('COL_EDIT')."</TD><td class='line'>".TRANS('COL_DEL')."</TD><td class='line'>"."CONTRATO"."</TD><td class='line'>"."CARTÃO"."</TD></tr>";
			
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
				print "<td class='line'>".$row['nrocontrato']."</td>";
				$dtcontrato = str_replace('/','',substr(converte_datacomhora($row['datacontrato']),0,10));
				print "<td class='line'>".mask($dtcontrato,'##/##/####')."</td>";
				print "<td class='line'>".$row['descricao']."</td>";
				print "<td class='line'>".$row['diavencto']."</td>";
				print "<td class='line' align='center'><a onClick=\"redirect('incluircontrato.php?action=alter&cod=".$row['id']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
				if(liberamenu('Excluir Contrato')=="N"){
					print "<td class='line'><a onClick=><img height='16' width='16' src='".ICONS_PATH."proibido.jpg' title='Exclus&atilde;o n&atilde;o permitida'></a></TD>";
				} else {
					print "<td class='line' align='center'><a onClick=\"confirmaAcao('".TRANS('MSG_DEL_REG')."','".$_SERVER['PHP_SELF']."', 'action=excluircontrato&cod=".$row['idtitular']."&idcontrato=".$row['id']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
				}

				if($_SESSION['modelocontrato'] == 1) {
				
				print "<td class='line' align='center'><a onClick=\"javascript:popup_doc('visualizacontrato.php?popup=true&cod=".$row['idtitular']."')\"><img height='16' width='16' src='".ICONS_PATH."search.png' title='"."Visualizar Contrato"."'></a></td>";
				print "<td class='line' align='center'><a onClick=\"javascript:popup_doc('visualizacartao.php?popup=true&cod=".$row['idtitular']."')\"><img height='16' width='16' src='".ICONS_PATH."search.png' title='"."Visualizar Cartão Desconto"."'></a></td>";
								
				}

				// Modelo 02 - Passa Tempo
				if($_SESSION['modelocontrato'] == 2) {
				
				print "<td class='line' align='center'><a onClick=\"javascript:popup_doc('visualizacontrato_2.php?popup=true&cod=".$row['idtitular']."')\"><img height='16' width='16' src='".ICONS_PATH."search.png' title='"."Visualizar Contrato"."'></a></td>";
								
				}
				
				// Modelo 03 - Itaguara
				if($_SESSION['modelocontrato'] == 3) {
				
				print "<td class='line' align='center'><a onClick=\"javascript:popup_doc('visualizacontrato_3.php?popup=true&cod=".$row['idtitular']."')\"><img height='16' width='16' src='".ICONS_PATH."search.png' title='"."Visualizar Contrato"."'></a></td>";
				print "<td class='line' align='center'><a onClick=\"javascript:popup_doc('visualizacartao_3.php?popup=true&cod=".$row['idtitular']."')\"><img height='16' width='16' src='".ICONS_PATH."search.png' title='"."Visualizar Cartão Desconto"."'></a></td>";
								
				}
				
				
				print "</TR>";
			}
			print "<tr><td colspan='4'>";
			$PAGE->showOutputPages();
			print "</td></tr>";

			$hidden = '';
			if(liberamenu('Incluir Contrato')=="N"){
				$hidden = " hidden='hidden'";
			}
			
			echo "<tr><td colspan='4' align='lefth'><a style='font-family:Tahoma; color:#1E90FF; font-weight:bold; text-decoration:underline; font-size:10pt;' href='incluircontrato.php?action=incluir&cellStyle=true&cod=".$_GET['cod']."');' $hidden>Incluir Contrato</td></tr>";			
		}
	print "</table>";		
	

print "</div>";

// 3a. ABA se precisar
//print "<div id='div_manutencao' class='conteudo' style='display: none'>"; 

		//echo "<script>redirect('visualizacontrato.php?cod=".$_GET['cod']."');</script>";

//print "</div>"; 
print "</td>"; 
print "</tr>"; 
print "</table>"; 
print "</body>"; 
print "</html>";