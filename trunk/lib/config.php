<?php
  
  @session_start();

  //setlocale (LC_ALL, 'pt_BR');
  date_default_timezone_set('America/Sao_Paulo');
  
  error_reporting(E_ALL & ~E_NOTICE);
  $versao = '0.'. @file_get_contents ('VERSAO.TXT'); 

  $inicio       = "inicio.php";      //Módulo inicial do SAGU
  $SAGU_HOST 	= "dev.cefetbambui.edu.br/desenvolvimento/sistema_academico";  //Host

  $BASE_URL = 'https://dev.cefetbambui.edu.br/desenvolvimento/sistema_academico/';
  $BASE_DIR = '/var/www/dev.cefetbambui.edu.br/desenvolvimento/sistema_academico/'; 

  //Dados da Instituição
  $IEendereco = "";
  $IEbairro   = "";
  $IEcidade   = "Bambu&iacute;";
  $IEUF       = "MG";
  $IECE	      = "";
  $IEfone     = "";
  $IEfax      = "";
  $IEemail    = "webmaster@cefetbambui.edu.br";
  $IEnome     = "CEFET-BAMBU&Iacute;";
  
  
  
  $frequencia_creditos = '16';
  
  $g_campus_id   	= 0;
  $g_campus_nome 	= "";
  $g_pessoas_list 	= 25;
  $g_cidades_list 	= 25;
  $g_paises_list 	= 25;
  $g_estados_list 	= 25;
  $g_limite_vest 	= 110;
  $g_limite_periodo 	= 10;
  $limite_list 		= 25;
  $mail_time_desenv 	= "webmaster@cefetbambui.edu.br";

  $estados_civis["S"] = "Solteiro";
  $estados_civis["C"] = "Casado";
  $estados_civis["V"] = "Viúvo";
  $estados_civis["D"] = "Desquitado";
  $estados_civis["U"] = "União Estável";
  $estados_civis["E"] = "Solteiro Emancipado";
  
  $turnos["M"] = "Manhã";
  $turnos["T"] = "Tarde";
  $turnos["N"] = "Noite";
  $turnos["V"] = "Vespertino";

  $sexos["F"] = "Feminino";
  $sexos["M"] = "Masculino";
  $sexos["O"] = "Outros";

  $tipos_pessoa["F"] = "Física";
  $tipos_pessoa["J"] = "Juridica";

  $reg_nasc_casam["N"] = "Nascimento";
  $reg_nasc_casam["C"] = "Casamento";

  $opcoes["t"] = "Sim";
  $opcoes["f"] = "Não";
  
  $status["1"] = "Sim";
  $status["0"] = "Não";

  $salas["1"] = "Sim";
  $salas["0"] = "Não";

  $historico["S"] = "Sim";
  $historico["N"] = "Não";
  
  $fontes["P"] = "Padrão";
  $fontes["N"] = "Negrito";
  $fontes["I"] = "Itálico";

  $status_aluno["G"] = "Graduacao";
  $status_aluno["C"] = "Calouros";
  $status_aluno["O"] = "Outros";

  $status_disciplina["0"] = "Cursada";
  $status_disciplina["1"] = "Liberada";
  $status_disciplina["2"] = "Bloqueada";
  $status_disciplina[""] = "Fora do Currículo";

  $status_matricula["1"] = "Reprovado";
  $status_matricula["2"] = "Desistente";
  $status_matricula["3"] = "Aprovado";
  $status_matricula["4"] = "Dispensado";

  $requisitos["P"] = "Pré-Requisito";
  $requisitos["C"] = "Co-Requisito";

  $grupos["access"] = "Acesso";
  $grupos["admin"] = "Administração";

  $curriculos["M"] = "Mínimo";
  $curriculos["C"] = "Complementar";
  $curriculos["O"] = "Optativa";
  $curriculos["P"] = "Proficiência";
  $curriculos["A"] = "Atividade Complementar";

  $sql_periodos_ensino_medio = "select '----- Selecione o Periodo -----','' union all select id||' / '||substr(descricao, 0, 30) as d,id from periodos where tipo = 2";
  
  $sql_periodos_credito = "select '----- Selecione o Periodo -----','' union all select id||' / '||substr(descricao, 0, 30) as d,id from periodos";
  
  $sql_periodos_academico = "select '----- Selecione o Periodo -----','' union all select id||' / '||substr(descricao, 0, 25) as d,id from periodos";
  
  $sql_periodos_extensao = "select '----- Selecione o Periodo -----','' union all select id||' / '||substr(descricao, 0, 25) as d,id from periodos";
  
  $sql_periodos_academico = "select '----- Selecione o Periodo -----','' union all select id||' / '||substr(descricao, 0, 25) as d,id from periodos";
 
  $sql_periodos_academico = "
  SELECT
  'Selecione o Periodo',
  '' union all select id||' / '||substr(descricao, 0, 25) as d,
  id
  FROM
  periodos
  ORDER BY 1 DESC;";
 
?>
