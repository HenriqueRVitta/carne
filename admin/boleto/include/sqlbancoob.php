<?php


	$data_vencimento = Fdate($dadosboleto['data_vencimento']);
	$data_processamento = Fdate($dadosboleto['data_processamento']);
	$valor_boleto = str_replace(',', '.', $dadosboleto['valor_boleto']);
	$inclusao = fdate(date("d/m/Y h:i:s"));
	 
		
	$query = "INSERT INTO carne_remessaboleto (nosso_numero,numero_documento,data_vencimento,data_processamento,valor_boleto,sacado,convenio,codigo_barras,linha_digitavel,codigo_banco_com_dv,inclusao) ".
	" values ('".$dadosboleto['nosso_numero']."','".$dadosboleto['numero_documento']."','".$data_vencimento.
	"','".$data_processamento."','".$valor_boleto."','".$dadosboleto['sacado']."','".$dadosboleto['convenio']."','".$dadosboleto['codigo_barras']."','".$dadosboleto['linha_digitavel']."','".$dadosboleto['codigo_banco_com_dv']."','".$inclusao."')";
	
	$resultado = mysql_query($query) or die('Erro no Insert '.$query);
	
	if ($resultado == 0) {
		$aviso = TRANS('ERR_INSERT');
	} else {
		$aviso = TRANS('OK_INSERT');
	}

?>
