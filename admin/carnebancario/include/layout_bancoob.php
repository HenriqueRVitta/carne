<?php

// +----------------------------------------------------------------------+
// | BoletoPhp - Vers�o Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo est� dispon�vel sob a Licen�a GPL dispon�vel pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Voc� deve ter recebido uma c�pia da GNU Public License junto com     |
// | esse pacote; se n�o, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colabora��es de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
// | PHPBoleto de Jo�o Prado Maia e Pablo Martins F. Costa                |
// |                                                                      |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Equipe Coordena��o Projeto BoletoPhp: <boletophp@boletophp.com.br>   |
// | Desenvolvimento Boleto BANCOOB/SICOOB: Marcelo de Souza              |
// | Ajuste de algumas rotinas: Anderson Nuernberg                        |
// +----------------------------------------------------------------------+

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title><?php echo $dadosboleto["identificacao"]; ?></title>
<link href="tabless.css" rel="stylesheet" type="text/css"  />
<META http-equiv=Content-Type content=text/html charset=ISO-8859-1>
<meta name="Generator" content="Projeto BoletoPHP - www.boletophp.com.br - Licen�a GPL" />
</HEAD>

<body>
  <table border="0" cellpadding="0" cellspacing="0" id="tb_logo">
    <tr>
      <td rowspan="2" valign="bottom" style="width:150px;"><img src="imagens/logosicoob.png" alt="Banco Sicoob" width="150" height="40" title="caixa" /></td>
      <td align="center" valign="bottom" style="font-size: 9px; border:none;">Banco</td>
      <td rowspan="2" align="right" valign="bottom" style="width:6px;"></td>
      <td rowspan="2" align="right" valign="bottom" style="font-size: 15px; font-weight:bold; width:445px;"><?php echo $dadosboleto["linha_digitavel"]; ?></td>
      <td rowspan="2" align="right" valign="bottom" style="width:2px;"></td>
    </tr>
    <tr>
      <td id="td_banco"><?php echo $dadosboleto["codigo_banco_com_dv"]; ?></td>
    </tr>
  </table>
  <table class="tabelas" style="width:666px; border-left:solid; border-left-width:2px; border-left-color:#000000;" border="0" cellspacing="0" cellpadding="0">
    <tr>

      <td class="td_7_sb"> </td>
      <td style="width: 468px;"><div class="titulo">Local do Pagamento</div>
      <div class="var">Pag&aacute;vel em qualquer banco at&eacute; a data de vencimento</div></td>
      <td class="td_7_cb"> </td>
      <td class="direito"><div class="titulo">Vencimento</div>
        <div class="var"><?php echo $dadosboleto["data_vencimento"]; ?></div></td>
      <td class="td_2"> </td>
    </tr>
    <tr>
      <td class="td_7_sb"> </td>
      <td><div class="titulo">Cedente</div>
      <div class="var"><span class="cp"><?php echo $dadosboleto["cedente"]; ?></span></div></td>
      <td class="td_7_cb"> </td>
      <td class="direito"><div class="titulo">Ag&ecirc;ncia / C&oacute;digo do Cedente</div>
      <div class="var"><?php echo $dadosboleto["agencia_codigo"]; ?></div></td>
      <td> </td>
    </tr>
  </table>
  <table class="tabelas" style="width:666px; border-left:solid; border-left-width:2px; border-left-color:#000000;" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td class="td_7_sb"> </td>
      <td style="width:103px;"><div class="titulo">Data  Documento</div>
        <div class="var"><?php echo $dadosboleto["data_documento"]; ?></div></td>
      <td class="td_7_cb"> </td>
      <td style="width:133px;"><div class="titulo">N&uacute;mero Documento</div>
      <div class="var"><?php echo $dadosboleto["numero_documento"]; ?></div></td>
      <td class="td_7_cb"> </td>
      <td style="width:62px;"><div class="titulo">Esp&eacute;cie Doc.</div>
      <div class="var"><?php echo $dadosboleto["especie_doc"]; ?></div></td>
      <td class="td_7_cb"> </td>
      <td style="width:34px;"><div class="titulo">Aceite</div>
      <div class="var"><?php echo $dadosboleto["aceite"];?></div></td>
      <td class="td_7_cb"> </td>
      <td style="width:103px;"><div class="titulo"></div>
      <div class="var"><?php echo $dadosboleto["data_processamento"]; ?></div></td>
      <td class="td_7_cb"> </td>
      <td class="direito"><div class="titulo">Nosso N&uacute;mero</div>
      <div class="var"><?php echo $dadosboleto["nosso_numero"]; ?></div></td>
      <td class="td_2"> </td>
    </tr>
  </table>
  <table class="tabelas" style="width:666px; border-left:solid; border-left-width:2px; border-left-color:#000000;" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td class="td_7_sb"> </td>
      <td style="width:118px;"><div class="titulo">Uso Banco</div>
      <div class="var"> </div></td>
      <td class="td_7_cb"> </td>
      <td style="width:55px;"><div class="titulo">Carteira</div>
      <div class="var"><?php echo $dadosboleto["carteira"]; ?></div></td>
      <td class="td_7_cb"> </td>
      <td style="width:55px;"><div class="titulo">Esp. Moeda</div>
      <div class="var"><?php echo $dadosboleto["especie"]; ?></div></td>
      <td class="td_7_cb"> </td>
      <td style="width:104px;"><div class="titulo">Qtde Moeda</div>
      <div class="var"><?php echo $dadosboleto["quantidade"]; ?></div></td>
      <td class="td_7_cb"> </td>
      <td style="width:103px;"><div class="titulo">Valor</div>
      <div class="var"><?php echo $dadosboleto["valor_unitario"]; ?></div></td>
      <td class="td_7_cb"> </td>
      <td class="direito"><div class="titulo">Valor do Documento</div>
      <div class="var"><?php echo $dadosboleto["valor_boleto"]; ?></div></td>
      <td class="td_2"> </td>
    </tr>
  </table>
  <table class="tabelas" style="width:666px; border-left:solid; border-left-width:2px; border-left-color:#000000;" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td rowspan="5" class="td_7_sb"></td>
      <td rowspan="5" valign="top"><div class="titulo" style="margin-bottom:18px;">Instru&ccedil;&otilde;es (texto de responsabilidade do Cedente)</div>
          <div class="var"><?php echo $dadosboleto["instrucoes1"]; ?><br />
      <?php echo $dadosboleto["instrucoes2"]; ?></div></td>
      <td class="td_7_cb"></td>
      <td class="direito"><div class="titulo">(+) Multa / Mora</div>
      <div class="var"> </div></td>
      <td class="td_2"></td>
    </tr>
    <tr>
      <td class="td_7_cb"></td>
      <td class="direito"><div class="titulo">(+) Outros Acr&eacute;scimos</div>
      <div class="var"> </div></td>
      <td class="td_2"></td>
    </tr>
    <tr>
      <td class="td_7_cb"></td>
      <td class="direito" valign="top"><div class="titulo">(=) Valor Cobrado</div>
      <div class="var"> </div></td>
      <td class="td_2"></td>
    </tr>
  </table>
  <table width="528" height="38" border="0" cellpadding="0" cellspacing="0" class="tabelas" style="width:666px; height:45px; border-left:solid; border-left-width:2px; border-left-color:#000000;">
    <tr>
      <td width="7" height="38" class="td_7_sb"></td>
      <td width="570" valign="top"><div class="titulo">Sacado</div>
          <div class="var" style="margin-bottom:2px; height:auto"><?php echo $dadosboleto["sacado"]; ?><?php echo isset($dadosboleto["cpf_cnpj"]) ? $dadosboleto["cpf_cnpj"] : '' ?><br><?php echo $dadosboleto["endereco"]; ?> / <?php echo $dadosboleto["cidade_uf"]; ?>
            - </div></td>
      <td width="83" class="td_7_sb"></td>
      
      <td width="4" class="td_2"></td>
    </tr>
  </table>
  <table style="width:666px; border-top:solid; border-top-width:2px; border-top-color:#000000" border="0" cellspacing="0" cellpadding="0">
    <tr>
    
      <td width="7" class="td_7_sb"> </td>
      <td width="440" style="width: 417px; height:50px;"><?php fbarcode($dadosboleto["codigo_barras"]); ?></td>
      <td width="37" class="td_7_sb"> </td>
      <td width="178" valign="top"><div class="titulo" style="text-align:left;">Autentica�ao Mec�nica</div></td>
      <td width="4" class="td_2"> </td>
    </tr>
  </table>

</body>

</html>

<?php 

$numero_documento = 0;
$numero_lote = 0;

include("sqlbancoob.php");

?>