<?php
session_start();


	include "./config.php";
	include DIR_TEMPLATE."header.php";
	
	
	/* Função para realizar o carregamento automatico das classes*/
	spl_autoload_register(function($classe){
		include DIR_HOME."classes/".$classe.".php";
	}); 

	if(isset($_GET['pagina'])) {
		$pagina = $_GET['pagina'];
		if($pagina=="alunos"||$pagina=="professores"||$pagina=="turmas"){
			include DIR_HOME. '/pages/' .$pagina.".php";
		}else{
			include DIR_HOME."erro404.php";
		}
	} else {
		print "<h1>Selecione uma pagina</h1>
		<a href='?pagina=professores'>Professores</a>
		<a href='?pagina=alunos'>Alunos</a>
		<a href='?pagina=turmas'>Turmas</a>
		";
	}
	
	include DIR_TEMPLATE."footer.php";
