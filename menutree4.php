<!DOCTYPE html>
<html>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />
	<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
	<script type='text/javascript' src='includes/javascript/menu_jquery.js'></script>

<?php 

	session_start();

	include ("PATHS.php");
	require_once("./includes/config.inc.php");
	include ("./includes/languages/".LANGUAGE."");
	require_once("./includes/functions/funcoes.inc");
	
	$OPERADOR_AREA = false;
	if(isset($_SESSION['s_area_admin']) && $_SESSION['s_area_admin'] == '1' && $_SESSION['s_nivel'] != '1')
		$OPERADOR_AREA = true;

	if (isset($_SESSION['s_page_home'])) $homeHome = $_SESSION['s_page_home']; else $homeHome = "home.php";
	if (isset($_SESSION['s_page_admin'])) $admHome = $_SESSION['s_page_admin']; else $admHome = $admDirPath."sistemas.php";
		
?>
	
<style type="text/css">

#cssmenu,
#cssmenu ul,
#cssmenu ul li,
#cssmenu ul li a {
  margin: 0;
  padding: 0;
  border: 0;
  list-style: none;
  line-height: 1;
  display: block;
  position: relative;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}
#cssmenu {
  width: 255px;
  font-family: Helvetica, Arial, sans-serif;
  color: #ffffff;
}
#cssmenu ul ul {
  display: none;
}
.align-right {
  float: right;
}
#cssmenu > ul > li > a {
  padding: 15px 20px;
  border-left: 1px solid #93bbce;
  border-right: 1px solid #93bbce;
  border-top: 1px solid #93bbce;
  cursor: pointer;
  z-index: 2;
  font-size: 18px;
  font-weight: bold;
  text-decoration: none;
  color: #ffffff;
  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.35);
  background: #c8dce6;
  background: -webkit-linear-gradient(#c8dce6, #b6d1de);
  background: -moz-linear-gradient(#c8dce6, #b6d1de);
  background: -o-linear-gradient(#c8dce6, #b6d1de);
  background: -ms-linear-gradient(#c8dce6, #b6d1de);
  background: linear-gradient(#c8dce6, #b6d1de);
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15);
}
#cssmenu > ul > li > a:hover,
#cssmenu > ul > li.active > a,
#cssmenu > ul > li.open > a {
  color: #eeeeee;
  background: #b6d1de;
  background: -webkit-linear-gradient(#b6d1de, #a5c6d6);
  background: -moz-linear-gradient(#b6d1de, #a5c6d6);
  background: -o-linear-gradient(#b6d1de, #a5c6d6);
  background: -ms-linear-gradient(#b6d1de, #a5c6d6);
  background: linear-gradient(#b6d1de, #a5c6d6);
}
#cssmenu > ul > li.open > a {
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.15);
  border-bottom: 1px solid #93bbce;
}
#cssmenu > ul > li:last-child > a,
#cssmenu > ul > li.last > a {
  border-bottom: 1px solid #93bbce;
}
.holder {
  width: 0;
  height: 0;
  position: absolute;
  top: 0;
  right: 0;
}
.holder::after,
.holder::before {
  display: block;
  position: absolute;
  content: '';
  width: 6px;
  height: 6px;
  right: 20px;
  z-index: 10;
  -webkit-transform: rotate(-135deg);
  -moz-transform: rotate(-135deg);
  -ms-transform: rotate(-135deg);
  -o-transform: rotate(-135deg);
  transform: rotate(-135deg);
}
.holder::after {
  top: 17px;
  border-top: 2px solid #ffffff;
  border-left: 2px solid #ffffff;
}
#cssmenu > ul > li > a:hover > span::after,
#cssmenu > ul > li.active > a > span::after,
#cssmenu > ul > li.open > a > span::after {
  border-color: #eeeeee;
}
.holder::before {
  top: 18px;
  border-top: 2px solid;
  border-left: 2px solid;
  border-top-color: inherit;
  border-left-color: inherit;
}
#cssmenu ul ul li a {
  cursor: pointer;
  border-bottom: 1px solid #32373e;
  border-left: 1px solid #32373e;
  border-right: 1px solid #32373e;
  padding: 10px 20px;
  z-index: 1;
  text-decoration: none;
  font-size: 14px;
  color: #eeeeee;
  background: #49505a;
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1);
}
#cssmenu ul ul li:hover > a,
#cssmenu ul ul li.open > a,
#cssmenu ul ul li.active > a {
  background: #424852;
  color: #ffffff;
}
#cssmenu ul ul li:first-child > a {
  box-shadow: none;
}
#cssmenu ul ul ul li:first-child > a {
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1);
}
#cssmenu ul ul ul li a {
  padding-left: 30px;
}
#cssmenu > ul > li > ul > li:last-child > a,
#cssmenu > ul > li > ul > li.last > a {
  border-bottom: 0;
}
#cssmenu > ul > li > ul > li.open:last-child > a,
#cssmenu > ul > li > ul > li.last.open > a {
  border-bottom: 1px solid #32373e;
}
#cssmenu > ul > li > ul > li.open:last-child > ul > li:last-child > a {
  border-bottom: 0;
}
#cssmenu ul ul li.has-sub > a::after {
  display: block;
  position: absolute;
  content: '';
  width: 5px;
  height: 5px;
  right: 20px;
  z-index: 10;
  top: 11.5px;
  border-top: 2px solid #eeeeee;
  border-left: 2px solid #eeeeee;
  -webkit-transform: rotate(-135deg);
  -moz-transform: rotate(-135deg);
  -ms-transform: rotate(-135deg);
  -o-transform: rotate(-135deg);
  transform: rotate(-135deg);
}
#cssmenu ul ul li.active > a::after,
#cssmenu ul ul li.open > a::after,
#cssmenu ul ul li > a:hover::after {
  border-color: #ffffff;
}
</style>
	
</head>
<body style="background-image:url('mcj.png');">
<div id='cssmenu'>
<ul>
   <li class='active'><a href='#'><span><h3>Op&ccedil;&otilde;es de Menu</h3></span></a></li>
   <li class='has-sub'><a href='#'><span>Cadastros</span></a>
            <ul>
               <li class='last'><a href='admin/geral/titular.php' target="centro"><span>Titular</span></a></li>
               <li class='last'><a href='admin/geral/dependente.php' target="centro"><span>Dependentes</span></a></li>
               <li class='last'><a href='admin/geral/contratos.php' target="centro"><span>Contrato</span></a></li>
               
	         <li class='has-sub'><a href='#'><span>Tabelas Auxiliares</span></a>
	            <ul>
	               <li class='last'><a href='admin/geral/tipodependente.php' target="centro"><span>Tipo de Dependente</span></a></li>
	               <li class='last'><a href='admin/geral/plano.php' target="centro"><span>Tipo de Plano</span></a></li>
	            </ul>
	         </li>

            </ul>
         </li>
         <li class='has-sub'><a href='#'><span>Financeiro</span></a>
            <ul>
               <li><a href='#'><span>Controle de Pagamento</span></a></li>
               <li class='last'><a href='#'><span>Gerar Boleto Cobran&ccedil;a</span></a></li>
            </ul>
         </li>
   </li>
   <li class='has-sub'><a href='#'><span>Configura&ccedil;&otilde;es</span></a>
            <ul>
               <li><a href='admin/geral/configuracoes.php' target="centro"><span>Editar Configura&ccedil;&otilde;es</span></a></li>
            </ul>
   </li>
</ul>
</div>
</body>
</html>

