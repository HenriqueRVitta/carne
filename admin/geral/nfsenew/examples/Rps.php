<?php

session_start();
	
	include ("../../../../includes/classes/conecta.class.php");
	include ("../../../../includes/config.inc.php");
	include ("../../../../includes/functions/funcoes.inc");
	
	$conec = new conexao;
	$conec->conecta('MYSQL');

	
	$query = "SELECT razao FROM cadastro_unidades";
	$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$configEmp = mysqli_fetch_array($resultado);
	$NomeEmpresa = retira_acentos_UTF8($configEmp['razao']);
	
	$query = "SELECT nfseprefeitura, cnpj, im, tiporps, naturezaoperacao, regimetributa, optantesimples, incentivadorcultural, codservico, codigocnae, codtribmun, codmunicipiotrib, retemiss, aliquota, tipoambiente FROM config";
	$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$config = mysqli_fetch_array($resultado);

	$dirarquivoremessa = "../../../../admin/carnebancario/remessas";
	
	
$cnpj = $config['cnpj'];
$im = $config['im'];
$codtribmun = $config['codtribmun'];
$tipoambiente = $config['tipoambiente'];
	
error_reporting(E_ALL);
ini_set('display_errors', 'On');


// Autoload das classes NFSe
require_once '../bootstrap.php';

use NFePHP\Common\Certificate;
use NFePHP\NFSeNac\Tools;
use NFePHP\NFSeNac\Rps;
use NFePHP\NFSeNac\Common\Soap\SoapFake;
use NFePHP\NFSeNac\Common\FakePretty;


try {

	
	$inicio = $_POST['inicio'];
	$fim= $_POST['fim'];

	$DataEmissao = date('Y-m-d').'T'.date('H:i:s');

	$IdCliente = '';
	$arr = $_POST['selecionado'];
	foreach ($arr as &$value) {
    
			$IdCliente.=trim($value).',';

	}

	$IdCliente = '('.substr($IdCliente,0,strlen($IdCliente)-1).')';
	

$querytitular = "SELECT a.idretorno, sum(a.valor) as valor, b.* ".
" FROM retornobanco a Join carne_titular b on b.id = a.idcliente ".
" where datapagto between '".$inicio."' and '".$fim."' and nfse = 1 group by a.cpfcnpj order by a.datapagto,b.nometitular";
$resultadoTitular = mysqli_query($conec->con,$querytitular) or die('ERRO NA QUERY !'.$querytitular);


while($rowtomador = mysqli_fetch_array($resultadoTitular)) {

$processarNFSe = false;

 if(in_array($rowtomador['idretorno'],$arr)){
 	$processarNFSe = true;
 }
 

if($processarNFSe == true) {

	// Obtendo o nro do LOTE
	// Insert em carne_loterps
	$querylote = "insert into carne_loterps (dtemissao) values ('".date("Y-m-d H:i:s")."')";
	$resultadoLote = mysqli_query($conec->con,$querylote) or die('ERRO NA QUERY !'.$querylote);
	$querylote = "Select max(id) as proximo from carne_loterps";
	$resultadoLote = mysqli_query($conec->con,$querylote) or die('ERRO NA QUERY !'.$querylote);
	$rowlote = mysqli_fetch_array($resultadoLote);
	$lote = $rowlote['proximo'];
	// Fim LOTE
	
    $configuracao = ['cnpj' =>  $cnpj,
    'im' => $im,
    'cmun' => $codtribmun,
    'razao' => $NomeEmpresa,
    'tpamb' => $tipoambiente
    ];

    $configJson = json_encode($configuracao);

    $content = file_get_contents('../certs/certificado.pfx');
    $password = '123456';
    
    $cert = Certificate::readPfx($content, $password);
    $soap = new SoapFake();
    $soap->disableCertValidation(true);
    
    $tools = new Tools($configJson, $cert);
    $tools->loadSoapClass($soap);

    $arps = [];
    
        
$std = new \stdClass();
$std->version = '1.00'; //indica qual JsonSchema USAR na validação
$std->IdentificacaoRps = new \stdClass();
$std->IdentificacaoRps->Numero = (int)$rowtomador['idretorno']; // 11; //limite 15 digitos
$std->IdentificacaoRps->Serie = '1'; //BH deve ser string numerico
$std->IdentificacaoRps->Tipo = (int)$config['tiporps']; // 1 - RPS 2-Nota Fiscal Conjugada (Mista) 3-Cupom
$std->DataEmissao = $DataEmissao; //'2018-10-31T12:33:22';
$std->NaturezaOperacao = (int)$config['naturezaoperacao'];	// 1 – Tributação no município
							                            // 2 - Tributação fora do município
							                            // 3 - Isenção
							                            // 4 - Imune
							                            // 5 – Exigibilidade suspensa por decisão judicial
							                            // 6 – Exigibilidade suspensa por procedimento administrativo

$std->RegimeEspecialTributacao = (int)$config['regimetributa'];	// 1 – Microempresa municipal
						                                       // 2 - Estimativa
						                                       // 3 – Sociedade de profissionais
						                                       // 4 – Cooperativa
						                                       // 5 – MEI – Simples Nacional
						                                       // 6 – ME EPP – Simples Nacional

$CodMunTomador = '3131307'; // Ipatinga
$cCidade = $rowtomador['cidade']; 

$BairroTomador = trim(substr($rowtomador['bairro'],0,50));
if(empty($rowtomador['bairro'])) {
	$BairroTomador = 'Nao informado';	
}
$numerotomador = 'S/N';
if(!empty($rowtomador['numero'])){
	$numerotomador = substr($rowtomador['numero'],0,3);
}

switch ($cCidade) {
	case "TARUMIRIM":
		$CodMunTomador = '3168408';
	case 'SOBRALIA':
		$CodMunTomador = '3167707';
	case 'CORONEL FABRICIANO':
		$CodMunTomador = '3119401';
	case 'ITANHOMI':
		$CodMunTomador = '3133204';
	case 'IPABA':
		$CodMunTomador = '3131158';
		
}


$std->OptanteSimplesNacional = (int)$config['optantesimples'];  		//1 - SIM 2 - Não
$std->IncentivadorCultural = (int)$config['incentivadorcultural']; 	//1 - SIM 2 - Não
$std->Status = 1;  // 1 – Normal  2 – Cancelado

$std->Tomador = new \stdClass();
//$std->Tomador->Cnpj = '00000000000000';
$std->Tomador->Cpf = $rowtomador['cpf'];
$std->Tomador->RazaoSocial = $rowtomador['nometitular'];

$std->Tomador->Endereco = new \stdClass();
$std->Tomador->Endereco->Endereco = $rowtomador['endereco'];
$std->Tomador->Endereco->Numero = (string)$numerotomador;
$std->Tomador->Endereco->Complemento = ' ';
$std->Tomador->Endereco->Bairro = $BairroTomador;
$std->Tomador->Endereco->CodigoMunicipio = (int)$CodMunTomador;
$std->Tomador->Endereco->Uf = $rowtomador['uf'];
$std->Tomador->Endereco->Cep = (int)$rowtomador['cep'];

$std->Servico = new \stdClass();
$std->Servico->ItemListaServico = $config['codservico'];
$std->Servico->CodigoCnae = $config['codigocnae'];
$std->Servico->CodigoTributacaoMunicipio = '522310000';
$std->Servico->Discriminacao = 'Convenio Promedico Saude';
$std->Servico->CodigoMunicipio = (int)$config['codtribmun'];

$ValorIss = 0.00;

$ValorServ = $rowtomador['valor'];
$ValorServicos = (float)$ValorServ;

$ValorLiquido = $rowtomador['valor'];

if($config['retemiss'] == 1){
	
	$ValorIss = intval(strval($rowtomador['valor'] * $config['aliquota'])) / 100;
	$ValorLiquido = number_format($ValorLiquido - $ValorIss, 2, '.', '');
}

$ValorLiquidoNfse = (float)$ValorLiquido;
$ValorIssFinal = (float)$ValorIss;

$std->Servico->Valores = new \stdClass();

$std->Servico->Valores->ValorServicos = $ValorServicos;
$std->Servico->Valores->ValorDeducoes = 0.00;
$std->Servico->Valores->ValorPis = 0.00;
$std->Servico->Valores->ValorCofins = 0.00;
$std->Servico->Valores->ValorInss = 0.00;
$std->Servico->Valores->ValorIr = 0.00;
$std->Servico->Valores->ValorCsll = 0.00;
$std->Servico->Valores->IssRetido = (int)$config['retemiss'];
$std->Servico->Valores->ValorIss = $ValorIssFinal;
$std->Servico->Valores->OutrasRetencoes = 0.00;
$std->Servico->Valores->BaseCalculo = $rowtomador['valor'];
$std->Servico->Valores->Aliquota = (int)$config['aliquota'];
$std->Servico->Valores->ValorLiquidoNfse = $ValorLiquidoNfse;
$std->Servico->Valores->DescontoIncondicionado = 0.00;
$std->Servico->Valores->DescontoCondicionado = 0.00;

/*
$std->IntermediarioServico = new \stdClass();
$std->IntermediarioServico->RazaoSocial = 'INSCRICAO DE TESTE SIATU - D AGUA -PAULINO S'; 
$std->IntermediarioServico->Cnpj = '99999999000191';
$std->IntermediarioServico->InscricaoMunicipal = '8041700010';

$std->ConstrucaoCivil = new \stdClass();
$std->ConstrucaoCivil->CodigoObra = '1234';
$std->ConstrucaoCivil->Art = '1234';
*/

/*
$rps = new Rps($std);
header("Content-type: text/xml");
echo $rps->render();
*/


   $rps = new Rps($std);

   $response = $tools->gerarNfse($rps, $lote);

   echo FakePretty::prettyPrint($response, '');



 
}
   
}

//header("Content-type: text/xml");
//echo $rps->render();


} catch (\Exception $e) {

    echo $e->getMessage();
    
}


/*	
for ($i = 1; $i < 99; $i++) {
	$seq = str_pad($i, 2, '0', STR_PAD_LEFT);
	$arquivogravacao = $dirarquivoremessa.'/'.'RPS'.date('d').date('m').$seq;
	if(!file_exists($arquivogravacao.".XML")) {
		break;
	}
}

file_put_contents($arquivogravacao . '.XML', $response);
*/


$conec->desconecta('MYSQL');



//echo "<pre>";
//print_r(json_encode($std));
//echo "</pre>";



