<?php	
	
	session_start();

	# Gráfico de PIZZA
	
	# PHPlot Example: Pie/text-data-single
	# http://www.phplot.com/phplotdocs/
  # https://sourceforge.net/projects/phplot/
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

    $lcJoin = "";
    if($_POST['plano'] <> '-1') {
        $lcJoin = " Join carne_contratos b on b.idtitular = a.id";
        $pcwhere = "a.situacao = 'ATIVO' and a.datainicio between '".$dtinicial."' and '".$dtfinal."' and b.plano = ".$_POST['plano'];
        $lcPlano = " Plano: ".$_POST['nomePlano'];
    }

    /* Filtrando registros ATIVOS */
    $pcwhere = "situacao = 'ATIVO' and a.datainicio between '".$dtinicial."' and '".$dtfinal."'";
    $pcgroup = " group by month(a.datainicio) order by month(a.datainicio)";

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

   /* Processando os Registros Inativos */
   /*
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
    */

//$lcCabecalho = $nomehosp."\n"."Gráfico de Evolução do Cadastro no Carnê "."\n"."Período:".$_POST['datainicio']." A ".$_POST['datafim']."";
$lcCabecalho = $nomehosp."\n"."NOVOS ASSOCIADOS - Periodo:".$_POST['datainicio']." A ".$_POST['datafim'].$lcPlano;

$plot = new phplot(800,600);
$plot->SetImageBorderType('plain');

$plot->SetPlotType('pie');
$plot->SetDataType('text-data-single');
$plot->SetDataValues($data);

# Set enough different colors;
$plot->SetDataColors(array('red', 'green', 'blue', 'yellow', 'cyan',
                        'magenta', 'brown', 'lavender', 'pink',
                        'gray', 'orange'));

# Main plot title:
$plot->SetTitle($lcCabecalho);

# Build a legend from our data array.
# Each call to SetLegend makes one line as "label: value".
foreach ($data as $row)
  $plot->SetLegend(implode(': ', $row));
# Place the legend in the upper left corner:
$plot->SetLegendPixels(5, 5);

$plot->DrawGraph();


?>
