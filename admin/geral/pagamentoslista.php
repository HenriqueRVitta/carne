<?php
/*      Copyright 2014 MCJ Assessoria Hospitalar e Informática LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 30/01/2015 07:34

		* Módulo Carnê *

		Lista a tabela carne_titular para consulta e registro de paggamentos

*/
	session_start();
	
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	include ("../../includes/classes/paging.class.php");
	
	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<html xmlns='http://www.w3.org/1999/xhtml' lang='pt-br' xml:lang='pt-br'>";
	print "<BODY onLoad='document.pagamentoslista.idSearch.focus();'bgcolor='".BODY_COLOR."'>";


	$fecha = "";
	if (isset($_GET['popup'])) {
		$fecha = "window.close()";
	} else {
		$fecha = "history.back()";
	}

	if (isset($_POST['search'])){
		$search = $_POST['search'];
	} else
		$search = "";
	

	print "<BR><B><font size=4>"."Administra&ccedil;&atilde;o de Pagamentos"."</font></B><BR>";

	print "<FORM name='pagamentoslista' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	$PAGE = new paging("PRINCIPAL");
	$PAGE->setRegPerPage($_SESSION['s_page_size']);
	
	
	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='5' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

       	$query = "SELECT max(id) as id FROM carne_titular ";
		$resultado = mysql_query($query) or die('ERRO NA EXECUÇÂO DA QUERY DE MAX ID!');
       	$maxid = mysql_fetch_array($resultado);
       	
       	$cond=0;
       	$query = "SELECT c.*, p.parcelamento, p.databaixa, p.vlrpago, p.taxa, p.mesano, space(25) as nome, p.docfinanceiro FROM carne_titular c Join carne_pagamentos p on p.idcliente=c.id ";
		if (isset($_GET['cod'])) {
			
			$query.= " WHERE c.id = ".$_GET['cod']." ";
			$cond=1;
		}


		$mesano = "&mesano=";
		$carne = "";
		$Parcelado = "Nao";		
		
		if ((isset($_POST['search'])) && !empty($_POST['search'])) {

			// Se informado código de barra ou prontuario
			if(is_numeric($_POST['search'])) {
				
				if(strlen($_POST['search'])>=18) { $Parcelado = "Sim"; } else { $Parcelado = "Nao"; }
				
				if($_SESSION['s_utilizacontrato']=='Nao') {
					$nPos = 4;
					$carne = substr($_POST['search'],0,4);
					
					if(strlen($_POST['search'])==9) {
						$nPos = 3;
						$carne = substr($_POST['search'],0,3);
					}

					if(strlen($_POST['search'])==8) {
						$nPos = 2;
						$carne = substr($_POST['search'],0,2);						
					}

					if(strlen($_POST['search'])==7) {
						$nPos = 1;
						$carne = substr($_POST['search'],0,1);						
					}

					
					// Implementação para o Novo Codigo de barra conforme impressao
					// no Modulo Adm Utilitarios -> Gerar Carne para Grafica Sistema SAGMAX
					if(strlen($_POST['search']) == 12 or strlen($_POST['search']) == 14 or strlen($_POST['search']) == 15 && substr($_POST['search'],0,1) == 9) {

						$query.= " where c.id = ".trim(substr($_POST['search'],1,5))."";
						
					} else {

						$query.= " where c.nrocarne = ".trim(substr($_POST['search'],0,$nPos))."";
						
					}
					
					
				} else {
					
					//$query.= " where c.id = ".trim(substr($_POST['search'],0,8))."";	
					$nPos = 4;
					
					if(strlen($_POST['search'])==9) {
						$nPos = 3;
					}

					if(strlen($_POST['search'])==8) {
						$nPos = 2;
					}

					if(strlen($_POST['search'])==7) {
						$nPos = 1;
					}
					
					$carne = trim($_POST['search']);						

					// Implementação para o Novo Codigo de barra conforme impressao
					// no Modulo Adm Utilitarios -> Gerar Carne para Grafica Sistema SAGMAX
					if(strlen($_POST['search']) == 12 or strlen($_POST['search']) == 14 or strlen($_POST['search']) == 15 && substr($_POST['search'],0,1) == 9) {

						$query.= " where c.id = ".trim(substr($_POST['search'],1,5))."";
						
					} else {

						if($nPos == 4) {
							$query.= " where c.id = ".trim(substr($_POST['search'],0,$nPos))."";
						} else {
							$query.= " where c.nrocarne = ".trim(substr($_POST['search'],0,$nPos))."";
						}
						
					}
					
				}
				
			} else {
				$query.= " where lower(c.nometitular) like lower(('%".$_POST['search']."%'))";				
			}
			$cond=2;

			//$mesano = "&mesano=".trim(substr($_POST['search'],4,6));
			$ini = 4;
			if(strlen($_POST['search']) == 9) {
				$ini = 3;
			}

			if(strlen($_POST['search']) == 8) {
				$ini = 2;
			}
			
			if(strlen($_POST['search']) == 7) {
				$ini = 1;
			}
			
			
			$mesano = "&mesano=".trim(substr($_POST['search'],$ini,6)).trim(substr($_POST['search'],7,7))."&parcelado=".$Parcelado;


			// Implementação para o Novo Codigo de barra conforme impressao
			// no Modulo Adm Sistema SAGMAX
			if(strlen($_POST['search']) == 12 or strlen($_POST['search']) == 14 or strlen($_POST['search']) == 15 && substr($_POST['search'],0,1) == 9) {
				$mesano = "&mesano=".trim(substr($_POST['search'],6,2)).trim(substr($_POST['search'],8,4))."&parcelado=".$Parcelado;
			}
			
		}

		if($cond==0) {
			$query.=" Where c.unidade =".$_SESSION['s_local']." ORDER BY p.id desc limit 50";
		} else {
			
			//$query.=" and c.unidade =".$_SESSION['s_local']." ORDER BY p.id desc limit 50";
			$query.=" and c.unidade =".$_SESSION['s_local']." ORDER BY p.mesano desc limit 50";
			
		}

		$querylista = $query;
		$resultado = mysql_query($query) or die('ERRO NA EXECUÃ‡Ã‚O DA QUERY DE CONSULTA 1! '.$query);		
		$pagamentos = mysql_query($query) or die('ERRO NA EXECUÃ‡Ã‚O DA QUERY DE CONSULTA 1! '.$query);
		
		$registros = mysql_num_rows($resultado);

		// Se não encontrou nenhum registro de pagamento
		// utiliza left Join carne_pagamentos na $query 
		if ((isset($_POST['search'])) && !empty($_POST['search'])) {
			
			if ($registros == 0) {
			$query = str_replace("Join carne_pagamentos","left Join carne_pagamentos",$query);
			$resultado = mysql_query($query) or die('ERRO NA EXECUÃ‡Ã‚O DA QUERY DE CONSULTA 1! '.$query);		
			$registros = mysql_num_rows($resultado);
			
				if($registros > 0){
					$querylista = $query;
				}
			}
		
		}		
		
	// variável que controla permissões dos botões para incluir, editar e excluir  do usuário
	$disabled = '';
	$clasbutton = " class='button'"; 	
	
	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {
		
		//$PAGE->execSQL();		
	    if(liberamenu('Incluir Titular')=="N"){
	    	$disabled = " disabled='disabled'";
	    	$clasbutton = " class='buttonDisabled'";
	    }
		
		print "<tr><td colspan='4'>".
			"<input type='text' class='text3' name='search' id='idSearch' value='".$search."' placeholder='Digite o nome do cliente'>&nbsp;";
			print "<input type='submit' name='BT_SEARCH' class='button' value='".TRANS('BT_FILTER')."'>".
			"</td></tr>";
		$id = "";

		
		// Se nada encontrado faço a pesquisa em carne_dependente
		if ($registros == 0) {

			$query = "SELECT c.*, p.parcelamento, p.databaixa, p.vlrpago, p.taxa, p.mesano, d.nome, p.docfinanceiro FROM carne_titular c Join carne_dependente d on d.idtitular = c.id ".
		    " Join carne_pagamentos p on p.idcliente=c.id Where d.nome like '%".trim($search)."%' limit 1000";
			$querylista = $query;
			$resultado = mysql_query($query) or die('ERRO NA EXECUÃ‡Ã‚O DA QUERY DE CONSULTA 1! '.$query);		
			$registros = mysql_num_rows($resultado);
		    
		}
	
		
		if ($registros == 0)
		{

			
			echo "<tr><td colspan='4'>".mensagem('Nenhum registro encontrado...')."</td></tr>";
			
			//echo "<tr><td colspan='4'>".mensagem(TRANS('MSG_NOT_REG_CAD'))."</td></tr>";
	 	    //echo "<script>redirect('pagamentos.php?cod=".$carne.$mesano."&action=incluir&cellStyle=true');</script>";
	 	    
	 	    exit;
			
		}
		
		else
		
		{

		/***********
		 * Começa aqui a verificação de inadimplência
		 * Henrique 26/06/2018 15:39
		 */
			
			$desde = "";
			$cMeses = "";
			$cMensagemAtraso = "";
			$idCliente = 0;
			if ((isset($_POST['search'])) && !empty($_POST['search'])) {

				// Pego a Data de Inicio do Cadastro do Titular
				$arraydados = mysql_fetch_array($resultado);
				
				$sqlpagto = "select a.id,a.nometitular, a.datainicio Data_Inicio, d.valor as ValordoPlano,
				sum(b.vlrpago) TotalPago,
				TIMESTAMPDIFF(MONTH,a.datainicio,now()) TotalMeses,
				count(b.databaixa) MesesPagos, sum(b.vlrpago) TotalPago,
				(TIMESTAMPDIFF(MONTH,a.datainicio,now()) - count(b.databaixa)) MesesInadimplente,
				(d.valor * (TIMESTAMPDIFF(MONTH,a.datainicio,now()) - count(b.databaixa))) as TotalDebito
				from carne_titular a Join carne_pagamentos b on b.idcliente = a.id
				join carne_contratos c on c.idtitular = a.id
				join carne_competenciaplano d on d.idplano = c.plano
				where a.id = ".$arraydados['id'];

				$resultadopagto = mysql_query($sqlpagto) or die('ERRO AO EXECUTAR QUERY carne_pagamentos!');
				while ($rowpagto= mysql_fetch_array($resultadopagto)) {
					
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
		 * Termina aqui a verificação de inadimplência
		 * Henrique 26/06/2018 15:39
		 */
			
			
			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr><td colspan='4'>";
			print "<B>&Uacute;ltimos 50 pagamentos efetuados...</B></TD>";
			print "</tr>";

			if(!empty($cMensagemAtraso)){
				print "<tr><td colspan='4'>";
				print "<B  style='background-color:LightSalmon;'>".$cMensagemAtraso."</B></TD>";
				print "</tr>";
			}
			
			//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
			print "<TR class='header'><td class='line'>"."Cliente"."</TD>"."<td class='line' width='10%'>"."C&oacute;digo"."</TD>"."<td class='line' width='10%'>"."Nro Carn&ecirc;"."</TD>"."<td class='line'>"."Data Pagto"."</TD>"."<td class='line'>"."Compet&ecirc;ncia"."</TD>"."<td class='line'>"."Vlr Pago"."</TD>".			
			"<td class='line'>"."Parcelado"."</TD>"."<td class='line' align='center'>"."PAGAR/CONSULTAR"."</TD></tr>";
			
			$j=2;
			$id = "";
			if(isset($idCliente) && $idCliente > 0){
				$id = $idCliente;
				$mesano = "&mesano=";
			} else {
				
				$id = "".trim($search);
				$mesano = "&mesano=";
				
			}
			
			// Se não é codigo de barra leva mes em branco
			if ((isset($_POST['search'])) && !empty($_POST['search'])) {
				if(strlen($_POST['search'])<=4 or !is_numeric($_POST['search'])) {
					$mesano = "";
				}
			}

			$resultado = mysql_query($querylista) or die('ERRO NA EXECUÃ‡Ã‚O DA QUERY DE CONSULTA 1! '.$querylista);		
			while ($row = mysql_fetch_array($resultado))
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

				$id = $row['id'];
				
				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
				$nm = trim($row['nome']);
				if(!empty($nm)) {
					print "<td class='line'>TITULAR: ".$row['nometitular']."<br><br>"."DEPENDENTE: ".$row['nome']."</td>";
				} else {
					print "<td class='line'>".$row['nometitular']."</td>";
				}
				print "<td class='line'>".$row['id']."</td>";
				print "<td class='line'>".$row['nrocarne']."</td>";
				
				$dtbaixa = str_replace('/','',substr(converte_datacomhora($row['databaixa']),0,10));
				
				if(empty($dtbaixa)) {
					print "<td class='line'>INADIMPLENTE</td>";
					print "<td class='line'>INADIMPLENTE</td>";
					print "<td class='line'>INADIMPLENTE</td>";
				} else {
					print "<td class='line'>".mask($dtbaixa,'##/##/####')."</td>";
					print "<td class='line'>".invertecomp($row['mesano'],1)."</td>";
					print "<td class='line'>".$row['vlrpago']."</td>";
				}
				

				if($row['parcelamento']==1) { $Parcela = "SIM"; } else { $Parcela = "NAO"; }
				print "<td class='line' align='center'>".$Parcela."</td>";
				
				// Se já foi Exportado para o Financeiro
				if(!empty($row['docfinanceiro'])){
					print "<td class='line' align='center'>Exportado Financeiro</td>";					
				} else {
					print "<td class='line' align='center'><a onClick=\"redirect('pagamentos.php?cod=".$row['id'].$mesano."&action=incluir&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."table-money-icon.png' title='".TRANS('HNT_EDIT')."'></a></td>";				
				}
											
				print "</TR>";
			}
			print "<tr><td colspan='4'>";
			print "</td></tr>";
			
				// Direciona para registrar pagamentos
				if ((isset($_POST['search'])) && !empty($_POST['search'])) {
					// Se informado código de barra ou prontuario/nrocarne
					if(is_numeric($_POST['search'])) {
						echo "<script>redirect('pagamentos.php?cod=".$id.$mesano."&action=incluir&cellStyle=true');</script>";
					}
			    }
									
		}

	} else
	
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

		print "<script>redirect('abastitular.php?cod=0&action=incluir');</script>";

	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<BR><b><font size=2 color='blue'>"."Edi&ccedil;&atilde;o Dados do Cliente"."</b></font><BR>";		

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."C&oacute;digo".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='codigo' class='mini2' id='idcodigo' onkeyup=\"maskIt(this,event,'######')\" value='".strzero($row['id'],6)."' readonly='true' ></td>";
		print "<TD class='line'><a href='titular.php'><img height='22' width='22' src='".ICONS_PATH."voltar.png' title='Voltar'></a></TD>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome do Cliente".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text3' name='nome' maxlength='45' id='idnome' value='".$row['nometitular']."'></td>";
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
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='cep' class='text4' id='cep' onBlur=\"javascript:consultacep(this.value);\"  maxlength='10' OnKeyPress=\"javascript:formatarcampo('##.###-###', this)\" value='".$row['cep']."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Endere&ccedil;o".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='endereco' maxlength='45' class='text3' id='idendereco' value='".$row['endereco']."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."N&uacute;mero".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='numero' class='text4' id='idnumero' value='".$row['numero']."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Bairro".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='bairro' maxlength='45' class='text3' id='idbairro' value='".$row['bairro']."'></td>";
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
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='celular' class='text3' maxlength='45' id='idcelular' onkeyup=\"maskIt(this,event,'(##)####-#####')\" value='".$row['celular']."'></td>";
		print "</TR><TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Data Nascimento".":</TD>";
		$dtnasc = str_replace('/','',substr(converte_datacomhora($row['datanasc']),0,10));
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='dtnasc' class='text4' onkeyup=\"maskIt(this,event,'##/##/####')\" id='iddtnasc' onBlur='return doDateVenc(this.id,this.value, 4)' value='".mask($dtnasc,'##/##/####')."'></td>";
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
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='profissao' maxlength='45' class='text3' id='idprofissao' value='".$row['profissao']."'></td>";
		print "</TR></TD>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Identidade".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='identidade' maxlength='45' class='text3' id='idrg' value='".$row['identidade']."'></td>";
		print "</TR><TR>";		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."CPF".":</TD>";
		print "<TD width='40%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='cpf' class='text3' id='idcpf' value='".$row['cpf']."' onkeyup=\"maskIt(this,event,'###.###.###-##')\"></td>";
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

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Prontu&aacute;rio".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='prontuario' maxlength='7' class='text4' onkeyup=\"maskIt(this,event,'#######')\" id='idprontuario' value='".$row['prontuario']."'></td>";
		print "</TR></TD>";
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nro Carn&ecirc;".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nrocarne' maxlength='9' class='text4' onkeyup=\"maskIt(this,event,'#######')\" id='idnrocarne' value='".$row['nrocarne']."'></td>";
		print "</TR></TD>";

		print "<TD width='5%' align='left' bgcolor='".TD_COLOR."'>"."Grupo".":</TD>";
		print "<TD width='10%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select2' name='estcivil' id='idestcivil'>";  
		print "<option value='1'".$selected1.">Hospital</option>";  
		print "<option value='2'".$selected2.">Geral</option>";  
		print "</select>";  
		print "</TR></TD>";
		
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome do Pai".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nomepai' maxlength='45' class='text3' id='idnomepai' value='".$row['nomepai']."'></td>";
		print "</TR></TD>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>"."Nome da M&atilde;e".":</TD>";
		print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nomemae' maxlength='45' class='text3' id='idnomemae' value='".$row['nomemae']."'></td>";
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


	} else

		// Variáveis convertidas
		if(isset($_POST['codigo'])) {
				
				$nascimento = Fdate($_POST['dtnasc']);
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
				
		}

	// Excluindo registro com Delete		
	if (isset($_GET['action']) && $_GET['action'] == "excluir"){
			$query2 = "DELETE FROM carne_titular WHERE id='".$_GET['cod']."'";
			$resultado2 = mysql_query($query2) or die('Erro na exclusão '.$query2);

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

			print "<script>redirect('".$_SERVER['PHP_SELF']."');</script>";
		

	} else


	// Incluindo registro com INSERT
	if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_CAD')))) {	

		$erro=false;

		$qryl = "SELECT * FROM carne_titular WHERE nometitular='".$_POST['nome']."' and nomemae='".$_SESSION['nomemae']."'";
		$resultado = mysql_query($qryl) or die('Erro na Query 1 :'.$qryl);
		$linhas = mysql_num_rows($resultado);

		if ($linhas > 0)
		{
				$aviso = TRANS('MSG_EXIST_REG_CAD_SYSTEM');
				$erro = true;;
		}

		
		if (!$erro)
		{

			if(empty($_POST['prontuario'])) { $prontuario = 0; } else $prontuario = $_POST['prontuario'];

			
			$query = "INSERT INTO carne_titular (nometitular,endereco,numero,cep,bairro,cidade,codcidade,uf,telefoneres,telefonecom,celular,datanasc,qtdefilhos,escolaridade,localtrabalho,profissao,identidade,cpf,estadocivil,sexo,nomemae,nomepai,email,unidade,nrocontrato,registro,prontuario)".
					" values ('".$_POST['nome']."','".$_POST['endereco']."','".$_POST['numero']."','".$cep."','".$_POST['bairro']."','".$_POST['cidade']."','".$codcidade."','".$_POST['uf']."','".$foneres."','".$fonecom."','".$celular."','".$nascimento."',".$_POST['qtdefilhos'].",'".$_POST['escolaridade']."','".$_POST['localtrab']."','".$_POST['profissao']."','".$_POST['identidade']."','".$_POST['cpf']."','".$_POST['estcivil']."','".$_POST['sexo']."','".$_POST['nomemae']."','".$_POST['nomepai']."','".strtolower($_POST['email'])."','".$_SESSION['s_local']."',".$zero.",'".$registro."','".$prontuario."')";
						
			$resultado = mysql_query($query) or die('Erro no Insert '.$query);
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
		
		echo "<script>redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else

	// Alterando o registro com UPDATE
	if ((isset($_POST['submit'])  && ($_POST['submit'] == TRANS('BT_ALTER')))) {	

		if(empty($_POST['prontuario'])) { $prontuario = 0; } else $prontuario = $_POST['prontuario'];
		
		$query2 = "UPDATE carne_titular SET nometitular='".$_POST['nome']."',endereco='".$_POST['endereco']."', numero='".$_POST['numero']."', cep='".$cep."', bairro='".$_POST['bairro']."', cidade='".$_POST['cidade']."', codcidade='"."', uf='".$_POST['uf']."', telefoneres='".$foneres."', telefonecom='".$fonecom."', celular='".$celular."', datanasc='".$nascimento."', qtdefilhos=".$_POST['qtdefilhos'].", escolaridade='".$_POST['escolaridade']."', localtrabalho='".$_POST['localtrab']."', profissao='".$_POST['profissao']."', identidade='".$_POST['identidade']."', cpf='".$_POST['cpf']."', estadocivil='".$_POST['estcivil']."', sexo='".$_POST['sexo']."', nomemae='".$_POST['nomemae']."', nomepai='".$_POST['nomepai']."', email='".strtolower($_POST['email'])."', unidade='".$_SESSION['s_local']."', prontuario='".$prontuario."' WHERE id=".$_POST['codigo']." ";		
		
		$resultado2 = mysql_query($query2) or die('Erro na query 2 :'.$query2);

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

<script language="JavaScript">
/* Formatação para qualquer mascara */

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
		var ok = validaForm('idcodigo','','Cï¿½digo',1);
		if (ok) var ok = validaForm('idnome','','Nome do Cliente',1);
		if (ok) var ok = validaForm('idcidade','','Cidade',1);		
		if (ok) var ok = validaForm('iddtnasc','','Data de Nascimento',1);		
		if (ok) var ok = validaForm('idcpf','','CPF',1);		

		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
