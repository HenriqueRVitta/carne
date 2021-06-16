<!DOCTYPE HTML>
<!-- SPACES 2 -->
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="Resource-type" content="document">
    <meta name="Robots" content="all">
    <meta name="Rating" content="general">
    <meta name="author" content="Gabriel Masson">
    <style type="text/css">
    .quebrapagina {
      page-break-before: always;
    }
    </style>
    <title>Carn&ecirc; para pagto Banco</title>
    <link href="../../includes/icons/favicon.ico" rel="shortcut icon" type="image/x-icon">
    <link href="../../admin/carnebancario/css/style.css" rel="stylesheet" type="text/css">
  </head>
  <body>
<?php

echo "<!-- PARCELA -->
  <div class=\"parcela\">
    <div class=\"grid\">
      <div class=\"col4\">
        <div class=\"destaca\">
          <table width=\"10%\">
            <tr>
              <td>
		<img src=\"imagens/logosicoob.png\" alt=\"Banco Sicoob\" width=\"150\" height=\"40\" title=\"Siccob\" />
              </td>
            <td>
		<small style=\"font-size:15px\">Banco</small><br>
		{$dadosboleto["codigo_banco_com_dv"]}
            </td>
            </tr>

            <tr>
              <td>
                <small style=\"font-size:15px\">Parcela</small>
                <br>{$contador} / {$TotalParcelas}
              </td>
            <td>
              <small style=\"font-size:15px;font-weight: bold;\">Vencimento</small>
              <br>{$dadosboleto["data_vencimento"]}
            </td>
            </tr>
            <tr>
              <td colspan=\"2\">
                <small style=\"font-size:15px\">Ag&ecirc;ncia/C&oacute;digo Cedente</small>
                <br>{$dadosboleto["agencia_codigo"]}
              </td>
            </tr>
            <tr>
              <td>
                <small style=\"font-size:15px\">Moeda</small>
                <br>{$dadosboleto["especie"]}
              </td>
            <td>
              <small style=\"font-size:15px\">Quantidade</small>
              <br>&nbsp;
            </td>
            </tr>
            <tr>
              <td>
                <small style=\"font-size:15px\">Valor do Documento</small>
                <br>R$ {$dadosboleto["valor_boleto"]}
              </td>
	      <td></td>
            </tr>
            <tr>
              <td>
                <small style=\"font-size:15px\">Principal</small>
                <br> R$
              </td>
              <td>
                <small style=\"font-size:15px\">Encargos:</small>
                <br> R$
              </td>
            </tr>
            <tr>
              <td>
                <small style=\"font-size:15px\">Sub-Total</small>
                <br> R$
              </td>
              <td>
                <small style=\"font-size:15px\">B&ocirc;nus</small>
                <br> R$
              </td>
            </tr>
            <tr>
              <td>
                <small style=\"font-size:15px\">(=) Valor Cobrado</small>
                <br> R$ {$dadosboleto["valor_boleto"]}
              </td>
	      <td></td>
            </tr>
            <tr>
              <td>
                <small style=\"font-size:15px\">Nosso n&uacute;mero</small>
                <br> {$dadosboleto["nosso_numero"]}
              </td>
              <td>
                <small style=\"font-size:15px\">Nro Doc.</small>
                <br> {$dadosboleto["numero_documento"]}
              </td>
            </tr>
            <tr>
              <td colspan=\"2\">
                <small style=\"font-size:15px;font-weight: bold;\">Sacado</small>
                <br> <p style=\"font-size:12px;font-weight: bold;\">{$dadosboleto["sacado"]} {$dadosboleto["cpf"]}
			    <br> {$dadosboleto["endereco1"]}</p>
              </td>
            </tr>

          </table>
        </div>
      </div>
      <div class=\"col8\">
        <table width=\"90%\">
          <tr>
              <td>
		<img src=\"imagens/logosicoob.png\" alt=\"Banco Sicoob\" width=\"150\" height=\"40\" title=\"Siccob\" />
              </td>
            <td>
		<small style=\"font-size:15px\">Banco</small><br>
		{$dadosboleto["codigo_banco_com_dv"]}
            </td>
	    <td></td>
            <td colspan=\"3\" style=\"text-align:right;font-size:19px\">
			{$dadosboleto["linha_digitavel"]}
            </td>
	</tr>
	<tr>
            <td colspan=\"3\" style=\"font-weight: bold;\">
              <small style=\"font-size:15px;\">Local de Pagamento</small>
              <br>Pag&aacute;vel em qualquer banco at&eacute; o vencimento.
            </td>
	    <td></td>
	    <td></td>
            <td colspan=\"2\" style=\"font-weight: bold;\">
              <small style=\"font-size:15px;font-weight: bold;\">Vencimento</small>
              <br>{$dadosboleto["data_vencimento"]}
            </td>
          </tr>
          <tr>
            <td>
              <small style=\"font-size:15px\">Data do Documento</small>
              <br>{$dadosboleto["data_documento"]}
            </td>
            <td>
              <small style=\"font-size:15px\">Nro Doc.</small>
              <br>{$dadosboleto["numero_documento"]}
            </td>
            <td>
              <small style=\"font-size:15px\">Esp&eacute;cie Doc.</small>
              <br>{$dadosboleto["especie_doc"]}
            </td>
            <td>
              <small style=\"font-size:15px\">Aceite</small>
              <br>{$dadosboleto["aceite"]}
            </td>
            <td>
              <small style=\"font-size:15px\">Data Processamento</small>
              <br>{$dadosboleto["data_processamento"]}
            </td>
            <td>
              <small style=\"font-size:15px\">Nosso n&uacute;mero</small>
              <br>{$dadosboleto["nosso_numero"]}
            </td>
          </tr>

          <tr>
            <td>
              <small style=\"font-size:15px\">Uso do Banco</small>
              <br>&nbsp;
            </td>
            <td>
              <small style=\"font-size:15px\">Carteira</small>
              <br> {$dadosboleto["carteira"]}
            </td>
            <td>
              <small style=\"font-size:15px\">Moeda</small>
              <br> {$dadosboleto["especie"]}
            </td>
            <td>
              <small style=\"font-size:15px\">Quantidade</small>
              <br>&nbsp;
            </td>
            <td>
              <small style=\"font-size:15px\">(x) Valor</small>
              <br>&nbsp;
            </td>
            <td>
              <small style=\"font-size:15px\">(=) Valor do Documento</small>
              <br>R$ {$dadosboleto["valor_boleto"]}
            </td>
          </tr>
          <tr>
            <td colspan=\"5\">
              <small style=\"font-size:16px\"><b>Termo de Responsabilidade do Beneficiario</b></small>
            </td>
            <td>
              <small style=\"font-size:15px\">(-) Desconto/Abatimento</small>
              <br>R$
            </td>
	  </tr>
	  <tr>
            <td colspan=\"5\" style=\"border:none;\">
                {$dadosboleto["demonstrativo1"]}
                <br>{$dadosboleto["demonstrativo2"]}
                <br>{$dadosboleto["demonstrativo3"]}
            </td>
            <td>
              <small style=\"font-size:15px\">(-) Outras Dedu&ccedil;&otilde;es</small>
              <br>R$
            </td>
          </tr>
	  <tr>
            <td colspan=\"5\" style=\"border:none;\">
            	{$dadosboleto["instrucoes1"]}
              <br>{$dadosboleto["instrucoes2"]}
            </td>
            <td>
              <small style=\"font-size:15px\">(+) Mora/Multa</small>
              <br>R$
            </td>
          </tr>
	  <tr>
            <td colspan=\"5\" style=\"border:none;\">
            {$dadosboleto["instrucoes3"]}
            </td>
            <td>
              <small style=\"font-size:15px\">(+) Outros Acr&eacute;scimos</small>
              <br>R$
            </td>
          </tr>
	  <tr>
            <td colspan=\"5\"><small>SACADO:</small>
			<br><p style=\"font-size:14px;font-weight: bold;\">{$dadosboleto["sacado"]} {$dadosboleto["endereco1"]} {$dadosboleto["endereco2"]}</p>
            </td>
            <td>
              <small style=\"font-size:15px\" style=\"border:none;\">(=) Valor Cobrado</small>
              <br>R$ {$dadosboleto["valor_boleto"]}
            </td>
      </tr>
	  <tr>
            <td colspan=\"7\" vAlign=\"bottom\" align=\"left\" height=\"80\" width=\"90.66%\">
					";?><?php fbarcode($dadosboleto["codigo_barras"]); ?><?php echo "
          <br>Autentica&ccedil;&atilde;o mec&acirc;nica - Ficha de compensa&ccedil;&atilde;o
            </td>
       </tr>
      
      </table>
      </div>
      </div>		      
</div>";

if($contadorpaginascarne == 3){
  echo "<p class='quebrapagina'></p>";
  $contadorpaginascarne == 1;
}
			
?>


  </body>
</html>
