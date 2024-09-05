ALTER TABLE carne_pagamentos ADD COLUMN valor_desconto DECIMAL(12,2) NOT NULL DEFAULT '0.00' AFTER valor_taxas;

ALTER TABLE carne_titular ADD COLUMN nomevendedor VARCHAR(100) NOT NULL DEFAULT ''

ALTER TABLE carne_pagamentos ADD COLUMN tipopagamento INTEGER NOT NULL DEFAULT '0'

ALTER TABLE carne_dependente ADD COLUMN grupo INTEGER NOT NULL DEFAULT '0'

ALTER TABLE carnepromedico.carne_titular ADD COLUMN emailenviado TEXT NOT NULL COMMENT 'Email enviado em massa'' AFTER situacaocontrato,
 ADD INDEX Index_situacaocontrato USING BTREE(situacaocontrato);

ALTER TABLE usuarios ADD COLUMN email VARCHAR(250) NOT NULL DEFAULT ''

ALTER TABLE carne_titular MODIFY COLUMN email VARCHAR(250) NOT NULL DEFAULT ''

ALTER TABLE carne_titular ADD COLUMN situacaocontrato INTEGER NOT NULL DEFAULT '0'

CREATE TABLE carne_situacaocontrato (
  Id INTEGER NOT NULL AUTO_INCREMENT,
  Descricao VARCHAR(45) NOT NULL,
  Unidade INTEGER NOT NULL DEFAULT '0',
  Inativo TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (Id))

ALTER TABLE carne_tipodependente ADD COLUMN inativo TINYINT(1) NOT NULL DEFAULT '0'

ALTER TABLE carne_localpagto ADD COLUMN inativo TINYINT(1) NOT NULL DEFAULT '0'

ALTER TABLE cadastro_unidades ADD COLUMN email VARCHAR(250) NOT NULL DEFAULT '';

ALTER TABLE carne_grupo ADD COLUMN inativo TINYINT(1) NOT NULL DEFAULT '0' AFTER `descricao`

ALTER TABLE carne_titular ADD INDEX Index_motivocancelado USING BTREE(`idmotivocancelado`);

ALTER TABLE carne_dependente ADD INDEX Index_motivocancelado USING BTREE(`idmotivocancelado`);

ALTER TABLE carne_titular ADD COLUMN idmotivocancelado INTEGER NOT NULL DEFAULT '0' AFTER motivocancelado;

ALTER TABLE carne_dependente ADD COLUMN idmotivocancelado INTEGER NOT NULL DEFAULT '0' AFTER motivocancelado

ALTER TABLE carne_dependente ADD COLUMN `motivocancelado` TEXT AFTER `dtinativo`

CREATE TABLE carne_motivocancelado (
  Id INTEGER NOT NULL AUTO_INCREMENT,
  Descricao VARCHAR(45),
  Inativo TINYINT(1) NOT NULL DEFAULT '0',
  Unidade INTEGER NOT NULL DEFAULT '1',
  PRIMARY KEY  USING BTREE(Id`)
)

ALTER TABLE carne_inadimplentes ADD COLUMN Unidade INTEGER NOT NULL DEFAULT '1' AFTER UltimoContato,
 ADD COLUMN Inclusao VARCHAR(250),
 ADD COLUMN Inativo VARCHAR(250)

CREATE TABLE carne_inadimplentes (
  Id INTEGER NOT NULL AUTO_INCREMENT,
  Cpf VARCHAR(45),
  Protestado VARCHAR(45),
  Nome VARCHAR(100),
  Telefone1 VARCHAR(45),
  Telefone2 VARCHAR(45),
  Adesao DATETIME,
  Atualizacao VARCHAR(45) NOT NULL DEFAULT 'PLANO MORTO',
  BaixaInicial DATETIME,
  BaixaFinal DATETIME,
  UltimoMesCarne VARCHAR(45),
  TotalParcelas INTEGER NOT NULL DEFAULT '0',
  ValorParcela DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  ValorProtesto DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  Observacao TEXT,
  ContratoCidade VARCHAR(45),
  Ligacao VARCHAR(45),
  Atualizado VARCHAR(45) NOT NULL DEFAULT 'OK',
  Assertiva VARCHAR(45),
  UltimoContato TEXT,
  PRIMARY KEY (Id),
  INDEX Index_Cpf USING BTREE(Cpf),
  INDEX Index_Nome USING BTREE(Nome))
  
ALTER TABLE carne_dependente ADD INDEX Index_idtitular USING BTREE(`idtitular`);

ALTER TABLE carne_contratos ADD INDEX Index_IdPlano USING BTREE(`plano`);

ALTER TABLE carne_contratos ADD INDEX Index_IdTitular USING BTREE(`idtitular`);

ALTER TABLE carne_titular ADD COLUMN motivocancelado TEXT DEFAULT NULL AFTER `situacao`;

ALTER TABLE carne_tipopessoa ADD COLUMN Unidade INTEGER NOT NULL DEFAULT '1';

ALTER TABLE carne_titular ADD COLUMN tipopessoa INTEGER NOT NULL DEFAULT '0' AFTER `nometitular`,
 ADD INDEX `Index_tipopessoa` USING BTREE(`tipopessoa`);

ALTER TABLE carne_dependente ADD COLUMN tipopessoa INTEGER NOT NULL AFTER `parentesco`,
 ADD INDEX `Index_tipoPessoa` USING BTREE(`tipopessoa`);

INSERT INTO carne_tipopessoa (Id,Descricao) values (1,'TITULAR/FUNCIONÁRIO'),(2,'RESPONSÁVEL'),(3,'DEPENDENTE'),(61,'CANCELADO')

CREATE TABLE carne_tipopessoa (
  Id INTEGER NOT NULL AUTO_INCREMENT,
  Descricao VARCHAR(45) NOT NULL DEFAULT '',
  Ativo VARCHAR(45) NOT NULL DEFAULT '1',
  PRIMARY KEY (Id))

ALTER TABLE usuarios MODIFY COLUMN nome VARCHAR(250) NOT NULL DEFAULT '';

ALTER TABLE carne_dependente ADD COLUMN cpf VARCHAR(45) NOT NULL DEFAULT ''

ALTER TABLE carne_pagamentos ADD COLUMN convenio VARCHAR(45) NOT NULL DEFAULT '',
 ADD COLUMN vlrunimed DECIMAL(12,2) NOT NULL DEFAULT '0.00',
 ADD COLUMN vlrcontribuicao DECIMAL(12,2) NOT NULL DEFAULT '0.00',
 ADD COLUMN vlrmensal DECIMAL(12,2) NOT NULL DEFAULT '0.00',
 ADD COLUMN apene DECIMAL(12,2) NOT NULL DEFAULT '0.00',
 ADD COLUMN tarifa DECIMAL(12,2) NOT NULL DEFAULT '0.00',
 ADD COLUMN juros DECIMAL(12,2) NOT NULL DEFAULT '0.00',
 ADD COLUMN utilizacao DECIMAL(12,2) NOT NULL DEFAULT '0.00',
 ADD COLUMN outros DECIMAL(12,2) NOT NULL DEFAULT '0.00'
 
CREATE TABLE carne_taxasdependente (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  iddependente INTEGER NOT NULL DEFAULT '0',
  aeromedico DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  comissao DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  coopart DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  taxabanco DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  apene DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  nomeboleto VARCHAR(60) NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  INDEX Index_2 USING BTREE(iddependente))

CREATE TABLE carne_taxastitular (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  idtitular INTEGER NOT NULL DEFAULT '0',
  aeromedico DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  comissao DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  coopart DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  taxabanco DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  apene DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  nomeboleto VARCHAR(60) NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  INDEX Index_2 USING BTREE(idtitular))

ALTER TABLE carne_pagamentos ADD COLUMN retornobanco INTEGER NOT NULL DEFAULT '0'
ALTER TABLE retornobanco ADD COLUMN banco_origem VARCHAR(45) NOT NULL DEFAULT ''

ALTER TABLE config ADD COLUMN codcedente VARCHAR(45) NOT NULL DEFAULT ''
ALTER TABLE configuracao ADD COLUMN codcedente VARCHAR(45) NOT NULL DEFAULT ''
ALTER TABLE carne_bancos ADD COLUMN codcedente VARCHAR(45) NOT NULL DEFAULT ''

ALTER TABLE carne_titular ADD COLUMN somenteresponsavel TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Somente resposanvel pelo pagfto do boleto' 

ALTER TABLE carne_dependente ADD COLUMN cobrarnoboleto TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1-Sim 2-Nao'

ALTER TABLE carne_remessaboleto ADD COLUMN idtitular INTEGER NOT NULL DEFAULT '0' AFTER inclusao,
 ADD COLUMN lote INTEGER NOT NULL DEFAULT '0'
 
CREATE TABLE carne_lote (
  id INTEGER NOT NULL AUTO_INCREMENT,
  data DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00',
  banco VARCHAR(45) NOT NULL,
  usuario INTEGER NOT NULL DEFAULT '0',
  unidade INTEGER NOT NULL DEFAULT '0',
  arquivo VARCHAR(120) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`))

ALTER TABLE carne_titular ADD COLUMN cartaoemitidopor VARCHAR(45) NOT NULL DEFAULT '' COMMENT 'usuario que emitiu cartao contribuinte'

ALTER TABLE carne_titular ADD COLUMN cartaoemitido DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00' COMMENT 'data em que foi emitido cartao do contribuinte'

ALTER TABLE carne_pagamentos ADD INDEX Index_4 USING BTREE(idcliente)

ALTER TABLE configuracao ADD COLUMN localpagto INTEGER NOT NULL DEFAULT '0' COMMENT 'codigo do local de pagto'

CREATE TABLE carne_loterps (
  id INTEGER NOT NULL AUTO_INCREMENT,
  dtemissao DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00',
  PRIMARY KEY (id))


ALTER TABLE config ADD COLUMN tipoambiente INTEGER NOT NULL DEFAULT '2'

ALTER TABLE config ADD COLUMN cnpj VARCHAR(45) NOT NULL DEFAULT '' ,
 ADD COLUMN im VARCHAR(45) NOT NULL DEFAULT '',
 ADD COLUMN tiporps INTEGER NOT NULL DEFAULT '1',
 ADD COLUMN naturezaoperacao INTEGER NOT NULL DEFAULT '1',
 ADD COLUMN regimetributa INTEGER NOT NULL DEFAULT '1',
 ADD COLUMN optantesimples INTEGER NOT NULL DEFAULT '1',
 ADD COLUMN incentivadorcultural INTEGER NOT NULL DEFAULT '1',
 ADD COLUMN codservico VARCHAR(45) NOT NULL DEFAULT '',
 ADD COLUMN codigocnae VARCHAR(45) NOT NULL DEFAULT '',
 ADD COLUMN codtribmun VARCHAR(45) NOT NULL DEFAULT '',
 ADD COLUMN codmunicipiotrib VARCHAR(45) NOT NULL DEFAULT '',
 ADD COLUMN retemiss INTEGER NOT NULL DEFAULT '1',
 ADD COLUMN aliquota INTEGER NOT NULL DEFAULT '0'


ALTER TABLE config ADD COLUMN percentualfiltro DECIMAL(12,2) NOT NULL DEFAULT '0.00'

ALTER TABLE retornobanco ADD COLUMN nfse TINYINT(1) NOT NULL DEFAULT '0',
 ADD COLUMN notafiscal VARCHAR(45) NOT NULL DEFAULT '',
 ADD COLUMN codverificacao VARCHAR(45) NOT NULL DEFAULT '',
 ADD COLUMN lote INTEGER NOT NULL DEFAULT '0',
 ADD COLUMN protocolonfse VARCHAR(200) NOT NULL DEFAULT ''
 
ALTER TABLE config ADD COLUMN nfseprefeitura VARCHAR(45) NOT NULL DEFAULT ''

ALTER TABLE carne_carnesgerados ADD INDEX Index_IdTitular USING BTREE(idtitular)

CREATE TABLE carne_carnesgerados (
  id INTEGER NOT NULL AUTO_INCREMENT,
  idtitular INTEGER NOT NULL DEFAULT '0',
  datainicio DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00',
  datafim DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00',
  usuario INTEGER NOT NULL DEFAULT '0',
  datagerou DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00',
  valor DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (id)
)

ALTER TABLE carne_pagamentos ADD COLUMN docfinanceiro VARCHAR(45) NOT NULL DEFAULT '' COMMENT 'documento do Financeiro quando exportado pelo carne'

ALTER TABLE carne_tipoplano ADD COLUMN travainternacao TINYINT(1) NOT NULL DEFAULT '0',
 ADD COLUMN travacirurgia TINYINT(1) NOT NULL DEFAULT '0'

ALTER TABLE carne_tipoplano ADD COLUMN carenciainternacao INTEGER NOT NULL DEFAULT '0',
 ADD COLUMN carenciacirurgia INTEGER NOT NULL DEFAULT '0'

*-*

ALTER TABLE retornobanco ADD INDEX Index_2 USING BTREE(datapagto)

ALTER TABLE carne_titular ADD INDEX Index_4 USING BTREE(`cpf`)

ALTER TABLE carne_titular ADD INDEX  Index_3 USING BTREE(`nrocarne`)

ALTER TABLE carne_bancos ADD COLUMN idretornobanco TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'Identificacao do Titular no Arquivo Retorno'

ALTER TABLE config ADD COLUMN idretornobanco TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'Identificacao do Titular no Arquivo Retorno'

ALTER TABLE config ADD COLUMN mesesematraso TINYINT(3) NOT NULL DEFAULT '3'

ALTER TABLE carne_titular ADD COLUMN valorplano DECIMAL(12,2) NOT NULL DEFAULT '0.00' COMMENT 'valor do plano quando plano e negociado'

ALTER TABLE carne_competenciaplano ADD COLUMN vlrfixonegociado TINYINT(1) NOT NULL DEFAULT '1'

ALTER TABLE config ADD COLUMN msgcarnemodelo4 TEXT

ALTER TABLE config ADD COLUMN dtnascdepobriga TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'Data de nascimento do dependente obrigatorio'

ALTER TABLE carne_titular ADD COLUMN ultimomescarne VARCHAR(6) NOT NULL DEFAULT ''

ALTER TABLE config ADD COLUMN dirarquivoretorno VARCHAR(250) NOT NULL DEFAULT ''

ALTER TABLE config ADD COLUMN dirarquivoremessa VARCHAR(250) NOT NULL DEFAULT '',
 ADD COLUMN carteiracobranca VARCHAR(45) NOT NULL DEFAULT ''

ALTER TABLE carne_bancos ADD COLUMN dirarquivoremessa VARCHAR(250) NOT NULL DEFAULT '',
 ADD COLUMN carteiracobranca VARCHAR(45) NOT NULL DEFAULT ''
 
CREATE TABLE carne_remessabanco (
  id INTEGER NOT NULL AUTO_INCREMENT,
  data DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00',
  unidade INTEGER NOT NULL DEFAULT '1',
  usuario INTEGER NOT NULL DEFAULT '0',
  arquivo VARCHAR(250) NOT NULL DEFAULT '',
  PRIMARY KEY (id))


CREATE TABLE carne_bancos (
id INTEGER NOT NULL AUTO_INCREMENT,
nome VARCHAR(45) NOT NULL DEFAULT '',
bancoemissor VARCHAR(45) NOT NULL DEFAULT '',
nroagencia VARCHAR(45) NOT NULL DEFAULT '' ,
digitoagencia VARCHAR(1) NOT NULL DEFAULT '',
nroconta VARCHAR(45) NOT NULL DEFAULT '' ,
digitoconta VARCHAR(1) NOT NULL DEFAULT '',
nrocontrato VARCHAR(45) NOT NULL DEFAULT '',
infocliente1 VARCHAR(40) NOT NULL DEFAULT '' ,
infocliente2 VARCHAR(40) NOT NULL DEFAULT '' ,
infocliente3 VARCHAR(40) NOT NULL DEFAULT '' ,
instrucaocaixa1 VARCHAR(40) NOT NULL DEFAULT '' ,
instrucaocaixa2 VARCHAR(40) NOT NULL DEFAULT '' ,
instrucaocaixa3 VARCHAR(40) NOT NULL DEFAULT '',
dirarquivoretorno VARCHAR(250) NOT NULL DEFAULT '',
PRIMARY KEY (id));


ALTER TABLE usuarios ADD COLUMN perfilcarne INTEGER NOT NULL DEFAULT '0' COMMENT 'perfil do usuario do carne'

ALTER TABLE usuarios ADD COLUMN vendedor INTEGER NOT NULL DEFAULT '0' COMMENT 'codigo do vendedor do carne'

ALTER TABLE carne_titular ADD COLUMN vendedor INTEGER NOT NULL DEFAULT '0'

ALTER TABLE carne_vendedor ADD COLUMN unidade INTEGER NOT NULL DEFAULT '1'

CREATE TABLE carne_vendedor (
  id INTEGER NOT NULL AUTO_INCREMENT,
  nome VARCHAR(45) NOT NULL DEFAULT '',
  email VARCHAR(200) NOT NULL DEFAULT '',
  celular VARCHAR(45) NOT NULL DEFAULT '',
  ativo TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id))


ALTER TABLE carne_titular ADD COLUMN dtinativo DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00'

ALTER TABLE carne_titular ADD COLUMN obs TEXT NOT NULL


ALTER TABLE carne_dependente ADD COLUMN situacao VARCHAR(45) NOT NULL DEFAULT 'ATIVO' ,
 ADD COLUMN dtinativo DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00',
 ADD COLUMN obs TEXT NOT NULL
 
 
ALTER TABLE carne_dependente ADD COLUMN nrocarteira VARCHAR(45) NOT NULL DEFAULT ''
ALTER TABLE carne_titular ADD COLUMN nrocarteira VARCHAR(45) NOT NULL DEFAULT ''


ALTER TABLE `carne_pagamentos` ADD COLUMN `idtaxas` INTEGER NOT NULL DEFAULT '0' AFTER `cod_importacao`,
 ADD COLUMN `valor_taxas` DECIMAL(12,2) NOT NULL DEFAULT '0.00' AFTER `idtaxas`;


CREATE TABLE carne_taxas (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  descricao VARCHAR(45) NOT NULL DEFAULT '',
  valor DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  status TINYINT(1) NOT NULL DEFAULT '0',
  inativo DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00',
  unidade INTEGER NOT NULL DEFAULT '1',
  registro DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00',
  PRIMARY KEY (id))

ALTER TABLE `carne_titular` ADD COLUMN `cpfcnpj` INTEGER NOT NULL DEFAULT '1' AFTER `plano`;


ALTER TABLE `carne_competenciaplano` ADD COLUMN `valor_dependente` DECIMAL(12,2) NOT NULL DEFAULT '0.00' AFTER `registro`;


ALTER TABLE config ADD COLUMN dirarquivoretorno VARCHAR(250) NOT NULL DEFAULT ''


ALTER TABLE config ADD COLUMN bancoemissor VARCHAR(45) NOT NULL DEFAULT '',
 ADD COLUMN nroagencia VARCHAR(45) NOT NULL DEFAULT '' ,
 ADD COLUMN digitoagencia VARCHAR(1) NOT NULL DEFAULT '',
 ADD COLUMN nroconta VARCHAR(45) NOT NULL DEFAULT '' ,
 ADD COLUMN digitoconta VARCHAR(1) NOT NULL DEFAULT '',
 ADD COLUMN nrocontrato VARCHAR(45) NOT NULL DEFAULT '',
 ADD COLUMN infocliente1 VARCHAR(40) NOT NULL DEFAULT '' ,
 ADD COLUMN infocliente2 VARCHAR(40) NOT NULL DEFAULT '' ,
 ADD COLUMN infocliente3 VARCHAR(40) NOT NULL DEFAULT '' ,
 ADD COLUMN instrucaocaixa1 VARCHAR(40) NOT NULL DEFAULT '' ,
 ADD COLUMN instrucaocaixa2 VARCHAR(40) NOT NULL DEFAULT '' ,
 ADD COLUMN instrucaocaixa3 VARCHAR(40) NOT NULL DEFAULT '' 

 
 CREATE TABLE carne_remessaboleto (
  idremessa INTEGER NOT NULL AUTO_INCREMENT,
  nosso_numero VARCHAR(45) NOT NULL DEFAULT '',
  numero_documento VARCHAR(45) NOT NULL DEFAULT '',
  data_vencimento DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00',
  data_processamento DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00',
  valor_boleto DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  sacado VARCHAR(45) NOT NULL DEFAULT '',
  convenio VARCHAR(45) NOT NULL DEFAULT '',
  codigo_barras VARCHAR(250) NOT NULL DEFAULT '',
  linha_digitavel VARCHAR(250) NOT NULL DEFAULT '',
  codigo_banco_com_dv VARCHAR(20) NOT NULL DEFAULT '',
  inclusao DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00',
  PRIMARY KEY (idremessa))
AUTO_INCREMENT = 24073
COMMENT = 'Remessa boleto bancario do carne'
 

#Tabelas do Carne
carne_bancos carne_competenciaplano carne_contratos carne_dependente carne_grupo carne_localpagto carne_pagamentos carne_parcelamento carne_remessabanco carne_remessaboleto carne_taxas carne_tipodependente carne_tipoplano carne_titular carne_vendedor config styles temas
