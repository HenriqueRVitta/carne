<?php /*                        Copyright 2005 FlÃ¯Â¿Â½vio Ribeiro

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
*/	session_start();

  	include ("../../includes/include_geral.inc.php");
  	include ("../../includes/functions/browser_detection.php");

	$browser = browser_detection('full');
	$_SESSION['s_browser'] = $browser[0];

	GLOBAL $conec;
	$conec = new conexao;
	$conec->conecta('MYSQL');

	
	if (AUTH_TYPE == "LDAP") {
		
		$conec->conLDAP(LDAP_HOST, LDAP_DOMAIN, LDAP_DN, LDAP_PASSWORD);
		$conecSec = new conexao; //Para testar no LDAP Labin
		$conecSec->conLDAP(LDAP_HOST, LDAP_DOMAIN_SEC, LDAP_DN, LDAP_PASSWORD);

		
		if ((senha_ldap($_POST['login'],$_POST['password'],'usuarios')=="ok") && ($conec->userLDAP($_POST['login'],$_POST['password']) || $conecSec->userLDAP($_POST['login'],$_POST['password'])))
		{
			
				$s_usuario=$_POST['login'];
		        $s_senha=$_POST['password'];
		        $s_local=$_POST['local'];
		        
			$queryOK = "SELECT u.*, n.*,s.* FROM usuarios u left join sistemas as s on u.AREA = s.sis_id ".
							"left join nivel as n on n.nivel_cod =u.nivel WHERE u.login = '".$_POST['login']."'";
			
			
			$resultadoOK = mysqli_query($conec->con,$queryOK) or die('IMPOSSÃ¯Â¿Â½VEL ACESSAR A BASE DE DADOS DE USUÃƒï¿½RIOS: LOGIN.PHP');
			$row = mysqli_fetch_array($resultadoOK);
			$s_nivel = $row['nivel'];

			if ($s_nivel<4){ //Verifica se nÃ¯Â¿Â½o estÃ¯Â¿Â½ desabilitado
				$s_logado=1;
			}

			$s_perfil_carne = $row['perfilcarne'];
			$s_nivel_desc = $row['nivel_nome'];
			$s_area = $row['AREA'];
			$s_uid = $row['user_id'];
			$s_area_admin =  $row['user_admin'];
			$s_screen = $row['sis_screen'];

			/*VERIFICA EM QUAIS Ã¯Â¿Â½REAS O USUÃ¯Â¿Â½RIO ESTÃ¯Â¿Â½ CADASTRADO*/
			$qryUa = "SELECT * FROM usuarios_areas where uarea_uid=".$s_uid.""; //and uarea_sid=".$s_area."
			$execUa = mysqli_query($conec->con,$qryUa) or die('IMPOSSÃ¯Â¿Â½VEL ACESSAR A BASE DE USUÃ¯Â¿Â½RIOS 02: LOGIN.PHP');
			$uAreas = "".$s_area.",";
			while ($rowUa = mysqli_fetch_array($execUa)){
				$uAreas.=$rowUa['uarea_sid'].",";
			}
			$uAreas = substr($uAreas,0,-1);
			$s_uareas = $uAreas;

			/*CHECA QUAIS OS MÃ¯Â¿Â½DULOS PODEM SER ACESSADOS PELAS Ã¯Â¿Â½REAS QUE O USUÃ¯Â¿Â½RIO PERTENCE*/
			$qry = "SELECT * FROM permissoes where perm_area in (".$uAreas.")";
			$exec = mysqli_query($conec->con,$qry) or die('IMPOSSÃ¯Â¿Â½VEL ACESSAR A BASE DE PERMISSÃ¯Â¿Â½ES: LOGIN.PHP');

			while($row_perm = mysqli_fetch_array($exec)){
				$s_permissoes[]=$row_perm['perm_modulo'];
			}
			$s_ocomon = 0;
			$s_invmon = 0;
			for ($i=0;$i<count($s_permissoes); $i++){
				if($s_permissoes[$i] == 1) $s_ocomon = 1;
				if($s_permissoes[$i] == 2) $s_invmon = 1;
			}

			$sqlPrefs = "SELECT * FROM uprefs WHERE upref_uid = ".$s_uid."";
			$execPrefs = mysqli_query($conec->con,$sqlPrefs);
			$rowPref = mysqli_fetch_array($execPrefs);			
			
			
			$sqlFormatBar = "SELECT * FROM config";
			$execFormatBar = mysqli_query($conec->con,$sqlFormatBar) or die ('NÃ¯Â¿Â½O FOI POSSÃ¯Â¿Â½VEL ACESSAR A TABELA DE CONFIGURAÃ¯Â¿Â½Ã¯Â¿Â½ES DO SISTEMA!');
			$rowFormatBar = mysqli_fetch_array($execFormatBar);
			if (strpos($rowFormatBar['conf_formatBar'],'%oco%')) {
				$formatBarOco = 1;
			} else {
				$formatBarOco = 0;
			}
			if (strpos($rowFormatBar['conf_formatBar'],'%mural%')) {
				$formatBarMural = 1;
			} else {
				$formatBarMural = 0;
			}
			
			$_SESSION['perfilcarne'] = $s_perfil_carne;
			$_SESSION['percentualfiltro'] = $rowFormatBar['percentualfiltro'];
			$_SESSION['nfseprefeitura'] = $rowFormatBar['nfseprefeitura'];
			$_SESSION['vlrbasecarne'] = $rowFormatBar['vlrbasecarne'];
			$_SESSION['s_acesso'] = "N";
			$_SESSION['s_logado'] = $s_logado;
			$_SESSION['s_usuario'] = $s_usuario;
			$_SESSION['s_uid'] = $s_uid;
			$_SESSION['s_senha'] = $s_senha;
			$_SESSION['s_nivel'] = $s_nivel;
			$_SESSION['s_nivel_desc'] = $s_nivel_desc;
			$_SESSION['s_area'] = $s_area;
			$_SESSION['s_uareas'] = $s_uareas;
			$_SESSION['s_permissoes'] = $s_permissoes;
			$_SESSION['s_area_admin'] = $s_area_admin;
			$_SESSION['s_ocomon'] = $s_ocomon;
			$_SESSION['s_invmon'] = $s_invmon;
			$_SESSION['s_allow_change_theme'] = $rowFormatBar['conf_allow_change_theme'];
			$_SESSION['s_screen'] = $s_screen;			
			$_SESSION['s_local'] = $s_local;

			$_SESSION['s_formatBarOco'] = $formatBarOco;
			$_SESSION['s_formatBarMural'] = $formatBarMural;
			$_SESSION['s_utilizacontrato'] = $rowFormatBar['contrato'];

			$_SESSION['ctacaixa'] = $rowFormatBar['ctacaixa'];
			$_SESSION['ctacontabil'] = $rowFormatBar['ctacontabil'];
			$_SESSION['centrocusto'] = $rowFormatBar['centrocusto'];
			$_SESSION['historicopadrao'] = $rowFormatBar['historicopadrao'];
			$_SESSION['codcliente'] = $rowFormatBar['codcliente'];
			 			
			if (!empty($rowPref['upref_lang'])){
				$_SESSION['s_language'] = $rowPref['upref_lang'];			
			} else {
				$_SESSION['s_language'] = $rowFormatBar['conf_language'];
			}

			$_SESSION['s_date_format'] = $rowFormatBar['conf_date_format'];

			$_SESSION['s_paging_full'] = 0;

			$_SESSION['s_page_size'] = $rowFormatBar['conf_page_size'];

			$_SESSION['s_allow_reopen'] = $rowFormatBar['conf_allow_reopen'];

			$_SESSION['s_allow_date_edit'] = $rowFormatBar['conf_allow_date_edit'];
			
			$_SESSION['s_ocomon_site'] = $rowFormatBar['conf_ocomon_site'];

			$sqlStyles = "SELECT * FROM temas t, uthemes u  WHERE u.uth_uid = ".$_SESSION['s_uid']." and t.tm_id = u.uth_thid";
			$execStyles = mysqli_query($conec->con,$sqlStyles) or die('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÃ¯Â¿Â½Ã¯Â¿Â½ES DO TEMA!<BR>'.$sqlStyles);
			$rowSty = mysqli_fetch_array($execStyles);
			$regs = mysqli_num_rows($execStyles);
			if ($regs==0){ //SE NÃ¯Â¿Â½O ENCONTROU TEMA ESPECÃ¯Â¿Â½FICO PARA O USUÃ¯Â¿Â½RIO
				unset($rowSty);
				$sqlStyles = "SELECT * FROM styles";
				$execStyles = mysqli_query($conec->con,$sqlStyles);
				$rowSty = mysqli_fetch_array($execStyles);
			}

			$_SESSION['s_colorDestaca'] = $rowSty['tm_color_destaca'];
			$_SESSION['s_colorMarca'] = $rowSty['tm_color_marca'];

			print "<script>redirect('../../index.php?".session_id()."');</script>";
		} else {

			print "<script>redirect('../../index.php?usu=".$_POST['login']."&inv=1');</script>";
			$conec->desconLDAP();
			$conecSec->desconLDAP();
			exit;
		}
		$conec->desconLDAP();
		$conecSec->desconLDAP();

	} else {

		if (senha_system($_POST['login'],$_POST['password'],'usuarios',$_POST['local'])=="ok")
		{

		        $s_usuario=$_POST['login'];
		        $s_senha=$_POST['password'];
		        $s_local=$_POST['local'];

			/*
		    $queryOK = "SELECT u.*, n.*,s.* FROM usuarios u left join sistemas as s on u.AREA = s.sis_id ".
							"left join nivel as n on n.nivel_cod =u.nivel WHERE u.login = '".$_POST['login']."'";
			*/
		        
		    $queryOK = "SELECT * FROM usuarios WHERE nome = '".$_POST['login']."'";
		    $resultadoOK = mysqli_query($conec->con,$queryOK) or die('IMPOSSÃƒï¿½VEL ACESSAR A BASE DE DADOS DE USUÃƒï¿½RIOS: LOGIN.PHP');
			$row = mysqli_fetch_array($resultadoOK);
			//$s_nivel = $row['nivel'];
			$s_nivel = 1;
			$s_coduser = $row['codigo'];
			$s_codvendedor = $row['vendedor'];
			$s_perfil_carne = $row['perfilcarne'];
			
			if ($s_nivel<4){ //Verifica se nÃƒÂ£o esta desabilitado
				$s_logado=1;
			}

			/*
			$s_nivel_desc = $row['nivel_nome'];
			$s_area = $row['AREA'];
			$s_uid = $row['user_id'];
			$s_area_admin =  $row['user_admin'];
			$s_screen = $row['sis_screen'];
			*/
			$s_nivel_desc = 'Administrador';
			$s_area = 1;
			$s_uid = $row['codigo'];
			$s_area_admin =  1;
			$s_screen = '';
			$acesso = trim($row['acesso1']).trim($row['acesso2']).trim($row['acesso3']).trim($row['acesso4']).trim($row['acesso5']).trim($row['acesso6']);
				
			$s_uareas = '';
			
			$s_permissoes[]=1;
			$s_permissoes[]=2;
			$s_ocomon = 1;
			$s_invmon = 1;
			
			
			$sqlFormatBar = "SELECT * FROM config"; //INFO FROM GENERAL CONF
			$execFormatBar = mysqli_query($conec->con,$sqlFormatBar) or die ('NÃƒÆ’O FOI POSSÃƒï¿½VEL ACESSAR A TABELA DE CONFIGURAÃƒâ€¡Ãƒâ€�ES DO SISTEMA!');
			$rowFormatBar = mysqli_fetch_array($execFormatBar);
			if (strpos($rowFormatBar['conf_formatBar'],'%oco%')) {
				$formatBarOco = 1;
			} else {
				$formatBarOco = 0;
			}
			if (strpos($rowFormatBar['conf_formatBar'],'%mural%')) {
				$formatBarMural = 1;
			} else {
				$formatBarMural = 0;
			}

			$_SESSION['perfilcarne'] = $s_perfil_carne;
			$_SESSION['percentualfiltro'] = $rowFormatBar['percentualfiltro'];			
			$_SESSION['nfseprefeitura'] = $rowFormatBar['nfseprefeitura'];
			$_SESSION['vendedor'] = $s_codvendedor;
			$_SESSION['vlrbasecarne'] = $rowFormatBar['vlrbasecarne'];
			$_SESSION['s_codoperacao'] = 0;
			$_SESSION['s_acesso'] = $acesso;
			$_SESSION['s_logado'] = $s_logado;
			$_SESSION['s_usuario'] = $s_usuario;
			$_SESSION['s_coduser'] = $s_coduser;
			$_SESSION['s_uid'] = $s_uid;
			$_SESSION['s_senha'] = $s_senha;
			$_SESSION['s_nivel'] = $s_nivel;
			$_SESSION['s_nivel_desc'] = $s_nivel_desc;
			$_SESSION['s_area'] = $s_area;
			$_SESSION['s_uareas'] = $s_uareas;
			$_SESSION['s_permissoes'] = $s_permissoes;
			$_SESSION['s_area_admin'] = $s_area_admin;
			$_SESSION['s_ocomon'] = $s_ocomon;
			$_SESSION['s_invmon'] = $s_invmon;
			$_SESSION['s_allow_change_theme'] = $rowFormatBar['conf_allow_change_theme'];
			$_SESSION['s_screen'] = $s_screen;
			$_SESSION['s_local'] = $s_local;
			

			$_SESSION['s_formatBarOco'] = $formatBarOco;
			$_SESSION['s_formatBarMural'] = $formatBarMural;
			$_SESSION['s_language'] = $rowFormatBar['conf_language'];
			$_SESSION['s_utilizacontrato'] = $rowFormatBar['contrato'];

			$_SESSION['ctacaixa'] = $rowFormatBar['ctacaixa'];
			$_SESSION['ctacontabil'] = $rowFormatBar['ctacontabil'];
			$_SESSION['centrocusto'] = $rowFormatBar['centrocusto'];
			$_SESSION['historicopadrao'] = $rowFormatBar['historicopadrao'];
			$_SESSION['codcliente'] = $rowFormatBar['codcliente'];
			
			$_SESSION['s_date_format'] = $rowFormatBar['conf_date_format'];

			$_SESSION['s_paging_full'] = 0;

			$_SESSION['s_page_size'] = $rowFormatBar['conf_page_size'];

			$_SESSION['s_allow_reopen'] = $rowFormatBar['conf_allow_reopen'];

			$_SESSION['s_allow_date_edit'] = $rowFormatBar['conf_allow_date_edit'];
			
			$_SESSION['s_ocomon_site'] = $rowFormatBar['conf_ocomon_site'];

			$sqlStyles = "SELECT * FROM temas t, uthemes u  WHERE u.uth_uid = ".$_SESSION['s_uid']." and t.tm_id = u.uth_thid";
			$execStyles = mysqli_query($conec->con,$sqlStyles) or die('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÃ¯Â¿Â½Ã¯Â¿Â½ES DO TEMA!<BR>'.$sqlStyles);
			$rowSty = mysqli_fetch_array($execStyles);
			$regs = mysqli_num_rows($execStyles);
			if ($regs==0){ //SE NÃ¯Â¿Â½O ENCONTROU TEMA ESPECÃ¯Â¿Â½FICO PARA O USUÃ¯Â¿Â½RIO
				unset($rowSty);
				$sqlStyles = "SELECT * FROM styles";
				$execStyles = mysqli_query($conec->con,$sqlStyles);
				$rowSty = mysqli_fetch_array($execStyles);
			}


			$_SESSION['s_colorDestaca'] = $rowSty['tm_color_destaca'];
			$_SESSION['s_colorMarca'] = $rowSty['tm_color_marca'];
			$_SESSION['s_colorLinPar'] = $rowSty['tm_color_lin_par'];
			$_SESSION['s_colorLinImpar'] = $rowSty['tm_color_lin_impar'];

			print "<script>redirect('../../index.php?".session_id()."');</script>";


		}
		else
		{

				print "<script>redirect('../../index.php?usu=".$_POST['login']."&inv=1');</script>";
				exit;
		}
	}



?>
