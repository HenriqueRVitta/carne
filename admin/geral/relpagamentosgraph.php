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
		$separacao  = "Mês/Ano";
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
	$data2 = array();
	
	while($row = mysql_fetch_array($resultado)){

		// Tratamento para quando for Mes/Ano
		if($_POST['separacao'] <> 2) {
			$lcCampo = retira_acentos_UTF8($row['filtro']);
		} else {
			$lcCampo = invertecomp($row['filtro'],1);
		}
		
		$data[] = array($lcCampo,$row['qtde']);
		
		//$data2[] = Array($lcCampo,'[1] => '.$row['qtde']);
		
		$i++;
	}
	
//$plot = new PHPlot(800,600);
$plot = new PHPlot(1280, 768);

$plot->SetImageBorderType('plain');

$plot->SetPlotType('pie');
$plot->SetDataType('text-data-single');
$plot->SetDataValues($data);

# Set enough different colors;
$plot->SetDataColors(array('red', 'green', 'blue', 'yellow', 'cyan',
                        'magenta', 'brown', 'lavender', 'pink',
                        'gray', 'orange'));

$lcCabecalho = $nomehosp."\n"."Gráfico de Recebimentos por ".$separacao."\n"."Período:".$_POST['datainicio']." A ".$_POST['datafim']."";

# Main plot title:
//$plot->SetTitle("World Gold Production, 1990\n(1000s of Troy Ounces)");
$plot->SetTitle($lcCabecalho);

# Build a legend from our data array.
# Each call to SetLegend makes one line as "label: value".
foreach ($data as $row)
  $plot->SetLegend(implode(': ', $row));
# Place the legend in the upper left corner:
$plot->SetLegendPixels(5, 5);

$plot->DrawGraph();

?>
