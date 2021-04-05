<?php
/*      Copyright 2014 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 04/04/2014 12:00

		* Modulo Carne *
*/

	session_start();
	
	include ('../../includes/include_geral.inc.php');
	include ('../../includes/include_geral_II.inc.php');
	include ('../../includes/classes/paging.class.php');
	include ("../../includes/javascript/funcoes.js");

	$conec = new conexao;
	$conec->conecta('MYSQL');
	
	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	
?>

<head> 
<title> Administra&ccedil;&atilde;o de Contratos e Carn&ecirc;</title> 
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

function MesAno(Id, pStr, pFmt){
	d = document.getElementById(Id);
	if (d.value != ""){ 
	if (d.value.length < 06){
	alert("Dados incorreta!\nDigite corretamente : mm/aaaa !");
	d.value="";
	d.focus(); 
	return false;
	}else{
	var mes = d.value.substring(0,2);
	var ano = d.value.substring(3,10);

	if(mes <= 0 || mes > 12) {
		
		alert("ALERTA DE ERRO!!\n\n" + pStr + " Mes incorreto, favor corrigir.");
		d.value="";
		d.focus(); 
		return false;
	}

	if(ano < 2000) {
		
		alert("ALERTA DE ERRO!!\n\n" + pStr + " Ano incorreto, favor corrigir.");
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

function validacpfcnpj() {

	if(document.getElementById("selcpf").checked == true) {
		var cpf = document.getElementById("idcpf").value;
		
		if(cpf != '') {
	    	if(!valida_cpf(document.getElementById('idcpf').value)) {
	    		alert('CPF Invalido');
	    		document.getElementById("idcpf").value = '';
	        	document.getElementById("idcpf").focus();
	    	}
		}
		
	}
	
	if(document.getElementById("selcnpj").checked == true) {
		var cpf = document.getElementById("idcpf").value;
		
		if(cpf != '') {
	    	if(!validarCNPJ(document.getElementById('idcpf').value)) {
	    		alert('CNPJ Invalido');
	    		document.getElementById("idcpf").value = '';
	        	document.getElementById("idcpf").focus();
	    	}
		}
		
	}

	
}

function validarCNPJ(cnpj) {
	 
    cnpj = cnpj.replace(/[^\d]+/g,'');
 
    if(cnpj == '') return false;
     
    if (cnpj.length != 14)
        return false;
 
    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" || 
        cnpj == "11111111111111" || 
        cnpj == "22222222222222" || 
        cnpj == "33333333333333" || 
        cnpj == "44444444444444" || 
        cnpj == "55555555555555" || 
        cnpj == "66666666666666" || 
        cnpj == "77777777777777" || 
        cnpj == "88888888888888" || 
        cnpj == "99999999999999")
        return false;
         
    // Valida DVs
    tamanho = cnpj.length - 2
    numeros = cnpj.substring(0,tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0))
        return false;
         
    tamanho = tamanho + 1;
    numeros = cnpj.substring(0,tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1))
          return false;
           
    return true;
    
}

	// Verifica CPF e CNPJ
    function SelecionaCPF() {

			var cpf = document.getElementById("idcpf").value;

			document.getElementById("idcpf").maxLength = 11;
			
        	document.getElementById("selcnpj").checked = false;
        	document.getElementById("selcpf").checked = true;

			if(cpf != '') {
	        	if(!valida_cpf(document.getElementById('idcpf').value)) {
	        		alert('CPF Invalido');
	        		document.getElementById("idcpf").value = '';
	           	}
			}
        	
        	document.getElementById("idcpf").focus();
        	
    }
    
    function SelecionaCNPJ() {

		var cnpj = document.getElementById("idcpf").value;

		document.getElementById("idcpf").maxLength = 14;
		
    	document.getElementById("selcnpj").checked = true;
    	document.getElementById("selcpf").checked = false;

		if(cnpj != '') {

	    	if(!validarCNPJ(document.getElementById('idcpf').value)) {
	    		alert('CNPJ Invalido');
	    		document.getElementById("idcpf").value = '';
	       	}
			
		}
	    	
    	document.getElementById("idcpf").focus();
    	
    }
    	

	// Validando campos do Titular
	function valida(){
		var ok = validaForm('idnometitular','','Nome do Cliente',1);
		if (ok) var ok = validaForm('idcidade','','Cidade',1);		
		if (ok) var ok = validaForm('iddtnasctitular','','Data de Nascimento',1);		

		//if (ok) var ok = validaForm('idcpf','','CPF',1);		
		
		return ok;
	}

	// Validando campos do Dependente
	function validadep(){
		var ok = validaForm('idnomedep','','Nome do Dependente',1);
		if (ok) var ok = validaForm('iddtnasc','','Data Nascimento Dependente',1);
		return ok;
	}

	// Validando campos do Contrato
	function validacon(){
		var ok = validaForm('iddtcontrato','','Data do Controato',1);
		if (ok) var ok = validaForm('iddiavencto','','Dia de Vencimento',1);
		return ok;
	}

</script>

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
	    alert("Esse browser n�o tem recursos para uso do Ajax"); 
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
	  
	  //ap�s ser processado - chama fun��o processXML que vai varrer os dados 
	 if(ajax.readyState == 4 ) { 
		if(ajax.status==200){

			r=ajax.responseText;
			cretorno = r.substring(0, (i = r.indexOf(',')));
			r = r.substring(++i);
			npermitido = r.substring(0, (i = r.indexOf(',')));

			if(cretorno=='true') {
				redirect("incluirdependentes.php?action=incluir&cellStyle=true&cod="+cod);				
			} else {
				
					alert('Quantidade m�xima de '+npermitido+' Dependentes por Titular atingido.');
								
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
	}//end function Dados 

	//fun��o respons�vel pelo nome dos elementos do form
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
 arAbas[2] = new stAba('td_manutencao','div_contrato'); 

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

<script type="text/javascript" >
    
    function limpa_formulario_cep() {
            //Limpa valores do formul�rio de cep.
            document.getElementById('idendereco').value=("");
            document.getElementById('idbairro').value=("");
            document.getElementById('idcidade').value=("");
            document.getElementById('iduf').value=("");
            //document.getElementById('ibge').value=("");
    }

    function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('idendereco').value=(conteudo.logradouro);
            document.getElementById('idbairro').value=(conteudo.bairro);
            document.getElementById('idcidade').value=(conteudo.localidade);
            document.getElementById('iduf').value=(conteudo.uf);
            //document.getElementById('ibge').value=(conteudo.ibge);
        } //end if.
        else {
            //CEP n�o Encontrado.
            limpa_formulario_cep();
            alert("CEP n�o encontrado.");
        }
    }
        
    function pesquisacep(valor) {

        //Nova vari�vel "cep" somente com d�gitos.
        var cep = valor.replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Express�o regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('idendereco').value="...";
                document.getElementById('idbairro').value="...";
                document.getElementById('idcidade').value="...";
                document.getElementById('iduf').value="...";
                //document.getElementById('ibge').value="...";

                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

                //Insere script no documento e carrega o conte�do.
                document.body.appendChild(script);

            } //end if.
            else {
                //cep � inv�lido.
                limpa_formulario_cep();
                alert("Formato de CEP inv�lido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formul�rio.
            limpa_formulario_cep();
        }
    };

</script>

<?php 

print "</head>";


if((isset($_GET['action']) && $_GET['action']=="editar") && empty($_POST['submit'])) {	
print "<body onLoad=\"AlternarAbas('td_consulta','div_consulta')\">"; 
} 

if((isset($_GET['editadependente']) && $_GET['editadependente']=="true") && empty($_POST['submit'])) {
print "<body onLoad=\"AlternarAbas('td_consulta','div_consulta')\">"; 
} else {
	
	if((isset($_GET['alterarcontrato']) && $_GET['alterarcontrato']=="true") && empty($_POST['submit'])) {
		print "<body onLoad=\"AlternarAbas('td_manutencao','div_contrato')\">"; 
	} else {
		print "<body onLoad=\"AlternarAbas('td_cadastro','div_cadastro')\">"; 
	}
}

	
	$fecha = "";
	if (isset($_GET['popup'])) {
		$fecha = "window.close()";
	} else {
		$fecha = "history.back()";
	}

	if(isset($_GET['cod'])) { $nCod = $_GET['cod']; } else { $nCod=0;}
	
print "<h2 align='left'><B><font size='4'>Administra&ccedil;&atilde;o de Contratos</font></B></h2>";
print "<input type='text' name='titular' id='idtitular' value='".$nCod."' hidden='true'>";

print "<table width='100%' height='480' cellspacing='0' cellpadding='0' 
border='0' style='border-left: 1px solid #000000;'> ";
print "<tr>"; 
print "<td height='20' width='200' class='menu' id='td_cadastro' align='center'
  onClick=\"AlternarAbas('td_cadastro','div_cadastro')\"> 
   Titular 
  </td>";
print "  <td height='20' width='200' class='menu' id='td_consulta' align='center'
  onClick=\"AlternarAbas('td_consulta','div_consulta')\"> 
   Dependentes 
  </td>"; 
print "  <td height='20' width='200' class='menu' id='td_manutencao' align='center'
  onClick=\"AlternarAbas('td_manutencao','div_contrato')\"> 
   Plano e/ou Contrato 
  </td>"; 
print "<td width='460' style='border-bottom: 1px solid #000000'> 
   &nbsp; 
  <td>"; 
print "</tr>"; 
print "<tr>"; 
print "<td height='460' class='tb-conteudo' colspan='4'>";

		$maxidtitular = 0;

		if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {
	      	$query = "SELECT max(id) as id FROM carne_titular ";
			$resultado = mysqli_query($conec->con,$query) or die('ERRO NA EXECU��O DA QUERY DE MAX ID 1 !');
	       	$maxidtitular = mysqli_fetch_array($resultado);
		}

		$queryRR = "SELECT dtnascdepobriga FROM config";
		$resultadoRR = mysqli_query($conec->con,$queryRR) or die('ERRO NA EXECU��O DA QUERY DE MAX ID 01!');
       	$configuraRR = mysqli_fetch_array($resultadoRR);
		$dtnascdepobriga = $configuraRR['dtnascdepobriga'];
		

// Aba Cadastrar Titular
print "<div id='div_cadastro' class='conteudo' style='display: none'>";


		// Alterar Titular
		if ((isset($_GET['action'])) && $_GET['action']=='incluirdependente') {

			$query2 = "Update carne_dependente set idtitular=".$_GET['cod']." WHERE id='".$_GET['coddep']."'";
			$resultado2 = mysqli_query($conec->con,$query2) or die('Erro ... '.$query2);

			if ($resultado2 == 0)
			{
					$aviso = TRANS('ERR_DEL');
			}
			else
			{
					$aviso = TRANS('OK_DEL');
			}
			
		
		}

		// Apagando Titutar, dependentes e contrato
		if ((isset($_GET['action'])) && $_GET['action']=='excluir') {

			$query2 = "Update carne_dependente set idtitular=0 WHERE id='".$_GET['cod']."'";
			$resultado2 = mysqli_query($conec->con,$query2) or die('Erro ... '.$query2);

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
		
		$PAGE = new paging_("PRINCIPAL");
		$PAGE->setRegPerPage($_SESSION['s_page_size']);
		$search = "";
		$cellStyle = "cellpadding='5' cellspacing='1'";
		
		print "<TABLE style='font-size:15px;' border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";
		

		// vari�vel que controla permiss�es dos bot�es para incluir, editar e excluir  do usu�rio
		$disabled = '';
		$clasbutton = " class='button'";

		if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") ) && empty($_POST['submit'])) {

		print "<FORM name='abastitular' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";
			
			
		$query = "SELECT max(id) as id FROM carne_titular ";
		$resid = mysqli_query($conec->con,$query) or die('ERRO NA EXECU��O DA QUERY DE MAX ID 2!');
       	$maxid = mysqli_fetch_array($resid);
       	
       	print "<BR><b><font size=2 color='blue'>"."Inclus&atilde;o do Cliente"."</b></font><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo".":</TD>";
		
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='text4' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($maxid['id']+1,6)."' readonly='true' ></td>";

		print "</TR><TR>";

		$proximocarne = '';
		$ready2 = "";
		
		// Se utiliza controle por carn�
		if($_SESSION['s_utilizacontrato']=='Nao') {
		    $queryR = "SELECT prox_contrato,dtnascdepobriga FROM config";
			$resultado = mysqli_query($conec->con,$queryR) or die('ERRO NA EXECU��O DA QUERY DE MAX ID!');
	       	$configura = mysqli_fetch_array($resultado);
	       	$proximocarne = $configura['prox_contrato']+1;

	       	if($_SESSION['s_utilizacontrato']=='Nao') { $ready2 = "readonly=true"; } else { $ready2 = ""; } 
	       	
		}
			
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Contrato;".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nrocarne' maxlength='9' class='text4' onkeyup=\"maskIt(this,event,'#######')\" id='idnrocarne' value='".$proximocarne."'".$ready2."></td>";
		print "</TR></TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro da Carteira".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nrocarteira' maxlength='45' class='text2' id='idnrocarteira' value=''></td>";
		print "</TR></TD>";
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome do Cliente".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='nometitular' maxlength='45' id='idnometitular'></td>";
		print "</TR><TR>";		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Sexo".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='sexo' id='idsexo'>";  
		print "<option value='M'>Masculino</option>";  
		print "<option value='F'>Feminino</option>";  
		print "</select>";  
		print "</TR></TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Cep".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='cep' id='idcep' onBlur='pesquisacep(this.value);'  maxlength='10' OnKeyPress=\"javascript:formatarcampo('##.###-###', this)\"></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Endere&ccedil;o".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='endereco' maxlength='45' class='text3' id='idendereco'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."N&uacute;mero".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='numero' maxlength='10' class='text4' id='idnumero'></td>";
		print "</TR><TR>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Bairro".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='bairro' maxlength='45' class='text3' id='idbairro'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Cidade".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='cidade' maxlength='45' class='text3' id='idcidade'></td>";
		print "</TR><TR>";
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."UF".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		
		print "<select style='width:50px; height:30px; font-size:14px' name='uf' id='iduf'>";  
		print "<option value='AC'>AC</option>";  
		print "<option value='AL'>AL</option>";  
		print "<option value='AM'>AM</option>";  
		print "<option value='AP'>AP</option>";  
		print "<option value='BA'>BA</option>";  
		print "<option value='CE'>CE</option>";  
		print "<option value='DF'>DF</option>";  
		print "<option value='ES'>ES</option>";  
		print "<option value='GO'>GO</option>"; 
		print "<option value='MA'>MA</option>";  
		print "<option value='MG' selected>MG</option>";  
		print "<option value='MS'>MS</option>";  
		print "<option value='MT'>MT</option>";  
		print "<option value='PA'>PA</option>";  
		print "<option value='PB'>PB</option>";  
		print "<option value='PE'>PE</option>";  
		print "<option value='PI'>PI</option>";  
		print "<option value='PR'>PR</option>";  
		print "<option value='RJ'>RJ</option>";  
		print "<option value='RN'>RN</option>";  
		print "<option value='RO'>RO</option>";  
		print "<option value='RR'>RR</option>";  
		print "<option value='RS'>RS</option>"; 
		print "<option value='SC'>SC</option>";  
		print "<option value='SE'>SE</option>";  
		print "<option value='SP'>SP</option>";  
		print "<option value='TO'>TO</option>";  
		print "</select>";  
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Fone Residencial".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='foneres' maxlength='45' onkeyup=\"maskIt(this,event,'(##)####-###############')\" class='text3' id='idfoneres'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Fone Comercial".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='fonecom' class='text3' maxlength='45' id='idfonecom' onkeyup=\"maskIt(this,event,'(##)####-###############')\"></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Celular".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='celular' class='text3' maxlength='45' id='idcelular' onkeyup=\"maskIt(this,event,'(##)#####-#####')\"></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Nascimento".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtnasctitular' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddtnasctitular' onBlur='return doDateVenc(this.id,this.value, 4)'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro de Filhos".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='qtdefilhos' class='text4' id='idqtdefilhos' onkeyup=\"maskIt(this,event,'##')\" value='00'></td>";
		print "</TR><TR>";		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Escolaridade".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='escolaridade' id='idescolaridade'>";  
		print "<option value='1'>N&atilde;o informado</option>";  
		print "<option value='2'>Analfabeto</option>";  
		print "<option value='3'>1&ordm; Grau Incompleto</option>";  
		print "<option value='4'>1&ordm; Grau Completo</option>";  
		print "<option value='5'>2&ordm; Grau Incompleto</option>";  
		print "<option value='6'>2&ordm; Grau Completo</option>";  
		print "<option value='7'>Superior Incompleto</option>";  
		print "<option value='8'>Superior Completo</option>";  
		print "</select>";  
		print "</TD></TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Local Trabalho".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='localtrab' maxlength='45' class='text3' id='idlocaltrab'></TD>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Profiss&atilde;o".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='profissao' maxlength='45' class='text3' id='idprofissao'></TD>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Identidade".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='identidade' maxlength='45' class='text3' id='idrg'></TD>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>";
		print "<input type='radio' name='selcpf' id='selcpf' value='1' onChange='SelecionaCPF()' checked> CPF";
  		print "<input type='radio' name='selcnpj' id='selcnpj' value='2' onChange='SelecionaCNPJ()'> CNPJ";
  		print "</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='cpf' class='text3' id='idcpf' onkeyup=\"maskIt(this,event,'##############')\" maxlength='11' onChange='validacpfcnpj()'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Email ".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='email' maxlength='100' class='text2' id='idemail'></td>";
		print "</TR><TR>";
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Estado Civil".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='estcivil' id='idestcivil'>";  
		print "<option value='1'>N&atilde;o informado</option>";  
		print "<option value='2'>Solteiro</option>";  
		print "<option value='3'>Casado</option>";  
		print "<option value='4'>Divorciado</option>";  
		print "<option value='5'>Separado</option>";  
		print "<option value='6'>Vi&uacute;vo</option>";  
		print "<option value='7'>Amasiado</option>";  
		print "<option value='8'>Indefinido</option>";  
		print "</select>";  
		print "</TD></TR><TR>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Registro".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='prontuario' maxlength='7' class='text4' onkeyup=\"maskIt(this,event,'#######')\" id='idprontuario' value='0'>
		<input type='button' value='Consulta' name='tipo1' class='minibutton2' onClick=\"javascript:popup_consulta('consultapacientes.php?popup=true&nome='+document.getElementById('idnometitular').value)\"></td>";
		print "</TR><TR>";

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Grupo".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		
		print "<select class='select2' name='grupo' id='idgrupo' onBlur='return Dados(this.value)'>";  
				print "<option value=-1>"."Selecione o Grupo"."</option>";
					$sql="Select id,descricao from carne_grupo where unidade = ".$_SESSION['s_local'];
					$commit = mysqli_query($conec->con,$sql);
					$i=0;
					while($row = mysqli_fetch_array($commit)){
						print "<option value=".$row['id'].">".$row['descricao']."</option>";
						$i++;
					}
				print "</select>";
		
		print "</TD></TR><TR>";
		
		$dataParcela = date("d/m/Y");
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inicial do Parcelamento".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datainicial' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddatainicial' onBlur='return doDateVenc(this.id,this.value, 4)' value='".$dataParcela."'></td>";
		print "</TR><TR>";
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."&Uacute;ltimo M&ecirc;s/Ano Carn&ecirc;".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='ultimomescarne' class='text4' onkeyup=\"maskIt(this,event,'##/####')\" id='idultimomescarne' onBlur='return MesAno(this.id,this.value, 4)' value=''></td>";
		print "</TR><TR>";
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome do Pai".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nomepai' maxlength='45' class='text3' id='idnomepai'></td>";
		print "</TR></TD>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome da M&atilde;e".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nomemae' maxlength='45' class='text3' id='idnomemae'></td>";
		print "</TR><TR>";

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Situa&ccedil;&atilde;o".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='situacao' id='idsituacao'>";  
		print "<option value='ATIVO'>Ativo</option>";
		print "<option value='INATIVO'>Inativo</option>";
		print "</select>";
		print "</TD></TR><TR>";

							
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inativo".":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtinativo' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddtinativo' onBlur='return doDateVenc(this.id,this.value, 4)' value=''></td>";
			print "</TR><TR>";
	
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."OBS".":</TD>";
			print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='obs' maxlength='200' id='idobs' value=''></td>";
			print "</TR><TR>";		
		

		print "</TR><TR>";

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Vendedor".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		
		print "<select class='select2' name='vendedor' id='idvendedor' onBlur='return Dados(this.value)'>";  
				if(isset($_SESSION['vendedor']) && $_SESSION['vendedor'] > 0 ) {
					$sql="Select id,nome from carne_vendedor where id = ".$_SESSION['vendedor'];
				} else {
					print "<option value=-1>"."Selecione o Vendedor"."</option>";
					$sql="Select id,nome from carne_vendedor where ativo = 1 and unidade = ".$_SESSION['s_local'];
				}

					$commit = mysqli_query($conec->con,$sql);
					$i=0;
					while($row = mysqli_fetch_array($commit)){
						print "<option value=".$row['id'].">".$row['nome']."</option>";
						$i++;
					}
				print "</select>";
		
		print "</TD></TR><TR>";
			
		
		
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='".TRANS('BT_CAD')."' name='submit'></TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";
			
		}
		

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {
	print "</form>";
		
	print "<FORM name='abastitular' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";
		
		// Rolar o scroll da tela para o in�cio
		//echo "<script>$('html, body').scrollTop(0);</script>";
	       	 	
		$query = "SELECT * FROM carne_titular ";
		if (isset($_GET['cod'])) {
			$query.= " WHERE id = ".$_GET['cod']." ";
		}
		$resultado = mysqli_query($conec->con,$query) or die('ERRO na Query Alterar dados!');
		$registros = mysqli_num_rows($resultado);
		
		$row = mysqli_fetch_array($resultado);

		print "<BR><b><font size=2 color='blue'>"."Edi&ccedil;&atilde;o Dados do Cliente"."</b></font><BR>";		

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='mini2' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($row['id'],6)."' readonly='true' ></td>";

		//print "<TD class='line'><a href='titular.php'><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD>";
		
		print "</TR><TR>";
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Contrato".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nrocarne' maxlength='9' class='text4' onkeyup=\"maskIt(this,event,'#######')\" id='idnrocarne' value='".$row['nrocarne']."'></td>";
		print "</TR></TD>";
		

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro da Carteira".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nrocarteira' maxlength='45' class='text2' id='idnrocarteira' value='".trim($row['nrocarteira'])."'></td>";
		print "</TR></TD>";
		
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome do Cliente".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='nometitular' maxlength='45' id='idnometitular' value='".trim($row['nometitular'])."'></td>";
		print "</TR><TR>";
				
		if($row['sexo']=='M'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($row['sexo']=='F'){ $selected2 = " selected"; } else { $selected2 = "";}
				
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Sexo".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='sexo' id='idsexo'>";  
		print "<option value='M'".$selected1.">Masculino</option>";  
		print "<option value='F'".$selected2.">Feminino</option>";  
		print "</select>";  
		print "</TR></TD>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Cep".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='cep' class='text4' id='cep' onBlur=\"javascript:pesquisacep(this.value);\"  maxlength='10' OnKeyPress=\"javascript:formatarcampo('##.###-###', this)\" value='".$row['cep']."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Endere&ccedil;o".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='endereco' maxlength='45' class='text3' id='idendereco' value='".trim($row['endereco'])."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."N&uacute;mero".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='numero' maxlength='10' class='text4' id='idnumero' value='".$row['numero']."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Bairro".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='bairro' maxlength='45' class='text3' id='idbairro' value='".trim($row['bairro'])."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Cidade".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='cidade' maxlength='45' class='text3' id='idcidade' value='".$row['cidade']."'></td>";
		print "</TR><TR>";
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."UF".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		
		print "<select style='width:50px; height:30px; font-size:14px' name='uf' id='iduf'>";  
		print "<option value='AC'>AC</option>";  
		print "<option value='AL'>AL</option>";  
		print "<option value='AM'>AM</option>";  
		print "<option value='AP'>AP</option>";  
		print "<option value='BA'>BA</option>";  
		print "<option value='CE'>CE</option>";  
		print "<option value='DF'>DF</option>";  
		print "<option value='ES'>ES</option>";  
		print "<option value='GO'>GO</option>"; 
		print "<option value='MA'>MA</option>";  
		print "<option value='MG'>MG</option>";  
		print "<option value='MS'>MS</option>";  
		print "<option value='MT'>MT</option>";  
		print "<option value='PA'>PA</option>";  
		print "<option value='PB'>PB</option>";  
		print "<option value='PE'>PE</option>";  
		print "<option value='PI'>PI</option>";  
		print "<option value='PR'>PR</option>";  
		print "<option value='RJ'>RJ</option>";  
		print "<option value='RN'>RN</option>";  
		print "<option value='RO'>RO</option>";  
		print "<option value='RR'>RR</option>";  
		print "<option value='RS'>RS</option>"; 
		print "<option value='SC'>SC</option>";  
		print "<option value='SE'>SE</option>";  
		print "<option value='SP'>SP</option>";  
		print "<option value='TO'>TO</option>";  
		print "<option value='".$row['uf']."' selected>".$row['uf']."</option>";
		print "</select>";  
		print "</TR><TR>";
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Fone Residencial".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='foneres' maxlength='45' onkeyup=\"maskIt(this,event,'(##)####-###############')\" class='text3' id='idfoneres' value='".$row['telefoneres']."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Fone Comercial".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='fonecom' class='text3' maxlength='45' id='idfonecom' onkeyup=\"maskIt(this,event,'(##)####-###############')\" value='".$row['telefonecom']."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Celular".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='celular' class='text3' maxlength='45' id='idcelular' onkeyup=\"maskIt(this,event,'(##)#####-#####')\" value='".$row['celular']."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Nascimento".":</TD>";
		$dtnasc = str_replace('/','',substr(converte_datacomhora($row['datanasc']),0,10));
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtnasc' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddtnasctitular' onBlur='return doDateVenc(this.id,this.value, 4)' value='".mask($dtnasc,'##/##/####')."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro de Filhos".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='qtdefilhos' class='text4' id='idqtdefilhos' onkeyup=\"maskIt(this,event,'##')\" value='".$row['qtdefilhos']."'></td>";
		print "</TR><TR>";		

		if($row['escolaridade']=='1'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($row['escolaridade']=='2'){ $selected2 = " selected"; } else { $selected2 = "";}
		if($row['escolaridade']=='3'){ $selected3 = " selected"; } else { $selected3 = "";}
		if($row['escolaridade']=='4'){ $selected4 = " selected"; } else { $selected4 = "";}
		if($row['escolaridade']=='5'){ $selected5 = " selected"; } else { $selected5 = "";}
		if($row['escolaridade']=='6'){ $selected6 = " selected"; } else { $selected6 = "";}
		if($row['escolaridade']=='7'){ $selected7 = " selected"; } else { $selected7 = "";}
		if($row['escolaridade']=='8'){ $selected8 = " selected"; } else { $selected8 = "";}

		if($row['cpfcnpj']=='1'){ $CheckedCPF = " Checked"; } else { $CheckedCPF = "";}
		if($row['cpfcnpj']=='2'){ $CheckedCNPJ = " Checked"; } else { $CheckedCNPJ = "";}
		
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Escolaridade".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='escolaridade' id='idescolaridade'>";  
		print "<option value='1'".$selected1.">N&atilde;o informado</option>";  
		print "<option value='2'".$selected2.">Analfabeto</option>";  
		print "<option value='3'".$selected3.">1&ordm; Grau Incompleto</option>";  
		print "<option value='4'".$selected4.">1&ordm; Grau Completo</option>";  
		print "<option value='5'".$selected5.">2&ordm; Grau Incompleto</option>";  
		print "<option value='6'".$selected6.">2&ordm; Grau Completo</option>";  
		print "<option value='7'".$selected7.">Superior Incompleto</option>";  
		print "<option value='8'".$selected8.">Superior Completo</option>";  
		print "</select>";  
		print "</TR></TD>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Local Trabalho".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='localtrab' maxlength='45' class='text3' id='idlocaltrab' value='".$row['localtrabalho']."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Profiss&atilde;o".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='profissao' maxlength='45' class='text3' id='idprofissao' value='".$row['profissao']."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Identidade".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='identidade' maxlength='45' class='text3' id='idrg' value='".$row['identidade']."'></td>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>";
		print "<input type='radio' name='selcpf' id='selcpf' value='1' ".$CheckedCPF." onChange='SelecionaCPF()'> CPF";
  		print "<input type='radio' name='selcnpj' id='selcnpj' value='2' ".$CheckedCNPJ." onChange='SelecionaCNPJ()'> CNPJ";
  		print "</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='cpf' class='text3' id='idcpf' value='".$row['cpf']."' onkeyup=\"maskIt(this,event,'##############')\" maxlength='14' onChange='validacpfcnpj()'></td>";
		
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Email ".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='email' maxlength='100' class='text3' id='idemail' value='".$row['email']."'></td>";
		print "</TR><TR>";
		
		if($row['estadocivil']=='1'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($row['estadocivil']=='2'){ $selected2 = " selected"; } else { $selected2 = "";}
		if($row['estadocivil']=='3'){ $selected3 = " selected"; } else { $selected3 = "";}
		if($row['estadocivil']=='4'){ $selected4 = " selected"; } else { $selected4 = "";}
		if($row['estadocivil']=='5'){ $selected5 = " selected"; } else { $selected5 = "";}
		if($row['estadocivil']=='6'){ $selected6 = " selected"; } else { $selected6 = "";}
		if($row['estadocivil']=='7'){ $selected7 = " selected"; } else { $selected7 = "";}
		if($row['estadocivil']=='8'){ $selected8 = " selected"; } else { $selected8 = "";}
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Estado Civil".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='estcivil' id='idestcivil'>";  
		print "<option value='1'".$selected1.">N&atilde;o informado</option>";  
		print "<option value='2'".$selected2.">Solteiro</option>";  
		print "<option value='3'".$selected3.">Casado</option>";  
		print "<option value='4'".$selected4.">Divorciado</option>";  
		print "<option value='5'".$selected5.">Separado</option>";  
		print "<option value='6'".$selected6.">Vi&uacute;vo</option>";  
		print "<option value='7'".$selected7.">Amasiado</option>";  
		print "<option value='8'".$selected8.">Indefinido</option>";  
		print "</select>";  
		print "</TR></TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Registro".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='prontuario2' maxlength='7' class='text4' onkeyup=\"maskIt(this,event,'#######')\" id='idprontuario2' value='".$row['prontuario']."'>
		<input type='button' value='Consulta' name='tipo1' class='minibutton2' onClick=\"javascript:popup_consulta('consultapacientes.php?popup=true&nome='+document.getElementById('idnometitular').value)\"></td>";

		print "</TR></TD>";
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Grupo".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";

		print "<select class='select2' name='grupo' id='idgrupo'>";  
		$sql="select id,descricao from carne_grupo where id =".$row['grupo']."";
		$commit = mysqli_query($conec->con,$sql) or die ('Erro na Query '.$sql);
		$rowR = mysqli_fetch_array($commit);		
		print "<option value=-1>"."Selecione o Grupo"."</option>";
				$sql="select id, descricao from carne_grupo where unidade =".$_SESSION['s_local']." order by id";
				$commit = mysqli_query($conec->con,$sql) or die ('Erro na Query '.$sql);;
							while($rowB = mysqli_fetch_array($commit)){
						print "<option value=".$rowB["id"]."";
                        			if ($rowB['id'] == $rowR['id'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB['descricao']."</option>";
					}
			print "</select>";
				
		print "</TR><TR>";
		
		if($row['datainicio']=='1900-01-01 00:00:00') {
			$dtinicio = '';
		} else { 
			$dtinicio = str_replace('/','',substr(converte_datacomhora($row['datainicio']),0,10));
		}
				
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inicial do Parcelamento".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datainicial' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddatainicial' onBlur='return doDateVenc(this.id,this.value, 4)' value='".mask($dtinicio,'##/##/####')."'></td>";
		print "</TR><TR>";

		$ultimomescarne = '';
		if(!empty($row['ultimomescarne'])){
			$ultimomescarne = substr($row['ultimomescarne'],4,2)."/".substr($row['ultimomescarne'],0,4);
		}
			
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."&Uacute;ltimo M&ecirc;s/Ano Carn&ecirc;".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='ultimomescarne' class='text4' onkeyup=\"maskIt(this,event,'##/####')\" id='idultimomescarne' onBlur='return MesAno(this.id,this.value, 4)' value='".$ultimomescarne."'></td>";
		print "</TR><TR>";
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome do Pai".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nomepai' maxlength='45' class='text3' id='idnomepai' value='".$row['nomepai']."'></td>";
		print "</TR></TD>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome da M&atilde;e".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nomemae' maxlength='45' class='text3' id='idnomemae' value='".$row['nomemae']."'></td>";
		print "</TR></TD>";

		if($row['situacao'] == 'ATIVO') { $ativo = " selected"; } else { $ativo = ""; }
		if($row['situacao'] == 'INATIVO') { $inativo = " selected"; } else { $inativo = ""; }
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Situa&ccedil;&atilde;o".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='situacao' id='idsituacao'>";  
		print "<option value='ATIVO'".$ativo.">Ativo</option>";
		print "<option value='INATIVO'".$inativo.">Inativo</option>";
		print "</select></TD><TR>";

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
		

		print "</TR></TD>";
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Vendedor".":</TD>";
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

		// Verifico se o Valor do plano e Negociado com o Cliente
		// e permito digitar o valor
		$sql="select d.vlrfixonegociado, c.descricao from carne_titular a Join carne_contratos b on a.id = b.idtitular
				join carne_tipoplano c on c.id = b.plano
				join carne_competenciaplano d on d.idplano = c.id
				where b.idtitular = ".$row['id']." and d.vlrfixonegociado = 2";
		$vlrfixo = mysqli_query($conec->con,$sql) or die ('Erro na Query '.$sql);;
		$vlrfixonegociado = 0;
		while($rowVlr = mysqli_fetch_array($vlrfixo)){
			$vlrfixonegociado = $rowVlr['vlrfixonegociado'];
		}
		
		if($vlrfixonegociado > 0){
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Valor do Plano Negociado".":</TD>";
			print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='valorplano' maxlength='15' id='idvalorplano' onkeypress=\"return formatar_moeda(this,',','.',event);\" value='".$row['valorplano']."'></td>";
			print "</TR><TR>";		
		}
		
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='alteratitular' class='mini2' id='idalteratitular' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($row['id'],6)."' readonly='true' hidden='true'></td>";
		
		print "</TR></TD>";
		
		
	    if(liberamenu('Alterar Titular')=="N"){
	    	$disabled = " disabled='disabled'";
	    	$clasbutton = " class='buttonDisabled'";
	    }
		
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' $clasbutton value='".TRANS('BT_ALTER')."' name='submit' $disabled>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";

		print "</form>";
		
	}
	
		print "</table>";		
		//print "</form>";
		
		// Vari�veis convertidas
		if(isset($_POST['codigo']) && $_POST['submit'] <> 'Salvar Contrato') {
				
				$nascimento = Fdate($_POST['dtnasctitular']);
				$zero = 0;
				$registro = date("Y-m-d H:i:s");
				$codcidade = '';
				$cep = str_replace("-", "", $_POST['cep']);
				$cep = str_replace(".", "", $cep );
	
				$foneres = str_replace("-", "", $_POST['foneres']);
				$foneres = str_replace("(", "", $foneres);
				$foneres = str_replace(")", "", $foneres);
	
				$fonecom = str_replace("-", "", $_POST['fonecom']);
				$fonecom = str_replace("(", "", $fonecom);
				$fonecom = str_replace(")", "", $fonecom);
				
				$celular = str_replace("-", "", $_POST['celular']);
				$celular = str_replace("(", "", $celular);
				$celular = str_replace(")", "", $celular);
				
				if(isset($_POST['datainicial'])) { $datainicio = Fdate($_POST['datainicial']); } else { $datainicio = '1900-01-01 00:00:00'; } 
				if(empty($_POST['nrocarne'])) { $nrocarne = 0; } else $nrocarne = $_POST['nrocarne'];
						
		}
		
		
			// Incluindo Titular com INSERT
			if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_CAD')))) {	

				// Verifico se existe somente um Plano cadastrado
				$sqlplano="SELECT count(*) as qtde,id FROM carne_tipoplano";
				$qtdplano = mysqli_query($conec->con,$sqlplano) or die ('Erro na Query '.$sql);;
				$qtdplanoexiste = 0;
				$idplano = 0;
				while($rowplan = mysqli_fetch_array($qtdplano)){
					$qtdplanoexiste = $rowplan['qtde'];
					$idplano = $rowplan['id'];
				}
				
				
				$erro=false;
		
				$qryl = "SELECT * FROM carne_titular WHERE nometitular='".$_POST['nometitular']."' and nomemae='".$_POST['nomemae']."' and datanasc = '".$nascimento."'";
				$resultado = mysqli_query($conec->con,$qryl) or die('Erro na Query :'.$qryl);
				$linhas = mysqli_num_rows($resultado);
				
				
				if ($linhas > 0)
				{
						$aviso = TRANS('MSG_EXIST_REG_CAD_SYSTEM_TIT');
						$erro = true;;
				}
		
				
				if (!$erro)
				{
		
					if(empty($_POST['prontuario'])) { $prontuario = 0; } else $prontuario = $_POST['prontuario'];
					
					$lcnome = retira_acentos_ISO($_POST['nometitular']);
					$lcnome = strtoupper($lcnome);
					
					$lcnomePai = retira_acentos_ISO($_POST['nomepai']);
					$lcnomePai = strtoupper($lcnomePai);
					
					$lcnomeMae = retira_acentos_ISO($_POST['nomemae']);
					$lcnomeMae = strtoupper($lcnomeMae);

					
					$lcnomeEndereco = retira_acentos_ISO($_POST['endereco']);

					$selCPFCNPJ = 1;
					
					if(isset($_POST['selcpf'])) { 
						$selCPFCNPJ = $_POST['selcpf'];
					} 
	
					if(isset($_POST['selcnpj'])) { 
						$selCPFCNPJ = $_POST['selcnpj'];
					} 

					if(empty($_POST['dtinativo'])) { $dtinativo = '1900-01-01 00:00:00'; } else $dtinativo = FDate($_POST['dtinativo']);
					$obs = $_POST['obs'];

					if(empty($_POST['ultimomescarne'])) { $ultimomescarne = ''; } else $ultimomescarne = substr($_POST['ultimomescarne'],3,4).substr($_POST['ultimomescarne'],0,2);
					
					
					$query = "INSERT INTO carne_titular (nometitular,endereco,numero,cep,bairro,cidade,codcidade,uf,telefoneres,telefonecom,celular,datanasc,qtdefilhos,escolaridade,localtrabalho,profissao,identidade,cpf,estadocivil,sexo,nomemae,nomepai,email,unidade,nrocontrato,registro,prontuario,nrocarne,grupo,datainicio,situacao,cpfcnpj,nrocarteira,dtinativo,obs,vendedor,ultimomescarne) ".
							" values ('".$lcnome."','".$lcnomeEndereco."','".$_POST['numero']."','".$cep."','".strtoupper($_POST['bairro'])."','".strtoupper($_POST['cidade'])."','".$codcidade."','".$_POST['uf']."','".$foneres."','".$fonecom."','".$celular."','".$nascimento."',".$_POST['qtdefilhos'].",'".$_POST['escolaridade']."','".$_POST['localtrab']."','".$_POST['profissao']."','".$_POST['identidade']."','".$_POST['cpf']."','".$_POST['estcivil']."','".$_POST['sexo']."','".$lcnomeMae."','".$lcnomePai."','".strtolower($_POST['email'])."','".$_SESSION['s_local']."',".$zero.",'".$registro."','".$prontuario."',".$nrocarne.",'".$_POST['grupo']."','".$datainicio."','".$_POST['situacao']."','".$selCPFCNPJ."','".$_POST['nrocarteira']."','".$dtinativo."','".$obs."',".$_POST['vendedor'].",'".$ultimomescarne."')";

					$resultado = mysqli_query($conec->con,$query) or die('Erro no Insert '.$query);
					if ($resultado == 0)
					{
						$aviso = TRANS('ERR_INSERT');
					}
					else
					{
						$aviso = TRANS('OK_INSERT');
						
						
						if($qtdplanoexiste == 1) {
						// $idplano = $rowplan['id'];
						
							$sqlplano="select max(id) as ultimoid from carne_titular";
							$qtdplano = mysqli_query($conec->con,$sqlplano) or die ('Erro na Query '.$sql);;
							$ultimoid = 0;
							while($rowplan = mysqli_fetch_array($qtdplano)){
								$ultimoid = $rowplan['ultimoid'];
							}
							
							$sqlplano="select datainicio, day(datainicio) dia, unidade, nrocarne from carne_titular where id = '".$ultimoid."'";
							$qtdplano = mysqli_query($conec->con,$sqlplano) or die ('Erro na Query '.$sql);;
							$datainicio = '1900-01-01 00:00:00';
							$dia = 0;
							$unidade = 1;
							$nrocarne = 0;
							
							while($rowplan = mysqli_fetch_array($qtdplano)){
								$datainicio = $rowplan['datainicio'];
								$dia = $rowplan['dia'];
								$unidade = $rowplan['unidade'];
								$nrocarne = $rowplan['nrocarne'];
							}
							
							$registro = date("Y-m-d H:i:s");
							
							$query = "INSERT INTO carne_contratos (nrocontrato,plano,datacontrato,diavencto,status,idtitular,unidade,registro)".
									" values ('".$nrocarne."','".$idplano."','".$datainicio."','".$dia."','0','".$ultimoid."',".$unidade.",'".$registro."')";
							$resultado = mysqli_query($conec->con,$query) or die('Erro no Insert '.$query);
					
						}
					
						
					}
				}
		
				if($aviso != TRANS('OK_INSERT')) {
					echo "<script>mensagem('".$aviso."');</script>";
				} 
				

			 $query = "SELECT max(id) as id FROM carne_titular ";
			 $resultado = mysqli_query($conec->con,$query) or die('ERRO NA EXECU��O DA QUERY DE MAX ID 3!');
	       	 $maxidtitular = mysqli_fetch_array($resultado);

	       	 $Log1 = liberamenu('Incluir Titular');
	    	 $Log2 = $maxidtitular['id'];
	    	 $log3 = strtoupper($_POST['nometitular']);
	    	 $log4 = false;
	    	 $log5 = "";
	 	     grava_log($_SESSION['s_uid'],$_SESSION['s_codoperacao'],$Log2,$log3,$log4,$log4,$log4,$log5);
	       	 

	 	     echo "<script>redirect('abastitular.php?action=alter&cod=".$maxidtitular['id']."&cellStyle=true');</script>";
				//echo "<script>redirect('".$_SERVER['PHP_SELF']."');</script>";
		
			}		

			
			// Alterando o Titular com UPDATE
			if((isset($_POST['alteratitular']))) {				

				$nascimento = Fdate($_POST['dtnasc']);
				
				if(isset($_POST['datainicial'])) { $datainicio = Fdate($_POST['datainicial']); } else { $datainicio = '1900-01-01 00:00:00'; } 
				
				
				$zero = 0;
				$registro = date("Y-m-d H:i:s");
				$codcidade = '';
				$cep = str_replace("-", "", $_POST['cep']);
				$cep = str_replace(".", "", $cep );
	
				$foneres = str_replace("-", "", $_POST['foneres']);
				$foneres = str_replace("(", "", $foneres);
				$foneres = str_replace(")", "", $foneres);
	
				$fonecom = str_replace("-", "", $_POST['fonecom']);
				$fonecom = str_replace("(", "", $fonecom);
				$fonecom = str_replace(")", "", $fonecom);
				
				$celular = str_replace("-", "", $_POST['celular']);
				$celular = str_replace("(", "", $celular);
				$celular = str_replace(")", "", $celular);

				$prontuario = $_POST['prontuario2'];
				
				if(empty($prontuario)) { 
					$prontuario = 0; 
				}
				
				//if(empty($_POST['prontuario'])) { $prontuario = 0; } else $prontuario = $_POST['prontuario'];
				if(empty($_POST['nrocarne'])) { $nrocarne = 0; } else $nrocarne = $_POST['nrocarne'];
				
				$selCPFCNPJ = 1;
				
				if(isset($_POST['selcpf'])) { 
					$selCPFCNPJ = $_POST['selcpf'];
				} 

				if(isset($_POST['selcnpj'])) { 
					$selCPFCNPJ = $_POST['selcnpj'];
				} 
				
				$lcnome = retira_acentos_ISO($_POST['nometitular']);
				$lcnome = strtoupper($lcnome);

				if(empty($_POST['dtinativo'])) { $dtinativo = '1900-01-01 00:00:00'; } else $dtinativo = FDate($_POST['dtinativo']);
				$obs = $_POST['obs'];

				if(empty($_POST['ultimomescarne'])) { $ultimomescarne = ''; } else $ultimomescarne = substr($_POST['ultimomescarne'],3,4).substr($_POST['ultimomescarne'],0,2);
									
				$valorplano = 0.00;
				if(isset($_POST['valorplano'])) { 
					$valorplano = $_POST['valorplano'];
				} 
				
				
				$query2 = "UPDATE carne_titular SET nometitular='".$lcnome."',endereco='".$_POST['endereco']."', datanasc='".$nascimento."', datainicio='".$datainicio."', numero='".$_POST['numero']."', cep='".$cep."', bairro='".$_POST['bairro']."', cidade='".$_POST['cidade']."', codcidade='"."', uf='".$_POST['uf']."', telefoneres='".$foneres."', telefonecom='".$fonecom."', celular='".$celular."', qtdefilhos=".$_POST['qtdefilhos'].", escolaridade='".$_POST['escolaridade']."', localtrabalho='".$_POST['localtrab']."', profissao='".$_POST['profissao']."', identidade='".$_POST['identidade']."', cpf='".$_POST['cpf']."', estadocivil='".$_POST['estcivil']."', sexo='".$_POST['sexo']."', nomemae='".$_POST['nomemae']."', nomepai='".$_POST['nomepai']."', email='".strtolower($_POST['email'])."', unidade='".$_SESSION['s_local']."', prontuario='".$prontuario."',nrocarne=".$nrocarne.", situacao='".$_POST['situacao']."', grupo=".$_POST['grupo'].", cpfcnpj = ".$selCPFCNPJ.", nrocarteira = '".$_POST['nrocarteira']."', dtinativo = '".$dtinativo."', obs = '".$obs."', vendedor = ".$_POST['vendedor'].", ultimomescarne = '".$ultimomescarne."', valorplano = ".$valorplano." WHERE id=".$_POST['alteratitular']." ";		
				
				$resultado2 = mysqli_query($conec->con,$query2) or die('Erro na query: '.$query2);

	       	 $Log1 = liberamenu('Alterar Titular');
	    	 $Log2 = $_POST['alteratitular'];
	    	 $log3 = strtoupper($_POST['nometitular']);
	    	 $log4 = false;
	    	 $log5 = "";
	 	     grava_log($_SESSION['s_uid'],$_SESSION['s_codoperacao'],$Log2,$log3,$log4,$log4,$log4,$log5);
	 	
			 // Henrique 01/09/2020 GLPI 20037
	 	     if($dtinativo <> '1900-01-01 00:00:00' && $_POST['situacao'] == 'INATIVO') {
		       	 $Log1 = liberamenu('Inativou Titular Carne');
		    	 $Log2 = $_POST['alteratitular'];
		    	 $log3 = strtoupper($_POST['nometitular']);
		    	 $log4 = false;
		    	 $log5 = "";
		 	     grava_log($_SESSION['s_uid'],$_SESSION['s_codoperacao'],$Log2,$log3,$log4,$log4,$log4,$log5);
			} 
	 	     
	 
				echo "<script>redirect('abastitular.php?action=alter&cod=".$_POST['alteratitular']."&cellStyle=true');</script>";
			}

						
			
					
			if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_ALTER')))) {
		
				$nascimento = Fdate($row['datanasc']);
				$zero = 0;
				$registro = date("Y-m-d H:i:s");
				$codcidade = '';
				$cep = str_replace("-", "", $row['cep']);
				$cep = str_replace(".", "", $cep );
	
				$foneres = str_replace("-", "", $row['telefoneres']);
				$foneres = str_replace("(", "", $foneres);
				$foneres = str_replace(")", "", $foneres);
	
				$fonecom = str_replace("-", "", $row['telefonecom']);
				$fonecom = str_replace("(", "", $fonecom);
				$fonecom = str_replace(")", "", $fonecom);
				
				$celular = str_replace("-", "", $row['celular']);
				$celular = str_replace("(", "", $celular);
				$celular = str_replace(")", "", $celular);
				
				if(isset($_POST['datainicial'])) { $datainicio = Fdate($_POST['datainicial']); } else { $datainicio = '1900-01-01 00:00:00'; } 				
				
				if(empty($row['prontuario'])) { $prontuario = 0; } else $prontuario = $row['prontuario'];
				
				$lcnome = retira_acentos_ISO($_POST['nometitular']);
				$lcnome = strtoupper($lcnome);
				
				$query2 = "UPDATE carne_titular SET nometitular='".$lcnome."',endereco='".$row['endereco']."', numero='".$row['numero']."', cep='".$cep."', bairro='".$row['bairro']."', cidade='".$row['cidade']."', codcidade='"."', uf='".$row['uf']."', telefoneres='".$foneres."', telefonecom='".$fonecom."', celular='".$celular."', datanasc='".$nascimento."', qtdefilhos=".$row['qtdefilhos'].", escolaridade='".$row['escolaridade']."', localtrabalho='".$row['localtrabalho']."', profissao='".$row['profissao']."', identidade='".$row['identidade']."', cpf='".$row['cpf']."', estadocivil='".$row['estadocivil']."', sexo='".$row['sexo']."', nomemae='".$row['nomemae']."', nomepai='".$row['nomepai']."', email='".strtolower($row['email'])."', unidade='".$_SESSION['s_local']."', prontuario='".$prontuario."' WHERE id=".$row['id']." ";
				
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
							
				echo "<script>redirect('abastitular.php?action=alter&cod=".$_POST['codigo']."&cellStyle=true');</script>";
		
			}
			
print "</div>";


// Aba Dependentes
print "<div id='div_consulta' class='conteudo' style='display: none'>";

		// Excluindo Dependente
		if ((isset($_GET['action'])) && $_GET['action']=='excluirdependente') {

			$idDep = $_GET['iddependente'];
			
			$query2 = "select nome from carne_dependente where id =".$_GET['iddependente']."";
			$resultado2 = mysqli_query($conec->con,$query2) or die('Erro ... '.$query2);
			$rowNome = mysqli_fetch_array($resultado2);
			$nomedep = $rowNome['nome'];

			$query2 = "delete from carne_dependente where id =".$_GET['iddependente']."";
			$resultado2 = mysqli_query($conec->con,$query2) or die('Erro ... '.$query2);
			
			if ($resultado2 == 0)
			{
					$aviso = TRANS('ERR_DEL');
			}
			else
			{
					$aviso = TRANS('OK_DEL');
			}

		
			 echo "<script>redirect('abastitular.php?action=alter&cod=".$_GET['cod']."&cellStyle=true');</script>";
						
		}


		$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];
		
		$PAGE = new paging_("PRINCIPAL");
		$PAGE->setRegPerPage($_SESSION['s_page_size']);
		$search = "";
		$cellStyle = "cellpadding='5' cellspacing='1'";

		print "<TABLE style='font-size:15px;' border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

		// Incluindo Dependentes
		if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {
		
		print "</form>";
		
		print "<FORM name='abastitular' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return validadep()\">";
			
		       	$query = "SELECT max(id) as id FROM carne_dependente ";
				$resultado = mysqli_query($conec->con,$query) or die('ERRO NA EXECU��O DA QUERY DE MAX ID 4!');
		       	$maxid = mysqli_fetch_array($resultado);
				
				print "<BR><b><font size=2 color='blue'>"."Inclus&atilde;o do Cliente"."</b></font><BR>";
		
				print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."ID do Titular".":</TD>";
				print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='idtitular' class='text4' id='idtitular' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($_GET['cod'],6)."' readonly='true' ></td>";

				//print "<TD class='line'><a href='titular.php'><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD>";
				
				print "</TR><TR>";
				print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo".":</TD>";
				print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='text4' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='' readonly='true' ></td>";
				print "</TR><TR>";		
				
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome Dependente".":</TD>";
				print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='nomedep' maxlength='45' id='idnomedep'></td>";
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
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Registro".":</TD>";
				print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='prontuario' maxlength='7' class='text4' onkeyup=\"maskIt(this,event,'#######')\" id='idprontuario' value=''></td>";
				print "</TR><TR>";
				
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Nascimento".":</TD>";
				
				// Quando Data de Nascimento e obrigato
				if($dtnascdepobriga==1){
					print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtnasc' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddtnasc' onBlur='return doDateVenc(this.id,this.value, 4)'>";
				} else {
					print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtnasc' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddtnasc2' onBlur='return doDateVenc(this.id,this.value, 4)'>";
				}
				print "<INPUT type='text' class='text4' name='idade'  id='ididade' hidden='true'></td>";
				print "</TR><TR>";
				
			print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Situa&ccedil;&atilde;o".":</TD>";
			print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select2' name='situacao' id='idsituacao'>";  
			print "<option value='ATIVO'>Ativo</option>";
			print "<option value='INATIVO'>Inativo</option>";
			print "</select>";
			print "</TD></TR>";
							
			print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Cobrar no Boleto".":</TD>";
			print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select2' name='cobrarnoboleto' id='idcobrarnoboleto'>";  
			print "<option value='1'>Sim</option>";
			print "<option value='2'>Não</option>";
			print "</select>";
			print "</TD></TR>";

			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inativo".":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtinativo' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddtinativo' onBlur='return doDateVenc(this.id,this.value, 4)' value=''></td>";
			print "</TR><TR>";
	
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."OBS".":</TD>";
			print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='obs' maxlength='200' id='idobs' value=''></td>";
			print "</TR><TR>";		
				
		
				print "<TR> <TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='Incluir Dependente' name='submit'>";
				print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";
		
				print "</TR>";

		// Come�a aqui a listar os dependentes		
   		$query = "SELECT * FROM carne_dependente WHERE idtitular = ".$_GET['cod']." ";
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
		$registros = mysqli_num_rows($resultado);

		if (isset($_GET['LIMIT']))
		$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));

		// vari�vel que controla permiss�es dos bot�es para incluir, editar e excluir  do usu�rio
		$disabled = '';
		$clasbutton = " class='button'";
		
		$PAGE->execSQL();

	    if(liberamenu('Incluir Dependente')=="N"){
	    	$disabled = " disabled='disabled'";
	    	$clasbutton = " class='buttonDisabled'";
	    }
		
		
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
					print "<TR class='header'><td class='line' width='70%'>"."Nome Dependente"."</TD>"."<td class='line'>"."Cobrar no Boleto"."<td class='line'>"."C&oacute;digo"."</TD>"."<td class='line'>"."Nro Carteira"."</TD>"."<td class='line'>"."Data Nasc"."</TD>".
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

						if($row['cobrarnoboleto'] == 1) { $cobrarnoboleto = 'Sim';} else { $cobrarnoboleto = 'Não'; }
		
						print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
						print "<td class='line'>".$row['nome']."</td>";
						print "<td class='line'>".$cobrarnoboleto."</td>";
						print "<td class='line'>".$row['id']."</td>";
						print "<td class='line'>".$row['nrocarteira']."</td>";
						$dtnasc = str_replace('/','',substr(converte_datacomhora($row['datanasc']),0,10));
						print "<td class='line'>".mask($dtnasc,'##/##/####')."</td>";
						print "<td class='line'><a onClick=\"redirect('abastitular.php?action=editar&cod=".$row['id']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
		
						if(liberamenu('Excluir Dependentes')=="N"){
							print "<td class='line'><a onClick=''><img height='16' width='16' src='".ICONS_PATH."proibido.jpg' title='Exclus&atilde;o n&atilde;o permitida'></a></TD>";
						} else {
							print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('MSG_DEL_REG')."','".$_SERVER['PHP_SELF']."', 'action=excluirdependente&cod=".$row['idtitular']."&iddependente=".$row['id']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
						}
						
						
						print "</TR>";
					}
					print "<tr><td colspan='4'>";
					$PAGE->showOutputPages();
					print "</td></tr>";
					
					
				}
				
				print "</form>";
				
			}

	// Alterando os dados Dependente
	if ((isset($_GET['action']) && $_GET['action']=="editar") && empty($_POST['submit'])) {

	print "</form>";		
		
	print "<FORM name='abastitular' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return validadep()\">";
		
		$query = "SELECT * FROM carne_dependente WHERE id = ".$_GET['cod']." ";
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
		$row = mysqli_fetch_array($resultado);

		print "<BR><b><font size=2 color='blue'>"."Edi&ccedil;&atilde;o Dados do Dependente"."</b></font><BR>";		

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."ID do Titular".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='idtitular' class='text4' id='idtitular' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($row['idtitular'],6)."' readonly='true' ></td>";

		//print "<TD class='line'><a href='titular.php'><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD>";
		
		print "</TR><TR>";
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='text4' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($row['id'],6)."' readonly='true'  ></td>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome Dependente".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='nomedep' maxlength='45' id='idnomedep' value='".$row['nome']."'></td>";
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
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Registro".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='prontuario' maxlength='7' class='text4' onkeyup=\"maskIt(this,event,'#######')\" id='idprontuario' value='".$row['prontuario']."'></td>";
		print "</TR><TR>";
		$dtnasc = str_replace('/','',substr(converte_datacomhora($row['datanasc']),0,10));
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Nascimento".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtnasc' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddtnasc' onBlur='return doDateVenc(this.id,this.value, 4)' value='".mask($dtnasc,'##/##/####')."'></td>";
		print "<INPUT type='text' class='text4' name='idade'  id='ididade' hidden='true'></td>";		
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

		if($row['cobrarnoboleto'] == 1) { $cobrarsim = " selected"; } else { $cobrarsim = ""; }
		if($row['cobrarnoboleto'] == 2) { $cobrarnao = " selected"; } else { $cobrarnao = ""; }
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Cobrar no Boleto".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='cobrarnoboleto' id='idcobrarnoboleto'>";  
		print "<option value='1'".$cobrarsim.">Sim</option>";
		print "<option value='2'".$cobrarnao.">Não</option>";
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
		
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' $clasbutton value='Salvar' name='submit' $disabled>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";

		print "</form>";		
		
	}
			
		print "</table>";		
		

		// Vari�veis convertidas
		if(isset($_POST['codigo'])) {
				
				$nascimento = Fdate($_POST['dtnasc']);
				$registro = date("Y-m-d H:i:s");
				if(empty($_POST['prontuario'])) { $prontuario = 0; } else $prontuario = $_POST['prontuario'];
					
		}
		
		// Incluindo Dependente com INSERT
		if ((isset($_POST['submit'])  && ($_POST['submit'] == 'Incluir Dependente'))) {	
	
			$situacao = $_POST['situacao'];
			if(empty($_POST['dtinativo'])) { $dtinativo = '1900-01-01 00:00:00'; } else $dtinativo = FDate($_POST['dtinativo']);
			$obs = $_POST['obs'];
			
			$erro=false;
	
			$qryl = "SELECT * FROM carne_dependente WHERE idtitular = ".$_POST['idtitular']." and nome='".$_POST['nomedep']."' and datanasc = '".$nascimento."'";
			$resultado = mysqli_query($conec->con,$qryl) or die('Erro na Query :'.$qryl);
			$linhas = mysqli_num_rows($resultado);
	
			if ($linhas > 0)
			{
					$aviso = TRANS('MSG_EXIST_REG_CAD_SYSTEM_DEP');
					$erro = true;;
			}
	
			
			if (!$erro)
			{
				
				$lcnome = retira_acentos_ISO($_POST['nomedep']);
				$lcnome = strtoupper($lcnome);
				
				$query = "INSERT INTO carne_dependente (nome,parentesco,sexo,datanasc,registro,idtitular,prontuario,nrocarteira,situacao,dtinativo,obs,cobrarnoboleto)".
						" values ('".$lcnome."','".$_POST['parentesco']."','".$_POST['sexo']."','".$nascimento."','".$registro."','".$_POST['idtitular']."',".$prontuario.",'".$_POST['nrocarteira']."','".$situacao."','".$dtinativo."','".$obs."',".$_POST['cobrarnoboleto'].")";
							
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
	    	 $Log2 = $_POST['idtitular'];
	    	 $log3 = strtoupper($_POST['nomedep']);
	    	 $log4 = false;
	    	 $log5 = "";
	 	     grava_log($_SESSION['s_uid'],$_SESSION['s_codoperacao'],$Log2,$log3,$log4,$log4,$log4,$log5);
			
			echo "<script>redirect('abastitular.php?action=alter&cod=".$_POST['idtitular']."&cellStyle=true');</script>";
			
		}

		// Alterando Dependente com UPDATE
		if ((isset($_POST['submit'])  && ($_POST['submit'] == 'Salvar'))) {	
						
			$lcnome = retira_acentos_ISO($_POST['nomedep']);
			$lcnome = strtoupper($lcnome);

			$situacao = $_POST['situacao'];
			$dtinativo = Fdate($_POST['dtinativo']);
			$obs = $_POST['obs'];
			
			$query2 = "UPDATE carne_dependente SET nome='".$lcnome."',parentesco='".$_POST['parentesco']."', sexo='".$_POST['sexo']."', datanasc='".$nascimento."', prontuario=".$prontuario.", nrocarteira = '".$_POST['nrocarteira']."', situacao = '".$situacao."', dtinativo = '".$dtinativo."', obs = '".$obs."', cobrarnoboleto = ".$_POST['cobrarnoboleto']." WHERE id=".$_POST['codigo']." ";
			
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
	
				echo "<script>redirect('abastitular.php?action=alter&cod=".$_POST['idtitular']."&cellStyle=true&editadependente=true');</script>";	
		}
		
print "</div>";

// ABA Contratos
print "<div id='div_contrato' class='conteudo' style='display: none'>"; 


		// Excluindo Contrato
		if ((isset($_GET['action'])) && $_GET['action']=='excluircontrato') {

			 $query2 = "select c.idtitular, t.nometitular from carne_contratos c join carne_titular t on t.id = c.idtitular where c.id ='".$_GET['idcontrato']."'";
			 $resultado2 = mysqli_query($conec->con,$query2) or die('Erro ... '.$query2);
			 $rowCont = mysqli_fetch_array($resultado2);
			 
			
 			 $Log1 = liberamenu('Excluir Contrato');
	       	 $Log2 = $rowCont['idtitular'];
	    	 $log3 = "Titular: ".$rowCont['nometitular'];;
	    	 $log4 = false;
	    	 $log5 = "";
	 	     grava_log($_SESSION['s_uid'],$_SESSION['s_codoperacao'],$Log2,$log3,$log4,$log4,$log4,$log5);
			
			$query2 = "delete from carne_contratos where id ='".$_GET['idcontrato']."'";
			$resultado2 = mysqli_query($conec->con,$query2) or die('Erro ... '.$query2);

			if ($resultado2 == 0)
			{
					$aviso = TRANS('ERR_DEL');
			}
			else
			{
					$aviso = TRANS('OK_DEL');
			}
			
				echo "<script>redirect('abastitular.php?action=alter&cod=".$_GET['cod']."&cellStyle=true');</script>";
						
		}

		$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];
		
		$PAGE = new paging_("PRINCIPAL");
		$PAGE->setRegPerPage($_SESSION['s_page_size']);
		$search = "";
		$cellStyle = "cellpadding='5' cellspacing='1'";
		
		
		print "<TABLE style='font-size:15px;' border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";
		
		// Incluindo Contrato
		if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {
		print "</form>";

		    $query = "SELECT prox_cartao_desc,prox_contrato,dtnascdepobriga FROM config";
			$resultado = mysqli_query($conec->con,$query) or die('ERRO NA EXECU��O DA QUERY DE MAX ID!');
	       	$config = mysqli_fetch_array($resultado);
	       	$proximocartao = $config['prox_cartao_desc']+1;
	       	$proximocontra = $config['prox_contrato']+1;
	       	$dtnascdepobriga = $config['dtnascdepobriga'];
		
		print "<FORM name='abastitular' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return validacon()\">";
		
			if(liberamenu('Incluir Contrato')=="N"){
		    	$disabled = " disabled='disabled'";
		    	$clasbutton = " class='buttonDisabled'";
		    }
		
		print "<BR><b><font size=2 color='blue'>"."Inclus&atilde;o do Plano/Contrato"."</b></font><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."ID do Titular".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='idtitular' class='text4' id='idtitular' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($_GET['cod'],6)."' readonly='true' ></td>";
		
		//print "<TD class='line'><a href='titular.php'><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD>";
		$DataContrato = date('d/m/Y');
		$DiaVencto	  = date('d');
		
		print "</TR><TR>";
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Contrato".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='text4' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='' onBlur='VerContrato(this.value)'></td>";
		print "<INPUT type='text' name='titular' class='text4' id='idtitular' onkeyup=\"maskIt(this,event,'######')\" value='".$_GET['cod']."' hidden='true' >";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inicio Contrato".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtcontrato' id='iddtcontrato' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\"  onBlur='return doDateVenc(this.id,this.value, 4)' value=''></td>";
		print "</TR><TR>";		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Plano".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select3' name='plano' id='idplano'>";  
				print "<option value=-1>"."Selecione o Plano"."</option>";
					$sql="Select id,descricao from carne_tipoplano where status = 0 and unidade = ".$_SESSION['s_local'];
					$commit = mysqli_query($conec->con,$sql);
					$i=0;
					while($row = mysqli_fetch_array($commit)){
						print "<option value=".$row['id'].">".$row['descricao']."</option>";
						$i++;
					}
				print "</select>";
		print "</TR></TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Dia do Vencimento".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='diavencto' class='text4' id='iddiavencto' maxlength='2' OnKeyPress=\"javascript:formatarcampo('##', this)\" value=''></td>";
		print "</TR><TR>";
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Status".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='status' id='idstatus'>";  
		print "<option value='0'>Ativo</option>";  
		print "<option value='1'>Inativo</option>";  
		print "</select>";  
		print "</TR><TR>";
		print "<TR> <TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' $clasbutton value='Salvar Contrato' name='submit' $disabled>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";

		
		$query = "SELECT nometitular from carne_titular where id = ".$_GET['cod']."";
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
		$rowTitular = mysqli_fetch_array($resultado);
		
		$query = "SELECT c.id, c.nrocontrato, c.idtitular, c.datacontrato, c.plano, c.diavencto, c.status, c.registro, p.descricao from carne_contratos c Join carne_tipoplano p on p.id = c.plano where c.idtitular=".$_GET['cod']." order by p.descricao desc";
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
		$registros = mysqli_num_rows($resultado);

		if (isset($_GET['LIMIT']))
		$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));
		
		$PAGE->execSQL();

		// Nenhum registro encontrado
		if (mysqli_num_rows($resultado) == 0)
		{
			echo "<tr><td colspan='4'>".mensagem(TRANS('MSG_NOT_REG_CAD'))."</td></tr>";
			echo "<tr><td colspan='4' align='lefth'></td></tr>";			
		}
		else
		{

			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr><td colspan='4'>";
			print "<B><br>".TRANS('FOUND')." <font color=red>".$PAGE->NUMBER_REGS."</font> ".TRANS('RECORDS_IN_SYSTEM').". ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</B></TD>";
			print "</tr>";
			//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
			print "<TR class='header'><td class='line' width='10%'>"."Nro Contrato"."</TD>"."<td class='line' width='20%'>"."Data Vencto Contrato"."<td class='line' width='20%'>"."Status"."<td class='line' width='50%'>"."Plano"."</TD>"."<td class='line' width='10%'>"."Dia Vencto"."</TD>".
				"<td class='line'>".TRANS('COL_EDIT')."</TD><td class='line'>".TRANS('COL_DEL')."</TD><td class='line'>"."CONTRATO"."</TD><td class='line'>"."CARTAO"."</TD></tr>";
			
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
				print "<td class='line'>".$row['nrocontrato']."</td>";
				$dtcontrato = str_replace('/','',substr(converte_datacomhora($row['datacontrato']),0,10));
				print "<td class='line'>".mask($dtcontrato,'##/##/####')."</td>";

				$status = "ATIVO";
				if($row['status'] == 1){
					$status = "INATIVO";	
				}
				print "<td class='line'>".$status."</td>";
				
				print "<td class='line'>".$row['descricao']."</td>";
				print "<td class='line'>".$row['diavencto']."</td>";
				print "<td class='line' align='center'><a onClick=\"redirect('abastitular.php?action=alterarcontrato&cod=".$row['id']."&cellStyle=true&alterarcontrato=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
				if(liberamenu('Excluir Contrato')=="N"){
					print "<td class='line'><a onClick=><img height='16' width='16' src='".ICONS_PATH."proibido.jpg' title='Exclus&atilde;o n&atilde;o permitida'></a></TD>";
				} else {
					print "<td class='line' align='center'><a onClick=\"confirmaAcao('".TRANS('MSG_DEL_REG')."','".$_SERVER['PHP_SELF']."', 'action=excluircontrato&cod=".$row['idtitular']."&idcontrato=".$row['id']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
				}
				
				if($_SESSION['modelocontrato'] == 1) {
					print "<td class='line' align='center'><a onClick=\"javascript:popup_doc('visualizacontrato.php?popup=true&cod=".$row['idtitular']."')\"><img height='16' width='16' src='".ICONS_PATH."search.png' title='"."Visualizar Contrato"."'></a></td>";
					print "<td class='line' align='center'><a onClick=\"javascript:popup_doc('visualizacartao.php?popup=true&cod=".$row['idtitular']."')\"><img height='16' width='16' src='".ICONS_PATH."search.png' title='"."Visualizar Cart�o Desconto"."'></a></td>";				
				}

				if($_SESSION['modelocontrato'] == 2) {
					print "<td class='line' align='center'><a onClick=\"javascript:popup_doc('visualizacontrato_2.php?popup=true&cod=".$row['idtitular']."')\"><img height='16' width='16' src='".ICONS_PATH."search.png' title='"."Visualizar Contrato"."'></a></td>";
				}

				// Modelo 03 - Itaguara
				if($_SESSION['modelocontrato'] == 3) {
				
				print "<td class='line' align='center'><a onClick=\"javascript:popup_doc('visualizacontrato_3.php?popup=true&cod=".$row['idtitular']."')\"><img height='16' width='16' src='".ICONS_PATH."search.png' title='"."Visualizar Contrato"."'></a></td>";
				print "<td class='line' align='center'><a onClick=\"javascript:popup_doc('visualizacartao_3.php?popup=true&cod=".$row['idtitular']."')\"><img height='16' width='16' src='".ICONS_PATH."search.png' title='"."Visualizar Cart�o Desconto"."'></a></td>";
								
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
			
			echo "<tr><td colspan='4' align='lefth'></td></tr>";			
		}
		
		}
		
	// Alterando Dados do Contrato
	if ((isset($_GET['action']) && $_GET['action']=="alterarcontrato") && empty($_POST['submit'])) {
		
	print "<FORM name='abastitular' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return validacon()\">";
		
	    if(liberamenu('Alterar Contrato')=="N"){
	    	$disabled = " disabled='disabled'";
	    	$clasbutton = " class='buttonDisabled'";
	    }

       	$query = "SELECT * FROM carne_contratos  WHERE id = ".$_GET['cod']." ";
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA EXECUÇÂO DA QUERY DE CONSULTA 1!');
		$row = mysqli_fetch_array($resultado);
		
		$nrocontrato = strzero($row['id'],6);
		if(!empty($row['nrocontrato'])){
			$nrocontrato = $row['nrocontrato'];
		};
		print "<BR><b><font size=2 color='blue'>"."Edi&ccedil;&atilde;o do Contrato"."</b></font><BR>";		
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."ID do Titular".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='idtitular' class='text4' id='idtitular' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($row['idtitular'],6)."' readonly='true' ></td>";

		//print "<TD class='line'><a href='titular.php'><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD>";
		
		print "</TR><TR>";
		
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Contrato".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='text4' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='".$nrocontrato."' ></td>";
		print "<INPUT type='text' name='titular' class='text4' id='idtitular' onkeyup=\"maskIt(this,event,'######')\" value='".$row['idtitular']."' hidden='true' >";
		print "<INPUT type='text' name='contrato' class='text4' id='idcontrato' onkeyup=\"maskIt(this,event,'######')\" value='".$_GET['cod']."' hidden='true' >";		
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Inicio Contrato".":</TD>";
		$dtcontrato = str_replace('/','',substr(converte_datacomhora($row['datacontrato']),0,10));
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtcontrato' id='iddtcontrato' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\"  onBlur='return doDateVenc(this.id,this.value, 4)' value='".mask($dtcontrato,'##/##/####')."'></td>";
		print "</TR><TR>";		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Plano".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";

		print "<select class='select3' name='plano' id='idplano'>";  
		$sql="select plano from carne_contratos where id =".$_GET['cod']."";
		$commit = mysqli_query($conec->con,$sql) or die ('Erro na Query '.$sql);
		$rowR = mysqli_fetch_array($commit);		
		print "<option value=-1>"."Selecione o Plano"."</option>";
				$sql="Select id,descricao from carne_tipoplano where status = 0 and unidade =".$_SESSION['s_local']." order by id";
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
		
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' $clasbutton value='Alterar Contrato' name='submit' $disabled>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";


	}
		
		
		print "</table>";		
		//print "</form>";		

		// Vari�veis convertidas
		if(isset($_POST['codigo'])) {
				
				$dtcontrato = Fdate($_POST['dtcontrato']);
				$zero = 0;
				$registro = date("Y-m-d H:i:s");
		}
		
	// Incluindo Contratos com INSERT
	if ((isset($_POST['submit'])  && ($_POST['submit'] == 'Salvar Contrato'))) {	

	    $query = "SELECT prox_cartao_desc,prox_contrato,dtnascdepobriga FROM config";
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA EXECU��O DA QUERY DE MAX ID!');
       	$config = mysqli_fetch_array($resultado);
       	$proximocartao = $config['prox_cartao_desc']+1;
       	$proximocontra = $config['prox_contrato']+1;
       	$dtnascdepobriga = $config['dtnascdepobriga'];
		
		
	if($_POST['plano'] <> -1) {
       	
		$query = "INSERT INTO carne_contratos (nrocontrato,plano,datacontrato,diavencto,status,idtitular,unidade,registro)".
				" values ('".$_POST['codigo']."','".$_POST['plano']."','".$dtcontrato."','".$_POST['diavencto']."','".$_POST['status']."','".$_POST['idtitular']."',".$_SESSION['s_local'].",'".$registro."')";
					
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
		
	}
		
		echo "<script>redirect('abastitular.php?action=alter&cod=".$_POST['idtitular']."&cellStyle=true');</script>";

	}
		
	// Alterando dados Contrato com UPDATE
	if ((isset($_POST['submit'])  && ($_POST['submit'] == 'Alterar Contrato'))) {	
					
			 $query2 = "select c.idtitular, t.nometitular from carne_contratos c join carne_titular t on t.id = c.idtitular where c.id ='".$_POST['contrato']."'";
			 $resultado2 = mysqli_query($conec->con,$query2) or die('Erro ... '.$query2);
			 $rowCont = mysqli_fetch_array($resultado2);
		
		 $Log1 = liberamenu('Alterar Contrato');
       	 $Log2 = $rowCont['idtitular'];
    	 $log3 = "Titular: ".$rowCont['nometitular'];;
    	 $log4 = false;
    	 $log5 = "";
 	     grava_log($_SESSION['s_uid'],$_SESSION['s_codoperacao'],$Log2,$log3,$log4,$log4,$log4,$log5);
							
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

		echo "<script>redirect('abastitular.php?action=alter&cod=".$_POST['idtitular']."&cellStyle=true&alterarcontrato=true');</script>";
			
	}
	
print "</div>";
 
print "</td>"; 
print "</tr>"; 
print "</table>";
print "</form>";
print "</body>"; 
print "</html>";
?>
