<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 07/01/2015 17:44

		* M�dulo Carn� *

		Essa aplica��o tem como objetivo geral controlar os Titulares e dependentes 
		que fazem �contribui��o� mensal com a Unidade de Sa�de (Hospital) para obter 
		um desconto em realiza��o de atendimentos �Particular� ou at� mesmo algum 
		diferencial em caso de interna��o SUS
*/

	session_start();

?>

	<!-- Traz os dados referente ao nro do carne informado -->
	<!--  <script type="text/javascript" src="scripts/lib/dadoscarne.js"></script> -->
	<script type="text/javascript" src="scripts/lib/dadoscarne.js"></script>	
		
<?php 
	
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/classes/paging.class.php");
	include ("../../includes/calendario.php");

	$conec = new conexao;
	$conec->conecta('MYSQL');

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	print "<BODY onLoad='document.pagamentosunimed.calendario1.focus();' bgcolor='".BODY_COLOR."'>";

	$fecha = "";
	if (isset($_GET['popup'])) {
		$fecha = "window.close()";
	} else {
		$fecha = "history.back()";
	}

	
	print "<BR><B><font size=4>"."Administra&ccedil;&atilde;o de Pagamentos - UNIMED"."</font></B><BR>";

	print "<FORM name='pagamentosunimed' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	$PAGE = new paging_("PRINCIPAL");
	$PAGE->setRegPerPage($_SESSION['s_page_size']);
	
		if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	

    
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

	$vinculoplano = 0;
	
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") ) && empty($_POST['submit'])) {

		if(isset($_GET['cod'])) {		
			$Where = " Where c.id = ".$_GET['cod']." ";
		}
		
		if(isset($_GET['carne'])) {
			$Where = " Where c.nrocarne = ".$_GET['carne']." ";
		}

		if(isset($_GET['carne']) and $_SESSION['s_utilizacontrato']=='Sim') {
			$Where = " Where c.id = ".$_GET['carne']." ";
		}
		
		
		$query = "SELECT c.id, c.nrocarne, c.nometitular, p.nrocontrato, p.plano, p.diavencto, p.datacontrato, q.percdesc, d.valor, d.compet_ini, d.compet_fim FROM carne_titular c ".
       	" Join carne_contratos p on p.idtitular = c.id ".
       	" Join carne_tipoplano q on q.id = p.plano ".
       	" Join carne_competenciaplano d on d.idplano = p.plano ".$Where;
       	
		
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY 1 !'.$query);
		$rowA = mysqli_fetch_array($resultado);
		$vinculoplano = mysqli_num_rows($resultado);
		
		$valor  = $rowA['valor'];
		$percentual = $rowA['percdesc'];
		
		$mesano = "";
		$ano	= "";
		$Date	= date("d/m/Y");
		
		if($_SESSION['s_utilizacontrato']=='Nao') {
			$nrocarnecontrato = $rowA['nrocarne'];
		} else {
			$nrocarnecontrato = $rowA['nrocontrato'];					
		}
		
		$ValorApagar = 0.00;
		
		// Recebe o par�metro GET do M�s e Ano de pagamento
		if(isset($_GET['mesano']) && !empty($_GET['mesano'])) {

			//print_r($_GET);
			//break;
			
			$mesano = substr($_GET['mesano'],0,2)."/".substr($_GET['mesano'],2,4);
			$ano = substr($_GET['mesano'],2,4);


			$ValorApagar = substr($_GET['mesano'],9,4);
			
			// Calcula o percentual do Sal�rio minino de 2014
			if($ano==2014) {
				$valor = ((724.00 * $percentual) / 100);
			}

			// Calcula o percentual do Sal�rio minino de 2015
			if($ano==2015) {
				$valor = ((788.00 * $percentual) / 100);
			}
			
			// Calcula o percentual do Sal�rio minino de 2016
			if($ano==2016) {
				$valor = ((880.00 * $percentual) / 100);
			}
			
			
			// Se Carn� parcelado pega os valores da tabela carne_parcelamento
			if($_GET['parcelado']=="Sim") {

				$mesanoParc = substr($_GET['mesano'],2,4).substr($_GET['mesano'],0,2);
				$nrocarneParc = substr($_GET['mesano'],0,4);
				
		       	$queryParcelado = "select vlrmensal, vlrparcelado from carne_parcelamento where nrocarne=".$nrocarneParc." and databaixa='1900-01-01 00:00:00' and mesano=".$mesanoParc.""; 
				$resultadoParcelado = mysqli_query($conec->con,$queryParcelado) or die('ERRO NA PARCELADO !'.$queryParcelado);
				$rowAParc = mysqli_fetch_array($resultadoParcelado);
				$ValorApagar = $rowAParc['vlrmensal'];
				
			}		
			
		} 
		

			/***********
		 * Come�a aqui a verifica��o de inadimpl�ncia
		 * Henrique 26/06/2018 15:39
		 */
			
			$desde = "";
			$cMeses = "";
			$cMensagemAtraso = "";
			$idCliente = 0;
			$descricaoPlano = "";

			if ((isset($_POST['search'])) && !empty($_POST['search'])) {

				// Pego a Data de Inicio do Cadastro do Titular
				$arraydados = mysqli_fetch_array($resultado);
				
				$sqlpagto = "select a.id,a.nometitular, a.datainicio Data_Inicio, d.valor as ValordoPlano,
				sum(b.vlrpago) TotalPago,
				TIMESTAMPDIFF(MONTH,a.datainicio,now()) TotalMeses,
				count(b.databaixa) MesesPagos, sum(b.vlrpago) TotalPago,
				(TIMESTAMPDIFF(MONTH,a.datainicio,now()) - count(b.databaixa)) MesesInadimplente,
				(d.valor * (TIMESTAMPDIFF(MONTH,a.datainicio,now()) - count(b.databaixa))) as TotalDebito
				from carne_titular a Join Carne_pagamentos b on b.idcliente = a.id
				join carne_contratos c on c.idtitular = a.id
				join carne_competenciaplano d on d.idplano = c.plano
				where a.id = ".$arraydados['id'];

				$resultadopagto = mysqli_query($conec->con,$sqlpagto) or die('ERRO NA EXECU��O DA QUERY carne_pagamentos!');
				while ($rowpagto= mysqli_fetch_array($resultadopagto)) {
					
					 if($rowpagto['MesesInadimplente'] > 0){
					 	
					 	$cMeses = $rowpagto['MesesInadimplente'];
					 	$desde = substr(converte_datacomhora($rowpagto['Data_Inicio']),0,10);

							 if($rowpagto['MesesInadimplente'] > 1){
							 	
							 	$cMeses.=" Meses";
						 		
							 } else {
							 	
							 	$cMeses.=" Mes";
							 }
							 	
					 
					 }
					 					 
					 
				}
				
				if(!empty($cMeses)){
					
					$cMensagemAtraso = "VERIFICAR INADIMPL&Ecirc;NCIA >> ".$cMeses." - Cliente DESDE: ".$desde;
				}
			}
		
		/***********
		 * Termina aqui a verifica��o de inadimpl�ncia
		 * Henrique 26/06/2018 15:39
		 */

        
			if(!empty($cMensagemAtraso)){
				print "<tr><td colspan='4' align='center'>";
				print "<h3><B  style='background-color:LightSalmon;'>VERIFICAR INADIMPL&Ecirc;NCIA >> ".$cMensagemAtraso."</B></h3></TD>";
				print "</tr>";
			}
			

            if($vinculoplano == 0){
                    print "<tr><td colspan='4' align='center'>";
                    print "<h3><B  style='background-color:LightSalmon;'>Cadastro do Titular falta vinculo com PLANO</B></h3></TD>";
                    print "</tr>";
            }			
		
        
		print "<BR><b><font size=2 color='blue'>Inclus&atilde;o de Pagamentos</b></font><BR>";
        print "<TR>";
		print "<TD width='10%' align='left' bgcolor='".TD_COLOR."'>Nro Contrato/Carn&ecirc;:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nrocarne' class='text4' id='idnrocarne' onblur='buscarCarne(this.value)' onkeyup=\"maskIt(this,event,'######')\" value='".$nrocarnecontrato."' readonly='readonly'>";
		print "<INPUT type='text' name='cliente' class='text4' id='idcliente' value='".$rowA['id']."' hidden='true'>";
		print "<INPUT type='text' name='convenio' class='text4' id='idconvenio' value='Unimed' hidden='true'>";
		print "<INPUT type='text' name='nomecliente' class='text3' id='idnomecliente' value='".$rowA['nometitular']."' disabled='disabled'>";
		print "</TD>";
		print "</TR><TR>";
					
		print "<TD width='10%'align='left' bgcolor='".TD_COLOR."'>Tipo de Plano:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select3' name='plano' id='idplano' disabled='disabled'>";  
		print "<option value=-1>Tipo de Plano</option>";
			$sql="Select id,descricao from carne_tipoplano order by id";
					$commit = mysqli_query($conec->con,$sql);
					$i=0;
		
					while($row = mysqli_fetch_array($commit)){

						if($row['id']==$rowA['plano']) { 
							$selected = " selected"; 
							$descricaoPlano = $row['descricao'];
						} else { $selected = ""; }
						
						print "<option value=".$row['id'].$selected.">".$row['descricao']."</option>";
						
						$i++;
					}
				print "</select>";

		print "</TR></TD><TR>";
				
		print "<TD width='10%' align='left' bgcolor='".TD_COLOR."'>Dia do Vencto:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='diavencto' class='text4' id='iddiavencto' value='".$rowA['diavencto']."' readonly='readonly'></TD>";
		print "<TD width='10%' align='left' bgcolor='".TD_COLOR."'>Data Pagto:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datapagto' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario1' onBlur='return doDateVenc(this.id,this.value, 4)' value='".$Date."'></TD>";
		print "</TR><TR>";		
		print "<TD width='10%' align='left' bgcolor='".TD_COLOR."'>M&ecirc;s e Ano:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='mesano' class='text4' onkeyup=\"maskIt(this,event,'##/####')\" id='idmesano' value='".trim($mesano)."'></TD>";
		print "<TD width='10%' align='left' bgcolor='".TD_COLOR."'>Local de Pagamento:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='localpagto' id='idlocalpagto' onBlur='return Dados(this.value)'>";  
				print "<option value=-1>Selecione Local Pagamento</option>";
					$sql="Select id,descricao from carne_localpagto where unidade = ".$_SESSION['s_local'];
					$commit = mysqli_query($conec->con,$sql);
					$i=0;
					while($row = mysqli_fetch_array($commit)){
						
						$selected = "";
						
						if(isset($_SESSION['localpagto']) && $_SESSION['localpagto'] == $row['id']) {
							$selected = " Selected";
						}
						print "<option value=".$row['id'].$selected.">".trim($row['descricao'])."</option>";
						$i++;
					}
				print "</select>";
		  
		print "</TD></TR><TR>";
		print "<TD width='10%' lign='left' bgcolor='".TD_COLOR."'>Valor Unimed:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='vlrunimed' maxlength='20' id='idvlrunimed' value='".$valor."'  onblur=\"valormensal(this);\" onkeypress=\"return formatar_moeda(this,',','.',event);\"</TD>";
        print "<TD width='10%' bgcolor='".TD_COLOR."'>Contribuição:</TD>";
		print "<TD width='30%' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='vlrcontribuicao' maxlength='20' id='idvlrcontribuicao' value='".$valor."'  onblur=\"valormensal(this);\" onkeypress=\"return formatar_moeda(this,',','.',event);\"</TD>";
		print "</TR><TR>";
		print "<TD width='10%' lign='left' bgcolor='".TD_COLOR."'>Valor Mensal:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='vlrmensal' maxlength='20' id='idvlrmensal' value='".$valor."' onblur=\"valormensal(this);\" onkeypress=\"return formatar_moeda(this,',','.',event);\"</TD>";
        print "<TD width='10%' bgcolor='".TD_COLOR."'>Apene:</TD>";
		print "<TD width='30%' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='apene' maxlength='20' id='idvlrApene' value='".$valor."' onblur=\"valormensal(this);\" onkeypress=\"return formatar_moeda(this,',','.',event);\"</TD>";
		print "</TR><TR>";
		print "<TD width='10%' lign='left' bgcolor='".TD_COLOR."'>Tarifa:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='tarifa' maxlength='20' id='idtarifa' value='".$valor."' onkeypress=\"return formatar_moeda(this,',','.',event);\"</TD>";
        print "<TD width='10%' bgcolor='".TD_COLOR."'>Juros:</TD>";
		print "<TD width='30%' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='juros' maxlength='20' id='idjuros' value='".$valor."' onkeypress=\"return formatar_moeda(this,',','.',event);\"</TD>";
		print "</TR><TR>";
		print "<TD width='10%' lign='left' bgcolor='".TD_COLOR."'>utilizaçao:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='utilizacao' maxlength='20' id='idutilizacao' value='".$valor."' onkeypress=\"return formatar_moeda(this,',','.',event);\"</TD>";
        print "<TD width='10%' bgcolor='".TD_COLOR."'>Outros:</TD>";
		print "<TD width='30%' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='outros' maxlength='20' id='idoutros' value='".$valor."' onkeypress=\"return formatar_moeda(this,',','.',event);\"</TD>";

        print "</TR>";
				
	    $disabled = '';
	    $clasbutton = " class='button'";
		
	    if(liberamenu('Incluir Pagamento Carne')=="N"){
	    	$disabled = " disabled='disabled'";
	    	$clasbutton = " class='buttonDisabled'";
	    }

	    if($vinculoplano == 0) {
	    	$disabled = " Hidden='Hidden'";
	    } 

		$descricaoPlano = trim(strtoupper($descricaoPlano));

		if($descricaoPlano != 'UNIMED') {
	    	$disabled = " Hidden='Hidden'";
			print "<TR>";
			print "<TD align='left' width='10%'></TD>";
			print "<TD align='left' width='30%'><h3><B  style='background-color:LightSalmon;'>Plano do Contribuinte não é UNIMED, impossível Salvar.</B></h3></TD>";
			print "</TR>";
		}
	    
		print "<TR>";
        print "<TD align='left' width='10%' bgcolor='".BODY_COLOR."'><input type='submit' $clasbutton value='Cadastrar' name='submit' $disabled></TD>";
		print "<TD align='left' width='30%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='Cancelar' name='cancelar' onClick=\"redirect('pagamentoslistaunimed.php')\"></TD>";
		print "</TR>";

		print "</table>";
		
		print "<br>";
		
		/***********
		 * Come�a aqui a lista dos pagamentos edetuados pelo cliente
		 * Henrique 02/02/2015 12:54
		 */

       	$queryPrincipal = "SELECT p.id, p.parcelamento, p.idcliente, p.nrocarne, p.mesano, p.databaixa, p.localpagto, p.vlrcalculado, p.vlrpago, p.taxa, p.unidade, c.nometitular, l.descricao, p.docfinanceiro, ".
        "p.vlrunimed, p.vlrcontribuicao, p.vlrmensal, p.apene, p.tarifa, p.juros, p.utilizacao, p.outros FROM carne_pagamentos p ".
       	" Join carne_titular c on c.id = p.idcliente ".
       	" Left Join carne_localpagto l on l.id = p.localpagto";
       	
		if(isset($_GET['cod'])) {
      		$queryPrincipal.= " WHERE p.idcliente = ".$_GET['cod']." ";
		}
		
		if(isset($_GET['carne'])) {
      		$queryPrincipal.= " WHERE p.nrocarne = ".$_GET['carne']." ";
		}
		
		$queryPrincipal.=" and p.unidade =".$_SESSION['s_local']." ORDER BY p.id desc";
		
		$resultado = mysqli_query($conec->con,$queryPrincipal) or die('ERRO NA QUERY 2 !'.$queryPrincipal);
	
		$registros = mysqli_num_rows($resultado);

	    $disabled = '';
	    $clasbutton = " class='button'";
		
		if (isset($_GET['LIMIT']))
		$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($queryPrincipal,(isset($_GET['FULL'])?$_GET['FULL']:0));
		
		$PAGE->execSQL();

		if (!isset($_GET['cellStyle'])) {
			$cellStyle = "cellpadding='5' cellspacing='0'";
		} else
			$cellStyle = "cellpadding='5' cellspacing='1'";
		print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";
		
		if (mysqli_num_rows($resultado) == 0)
		{
			echo "<tr><td colspan='4'>".mensagem('Nenhum pagamento registrado')."</td></tr>";
		}
		else
		{

			print "<tr><td colspan='4'>";
			print "<B>".TRANS('FOUND')." <font color=red>".$PAGE->NUMBER_REGS."</font> ".TRANS('RECORDS_IN_SYSTEM').". ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</B></TD>";
			print "</tr>";
			//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
			print "<TR class='header'><td class='line' width='30%'>Cliente</TD>"."<td class='line' width='10%' >Vlr Unimed</TD>"."<td class='line' width='15%' >Vlr Contrib</TD>"."<td class='line' width='15%' >Valor Mensal</TD>"."<td class='line' width='10%'>Apene</td>"."<td class='line' width='10%'>Tarifa</TD>"."<td class='line' width='20%'>Juros</td>".
			"<td class='line' width='20%'>Utiliza&ccedil;&atilde;o</td>"."<td class='line' width='10%'>Total Pago</TD>"."<td class='line' width='20%'>Data Pagto</td>"."<td class='line' width='20%'>Compet</td>"."<td class='line'>Recibo<td class='line'>".TRANS('COL_EDIT')."</TD><td class='line'>".TRANS('COL_DEL')."</TD></tr>";

			$lcLibera1 = liberamenu('Alterar Pagamento Carne');
			$lcLibera2 = liberamenu('Excluir Pagamento Carne');
		
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
				print "<td class='line'>".$row['nometitular']."</td>";
				print "<td class='line'>".$row['vlrunimed']."</td>";
				print "<td class='line'>".$row['vlrcontribuicao']."</td>";
				print "<td class='line'>".$row['vlrmensal']."</td>";
				print "<td class='line'>".$row['apene']."</td>";
				print "<td class='line'>".$row['tarifa']."</td>";
				print "<td class='line'>".$row['juros']."</td>";
				print "<td class='line'>".$row['utilizacao']."</td>";

                $totalPago = ($row['vlrunimed']+$row['vlrcontribuicao']+$row['vlrmensal']+$row['apene']+$row['tarifa']+$row['juros']+$row['utilizacao']);

                print "<td class='line'>".$totalPago."</td>";

				$dtregistro = str_replace('/','',substr(converte_datacomhora($row['databaixa']),0,10));
				print "<td class='line'>".mask($dtregistro,'##/##/####')."</td>";
				print "<td class='line'>".invertecomp($row['mesano'],1)."</td>";

				// Se ja foi exportado para o Financeiro nao deixar alterar
				if(!empty($row['docfinanceiro'])) {
					
					print "<td class='line' align='center'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['id']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."print.ico' title='Imprimir Recibo Pagto'></a></td>";
					print "<td class='line'>Exportado</td>";
					print "<td class='line'>Financeiro</TD>";
					
				} else {
					
					print "<td class='line' align='center'><a href=\"recibos/recibomodelo01.php?cod=".$row['id']."&cellStyle=true'\" target='_blank'><img height='16' width='16' src='".ICONS_PATH."print.ico' title='Imprimir Recibo Pagto'></a></td>";

					if($lcLibera1=="N") {
				    	print "<td disabled='disabled' class='line' align='center'><a onClick=''><img height='16' width='16' src='".ICONS_PATH."proibido.jpg' title='Altera&ccedil;&atilde;o n&atilde;o permitida'></a></TD>";
			       } else {
						print "<td class='line' align='center'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['id']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
			       }
					
				    if($lcLibera2=="N") {
				    	print "<td disabled='disabled' class='line' align='center'><a onClick=''><img height='16' width='16' src='".ICONS_PATH."proibido.jpg' title='Exclus&atilde;o n&atilde;o permitida'></a></TD>";
			       } else {
						print "<td class='line' align='center'><a onClick=\"confirmaAcao('".TRANS('MSG_DEL_REG')."','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['id']."&idcliente=".$row['idcliente']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
			       }
						
		       
				}

			
				
				print "</TR>";
			}
			print "<tr><td colspan='4'>";
			$PAGE->showOutputPages();
			print "</td></tr>";
		
		}
		
	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		print "<BR><b><font size=2 color='blue'>"."Edi&ccedil;&atilde;o de Pagamentos"."</b></font><BR>";		

       	$query = "SELECT c.id, c.nometitular, p.nrocontrato, p.plano, p.diavencto, p.datacontrato, q.percdesc, d.valor, d.compet_ini, d.compet_fim, ".
       	" k.id as idpagto, k.mesano, k.databaixa, k.localpagto, k.vlrpago, k.idtaxas, k.valor_taxas, k.docfinanceiro, ".
		" k.vlrunimed, k.vlrcontribuicao, k.vlrmensal, k.apene, k.tarifa, k.juros, k.utilizacao, k.outros FROM carne_titular c ".
       	" Join carne_contratos p on p.idtitular = c.id ".
       	" Join carne_tipoplano q on q.id = p.plano ".
       	" Join carne_competenciaplano d on d.idplano = p.plano ".
       	" Join carne_pagamentos k on k.idcliente = c.id ".
       	" Where k.id = ".$_GET['cod']." ";
		
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY 3 !'.$query);
		$rowA = mysqli_fetch_array($resultado);

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Contrato/Carn&ecirc;".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nrocarne' class='text4' id='idnrocarne' onblur='buscarCarne(this.value)' onkeyup=\"maskIt(this,event,'######')\" value='".$rowA['nrocontrato']."' readonly='readonly'>";
		print "<INPUT type='text' name='cliente' class='text4' id='idcliente' value='".$rowA['id']."' hidden='true'>";
		print "<INPUT type='text' name='idpagto' class='text4' id='idpagto' value='".$rowA['idpagto']."' hidden='true'>";
		print "<INPUT type='text' name='nomecliente' class='text3' id='idnomecliente' value='".$rowA['nometitular']."' disabled='disabled'>";
		print "</td>";
		print "</TR><TR>";	
			
		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Tipo de Plano".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='plano' id='idplano' disabled='disabled'>";  
		print "<option value=-1>"."Tipo de Plano"."</option>";
			$sql="Select id,descricao from carne_tipoplano order by id";
					$commit = mysqli_query($conec->con,$sql);
					$i=0;
		
					while($row = mysqli_fetch_array($commit)){

						if($row['id']==$rowA['plano']) { $selected = " selected"; } else { $selected = "";}
						
						print "<option value=".$row['id'].$selected.">".$row['descricao']."</option>";
						$i++;
					}
				print "</select>";

		print "</TR></TD><TR>";

		$dtpagto = str_replace('/','',substr(converte_datacomhora($rowA['databaixa']),0,10));
		$mesano = invertecomp($rowA['mesano'],1);
		
		print "<TD width='10%' align='left' bgcolor='".TD_COLOR."'>Dia do Vencto:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='diavencto' class='text4' id='iddiavencto' value='".$rowA['diavencto']."' readonly='readonly'></td>";
		print "<TD width='10%' align='left' bgcolor='".TD_COLOR."'>Data Pagto:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='datapagto' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='calendario1' onBlur='return doDateVenc(this.id,this.value, 4)' value='".mask($dtpagto,'##/##/####')."'></td>";
		print "</TR><TR>";		
		print "<TD width='10%' align='left' bgcolor='".TD_COLOR."'>M&ecirc;s e Ano:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='mesano' class='text4' onkeyup=\"maskIt(this,event,'##/####')\" id='idmesano' value='".trim($mesano)."'></TD>";
		print "<TD width='10%' align='left' bgcolor='".TD_COLOR."'>Local de Pagamento:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='localpagto' id='idlocalpagto' onBlur='return Dados(this.value)'>";  
				print "<option value=-1>Selecione Local Pagamento</option>";
					$sql="Select id,descricao from carne_localpagto where unidade = ".$_SESSION['s_local'];
					$commit = mysqli_query($conec->con,$sql);
					$i=0;
					while($row = mysqli_fetch_array($commit)){
						
						$selected = "";
						
						if(isset($_SESSION['localpagto']) && $_SESSION['localpagto'] == $row['id']) {
							$selected = " Selected";
						}
						print "<option value=".$row['id'].$selected.">".trim($row['descricao'])."</option>";
						$i++;
					}
				print "</select>";

		print "</TD></TR><TR>";
		print "<TD width='10%' lign='left' bgcolor='".TD_COLOR."'>Valor Unimed:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='vlrunimed' maxlength='20' id='idvlrunimed' value=".$rowA['vlrunimed']." onblur=\"valormensal(this);\" onkeypress=\"return formatar_moeda(this,',','.',event);\"</TD>";
        print "<TD width='10%' bgcolor='".TD_COLOR."'>Contribuição:</TD>";
		print "<TD width='30%' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='vlrcontribuicao' maxlength='20' id='idvlrcontribuicao' value=".$rowA['vlrcontribuicao']." onblur=\"valormensal(this);\" onkeypress=\"return formatar_moeda(this,',','.',event);\"</TD>";
		print "</TR><TR>";
		print "<TD width='10%' lign='left' bgcolor='".TD_COLOR."'>Valor Mensal:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='vlrmensal' maxlength='20' id='idvlrmensal' value=".$rowA['vlrmensal']." onblur=\"valormensal(this);\" onkeypress=\"return formatar_moeda(this,',','.',event);\"</TD>";
        print "<TD width='10%' bgcolor='".TD_COLOR."'>Apene:</TD>";
		print "<TD width='30%' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='apene' maxlength='20' id='idvlrApene' value=".$rowA['apene']." onblur=\"valormensal(this);\" onkeypress=\"return formatar_moeda(this,',','.',event);\"</TD>";
		print "</TR><TR>";
		print "<TD width='10%' lign='left' bgcolor='".TD_COLOR."'>Tarifa:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='tarifa' maxlength='20' id='idtarifa' value=".$rowA['tarifa']." onkeypress=\"return formatar_moeda(this,',','.',event);\"</TD>";
        print "<TD width='10%' bgcolor='".TD_COLOR."'>Juros:</TD>";
		print "<TD width='30%' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='juros' maxlength='20' id='idjuros' value=".$rowA['juros']." onkeypress=\"return formatar_moeda(this,',','.',event);\"</TD>";
		print "</TR><TR>";
		print "<TD width='10%' lign='left' bgcolor='".TD_COLOR."'>utilizaçao:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='utilizacao' maxlength='20' id='idutilizacao' value=".$rowA['utilizacao']." onkeypress=\"return formatar_moeda(this,',','.',event);\"</TD>";
        print "<TD width='10%' bgcolor='".TD_COLOR."'>Outros:</TD>";
		print "<TD width='30%' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text4' name='outros' maxlength='20' id='idoutros' value=".$rowA['outros']." onkeypress=\"return formatar_moeda(this,',','.',event);\"</TD>";

		print "</TR><TR>";

	    $disabled = '';
	    $clasbutton = " class='button'";

	    if(liberamenu('Alterar Pagamento Carne')=="N"){
	    	$disabled = " disabled='disabled'";
	    	$clasbutton = " class='buttonDisabled'";
	    }

		print "<TD align='left' width='10%' bgcolor='".BODY_COLOR."'><input type='submit' $clasbutton value='".TRANS('BT_ALTER')."' name='submit' $disabled>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'></TD>";
		print "<TD align='left' width='30%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";

	} else

		// Vari�veis convertidas
		if(isset($_POST['codigo'])) {
				
				if(empty($_POST['datainativo'])) {
					$inativo = Fdate('01/01/1900');
				} else {
					$inativo = Fdate($_POST['datainativo']);
				}
				$registro = date("Y-m-d H:i:s");
		}

	// Excluindo registro com Delete		
	if (isset($_GET['action']) && $_GET['action'] == "excluir"){
		
			 $query2 = "select p.nrocarne, p.idcliente, t.nometitular from carne_pagamentos p join carne_titular t on t.id = p.idcliente where p.id ='".$_GET['cod']."'";
			 $resultado2 = mysqli_query($conec->con,$query2) or die('Erro ... '.$query2);
			 $rowCont = mysqli_fetch_array($resultado2);
			
			 $Log1 = liberamenu('Excluir Pagamento Carne');
	       	 $Log2 = $rowCont['idcliente'];
	    	 $log3 = $rowCont['nometitular'];
	    	 $log4 = false;
	    	 $log5 = "";
	 	     grava_log($_SESSION['s_uid'],$_SESSION['s_codoperacao'],$Log2,$log3,$log4,$log4,$log4,$log5);
		
			$query2 = "DELETE FROM carne_pagamentos WHERE id='".$_GET['cod']."'";
			$resultado2 = mysqli_query($conec->con,$query2) or die('Erro na exclus�o '.$query2);

			if ($resultado2 == 0)
			{
					$aviso = TRANS('ERR_DEL');
			}
			else
			{
					$aviso = TRANS('OK_DEL');
			}
			
			if($aviso != TRANS('OK_DEL')) {
				print "<script>mensagem('".$aviso."');</script>";
			}

			print "<script>redirect('".$_SERVER['PHP_SELF']."?cod=".$_GET['idcliente']."&action=incluir&cellStyle=true');</script>";
		

	} else


	// Incluindo registro com INSERT
	if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_CAD')))) {	

		 $query2 = "select nometitular from carne_titular where id ='".$_POST['cliente']."'";
		 $resultado2 = mysqli_query($conec->con,$query2) or die('Erro ... '.$query2);
		 $rowCont = mysqli_fetch_array($resultado2);
		
		 $Log1 = liberamenu('Incluir Pagamento Carne');
       	 $Log2 = $_POST['cliente'];
    	 $log3 = "Titular: ".$rowCont['nometitular'];;
    	 $log4 = false;
    	 $log5 = "";
 	     grava_log($_SESSION['s_uid'],$_SESSION['s_codoperacao'],$Log2,$log3,$log4,$log4,$log4,$log5);
		
		$lnCompet = substr($_POST['mesano'],3,4).substr($_POST['mesano'],0,2);
		$dtpagto = Fdate($_POST['datapagto']);
		$vlrunimed = str_replace(',','',$_POST['vlrunimed']);
		$vlrcontribuicao = str_replace(',','',$_POST['vlrcontribuicao']);
		$vlrmensal = str_replace(',','',$_POST['vlrmensal']);
		$apene = str_replace(',','',$_POST['apene']);
		$tarifa = str_replace(',','',$_POST['tarifa']);
		$juros = str_replace(',','',$_POST['juros']);
		$utilizacao = str_replace(',','',$_POST['utilizacao']);
		$outros = str_replace(',','',$_POST['outros']);

		$totalPago = ($vlrunimed+$vlrcontribuicao+$vlrmensal+$apene+$tarifa+$juros+$utilizacao);
		$totalPago = str_replace(',','',$totalPago);

		$_SESSION['localpagto'] = $_POST['localpagto'];


		$query = "INSERT INTO carne_pagamentos (idcliente,nrocarne,mesano,databaixa,localpagto,vlrunimed,vlrcontribuicao,vlrmensal,apene,tarifa,juros,utilizacao,outros,unidade,usuario,convenio,vlrpago)".
					" values (".$_POST['cliente'].",".$_POST['nrocarne'].",".$lnCompet.",'".$dtpagto."',".$_POST['localpagto'].",".$vlrunimed.",".$vlrcontribuicao.",".$vlrmensal.",".$apene.",".$tarifa.",".$juros.",".$utilizacao.",".$outros.",".$_SESSION['s_local'].",".$_SESSION['s_uid'].",'".$_POST['convenio']."',".$totalPago.")";
		$resultado = mysqli_query($conec->con,$query) or die('Erro no Insert '.$query);
			
		if ($resultado == 0)
			{
				$aviso = TRANS('ERR_INSERT');
			}
			else
			{
				$aviso = TRANS('OK_INSERT');

			
			}
			
		if($aviso != TRANS('OK_INSERT')) {
			echo "<script>mensagem('".$aviso."');</script>";
		} 

		echo "<script>redirect('pagamentoslistaunimed.php?cellStyle=true');</script>";

	} else

	// Alterando o registro com UPDATE
	if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_ALTER')))) {	
					
		 $query2 = "select p.nrocarne, p.idcliente, t.nometitular from carne_pagamentos p join carne_titular t on t.id = p.idcliente where p.id ='".$_POST['idpagto']."'";
		 $resultado2 = mysqli_query($conec->con,$query2) or die('Erro ... '.$query2);
		 $rowCont = mysqli_fetch_array($resultado2);
		
		 $Log1 = liberamenu('Alterar Pagamento Carne');
       	 $Log2 = $rowCont['idcliente'];
    	 $log3 = $rowCont['nometitular'];
    	 $log4 = false;
    	 $log5 = "";
 	     grava_log($_SESSION['s_uid'],$_SESSION['s_codoperacao'],$Log2,$log3,$log4,$log4,$log4,$log5);
		
 	     
		$lnCompet = substr($_POST['mesano'],3,4).substr($_POST['mesano'],0,2);
		$dtpagto = Fdate($_POST['datapagto']);
		
		$vlrunimed = str_replace(',','',$_POST['vlrunimed']);
		$vlrcontribuicao = str_replace(',','',$_POST['vlrcontribuicao']);
		$vlrmensal = str_replace(',','',$_POST['vlrmensal']);
		$apene = str_replace(',','',$_POST['apene']);
		$tarifa = str_replace(',','',$_POST['tarifa']);
		$juros = str_replace(',','',$_POST['juros']);
		$utilizacao = str_replace(',','',$_POST['utilizacao']);
		$outros = str_replace(',','',$_POST['outros']);

		$totalPago = ($vlrunimed+$vlrmensal+$vlrcontribuicao+$apene+$tarifa+$juros+$utilizacao);
		$totalPago = str_replace(',','',$totalPago);

		// k.vlrunimed, k.vlrcontribuicao, k.vlrmensal, k.apene, k.tarifa, k.juros, k.utilizacao, k.outros

		$query2 = "UPDATE carne_pagamentos SET mesano=".$lnCompet.",databaixa='".$dtpagto."', localpagto=".$_POST['localpagto'].", vlrpago=".$totalPago.", usuario=".$_SESSION['s_uid'].
		",vlrunimed=".$vlrunimed.",vlrcontribuicao=".$vlrcontribuicao.",vlrmensal=".$vlrmensal.",apene=".$apene.
		",tarifa=".$tarifa.",juros=".$juros.",utilizacao=".$utilizacao.",outros=".$outros." WHERE id=".$_POST['idpagto']." ";		
		
		$resultado2 = mysqli_query($conec->con,$query2) or die('Erro na query 4: '.$query2);
		
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

			echo "<script>redirect('pagamentosunimed.php?cod=".$_POST['cliente']."&action=incluir&cellStyle=true');</script>";
			
	}

	print "</table>";
	print "</form>";
	
?>

<script language="JavaScript">
/* Formata��o para qualquer mascara */

function valormensal(nVal){
	var nUnimed = document.getElementById('idvlrunimed').value;
	var nContribuicao = document.getElementById('idvlrcontribuicao').value;
	var nApene = document.getElementById('idvlrApene').value;
	var nTotal = parseFloat(nUnimed) + parseFloat(nContribuicao) + parseFloat(nApene);
	document.getElementById('idvlrmensal').value = nTotal.toFixed(2);
}


function ConsultaTaxas(valor){

	var idtaxas = $("#idtaxas").val();

    if(idtaxas != ""){

    	$("#validataxas").html('<span style="width: 70px; margin-top: 35px; color: red;">Calculando valor...!</span>');
    	
        $.ajax({

            type:"POST",

            url: "/carne/admin/geral/dadostaxas.php",

            dataType:"html",

            data:"idtaxas="+idtaxas,

            success:function(data){

                $("#validataxas").html(data);

            }

        });

    }

};

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

	function valida(){
		var ok = validaForm('calendario1','','Data Pagamento',1);
		if (ok) var ok = validaForm('idmesano','','Mes/Ano',1);		
		if (ok) var ok = validaForm('idvlrunimed','','Valor Unimed',1);
		if (ok) var ok = validaForm('idvlrcontribuicao','','Valor Contribuicao',1);
		return ok;
	}

</script>


<?php 
print "</body>";
print "</html>";
