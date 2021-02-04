<?php

// arquivo: config.inc.php

// configurar de acordo com as suas necessidades

// Configura��es vitais


//Usuario do banco
define ( "SQL_USER", "root"); // Usu�rio do Banco de Dados 

//Senha do banco
define ( "SQL_PASSWD", "mcjroot");// Senha do Banco de Dados

//Servidor do banco
define ( "SQL_SERVER", "192.168.108.250"); // 192.168.108.250 IP ou nome Servidor



//Nome do banco
//define ( "SQL_DB", "1433_tres_coracoes"); 
//define ( "SQL_DB", "1772_promedico"); 
 define ( "SQL_DB", "carnepromedico");
//define ( "SQL_DB", "1084_itaguara");
//define ( "SQL_DB", "1489_saude_harmonia");
//define ( "SQL_DB", "1805_dominus");

/*
define ( "SQL_USER", "carnemcj"); // Usu�rio do Banco de Dados 
define ( "SQL_PASSWD", "mcjuser@2017");// Senha do Banco de Dados
define ( "SQL_SERVER", "carnemcj.mysql.dbaas.com.br"); // 192.168.108.250 IP ou nome Servidor
define ( "SQL_DB", "carnemcj"); // Banco de dados
*/

// 1433_tres_coracoes
// 1489_saude_harmonia

define ( "TB_CCUSTO","CCUSTO"); 	 //Tabela de CEntro de custos dentro da base de dados - padrÃ£o: CCUSTO
define ( "CCUSTO_ID","codigo");		 //Chave primÃ¡ria da tabela de centros de custo
define ( "CCUSTO_DESC","descricao"); //Campo referente Ã  descriÃ§Ã£o do Centro de Custo
define ( "CCUSTO_COD","codccusto");  //Campo referente ao cÃ³digo de Centro de Custo


define ("INST_TERCEIRA", "-1"); //Define que UNIDADES  nÃ£o devem aparecer na estatÃ­stica geral da tela de abertura - o cÃ³digo deve ser extraido da tabela: INSTITUICOES. 
									//Por padrÃ£o todas as unidades aparecem na estatÃ­stica inicial.

define ( "LOG_PATH", "../../logs/logs.txt"); //Esse diretÃ³rio deve ter permissÃ£o de escrita pra gravar os logs.
define ( "LOGO_PATH", "../../includes/logos");
define ( "ICONS_PATH", "../../includes/icons/");

define ( "HELP_ICON", "".ICONS_PATH."solucoes2.png");
define ( "HELP_PATH", "../../includes/help/");

define ( "LANGUAGE", "pt_BR.php");
//define ( "LANGUAGE", "en.php");


//Define o tipo de autenticaÃ§Ã£o do sistema , por padrÃ£o a altenticaÃ§Ã£o Ã© feita na tabela de usuÃ¡rios do prÃ³prio MCJ, porÃ©m isso pode ser feito atravÃ©s de um serviÃ§o LDAP.
//INICIALMENTE Ã‰ NECESSÃ�RIO DEIXAR A AUTENTICAÃ‡ÃƒO LOCAL PARA QUE SEJA POSSÃ�VEL ACESSAR O SISTEMA PELA PRIMEIRA VEZ E CRIAR OS USUÃ�RIOS.
define ( "AUTH_TYPE" , "SYSTEM"); //DEFAULT
#define ( "AUTH_TYPE", "LDAP"); // ALTERNATIVE

// VariÃ¡veis para conexÃ£o LDAP   DEVEM SER CONFIGURADAS EM CASO DE AUTH_TYPE==LDAP   DE ACORDO COM  AS CONFIGURAÃ‡Ã•ES DO SEU SERVIDOR LDAP
define ( "LDAP_HOST", "localhost"); //IP do servidor LDAP
define ( "LDAP_DOMAIN", "ou=People,dc=yourdomain,dc=edu,dc=br"); 
define ( "LDAP_DOMAIN_SEC", "ou=People,dc=yourdomain1,dc=yourdomain2,dc=edu,dc=br"); //Segundo domÃ­nio LDAP
define ( "LDAP_DN", "cn=admin,dc=yourdomain,dc=edu,dc=br");
define ( "LDAP_PASSWORD", "");	

//define('WP_MEMORY_LIMIT', '64M');
//ini_set('max_execution_time',120);
ini_set('memory_limit', '120M');


		//CARGA HORÃ�RIA DE CADA AREA DE ATENDIMENTO - OS PARÃ‚METROS SÃƒO: HORA DE INICIO, HORA DE FIM...
		//... FINAL DO INTERVALO, E CARGA DE TRABALHO NO SÃ�BADO!!	
        $H_default = array (8,22,13,4);  //AQUI A JORNADA Ã‰: DAS 8:00 Ã€S 22:00 , HORA FINAL DE INTERVALO: 13:00 E SÃ�BADO SÃƒO TRABALHADAS 4 HORAS

		$H_horarios = array (1=>$H_default);//PARA CADA Ã�REA DE ATENDIMENTO QUE TIVER HORÃ�RIO DE ATENDIMENTO DIVERENTE DO DEFAULT...
											//...DEVE SER CRIADA UMA ENTRADA NO ARRAY H_horarios
											/*EX: 
											
												$H_novaArea = array (10,18,13,0); AQUI O HORÃ�RIO DE INÃ�CIO Ã‰ 10:00 E O FINAL DE EXPEDIENTE Ã‰ 18:00 
																SEM TRABALHO NO SÃ�BADO (vida boa..)  :)
												
												$H_horarios = array (1=>$H_default, 2=>$H_novaArea);
											*/

?>
