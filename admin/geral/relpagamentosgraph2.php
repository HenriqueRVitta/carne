<?php	
	
	session_start();

	# Gráfico de PIZZA
	
	# PHPlot Example: Pie/text-data-single
	# http://www.phplot.com/phplotdocs/
	# Mais informações no site -> PHPlot Examples
	include ("../../includes/phplot/phplot.php");

	include ("../../includes/classes/headers.class.php");
	include ("../../includes/classes/conecta.class.php");
	include ("../../includes/classes/auth.class.php");
	include ("../../includes/classes/dateOpers.class.php");
 	include ("../../includes/config.inc.php");

 	include ("../../includes/languages/".LANGUAGE.""); //TEMPORARIAMENTE
 	include ("../../includes/menu/menu.php");
	include ("../../includes/functions/funcoes.inc");
		
	$conec = new conexao;
	$conec->conecta('MYSQL');
	
	
	$lnCompet = substr($_POST['mesano'],3,4).substr($_POST['mesano'],0,2);
	$dtinicial = Fdate($_POST['datainicio']);
	$dtfinal = Fdate($_POST['datafim']);
	$titular = $_POST['titular'];
	$plano = $_POST['plano'];
	$localpagto = $_POST['localpagto'];
	$cidade		= $_POST['cidade'];
	$pcwhere	= "";
	$lcString	= "";
	
	$nordem = $_POST['separacao'];
	$separacao = "";
	
	switch ( $nordem ){
	  case 1:
		$pcordem	= " order by c.cidade";
		$pcgroup	= " group by c.cidade";
		$lcColumn	= " c.cidade";
		$separacao  = "Cidade";
		break;
	  case 2:
		$pcordem	= " order by k.mesano";
		$pcgroup	= " group by k.mesano";
		$lcColumn	= " k.mesano";
		$separacao  = "Mes/Ano";
		break;
	  case 3:
		$pcordem	= " order by u.nome";
		$pcgroup	= " group by u.nome";
		$lcColumn	= " u.nome";
		$separacao  = "Usuários";
		break;
	  case 4:
		$pcordem	= " order by l.descricao";
		$pcgroup	= " group by l.descricao";
		$lcColumn	= " l.descricao";
		$separacao  = "Local de Pagto";
		break;
	  default:
		$pcordem	= " order by c.cidade";
		$pcgroup	= " group by c.cidade";
		$lcColumn	= " c.cidade";
		$separacao  = "Cidade";
		
	}
		

    $query = "select nome_hosp from configuracao limit 1";
    $resultado = mysql_query($query) or die('ERRO NA QUERY !'.$query);
	$rowConfg = mysql_fetch_array($resultado);
	$nomehosp = $rowConfg['nome_hosp'];
	
       $query = "SELECT".$lcColumn." as filtro, count(*) as qtde, sum(vlrpago) as total FROM carne_titular c
		Join carne_contratos p on p.idtitular = c.id
		Join carne_tipoplano q on q.id = p.plano
		Join carne_competenciaplano d on d.idplano = p.plano
		Join carne_pagamentos k on k.idcliente = c.id
		Join carne_localpagto l on l.id = k.localpagto
		left Join usuarios u on u.codigo = k.usuario
		Where k.databaixa between '".$dtinicial."' and '".$dtfinal."' ".$pcgroup." ".$pcordem;
               
      //print_r($query);
      //break;
      
    $resultado = mysql_query($query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$lntotalpg = 0.00;
	$lnqtde = 0.00;

	$data = array();
	
	while($row = mysql_fetch_array($resultado)){

		// Tratamento para quando for Mes/Ano
		if($_POST['separacao'] <> 2) {
			$lcCampo = retira_acentos_UTF8($row['filtro']);
		} else {
			$lcCampo = invertecomp($row['filtro'],1);
		}
		
		$data[] = array($lcCampo,$row['qtde']);
				
		$i++;
	}

$lcCabecalho = $nomehosp."\n"."Gráfico de Recebimentos por ".$separacao."\n"."Período:".$_POST['datainicio']." A ".$_POST['datafim']."";

//$plot = new PHPlot(1024, 768);
$plot = new PHPlot(1280, 768);
$plot->SetImageBorderType('plain');
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataValues($data);
$plot->SetTitle($lcCabecalho);

# Turn off X tick labels and ticks because they don't apply here:
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');

# Make sure Y=0 is displayed:
$plot->SetPlotAreaWorld(NULL, 0);
# Y Tick marks are off, but Y Tick Increment also controls the Y grid lines:
$plot->SetYTickIncrement(100);

# Turn on Y data labels:
$plot->SetYDataLabelPos('plotin');

# With Y data labels, we don't need Y ticks or their labels, so turn them off.
$plot->SetYTickLabelPos('none');
$plot->SetYTickPos('none');

# Format the Y Data Labels as numbers with 1 decimal place.
# Note that this automatically calls SetYLabelType('data').
$plot->SetPrecisionY(1);

$plot->DrawGraph();

?>
