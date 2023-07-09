<?php

/* Ela faz autoload automático de classes, ou seja, ela realiza o include dinâmico dos arquivos com as classes. */
spl_autoload_register(function($filename) {

	/* A constante DIRECTORY_SEPARATOR armazena o separador de diretórios do sistemas operacional */
	$file = '.' . DIRECTORY_SEPARATOR . $filename . '.php';

	/* Verifica se é diretório é igual => '/' (servidor linux) */
	if(DIRECTORY_SEPARATOR == '/') {
		$file = str_replace('\\', '/', $file);
	}

	/* A função file_exists() verifica se um arquivo ou diretório existe. */
	if(file_exists($file)) {
		require $file;
	} else {
		/* Mostra uma mensagem de (arquivo não existe). */
		echo 'File not exists';
	}
});
