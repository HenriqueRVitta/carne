<?php
      
/*
 * 	Gerador de arquivos (Doc => rtf)
 *  Se voçê quer criar um criar um documento do word com 
 *  paramêtros dinâmicos e só usar esse gerador rtf, a vantagem de 
 *  se usar rtf e que funciona em todos os sistemas operacionais.
 */

header( "Content-type: application/msword" );
header( "Content-Disposition: inline, filename=ARQ_00001.rtf");
$caminho_arquivo = "D:/WebServer/carne/admin/geral/arquivos/ARQ_00001.rtf";
$oqueler = fopen($caminho_arquivo, "r");
$conteudo_arquivo = fread($oqueler, filesize($caminho_arquivo));
fclose($oqueler);
echo "O arquivo contém:<BR>$conteudo_arquivo";

break;

$TipoArquivo = "modelo.rtf";
$TipoArquivo = trim($TipoArquivo);
$arqnome    = "modelo/$TipoArquivo";
print "Arqnome – $arqnome<br>";

// — le arquivo
$arq = fopen($arqnome,"rb");
$mReg = fread($arq,1000000);

fclose($arq);
// — dados
$CodigoArquivo = "00001";
$NumeroTeste = "1000";
$Nome = "FULANO DE TAL DA SILVA";
$Endereco = "Rua 100 na asa sul";
$mReg = str_replace("xxxNumeroTestexxx",$NumeroTeste,$mReg);
$mReg = str_replace("xxxNomexxx",$Nome,$mReg);
$mReg = str_replace("xxxEnderecoxxx",$Endereco,$mReg);

// — grava arquivo
print "CodigoArquivo – $CodigoArquivo<br>";
$NomeArquivoDoc = "arquivos/ARQ_".$CodigoArquivo.".rtf";
print "NomeArquivoDoc – $NomeArquivoDoc<br>";
print "NumeroTeste – $NumeroTeste<br>";
$arqsai = fopen($NomeArquivoDoc,"w");
$mPulo = chr(13).chr(10);
fwrite ($arqsai, $mReg, strlen($mReg));
fclose ($arqsai);


//header('Content-type: application/msword'); 
//header('Content-Disposition: inline, filename=arquivos/ARQ_00001.rtf');
//echo $mReg;

?>