<?php

    /*
	 * Dica: Sempre mantenha os arquivos de download em uma mesma pasta, separada dos arquivos do site.
	 * Neste script usaremos a pasta download para esta função.
	 */
    $lcFileDownload = $_GET['file'];

	$arquivo = $lcFileDownload; // Nome do Arquivo
	$local = __DIR__.'/remessas/out/'; // Pasta que contém os arquivos para download
	$local_arquivo = $local.$arquivo; // Concatena o diretório com o nome do arquivo
	/*
	 * Por segurança, o script verifica se o usuário esta tentato sair da pasta especificada para 
	 * os arquivos de download (stripos($arquivo, './') !== false || stripos($arquivo, '../') !== false),
	 * isso irá bloquear a tentativa de forçar download de arquivos não permitidos.
	 * Na mesma função verificamos se o arquivo existe (!file_exists($arquivo)).
	 */

     if(stripos($arquivo, './') !== false || stripos($arquivo, '../') !== false || !file_exists($local_arquivo))
    {
    	echo '<p><h3>Erro ao tentar baixar o Arquivo de Remmesa Banco.</h3></p><br><br>';
    }
    else
    {
        
	    header('Cache-control: private');
	    header('Content-Type: application/octet-stream');
	    header('Content-Length: '.filesize($local_arquivo));
	    header('Content-Disposition: filename='.$arquivo);
	    header("Content-Disposition: attachment; filename=".basename($local_arquivo));
	    // Envia o arquivo Download
		readfile($local_arquivo);
        exit;

    }
?>
