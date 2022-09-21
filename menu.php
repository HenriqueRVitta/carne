<?php 
/*      Copyright 2012 MCJ Assessoria Hospitalar e Inform�tica LTDA

         Desenvolvedor: Carlos Henrique R Vitta

         Este software, tem como finalidade proporcionar aos colaboradores da MCJ
         (Gerentes,T�cnicos e desenvolvedores) o registro das tarefas executadas
         nos projetos desenvolvidos pela empresa
*/
	session_start();

	include ("PATHS.php");
	require_once("./includes/config.inc.php");
	include ("./includes/languages/".LANGUAGE."");
	require_once("./includes/functions/funcoes.inc");
	
	$OPERADOR_AREA = false;
	if(isset($_SESSION['s_area_admin']) && $_SESSION['s_area_admin'] == '1' && $_SESSION['s_nivel'] != '1')
		$OPERADOR_AREA = true;

	print "<style type='text/css'> </style>";
	?>
	<script language='JavaScript' type='text/javascript'>
	<?php 
	require_once $phplmDirPath.'libjs/layersmenu-browser_detection.js';
	?>
	</script>
	 <script language='JavaScript' type='text/javascript' src='includes/menu/phplm320/libjs/layerstreemenu-cookies.js'></script>
	<?php 
	require_once $phplmDirPath.'lib/PHPLIB.php';
	require_once $phplmDirPath.'lib/layersmenu-common.inc.php';
	require_once $phplmDirPath.'lib/treemenu.inc.php';
	
	$mid = new TreeMenu();

	$mid->setLibjsdir($phplmDirPath.'libjs/');
	$mid->setImgdir($phplmDirPath.'menuimages/');
	$mid->setImgwww($phplmDirPath.'menuimages/');
	$mid->setIcondir($phplmDirPath.'menuicons/');
	$mid->setIconwww($phplmDirPath.'menuicons/');

	$menuAuditor = ".|";
	

	    if(liberamenu('Titular')=="S"){
	
			$menuTitu   = "..|"."Titular"."|".$admDirPath."titular.php|"."Gerenciar Cadastro do Titular"."|".$iconsPath."sysadmin.png|centro";
			//$menuTitu   = "..|"."Titular"."|".$admDirPath."abastitular.php|"."Gerenciar Cadastro do Titular"."|".$iconsPath."sysadmin.png|centro";

	    } else $menuTitu="..|";
	    
   	    if(liberamenu('Dependentes')=="S"){
   	    		    
			$menuDepen = "..|"."Dependentes"."|".$admDirPath."dependente.php|"."Gerenciar Cadastro de Dependentes"."|".$iconsPath."groupevent.png|centro";   	    	

   	    } else 	$menuDepen="..|";


   	    if(liberamenu('Contratos')=="S"){
   	    
   	    	$menuContr  = "..|"."Contrato"."|".$admDirPath."contratos.php|"."Gerenciar Contratos"."|".$iconsPath."attach.png|centro";

   	    } else $menuContr="..|";
   	    
	    if(liberamenu('Tabelas Auxiliares')=="S"){
	    	
	    	$menuTabau  = "..|"."Tabelas Auxiliares"."|"."|"."Gerenciar Tabelas Auxiliares"."|".$iconsPath."brasil-flag-icon.png|centro";

	    	if(liberamenu('Tipo de Dependente')=="S"){
	    		$menuTipde  = "...|"."Tipo de Dependente"."|".$admDirPath."tipodependente.php|"."Gerenciar Tipo Dependente"."|".$iconsPath."groupevent.png|centro";
	    	} else $menuTipde="...|";

	    	if(liberamenu('Tipo de Plano')=="S"){	    	
	    		$menuPlano  = "...|"."Tipo de Plano"."|".$admDirPath."plano.php|"."Gerenciar Tipo Plano"."|".$iconsPath."personal.png|centro";
	    	} else $menuPlano="...|";

	    	if(liberamenu('Local de Pagamento')=="S"){	    	
	    		$menuLocal  = "...|"."Local de Pagamento"."|".$admDirPath."localpagto.php|"."Gerenciar Local Pagamento"."|".$iconsPath."orcamento.png|centro";
	    	} else $menuLocal="...|";
	    	
	    	if(liberamenu('Grupo')=="S"){	    	
	    		$menuGrupo  = "...|"."Grupo"."|".$admDirPath."grupo.php|"."Gerenciar Grupo"."|".$iconsPath."groupevent.png|centro";
	    	} else $menuGrupo="...|";
	    	
	    	if(liberamenu('Taxas')=="S"){	    	
	    		$menuTaxas  = "...|"."Taxas"."|".$admDirPath."taxas.php|"."Gerenciar Taxas"."|".$iconsPath."money_add.png|centro";
	    	} else $menuTaxas="...|";
	    	
	    	if(liberamenu('Taxas')=="S"){	    	
	    		$menuVendedor  = "...|"."Vendedor"."|".$admDirPath."vendedor.php|"."Gerenciar Vendedor"."|".$iconsPath."groupevent.png|centro";
	    	} else $menuVendedor ="...|";

	    	if(liberamenu('Taxas')=="S"){	    	
	    		$menuUsuarios = "...|"."Usu&aacute;rios"."|".$admDirPath."usuarios.php|"."Gerenciar Usuarios"."|".$iconsPath."groupevent.png|centro";
	    	} else $menuUsuarios ="...|";
	    	
	    }	else {

	    	$menuTabau  = ".|";
	    	$menuTipde  = ".|";
	    	$menuPlano  = ".|";
			$menuLocal  = ".|";
			$menuGrupo  = ".|";			
			$menuTaxas  = ".|";
			$menuVendedor = ".|";
			$menuUsuarios =  ".|";
			
	    }

	    if(liberamenu('Relatorio Cadastros Carne')=="S") {
		    // Menu Relat�rio dos Cadastros
	   	    $menuRelCad  = "..|"."Relat&oacute;rios do Cadastro"."|"."|"."Gerenciar Relatorios do cadastro"."|".$iconsPath."reports.png|centro";
	   	    // Menu Relat�rio dos Cadastros Titular
	   	    $menuRelCad2  = "...|"."Titular e Dependentes"."|".$admDirPath."relcadastros.php|"."Relatorios de Cadastros"."|".$iconsPath."reports.png|centro";
	   	    // Relatorio do Cadastro para emissao do cartao do Contribuinte
			$menuRelCad3  = "...|"."Cart&atilde;o do Contribuinte"."|".$admDirPath."relcartaocontribuinte.php|"."Relatorios de Cadastros"."|".$iconsPath."reports.png|centro";	   	    
	   	    //relcartaocontribuinte
	   	    
	    } else {
	   	    $menuRelCad  = "..|";
	   	    $menuRelCad2  = "..|";
	   	    $menuRelCad3  = "..|";
	    }
	    
	    
	    if(liberamenu('Financeiro')=="S") {
	    	
		$menuFina   = ".|"."Financeiro"."|"."|"."Gerenciar Financeiro"."|".$iconsPath."password.png|centro";
	
		    if(liberamenu('Registro de Pagamentos')=="S") {
				$menuFina1  = "..|"."Controle de Pagamento"."|"."|"."Gerenciar Pagamentos"."|".$iconsPath."comprar.png|centro";
		    } else $menuFina1=".|";

	    	if(liberamenu('Registro de Pagamentos')=="S"){
	    		$menuPagto  = "...|"."Registro de Pagamentos"."|".$admDirPath."pagamentoslista.php|"."Gerenciar Pagamentos"."|".$iconsPath."money_add.png|centro";
	    	} else $menuPagto=".|";
		    
	    	if(liberamenu('Relatorio Pagamentos Carne')=="S"){
	    		$menuConPagto  = "...|"."Relat&oacute;rios de Pagamentos"."|".$admDirPath."relpagamentos.php|"."Relatorio de Pagamentos"."|".$iconsPath."reports.png|centro";
	    	} else $menuConPagto="...|";
	    	
		    if(liberamenu('Gerar Boleto Cobranca')=="S") {
			    $menuFina2  = "...|"."Gerar Carn&ecirc; Cobran&ccedil;a"."|".$admDirPath."relgerarboleto.php|"."Gerar Boletos de Cobran&ccedil;a"."|".$iconsPath."password.png|centro";
		    } else 	$menuFina2="...|";
		    
		    if(liberamenu('Exporta Financeiro')=="S") {
			    $menuExporta  = "...|"."Exporta Financeiro"."|".$admDirPath."exportafinanceiro.php|"."Exporta os pagamentos para o Financeiro"."|".$iconsPath."orcamento.png|centro";
		    } else 	$menuExporta="...|";

		    if(liberamenu('Pagamentos por Grupo')=="S") {
			    $menuPagtoGru  = "...|"."Pagamentos por Grupo"."|".$admDirPath."pagamentogrupo.php|"."Registrar pagamentos por Grupo de Titular"."|".$iconsPath."money_add.png|centro";
		    } else 	$menuPagtoGru="...|";
		    
		    if(liberamenu('Gerar Parcelas Carne')=="S") {
				$menuParcela  = "..|"."Parcelamento de Carn&ecirc;"."|"."|"."Gerenciar Parcelamentos de Carn&ecirc;"."|".$iconsPath."comprar.png|centro";
		    } else $menuParcela=".|";

	    	if(liberamenu('Gerar Parcelas Carne')=="S"){
	    		$menuParcela1  = "...|"."Gerar Parcelas de Carn&ecirc;"."|".$admDirPath."geraparcelacarne.php|"."Gerar Parcelas Pagamentos"."|".$iconsPath."money_add.png|centro";
	    	} else $menuParcela1=".|";

	    	if(liberamenu('Relatorio Carne Parcelado')=="S"){
	    		$menuParcela2  = "...|"."Relat&oacute;rio de Carn&ecirc; Parcelado"."|".$admDirPath."relcarneparcelado.php|"."Relat&oacute;rio dos Carn&ecirc; parcelados"."|".$iconsPath."reports.png|centro";
	    	} else $menuParcela2=".|";


		    if(liberamenu('Gerar Boleto Cobranca')=="S") {
				$menuCobBan  = "..|"."Cobran&ccedil;a Banc&aacute;ria"."|"."|"."Gerenciar  Cobran&ccedil;a na rede Banc&aacute;ria"."|".$iconsPath."comprar.png|centro";
		    } else $menuCobBan=".|";
	    	
		    
		    if(liberamenu('Gerar Boleto Cobranca')=="S") {
			    $menuCobBan1  = "...|"."Gerar Boleto Banco"."|".$admDirPath."relgerarboletobancario.php|"."Gerar Boletos para Cobran&ccedil;a na rede Banc&aacute;ria"."|".$iconsPath."password.png|centro";
		    } else 	$menuCobBan1 ="...|";

		    if(liberamenu('Gerar Boleto Cobranca')=="S") {
			    $menuCobCarne  = "...|"."Gerar Carn&ecirc; Banco"."|".$admDirPath."relgerarcarnebancario.php|"."Gerar Carn&ecirc; para Cobran&ccedil;a na rede Banc&aacute;ria"."|".$iconsPath."password.png|centro";
		    } else 	$menuCobCarne ="...|";

		    if(liberamenu('Gerar Boleto Cobranca')=="S") {
			    $menuCobRemessa = "...|"."Gerar Remessa Banco"."|".$admDirPath."geraremessabanco.php|"."Gerar Remessas para Cobran&ccedil;a na rede Banc&aacute;ria"."|".$iconsPath."password.png|centro";
		    } else 	$menuCobRemessa ="...|";
		    
		    
		    if(liberamenu('Gerar Boleto Cobranca')=="S") {
			    $menuArqRetorno  = "...|"."Arquivo Retorno"."|".$admDirPath."arquivoretorno.php|"."Processa arquivo retorno do banco"."|".$iconsPath."orcamento.png|centro";
		    } else 	$menuArqRetorno ="...|";
		    
		    if(liberamenu('Gerar Boleto Cobranca')=="S") {
			    $menuNFSe  = "...|"."Gerar NFSe"."|".$admDirPath."nfsegerar.php|"."Gera NFSe conforme recebimento do Banco"."|".$iconsPath."orcamento.png|centro";
		    } else 	$menuNFSe ="...|";


		    if(liberamenu('Registro de Pagamentos')=="S") {
				$menuUnimed  = "..|"."Unimed"."|"."|"."Gerenciar  Cobran&ccedil;a Unimed"."|".$iconsPath."comprar.png|centro";
		    } else $menuUnimed=".|";
	    	
		    
		    if(liberamenu('Registro de Pagamentos')=="S") {
			    $menuUnimed1  = "...|"."Registro de Pagamentos"."|".$admDirPath."pagamentoslistaunimed.php|"."Gerenciar Pagamentos Unimed"."|".$iconsPath."money_add.png|centro";
		    } else 	$menuUnimed1 ="...|";

		    if(liberamenu('Registro de Pagamentos')=="S") {
			    $menuUnimed2  = "...|"."Relat&oacute;rios de Pagamentos"."|".$admDirPath."relpagamentosunimed.php|"."Relatorio de Pagamentos Unimed"."|".$iconsPath."reports.png|centro";
		    } else 	$menuUnimed2 ="...|";


		    
	    } else {
	    	
	    	$menuFina=".|";
	    	$menuFina1=".|";
	    	$menuFina2=".|";
	    	$menuFina3=".|";
	    	$menuPagto=".|";
			$menuConPagto=".|";
			$menuExporta=".|";  
			$menuPagtoGru=".|";	
			$menuParcela=".|";
			$menuParcela1=".|";
			$menuParcela2=".|";
			$menuCobBan=".|";
			$menuCobBan1=".|";
			$menuCobCarne=".|";
			$menuCobRemessa=".|";
			$menuArqRetorno=".|";
			$menuNFSe=".|";
			$menuUnimed=".|";
			$menuUnimed1=".|";
			$menuUnimed2=".|";			
			$menuConfi=".|";
			$menuConfi1=".|";
			$menuConfi2=".|";
			$menuConfi3=".|";
			
	    }
	    
    if(liberamenu('Configuracoes')=="S") {
		$menuConfi  = ".|"."Configura&ccedil;&otilde;es"."|"."|"."Gerenciar Configura&ccedil;&otilde;es do Sistema"."|".$iconsPath."kcontrol.png|centro";
    } else 	$menuConfi=".|";

    if(liberamenu('Configuracoes')=="S") {
	   $menuConfi1  = "..|"."Par&acirc;metros do Sistema"."|".$admDirPath."configuracoes.php|"."Gerenciar Configura&ccedil;&otilde;es do Sistema"."|".$iconsPath."kcontrol.png|centro";
    } else 	$menuConfi1 ="..|";

    if(liberamenu('Configuracoes')=="S") {
	   $menuConfi2  = "..|"."Par&acirc;metros da NFSe"."|".$admDirPath."configuracoesnfse.php|"."Gerenciar Configura&ccedil;&otilde;es da NFSe"."|".$iconsPath."kcontrol.png|centro";
    } else 	$menuConfi2 ="..|";
    
    
    if(liberamenu('Configuracoes')=="S") {
	    $menuConfi3  = "..|"."Cobran&ccedil;a Banc&aacute;ria"."|".$admDirPath."configuracoesbancaria.php|"."Gerenciar Configura&ccedil;&otilde;es Cobran&ccedil;a na rede Banc&aacute;ria"."|".$iconsPath."kcontrol.png|centro";
    } else 	$menuConfi3 ="..|";

    
	    if(liberamenu('Auditor do Sistema')=="S") {
		    // Menu Auditor do Sistema
			$menuAuditor = ".|"."Auditor do Sistema"."|".$admDirPath."relauditoria.php|"."Auditoria do Sistema"."|".$iconsPath."password.png|centro";
	    } else {
		$menuAuditor = ".|";
	    }	    
    
	    
    
$menuHome= ".|"."Cadastros"."|"."|"."Gerenciar Cadastros do Sistema"."|".$iconsPath."gohome.png|centro
".$menuTitu."
".$menuDepen."
".$menuContr."
".$menuTabau."
".$menuTipde."
".$menuPlano."
".$menuLocal."
".$menuGrupo."
".$menuTaxas."
".$menuVendedor."
".$menuUsuarios."
".$menuRelCad."
".$menuRelCad2."
".$menuRelCad3."
".$menuFina."
".$menuFina1."
".$menuPagto."
".$menuConPagto."
".$menuFina2."
".$menuExporta."
".$menuPagtoGru."
".$menuParcela."
".$menuParcela1."
".$menuParcela2."
".$menuCobBan."
".$menuCobBan1."
".$menuCobCarne."
".$menuCobRemessa."
".$menuArqRetorno."
".$menuNFSe."
".$menuUnimed."
".$menuUnimed1."
".$menuUnimed2."
".$menuConfi."
".$menuConfi1."
".$menuConfi2."
".$menuConfi3."
".$menuAuditor."";

	$mid->setMenuStructureString($menuHome);
	$mid->parseStructureForMenu('treemenu5');

// FIM DA INCLUSAO	PARA USUARIO SOMENTE CONSULTAS E ABERTURA DE OCORRENCIA


print "<html>";
print "<title>Carn�</title>";
print "<link rel=stylesheet type='text/css' href='includes/css/estilos.css.php'>";
print "</head><body class='menu'>";


	//Para compatibilizar os scripts da vers�o 1.40 na restaura��o da sess�o
	if (isset($_SESSION['s_page_ocomon']) && $_SESSION['s_page_ocomon'] == basename($_SESSION['s_page_ocomon'])) $_SESSION['s_page_ocomon'] = $ocoHome.$_SESSION['s_page_ocomon'];
	if (isset($_SESSION['s_page_simples']) && $_SESSION['s_page_simples'] == basename($_SESSION['s_page_simples'])) $_SESSION['s_page_simples'] = $simplesHome.$_SESSION['s_page_simples'];
	if (isset($_SESSION['s_page_invmon']) && $_SESSION['s_page_invmon'] == basename($_SESSION['s_page_invmon'])) $_SESSION['s_page_invmon'] = $invHome.$_SESSION['s_page_invmon'];
	if (isset($_SESSION['s_page_home']) && $_SESSION['s_page_home'] == basename($_SESSION['s_page_home'])) $_SESSION['s_page_home'] = $homeHome.$_SESSION['s_page_home'];
	if (isset($_SESSION['s_page_admin']) && $_SESSION['s_page_admin'] == basename($_SESSION['s_page_admin'])) $_SESSION['s_page_admin'] = $admHome.$_SESSION['s_page_admin'];


	if (isset($_SESSION['s_page_simples'])) $simplesHome = $_SESSION['s_page_simples']; else $simplesHome = $ocoDirPath."abertura_user.php?action=listall";
	if (isset($_SESSION['s_page_invmon'])) $invHome = $_SESSION['s_page_invmon']; else $invHome = $invDirPath."abertura.php";
	if (isset($_SESSION['s_page_home'])) $homeHome = $_SESSION['s_page_home']; else $homeHome = "home.php";
	if (isset($_SESSION['s_page_ocomon'])) $ocoHome = $_SESSION['s_page_ocomon']; else $ocoHome = $ocoDirPath."abertura.php";
	if (isset($_SESSION['s_page_admin'])) $admHome = $_SESSION['s_page_admin']; else $admHome = $admDirPath."sistemas.php";


	if (isset($_GET['LOAD']) && $_GET['LOAD'] == 'ADMIN'){//QUANDO A P�GINA FOR RECARREGADA PARA NOVO TEMA
		$where = TRANS('MNL_ADM');
		$menu="treemenu3";
		print "<script>window.parent.frames['centro'].location = '".$admHome."'</script>";
	}	else

	$menu="treemenu5";
	print "<script>window.parent.frames['centro'].location = '".$homeHome."'</script>";
	$where = "<a class='menu' target='centro'>"."Op&ccedil;&otilde;es de Menu"."</a>";
			
	print "<TABLE  class='header_centro' cellspacing='1' border='0' cellpadding='1' width='55%'><tr><td><b>".$where."</b></td></tr></table><br>";
	print "<table class='menu' >";

	if (!empty($menu)){
		print $mid->newTreeMenu($menu);
		}
	print "</table>";

print "</body></html>";

?>
<script type="text/javascript">
	 function popup(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'popup','dependent=yes,width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
      	//x.moveTo(100,100);
		x.moveTo(window.parent.screenX+100, window.parent.screenY+100);
		return false
     }
</script>