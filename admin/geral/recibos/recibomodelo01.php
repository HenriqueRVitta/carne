<?php

$body = "<html>
<head>
<title>Recibo de Mesnsalidades</title>
<style type='text/css'>
.tg  {border-collapse:collapse;border-spacing:0;width:8cm;}
.tg td{border-color:black;border-style:solid;border-width:0px;font-family:Arial, sans-serif;font-size:14px;
  overflow:hidden;padding:10px 5px;word-break:normal;}
.tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
.tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}
</style>
</head>
<body>";


session_start();

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
//error_reporting(0); 
error_reporting(E_ALL);

include ('../../../includes/classes/conecta.class.php');
include ('../../../includes/classes/auth.class.php');
include ('../../../includes/classes/dateOpers.class.php');
include ('../../../includes/config.inc.php');
include ('../../../includes/functions/funcoes.inc');

//ob_clean();
ob_start();

$conec = new conexao;
$conec->conecta('MYSQL');

$query = 'select nome_hosp, end_hosp, cep_hosp, cgc_hosp, ddd1_hosp, fone_hosp, ddd2_hosp, fax_hosp, linhacab  from configuracao limit 1';
$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
$rowConfg = mysqli_fetch_array($resultado);
$nomehosp = $rowConfg['nome_hosp'];
$end_hosp = $rowConfg['end_hosp'];
$cep_hosp = $rowConfg['cep_hosp'];
$cgc_hosp = $rowConfg['cgc_hosp'];
$ddd1_hosp= $rowConfg['ddd1_hosp'];
$fone_hosp= $rowConfg['fone_hosp'];
$ddd2_hosp= $rowConfg['ddd2_hosp'];
$fax_hosp = $rowConfg['fax_hosp'];
$linhacab = $rowConfg['linhacab'];

$query = 'SELECT a.idcliente, a.databaixa, a.vlrpago, a.mesano, b.nometitular FROM carne_pagamentos a join carne_titular b on b.id = a.idcliente where a.id = '.$_GET['cod'];
$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
$rowPaciente = mysqli_fetch_array($resultado);
$idcliente = $rowPaciente['idcliente'];
$databaixa = $rowPaciente['databaixa'];
$vlrpago   = $rowPaciente['vlrpago'];
$nometitular= $rowPaciente['nometitular'];
$mesano = $rowPaciente['mesano'];
$mesesReferente = substr($mesano,4,2).'/'.substr($mesano,0,4);

$query = "SELECT sum(vlrpago) as vlrpago, mesano FROM carne_pagamentos where idcliente = ".$idcliente." group by databaixa,mesano order by mesano";
$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
$mesesAnteriores = '';
$valorTotalMeses = 0.00;
while($rowMeses = mysqli_fetch_array($resultado)){
    $mesesAnteriores.=substr($rowMeses['mesano'],4,2).'/'.substr($rowMeses['mesano'],0,4).' ';
    if($rowMeses['vlrpago'] > 0){
        $valorTotalMeses=$rowMeses['vlrpago'];
    }
    
}
if(!empty($mesesAnteriores)) {
    $mesesReferente = $mesesAnteriores;
    $vlrpago = $valorTotalMeses;
}

$fone1 = "(".$ddd1_hosp.") ".$fone_hosp;
$fone2 = '';
if(!empty($ddd2_hosp)) {
    $fone2 = "(".$ddd2_hosp.") ".$ddd2_hosp;
}

$lcString = "
<table class='tg'>
<thead>
  <tr>
    <th class='tg-0pky' style='text-align:center;font-weight:bold;font-size:18px'>RECIBO DE PAGAMENTO</th>
  </tr>
</thead>
<tbody>
  <tr>
    <td class='tg-0pky' style='text-align:center;font-weight:bold;font-size:13px'>".$nomehosp."</td>
  </tr>
  <tr>
    <td class='tg-0pky'>CNPJ.:".mask($cgc_hosp,'##.###.###/####-##')."</td>
  </tr>
  <tr>
    <td class='tg-0pky'>MATRÍCULA: ".$idcliente."</td>
  </tr>
  <tr>
    <td class='tg-0pky' style='font-weight:bold;font-size:12px'>NOME: ".$nometitular."</td>
  </tr>
  <tr>
    <td class='tg-0pky'>DATA: ".date( 'd/m/Y', strtotime($databaixa))."</td>
  </tr>
  <tr>
    <td class='tg-0pky'>MES REF.: ".$mesesReferente."</td>
  </tr>
  <tr>
    <td class='tg-0pky' style='font-weight:bold;'>TIPO DE PAGAMENTO: MENSALIDADE</td>
  </tr>
  <tr>
    <td class='tg-0pky' style='font-weight:bold;'>VALOR.: R$ ".$vlrpago."</td>
  </tr>
  <tr>
    <td class='tg-0pky'></td>
  </tr>
  <tr>
    <td class='tg-0pky' style='text-align:center;font-weight:bold;font-size:14px;border-width:1px'>".$linhacab."</td>
  </tr>
</tbody>
</table>
</body>
</html>";

$html = $body.$lcString;


include("../../../includes/mpdf/vendor/autoload.php");

/*
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [80, 120]]);
$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
*/

// Referência
// https://github.com/mpdf/mpdf/issues/615

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [80, 130],
'default_font_size' => 0, // font size - default 0
'default_font' => '', // default font family
'margin_left' => 1,
'margin_right' => 1,
'margin_top' => 1,
'margin_bottom' => 1,
'margin_header' => 0,
'margin_footer' => 0,
'orientation' => 'P']); // L - landscape, P - portrait

$mpdf->WriteHTML($html);
$mpdf->Output();
exit;

//echo $html;

?>


