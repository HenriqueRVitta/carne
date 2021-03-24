<?php 
header ('Content-type: text/html; charset=ISO-8859-1');

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
	
	print "<BODY onLoad='document.configuracaobancaria.bancoemissor.focus();'bgcolor='".BODY_COLOR."'>";


	$fecha = "";
	if (isset($_GET['popup'])) {
		$fecha = "window.close()";
	} else {
		$fecha = "history.back()";
	}

	$localpagto = 0;
	
    $disabled = '';
    $clasbutton = " class='button'";
	
	print "<BR><B><font size=4>"."Administra&ccedil;&atilde;o Configura&ccedil;&otilde;es Banc&aacute;ria"."</font></B><BR>";

	print "<FORM name='configuracaobancaria' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";

		print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

       	$query = "SELECT tm_color_topo, tm_color_td, tm_color_body from styles";
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA EXECU��O DA QUERY DE MAX ID!');
       	$style = mysqli_fetch_array($resultado);

       	$query = "SELECT bancoemissor, nroagencia, digitoagencia, nroconta, digitoconta, nrocontrato, infocliente1, infocliente2, infocliente3, instrucaocaixa1, instrucaocaixa2, instrucaocaixa3, dirarquivoretorno, dirarquivoremessa, carteiracobranca, idretornobanco, localpagto FROM config";

		if (isset($_POST['bancoemissor']) && !empty($_POST['bancoemissor'])) {
       		$query = "SELECT id, nome, bancoemissor, nroagencia, digitoagencia, nroconta, digitoconta, nrocontrato, infocliente1, infocliente2, infocliente3, instrucaocaixa1, instrucaocaixa2, instrucaocaixa3, dirarquivoretorno, dirarquivoremessa, carteiracobranca, idretornobanco, localpagto FROM carne_bancos where nome = '".$_POST['bancoemissor']."'";
		}
       	
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
		$config = mysqli_fetch_array($resultado);
		
		$localpagto = $config['localpagto'];
		
		print "<BR><b><font size=2 color='blue'>"."Edi&ccedil;&atilde;o Par&acirc;metros de Configura&ccedil;&atilde;o"."</b></font><BR>";		
		print "<TR>";
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Banco para emissao do Boleto".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		
		print "<select class='select2' name='bancoemissor' id='bancoemissor' >";

		if (isset($_POST['bancoemissor']) && !empty($_POST['bancoemissor'])) {
			print "<option value='".$_POST['bancoemissor']."'>".$_POST['bancoemissor']."</option>";  
		} else {

			print "<option value=''></option>";  
			
		}
		print "<option value='Sicoob'>Sicoob</option>";  
		print "<option value='Bradesco'>Bradesco</option>";  
		
		print "</select>";
		print "</TR><TR>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro da Ag&ecirc;ncia e d&iacute;gito".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nroagencia' maxlength='7' class='text4' id='idnroagencia' onkeyup=\"maskIt(this,event,'#######')\" value='".$config['nroagencia']."'".">
		<INPUT type='text' class='text4' name='digitoagencia' maxlength='1' style='width:25px; text-align:center;' id='iddigitoagencia' onkeyup=\"maskIt(this,event,'#')\" value='".$config['digitoagencia']."'".">Dig</td>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro da Conta e d&iacute;gito".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='nroconta' maxlength='10' id='idnroconta' value='".$config['nroconta']."'".">
		<INPUT type='text' class='text4' name='digitoconta' maxlength='1' style='width:25px; text-align:center;' id='iddigitoconta' onkeyup=\"maskIt(this,event,'#')\" value='".$config['digitoconta']."'".">Dig</td>";
		print "</TR><TR>";		

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro do Contrato/Conv&ecirc;nio".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nrocontrato' maxlength='7' class='text4' id='idnrocontrato' value='".$config['nrocontrato']."'></td>";
		
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Pasta dos Arquivos de Remessa Banc&aacute;rio".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='dirarquivoremessa' maxlength='250' id='iddirarquivoremessa' value='".$config['dirarquivoremessa']."'></td>";
		
		// Campo oculto Diretorio do Arquivo Retorno
		// desixar para o usu�rio escolher a pasta quando for importar o retorno do banco
		print "<INPUT type='hidden' class='text3' name='dirarquivoretorno' maxlength='250' id='iddirarquivoretorno' value='".$config['dirarquivoretorno']."'></td>";

		print "</TR><TR>";

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Carteira de Cobran�a".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		
		if($config['carteiracobranca']=='Com Registro') { $comRegistro = " selected"; } else { $comRegistro = "";}
		if($config['carteiracobranca']=='Sem Registro') { $semRegistro = " selected"; } else { $semRegistro = "";}
		
		print "<select class='select2' name='carteiracobranca' id='idcarteiracobranca' >";
		print "<option value=''></option>";  
		print "<option value='1'".$comRegistro.">Com Registro</option>";  
		print "<option value='2'".$semRegistro.">Sem Registro</option>";  
		
		print "</select>";
		
		
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Informa&ccedil;&atilde;o para o Cliente 1".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='infocliente1' maxlength='40' id='idinfocliente1' value='".$config['infocliente1']."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Informa&ccedil;&atilde;o para o Cliente 2".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='infocliente2' maxlength='40' id='idinfocliente2' value='".$config['infocliente2']."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Informa&ccedil;&atilde;o para o Cliente 3".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3'  name='infocliente3' maxlength='40' id='idinfocliente3' value='".$config['infocliente3']."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Instru&ccedil;&otilde;es para o Caixa 1".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='instrucaocaixa1' maxlength='40' id='idinstrucaocaixa1' value='".$config['instrucaocaixa1']."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Instru&ccedil;&otilde;es para o Caixa 2".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='instrucaocaixa2' maxlength='40' id='idinstrucaocaixa2' value='".$config['instrucaocaixa2']."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Instru&ccedil;&otilde;es para o Caixa 3".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='instrucaocaixa3' maxlength='40' id='idinstrucaocaixa3' value='".$config['instrucaocaixa3']."'></td>";
		print "</TR><TR>";
		

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Identifica&ccedil;&atilde;o do Titular no Arquivo Retorno".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		
		if($config['idretornobanco']==1) { $Codigo = " selected"; } else { $Codigo = "";}
		if($config['idretornobanco']==2) { $NroContrato = " selected"; } else { $NroContrato = "";}
		
		print "<select class='select2' name='retornobanco' id='idretornobanco' >";
		print "<option value='1'".$Codigo.">ID Titular</option>";  
		print "<option value='2'".$NroContrato.">CPF</option>";  
		
		print "</select>";
		
		
		print "</TR><TR>";

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Local de Pagamento para esse Banco".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";

		print "<select class='select2' name='localpagto' id='localpagto'>";  
		$sql="select id,descricao from carne_localpagto where id =".$localpagto."";
		$commit = mysqli_query($conec->con,$sql) or die ('Erro na Query '.$sql);
		$rowR = mysqli_fetch_array($commit);		
		print "<option value=-1>"."Selecione Local"."</option>";
				$sql="select id, descricao from carne_localpagto where unidade =".$_SESSION['s_local']." order by id";
				$commit = mysqli_query($conec->con,$sql) or die ('Erro na Query '.$sql);;
							while($rowB = mysqli_fetch_array($commit)){
						print "<option value=".$rowB["id"]."";
                        			if ($rowB['id'] == $rowR['id'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB['descricao']."</option>";
					}
			print "</select>";
		print "</TR>";
		
		if(liberamenu('Alterar Configuracoes')=="N"){
	    	$disabled = " disabled='disabled'";
	    	$clasbutton = " class='buttonDisabled'";
	    }
				
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' $clasbutton id='idAlterar' $disabled value='".TRANS('BT_ALTER')."' name='submit'></TD>";
		print "<TD align='left' width='40%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";

		$dtvencto = date("Y-m-d H:i:s");
	
	// Alterando o registro com UPDATE
	if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_ALTER')))) {	

		$query2 = "select bancoemissor, localpagto from carne_bancos where nome = '".$_POST['bancoemissor']."'";
		$resultado2 = mysqli_query($conec->con,$query2) or die('Erro na query: '.$query2);

		$linha=mysqli_num_rows($resultado2);

		$barra = '"\"';
		
		if($_POST['carteiracobranca']=='1'){ $carteira = "Com Registro"; } else { $carteira = "Sem Registro"; }
		$dirarquivoremessa = str_replace("\\", "/",$_POST['dirarquivoremessa']);
		
		if ($linha == 0)
		{

			$queryinsert = "insert into carne_bancos (nome,bancoemissor,nroagencia,digitoagencia,nroconta,digitoconta,nrocontrato,infocliente1,infocliente2,infocliente3,instrucaocaixa1,instrucaocaixa2,instrucaocaixa3,dirarquivoretorno,dirarquivoremessa,carteiracobranca,idretornobanco,localpagto)".
			" values ("."'".$_POST['bancoemissor']."','".$_POST['bancoemissor']."','".$_POST['nroagencia']."','".$_POST['digitoagencia']."','".$_POST['nroconta']."','".
			$_POST['digitoconta']."','".$_POST['nrocontrato']."','".
			$_POST['infocliente1']."','".$_POST['infocliente2']."','".$_POST['infocliente3']."','".
			$_POST['instrucaocaixa1']."','".$_POST['instrucaocaixa2']."','".$_POST['instrucaocaixa3']."','".
			$_POST['dirarquivoretorno']."','".$dirarquivoremessa."','".$carteira."',".$_POST['retornobanco'].",".$_POST['localpagto'].")";
			
			$resultadoinsert = mysqli_query($conec->con,$queryinsert) or die('Erro na query: '.$queryinsert);
			
				if ($resultadoinsert == 0)
				{
					$aviso = TRANS('ERR_EDIT');
					
				} else {
				
						echo "<script>mensagem('Dados salvo com sucesso');</script>";
						
				}

		} else {

			$query2 = "UPDATE carne_bancos SET nome = '".$_POST['bancoemissor']."', bancoemissor='".$_POST['bancoemissor']."', nroagencia='".$_POST['nroagencia']."', digitoagencia='".$_POST['digitoagencia']."', nroconta='".$_POST['nroconta']."', digitoconta='".$_POST['digitoconta']."', nrocontrato='".$_POST['nrocontrato']."', infocliente1='".$_POST['infocliente1']."', infocliente2='".$_POST['infocliente2']."', infocliente3='".$_POST['infocliente3']."', instrucaocaixa1='".$_POST['instrucaocaixa1']."', instrucaocaixa2='".$_POST['instrucaocaixa2']."', instrucaocaixa3='".$_POST['instrucaocaixa3']."', dirarquivoretorno='".$_POST['dirarquivoretorno']."', dirarquivoremessa='".$dirarquivoremessa."', carteiracobranca='".$carteira."', idretornobanco = ".$_POST['retornobanco'].", localpagto = ".$_POST['localpagto']." Where nome = '".$_POST['bancoemissor']."'";
			
			$resultado2 = mysqli_query($conec->con,$query2) or die('Erro na query: '.$query2);

				if ($resultado2 == 0)
				{
					$aviso = TRANS('ERR_EDIT');
					
				} else {
				
						echo "<script>mensagem('Dados salvo com sucesso');</script>";
						
				}
			
		}		

			
			echo "<script>redirect('".$_SERVER['PHP_SELF']."');</script>";
						
	}

	print "</table>";

?>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script language="JavaScript" type="text/javascript">
/* Formatacao para qualquer mascara */

$(document).ready(function() {

    $("#bancoemissor").change(function(){
    	
        var banco = $("#bancoemissor").val();
		
        if(banco != "") {

            $.ajax({

                type:"POST",

                url: "consultadadosbanco.php",

                dataType:"html",

                data:"banco="+banco,

                success:function(data) {

	               	$("#idnroagencia").html(data);
                	$("#iddigitoagencia").html(data);
                	
	               	$("#idnroconta").html(data);
                	$("#iddigitoconta").html(data);

                	$("#idnrocontrato").html(data);

	               	$("#iddirarquivoremessa").html(data);

	               	$("#idinfocliente1").html(data);
	               	$("#idinfocliente2").html(data);
	               	$("#idinfocliente3").html(data);
	               	
	               	$("#idinstrucaocaixa1").html(data);
	               	$("#idinstrucaocaixa2").html(data);
	               	$("#idinstrucaocaixa3").html(data);
	               	
                }

            });

        }
        
    });  

});


function BuscaBanco(Id, pStr) {

	var b = document.getElementById(Id);
	var banco = b.value

    if(banco != ""){ 

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
       	   	
	 if(ajax) {

		  ajax.open("GET", "consultadadosbanco.php?banco="+banco, true); 
		  ajax.onreadystatechange = function(data) { 
					return data;
			};
			  
	 }

	 ajax.send(null); 
	 
    }

}

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
		var ok = validaForm('idnroconta','','Nro da Conta',1);
		if (ok) var ok = validaForm('iddigitoconta','','Digito da Conta',1);
		if (ok) var ok = validaForm('idnroagencia','','Nro Agencia',1);		
		if (ok) var ok = validaForm('iddigitoagencia','','Digito da Agencia',1);		
		if (ok) var ok = validaForm('idnrocontrato','','Nro Contrato',1);		

		var bancoemissor = document.getElementById("bancoemissor").value;
		if(bancoemissor == ''){
			alert('Banco deve ser informado');
			ok = false;
		}
		
		return ok;
	}
	
-->
</script>


<?php 
print "</body>";
print "</html>";

