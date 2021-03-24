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

	$dirarquivoremessa = "../../../../admin/loterps";
		
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
	
	$GLOBALS['contentLoteRps'][] = '';
	$GLOBALS['filedownload'] = '';
	
	global $GLOBALS;
	
	$contentLoteRps = '';
	
	$inicio = $_POST['inicio'];
	$fim= $_POST['fim'];
	$arr = $_POST['selecionado'];

	$DataEmissao = date('Y-m-d').'T'.date('H:i:s');

	$IdCliente = '';

	foreach ($arr as &$value) {
    
			$IdCliente.=trim($value).',';

	}

	$IdCliente = '('.substr($IdCliente,0,strlen($IdCliente)-1).')';

$querytitular = "SELECT a.idretorno, sum(a.valor) as valor, b.* ".
" FROM retornobanco a Join carne_titular b on b.id = a.idcliente ".
" where idretorno in".$IdCliente." group by a.cpfcnpj order by a.datapagto,b.nometitular limit 50";
$resultadoTitular = mysqli_query($conec->con,$querytitular) or die('ERRO NA QUERY !'.$querytitular);
	

$ContadorLote = 1;

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
    $password = '1234';
        
    $cert = Certificate::readPfx($content, $password);
    $soap = new SoapFake();
    $soap->disableCertValidation(false);
    
    $tools = new Tools($configJson, $cert);
    $tools->loadSoapClass($soap);

    $arps = [];
    $argsinfo = [];
    
    
        
$std = new \stdClass();
$std->version = '1.00'; //indica qual JsonSchema USAR na validação
$std->IdentificacaoRps = new \stdClass();
$std->IdentificacaoRps->Numero = $ContadorLote; //(int)$rowtomador['idretorno']; // 11; //limite 15 digitos
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

$CodMunTomador = $config['codtribmun']; //'3131307'; // Ipatinga
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

$ValorIss = 0.00;
$aliquota = 0.00;

if($config['aliquota'] > 0) {
	$ValorIss = intval(strval($rowtomador['valor'] * $config['aliquota'])) / 100;
	$ValorIss = strval($rowtomador['valor'] * $config['aliquota']) / 100;
	$ValorIss = round(strval($rowtomador['valor'] * $config['aliquota']) / 100,2);
	
	$aliquota = ($config['aliquota'] / 100);
	$aliquota = number_format($aliquota, 2, '.', '');
}
	
if($config['retemiss'] == 1){
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
$std->Servico->Valores->Aliquota = (float)$aliquota ;
$std->Servico->Valores->ValorLiquidoNfse = $ValorLiquidoNfse;
$std->Servico->Valores->DescontoIncondicionado = 0.00;
$std->Servico->Valores->DescontoCondicionado = 0.00;

    $arps[] = new Rps($std);
    $GLOBALS['contentLoteRps'][] = $arps;
    
    $ContadorLote++;
    
    // Comentado para que gere um lote com varios registros
    //$response = $tools->recepcionarLoteRps($arps, $lote);
    //echo FakePretty::prettyPrint($response, '');
    
    // $ContadorLote; //(int)$rowtomador['idretorno']
    
	// Gravando na tabela retornobanco dados do RPS gerado
	$queryloteRps = "Update retornobanco set notafiscal = 'RPS ".$ContadorLote."', lote = ".$lote." where idretorno = ".$rowtomador['idretorno']."";
	$resultadoLoteRps = mysqli_query($conec->con,$queryloteRps) or die('ERRO NA QUERY !'.$queryloteRps);
    
                
}
   
}

	$arps_ = $GLOBALS['contentLoteRps'];

    $response = $tools->recepcionarLoteRps($arps_, $lote);

    echo "<br>";
    echo "<a href='".$GLOBALS['filedownload']."' download>Baixar o arquivo RPS gerado.</a>";
    
    echo FakePretty::prettyPrint($response, '');


    
	    
           
} catch (\Exception $e) {
    echo $e->getMessage();
}