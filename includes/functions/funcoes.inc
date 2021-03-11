<?php  /*                        Copyright 2005 Flï¿½vio Ribeiro

         This file is part of CARNÊ.

         CARNÊ is free software; you can redistribute it and/or modify
         it under the terms of the GNU General Public License as published by
         the Free Software Foundation; either version 2 of the License, or
         (at your option) any later version.

         CARNÊ is distributed in the hope that it will be useful,
         but WITHOUT ANY WARRANTY; without even the implied warranty of
         MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
         GNU General Public License for more details.

         You should have received a copy of the GNU General Public License
         along with Foobar; if not, write to the Free Software
         Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  */

	define ( "MENU_USUARIO","<link rel='stylesheet' type='text/css' href='../includes/css/estilos.css.php'>");
	define ( "MENU_ADMIN","");

	function NVL($VALOR){
		if ($VALOR=='')
			return '&nbsp'; else
			return $VALOR;
	}

	function NL ($colspan=1) { //NEW LINE
		print "<tr><td colspan='".$colspan."'>&nbsp;</td></tr>";
	}

	function valueSeparator ($VALUE, $SEP){
		$NOTSEP = "";
		if ($SEP == ".") $NOTSEP = ",";
		if ($SEP == ",") $NOTSEP = ".";

		if(strpos($VALUE,$NOTSEP)) {
			$VALUE = str_replace($NOTSEP,$SEP, $VALUE);
		}
		if(!strpos($VALUE,$SEP)) $VALUE.=$SEP."00";
		return $VALUE;
	}


	function TRANS($INDEX, $SUGGEST='', $ASPAS=1){
		$aviso ="";
		//$aviso = "&nbsp;<font color=green>OK</font>";
		$destaca1 = ""; $destaca2 = "";
		//$destaca1 = "<u>"; $destaca2 = "</u>";
		//$destaca1 = "<font color=green>"; $destaca2 = "</font>";
		if (!isset($_SESSION['s_language']))  $_SESSION['s_language']= "en.php";
		
		if (is_file ("../../includes/languages/".$_SESSION['s_language'].""))
			include ("../../includes/languages/".$_SESSION['s_language'].""); else
		if (is_file ("../languages/".$_SESSION['s_language'].""))
			include ("../languages/".$_SESSION['s_language'].""); else
		if (is_file ("./includes/languages/".$_SESSION['s_language'].""))
			include ("./includes/languages/".$_SESSION['s_language'].""); else
		if (is_file ("../includes/languages/".$_SESSION['s_language'].""))
			include ("../includes/languages/".$_SESSION['s_language'].""); else {

			print "SORRY! WRONG PATH TO THE LANGUAGE FILE!<BR>See carne/includes/languages/ to see the available language files!";
			exit;
		}

		if (!isset($TRANS[$INDEX])) {
			if ($ASPAS) {
				return '<font color=red>$TRANS[\''.$INDEX.'\']="</font>'.$SUGGEST.'<font color=red>";</font>';
			} else
				return '<font color=red>$TRANS['.$INDEX.']="</font>'.$SUGGEST.'<font color=red>";</font>';
		}else
			return $destaca1.$TRANS[$INDEX].$destaca2.$aviso;
	}



	function start_session(){
		static $started=false;
		if(!$started){
		session_start();
		$started = true;
		}
	}

	function dump($variavel,$info="",$cor='magenta'){
		//if ( isLocked() ){
			if (trim($info)!="") {
				echo "<br><font color='".$cor."'>".$info."</font>";
			}

			if (is_array($variavel)) {
				echo "<pre>";
				print_r($variavel);
				echo "</pre>";
			} else {
				echo "<pre>";
				echo $variavel;
				echo "</pre>";
			}
		//}
	}

	function normaliza ($str){
		//$str2 = $str;
		//$str2 = toHtml($str2);
		return toHtml($str);
	}


	function reIndexArray(&$array) {
		$tmpArray = array();

		if (is_array($array)){
			$array = array_unique($array);
			foreach($array as $value) {
				$tmpArray[] = $value;
			}
			//unset ($array);
			for ($i = 0; $i<=count($array); $i++){
				array_pop($array);
			}
		}

		$array = $tmpArray;
	}



	function reIndexArray_05_03_07(&$array) {
		$tmpArray = array();

		if (is_array($array)){
			$array = array_unique($array);
			foreach($array as $value) {
				$tmpArray[] = $value;
			}
			//unset ($array);
			for ($i = 0; $i<count($array); $i++){
				array_pop($array);
			}
		}
		$array = $tmpArray;
	}

	function reIndexArray2(&$array) {
		$tmpArray = array();

		$array = array_unique($array);
		foreach($array as $value) {
			if ($value!="")
				$tmpArray[] = $value;
		}
		//unset ($array);
		for ($i = 0; $i<count($array); $i++){
			array_pop($array);
		}

		$array = $tmpArray;
	}



	function conecta($host, $bd, $user, $senha, $sistema)
	{
		// $host = servidor do mysql, $bd = nome do banco de dados, $user = usuï¿½rio do mysql, $senha = senha dp mysql, $sistema = sistema que esta sendo usado
		$conexao=mysql_connect($host,$user,$senha) or die(mysql_error());
		$db=mysql_select_db($bd,$conexao);
		if ($conexao == 0)
		{
			$retorno = "ERRO DE CONEXï¿½O - Servidor ".$host." - Sistema ".$sistema."<br>";
		}
		else
		if ($db == 0)
		{
			$retorno = "ERRO DE CONEXï¿½O - Banco de dados ".$bd." - Sistema ".$sistema."<br>";
		}
		else
		{
			$retorno = "ok";
		}
		return  $retorno;
	}

	function desconecta($conexao){
		mysql_close($conexao);
	}

	function senha_system($user,$password,$tabela,$local)
	{
		// $user = usuÃ¡rio para login, $senha = senha do usuï¿½rio, $tabela = nome da tabela de usuï¿½rios
		$localatua = $local;
		$login = md5($password);
		$query = "SELECT * from ".$tabela." where (nome = '".$user."' and senhaweb = '".$login."')";

		$resultado = mysql_query($query) or die(mysql_error());
		if ($resultado == 0)
		{
			$retorno = "ERRO DE LOGIN - Tabela ".$tabela."<br>";
		}
		else
		if (mysql_numrows($resultado) == 0)
		{
			$retorno = "ERRO DE LOGIN - UsuÃ¡rio ".$user."<br>";
		}
		else
		{
			$retorno = "ok";
			
			/*
			$permissao = mysql_fetch_array($resultado);

			if($permissao['user_id'] > 1) {

				if($permissao['local'] != $localatua) {

				 $retorno = "ERRO DE LOGIN - UsuÃ¡rio ".$user."<br>";
				 
				} else { $retorno = "ok"; }
				
			} else {
			
				$retorno = "ok";				

			}
			*/
			
			}
			
		return $retorno;
	}

	function senha_ldap($user,$password,$tabela)
	{
		// $user = usuï¿½rio para login, $senha = senha do usuï¿½rio, $tabela = nome da tabela de usuï¿½rios
		$login = md5($password);
		$query = "SELECT * from $tabela where (nome = 'strtoupper($user)')";
		$resultado = mysql_query($query) or die(mysql_error());
		if ($resultado == 0)
		{
			$retorno = "ERRO DE LOGIN - Tabela $tabela<br>";
		}
		else
		if (mysql_numrows($resultado) == 0)
		{
			$retorno = "ERRO DE LOGIN - Usuï¿½rio $user<br>";
		}
		else
		{
			$retorno = "ok";
		}
		return $retorno;
	}

/*
	function senha_sag($password,$cod) {

	 for ($i=0, $n=256; $i<$n; $i++) {
	  $vcar[$i]=257 - $i;
	 }

	 if($cod<3) {
	 	$password = limitartexto($password,12);
     }

	 for ($i=0, $n=256; $i<$n; $i++) {
	  $vcar[$i]=257 - $i;
	 }
     
	 return $retorno;
	 
	}
*/
	
	function limitartexto($string, $tamanho, $encode = 'UTF-8') {
    if( strlen($string) > $tamanho )
        $string = mb_substr($string, 0, $tamanho - 3, $encode) . '...';
    else
        $string = mb_substr($string, 0, $tamanho, $encode);
 
    return $string;
}	
	

	function geraLog($filename,$data,$usuario,$pagina,$acao){
		$conteudo = "DATA: $data\t";
		$conteudo.= "USUï¿½RIO: $usuario\t";
		$conteudo.= "PAGINA: $pagina\t";
		$conteudo.= " Aï¿½ï¿½O: $acao\t";
		$conteudo.="\n";

		if (is_writable($filename)) {

			if (!$handle = fopen($filename, 'a')) {
				$warning = "O arquivo nï¿½o pode ser aberto (".$filename.")!";
				exit;
			}
			if (!fwrite($handle, $conteudo)) {
				$warning = "O arquivo nï¿½o pode ser escrito (".$filename.")!";
				exit;
			}
			$warning= "Sucesso, (".$conteudo.") escrito no arquivo (".$filename.")!";
			fclose($handle);

			} else {
				$warning = "O arquivo ".$filename." nï¿½o tem permissï¿½o de escrita!";
			}

			return $warning;
	}

###############################################################################



#################################################################################
function cabecalho($logo,$msg1,$msg2,$titulo)
{
	return "
		<table width=80% border=0 cellspacing=1 cellpadding=1 align=center bgcolor=black>
		  <tr bgcolor=#FFFFFF>
		    <td width=30%>
		      <div align=center><font size=3 face=Arial, sans-serif><img src=./$logo></font></div>
		    </td>
		    <td width=40%>
		      <div align=center><font size=3 face=Arial, sans-serif> <b>$msg1</b></font></div>
		    </td>
		    <td width=30%>
		      <div align=center><font size=3 face=Arial, sans-serif><b><font size=2>$msg2</font></b></font></div>
		    </td>
		  </tr>
		  <tr bgcolor=#FFFFFF>
		    <td colspan=3>
		      <div align=center><font size=2 face=Arial, sans-serif><b>$titulo</b></font></div>
		    </td>
		  </tr>
		</table>

	";
}


function testaArea($area,$rowArea,$horarios){

	if (array_key_exists($rowArea,$horarios)){  //verifica se o cï¿½digo da ï¿½rea possui carga horï¿½ria definida no arquivo config.inc.php
		$area = $rowArea; //Recebe o valor da ï¿½rea de atendimento do chamado
	} else $area= 1; //Carga horï¿½ria default definida no arquivo config.inc.php
	return $area;
}

###################################################################################################

		//TIPO: tipo de relatï¿½rio - formataï¿½ï¿½o especï¿½fica
		//SQL: Query no banco de dados
		//CAMPOS: Array com o nome dos campos que eu quero imprimir no relatï¿½rio
		//HEADERS: Array com os cabeï¿½alhos de cada coluna do relatï¿½rio
		function gera_relatorio($tipo,$sql,$campos,$headers,$logo,$msg1,$msg2,$msg3){
			//Estilo aplicado nos relatï¿½rios
			print "<style type=\"text/css\"><!--";
			print "table.relatorio_1 {width:80%; margin-left:auto; margin-right: auto; text-align:left;
					border: 0px; border-spacing:1 ;background-color:white; padding-top:10px;
					page-break-after: auto;}";
			print "td.linha {font-family:arial; font-size:12px; line-height:0.8em;}";
			print "td.linha_par {font-family:arial; font-size:12px; line-height:0.8em; background-color:#EAEAEA}";
			print "td.linha_impar {font-family:arial; font-size:12px; line-height:0.8em;background-color:#C8C8C8}";
			print "td.cabs {font-family:arial; font-size:12px; font-weight:bold; background-color: #A3A352;}";
			print "td.foot {font-family:arial; font-size:12px; font-weight:bold; line-height:0.8em; background-color: #A8A8A8;}";
			//print "{page-break-after: always;}";
			print "--></STYLE>";

			if (count($campos) != count($headers))  {//Verifica se cada campo da tabela possui um header!
				print "O nï¿½mero de campos nï¿½o fecha com o nï¿½mero de headers!";
				exit;
			}//if campos == headers


			$commit = mysql_query($sql);
	        	$linhas = mysql_num_rows($commit);
			$k=0;
			$fields ="";
			$total = "";
			while($k < mysql_num_fields($commit)){ //quantidade de campos retornados da consulta
				$field = mysql_fetch_field($commit,$k);//Retorna um objeto com informaï¿½ï¿½es dos campos
				$fields.=$field->name; //Joga os nomes dos campos para uma string
				$k++;
			} // while

			if ($linhas==0) {
			    print "Nenhuma linha retornada pela consulta";
			}else{
				print cabecalho($logo,$msg1,$msg2,$msg3);


				if ($tipo==1|| $tipo==0) {//Tipo definido de relatï¿½rio //
					print "<TABLE class=\"relatorio_1\" cellpadding=4>";
					print "<tr>";
					for ($i=0; $i<count($headers); $i++){
						print "<td class=\"cabs\">".$headers[$i]."</td>";
					}//for
					print "</tr>";
					$l = 0; //variï¿½vel que controla se a linha ï¿½ par ou impar
					while($row=mysql_fetch_array($commit)){
						if ($l % 2) {
							$par_impar = "_par";
						} else {
							$par_impar = "_impar";
						}//if - else
						print "<tr>";
						for ($i=0; $i<count($campos); $i++){ //IMPRIME CAMPO A CAMPO
							print "<td class=\"linha$par_impar\">";
							$sep = explode(",",$campos[$i]); //Se algum campo passado tem mais de um argumento ï¿½ separado
							for ($j=0; $j<count($sep); $j++){
								$pos = strpos($fields,$sep[$j]); //Verifica na string gerada se o argumento passado existe como um nome de campo
								if ($pos===false) {
									print $sep[$j]; //Se o campo nï¿½o existe ï¿½ impresso literalmente
								} else
									print $row[$sep[$j]]; //Se o campo existe ï¿½ impresso seu valor;
							} //for J//
							print "</td>";
						}//for i//
						print "</tr>";
						$l++;
					} // while
						//RODAPï¿½
						print "<tr>";
						for ($i=0; $i<count($campos); $i++){ //IMPRIME CAMPO A CAMPO
							if ($i==count($campos)-1) {
							    $total = $linhas;
							} else
							if ($i==count($campos)-2) {
							    $total = "TOTAL";
							}
							print "<td class=\"foot\">$total</td>";
						}//for
						print "</tr>";
					print "</table>";
				} else

				if ($tipo==2) {//Outra formataï¿½ï¿½o para saï¿½da do relatï¿½rio//
					print "<table class=\"relatorio_1\">";
					while($row=mysql_fetch_array($commit)){
						//print "<tr>";
						for ($i=0; $i<count($campos); $i++){ //IMPRIME CAMPO A CAMPO
							print "<tr>";
							//print "<td>".$headers[$i]."</td>";
							print "<td>";
							$sep = explode(",",$campos[$i]); //Se algum campo passado tem mais de um argumento ï¿½ separado
							for ($j=0; $j<count($sep); $j++){
								$pos = strpos($fields,$sep[$j]); //Verifica na string gerada se o argumento passado existe como um nome de campo
								if ($pos===false) {
									print $sep[$j]; //Se o campo nï¿½o existe ï¿½ impresso literalmente
								} else {
									print $row[$sep[$j]]; //Se o campo existe ï¿½ impresso seu valor;
								}
								//print "</td>";
							} //for J//
							print "</td>";


						}//for i//
						print "</tr>";
					}//while
					print "</table>";
				} //fim do tipo==2 //
			} //else linhas != 0 //
		}//funï¿½ï¿½o


function menu_admin(){
	return "";
}



function veremail($email)
{
	if(!eregi("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$",$email))
		$passou = "erro";
	else
		$passou = "ok";
	return $passou;
}


function mensagem($msg){

	return "<TABLE class='msg' ". //#EFEFE7  "STYLE='{border-bottom:  solid #999999; border-top:  thin solid #999999; border-left:thin  ".
					//"solid #999999; border-right: thin solid #999999; }' ".
			"cellspacing='1' border='0' cellpadding='1' align='center' width='320'>".//#5E515B
			"<TR>".
			"<TD align='center'><b>".$msg."<b></td>".
                	"</TR>".
		"</TABLE>";

}

################################################################################

function putComma ($vetor){
	$chamados="";
	if (is_array($vetor)){

		if (count($vetor) >= 1) {

			for ($i=0; $i<count($vetor); $i++){
				$chamados.= "$vetor[$i],";
			}
			if (strlen($chamados)>0) {
				$chamados = substr($chamados,0,-1);
			}
		} else
		//$chamados = $vetor[0];
		$chamados = $vetor;
	} else
		$chamados = $vetor;
	return $chamados;
}

###############################################################################

function converte_dma_para_amd($dataform)//converte a data do formato dd/mm/aaaa para aaaa-mm-dd
{
        if (empty($dataform)) {
        	$data = "";
        } else {

		if (strpos($dataform," ")){
			$datatransHora = explode(" ",$dataform);

			$hora = $datatransHora[1];
			$datatrans = $datatransHora[0];
		} else {
			$hora = "00:00:00";
			$datatrans = $dataform;
		}
		if (strpos($datatrans,"-")) {
			$datatransf = explode("-",$datatrans);
		} else
			$datatransf = explode("/",$datatrans);
		$data = "$datatransf[2]-$datatransf[1]-$datatransf[0]";
        }
        return $data;
}


function converte_datacomhora($dataform)//pega a data do formato aaaa-mm-dd +hora, e transforma p/ dd-mm-aaaa +hora
{
	if (empty($dataform)){
		$datacompleta = "";
	} else {
		//separando o dia e a hora
	        $data_hora = explode(" ",$dataform);
		$data="$data_hora[0]";
		$hora="$data_hora[1]";

		//formatando o dia em dd-mm-aaaa
		$datatransf = explode("-",$data);
        	$data = "$datatransf[2]/$datatransf[1]/$datatransf[0]";
		$datacompleta = $data." ".$hora;
	}
        return $datacompleta;
}

################################################################################

function date_difference($data1, $data2)
{

	if ($data1>$data2) {
		$temp = $data1;
		$data1 = $data2;
		$data2 = $temp;
	}
        $s = strtotime($data2)-strtotime($data1);
        $d = intval($s/86400);
        $s -= $d*86400;
        $h = intval($s/3600);
        $s -= $h*3600;
        $m = intval($s/60);
        $s -= $m*60;

        $v = $d." dias, ".$h.":".$m.":".$s;
        return $v;
}


function date_diff2($data1, $data2)
{
        $s = strtotime($data2)-strtotime($data1);
        $d = intval($s/86400);
        $s -= $d*86400;
        $h = intval($s/3600);
        $s -= $h*3600;
        $m = intval($s/60);
        $s -= $m*60;

        $v = $d;
        return $v;
}

function date_diff_dias($data1, $data2)
{
	if (empty($data1) || empty($data2)){
		$v = "";
	} else {
		$s = strtotime($data2)-strtotime($data1);
		$d = intval($s/86400);
		$s -= $d*86400;
		$h = intval($s/3600);
		$s -= $h*3600;
		$m = intval($s/60);
		$s -= $m*60;

		$v = $d;
	}
	return $v;
}



function segundos_em_horas($segundos){

	$h=0;
	$m=0;

	while($segundos >=60){
		if ($segundos >= 3600) {
			while ($segundos >= 3600) { //ORDEM DE HORAS
				$segundos-=3600;
					$h+=1;
			}
		} else
		if ($segundos >= 60) {
			while ($segundos >= 60) {//ORDEM DE MINUTOS
				$segundos-=60;
				$m+=1;
			}
		}
	}

	if(strlen($h) == 1){$h = "0".$h;}; //Coloca um zero antes
	if(strlen($m) == 1){$m = "0".$m;}; //Coloca um zero antes
	if(strlen($segundos) == 1){$segundos = "0".$segundos;}; //Coloca um zero antes

	$horas = $h.":".$m.":".$segundos;

	return $horas;
}


##UTILIZAR SEMPRE PARA GRAVAR NO BANCO
function FDate ($data){ //Retorna saï¿½da no formado AAAA-MM-DD HH:mm:SS

	if (!empty($data)) {
		$ano = 0;
		$mes = 0;
		$dia = 0;
		$hora = 0;
		$minuto = 0;
		$segundo = 0;
		$Time = "00:00:00";

		$DateParts= explode(" ",$data);
		$Date = $DateParts[0];
		if (isset($DateParts[1]))
			$Time = $DateParts[1];

		//formato brasileiro com hora!!!
		if (ereg ("([0-9]{1,2})[/|-]([0-9]{1,2})[/|-]([0-9]{4}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $Date." ".$Time, $sep)) {

			$dia = $sep[1];
			$mes = $sep[2];
			$ano = $sep[3];
			$hora = $sep[4];
			$minuto = $sep[5];
			$segundo = $sep[6];
		} else
			//formato americano com hora
		if (ereg ("([0-9]{4})[/|-]([0-9]{1,2})[/|-]([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $Date." ".$Time, $sep)) {
			$dia = $sep[3];
			$mes = $sep[2];
			$ano = $sep[1];
			$hora = $sep[4];
			$minuto = $sep[5];
			$segundo = $sep[6];
		} else
			print "Invalid date format!!";
		//$data = strtotime($ano."-".$mes."-".$dia." ".$hora.":".$minuto.":".$segundo);
		$data = $ano."-".$mes."-".$dia." ".$hora.":".$minuto.":".$segundo;
		return $data;
	} else
		return "0000-00-00 00:00:00";
//...
}


#######################################################
## Exibe a data formatada conforme definido no menu de administraï¿½ï¿½o
function formatDate($DATE, $TIMEFORMAT = "" )
{
	if($DATE != "0000-00-00 00:00:00" && !empty($DATE)) {
	//if($DATE != "0000-00-00 00:00:00") {
		if (strtotime($DATE) != -1) {
			//DATE = "2007/05/25"; //Always American format
			$Date = $_SESSION['s_date_format'];

			if ($TIMEFORMAT!=""){
				if (strpos($_SESSION['s_date_format']," ")){
					$DateParts= explode(" ",$_SESSION['s_date_format']);
					$Date = $DateParts[0];
					$Time = $DateParts[1];
				}
			}
			//$output = strftime($Date.$TIMEFORMAT, strtotime($DATE));
			$output = strftime($Date, strtotime($DATE));

			return $output;
		} else
			return "INVALID DATE FORMAT!";
	} else {
		return "";
	}
}

function diff_em_segundos ($data1, $data2) {

		$data1 = FDate($data1);
		$data2 = FDate($data2);

		$s = strtotime($data2)-strtotime($data1);
		$secs = $s;

		$d = intval($s/86400);
		$s -= $d*86400;
		$h = intval($s/3600);
		$s -= $h*3600;
		$m = intval($s/60);
		$s -= $m*60;

		if(strlen($h) == 1){$h = "0".$h;}; //Coloca um zero antes
		if(strlen($m) == 1){$m = "0".$m;}; //Coloca um zero antes
		if(strlen($s) == 1){$s = "0".$s;}; //Coloca um zero antes

		$v = $d." dias ".$h.":".$m.":".$s;
		$min = $m;

		$dias = $d;

		$horas = $h;
		$minutos = $m;
		$segundos = $s;

		$dias *=86400; //Dia de 24 horas
		$horas *=3600;
		$minutos *=60;
		$segundos +=$dias+$horas+$minutos;

		$h = intval($segundos/3600);
		$m = intval($segundos/60);

		return $secs;
}



function datam($dataform)//pega a data informada, e formata dd-mm-aaaa c/ a hora atual
{
	if (empty($dataform)){
		$data = "";
	} else {
		$data = "";
		$datatrans = array();
		$datatransHora = array();
		//$dataArray = array();

		if (strpos($dataform," ")){
			$datatransHora = explode(" ",$dataform);

			$hora = $datatransHora[1];
			$datatrans = $datatransHora[0];
		} else {
			$datatransHora[0] = $dataform;
			$hora = strftime("%H:%M:%S");
		}

		if (strpos($datatransHora[0],"-")){
				$datatrans = explode("-",$datatransHora[0]);
		} else
		if (strpos($datatransHora[0],"/")) {
				$datatrans = explode("/",$datatransHora[0]);
		}
		$data = $datatrans[2]."-".$datatrans[1]."-".$datatrans[0];
		$data = $data; //." ".$hora;
	}
        return $data;
}

function datam2($dataform)
{
	if (empty($dataform)) {
		$data = "";
	} else {
		$datatrans = explode("-",$dataform);
		$data = "$datatrans[2]/$datatrans[1]/$datatrans[0]";
		$hora = strftime("%H:%M:%S");
		$data = $data." ".$hora;
	}
        return $data;
}


function inteiro ($string){

	settype ($string,"integer");
	return $string;
}


function horaAhora($valor,$hora){

	for ($i=1; $i<=$valor; $i++){
		if ($hora>=23) {
			$hora=-1;
		}
		$hora++;
	}
	return $hora;
}

function datab($dataform)
{
        if (empty($dataform)) {
        	$data = "";
        } else {
		$hora = substr($dataform,11,17);
		$data = substr($dataform,0,10);
		$datatrans = explode("-",$data);
		$data = "$datatrans[2]/$datatrans[1]/$datatrans[0]";
		$data = $data." ".$hora;
	}
        return $data;
}
function datab2($dataform)
{
        if (empty($dataform)) {
        	$data = "";
        } else {
		$hora = substr($dataform,11,17);
		$data = substr($dataform,0,10);
		$datatrans = explode("-",$data);
		$data = "$datatrans[2]/$datatrans[1]/$datatrans[0]";
	}

        return $data;
}


function dataRED($dataform)
{
		//Formato de Data Reduzido
        $hora = substr($dataform,11,17);
        $data = substr($dataform,0,10);

        $hora = substr($hora,0,5);

        $datatrans = explode("-",$data);
		$anored= substr($datatrans[0],2,2);
        $data = "$datatrans[2]/$datatrans[1]/$anored";
        $data = $data."-".$hora;

        return $data;
}
#################################################################################


function ajusta ($palavra,$num) {
	$limite = $num;
	$i = strlen($palavra);
	while ($i < $limite) {
		$palavra.='-';
		$i++;
	}

	return $palavra;

}

################################################################################

function envia_email_new_user($login, $nome, $email, $rand, $site)
{
	$msg = "";
	$msg .= "SISTEMA CARNÊ\t\n";
	$msg .= "Prezado ".$nome."\t\n";
	$msg .= "Sua solicitaï¿½ï¿½o para criaï¿½ï¿½o do login: ".$login." foi bem sucedida!\t\n";
	$msg .= "Para confirmar sua inscriï¿½ï¿½o clique no link abaixo: \t\n";
	$msg .= "".$site."/carne/geral/confirma.php?rand=".$rand." \t\n";

	$mailheaders = "From: SISTEMA_carne\n";
	$mailheaders .= "Replay-to: ".$email."\n\n";

	mail($email, "CONFIRMAï¿½ï¿½O DE CADASTRO", $msg, $mailheaders);
	return "OK";
}


function noHtml ($string) {

	$string=addslashes($string);
	//$string=addslashes($string);
	return stripslashes(htmlspecialchars($string, ENT_QUOTES));
}

function toHtml ($string){

   $trans_tbl = get_html_translation_table (HTML_ENTITIES);
   $trans_tbl = array_flip ($trans_tbl);
   return strtr ($string, $trans_tbl);
}

function isIn ($pattern, $values){

	$found = false;
	if (strpos($values,",")){
		$valuesArray = explode(",",$values);

		for ($i = 0; $i < count($valuesArray); $i++){
			if ($valuesArray[$i] == (int)$pattern) $found = true;

		}

	} else
	if ($values == (int)$pattern) $found = true;

	return $found;

}

function sepComma($value,$array){
	if (strpos($value,",")) {
		$array = explode(",",$value);

	} else $array = $value;

	return $array;
}



function random (){
	$rand ="";
	for ($i=0; $i<10; $i++) {
		$rand.= mt_rand(1,300);
	}

	return ($rand);
}

function transbool ($bool){
	if ($bool == 0) $trans = TRANS('NOT'); else
	if ($bool == 1) $trans = TRANS('YES'); else
	$trans = $bool;

	return $trans;
}


function transvars ($msg, $arrayEnv) {

	foreach ($arrayEnv as $id=> $var){
		$msg = str_replace($id, $var, $msg);
	}

	return $msg;
}


function send_mail($event, $e_destino,$mailConf, $msgConf, $envVars){


//$event: Tipo de evento, os eventos sï¿½o definidos pela situaï¿½ï¿½o (abertura, ediï¿½ï¿½o ou assentamento)
//        e pelo destino (usuï¿½rio, operador ou ï¿½rea)
//$e_destino: e-mail de destino
//$mailConf: array com as informaï¿½ï¿½es de conexï¿½o smtp
//$msgConf: array com as informaï¿½ï¿½es de mensagem
//$envVar: array com as variï¿½veis de ambiente
	if (is_file( "./.root_dir" )) {
 		require_once("./includes/phpmailer/class.phpmailer.php");
 	} else {
 		require_once("../../includes/phpmailer/class.phpmailer.php");
 	}

	$mail = new PHPMailer();
	$mail->SetLanguage("en", "../../includes/phpmailer/language/");


	if ($mailConf['mail_issmtp']) {
		$mail->IsSMTP();
	}                                      // set mailer to use SMTP
	$mail->Host = $mailConf['mail_host'];  // specify main and backup server
	$mail->SMTPAuth = $mailConf['mail_isauth'];     // turn on SMTP authentication
	$mail->Username = $mailConf['mail_user'];  // SMTP username
	$mail->Password = $mailConf['mail_pass']; // SMTP password

	$mail->From = $mailConf['mail_from'];
	//$mail->IsHTML(false);
	$mail->IsHTML($mailConf['mail_ishtml']);                 //$mail->IsHTML(true); // set email format to HTML



	$mail->FromName = $msgConf['msg_fromname'];
	$mail->AddAddress($e_destino);
	//$mail->AddAddress("josh@example.net", "Josh Adams");
	//$mail->AddAddress("ellen@example.com");                  // name is optional
	$mail->AddReplyTo($msgConf['msg_replyto'], "SISTEMA CARNÊ");

	//$mail->WordWrap = 50;                                 // set word wrap to 50 characters
	//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
	//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
	$mail->Subject = transvars($msgConf['msg_subject'],$envVars);

	if ($mailConf['mail_ishtml']){
		$mail->Body = transvars($msgConf['msg_body'],$envVars);
	} else {
		$mail->Body = nl2br(transvars($msgConf['msg_altbody'],$envVars));
	}

	$mail->AltBody = nl2br(transvars($msgConf['msg_altbody'],$envVars));

		## TO USE ONLY FOR DEVELOPERS WHEN DEBUGING
// 		dump ($mailConf['mail_issmtp'],'IsSMTP');
// 		dump ($mail->Host,'Host');
// 		dump ($mail->SMTPAuth,'SMTPAuth');
// 		dump ($mail->Username,'Username');
// 		dump ($mail->Password,'Password');
// 		dump ($mail->From,'From');
// 		dump ($mailConf['mail_ishtml'],'IsHTML');
// 		dump ($mail->FromName,'FromName');
// 		dump ($e_destino,'AddAddress');
// 		dump ($msgConf['msg_replyto'],'AddReplyTo');
// 		dump ($mail->Subject,'Subject');
// 		dump ($mail->Body,'Body');
// 		dump ($mail->AltBody,'AltBody');
// 		exit;

	if(!$mail->Send())
	{
		echo "A mensagem nï¿½o pï¿½de ser enviada. <p>";
		echo "Mailer Error: " . $mail->ErrorInfo;
		exit;
	}

	return true;
}


function mail_send($mailConf,$to,$cc,$subject,$body,$replyto, $envVars){

//$mailConf: array com as informaï¿½ï¿½es de conexï¿½o smtp

	if (is_file( "./.root_dir" )) {
 		require_once("./includes/phpmailer/class.phpmailer.php");
 	} else {
 		require_once("../../includes/phpmailer/class.phpmailer.php");
 	}

	$mail = new PHPMailer();
	$mail->SetLanguage("en", "../../includes/phpmailer/language/");

	if ($mailConf['mail_issmtp']) {
		$mail->IsSMTP();
	}                                      // set mailer to use SMTP
	$mail->Host = $mailConf['mail_host'];  // specify main and backup server
	$mail->SMTPAuth = $mailConf['mail_isauth'];     // turn on SMTP authentication
	$mail->Username = $mailConf['mail_user'];  // SMTP username
	$mail->Password = $mailConf['mail_pass']; // SMTP password

	$mail->From = $mailConf['mail_from'];
	$mail->FromName =$mailConf['mail_from_name'];
	$mail->IsHTML($mailConf['mail_ishtml']);


	$mail->AddReplyTo($replyto, $mail->FromName =$mailConf['mail_from_name']);

	// 	$mail->AddAddress($to);
	// 	if (isset($cc) && $cc!=""){
	// 		$mail->AddCC($cc);
	// 	}

	$sepTo = explode(",",$to);
	if (is_array($sepTo)){
		$recipients = count($sepTo);
	} else
		$recipients = 1;

	for ($i = 0; $i < $recipients; $i++) {
		$mail->AddAddress(trim($sepTo[$i]));
	}

	if (isset($cc) && $cc!=""){

		$sepCC = explode(",",$cc);

		if (is_array($sepCC)){
			$copies = count($sepCC);
		} else
			$copies = 1;

		for ($i = 0; $i < $copies; $i++) {
			$mail->AddCC(trim($sepCC[$i]));
		}
	}


	//$mail->Subject = $subject;
	$mail->Subject = transvars($subject,$envVars);

	if ($mailConf['mail_ishtml']){
		//$mail->Body = nl2br($body);
		$mail->Body = nl2br(transvars($body,$envVars));
	} else {
		//$mail->Body = nl2br($body);
		$mail->Body = nl2br(transvars($body,$envVars));
	}

	$mail->AltBody = nl2br($body);

	if(!$mail->Send())
	{
		echo "A mensagem nï¿½o pï¿½de ser enviada. <p>";
		echo "Mailer Error: " . $mail->ErrorInfo;
		//exit;
	}

	return true;
}



function showArray($array){
	print "<pre>";
	print_r ($array);
	print "</pre>";
	return true;
}

function isImage($type){
	if(eregi("^image\/(pjpeg|jpeg|png|gif|x-ms-bmp)$", $type))
		return true; else
		return false;
}

function noSpace($WORD) {

	$newWord = trim(str_replace(" ","_", $WORD));
	return $newWord;
}


function upload($img_file,$config, $FILE_TYPES="%%IMG%") {
	// Prepara a variï¿½vel caso o formulï¿½rio tenha sido postado
	$arquivo = isset($_FILES[$img_file]) ? $_FILES[$img_file] : FALSE;

/*	$config = array();
	// Tamano mï¿½ximo da imagem, em bytes
	$config["tamanho"] = 307200; //Aproximadamente 300k
	// Largura Mï¿½xima, em pixels
	$config["largura"] = 800;
	// Altura Mï¿½xima, em pixels
	$config["altura"] = 600;*/

	$saida = "OK";

	if($arquivo) {
		$erro = array();

		// Verifica o mime-type do arquivo para ver se ï¿½ de imagem.
		// Caso fosse verificar a extensï¿½o do nome de arquivo, o cï¿½digo deveria ser:
		//
		// if(!eregi("\.(jpg|jpeg|bmp|gif|png){1}$", $arquivo["name"])) {
		//      $erro[] = "Arquivo em formato invï¿½lido! A imagem deve ser jpg, jpeg, bmp, gif ou png. Envie outro arquivo"; }
		//
		// Mas, o que ocorre ï¿½ que alguns usuï¿½rios mal-intencionados, podem pegar um vï¿½rus .exe e simplesmente mudar a extensï¿½o
		// para alguma das imagens e enviar. Entï¿½o, nï¿½o adiantaria em nada verificar a extensï¿½o do nome do arquivo.

		$mime = array();

		$TYPE = explode("%",$FILE_TYPES);

		reIndexArray2($TYPE);

		$mime['PDF'] = "application\/pdf";
		$mime['TXT'] = "text\/plain";
		$mime['RTF'] = "application\/rtf";
		$mime['HTML'] = "text\/html";
		$mime['IMG'] = "image\/(pjpeg|jpeg|png|gif|x-ms-bmp)";

		$mime['ODF'] = "application\/vnd.oasis.opendocument.(text|spreadsheet|presentation|graphics)";
		$mime['OOO'] = "application\/vnd.sun.xml.(writer|calc|draw|impress)";
		$mime['MSO'] = "application\/(msword|vnd.ms-excel|vnd.ms-powerpoint)";
		//application/vnd.ms-powerpoint
		//application/vnd.sun.xml.writer; application/vnd.sun.xml.calc; application/vnd.sun.xml.draw; application/vnd.sun.xml.impress

		//if(preg_match('/^(.*)\.(doc|txt|pdf|xls|htm|html|rtf)$/', $arquivo["type"])) //application/pdf
		//if(!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $arquivo["type"])) {

		$typeOK = false;
		$types = "";
		for ($i=0; $i<count($TYPE); $i++) {
			if (strlen($types)>0) $types.=", ";
			$types.=$TYPE[$i];
			if(eregi("^".$mime[$TYPE[$i]]."$", $arquivo["type"])) {
				$typeOK = true;
			}
		}

		if (!$typeOK) {
			$erro[] = "Arquivo em formato invï¿½lido! Os Tipos permitidos sï¿½o: ".$types;

		} else  {
			// Verifica tamanho do arquivo
			if($arquivo["size"] > $config["conf_upld_size"]) {
				$kbytes = $config["conf_upld_size"]/1024;

				$erro[] = "Arquivo em tamanho muito grande! O arquivo deve ser de no mï¿½ximo " . $kbytes . " Kbytes.";
			}

			if(eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $arquivo["type"])) {
				// Para verificar as dimensï¿½es da imagem
				$tamanhos = getimagesize($arquivo["tmp_name"]);
				// Verifica largura
				if($tamanhos[0] > $config["conf_upld_width"]) {
					$erro[] = "Largura da imagem nï¿½o deve ultrapassar " . $config["conf_upld_width"] . " pixels";
				}
				// Verifica altura
				if($tamanhos[1] > $config["conf_upld_height"]) {
					$erro[] = "Altura da imagem nï¿½o deve ultrapassar " . $config["conf_upld_height"] . " pixels";
				}
			}
		}

		if(sizeof($erro)) {
			$saida = "<b>ALERTA:</b><br />";
			foreach($erro as $err) {
				$saida.=" - ".$err."<br>";
			}
		}

		if($arquivo && !sizeof($erro)) {
			//echo "<BR><BR>UPLOAD REALIZADO COM SUCESSO!";
			$saida = "OK";
		}

	} else print "ERRO NO ARQUIVO!";

	return $saida;

}


	function montaArray($mysql_result,$mysql_fetch_array){
		$k=0;
		$valores = array();
		while($k < mysql_num_fields($mysql_result)){ //quantidade de campos retornados da consulta
			$field = mysql_fetch_field($mysql_result,$k);//Retorna um objeto com informaï¿½ï¿½es dos campos
			$valores[$field->name] = $mysql_fetch_array[$field->name];
			$k++;
		} // while
		return $valores;
	}

	function findAllOccurences($Haystack, $needle, $limit=0)
	{
		$Positions = array();
		$currentOffset = 0;
		$count=0;
		while(($pos = strpos($Haystack, $needle, $offset)) && ($count < $limit || $limit == 0))
		{
			$Positions[] = $pos;
			$offset = $pos + strlen($needle);
			$count++;
		}
		return $Positions;
	}


	//Destaca as entradas '$string' em um texto '$texto' passado
	function destaca($string, $texto) {

		$string.="|".noHtml($string)."|".toHtml($string);

		//$string = htmlentities($string);

		$pattern = explode("|", $string);
		$pattern = array_unique($pattern);
		$destaque = array();

		reIndexArray($pattern);

		$texto2 = toHtml(strtolower($texto));
		//$texto2 = htmlentities($texto);

		for ($i=0; $i<count($pattern); $i++) {
			$destaque = "<font style='{background-color:yellow}'>".$pattern[$i]."</font></b>";
			$texto2 = str_replace(strtolower($pattern[$i]), strtolower($destaque), $texto2);
		}

		return $texto2;
	}


	function valida($CAMPO, $VALOR, $TIPO, $OBRIGATORIO, &$ERR) {

		$LISTA = array();
		$LISTA['INTFULL'] = "/^\d*$/"; //INTEIRO QUALQUER
		$LISTA['INTEIRO'] = "/^[1-9]\d*$/"; //Nï¿½O INICIADOS POR ZERO
		$LISTA['MAIL']= "/^[\w!#$%&'*+\/=?^`{|}~-]+(\.[\w!#$%&'*+\/=?^`{|}~-]+)*@(([\w-]+\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/";
		$LISTA['DATA'] = "/^((0?[1-9]|[12]\d)\/(0?[1-9]|1[0-2])|30\/(0?[13-9]|1[0-2])|31\/(0?[13578]|1[02]))\/(19|20)?\d{2}$/";
		$LISTA['DATA_'] = "/^((0?[1-9]|[12]\d)\-(0?[1-9]|1[0-2])|30\-(0?[13-9]|1[0-2])|31\-(0?[13578]|1[02]))\-(19|20)?\d{2}$/";
		$LISTA['DATAHORA'] = "/^(((0?[1-9]|[12]\d)\/(0?[1-9]|1[0-2])|30\/(0?[13-9]|1[0-2])|31\/(0?[13578]|1[02]))\/(19|20)?\d{2})[ ]([0-1]\d|2[0-3])+:[0-5]\d:[0-5]\d$/";
		$LISTA['MOEDA'] = "/^\d{1,3}(\.\d{3})*\,\d{2}$/";
		$LISTA['MOEDASIMP'] = "/^\d*\,\d{2}$/";
		$LISTA['ETIQUETA'] = "/^[1-9]\d*(\,\d+)*$/"; //expressï¿½o para validar consultas separadas por vï¿½rgula;
		$LISTA['ALFA'] = "/^[A-Z]|[a-z]([A-Z]|[a-z])*$/";
		$LISTA['ALFANUM'] = "/^([A-Z]|[a-z]|[0-9])([A-Z]|[a-z]|[0-9])*\.?([A-Z]|[a-z]|[0-9])([A-Z]|[a-z]|[0-9])*$/"; //Valores alfanumï¿½rias aceitando separaï¿½ï¿½o com no mï¿½ximo um ponto.
		$LISTA['ALFAFULL'] = "/^[\w!#$%&'*+\/=?^`{|}~-]+(\.[\w!#$%&'*+\/=?^`{|}~-]+)*$/";
		$LISTA['FONE'] = "/^(([+][\d]{2,2})?([-]|[\s])?[\d]*([-]|[\s])?[\d]+)+([,][\s]([+][\d]{2,2})?([-]|[\s])?[\d]*([-]|[\s])?[\d]+)*$/";
		$LISTA['COR'] = "/^([#]([A-F]|[a-f]|[\d]){6,6})|([I][M][G][_][D][E][F][A][U][L][T])$/";

		$ERRO = array();
		$ERRO['OBRIGATORIO'] = "O campo ".$CAMPO." ï¿½ obrigatï¿½rio!";
		$ERRO['INTFULL'] = "O campo ".$CAMPO." deve conter apenas numeros inteiros!";
		$ERRO['INTEIRO'] = "O campo ".$CAMPO." deve conter apenas numeros inteiros nï¿½o iniciados por ZERO!";
		$ERRO['MAIL']= "Formato de e-mail invï¿½lido!";
		$ERRO['DATA'] ="Formato de data invalido! dd/mm/aaaa";
		$ERRO['DATA_'] = "Formato de data invalido! dd-mm-aaaa";
		$ERRO['DATAHORA'] = "Formato de data invalido! dd/mm/aaaa H:m:s";
		$ERRO['MOEDA'] = "Formato de moeda invï¿½lido!";
		$ERRO['MOEDASIMP'] ="Formato de moeda invï¿½lido! XXXXXX,XX";
		$ERRO['ETIQUETA'] = "o Formato do campo ".$CAMPO." deve ser de valores inteiros nï¿½o iniciados por Zero e separados por vï¿½rgula!";
		$ERRO['ALFA'] = "Esse o campo ".$CAMPO." sï¿½ aceita carateres do alfabeto sem espaï¿½os!";
		$ERRO['ALFANUM'] ="O campo ".$CAMPO." sï¿½ aceita valores alfanumï¿½ricos sem espaï¿½os ou separados por um ponto(no mï¿½ximo um)!";
		$ERRO['ALFAFULL'] = "O campo ".$CAMPO." sï¿½ aceita valores alfanumï¿½ricos sem espaï¿½os!";
		$ERRO['FONE'] = "O campo ".$CAMPO." sï¿½ aceita valores formatados para telefones (algarismos, traï¿½os e espaï¿½os) separados por vï¿½rgula.";
		$ERRO['COR'] ="O campo ".$CAMPO." sï¿½ aceita valores formatados para cores HTML! Ex: #FFCC99";

		if ($LISTA[$TIPO] == '') {
			print "ï¿½NDICE INVï¿½LIDO!";
			return false;
		} else
		if ($OBRIGATORIO) {
			if ($VALOR=='') {
				$ERR = $ERRO['OBRIGATORIO'];
				return false;
			} else
			if (preg_match($LISTA[$TIPO], $VALOR)) {
				return true;
			} else {
				$ERR = $ERRO[$TIPO];
				return false;
			}

		} else
		if ($VALOR != ''){
			if (preg_match($LISTA[$TIPO], $VALOR)) {
				return true;
			} else {
				$ERR = $ERRO[$TIPO];
				return false;
			}

		} else {
			return true;
		}
	}


	function getDirFileNames($dir, $ext='php|PHP') {
		// Abre um diretorio conhecido, e faz a leitura de seu conteudo de acordo com a extensï¿½o solicitada

		$array = array();
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if($file != '..' && $file !='.' && $file !=''){
						if (eregi("\.(".$ext."){1}$", $file))
							$array[]=$file;
					}
				}
				closedir($dh);
			}
		}
		return $array;
	}

	function dPgetIniSize($val) {
		$val = trim($val);
		if (strlen($val <= 1)) return $val;
		$last = $val{strlen($val)-1};
		switch($last) {
			case 'k':
			case 'K':
				return (int) $val * 1024;
			break;
			case 'm':
			case 'M':
				return (int) $val * 1048576;
			break;
			default:
				return $val;
		}
	}

	// Retorna Zero a esquerda
	// $val = numero e $tam = quantos zeros a esquerda
	function Strzero($val,$tam) {
		
		$val = str_pad($val, $tam, "0", STR_PAD_LEFT);
		return $val;
	}

		// Funï¿½ï¿½o que mostra o nï¿½mero da semana no ano
	// Basta dar o dia, o mï¿½s e o ano, que serï¿½ retornado o nï¿½mero da semana no ano daquele dia do mï¿½s.
	// A funï¿½ï¿½o funciona assim: ï¿½ calculado quantos dias se passaram entre o dia 1 de janeiro do ano atï¿½ a data fornecida, 
	// isso ï¿½ realizado com o mktime e o date. Depois, dividimos por 7 para dar o nï¿½mero de semanas 
	// contidos nesse tempo. intval retorna o inteiro dessa divisï¿½o, e assim, a funï¿½ï¿½o retorna o 
	// nï¿½mero de semanas que se passaram, ou seja, o nï¿½mero da semana no ano.
	function semana_do_ano($dia,$mes,$ano){
	
	$var=intval( date('z', mktime(0,0,0,$mes,$dia,$ano) ) / 7 ) + 1;
	
	return $var;
	
	}

	
	//Dias da semana pelo nï¿½mero da semana
	function DiaSemNrSemana($nrsem) {
	
	// Semana desejada~
    $wanted_week = $nrsem; // 34
     
    $week = date('W');
    $week_diff = $wanted_week - $week;
    $ts_week = strtotime("$week_diff week");
    $day_of_week = date('w', $ts_week);
     
    for ($i = 0; $i <= 6; $i++) {
     
        // TimeStamp contendo os dias da semana de domingo a sabado
        $ts = strtotime( ($i-$day_of_week)." days", $ts_week );
     
        echo date('d/m/Y', $ts) . "\n";
    }
	}
	

	// Funï¿½ï¿½o que mostra o nï¿½mero da semana corrente
	function nr_semana_corrente(){
    	$var = strftime("%U");
    return $var;

	}
	

	/**
	* Funï¿½ï¿½o para calcular o prï¿½ximo dia ï¿½til de uma data
	* Formato de entrada da $data: AAAA-MM-DD
	*/
	function proximoDiaUtil($data, $saida = 'd/m/Y') {
	// Converte $data em um UNIX TIMESTAMP
	$timestamp = strtotime($data);
	
	// Calcula qual o dia da semana de $data
	// O resultado serï¿½ um valor numï¿½rico:
	// 1 -> Segunda ... 7 -> Domingo
	$dia = date('N', $timestamp);
	
	// Se for sï¿½bado (6) ou domingo (7), calcula a prï¿½xima segunda-feira
	if ($dia >= 6) {
	$timestamp_final = $timestamp + ((8 - $dia) * 3600 * 24);
	} else {
	// Nï¿½o ï¿½ sï¿½bado nem domingo, mantï¿½m a data de entrada
	$timestamp_final = $timestamp;
	}
	
	return date($saida, $timestamp_final);
	}

/**
 * Remover os acentos de uma string
 *
 * @param string $str
 * @return string
 */
function tiraAcento( $str ) {

return strtr(utf8_decode($str),utf8_decode('Å Å’Å½Å¡Å“Å¾Å¸Â¥ÂµÃ€Ã�Ã‚ÃƒÃ„Ã…Ã†Ã‡ÃˆÃ‰ÃŠÃ‹ÃŒÃ�ÃŽÃ�Ã�Ã‘Ã’Ã“Ã”Ã•Ã–Ã˜Ã™ÃšÃ›ÃœÃ�ÃŸÃ Ã¡Ã¢Ã£Ã¤Ã¥Ã¦Ã§Ã¨Ã©ÃªÃ«Ã¬Ã­Ã®Ã¯Ã°Ã±Ã²Ã³Ã´ÃµÃ¶Ã¸Ã¹ÃºÃ»Ã¼Ã½Ã¿'),'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');

}

function retira_acentos_ISO($texto){

$conversao = array('á' => 'a','à' => 'a','ã' => 'a','â' => 'a', 'é' => 'e',
 'ê' => 'e', 'í' => 'i', 'ï'=>'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', "ö"=>"o",
 'ú' => 'u', 'ü' => 'u', 'ç' => 'c', 'ñ'=>'n', 'Á' => 'A', 'À' => 'A', 'Ã' => 'A',
 'Â' => 'A', 'É' => 'E', 'Ê' => 'E', 'Í' => 'I', 'Ï'=>'I', "Ö"=>"O", 'Ó' => 'O',
 'Ô' => 'O', 'Õ' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ç' =>'C', 'Ñ'=>'N');

return strtr($texto, $conversao);

}

function retira_acentos_UTF8($texto){

//Substitua pela string que desejas converter
//preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $string)); 
$retorno = preg_replace( '/[`^~\'"]/', null, iconv( 'ISO-8859-1', 'ASCII//TRANSLIT', $texto ) );
 
return $retorno;

}

/**
* Calcula o intervalo entre duas datas no formato ISO, o intervalo é dado
* no formato específicado em intevalor q pode ser
* y - ano
* m - meses
* d - dias
* h - horas
* n - minutos
* default ´se gundos
*
* @param string $data1
* @param string $data2
* @param string $intervalo m, d, h, n,y
* @return int|string intervalo de horas
*/
function dataDif($data1, $data2, $intervalo) {

			switch ($intervalo) {
				
			case 'y':
			$Q = 86400*365;
			break; //ano
			case 'm':
			$Q = 2592000;
	            break; //mes
	        case 'd':
	            $Q = 86400;
	            break; //dia
	        case 'h':
	            $Q = 3600;
	            break; //hora
	        case 'n':
	            $Q = 60;
	            break; //minuto
	        default:
	            $Q = 1;
	            break; //segundo
	    }
	 
	    return round((strtotime($data2) - strtotime($data1)) / $Q);
	}
/*
* Verifica conexão com a Internet
* se conectado retorna verdadeiro
*/	
function conexaointernet() {
	
	if (!$sock = @fsockopen('www.google.com.br', 80, $num, $error, 5)) {
		//echo 'OFF LINE';
		return false;  
	} else {
		//echo 'OK';
		return true;  	
	}  
	
}

/*
* Função de máscara
* Aplicar máscara em campos/valores como cpf, cnpj, data, hora e o que desejar
* Exemplo de uso:
* $cnpj = "11222333000199";
* $cpf = "00100200300";
* $cep = "08665110";
* $data = "10102010";
*	 
*	echo mask($cnpj,'##.###.###/####-##');
*	echo mask($cpf,'###.###.###-##');
*	echo mask($cep,'#####-###');
*	echo mask($data,'##/##/####');
*/
function mask($val, $mask) {
 $maskared = '';
 $k = 0;
 for($i = 0; $i<=strlen($mask)-1; $i++)
 {
 	if($mask[$i] == '#')
 	 {
		 if(isset($val[$k]))
		 $maskared .= $val[$k++];
	 }
	 else  {
	 if(isset($mask[$i]))
	 $maskared .= $mask[$i];
	 	   }
 }
	 return $maskared;
}

//pega a data do 1900-01-01 00:00:00 em converte para data em branco/vazia
function databranco($dataform) {
	if ($dataform=='1900-01-01 00:00:00'){
		$databranco = "";
	} else {
		$databranco = mask(str_replace('/','',substr(converte_datacomhora($dataform),0,10)),'##/##/####');
	}
	
	return $databranco;	
}


// Inverte a competência Exemplo: 201404 para 04/2014
function invertecomp($comp,$var) {

	if($var==1) {
		$compet = substr($comp,4,2)."/".substr($comp,0,4);		
	}
	
	return $compet;	
}


function liberamenu($opcao) {

	$vetor = ArrayMenus();

			
$key = array_search($opcao,$vetor);
$liberado = substr(trim($_SESSION['s_acesso']),$key-1,1);

// Grava na sessão o nro da operação para o auditoria 
$_SESSION['s_codoperacao'] = $key;

return $liberado;
	
}

function ArrayMenus() {

	$vetor = array("1163" => "Carne", "1164" => "Cadastros", "1165" => "Titular", "1166" => "Incluir Titular",
			    "1167" => "Alterar Titular", "1168" => "Excluir Titular", "1169" => "Dependentes", "1170" => "Incluir Dependente",
			    "1171" => "Alterar Dependentes", "1172" => "Excluir Dependentes", "1173" => "Contratos", "1174" => "Incluir Contrato",
			    "1175" => "Alterar Contrato", "1176" => "Excluir Contrato", "1177" => "Tabelas Auxiliares", "1178" => "Tipo de Dependente",
				"1179" => "Incluir Tipo Dependente", "1180" => "Alterar Tipo Dependente", "1181" => "Excluir Tipo Dependente",
				"1182" => "Tipo de Plano", "1183" => "Incluir Tipo Plano", "1184" => "Alterar Tipo Plano", "1185" => "Excluir Tipo Plano",
				"1186" => "Financeiro", "1187" => "Registro de Pagamentos", "1188" => "Gerar Boleto Cobranca", "1189" => "Configuracoes",
				"1190" => "Alterar Configuracoes",
                "1201" => "Local de Pagamento", 
                "1202" => "Incluir Local Pagamento", "1203" => "Alterar Local Pagamento","1204" => "Excluir Local Pagamento", 
				"1205" => "Grupo", "1206" => "Incluir Grupo","1207" => "Alterar Grupo", "1208" => "Excluir Grupo",
                "1209" => "Incluir Pagamento Carne", "1210" => "Alterar Pagamento Carne", "1211" => "Excluir Pagamento Carne", 
                "1212" => "Relatorio Pagamentos Carne","1213" => "Relatorio Cadastros Carne",
				"1215" => "Exporta Financeiro","1216" => "Pagamentos por Grupo",
				"1217" => "Gerar Parcelas Carne","1218" => "Relatorio Carne Parcelado",
				"1276" => "Taxas", "1277" => "Incluir Taxas","1278" => "Alterar Taxas", "1279" => "Excluir Taxas", "1282" => "Auditor do Sistema",
				"1304" => "Inativou Titular Carne");
	
	return $vetor;
	
}



function grava_log($log_usu,$log_ope,$log_doc,$log_des,$log_dat,$log_hor,$log_modulo,$log_detalhes) {

	$Data = date("Y-m-d");

	 $pcStringSql = "select curtime() as hora";
	 $resultado = mysql_query($pcStringSql) or die('Erro no Insert '.$query);
	 $rowH = mysql_fetch_array($resultado);
	 $hora = substr($rowH['hora'],0,5);
	 
	 $pcStringSql = "insert into auditoria (data, hora, usuario, operacao, documento, descricao, modulo, detalhes) values".
	 "('".$Data."','".$hora."',".$_SESSION['s_uid'].",".$_SESSION['s_codoperacao'].",'".$log_doc."','".$log_des."','S','MODULO CARNE')";

	 $resultado = mysql_query($pcStringSql) or die('Erro no Insert '.$query);

 	 // gravaemarquivo($pcStringSql);
 	 
}


// Função para gravar sctring em arquivos TXT
function gravaemarquivo($pcStringSql) {

	// Abre ou cria o arquivo bloco1.txt
	// "a" representa que o arquivo é aberto para ser escrito
	$fp = fopen("bloco1.txt", "a");
	 
	// Escreve "exemplo de escrita" no bloco1.txt
	$escreve = fwrite($fp, $pcStringSql);
	 
	// Fecha o arquivo
	fclose($fp);

}

function geraCodigoBarra($numero){
		$fino = 1;
		$largo = 3;
		$altura = 50;
		
		$barcodes[0] = '00110';
		$barcodes[1] = '10001';
		$barcodes[2] = '01001';
		$barcodes[3] = '11000';
		$barcodes[4] = '00101';
		$barcodes[5] = '10100';
		$barcodes[6] = '01100';
		$barcodes[7] = '00011';
		$barcodes[8] = '10010';
		$barcodes[9] = '01010';
		
		for($f1 = 9; $f1 >= 0; $f1--){
			for($f2 = 9; $f2 >= 0; $f2--){
				$f = ($f1*10)+$f2;
				$texto = '';
				for($i = 1; $i < 6; $i++){
					$texto .= substr($barcodes[$f1], ($i-1), 1).substr($barcodes[$f2] ,($i-1), 1);
				}
				$barcodes[$f] = $texto;
			}
		}
		
		echo '<img src="imagens/p.gif" width="'.$fino.'" height="'.$altura.'" border="0" />';
		echo '<img src="imagens/b.gif" width="'.$fino.'" height="'.$altura.'" border="0" />';
		echo '<img src="imagens/p.gif" width="'.$fino.'" height="'.$altura.'" border="0" />';
		echo '<img src="imagens/b.gif" width="'.$fino.'" height="'.$altura.'" border="0" />';
		
		echo '<img ';
		
		$texto = $numero;
		
		if((strlen($texto) % 2) <> 0){
			$texto = '0'.$texto;
		}
		
		while(strlen($texto) > 0){
			$i = round(substr($texto, 0, 2));
			$texto = substr($texto, strlen($texto)-(strlen($texto)-2), (strlen($texto)-2));
			
			if(isset($barcodes[$i])){
				$f = $barcodes[$i];
			}
			
			for($i = 1; $i < 11; $i+=2){
				if(substr($f, ($i-1), 1) == '0'){
  					$f1 = $fino ;
  				}else{
  					$f1 = $largo ;
  				}
  				
  				echo 'src="imagens/p.gif" width="'.$f1.'" height="'.$altura.'" border="0">';
  				echo '<img ';
  				
  				if(substr($f, $i, 1) == '0'){
					$f2 = $fino ;
				}else{
					$f2 = $largo ;
				}
				
				echo 'src="imagens/b.gif" width="'.$f2.'" height="'.$altura.'" border="0">';
				echo '<img ';
			}
		}
		echo 'src="imagens/p.gif" width="'.$largo.'" height="'.$altura.'" border="0" />';
		echo '<img src="imagens/b.gif" width="'.$fino.'" height="'.$altura.'" border="0" />';
		echo '<img src="imagens/p.gif" width="1" height="'.$altura.'" border="0" />';
	}

	
function retorna_mes($MES){ 
    switch ($MES) {  
        case 1 : $MES='Janeiro'; break; 
        case 2 : $MES='Fevereiro';    break; 
        case 3 : $MES='Março';    break; 
        case 4 : $MES='Abril';    break; 
        case 5 : $MES='Maio';    break; 
        case 6 : $MES='Junho';    break; 
        case 7 : $MES='Julho';    break; 
        case 8 : $MES='Agosto';    break; 
        case 9 : $MES='Setembro'; break; 
        case 10 : $MES='Outubro'; break; 
        case 11 : $MES='Novembro';    break; 
        case 12 : $MES='Dezembro'; break; 
    } 
    return $MES; 
} 

/**
 * Redirect with POST data.
 *
 * @param string $url URL.
 * @param array $post_data POST data. Example: array('foo' => 'var', 'id' => 123)
 * @param array $headers Optional. Extra headers to send.
 */
function redirect_post($url, array $data, array $headers = null) {
	
	$php_errormsg = 'Erro ao processar POST na função redirect_post';

    $params = array(
        'http' => array(
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
    if (!is_null($headers)) {
        $params['http']['header'] = '';
        foreach ($headers as $k => $v) {
            $params['http']['header'] .= "$k: $v\n";
        }
    }
    $ctx = stream_context_create($params);
    $fp = @fopen($url, 'rb', false, $ctx);
    if ($fp) {
        echo @stream_get_contents($fp);
        die();
    } else {
        // Error
        throw new Exception("Error loading '$url', $php_errormsg");
    }
}

?>
