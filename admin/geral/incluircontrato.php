<?php
/*      Copyright 2014 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 08/04/2014 08:00

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
?>
   	<!-- Utilize isso para evitar conflito entre scripts
	veja exemplo: http://blog.sahb.com.br/2009/04/conflito-entre-prototypejs-e-jqueryjs/ -->
	<script type="text/javascript" src="/carne/admin/geral/scripts/lib/jquery.js"></script>
    <script type="text/javascript">
        jQuery.noConflict();
    </script>

	<!-- Traz os dados referente ao CPF do paciente -->
	<script type="text/javascript" src="/carne/admin/geral/scripts/lib/idademaxima.js"></script>

<?php 

print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	print "<BODY onLoad='document.contratos.idcodigo.focus();'bgcolor='".BODY_COLOR."'>";


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

	$PAGE = new paging("PRINCIPAL");
	$PAGE->setRegPerPage($_SESSION['s_page_size']);
	
	
	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";
	
       	$query = "SELECT max(id) as id FROM carne_contratos ";
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA EXECU��O DA QUERY DE MAX ID!');
       	$maxid = mysqli_fetch_array($resultado);
       	
       	$cond=0;
       	$query = "SELECT * FROM carne_contratos ";
		if (isset($_GET['cod'])) {
			$query.= " WHERE id = ".$_GET['cod']." ";
			$cond=1;
		}

		if($cond==0) {
			$query.=" Where unidade =".$_SESSION['s_local']." ORDER BY id desc";
		} else {
			$query.=" and unidade =".$_SESSION['s_local']."";
		}

		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA EXECUÇÂO DA QUERY DE CONSULTA 1!');
		$registros = mysqli_num_rows($resultado);

	// vari�vel que controla permiss�es dos bot�es para incluir, editar e excluir  do usu�rio
	$disabled = '';
	$clasbutton = " class='button'";
		
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

	    if(liberamenu('Incluir Contrato')=="N"){
	    	$disabled = " disabled='disabled'";
	    	$clasbutton = " class='buttonDisabled'";
	    }
		
		print "<BR><b><font size=2 color='blue'>"."Inclus&atilde;o do Contrato"."</b></font><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Contrato".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='text4' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($maxid['id']+1,6)."' onBlur='VerContrato(this.value)'></td>";
		print "<INPUT type='text' name='titular' class='text4' id='idtitular' onkeyup=\"maskIt(this,event,'######')\" value='".$_GET['cod']."' hidden='true' >";
		print "<TD class='line'><a href='abas.php?cod=".$_GET['cod']."'><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data do Contrato".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtcontrato' id='iddtcontrato' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\"  onBlur='return doDateVenc(this.id,this.value, 4)'></td>";
		print "</TR><TR>";		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Plano".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='plano' id='idplano'>";  
				print "<option value=-1>"."Selecione o Plano"."</option>";
					$sql="Select id,descricao from carne_tipoplano where unidade = ".$_SESSION['s_local'];
					$commit = mysqli_query($conec->con,$sql);
					$i=0;
					while($row = mysqli_fetch_array($commit)){
						print "<option value=".$row['id'].">".$row['descricao']."</option>";
						$i++;
					}
				print "</select>";
		print "</TR></TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Dia do Vencimento".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='diavencto' class='text4' id='iddiavencto' maxlength='2' OnKeyPress=\"javascript:formatarcampo('##', this)\"></td>";
		print "</TR><TR>";
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Status".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='status' id='idstatus'>";  
		print "<option value='0'>Ativo</option>";  
		print "<option value='1'>Inativo</option>";  
		print "</select>";  
		print "</TR><TR>";
		print "<TR> <TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' $clasbutton value='".TRANS('BT_CAD')."' name='submit' $disabled>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";
		

	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

	    if(liberamenu('Alterar Contrato')=="N"){
	    	$disabled = " disabled='disabled'";
	    	$clasbutton = " class='buttonDisabled'";
	    }
		
		$row = mysqli_fetch_array($resultado);

		print "<BR><b><font size=2 color='blue'>"."Edi&ccedil;&atilde;o do Contrato"."</b></font><BR>";		

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Contrato".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='text4' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($row['id'],6)."' readonly='true' ></td>";
		print "<INPUT type='text' name='titular' class='text4' id='idtitular' onkeyup=\"maskIt(this,event,'######')\" value='".$row['idtitular']."' hidden='true' >";
		print "<INPUT type='text' name='contrato' class='text4' id='idcontrato' onkeyup=\"maskIt(this,event,'######')\" value='".$_GET['cod']."' hidden='true' >";		
		print "<TD class='line'><a href='abas.php?cod=".$row['idtitular']."'><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data do Contrato".":</TD>";
		$dtcontrato = str_replace('/','',substr(converte_datacomhora($row['datacontrato']),0,10));
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtcontrato' id='iddtcontrato' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\"  onBlur='return doDateVenc(this.id,this.value, 4)' value='".mask($dtcontrato,'##/##/####')."'></td>";
		print "</TR><TR>";		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Plano".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";

		print "<select class='select2' name='plano' id='idplano'>";  
		$sql="select plano from carne_contratos where id =".$_GET['cod']."";
		$commit = mysqli_query($conec->con,$sql) or die ('Erro na Query '.$sql);
		$rowR = mysqli_fetch_array($commit);		
		print "<option value=-1>"."Selecione o Plano"."</option>";
				$sql="Select id,descricao from carne_tipoplano where unidade =".$_SESSION['s_local']." order by id";
				$commit = mysqli_query($conec->con,$sql) or die ('Erro na Query '.$sql);;
							while($rowB = mysqli_fetch_array($commit)){
						print "<option value=".$rowB["id"]."";
                        			if ($rowB['id'] == $rowR['plano'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB['descricao']."</option>";
					}
			print "</select>";
		print "</TR></TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Dia do Vencimento".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='diavencto' class='text4' id='iddiavencto' maxlength='2' OnKeyPress=\"javascript:formatarcampo('##', this)\" value='".$row['diavencto']."'></td>";
		print "</TR><TR>";

		if($row['status']=='0'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($row['status']=='1'){ $selected2 = " selected"; } else { $selected2 = "";}
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Status".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='status' id='idstatus'>";  
		print "<option value='0'".$selected1.">Ativo</option>";  
		print "<option value='1'".$selected2.">Inativo</option>";  
		print "</select>";  
		print "</TR></TR>";
		
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' $clasbutton value='".TRANS('BT_ALTER')."' name='submit' $disabled>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";


	}

		// Vari�veis convertidas
		if(isset($_POST['codigo'])) {
				
				$dtcontrato = Fdate($_POST['dtcontrato']);
				$zero = 0;
				$registro = date("Y-m-d H:i:s");
		}

	// Incluindo registro com INSERT
	if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_CAD')))) {	

	    $query = "SELECT prox_cartao_desc,prox_contrato FROM config";
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA EXECU��O DA QUERY DE MAX ID!');
       	$config = mysqli_fetch_array($resultado);
       	$proximocartao = $config['prox_cartao_desc']+1;
       	$proximocontra = $config['prox_contrato']+1;
		
		
		
		$query = "INSERT INTO carne_contratos (nrocontrato,plano,datacontrato,diavencto,status,idtitular,unidade,registro)".
				" values ('".$_POST['codigo']."','".$_POST['plano']."','".$dtcontrato."','".$_POST['diavencto']."','".$_POST['status']."','".$_POST['titular']."',".$_SESSION['s_local'].",'".$registro."')";
					
		$resultado = mysqli_query($conec->con,$query) or die('Erro no Insert '.$query);
		if ($resultado == 0)
		{
			$aviso = TRANS('ERR_INSERT');
		}
		else
		{
			$aviso = TRANS('OK_INSERT');
			
			$query = "update carne_titular set nrocontrato=".$_POST['codigo']." where id =".$_POST['titular']."";
			$resultado = mysqli_query($conec->con,$query) or die('Erro no Update '.$query);

			$query2 	= "UPDATE config SET prox_contrato='".$proximocontra."'";
			$resultado2 = mysqli_query($conec->con,$query2) or die('Erro na query: '.$query2);
			
		}

		if($aviso != TRANS('OK_INSERT')) {
			echo "<script>mensagem('".$aviso."');</script>";
		} 
		
		echo "<script>redirect('abas.php?cod=".$_POST['titular']."');</script>";

	} else

	// Alterando o registro com UPDATE
	if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_ALTER')))) {	
					
		$query2 = "UPDATE carne_contratos SET nrocontrato='".$_POST['codigo']."',plano='".$_POST['plano']."', datacontrato='".$dtcontrato."', diavencto='".$_POST['diavencto']."', status='".$_POST['status']."', idtitular='".$_POST['titular']."', unidade=".$_SESSION['s_local']." where id=".$_POST['contrato']."";		
		
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

			echo "<script>redirect('abas.php?cod=".$_POST['titular']."');</script>";

	}

	print "</table>";

?>

<script language="JavaScript">

function VerContrato(nrocontrato) { 

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

	  ajax.open("GET", "vercontrato.php?cod="+nrocontrato, true); 
	  ajax.onreadystatechange = function() { 
	  //enquanto estiver processando...emite a msg de carregando 
	  if(ajax.readyState == 1) {
	   mensagem( "Carregando...!" );  
	  } 

	  var nrocontrato=gE('idcodigo');
	  
	  
	  //alert(ajax.readyState+' '+ajax.responseText+' '+ajax.status);
	  
	  //ap�s ser processado - chama fun��o processXML que vai varrer os dados 
	 if(ajax.readyState == 4 ) { 
		if(ajax.status==200){

			r=ajax.responseText;
			rcontrato = r.substring(0, (i = r.indexOf(',')));
			r = r.substring(++i);
			rnometitular = r.substring(0, (i = r.indexOf(',')));

			if(rcontrato > 0){
				mensagem('Nro de Contrato '+rcontrato+' j� existe para '+rnometitular);
				nrocontrato.value = 0; 
			}

			
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
	} 
	

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
		if (ok) var ok = validaForm('idcodigo','','Nro do Contrato',1);
		if (ok) var ok = validaForm('iddtcontrato','','Data do Contrato',1);
		if (ok) var ok = validaForm('iddiavencto','','Dia do Vencimento',1);		

		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
