<?php 
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
  
require_once('../webdiario.conf.php');

require_once("pslib.php"); 

//require_once("pslib.class");
function GetRowValues($res,$row)
{
    return pg_fetch_row($res,$row);
}

function GetValue($col)
{
    return pg_result($res,$row,$col-1);
}


$var = explode(":",$_POST[getdisciplina]);
$getdisciplina = $var[0];
$getofer = $var[1];

$id_disc = $var[1];
$data_em = date("d/m/Y");
$dia = -1;
$turno = 0;
$ref_periodo = $_POST[getperiodo];
$curso_id = 1;
$campus_id = 'undefined';

/*
$var = explode(":",$_POST[getdisciplina]);
$getdisciplina = $var[0];
$getofer = $var[1];

$id_disc = 573;
$data_em = '06/04/2006';
$dia = -1;
$turno = 0;
$ref_periodo = 501;
$curso_id = 1;
$campus_id = 'undefined';
*/


?>
<html>
<head>
    <title>Cadernos de Chamada</title>
</head>
<body  bgcolor="#FFFFFF">
<script Language="Javascript">
var NOVAWIN = window.open("/aguarde.html", "NOVAWIN", "status=no,toolbar=no,location=no,menu=no,scrollbars=no,width=260,height=105,left=280,top=235");
</script>

<?php

   // CheckFormParameters(array('ref_periodo','data_em','id_disc','dia','turno'));
   
 //  echo("<font face=\"Verdana, Arial, Helvetica, sans-serif\">Caderno de Chamada <BR> </font>");

   $nr_pag = 1;
   $data = $data_em ;         //Data sugerida pelo usuário
   
   //======================== DECLARA NOME DO ARQUIVO PS DESTINO
   if ($id_disc != 0)
   {
      $nome_arq_ps = "ps/caderno_chamada_" . "$id_disc" . "_" . "$ref_periodo" . ".ps";
   }
   else
   {
      $nome_arq_ps = "ps/caderno_chamada_" . "$dia" . "_" . "$turno" . "_" . "$campus_id" . "_" . "$ref_periodo" . ".ps";
   }
   
   $myfile_ps = fopen($nome_arq_ps,"w");

   //========================= ABRE ARQUIVO PS DESTINO
   PS_open($myfile_ps, "SAGU", $nome_arq_ps, 'Landscape');

   //========================= AJUSTA O USO DE ACENTOS
   PS_set_acent($myfile_ps);

   //========================= INICIA A PRIMEIRA PAGINA
   PS_begin_page($myfile_ps, $nr_pag);
   
   if($id_disc != 0)
   {
    $sql =  " SELECT distinct " .
    	    "	   A.ref_disciplina, " .
            "      C.descricao_extenso, " .
            "      A.ref_curso, " .
            "      curso_desc(A.ref_curso), " .
            "      get_departamento(A.ref_disciplina), " .
            "      B.ref_professor_aux, " .
            "      pessoa_nome(B.ref_professor_aux), " .
            "      B.dia_semana, " .
            "      get_dia_semana(B.dia_semana), " .
            "      C.num_creditos, " .
            "      C.carga_horaria, " .
            "      A.creditos_aprov, " .
            "      A.carga_horaria_aprov, " .
            "      B.num_creditos_desconto, " .
            "      A.ref_periodo, " .
            "      B.num_sala, " .
            "      A.ref_disciplina_ofer, " .
            "      A.ref_pessoa, " .
            "      pessoa_nome(A.ref_pessoa), " .
            "      A.ref_disciplina_subst, " .
            "      descricao_disciplina(A.ref_disciplina_subst), " .
            "      get_creditos(A.ref_disciplina_subst), " .
            "      get_carga_horaria(A.ref_disciplina_subst), " .
            "      get_campus(A.ref_campus), " .
            "      A.ref_campus, " .
            "      is_ouvinte(A.ref_pessoa, A.ref_curso), " .
            "      B.turno, " .
            "      get_turno(B.turno), " .
            "      A.turma, " .
            "      B.dia_semana_aux, " .
            "      get_dia_semana(B.dia_semana_aux), " .
            "      B.turno_aux, " .
            "      get_turno(B.turno_aux), " .
            "      B.num_sala_aux, " .
            "      get_complemento_ofer(A.ref_disciplina_ofer), " .
            "      A.dt_cancelamento, " .
            "      get_tipo_curso(A.ref_curso) " .
            " FROM matricula A, disciplinas_ofer_compl B, disciplinas C " .
            " WHERE A.ref_disciplina_ofer = '$id_disc' and " .
    	    "       B.dia_semana = '$dia' and " .
            "       B.turno = '$turno' and " .
            "       A.obs_aproveitamento = '' and " .  // Aproveitamentos não entram no caderno
            "       A.ref_disciplina_ofer = B.ref_disciplina_ofer and " .
            "       A.ref_disciplina = C.id and " .
	    "       ( (A.dt_cancelamento is null) or (A.dt_cancelamento >= get_dt_inicio_aula(A.ref_periodo)) ) " .
            " ORDER BY A.creditos_aprov, " .
    	    "          A.carga_horaria_aprov, " .
    	    "          A.turma, " .
            "          A.ref_disciplina, " .
            "          descricao_disciplina(A.ref_disciplina_subst), " .
    	    "          is_ouvinte(A.ref_pessoa, A.ref_curso), " .
    	    "          pessoa_nome(A.ref_pessoa) ";
   } 
   else 
   {
    $sql =  " SELECT distinct " .
            " 	   A.ref_disciplina, " .
            "      C.descricao_extenso, " .
            "      A.ref_curso, " .
            "      curso_desc(A.ref_curso), " .
            "      get_departamento(A.ref_disciplina), " .
            "      B.ref_professor_aux, " .
            "      pessoa_nome(B.ref_professor_aux), " .
            "      B.dia_semana, " .
            "      get_dia_semana(B.dia_semana), " .
            "      C.num_creditos, " .
            "      C.carga_horaria, " .
            "      A.creditos_aprov, " .
            "      A.carga_horaria_aprov, " .
            "      B.num_creditos_desconto, " .
            "      A.ref_periodo, " .
            "      B.num_sala, " .
            "      A.ref_disciplina_ofer, " .
            "      A.ref_pessoa, " .
            "      pessoa_nome(A.ref_pessoa), " .
            "      A.ref_disciplina_subst, " .
            "      descricao_disciplina(A.ref_disciplina_subst), " .
            "      get_creditos(A.ref_disciplina_subst), " .
            "      get_carga_horaria(A.ref_disciplina_subst), " .
            "      get_campus(A.ref_campus), " .
            "      A.ref_campus, " .
            "      is_ouvinte(A.ref_pessoa, A.ref_curso), " .
            "      B.turno, " .
    	    "      get_turno(B.turno), " .
    	    "      A.turma, " .
            "      B.dia_semana_aux, " .
            "      get_dia_semana(B.dia_semana_aux), " .
            "      B.turno_aux, " .
            "      get_turno(B.turno_aux), " .
            "      B.num_sala_aux, " .
            "      get_complemento_ofer(A.ref_disciplina_ofer), " .
            "      A.dt_cancelamento, " .
            "      get_tipo_curso(A.ref_curso) " .
            " FROM matricula A, disciplinas_ofer_compl B, disciplinas C, disciplinas_ofer D " .
            " WHERE A.ref_periodo = '$ref_periodo' and " .
            "       B.dia_semana = '$dia' and " .
            "       B.turno = '$turno' and " .
            "       A.obs_aproveitamento = '' and ";  // Aproveitamentos não entram no caderno
            
            if ($campus_id)
            {
                $sql .= " D.ref_campus = '$campus_id' and ";
            }
            
            if ($curso_id)
            {
                $sql .= " D.ref_curso = '$curso_id' and ";
            }

            $sql .= "       A.ref_disciplina_ofer = B.ref_disciplina_ofer and " .
            "       A.ref_disciplina = C.id and " .
            "       A.ref_disciplina_ofer = D.id and " .
            "       B.ref_disciplina_ofer = D.id and " .
            "       A.ref_periodo = D.ref_periodo and " .
    	    "       ( (A.dt_cancelamento is null) or (A.dt_cancelamento >= get_dt_inicio_aula(A.ref_periodo)) ) " .
            " ORDER BY A.ref_disciplina_ofer, " .
    	    "          A.creditos_aprov, " .
    	    "          A.carga_horaria_aprov, " .
    	    "          A.turma, " .
            "          A.ref_disciplina, " .
            "          descricao_disciplina(A.ref_disciplina_subst), " .
    	    "          is_ouvinte(A.ref_pessoa, A.ref_curso), " .
    	    "          pessoa_nome(A.ref_pessoa); ";
    }

function cabecalho($myfile_ps, $data, $ref_disciplina, $disciplina, $ref_curso, $curso, $campus, $texto, $dia_semana, $dia_semana_desc, $departamento, $creditos, $hora_aula, $creditos_desconto, $hora_aula_desconto, $ref_professor, $nome_professor, $periodo, $sala, $fl_ouvinte, $turno, $turno_desc, $ref_disciplina_ofer, $descricao_disciplina_subst, &$quebra_pagina, $complemento_disc)
{

 PS_line($myfile_ps, 45, -15, 814, -15, 2);
 PS_show_xy_font($myfile_ps, 'Lista de Chamada', 45, -30, 'Arial-Bold', 12);
 
 if($fl_ouvinte)
 {
    PS_show_xy_font($myfile_ps, "ALUNO OUVINTE", 370, -30, 'Arial-Bold', 10);
 }

 PS_show_xy_font($myfile_ps, "Emissão: $data", 580, -30, 'Arial', 8);
 PS_show_xy_font($myfile_ps, "Disciplina:", 45, -42, 'Arial-Bold', 10);

 $nome_disciplina = $ref_disciplina . ' - ' . $disciplina;

 if($descricao_disciplina_subst != '')
 {
    $nome_disciplina = $nome_disciplina  . ' (' . $descricao_disciplina_subst . ')';
 }

 if($complemento_disc != '')
 {
    $nome_disciplina = $nome_disciplina . ' (' . $complemento_disc . ')';
 }

 PS_show_xy_font($myfile_ps, "$nome_disciplina", 98, -42, 'Arial', 10);

// echo '$nome_disciplina:'.$nome_disciplina.'</br>';

 $lin = -54;

 PS_show_xy_font($myfile_ps, "Centro:", 45, "$lin", 'Arial-Bold', 10);
 PS_show_xy_font($myfile_ps, "$departamento", 85, "$lin", 'Arial', 10);

 // echo '$departamento:'.$departamento.'</br>';

 
 PS_show_xy_font($myfile_ps, "Unidade:", 330, "$lin", 'Arial-Bold', 10);
 PS_show_xy_font($myfile_ps, "$campus", 375, "$lin", 'Arial', 10);

 // echo '$campus:'.$campus.'</br>';
 
 PS_show_xy_font($myfile_ps, "Período:", 480, "$lin", 'Arial-Bold', 10);
 PS_show_xy_font($myfile_ps, "$periodo", 522, "$lin", 'Arial', 10);

 // echo '$periodo:'.$periodo.'</br>'; 
 
 PS_show_xy_font($myfile_ps, "Sala:", 580, "$lin", 'Arial-Bold', 10);
 PS_show_xy_font($myfile_ps, "____", 606, "$lin", 'Arial', 10);

 // OK  echo '$sala:'.$sala.'</br>';

 $lin = $lin - 12;
 
 PS_show_xy_font($myfile_ps, "Dia:", 45, "$lin", 'Arial-Bold', 10);
 PS_show_xy_font($myfile_ps, "$dia_semana_desc", 70, "$lin", 'Arial', 10);

 // echo '$dia_semana_desc:'.$dia_semana_desc.'</br>';
 
 PS_show_xy_font($myfile_ps, "Turno:", 330, "$lin", 'Arial-Bold', 10);
 PS_show_xy_font($myfile_ps, "$turno_desc", 365, "$lin", 'Arial', 10);

// echo '$turno_desc:'.$turno_desc.'</br>';
 
 PS_show_xy_font($myfile_ps, "H/A Total:", 480, "$lin", 'Arial-Bold', 10);
 $hora_aula = substr($hora_aula, 0, strpos($hora_aula, ".")+3);
 
 PS_show_xy_font($myfile_ps, "$hora_aula", 530, "$lin", 'Arial', 10);

 // echo '$hora_aula:'.$hora_aula.'</br>';

 PS_show_xy_font($myfile_ps, "H/A Previstas: ", 580, "$lin", 'Arial-Bold', 10);
 $hora_aula_desconto = substr($hora_aula_desconto, 0, strpos($hora_aula_desconto, ".")+3);
 PS_show_xy_font($myfile_ps, "$hora_aula_desconto", 650, "$lin", 'Arial', 10);

 $lin = $lin - 12;

 $frequencia_minima = (($hora_aula * 75) / 100);
 PS_show_xy_font($myfile_ps, "* Freqüência Mínima para aprovação: $frequencia_minima H/A", 370, "$lin", 'Arial', 8);

 $lin_aux = $lin - 12;
 
 PS_show_xy_font($myfile_ps, "$ref_disciplina_ofer", 780, "$lin_aux", 'Arial', 10);
 
 PS_show_xy_font($myfile_ps, "Professor:", 45, "$lin", 'Arial-Bold', 10);


 if ($ref_professor == '')
 {
    //$conn2 = new Connection;
    //$conn2->Open();

    $sql = " select B.ref_professor, " .
           "        pessoa_nome(B.ref_professor) " .
           " from disciplinas_ofer_compl A, disciplinas_ofer_prof B " .
           " where A.ref_disciplina_ofer = B.ref_disciplina_ofer and " .
    	   "       A.id = B.ref_disciplina_compl and " .
    	   "       A.ref_disciplina_ofer = '$ref_disciplina_ofer' and " .
      	   "       A.dia_semana = '$dia_semana' and " .
      	   "       A.turno = '$turno'";

    $query1 = pg_exec($dbconnect, $sql);
    
    $totalLinhas = pg_numrows($query1);
    
   /* while( $query2->MoveNext() )     
    {*/
   for($row = 0; $row < $totalLinhas ;  $row++)
   {
     list ($ref_professor,
           $nome_professor) = GetRowValues($query1,$row);

   	PS_show_xy_font($myfile_ps, "$ref_professor - $nome_professor", 99, "$lin", 'Arial', 10);
   	$lin = $lin - 12;
	$quebra_pagina = $quebra_pagina - 1;
    }
 
   // $query2->Close();
    //@$conn2->Close();
   pg_close($dbconnect);
    $lin = $lin - 5;
    
 }
 else
 {
    PS_show_xy_font($myfile_ps, "$ref_professor - $nome_professor", 99, "$lin", 'Arial', 10);
    $lin = $lin - 17;
    $quebra_pagina = $quebra_pagina - 1;
 }
 
PS_line($myfile_ps, 45, "$lin", 814, "$lin", 2);

return $lin;

}


function titulo_tab ($myfile_ps, $lin_ini, $lin_fin, $col_ini, $col_fin, $col_ini, $col_txt, $lin_txt, $k)
{

$lin_fin = $lin_ini - 38;
$col_ini = 45;
$col_fin = $col_ini + 15;

PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);

$col_ini = $col_fin;
$col_fin = $col_ini + 35;
$col_txt = $col_ini + 3;
$lin_txt = $lin_fin + 10;

PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
PS_show_xy_font($myfile_ps, 'Cód', $col_txt, $lin_txt, 'Arial-Bold', 10);

$col_ini = $col_fin;
$col_fin = $col_ini + 225;
$col_txt = $col_ini + 5;
$lin_txt = $lin_fin + 10;

PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
PS_show_xy_font($myfile_ps, 'Nome', $col_txt, $lin_txt, 'Arial-Bold', 10);

$col_ini = $col_fin;
$col_fin = $col_ini + 20;
$col_txt = $col_ini + 2;
$lin_txt = $lin_fin + 10;

PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
PS_show_xy_font($myfile_ps, 'Cur', $col_txt, $lin_txt, 'Arial-Bold', 10);

$col_ini = $col_fin;
$col_fin = $col_ini + 15;
$col_txt = $col_ini + 1;
$lin_txt = $lin_fin + 30;

PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
PS_show_xy_font($myfile_ps, 'Aula', $col_txt, $lin_txt, 'Arial', 6);

$col_txt = $col_ini + 1;
$lin_txt = $lin_fin + 15;

PS_show_xy($myfile_ps, 'Mês', $col_txt, $lin_txt);

$col_txt = $col_ini + 1;
$lin_txt = $lin_fin + 4;

PS_show_xy($myfile_ps, 'Dia', $col_txt, $lin_txt);

$lin_fin = $lin_ini - 10;
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
$col_txt = $col_ini + 4;
$lin_txt = $lin_fin + 2;

$ind = 1;

    for ($k=1;$k < 21;$k++)
    {
    	PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
    	PS_show_xy_font($myfile_ps, $ind, $col_txt, $lin_txt, 'Arial', 7);

    	$col_ini = $col_fin;
       	$col_fin = $col_ini + 15;
    	$col_txt = $col_ini + 4;

    	++ $ind;
    }

$lin_fin = $lin_ini - 38;
$lin_txt = $lin_fin + 10;
$col_ini = $col_fin - 15;
$col_fin = $col_ini + 26;
$col_txt = $col_ini + 7;

PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
PS_show_xy_font($myfile_ps, 'Fr', $col_txt, $lin_txt, 'Arial-Bold', 10);

$col_ini = $col_fin;
$col_fin = $col_ini + 26;
$col_txt = $col_ini + 7;

PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
PS_show_xy_font($myfile_ps, 'N1', $col_txt, $lin_txt, 'Arial-Bold', 10);

$col_ini = $col_fin;
$col_fin = $col_ini + 26;
$col_txt = $col_ini + 7;

PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
PS_show_xy_font($myfile_ps, 'N2', $col_txt, $lin_txt, 'Arial-Bold', 10);

$col_ini = $col_fin;
$col_fin = $col_ini + 26;
$col_txt = $col_ini + 7;

PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
PS_show_xy_font($myfile_ps, 'Md', $col_txt, $lin_txt, 'Arial-Bold', 10);

$col_ini = $col_fin;
$col_fin = $col_ini + 26;
$col_txt = $col_ini + 4;

PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
PS_show_xy_font($myfile_ps, 'Ex', $col_txt, $lin_txt, 'Arial-Bold', 10);

$col_ini = $col_fin;
$col_fin = $col_ini + 26;
$col_txt = $col_ini + 7;

PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
PS_show_xy_font($myfile_ps, 'Nf', $col_txt, $lin_txt, 'Arial-Bold', 10);

$col_ini = $col_fin;
$col_fin = $col_ini + 163;
$col_txt = 705;

$lin_ini = $lin_ini - 10;
$lin_fin = $lin_ini - 14;
$col_ini = 355;
$col_fin = $col_ini + 15;

    for ($k=1;$k < 21;$k++)
    {
    PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
    $col_ini = $col_fin;
    $col_fin = $col_ini + 15;
    }

$lin_ini = $lin_ini - 14;
$lin_fin = $lin_ini - 14;
$col_ini = 355;
$col_fin = $col_ini + 15;

    for ($k=1;$k < 21;$k++)
    {
    	PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
    	$col_ini = $col_fin;
    	$col_fin = $col_ini + 15;
    }
    
return $lin_ini;

}

function rodape ($myfile_ps, $lin_fin, $lin, $campus)
{

$lin = $lin_fin - 7;

PS_line($myfile_ps, 45, $lin, 814, $lin, 2);

$lin = $lin - 15;

PS_show_xy_font($myfile_ps, 'Registro de Presença:', 45, $lin, 'Arial-Bold', 8);
PS_show_xy_font($myfile_ps, '-    Ex.: 2 H/A |-/|    4 H/A |=|', 132, $lin, 'Arial', 8);
PS_show_xy_font($myfile_ps, 'Códigos:', 350, $lin, 'Arial', 8);
PS_show_xy_font($myfile_ps, 'Fr', 390, $lin, 'Arial-Bold', 8);
PS_show_xy_font($myfile_ps, '- Freqüência', 400, $lin, 'Arial', 8);
PS_show_xy($myfile_ps, 'OBS.:', 512, $lin);

$lin = $lin - 14;

PS_show_xy_font($myfile_ps, 'Registro de Ausência:', 45, $lin, 'Arial-Bold', 8);
PS_show_xy_font($myfile_ps, '/    Ex.: 4 H/A |X|', 132, $lin, 'Arial', 8);
PS_show_xy_font($myfile_ps, 'N', 390, $lin, 'Arial-Bold', 8);
PS_show_xy_font($myfile_ps, ' - Notas das avaliações (1 e 2)', 400, $lin, 'Arial', 8);

$lin = $lin - 14;

PS_show_xy_font($myfile_ps, '* :', 45, $lin, 'Arial-Bold', 8);
PS_show_xy_font($myfile_ps, 'Trancamento conforme processo encaminhado via Protocolo.', 55, $lin, 'Arial', 8);
PS_show_xy_font($myfile_ps, 'Md', 390, $lin, 'Arial-Bold', 8);
PS_show_xy_font($myfile_ps, ' - Média => (N1+N2)/2', 400, $lin, 'Arial', 8);
$lin = $lin - 14;

PS_show_xy_font($myfile_ps, 'Somente a Secretaria Geral está autorizada a incluir alunos na folha de chamada.', 45, $lin, 'Arial', 8);
PS_show_xy_font($myfile_ps, 'Ex', 390, $lin, 'Arial-Bold', 8);
PS_show_xy_font($myfile_ps, ' - Nota do Exame', 400, $lin, 'Arial', 8);

$lin = $lin - 14;

PS_show_xy($myfile_ps, 'Horas/Aula Dadas: ___________', 45, $lin);
PS_show_xy($myfile_ps, 'Professor(a): _____________________________', 175, $lin);
PS_show_xy_font($myfile_ps, 'Nf ', 390, $lin, 'Arial-Bold', 8);
PS_show_xy_font($myfile_ps, ' - Nota Final => (Md+Ex)/2', 400, $lin, 'Arial', 8);

$lin = $lin - 7;

PS_line($myfile_ps, 45, $lin, 814, $lin, 2);
}

// END Functions

// Begin Program Principal

$quebra_pagina = 25;
//$conn = new Connection;
//$conn->Open();
//$query = $conn->CreateQuery($sql);

//echo $sql;
//exit;

$query = pg_exec($dbconnect, $sql);

/*
echo $sql;
exit;
*/
//SaguAssert($query,"Não foi possível executar a consulta SQL!");

$num = 1 ;
$count = 1;
$totalLinhas = pg_numrows($query);
/*
echo $totalLinhas;
exit;
*/

if($totalLinhas < 1)
{
      echo '<script language=javascript>                   window.alert("ERRO! Não existem alunos matriculados nesta disciplina!"); javascript:window.history.back(1);               </script>';
      exit;
}


//print_r($query);
/*
while($row =  pg_fetch_all($query)) 
{
      //echo $row['id'];
      //echo $row['author'];
     //echo $row['email'];
   print_r($row);
} */
for($row = 0; $row < $totalLinhas ;  $row++)
{

/*   
   $ref_disciplina = $row[0];
   $disciplina = $row[1];
   $ref_curso = $row[2];
   $curso = $row[3];
   $departamento = $row[4];
   $ref_professor_aux = $row[5];
   $nome_professor_aux = $row[6];
   $dia_semana = $row[7];
   $dia_semana_desc = $row[8];
   $creditos = $row[9];
   $hora_aula = $row[10];
   $creditos_aprov = $row[11];
   $hora_aula_aprov = $row[12];
   $creditos_desconto = $row[13];
   $periodo = $row[14];
   $sala = $row[15];
   $ref_disciplina_ofer = $row[16];
   $ref_pessoa = $row[17];
   $nome = $row[18];
   $ref_disciplina_subst = $row[19];
   $descricao_disciplina_subst = $row[20];
   $creditos_subst = $row[21];
   $hora_aula_subst = $row[22];
   $campus = $row[23];
   $ref_campus = $row[24];
   $fl_ouvinte = $row[25];
   $turno = $row[26];
   $turno_desc = $row[27];
   $turma = $row[28];
   $dia_semana_aux = $row[29];
   $dia_semana_aux_desc = $row[30];
   $turno_aux = $row[31];
   $turno_desc_aux = $row[32];
   $num_sala_aux = $row[33];
   $complemento_disc = $row[34];
   $dt_cancelamento = $row[35];
   $ref_tipo_curso = $row[36];
 */
         list($ref_disciplina,
        $disciplina,
        $ref_curso,
        $curso,
        $departamento,
        $ref_professor_aux,
        $nome_professor_aux,
        $dia_semana,
        $dia_semana_desc,
        $creditos,
        $hora_aula,
        $creditos_aprov,
        $hora_aula_aprov,
        $creditos_desconto,
        $periodo,
        $sala,
        $ref_disciplina_ofer,
        $ref_pessoa,
        $nome,
        $ref_disciplina_subst,
        $descricao_disciplina_subst,
        $creditos_subst,
        $hora_aula_subst,
        $campus,
        $ref_campus,
        $fl_ouvinte,
        $turno,
        $turno_desc,
    	$turma,
    	$dia_semana_aux,
    	$dia_semana_aux_desc,
    	$turno_aux,
    	$turno_desc_aux,
    	$num_sala_aux,
        $complemento_disc,
        $dt_cancelamento,
        $ref_tipo_curso) = GetRowValues($query,$row); 

$aux_ref_professor_aux = $ref_professor_aux;
$hora_aula_desconto = 0;

// O número de créditos e a carga horária utilizados 
// serão o que o aluno realmente estará em sala de aula
if (($ref_disciplina_subst != 0) && ($ref_disciplina_subst != '') && ($creditos != $creditos_subst))
{
   $creditos  = $creditos_subst;
   $hora_aula = $hora_aula_subst;
}

if (($creditos_desconto != 0) && ($creditos_desconto != ''))
{
   $creditos_desconto  = sprintf("%.2f", $creditos_desconto);
   
   // Os cursos técnicos quebram a regra pois tem Carga Horária igual a Número de Créditos
   if ($ref_tipo_curso != '7')
   {
       $hora_aula_desconto = ($creditos_desconto * 15);
   }
   $hora_aula_desconto = ($hora_aula_desconto + ($hora_aula_desconto / 15));
}

if (($creditos_aprov != 0) && ($creditos_aprov != ''))
{
   $creditos          = $creditos_aprov;
   $creditos          = sprintf("%.2f", $creditos);
   $creditos_desconto = $creditos_aprov;
   $creditos_desconto = sprintf("%.2f", $creditos_desconto);
}

if (($hora_aula_aprov != 0) && ($hora_aula_aprov != ''))
{
   $hora_aula          = $hora_aula_aprov;
   $hora_aula_desconto = ($hora_aula_aprov + ($hora_aula_aprov / 15));
}

$hora_aula = ($hora_aula + ($hora_aula / 15));

if (($hora_aula_desconto == 0) || ($hora_aula_desconto == ''))
{
    $hora_aula_desconto = $hora_aula;
}

if ($fl_ouvinte == '')
{
   $fl_ouvinte = '0';
}
if ($row==0)
{
   $aux_ouvinte = $fl_ouvinte;
   $aux_ofer = $ref_disciplina_ofer;
   $aux_creditos = $creditos;
   $aux_hora_aula = $hora_aula;
   $aux_turma = $turma;
   $aux_ref_professor_aux = $ref_professor_aux;
   $aux_descricao_disciplina_subst = $descricao_disciplina_subst;
   $aux_ref_disciplina = $ref_disciplina;
   
   //===== Rotate (Para usar a página em LANDSCAPE)
   PS_rotate($myfile_ps, 90);

   //===== Inserir Cabeçalho
   $quebra_pagina = 25;
   
   $lin_ini = cabecalho($myfile_ps, $data, $ref_disciplina, $disciplina, $ref_curso, $curso, $campus, $texto, $dia_semana, $dia_semana_desc, $departamento, $creditos, $hora_aula, $creditos_desconto, $hora_aula_desconto, $ref_professor, $nome_professor, $periodo, $sala, $fl_ouvinte, $turno, $turno_desc, $ref_disciplina_ofer, $descricao_disciplina_subst, $quebra_pagina, $complemento_disc);

   $lin_ini = $lin_ini - 14;

   //===== Inserir Título da Tabela
   $lin_ini = titulo_tab ($myfile_ps, $lin_ini, $lin_fin, $col_ini, $col_fin, $col_ini, $col_txt, $lin_txt, $k);
   
   $lin_ini = $lin_ini - 14;
}

else

if( ($fl_ouvinte != $aux_ouvinte) || ($ref_disciplina_ofer != $aux_ofer) || ($aux_creditos != $creditos) || ($aux_hora_aula != $hora_aula) || ($descricao_disciplina_subst != $aux_descricao_disciplina_subst) || ($aux_ref_disciplina != $ref_disciplina) || (($aux_turma != $turma) && ($aux_ref_professor_aux != '') && ($aux_ref_professor_aux != '0'))   )
{
    
   rodape ($myfile_ps, $lin_fin, $lin, $campus);
   PS_rotate($myfile_ps, 360);
   PS_end_page($myfile_ps);
   $nr_pag ++;
   PS_begin_page($myfile_ps, $nr_pag);
   PS_rotate($myfile_ps, 90);

   if (($aux_turma != $turma)&&($aux_ref_professor_aux != '')&&($aux_ref_professor_aux != '0'))
   {
   	$ref_professor   = $ref_professor_aux;
   	$nome_professor  = $nome_professor_aux;
   	$dia_semana      = $dia_semana_aux;
   	$dia_semana_desc = $dia_semana_aux_desc;
   	$turno_desc      = $turno_desc_aux;
   	$sala            = $num_sala_aux;
   }
   else
   {
   	$ref_professor  = '';
   	$nome_professor = '';
   }

   //===== Inserir Cabeçalho
   $quebra_pagina = 25;

   $lin_ini = cabecalho($myfile_ps, $data, $ref_disciplina, $disciplina, $ref_curso, $curso, $campus, $texto, $dia_semana, $dia_semana_desc, $departamento, $creditos, $hora_aula, $creditos_desconto, $hora_aula_desconto, $ref_professor, $nome_professor, $periodo, $sala, $fl_ouvinte, $turno, $turno_desc, $ref_disciplina_ofer, $descricao_disciplina_subst, $quebra_pagina, $complemento_disc);
   
   $lin_ini = $lin_ini - 14;

   //===== Inserir Título da Tabela
   $lin_ini = titulo_tab ($myfile_ps, $lin_ini, $lin_fin, $col_ini, $col_fin, $col_ini, $col_txt, $lin_txt, $k);

   $lin_ini = $lin_ini - 14;
   $count = 1 ;
   $num = 1;
   $aux_ouvinte = $fl_ouvinte;
   $aux_ofer = $ref_disciplina_ofer;
   $aux_creditos = $creditos;
   $aux_hora_aula = $hora_aula;
   $aux_turma = $turma;
   $aux_descricao_disciplina_subst = $descricao_disciplina_subst;
   $aux_ref_disciplina = $ref_disciplina;
}

$lin_fin = $lin_ini - 14;
$col_ini = 45;
$col_fin = $col_ini + 15;
$lin_txt = $lin_fin + 3;


PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);

if ($dt_cancelamento)
{
    PS_show_xy_font($myfile_ps, "*", 50, $lin_txt, 'Arial', 8);
    $num = $num - 1;
}
else
{
    PS_show_xy_font($myfile_ps, "$num", 48, $lin_txt, 'Arial', 8);
}
$col_ini = $col_fin;
$col_fin = $col_ini + 35;
$col_txt = $col_ini + 3;

PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
PS_show_xy($myfile_ps, $ref_pessoa, $col_txt, $lin_txt);

$col_ini = $col_fin;
$col_fin = $col_ini + 225;
$col_txt = $col_ini + 1;
$lin_txt = $lin_fin + 3;

PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
PS_show_xy_font($myfile_ps, $nome, $col_txt, $lin_txt, 'Arial', 8);

$col_ini = $col_fin;
$col_fin = $col_ini + 35;
$col_txt = $col_ini + 7;
$lin_txt = $lin_fin + 3;

PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
PS_show_xy_font($myfile_ps, $ref_curso, $col_txt, $lin_txt, 'Arial', 8);

$col_ini = $col_fin;
$col_fin = $col_ini + 15;

PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;

if ($dt_cancelamento)
{
    $dt_cancelamento = InvData($dt_cancelamento);
    PS_show_xy_font($myfile_ps, "Trancou em $dt_cancelamento", $col_ini+2, $lin_txt+2, 'Arial', 5);
}

PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 15;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 26;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 26;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 26;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 26;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 26;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$col_fin = $col_ini + 26;
PS_rect($myfile_ps, $col_ini, $lin_ini, $col_fin, $lin_fin, 0.3);
$col_ini = $col_fin;
$lin_ini = $lin_fin ;
$num ++;

   if ($count==$quebra_pagina)
   {
     if ($totalLinhas > $row+1)
     {
        rodape ($myfile_ps, $lin_fin, $lin, $campus);
        PS_rotate($myfile_ps, 360);
        PS_end_page($myfile_ps);
        $nr_pag ++;
        PS_begin_page($myfile_ps, $nr_pag);
        PS_rotate($myfile_ps, 90);
        $aux_turma = 'A';

  	if (($aux_turma != $turma) && ($aux_ref_professor_aux != '') && ($aux_ref_professor_aux != '0'))
  	{
     		$ref_professor   = $ref_professor_aux;
         	$nome_professor  = $nome_professor_aux;
     		$dia_semana      = $dia_semana_aux;
     		$dia_semana_desc = $dia_semana_aux_desc;
     		$turno_desc      = $turno_desc_aux;
     		$sala            = $num_sala_aux;
  	}
  	else
  	{	
     		$ref_professor  = '';
     		$nome_professor = '';
  	}

        $aux_turma = $turma;
	
        $quebra_pagina = 25;
        
    	$lin_ini = cabecalho($myfile_ps, $data, $ref_disciplina, $disciplina, $ref_curso, $curso, $campus, $texto, $dia_semana, $dia_semana_desc, $departamento, $creditos, $hora_aula, $creditos_desconto, $hora_aula_desconto, $ref_professor, $nome_professor, $periodo, $sala, $fl_ouvinte, $turno, $turno_desc, $ref_disciplina_ofer, $descricao_disciplina_subst, $quebra_pagina, $complemento_disc);

        $lin_ini = $lin_ini - 14;

        $lin_ini = titulo_tab ($myfile_ps, $lin_ini, $lin_fin, $col_ini, $col_fin, $col_ini, $col_txt, $lin_txt, $k);

        $lin_ini = $lin_ini - 14;
	
        $count = 1 ;
     }
   }

$count ++ ;

}

rodape ($myfile_ps, $lin_fin, $lin, $campus);

//========================= LOGOTIPO
PS_rotate($myfile_ps, 360);

//========================= FECHA A PÁGINA
PS_end_page($myfile_ps);

//========================= FECHA O ARQUIVO PS DESTINO
PS_close($myfile_ps);

//========================= CANCELA CONEXÃO
// @$query->Close();
// @$conn->Close();

pg_close($dbconnect);


?>

<!-- <script Language="Javascript">
   NOVAWIN.close();
   location="";
 </script>-->
<?php 
      
// header("Location:$nome_arq_ps") 

// EFETUA O DOWNLOAD
// REDIRECIONA
echo "<meta http-equiv=\"refresh\" content=\"0;url=download.php?file=$nome_arq_ps \">";
?>
      
 <form name="myform" action="" >
   <p align="center">
     <input type="button" name="botao" value="&lt;&lt; Retornar" onClick="history.go(-1)">
     <input type="button" name="botao2" value="Imprimir Novamente" onclick="location='<?php echo($nome_arq_ps)  ?>'">
   </p>
  </form>
</body>
</html>
