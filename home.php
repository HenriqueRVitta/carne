<?php 

 /*                        Copyright 2005 Flávio Ribeiro

         This file is part of OCOMON.

         OCOMON is free software; you can redistribute it and/or modify
         it under the terms of the GNU General Public License as published by
         the Free Software Foundation; either version 2 of the License, or
         (at your option) any later version.

         OCOMON is distributed in the hope that it will be useful,
         but WITHOUT ANY WARRANTY; without even the implied warranty of
         MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
         GNU General Public License for more details.

         You should have received a copy of the GNU General Public License
         along with Foobar; if not, write to the Free Software
         Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  */session_start();

	include ("PATHS.php");
	//include ("".$includesPath."var_sessao.php");
	include ("includes/functions/funcoes.inc");
	include ("includes/javascript/funcoes.js");

	//include ("includes/queries/queries.php");
	include ("".$includesPath."config.inc.php");
	// ("".$includesPath."languages/".LANGUAGE."");
	include ("".$includesPath."versao.php");

	include("includes/classes/conecta.class.php");
	include("includes/classes/auth.class.php");

	if ($_SESSION['s_logado']==0)
	{
	        print "<script>window.open('adm.php','_parent','')</script>";
		exit;
	}


	$conec = new conexao;
	$conec->conecta('MYSQL');

	$_SESSION['s_page_home'] = $_SERVER['PHP_SELF'];

	print "<html>";
	print "<head>";
	print "<title>CARN&Ecirc; ".VERSAO."</title>";
	print "<link rel=stylesheet type='text/css' href='includes/css/estilos.css.php'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],3);

	//Todas as áreas que o usuário percente
	$uareas = $_SESSION['s_area'];
	if ($_SESSION['s_uareas']) {
		$uareas.=",".$_SESSION['s_uareas'];
	}
	
	
	print "<br>";
	print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='50%'>";
	print "<tr><td colspan='7'></td></tr>";

	print "<tr><td style='{padding-left:5px;}'><div id='geral' >"; //style='{display:none}'

	print "</div></td></tr>"; //geral
	print "</table>";
	?>
	<SCRIPT LANGUAGE=javaScript>
	<!--
		function invertView(id) {
			var element = document.getElementById(id);
			var elementImg = document.getElementById('img'+id);
			var address = './includes/icons/';

			if (element.style.display=='none'){
				element.style.display='';
				elementImg.src = address+'close.png';
			} else {
				element.style.display='none';
				elementImg.src = address+'open.png';
			}
		}

	//-->
	</script>
	<?php 

print "</body>";
print "</html>";
?>