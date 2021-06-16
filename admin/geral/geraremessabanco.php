<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Informática LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 05/9/2020 09:15 GLPI 20074

		* Módulo Carnê *

		Opções de geração de Remessas para Banco CNAB400 ou CNAB250 

*/
	session_start();
	
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/classes/paging.class.php");
	include ("../../includes/calendario.php");

	$conec = new conexao;
	$conec->conecta('MYSQL');
	
	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	print "<BODY onLoad='document.relgerarboleto.calendario1.focus();' bgcolor='".BODY_COLOR."'>";
	
	print "<BR><B><font size=4>"."Gerar Remessa para Cobran&ccedil;a na rede Banc&aacute;ria"."</font></B><BR>";

	print "<FORM name='geraremessabanco' method='POST' action='geraremessabanco.php' onSubmit=\"return valida()\">";
			
		if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";

		$dtinicial = date("d/m/Y",strtotime('+ 1 month')); 
		$dtfinal = date("d/m/Y",strtotime('+ 365 days'));
		
		$mesano = date('m/Y');
		
		$dtinicialcontrato = date("d/m/Y"); 
		$dtfinalcontrato = date("t/m/Y");
		
		// Data vencto de Contrato a cada 15 dias
		$diaDoMes = explode("/",$dtinicialcontrato);
		if($diaDoMes[0] <= 15){
			$dtinicialcontrato = "01"."/".$diaDoMes[1]."/".$diaDoMes[2];
			$dtfinalcontrato = "15"."/".$diaDoMes[1]."/".$diaDoMes[2];
		} else {
			$dtinicialcontrato = "16"."/".$diaDoMes[1]."/".$diaDoMes[2];
		}

	$ChechedMes = ' selected';
	$CheckedCon = '';
	if(isset($_POST['filtro']) && $_POST['filtro'] == 'Contrato'){
		$ChechedMes = '';
		$CheckedCon = ' selected';
	}

	$ChechedCar = ' selected';
	$CheckedBol = '';
	if(isset($_POST['layoutimpressao_']) && $_POST['layoutimpressao_'] == 'Boleto'){
		$ChechedCar = '';
		$CheckedBol = ' selected';
	}


	$grupo = "";
	$wheregurupo = "";
	if(isset($_POST['grupo']) && $_POST['grupo'] <> "-1"){
		$grupo = $_POST['grupo'];
		$wheregurupo = " and a.grupo = ".$_POST['grupo'];
	}


	if(isset($_POST['datainicio'])) {

		$dtinicial = $_POST['datainicio'];
		$dtfinal = $_POST['datafim'];
		
	}

	if(isset($_POST['iniciocontrato'])) {

		$dtinicialcontrato = $_POST['iniciocontrato'];
		$dtfinalcontrato = $_POST['fimcontrato'];
		
	}

	if(isset($_POST['mesano'])) {

		$mesano = $_POST['mesano'];
		
	}


	print "<TABLE border='0' align='left' ".$cellStyle."  width='80%' bgcolor='".BODY_COLOR."'>";

		print "<BR><b><font size=2 color='blue'>"."Op&ccedil;&otilde;es de Filtro"."</b></font><BR>";
		print "<TD></TD>";
		print "</TR><TR>";			
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Filtrar Por".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='filtro' id='filtro' >";
		print "<option value='Mes' ".$ChechedMes.">M&ecirc;s/Ano de Vencto</option>";  
		print "<option value='Contrato' ".$CheckedCon.">Inicio/Fim Contrato</option>";  
		print "</select>";
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Grupo".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='grupo' id='grupo'>";  
				print "<option value=-1>"."Selecione o Grupo"."</option>";
					$sql="Select id,descricao from carne_grupo where unidade = ".$_SESSION['s_local'];
					$commit = mysqli_query($conec->con,$sql);
					$i=0;
					while($row = mysqli_fetch_array($commit)){
						$selectedGrupo = "";
						if($grupo == $row['id']){
							$selectedGrupo = " selected";
						}
						print "<option value=".$row['id'].$selectedGrupo.">".$row['descricao']."</option>";
						$i++;
					}
				print "</select>";
		print "</TR><TR>";		
		print "<TD width='10%' align='left' bgcolor='".TD_COLOR."'>"."M&ecirc;s/Ano de Vencto".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='mesano' class='text4' onkeyup=\"maskIt(this,event,'##/####')\" id='idmesano' value='".$mesano."'></td>";
		print "</TR><TR>";		
		print "<TD width='10%' align='left' bgcolor='".TD_COLOR."'>"."Inicio Contrato".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='iniciocontrato' class='text4' style='width:100px; text-align:center;' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario3' onBlur='return doDateVenc(this.id,this.value, 4)' value='".$dtinicialcontrato."'></td>";
		print "<TD width='10%' align='left' bgcolor='".TD_COLOR."' >"."Fim Contrato".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='fimcontrato' class='text4' style='width:100px; text-align:center;' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario4' onBlur='return doDateVenc(this.id,this.value, 4)' value='".$dtfinalcontrato."'></td>";
		print "</TR><TR>";		
		print "<TD width='10%' align='left' bgcolor='".TD_COLOR."'>"."Data Inicial Boleto".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datainicio' class='text4' style='width:100px; text-align:center;' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario1' onBlur='return doDateVenc(this.id,this.value, 4)' value='".$dtinicial."'></td>";
		print "<TD width='10%' align='left' bgcolor='".TD_COLOR."' >"."Data Final".":</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datafim' class='text4' style='width:100px; text-align:center;' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario2' onBlur='return doDateVenc(this.id,this.value, 4)' value='".$dtfinal."'></td>";
		print "</TR><TR>";			
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Banco Emissor".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='bancoemissor_' id='bancoemissor_' >";
		print "<option value=''></option>";  
		print "<option value='Sicoob' selected>Sicoob</option>";  
		print "<option value='Bradesco'>Bradesco</option>";  
		print "</select>";
	
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."LayOut Impress&atilde;o".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='layoutimpressao_' id='layoutimpressao_'>";
		print "<option value=''></option>";  
		print "<option value='Carne' ".$ChechedCar." >Carn&ecirc; Banc&aacute;rio</option>";
		print "<option value='Boleto' ".$CheckedBol." >Boleto Banc&aacute;rio</option>";
		print "</select>";

		print "</TR><TR>";		

		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' value='BUSCAR CONTRIBUINTES' name='submit' id='buscarpagamentos'>";

		print "</TR></TR>";

	print "</table>";
	print "</form>";

	
if(isset($_POST['mesano'])) {
	
	
	//print "<FORM name='geraremessabanco' method='POST' action='nfsenew/examples/RecepcionarLoteRps.php' onSubmit=\"return verificaChecks(); return false;\">";
	print "<FORM name='geraremessabanco' method='POST' target='_blank' action='../boleto/boleto_bancoob_cnab240.php' onSubmit=\"return verificaChecks(); return false;\">";

	$dtinicial = Fdate($_POST['datainicio']);
	$dtfinal = Fdate($_POST['datafim']);

	$iniciocontrato = Fdate($_POST['iniciocontrato']);
	$fimcontrato = Fdate($_POST['fimcontrato']);


	print "<INPUT type='text' name='inicio' class='text4' value='".$dtinicial."' hidden='hidden'>";
	print "<INPUT type='text' name='fim' class='text4' value='".$dtfinal."' hidden='hidden'>";

	print "<select class='select2' name='bancoemissor' id='bancoemissor' hidden='hidden'>";
	print "<option value='".$_POST['bancoemissor_']."'>".$_POST['bancoemissor_']."</option>";  
	print "</select>";

	print "<select class='select2' name='layoutimpressao' id='layoutimpressao' hidden='hidden'>";
	print "<option value='".$_POST['layoutimpressao_']."'>".$_POST['layoutimpressao_']."</option>"; 
	print "</select>";

	print "<select class='select2' name='filtro_' id='filtro_' hidden='hidden'>";
	print "<option value='".$_POST['filtro']."'>".$_POST['filtro']."</option>";  
	print "</select>";

	$mesano = substr($_POST['mesano'],3,4).substr($_POST['mesano'],0,2);
	
	$pcWhere = " where b.datacontrato between '".$iniciocontrato."' and '".$fimcontrato."' and a.situacao != 'INATIVO' ".$wheregurupo." order by a.nometitular";

	if($_POST['filtro'] == 'Mes'){
		$pcWhere = " where a.ultimomescarne ='".$mesano."' and a.situacao != 'INATIVO' ".$wheregurupo." order by a.nometitular";
	}

	$dataDia = date('Y-m-d');
	$sqlQuery = "select distinct a.id, a.cpf, a.nometitular, concat(substr(a.ultimomescarne,5,2),'/',substr(a.ultimomescarne,1,4)) as mesano, b.diavencto,
				c.descricao, d.valor, d.valor_dependente, b.datacontrato, date(e.inclusao) as inclusao
				from carne_titular a
				left Join carne_contratos b on b.idtitular = a.id
				left Join carne_tipoplano c on c.id = b.plano
				left join carne_remessaboleto e on e.idtitular = a.id and date(e.inclusao) = '".$dataDia."'
				left Join carne_competenciaplano d on d.idplano = b.plano".$pcWhere;


	$commit=mysqli_query($conec->con,$sqlQuery) or die('ERRO na query'.$sqlQuery);
	
	if (mysqli_num_rows($commit) == 0){
	
		print "<div class='alert alert-danger'>Nenhum Registro encontrato..</div>";
	
	} else {
		
		if (!isset($_GET['cellStyle'])) {
			$cellStyle = "cellpadding='5' cellspacing='0'";
		} else
			$cellStyle = "cellpadding='5' cellspacing='1'";

		$Print = "<table width='100%'><tr><td>";
		$Print.= "<input type='submit' style='background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center;' value='Gerar Arquivo de Remessa' name='submit' id='idsubmit'>";
		//$Print.= "</td></tr>";
		$Print.= "</td>";
		$Print.= "<td>";
		$Print.= "Boleto<input type='checkbox' name='boleto' checked='checked' value='1'>";
		$Print.= "</td><td>";
		$Print.= "Remessa<input type='checkbox' name='remessa' checked='checked' value='2'>";
		$Print.= "</td></tr>";
		$Print.= "<tr><td><input type='checkbox' name='tudo' onclick='verificaStatus(this)' checked/>Marcar/Desmarcar Todos</td>";
		$Print.= "</tr></table>";		

		$Print.= "<table id='pagtos' name='pagtos' border='0' align='left' ".$cellStyle."  width='100%' bgcolor='#87CEFA' style='font-size:12'>";

		
		$Print.="<TR class='header'><td class='line'>"."Sel."."</TD>"."<td class='line' width='10%'>"."Contribuinte"."</TD>"."<td class='line' width='10%'>"."Contribuinte"."</TD>"."<td class='line' width='10%'>"."Mês/Vencto"."</TD>"."<td class='line' width='10%'>"."Dia Vencto"."<td class='line' width='10%'>"."Data Contrato"."</TD>"."<td class='line' width='10%'>"."Plano"."</TD>"."<td class='line' width='10%'>"."Vlr Titular"."</TD>"."<td class='line' width='10%'>"."Vlr Dep."."</TD>"."<td class='line' width='10%'>"."Editar"."</TD></tr>";
		
		$j=2;
		$nContador = 0;
		$nTotalGeral = 0;
		doubleval($nTotalGeral);
		
		
		while($row = mysqli_fetch_array($commit)){
	
			if ($j % 2)
			{
				$trClass = "lin_par";
			}
			else
			{
				$trClass = "lin_impar";
			}

			$j++;
			$nContador++;
			$nTotalGeral+=$row['valor']+$row['valor_dependente'];
			
			$Checked = "checked='checked'";
			if(empty($row['cpf'])) {
				$Checked='';
			}

			$plano = $row['descricao'];
			if(empty($row['descricao'])){
				$plano = "FALTA INFORMAR PLANO NO CADASTRO";
				$Checked='';
			}

			if(!is_null($row['inclusao']) && $row['inclusao'] == $dataDia){
				$trClass = "";
				$plano = " ** BOLETO JÁ FOI GERADO HOJE **";
				$Checked='';
			}

			$datacontrato = date('d-m-Y', strtotime($row['datacontrato']));

			$Print.="<tr class='".$trClass."' id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
			//$Print.="<td class='line' width='5%'><input type='checkbox' name='selecionado[]' ".$Checked." value='".$row['idretorno']."' id='selecionado'> </td>";
			$Print.="<td class='line' width='5%'><input type='checkbox' name='selecionado[]' ".$Checked." value='".$row['id']."' </td>";
			$Print.="<td class='line' width='10%'>".$row['cpf']."</td>";
			$Print.="<td class='line' width='30%'>".$row['nometitular']."</td>";
			$Print.="<td class='line' width='10%'>".$row['mesano']."</td>";
			$Print.="<td class='line' width='10%'>".$row['diavencto']."</td>";			
			$Print.="<td class='line' width='10%'>".$datacontrato."</td>";
			$Print.="<td class='line' width='30%'>".$plano."</td>";			
			$Print.="<td class='line' width='10%'>".$row['valor']."</td>";			
			$Print.="<td class='line' width='10%'>".$row['valor_dependente']."</td>";
			$Print.="<td class='line'><a target='_blank' href='abastitular.php?action=alter&cod=".$row['id']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";

			$Print.="</tr>";
		
		}

		$Print.="<tfoot>";
		$Print.="<tr>";
		$Print.="<td></td>";
		$Print.="<td>Qtde: ".$nContador."</td>";
		$Print.="<td></td>";
		$Print.="<td></td>";
		$Print.="<td></td>";
		$Print.="<td></td>";
		$Print.="<td></td>";
		$Print.="<td>".$nTotalGeral."</td>";
		$Print.="<td></td>";
		$Print.="</tr>";
		$Print.="</tfoot>";
  		
		$Print.="</Table>";
		
		print $Print;
	
	
	}

print "</form>";
	
}	
	

?>

<script language="JavaScript">
/* Formatacao para qualquer mascara */

function verificaStatus(nome){

	if(nome.form.tudo.checked == 1)
	{
	nome.form.tudo.checked = 1;
	marcarTodos(nome);
	}
	else
	{
	nome.form.tudo.checked = 0;
	desmarcarTodos(nome);
	}

}
 
function marcarTodos(nome) {
   for (i=0;i<nome.form.elements.length;i++)
		if(nome.form.elements[i].type == "checkbox")
		 nome.form.elements[i].checked=1

}

function desmarcarTodos(nome){
   for (i=0;i<nome.form.elements.length;i++)
	  if(nome.form.elements[i].type == "checkbox")
		 nome.form.elements[i].checked=0

		 nome.form.boleto.checked = 1;
		 nome.form.remessa.checked = 1;

}



// Verifica se marcou algum CheckBox
function verificaChecks() {

	
    var aChk = document.getElementsByName("selecionado[]"); 

    var Selecionado = false;
    
    for (var i=0;i<aChk.length;i++){ 

        if (aChk[i].checked == true){ 

        	Selecionado = true;
        	exit;
        	
        	
        }  else {

        	Selecionado = false;

        }

    }
    
	if(Selecionado == false){
    	alert('Nenhum registro foi selecionado.');
    	return false;
	
	}
		
}


// Ocultar/desabilitar botao Enviar NFSe/RPS
function OcultaEnviar(Id, pStr) {

	d = document.getElementById(Id);	
	document.getElementById('idsubmit').style.display="none";
	//document.getElementById('buscarpagamentos').click();
	
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
	
		var ok = validaForm('calendario1','','Data Inicial',1);
		var ok = validaForm('calendario2','','Data Final',1);
		var ok = validaForm('calendario3','','Inicio Contrato',1);
		var ok = validaForm('calendario4','','Fim Contrato',1);
		var ok = validaForm('idmesano','','Mes e Ano',1);

		document.getElementById('inicio').value = document.getElementById("datainicio").value;
		document.getElementById('fim').value = document.getElementById("datafim").value;
		document.getElementById("layoutimpressao").val = document.getElementById("layoutimpressao_").val;

		var bancoemissor = document.getElementById("bancoemissor").value;
		if(bancoemissor == ''){
			alert('Banco Emissor deve ser informado.');
			ok = false;
		}

		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
