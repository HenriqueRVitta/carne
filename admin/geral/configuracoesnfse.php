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
	
	print "<BODY onLoad='document.dependente.idnome.focus();'bgcolor='".BODY_COLOR."'>";


	$fecha = "";
	if (isset($_GET['popup'])) {
		$fecha = "window.close()";
	} else {
		$fecha = "history.back()";
	}

	
    $disabled = '';
    $clasbutton = " class='button'";
	
	print "<BR><B><font size=4>"."Administra&ccedil;&atilde;o Configura&ccedil;&otilde;es da NFSe"."</font></B><BR>";

	print "<FORM name='configuracaonfse' method='POST' action='".$_SERVER['PHP_SELF']."'>";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";

		print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

       	$query = "SELECT tm_color_topo, tm_color_td, tm_color_body from styles";
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA EXECU��O DA QUERY DE MAX ID!');
       	$style = mysqli_fetch_array($resultado);
       	
		$query = "SELECT nfseprefeitura, percentualfiltro, cnpj, im, tiporps, naturezaoperacao, regimetributa, optantesimples, incentivadorcultural, codservico, codigocnae, codtribmun, codmunicipiotrib, retemiss, aliquota, tipoambiente FROM config";
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
		$config = mysqli_fetch_array($resultado);
		
		if(empty($config['prox_cartao_desc'])) { $ready1 = ""; } else { $ready1 = "readonly=\'true\'"; } 
		if(empty($config['prox_contrato'])) { $ready2 = ""; } else { $ready2 = "readonly=true"; } 

		
		print "<BR><b><font size=2 color='blue'>"."Edi&ccedil;&atilde;o Par&acirc;metros da NFSe"."</b></font><BR>";		
		print "<TR>";

		
		// Tipo de Ambiente tipoambiente 
		if($config['tipoambiente']=='1'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($config['tipoambiente']=='2'){ $selected2 = " selected"; } else { $selected2 = "";}
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Tipo de Ambiente".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='tipoambiente' id='idtipoambiente'>";  
		print "<option value='1'".$selected1.">Produ&ccedil;&atilde;o</option>";
		print "<option value='2'".$selected2.">Homologa&ccedil;&atilde;o</option>";  
		print "</select>";
		print "</TR><TR>";
		
		// NFSe Prefeitura
		if($config['nfseprefeitura']=='Ipatinga'){ $selected1 = " selected"; } else { $selected1 = "";}
				
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."NFSe Prefeitura".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='nfseprefeitura' id='idnfseprefeitura' onChange='return ValidaPrefeitura(this.value)'>";  
		print "<option value=''></option>";
		print "<option value='Ipatinga'".$selected1.">Ipatinga</option>";  
		print "</select>";
		print "</TR><TR>";

		// percentualfiltro refere-se ao percentual a ser processado NFSe
		// Ocultado em 08/11/2019
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."' hidden='hidden'>"."Percentual de Filtro".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."' hidden='hidden'><INPUT hidden='hidden' type='text' name='percentualfiltro' maxlength='12' class='text4' id='idpercentualfiltro' value='".$config['percentualfiltro']."' onkeypress=\"return formatar_moeda(this,',','.',event);\"</td>";
		print "</TR><TR>";
		
		// CNPJ
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."CNPJ do Emitente".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text2' name='cnpj' maxlength='14' id='idcnpj' value='".$config['cnpj']."'"."></td>";
		print "</TR><TR>";		

		// Inscri��o Municipal
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."Inscri&ccedil;&atilde;o Municipal do Emitente".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='im' class='text4' id='idim' maxlength='45' value='".$config['im']."'></td>";
		print "</TR><TR>";

		// Tipo 1 - RPS 2-Nota Fiscal Conjugada (Mista) 3-Cupom		
		if($config['tiporps']=='1'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($config['tiporps']=='2'){ $selected2 = " selected"; } else { $selected2 = "";}
		if($config['tiporps']=='3'){ $selected3 = " selected"; } else { $selected3 = "";}
						
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"." Tipo de NFSe".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='tiporps' id='idtiporps'>";  
		print "<option value='1'".$selected1.">RPS</option>";  
		print "<option value='2'".$selected2.">Nota Fiscal</option>";  
		print "<option value='3'".$selected3.">Cupom</option>";
		print "</select>";
		print "</TR><TR>";

		
		// C�digo de natureza da opera��o 
		if($config['naturezaoperacao']=='1'){ $selected1 = " selected"; } else { $selected1 = "";}
		if($config['naturezaoperacao']=='2'){ $selected2 = " selected"; } else { $selected2 = "";}
		if($config['naturezaoperacao']=='3'){ $selected3 = " selected"; } else { $selected3 = "";}
		if($config['naturezaoperacao']=='4'){ $selected4 = " selected"; } else { $selected4 = "";}
		if($config['naturezaoperacao']=='5'){ $selected5 = " selected"; } else { $selected5 = "";}
		if($config['naturezaoperacao']=='6'){ $selected6 = " selected"; } else { $selected6 = "";}
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Natureza da Opera&ccedil;&atilde;o".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='naturezaoperacao' id='idnaturezaoperacao'>";  
		print "<option value='1'".$selected1.">Tributa&ccedil;&atilde;o no munic&iacute;pio</option>";  
		print "<option value='2'".$selected2.">Tributa&ccedil;&atilde;o fora munic&iacute;pio</option>";  
		print "<option value='3'".$selected3.">Isen&ccedil;&atilde;o</option>";  
		print "<option value='4'".$selected4.">Imune</option>";  
		print "<option value='5'".$selected5.">Exigibilidade suspensa por decisao judicial</option>";  
		print "<option value='6'".$selected6.">Exigibilidade suspensa por procedimento administrativo</option>"; 
		
		print "</select>";
		print "</TR><TR>";

		// C�digo de identifica��o do regime especial de tributa��o 
		if($config['regimetributa']=='1'){ $selected_1 = " selected"; } else { $selected_1 = "";}
		if($config['regimetributa']=='2'){ $selected_2 = " selected"; } else { $selected_2 = "";}
		if($config['regimetributa']=='3'){ $selected_3 = " selected"; } else { $selected_3 = "";}
		if($config['regimetributa']=='4'){ $selected_4 = " selected"; } else { $selected_4 = "";}
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Regime Especial de tributa&ccedil;&atilde;o".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='regimetributa' id='idregimetributa'>";  
		print "<option value='1'".$selected_1.">Microempresa municipal</option>";  
		print "<option value='2'".$selected_2.">Estimativa</option>";  
		print "<option value='3'".$selected_3.">Sociedade de profissionais</option>";  
		print "<option value='4'".$selected_4.">Cooperativa</option>";  
		print "</select>";
		print "</TR><TR>";

		// Optante pelo Simples 
		if($config['optantesimples']=='1'){ $selected_1 = " selected"; } else { $selected_1 = "";}
		if($config['optantesimples']=='2'){ $selected_2 = " selected"; } else { $selected_2 = "";}
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Optante pelo Simples".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='optantesimples' id='idoptantesimples'>";  
		print "<option value='1'".$selected_1.">Sim</option>";  
		print "<option value='2'".$selected_2.">Nao</option>";  
		print "</select>";
		print "</TR><TR>";

		
		// Iincentivador cultural 
		if($config['incentivadorcultural']=='1'){ $selected_1 = " selected"; } else { $selected_1 = "";}
		if($config['incentivadorcultural']=='2'){ $selected_2 = " selected"; } else { $selected_2 = "";}
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Incentivador Cultural".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='incentivadorcultural' id='idincentivadorcultural'>";  
		print "<option value='1'".$selected_1.">Sim</option>";  
		print "<option value='2'".$selected_2.">Nao</option>";  
		print "</select>";
		print "</TR><TR>";
		

		// Retem ISS
		if($config['retemiss']=='1'){ $selected_1 = " selected"; } else { $selected_1 = "";}
		if($config['retemiss']=='2'){ $selected_2 = " selected"; } else { $selected_2 = "";}
		
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Retem ISS".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='retemiss' id='idretemiss'>";  
		print "<option value='1'".$selected_1.">Sim</option>";  
		print "<option value='2'".$selected_2.">Nao</option>";  
		print "</select>";
		print "</TR><TR>";
		
		
		// Aliquota  
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."Aliquota".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='aliquota' maxlength='4' class='text4' id='idaliquota' value='".$config['aliquota']."'></td>";
		print "</TR><TR>";
		
		
		// C�digo de identifica��o do munic�pio conforme Tabela do IBGE  
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo Munic&iacute;pio IBGE".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codtribmun' maxlength='7' class='text4' id='codtribmun' value='".$config['codtribmun']."'></td>";
		print "</TR><TR>";
		
		// C�digo CNAE - C�digo Nacional Atividade Econ�mica Principal
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo CNAE - C&oacute;digo Nacional Atividade Econ&ocirc;mica".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigocnae' maxlength='45' class='text4' id='idcodigocnae' value='".$config['codigocnae']."'></td>";
		print "</TR><TR>";
		
		// C�digo de item da lista de servi�o  
		print "<TD width='30%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo Item Servico".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codservico' maxlength='45' class='text4' id='idcodservico' value='".$config['codservico']."'></td>";
		print "</TR><TR>";


// 		$query = "SELECT codmunicipiotrib, retemiss, aliquota FROM config";
		
		
		if(liberamenu('Alterar Configuracoes')=="N"){
	    	$disabled = " disabled='disabled'";
	    	$clasbutton = " class='buttonDisabled'";
	    }
				
		print "<TD align='left' width='30%' bgcolor='".BODY_COLOR."'><input type='submit' $clasbutton id='idAlterar' $disabled value='".TRANS('BT_ALTER')."' name='submit'></TD>";
		print "<TD align='left' width='40%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		echo "<tr><td colspan='4' align='lefth'><br><a style='font-family:Tahoma; color:#1E90FF; font-weight:bold; text-decoration:underline; font-size:10pt;' onclick='redirect(\"cores.php\")'>Tabela de Cores</td></tr>";
		
		print "</TR>";

		$dtvencto = date("Y-m-d H:i:s");
	
	// Alterando o registro com UPDATE
	if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_ALTER')))) {	

			$query2 = "UPDATE config SET nfseprefeitura='".$_POST['nfseprefeitura']."', percentualfiltro=".$_POST['percentualfiltro'].", cnpj='".$_POST['cnpj']."', im='".$_POST['im']."', tiporps=".$_POST['tiporps'].", naturezaoperacao=".$_POST['naturezaoperacao'].", regimetributa=".$_POST['regimetributa'].", optantesimples=".$_POST['optantesimples'].", incentivadorcultural=".$_POST['incentivadorcultural'].", codservico='".$_POST['codservico']."', codigocnae='".$_POST['codigocnae']."', retemiss=".$_POST['retemiss'].", aliquota=".$_POST['aliquota'].", tipoambiente=".$_POST['tipoambiente'].", codtribmun='".$_POST['codtribmun']."'";
						
			$resultado2 = mysqli_query($conec->con,$query2) or die('Erro na query: '.$query2);

			$_SESSION['percentualfiltro'] = $_POST['percentualfiltro'];			
			$_SESSION['nfseprefeitura'] = $_POST['nfseprefeitura'];
			
			
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


			
			echo "<script>redirect('".$_SERVER['PHP_SELF']."');</script>";
						
	}

	print "</table>";

?>

<script type="text/javascript">

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
};

function ValidaPrefeitura(valor){
	
	var cidade = valor;

	// 3131307 - ipatinga		
    if (cidade == 'Ipatinga' )
        document.getElementById("codtribmun").Value = '3131307';
		$('codtribmun').val('3131307');
			
	return true;
		
};

</script>


<?php 
print "</body>";
print "</html>";
