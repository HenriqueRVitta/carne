<?php 
header("Content-Type: text/html; charset=ISO-8859-1",true);

/*
 * No Banco de Dados MYSQL execute
 * ALTER DATABASE `sua_base` CHARSET = Latin1 COLLATE = latin1_swedish_ci
 * 
 * N�o distingue letras min�sculas e mai�sculas e nem caracteres acentuados e 
 * com cedilha, ou seja, o registro que cont�m a palavra �Intui��o� ser� retornado 
 * quando houver uma procura pela palavra �int�icao�.;
 * 
 */

/*      Copyright 2012 MCJ Assessoria Hospitalar e Inform�tica LTDA

         Desenvolvedorle: Carlos Henrique R Vitta

         Este software, tem como finalidade proporcionar aos colaboradores da MCJ
         (Gerentes,Técnicos e desenvolvedores) o registro das tarefas executadas
         nos projetos desenvolvidos pela empresa
*/

is_file( "./includes/config.inc.php" )
	or die( "Você precisa configurar o arquivo config.inc.php em INCLUDES/para iniciar o uso do !<br>Leia o arquivo <a href='LEIAME.txt'>LEIAME.TXT</a> para obter as principais informaçês sobre a instalaça!".
		"<br><br>You have to configure the config.inc.php file in INCLUDES/ to start!<br>Read the file <a href='README.txt'>README.TXT</a>to get the main informations about Installation!" );

	session_start();
	//session_destroy();
	if (!isset($_SESSION['s_language']))  $_SESSION['s_language']= "pt_BR.php";

	if (!isset($_SESSION['s_usuario']))  $_SESSION['s_usuario']= "";
	if (!isset($_SESSION['s_coduser']))  $_SESSION['s_coduser']= "";
	if (!isset($_SESSION['s_logado']))  $_SESSION['s_logado']= "";
	if (!isset($_SESSION['s_nivel']))  $_SESSION['s_nivel']= "";
	if (!isset($_SESSION['s_local']))  $_SESSION['s_local']=0;
		
	include ("PATHS.php");
	//include ("".$includesPath."var_sessao.php");
	include ("includes/functions/funcoes.inc");
	include ("includes/javascript/funcoes.js");
	include ("includes/queries/queries.php");
	include ("includes/config.inc.php");
	include ("includes/versao.php");

	include("includes/classes/conecta.class.php");
	$conec = new conexao;
	$conec->conecta('MYSQL') ;

	if (is_file("./includes/icons/favicon.ico")) {
		print "<link rel='shortcut icon' href='./includes/icons/favicon.ico'>";
	}

	$qryLang = "SELECT * FROM config";
	$execLang = mysqli_query($conec->con,$qryLang);
	$rowLang = mysqli_fetch_array($execLang) or die('Erro na query '.$execLang);
	if (!isset($_SESSION['s_language'])) $_SESSION['s_language']= $rowLang['conf_language'];
	if (!isset($_SESSION['s_modelomenu'])) $_SESSION['s_modelomenu']= $rowLang['modelomenu'];
	if (!isset($_SESSION['modelocarne']))  $_SESSION['modelocarne']=$rowLang['modelocarne'];
	if (!isset($_SESSION['modelocontrato']))  $_SESSION['modelocontrato']=$rowLang['modelocontrato'];
	if (!isset($_SESSION['where']))  $_SESSION['where']="";
	
	$modelomenu = "menu.php";
	switch ($_SESSION['s_modelomenu']) {
    case 1:
        	$modelomenu = "menu.php";
        break;
    case 2:
        	$modelomenu = "menutree.php";
        break;
    case 3:
        	$modelomenu = "menutree2.php";
        break;
    case 4:
        	$modelomenu = "menutree3.php";
        break;
    case 5:
        	$modelomenu = "menutree4.php";
        break;
        
	}
		
	$qrylocal = "SELECT razao from cadastro_unidades where codigo=".$_SESSION['s_local']."";
	$exelocal = mysqli_query($conec->con,$qrylocal) or die('Erro na query: ' .$qrylocal.mysqli_error($conec->con));
	$rowlocal = mysqli_fetch_array($exelocal);

	$uLogado = '';
	if(!empty($_SESSION['s_coduser'])) {
	 $uLogado = str_pad($_SESSION['s_coduser'], 3, "0", STR_PAD_LEFT)." ".trim($_SESSION['s_usuario']);
	}
	//$rowlocal['razao'];
	
	if (empty($uLogado)) {
		$USER_TYPE = "Usu&aacute;rio";//$TRANS['MNS_OPERADOR'];
		$uLogado = "N&atilde;o logado"; //$TRANS['MNS_NAO_LOGADO'];
		$logInfo = "<font class='topo'>".TRANS('MNS_LOGON')."</font>"; //$TRANS['MNS_LOGON']
		$hnt = TRANS('HNT_LOGON');
	} else {
		if ($_SESSION['s_nivel'] < 3) {
			$USER_TYPE = "Usu&aacute;rio";
		} else
			$USER_TYPE = "Usu&aacute;rio";
		$logInfo = "<font color='#1E90FF'>".TRANS('MNS_LOGOFF')."</font>";
		$hnt = TRANS('HNT_LOGOFF');
	}
	$marca = "HOME";
	
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"http://www.w3.org/TR/html4/loose.dtd\">";
echo "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
echo "<head>";

echo "<title>Sistema de Carn&ecirc; - &Aacute;rea Administrativa</title>";
echo "<link rel='stylesheet' href='includes/css/estilos.css.php'>";
echo "</head><body onLoad=\"setHeight('centro'); setHeight('centro2')\">";
echo "<table width='100%' border='0px' id='geral'><tr><td colspan='2'>";


$pnglogin= "<td align='right' width='70'><a href='".$commonPath."logout.php' title='".$hnt."'>".$logInfo."&nbsp;<img class='profile-img' src='//ssl.gstatic.com/accounts/ui/avatar_2x.png' style=\"{vertical-align:middle;}\" height='15' width='15' border='0' alt=''></a></td><td >|</td>";

if(!conexaointernet()){
		// Se Internet OFF LINE
		// utiliza png Local
		$pnglogin = "<td align='right' width='70'><a href='".$commonPath."logout.php' title='".$hnt."'>".$logInfo."&nbsp;<img src='includes/icons/password2.png' style=\"{vertical-align:middle;}\" height='15' width='15' border='0'></a></td><td >|</td>";
}



echo "<table class='topo' border='0' id='cabecalho'>";
echo "<tr><td ><img src='logo.png' height='95' width='350'></td>";
echo "<td align='left'><a href='http://chamados.mcj.com.br' target='_blank' style='color:blue; text-decoration: underline;'>CHAMADOS</a></td>";
echo "<td align='right'><font color='#1E90FF'>".$USER_TYPE.":</font><b> ".$uLogado."</b></td><td align='right'>|</td>".$pnglogin;
echo "</tr></table>";
		
print "<table class='barra' border='0' id='barra'><tr>";

	if (empty($_SESSION['s_permissoes'])&& $_SESSION['s_nivel']!=1){
		print "<td width='5%'>&nbsp;</td>";
		print "<td width='7%'>&nbsp;</td>";
		print "<td width='7%'>&nbsp;</td>";
		print "<td width='5%' >&nbsp;</td>";
		print "<td width='76%'>&nbsp;</td>";
	
	} else {

		
		$qryStyle = "SELECT * FROM temas t, uthemes u  WHERE u.uth_uid = ".$_SESSION['s_uid']." and t.tm_id = u.uth_thid";
		$execStyle = mysqli_query($conec->con,$qryStyle) or die('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÔES DE ESTILOS!<BR>'.$qryStyle);
		$rowStyle = mysqli_fetch_array($execStyle);
		$regs = mysqli_num_rows($execStyle);
		if ($regs==0){ //SE N�O ENCONTROU TEMA ESPEC�FICO PARA O USU�RIO
			unset ($rowStyle);
			$qryStyle = "SELECT * FROM styles";
			$execStyle = mysqli_query($conec->con,$qryStyle);
			$rowStyle = mysqli_fetch_array($execStyle);
		}

		print "<td id='HOME' width='5%' class='barraMenu'><a class='barra' onMouseOver=\"destaca('HOME')\" onMouseOut=\"libera('HOME')\" onclick=\"loadIframe('".$modelomenu."?sis=h','menu','home.php', 'centro',3,'HOME')\" >&nbsp;".TRANS('MNS_HOME')."&nbsp;</a></td>";
		$sis="";
		$sisPath="";
		$sistem="home.php";
		$marca = "HOME";
		print "<td width='72%'></td>";
		$conec->desconecta('MYSQL');
	}
	print "</tr></table>";

print "</td></tr>";

if ($_SESSION['s_logado']) {

	//BLOCO PARA RECARREGAR A P�GINA NO M�DULO ADMIN QUANDO FOR SELECIONADO NOVO TEMA
 	if (isset($_GET['LOAD']) && $_GET['LOAD'] == 'ADMIN'){
 		$PARAM = "&LOAD=ADMIN";
 		$marca = "ADMIN";
 	}else
 		$PARAM = "";

	print "<tr><td width='273'>";//id='centro'
	print "<iframe class='frm_menu' src='".$modelomenu."?".$sis."".$PARAM."' name='menu' align='left' width='270' height='1168' scrolling='no' frameborder='1' STYLE='{border-right: thin solid #999999; border-bottom: thin solid #999999;}'></iframe>";
	print "</td>";
	print "<td>";
	print "<iframe class='frm_centro' src='".$sisPath."".$sistem."'  name='centro' align='left' width='100%' height='1168' scrolling='auto' frameborder='0' STYLE='{border-bottom: thin solid #999999;}'></iframe></td>";
	print "</tr>";
	$copyright1 = "";
	$copyright2 = " style='display: none'";
		
	} else {

		$copyright1 = " style='display: none'";
		$copyright2 = "";
		print "<form name='logar' method='post' action='".$commonPath."login.php?".session_id()."' onSubmit=\"return valida()\">";
		print "<tr><td>";
		print "<table class='login' id='login' border='0'>";
		if (isset($_GET['inv']) ) {
			if ($_GET['inv']=="1") {
				print "<tr align=\"center\">".
					"<td colspan=2 align=\"center\"><font color='red' size=2>".TRANS('ERR_LOGON')."!"."<font></td>".
					"</tr>";
			}
		}

		if (isset($_GET['usu']) ) {
			$typedUser = $_GET['usu'];
		} else {
			$typedUser = "";
		}

		print "<tr><td ><input type='text' class='helplogin' name='login' value='".$typedUser."' id='idLogin' tabindex='1' placeholder=' Usu&aacute;rio'></td><td rowspan='2'></td></tr>". //class='help'
			"<tr><td><input type='password' class='helplogin' name='password'  id='idSenha' tabindex='2' placeholder=' Senha' spellcheck='false'></td></tr>".
			"<tr><td>";

		print "<SELECT class='helplogin' style='width:250px; height:30px' name='local' id='idlocal'>";
			print "<option value=-1 selected></option>";
			
			$query = "SELECT id,razao from cadastro_unidades order by razao";
			$resul = mysqli_query($conec->con,$query) or die('Nao foi possivel ler as informaoes de Local de Atua��o!');;
			while ($rownivel = mysqli_fetch_array($resul)){
				print "<option value='".$rownivel['id']."' selected>".substr($rownivel['razao'],0,45)."</option>";
			}
			print "</select></td>";

print "<br><div class='banner'>
<font size='5' face='Sans-Serif'".$copyright2."><b>"."M&oacute;dulo Carn&ecirc;"."</b></font><br><br>
<font size='2' face='Sans-Serif' color='#1E90FF'".$copyright2."><b>"."Vers&atilde;o 2.21.03"."</b></font><br>
<font size='1' face='Sans-Serif' color='#1E90FF'".$copyright1."><b><br>"."&copy; copyright - MCJ Assessoria Hospitalar e Inform�tica LTDA Todos os Direitos Reservados"."</b></font>
</div>";
print "</td></tr>";	
///<img class='profile-img' src='//ssl.gstatic.com/accounts/ui/avatar_2x.png' style=\"{vertical-align:middle;}\" height='30' width='30' border='0' alt=''>
			
		print "<tr><td><input type='submit' class='blogin' value='".TRANS('cx_login')."' tabindex='3'></tr></td>";
			
		print "</table>";
		print "</tr>";
		print "</form>";
		$conec->desconecta('MYSQL');
	}

print "<tr><td colspan='2' align='center' >"."<font size='1' face='Sans-Serif' color='#1E90FF'".$copyright1."><b>"."&copy; copyright - MCJ Assessoria Hospitalar e Inform�tica LTDA Todos os Direitos Reservados"."</b></font></td></tr>";

print "</table>";

print "</body></html>";


?>
<script type="text/javascript">
<!--
var GLArray = new Array();
	function loadIframe(url1,iframeName1, url2,iframeName2,ACCESS,ID) {

		var nivel_user = '<?php print $_SESSION['s_nivel'];?>';
		var HOM = document.getElementById('HOME');
		var OCO = document.getElementById('OCOMON');
		var INV = document.getElementById('INVMON');
		var ADM = document.getElementById('ADMIN');

		if (nivel_user <= ACCESS) {


			marca(ID);

			if (HOM != null)
				if (ID != "HOME") {
					HOM.style.background ="";
					HOM.style.color ="";
				}
			if (OCO != null)
				if (ID != "OCOMON") {
					OCO.style.background ="";
					OCO.style.color ="";
				}
			if (INV != null)
				if (ID != "INVMON") {
					INV.style.background ="";
					INV.style.color ="";
				}
			if (ADM != null)
				if (ID != "ADMIN") {
					ADM.style.background ="";
					ADM.style.color ="";
				}

			if (iframeName2!=""){
				if ((window.frames[iframeName1]) && (window.frames[iframeName2])) {
					window.frames[iframeName1].location = url1;
					return false;
				}
			} else
			if (window.frames[iframeName1]) {
				window.frames[iframeName1].location = url1;
				return false;
			}

			else
				return true;
		} else {
			window.alert('Acesso indispon&iacute;vel!');
			return true;
		}
	}

	function popup(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'Sobre','width=800,height=600,scrollbars=yes,statusbar=no,resizable=no');
		x.moveTo(10,10);
		return false
	}

	function showPopup(id){
		var obj = document.getElementById(id);
		if (obj.value==2) {
			return popup('sobre.php');
		} else
			return false;
	}

	function setHeight(id){

		var obj = document.getElementById(id);
		if (obj!=null) {
			//obj.style.height = screen.availHeight - 300;
			// Alterado em 27/03/2014
			obj.style.height = 600;
			marca('<?php print $marca;?>');
		} else {
			//document.logar.login.focus();
		}
		return true;
	}


	function mini_popup(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'_blank','dependent=yes,width=400,height=260,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);

		return false
	}

	function destaca(id){
			var obj = document.getElementById(id);
			var valor = '<?php isset($rowStyle['tm_barra_fundo_destaque'])? print $rowStyle['tm_barra_fundo_destaque']: print ""?>';
			if (valor!=''){
				if (obj!=null) {
					obj.style.background = valor;
				}
			}
	}

	function libera(id){
		if ( verificaArray('', id) == false ) {
			var obj = document.getElementById(id);
			if (obj!=null) {
				obj.style.background = ''; //#675E66
				//obj.className = "released";
			}
		}
	}

	function marca(id){
		var obj = document.getElementById(id);
		verificaArray('guarda', id);

		var valor = '<?php isset($rowStyle['tm_barra_fundo_destaque'])? print $rowStyle['tm_barra_fundo_destaque']: print ""?>';
		var valor2 = '<?php isset ($rowStyle['tm_barra_fonte_destaque'])? print $rowStyle['tm_barra_fonte_destaque']: print ""?>';
		if (valor != '' && valor2 != '') {
			if (obj!=null) {
				obj.style.background = valor;  //'#666666'
				obj.style.color = valor2;
				//obj.className = "marked";
			}
		}
		verificaArray('libera',id);
	}

	function verificaArray(acao, id) {
		var i;
		var tamArray = GLArray.length;
		var existe = false;

		for(i=0; i<tamArray; i++) {
			if ( GLArray[i] == id ) {
				existe = true;
				break;
			}
		}

		if ( (acao == 'guarda') && (existe==false) ) {  //
			GLArray[tamArray] = id;
		} else if ( (acao == 'libera') ) {
			//-----------------------------
			//-----------------------------
			var temp = new Array(tamArray-1); //-1
			var pos = 0;
			for(i=0; i<tamArray; i++) {
				if ( GLArray[i] == id ) {
					temp[pos] = GLArray[i];
					pos++;
				}
			}

			GLArray = new Array();
			var pos = temp.length;
			for(i=0; i<pos; i++) {
				GLArray[i] = temp[i];
			}
		}

		return existe;
	}

	function valida(){

		var ok = validaForm('idLogin','ALFAFULL','Usuário',1)
		if (ok) var ok = validaForm('idSenha','ALFAFULL','Senha',1);
		if (ok) var ok = validaForm('idlocal','COMBO','Unidade',1);
		return ok;
	}

-->
</script>

<!--
var obj = document.getElementById('tabela_ficha');
           var objOpcoes = document.getElementById('opcoesSel');
                     var valor = objOpcoes.style.height;
           valor = valor.replace('px', '');
           obj.style.height = screen.availHeight - valor - 300;
                     var form = document.forms[0];
           form.acao.value = 'EXIBE_FICHA';
           form.target = 'ficha';



-->

