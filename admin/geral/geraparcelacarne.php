<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Informática LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 04/02/2015 14:13

		* Módulo Carnê *

		Opção de geração de Carnê/Boleto de cobrança

*/
	session_start();
	
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/classes/paging.class.php");
	include ("../../includes/calendario.php");

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	$fecha = "";
	if (isset($_GET['popup'])) {
		$fecha = "window.close()";
	} else {
		$fecha = "history.back()";
	}
	
	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	print "<BODY onLoad='document.geraparcelacarne.idparcelas.focus();' bgcolor='".BODY_COLOR."'>";
	
	print "<BR><B><font size=4>"."Gerar Parcelamento de Boleto/Carn&ecirc; de Cobran&ccedil;a"."</font></B><BR>";

	//print_r($_POST);
	
	// Exibe o campo para informar o Nro do Carnê
	if(!isset($_POST['processar']) && empty($_POST['submit'])) {
		
	print "<FORM name='geraparcelacarne' method='POST' action='geraparcelacarne.php' onSubmit=\"return valida()\">";	
			
		if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
		} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	
	print "<TABLE border='0' align='left' ".$cellStyle."  width='80%' bgcolor='".BODY_COLOR."'>";

		print "<BR><b><font size=2 color='blue'>"."Op&ccedil;&otilde;es de Filtro"."</b></font><BR>";
		print "<TD></TD>";
		print "<TR>";				
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."N&uacute;mero do Carn&ecirc;".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><input type='text' class='text3' name='nrocarne' id='idnrocarne' value=''></td>";
		print "</TR>";

		print "<TR>";		

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome do Cliente".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='idtitular' id='idtitular' required>";  
		print "<option value=''>"."Todos"."</option>";
			$sql="Select id,nometitular from carne_titular where situacao = 'ATIVO' order by nometitular";
					$commit = mysql_query($sql);
					$i=0;
		
					while($row = mysql_fetch_array($commit)){

						print "<option value=".$row['id'].">".$row['nometitular']."</option>";
						$i++;
					}
				print "</select>";
		
		print "</TR>";		
				
		print "<TD align='left' width='5%' bgcolor='".BODY_COLOR."'><input type='submit' value='PROCESSAR' name='submit' >";

		print "</TR></TR>";
		print "</table>";
		print "</br>";
		
       	$query = "SELECT max(id) as id FROM carne_titular ";
		$resultado = mysql_query($query) or die('ERRO NA EXECUÇÂO DA QUERY DE MAX ID!');
       	$maxid = mysql_fetch_array($resultado);
       	
       	$cond=0;
       	$query = "SELECT c.*, p.dtregistro, p.id as idparcela, p.databaixa, p.vlrmensal, p.vlrparcelado, p.taxa, p.mesano FROM carne_titular c Join carne_parcelamento p on p.idcliente=c.id ";
		if (isset($_GET['cod'])) {
			$query.= " WHERE c.id = ".$_GET['cod']." ";
			$cond=1;
		}
		
		$carne = "";
		
		if($cond==0) {
			$query.=" Where c.unidade =".$_SESSION['s_local']." ORDER BY p.id desc limit 50";
		} else {
			$query.=" and c.unidade =".$_SESSION['s_local']." ORDER BY p.id desc limit 50";
		}

		$resultado = mysql_query($query) or die('ERRO NA EXECUÃ‡Ã‚O DA QUERY DE CONSULTA 1! '.$query);
		$registros = mysql_num_rows($resultado);

		// variável que controla permissões dos botões para incluir, editar e excluir  do usuário
		$disabled = '';
		$clasbutton = " class='button'"; 	
		print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";
		
			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr><td colspan='4'>";
			print "<B>&Uacute;ltimos 50 parcelamentos efetuados...</B></TD>";
			print "</tr>";

			//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
			print "<TR class='header'><td class='line' width='30%'>"."Cliente"."</TD>"."<td class='line' width='10%'>"."C&oacute;digo"."</TD>"."<td class='line' width='10%'>"."Nro Carn&ecirc;"."</TD>"."<td class='line'>"."Data Gera&ccedil;&atilde;o"."</TD>"."<td class='line'>"."Data Pagto"."</TD>"."<td class='line'>"."Compet&ecirc;ncia"."</TD>"."<td class='line' width='10%'>"."Valor Plano"."</TD>"."<td class='line' width='10%'>"."Valor Parcelado"."</TD>".			
			"<td class='line' align='center'>"."Excluir"."</TD></tr>";
			
			$j=2;
			$id = "";
			//while ($row = mysql_fetch_array($PAGE->RESULT_SQL))
			while ($row = mysql_fetch_array($resultado))
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

				$id = $row['id'];
				
				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
				print "<td class='line'>".$row['nometitular']."</td>";
				print "<td class='line'>".$row['id']."</td>";
				print "<td class='line'>".$row['nrocarne']."</td>";
				
				$dtregistro = str_replace('/','',substr(converte_datacomhora($row['dtregistro']),0,10));
				
				print "<td class='line'>".mask($dtregistro,'##/##/####')."</td>";

				$dtbaixa = str_replace('/','',substr(converte_datacomhora($row['databaixa']),0,10));
				
				if($row['databaixa']=='1900-01-01 00:00:00') {
				print "<td class='line'></td>";					
				} else {
				print "<td class='line'>".mask($dtbaixa,'##/##/####')."</td>";					
				}
				
				print "<td class='line'>".invertecomp($row['mesano'],1)."</td>";
				print "<td class='line'>".$row['vlrmensal']."</td>";
				print "<td class='line'>".$row['vlrparcelado']."</td>";
				
			    if(liberamenu('Excluir Tipo Plano')=="N"){
			    	print "<td disabled='disabled' class='line'><a onClick=''><img height='16' width='16' src='".ICONS_PATH."proibido.jpg' title='Exclus&atilde;o n&atilde;o permitida'></a></TD>";
		       } else {
					print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('MSG_DEL_REG')."','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['idparcela']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
		       }
				
				//print "<td class='line' align='center'><a onClick=\"redirect('pagamentos.php?cod=".$row['id'].$mesano."&action=incluir&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."table-money-icon.png' title='".TRANS('HNT_EDIT')."'></a></td>";				
											
				print "</TR>";
			}
			print "<tr><td colspan='4'>";
			print "</td></tr>";
						
		
	print "</table>";
	print "</form>";
	
	}

	// Excluindo registro com Delete		
	if (isset($_GET['action']) && $_GET['action'] == "excluir"){
		
			$query2 = "DELETE FROM carne_parcelamento WHERE id='".$_GET['cod']."'";
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
		

	}
	
	// Exibe os Dados de Pagamento do Carnê e Gera o Parcelamento
	if(!empty($_POST['submit']) && $_POST['submit']=='PROCESSAR') {

	$PAGE = new paging("PRINCIPAL");
	$PAGE->setRegPerPage($_SESSION['s_page_size']);

		if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
			} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	
	print "<FORM name='geraparcelacarne' method='POST' action='geracarneparcelamento.php' target='_blank' onSubmit=\"return valida()\">";	

		if(isset($_POST['nrocarne'])) {
			$Where = " Where c.nrocarne = ".$_POST['nrocarne']." ";
		}

			if (isset($_POST['idtitular']) && $_POST['idtitular'] > 0) {
				$Where = " Where c.id = ".$_POST['idtitular']." ";
			}
		
		$query = "SELECT c.id, c.nrocarne, c.nometitular, p.nrocontrato, p.plano, p.diavencto, p.datacontrato, q.percdesc, d.valor, d.compet_ini, d.compet_fim FROM carne_titular c ".
       	" Join carne_contratos p on p.idtitular = c.id ".
       	" Join carne_tipoplano q on q.id = p.plano ".
       	" Join carne_competenciaplano d on d.idplano = p.plano ".$Where;
       	
		
		$resultado = mysql_query($query) or die('ERRO NA QUERY !'.$query);
		$rowA = mysql_fetch_array($resultado);
		$valor  = Round($rowA['valor']).".00";
		$percentual = $rowA['percdesc'];
		
		$mesano = "";
		$ano	= "";
		$Date	= date("d/m/Y");
		
		if($_SESSION['s_utilizacontrato']=='Nao') {
			$nrocarnecontrato = $rowA['nrocarne'];
		} else {
			$nrocarnecontrato = $rowA['nrocontrato'];					
		}
		
		$ValorApagar = 0.00;
		
	
		if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
		} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	
	print "<BR><TABLE border='0' align='left' ".$cellStyle."  width='80%' bgcolor='".BODY_COLOR."'>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Contrato/Carn&ecirc;".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nrocarne' class='text4' id='idnrocarne' onblur='buscarCarne(this.value)' onkeyup=\"maskIt(this,event,'######')\" value='".$nrocarnecontrato."' readonly='readonly'>";
		print "<INPUT type='text' name='cliente' class='text4' id='idcliente' value='".$rowA['id']."' hidden='true'>";
		print "<INPUT type='text' name='nomecliente' class='text3' id='idnomecliente' value='".$rowA['nometitular']."' disabled='disabled'>";
		print "</td>";
		print "</TR><TR>";	
			
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Tipo de Plano".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select3' name='plano' id='idplano' disabled='disabled'>";  
		print "<option value=-1>"."Tipo de Plano"."</option>";
			$sql="Select id,descricao from carne_tipoplano order by id";
					$commit = mysql_query($sql);
					$i=0;
		
					while($row = mysql_fetch_array($commit)){

						if($row['id']==$rowA['plano']) { $selected = " selected"; } else { $selected = "";}
						
						print "<option value=".$row['id'].$selected.">".$row['descricao']."</option>";
						$i++;
					}
				print "</select>";

		print "</TR></TD><TR>";

		//date("Y-m-d H:i:s");		
		$inicial = "01/".date("m")."/".date("Y");
		$final = "31/12/".date("Y");
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inicial".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datainicio' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario1' onBlur='return doDateVenc(this.id,this.value, 4)' value='".$inicial."'></td>";
		print "</TR><TR>";
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Final".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datafim' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario2' onBlur='return doDateVenc(this.id,this.value, 4)' value='".$final."'></td>";
		print "</TR><TR>";
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Percentual Taxa".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='vlrtaxa' maxlength='20' id='idvlrtaxa' value='".$percentual."' readonly='readonly'>%</td>";
		print "</TR><TR>";
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Valor do Plano".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='vlrplano' maxlength='20' id='idvlrplano' value='".$valor."' readonly='readonly'</td>";
		print "</TR><TR>";
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Quantidade de Parcelas".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='parcelas' maxlength='3' id='idparcelas' value=''></td>";
		print "</TR><TR>";
		
		if($ValorApagar==0) {
			$ValorApagar = $valor;
		}
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Valor das Parcelas".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='vlrpago' maxlength='20' id='idvlrpago' value='".$ValorApagar."' onkeypress=\"return formatar_moeda(this,',','.',event);\"</td>";
		print "</TR><TR>";
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Carn&ecirc;/Capa:".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='tipoimpressao' id='idtipoimpressao'>";  
		print "<option value=1>"."Capa"."</option>";
		print "<option value=2>"."Carn&ecirc;"."</option>";
		print "</select>";

		print "</TR></TR>";
		
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='PROCESSAR' name='submit' >";
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";
		
		print "</TR></TR>";
		
	print "</table>";

	
		/***********
		 * Começa aqui a lista dos pagamentos edetuados pelo cliente
		 * Henrique 11/03/2015 14:20
		 */

       	$queryPrincipal = "SELECT p.id, p.idcliente, p.nrocarne, p.mesano, p.databaixa, p.localpagto, p.vlrcalculado, p.vlrpago, p.taxa, p.unidade, c.nometitular, l.descricao FROM carne_pagamentos p ".
       	" Join carne_titular c on c.id = p.idcliente ".
       	" Left Join carne_localpagto l on l.id = p.localpagto";


			if (isset($_POST['idtitular']) && $_POST['idtitular'] > 0) {
				$queryPrincipal.= " WHERE p.idcliente = ".$_POST['idtitular']." ";
			}

/*       	
		if(isset($_POST['nrocarne'])) {
      		$queryPrincipal.= " WHERE p.nrocarne = ".$_POST['nrocarne']." ";
		}
*/
					
		$queryPrincipal.=" and p.unidade =".$_SESSION['s_local']." ORDER BY p.id desc";
		
		$resultado = mysql_query($queryPrincipal) or die('ERRO NA QUERY !'.$queryPrincipal);
	
		$registros = mysql_num_rows($resultado);

	    $disabled = '';
	    $clasbutton = " class='button'";
		
		if (isset($_GET['LIMIT']))
		$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($queryPrincipal,(isset($_GET['FULL'])?$_GET['FULL']:0));
		
		$PAGE->execSQL();

		if (!isset($_GET['cellStyle'])) {
			$cellStyle = "cellpadding='5' cellspacing='0'";
		} else
			$cellStyle = "cellpadding='5' cellspacing='1'";
		print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";
		
		if (mysql_num_rows($resultado) == 0)
		{
			echo "<tr><td colspan='4'>".mensagem('Nenhum pagamento registrado')."</td></tr>";
		}
		else
		{

			print "<tr><td colspan='4'>";
			print "<B>".TRANS('FOUND')." <font color=red>".$PAGE->NUMBER_REGS."</font> ".TRANS('RECORDS_IN_SYSTEM').". ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</B></TD>";
			print "</tr>";
			//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
			print "<TR class='header'><td class='line' width='30%'>"."Cliente"."</TD>"."<td class='line' width='10%' >"."Taxa %"."</TD>"."<td class='line' width='15%' >"."Vlr Calculado"."</TD>"."<td class='line' width='15%' >"."Valor Pago"."</TD>"."<td class='line' width='10%'>"."Data Pagto"."</TD>"."<td class='line' width='10%'>"."Compet&ecirc;ncia"."</TD>"."<td class='line' width='20%'>"."Local Pagamento"."</td>"."</tr>";

			$lcLibera1 = liberamenu('Alterar Pagamento Carne');
			$lcLibera2 = liberamenu('Excluir Pagamento Carne');
		
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
				print "<td class='line'>".$row['nometitular']."</td>";
				print "<td class='line'>".$row['taxa']."</td>";
				print "<td class='line'>".$row['vlrcalculado']."</td>";
				print "<td class='line'>".$row['vlrpago']."</td>";
				$dtregistro = str_replace('/','',substr(converte_datacomhora($row['databaixa']),0,10));
				print "<td class='line'>".mask($dtregistro,'##/##/####')."</td>";
				print "<td class='line'>".invertecomp($row['mesano'],1)."</td>";
				print "<td class='line'>".$row['descricao']."</td>";
				
				
				print "</TR>";
			}
			print "<tr><td colspan='4'>";
			$PAGE->showOutputPages();
			print "</td></tr>";
		
		}
	
	print "</form>";
	
	}
	
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
		var ok = validaForm('calendario1','','Data Inicial',1);
		var ok = validaForm('calendario2','','Data Final',1);
		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
