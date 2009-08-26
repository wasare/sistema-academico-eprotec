<?php

session_start();
error_reporting(0);

ini_set('display_errors', '0');

// CONTROLE DE SESSAO DE LOGIN NO MODULO ALUNO
$_SESSION['aluno_login'] = 1;

require_once('../../configs/configuracao.php');

//EFETUA A CONEXÃO
$conn = NewADOConnection('postgres');
$conn->Connect("'$aluno_host'", "'$aluno_user'", "'$aluno_password'", "'$aluno_database'");


date_default_timezone_set('America/Sao_Paulo');


function aluno_br_date($date)
{
  $dia = Substr($date, 8, 2);
  $mes = Substr($date, 5, 2);
  $ano = Substr($date, 0, 4);
  $newdate = $dia . '/' . $mes . '/' . $ano;
  return $newdate;
}


if ( isset($_SESSION['user']) and $_SESSION['user'] != '' and !isset($_POST["btnOK"]) )
{
   $user = $_SESSION['user'];
   $senha = $_SESSION['senha'];
   $nasc = $_SESSION['nasc'];

}
else
{   
   $user = $_POST['user'];
   $senha = md5($_POST['senha']);
   $nasc = $_POST['nasc'];
   
   $_SESSION['user'] = $user;
   $_SESSION['senha'] = $senha;
   $_SESSION['nasc'] = $nasc;

}

$qryUsuarioCont = 'SELECT 
                     COUNT(*)
                  FROM 
                     acesso_aluno a, pessoas b
                  WHERE a.ref_pessoa = '.$user.' AND
                         b.id = '.$user.' AND
                         dt_nascimento = \''.$nasc.'\' AND
                        a.senha = \''.$senha.'\';';

$AlunoCont = $conn->getOne($qryUsuarioCont);

// VERIFICA O ACESSO
if ($AlunoCont != 1) 
{
		
   print '<script language=javascript>              
         window.alert("Usuário e/ou senha inválido(s)"); javascript:window.history.back(1);         </script>';
   exit;

} 
else 
{

// VERIFICA MATRICULA NO PERIODO CORRENTE
   $m = date("m");
   
   if($m > 7)
   {
      $m = '06';
   }
   else
   {
      $m = '01';
   }
   
   $DataInicial = date("01/01/2006");   

   $qryPeriodoCont = 'SELECT 
                           COUNT(*)
                        FROM 
                           periodos d, 
                           matricula e
                     WHERE 
                           e.ref_pessoa = '.$user.' AND
                           d.dt_inicial >= \''.$DataInicial.'\';';
                           
   $qryPeriodoCont = 'SELECT
   DISTINCT COUNT(*)
   FROM matricula a, pessoas b, disciplinas c, periodos d, cursos e
   WHERE
      a.ref_disciplina
            IN (
                  SELECT
                     DISTINCT
                        a.ref_disciplina
                     FROM matricula a, disciplinas b
                     WHERE
                        a.ref_disciplina = b.id AND
                        a.ref_motivo_matricula = 0 AND
                        a.ref_pessoa = %s
             ) AND
      d.dt_final >= \'%s\' AND
      a.ref_curso = e.id AND
      a.ref_periodo = d.id AND
      a.ref_disciplina = c.id AND
      a.ref_pessoa = b.id AND
      a.ref_pessoa = %s;';

   $aluno = $user;
   $data = $DataInicial;

   $MatCont = $conn->getOne(sprintf($qryPeriodoCont,$aluno, $data, $aluno));
   
   if ($MatCont == 0) 
   {
       print '<script language=javascript>              
         window.alert("Matrícula para o período corrente não encontrada!"); javascript:window.history.back(1);         </script>';
      exit;
   }
}

?>
