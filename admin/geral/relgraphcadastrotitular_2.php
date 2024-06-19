<?php	
	
	session_start();

	# Gr�fico de PIZZA
	
	# PHPlot Example: Pie/text-data-single
	# http://www.phplot.com/phplotdocs/
  # https://sourceforge.net/projects/phplot/
	# Mais informa��es no site -> PHPlot Examples
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
	
	
	$dtinicial = Fdate($_POST['datainicio']);
	$dtfinal = Fdate($_POST['datafim']);
	$plano = $_POST['plano'];
	$pcwhere	= "";
	$lcString	= "";
	
	$separacao = "";
	

    $query = "select nome_hosp from configuracao limit 1";
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$rowConfg = mysqli_fetch_array($resultado);
	$nomehosp = $rowConfg['nome_hosp'];
	$lcPlano = "";

    /* Filtrando registros ATIVO*/
    $pcwhere = "a.situacao = 'ATIVO' and a.datainicio between '".$dtinicial."' and '".$dtfinal."'";
    $pcgroup = " group by month(a.datainicio) order by month(a.datainicio)";
    $lcJoin = "";
    if($_POST['plano'] <> '-1') {
        $lcJoin = " Join carne_contratos b on b.idtitular = a.id";
        $pcwhere = "a.situacao = 'ATIVO' and a.datainicio between '".$dtinicial."' and '".$dtfinal."' and b.plano = ".$_POST['plano'];
        $lcPlano = " Plano: ".$_POST['nomePlano'];
    }
    $query = "SELECT count(*) as qtde, month(a.datainicio) as mes FROM carne_titular a $lcJoin
    where ".$pcwhere." ".$pcgroup;
     
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$lntotalpg = 0.00;
	$lnqtde = 0.00;

	$data = array();
	
	while($row = mysqli_fetch_array($resultado)){

        $lcCampo = 'JAN';

        switch ($row['mes']) {
            case '1':
                $lcCampo = 'JAN';
              break;
            case '2':
                $lcCampo = 'FEV';
              break;
            case '3':
                $lcCampo = 'MAR';
              break;
              case '4':
                $lcCampo = 'ABR';
              break;
              case '5':
                $lcCampo = 'MAI';
              break;
              case '6':
                $lcCampo = 'JUN';
              break;
              case '7':
                $lcCampo = 'JUL';
              break;
              case '8':
                $lcCampo = 'AGO';
              break;
              case '9':
                $lcCampo = 'SET';
              break;
              case '10':
                $lcCampo = 'OUT';
              break;
              case '11':
                $lcCampo = 'NOV';
              break;
              case '12':
                $lcCampo = 'DEZ';
              break;
              default:
              $lcCampo = 'JAN';
          } 

		$data[] = array($lcCampo,$row['qtde']);
				
		$i++;
	}

   /* Processando os Inativos */
   $pcwhere = "situacao = 'INATIVO' and dtinativo between '".$dtinicial."' and '".$dtfinal."'";
   $pcgroup = " group by month(dtinativo) order by month(dtinativo)";

    $query = "SELECT count(*) as qtde, month(dtinativo) as mes FROM carne_titular
    where ".$pcwhere." ".$pcgroup;
     
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$lntotalpg = 0.00;
	$lnqtde = 0.00;

	$data_2 = array();
	
	while($row = mysqli_fetch_array($resultado)){

        $lcCampo = 'JAN';

        switch ($row['mes']) {
            case '1':
                $lcCampo = 'JAN';
              break;
            case '2':
                $lcCampo = 'FEV';
              break;
            case '3':
                $lcCampo = 'MAR';
              break;
              case '4':
                $lcCampo = 'ABR';
              break;
              case '5':
                $lcCampo = 'MAI';
              break;
              case '6':
                $lcCampo = 'JUN';
              break;
              case '7':
                $lcCampo = 'JUL';
              break;
              case '8':
                $lcCampo = 'AGO';
              break;
              case '9':
                $lcCampo = 'SET';
              break;
              case '10':
                $lcCampo = 'OUT';
              break;
              case '11':
                $lcCampo = 'NOV';
              break;
              case '12':
                $lcCampo = 'DEZ';
              break;
              default:
              $lcCampo = 'JAN';
          } 

		$data_2[] = array($lcCampo,$row['qtde']);
				
		$i++;
	}


//$lcCabecalho = $nomehosp."\n"."Gráfico de Evolução do Cadastro no Carnê "."\n"."Período:".$_POST['datainicio']." A ".$_POST['datafim']."";
$lcCabecalho = $nomehosp."\n"."Periodo:".$_POST['datainicio']." A ".$_POST['datafim'].$lcPlano;

$plot = new PHPlot(800,600);
$plot->SetImageBorderType('plain');

# Disable auto-output:
$plot->SetPrintImage(0);

# There is only one title: it is outside both plot areas.
$plot->SetTitle($lcCabecalho);

# Set up area for first plot:
$plot->SetPlotAreaPixels(80, 40, 740, 340);

# Do the first plot:
$plot->SetDataType('text-data');
$plot->SetDataValues($data);
$plot->SetPlotAreaWorld(NULL, 0, NULL, 100);
$plot->SetDataColors(array('blue'));
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');
$plot->SetYTickIncrement(10);
$plot->SetYTitle("NOVOS ASSOCIADOS\n 100 Mensal");

$plot->SetPlotType('bars');
$plot->DrawGraph();

# Set up area for second plot:
$plot->SetPlotAreaPixels(80, 400, 740, 550);

# Do the second plot:
$plot->SetDataType('text-data');
$plot->SetDataValues($data_2);
$plot->SetPlotAreaWorld(NULL, 0, NULL, 100);
$plot->SetDataColors(array('red'));
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');
$plot->SetYTickIncrement(10);
$plot->SetYTitle("INATIVOS NO PERIODO\n 100 Mensal");

$plot->SetPlotType('bars');
$plot->DrawGraph();

# Output the image now:
$plot->PrintImage();



?>
