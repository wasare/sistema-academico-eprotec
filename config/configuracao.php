<?php

/**
 * Forca o fuso horario da aplicacao
 */
date_default_timezone_set('America/Sao_Paulo');

/**
 * Banco de dados
 */
$host     = '192.168.0.234';
$database = 'sa';
$user = 'usrsagu'; 
$password = 'x6S8YzrJBs';

//$user = trim($_POST['uid']); 
//$password = trim($_POST['pwd']);

/**
 * Variaveis de acesso a dados - SA 
 */
$param_conn['host']     = $host;
$param_conn['database'] = $database;
$param_conn['user']     = $user;
$param_conn['password'] = $password;
$param_conn['port']     = $port;
//print_r($param_conn);

/**
 * Variaveis de acesso a dados - Web Diario
 */
$webdiario_host		= $param_conn['host'];
$webdiario_database = $param_conn['database'];
$webdiario_user		= 'usrsagu';
$webdiario_password = 'x6S8YzrJBs';
$webdiario_port		= $param_conn['port'];


/**
 * Variaveis de acesso a dados - Modulo do aluno
 */
$aluno_host     = $param_conn['host'];
$aluno_database = $param_conn['database'];
$aluno_user     = 'aluno';
$aluno_password = '@1srv27';
$aluno_port     = '';


/**
 * Variaveis do sistema
 */
$BASE_URL       = 'https://'. $_SERVER['SERVER_NAME'] .'/desenvolvimento/wanderson/dev/sa/';
$BASE_DIR       = '/var/www/dev.cefetbambui.edu.br/desenvolvimento/wanderson/dev/sa/';
$LOGIN_URL      = $BASE_URL .'index.php';
$LOGIN_LOG_FILE = $BASE_DIR .'app/sagu/logs/login.log';
$PATH_IMAGES    = $BASE_URL."public/images/";
$REVISAO 		= @file_get_contents ('../VERSAO.TXT');
$SESS_TABLE     = 'sessao';



/*
   ALGUNS PARAMETROS DO SISTEMA ACADEMICO
   ** acima de cada parametro os respectivos arquivos onde sao utilizados **
*/

// app/diagrama.php
// public/help.php
$IEnome     = 'IFMG - Campus Bambu&iacute;';

// app/index.php
// public/help.php
$IEurl      = 'http://www.ifmg.edu.br/bambui';

// app/sagu/academico/cursos_disciplinas_edita.phtm
$curriculos["M"] = "M&iacute;nimo";
$curriculos["C"] = "Complementar";
$curriculos["O"] = "Optativa";
$curriculos["P"] = "Profici&ecirc;ncia";
$curriculos["A"] = "Atividade complementar";

// app/sagu/academico/cursos_disciplinas_edita.phtm
$historico["S"] = "Sim";
$historico["N"] = "N&atilde;o";

// app/sagu/academico/curso_altera.phtml 
// app/sagu/academico/lista_disciplinas_ofer.phtml
$status["1"] = "Sim";
$status["0"] = "N&atilde;o";

// app/sagu/academico/pessoaf_edita.phtml
// app/sagu/academico/documentos_edita.phtml
// app/sagu/academico/post/confirm_pessoaf_inclui.phtml
$opcoes["t"] = "Sim";
$opcoes["f"] = "N&atilde;o";

// app/sagu/academico/pessoaf_edita.phtml 
// app/sagu/academico/post/confirm_pessoaf_inclui.phtml 
$estados_civis["S"] = "Solteiro";
$estados_civis["C"] = "Casado";
$estados_civis["V"] = "Vi&uacute;vo";
$estados_civis["D"] = "Desquitado";
$estados_civis["U"] = "Uni&atilde;o est&aacute;vel";
$estados_civis["E"] = "Solteiro emancipado";

// app/sagu/generico/post/lista_areas_ensino.php3
// app/sagu/generico/post/lista_cidades.php3
// app/sagu/generico/post/lista_escolas.php3
// app/sagu/generico/post/lista_professores.php3
// app/sagu/generico/post/lista_pessoas.php3
// app/sagu/generico/post/lista_sql.php3
// app/sagu/academico/consulta_disciplinas_equivalentes.phtml
$limite_list        = 25;


// app/sagu/academico/periodos_altera.phtml
// app/sagu/academico/novo_contrato.phtml
// app/sagu/academico/periodos.phtml
// app/sagu/academico/alterar_contrato.phtml
// app/sagu/academico/atualiza_disciplina_ofer.phtml
// app/sagu/academico/disciplina_ofer.phtml
$sql_periodos_academico    = "
  SELECT
    'Selecione o Periodo',
    '' union all select id||' / '||substr(descricao, 0, 25) as d,
    id
  FROM
    periodos
  ORDER BY 1 DESC;";

?>
