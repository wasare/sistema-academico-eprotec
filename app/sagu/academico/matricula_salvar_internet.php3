<?php 

  require("../../../lib/common.php");
  require("../lib/InvData.php3");
  require("../lib/GetField.php3");


  //Recebendo dados do arquivo matricula_disciplinas_internet.phtml
  //$ofer1_code1 = $_POST['ofer1_code1[]'];
  $ref_periodo = $_POST['ref_periodo'];
  $ref_pessoa = $_POST['ref_pessoa'];
  $email = $_POST['email'];
  $nome_pessoa = $_POST['nome_pessoa'];
  $ref_curso = $_POST['ref_curso'];
  $nome_curso = $_POST['nome_curso'];
  $ref_contrato = $_POST['ref_contrato'];
  $ref_campus = $_POST['ref_campus'];
  $semestre = $_POST['semestre'];
  $semestre_atual = $_POST['semestre_atual'];
  $dependencias = $_POST['dependencias'];
  
?>
<html>
<head>
<title>Matrícula</title>

<?php

//print_r($ofer1_code1);

function arr_sum($Vetor)
{
  $iTotalVetor = count($Vetor);
  $iSumVetor = 0;
  for ( $n=0; $n<$iTotalVetor; $n++ )
  {
    $iSumVetor += $Vetor[$n];
  }
  return ($iSumVetor);
}


function Mostra_Matricula($ref_contrato, $ref_curso, $ref_campus, $ref_periodo, $ref_pessoa, $nome_pessoa, $email, $link2, $conn)
{
  echo("<table width=\"760\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" background=\"/images/fundo_matricula.png\">");
  echo("<tr>\n");
  echo ("<td height=\"30\" colspan=\"5\" align=\"center\"><Font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"4\"><b>MATRÍCULA EFETUADA COM SUCESSO!!!</b></td>");
  echo("  </tr>");
 
  echo("</td>");
  echo("</tr>");

  $sql = " select ref_curso, " .
         "        curso_desc(ref_curso), " .
         "        get_campus(ref_campus), " .
         "        ref_pessoa, " .
         "        pessoa_nome(ref_pessoa) " .
         " from contratos " .
         " where id = '$ref_contrato'";

  $query = $conn->CreateQuery($sql);

  if ($query->MoveNext())
  {
    list ( $curso_id,
           $curso_desc,
           $campus_desc,
           $ref_pessoa,
       	   $pessoa_nome) = $query->GetRowValues();
  }

  @$query->Close();

  $sql = " select A.ref_disciplina,  " .
         "        descricao_disciplina(A.ref_disciplina),".
         "        A.ref_disciplina_subst,".
         "        descricao_disciplina(A.ref_disciplina_subst),".
         "        get_dia_semana(dia_disciplina_ofer_todos(B.id)), " .
         "        turno_disciplina_ofer_todos(B.id), " .
         "        get_horarios_todos(B.id), " .
         "        A.dt_cancelamento is null, " .
         "        A.ref_campus, " .
         "        professor_disciplina_ofer(B.id), " .
         "        curso_desc('$ref_curso'), " .
         "        get_campus('$ref_campus') " .
         " from matricula A, disciplinas_ofer B ".
         " where A.ref_disciplina_ofer=B.id and ".
         "       A.ref_contrato='$ref_contrato' and ".
         "       A.ref_periodo='$ref_periodo' ".
         " order by dia_disciplina_ofer_todos(B.id); ";
  
  $query = $conn->CreateQuery($sql);

  echo("<tr>"); 
  echo ("<td colspan=\"5\" align=\"center\"><hr></td>");
  echo("</tr>"); 

  echo("<tr bgcolor=\"#000099\">\n");
  echo ("<td colspan=\"5\" align=\"center\"><Font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"3\" color=\"#ffffff\"><b>Aluno: $ref_pessoa - $pessoa_nome<br>Curso: $curso_id - $curso_desc - $campus_desc<br>Disciplinas Matriculadas em $ref_periodo</b></td>");
  echo("  </tr>"); 

  echo("<tr bgcolor=\"#000000\">\n");
  echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód.</b></font></td>");
  echo ("<td width=\"55%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
  echo ("<td width=\"9%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Dia</b></font></td>");
  echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Turno</b></font></td>");
  echo ("<td width=\"18%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Horário</b></font></td>");
  echo("  </tr>"); 

  $i=1;
  
  while( $query->MoveNext() )
  {
  list ( $ref_disciplina,
         $desc_disciplina,
         $ref_disciplina_subst,
         $desc_disciplina_subst,
         $dia_semana,
         $turno,
    	 $horario,
    	 $dt_cancelamento,
    	 $ref_campus,
         $professor,
         $curso_desc,
         $campus_desc) = $query->GetRowValues();

  
    $status = '&nbsp;';

    if ($ref_disciplina_subst)
    {
        $desc_disciplina = $desc_disciplina . "( " . $ref_disciplina_subst . " - "  .$desc_disciplina_subst . ")";
    }

    if ($i % 2)
    {
        $bg = "#EEEEFF";
        $fg = "#000099";
    }
    else
    {
        $bg = "#FFFFEE";
        $fg = "#000099";
    }

    if ($dt_cancelamento != 't')
    {  $status = 'Cancel';  }


    if ($dt_cancelamento!='t')
    { $cancelada = "[C]"; }
    else
    { $cancelada = ""; }

    $cancelada = "<font color=red> $cancelada </font>";

    echo("<tr bgcolor=\"$bg\">\n");
    echo ("<td><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_disciplina</td>");
    echo ("<td><Font face=\"Verdana\" size=\"1\" color=\"$fg\">");
    if ($ref_campus != '1')
    {  echo("<img src=\"../images/checkoff.gif\" title=\"fora sede\">");  
    }
    else
    {  echo("<img src=\"../images/checkon.gif\" title=\"na da sede\">");
    }

    echo("$cancelada" . "$desc_disciplina <i>($professor)</i></td>");
    echo ("<td><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$dia_semana</td>");
    echo ("<td align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$turno</td>");
    echo ("<td><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$horario&nbsp;</td>");
    echo("  </tr>");

    $i++;

  }

  echo("<tr>"); 
  echo ("<td colspan=\"5\" align=\"center\"><hr></td>");
  echo("</tr>");

  echo("</table></center>");

}

/******************************************************************************
**
** INICIA GRAVAÇÃO MATRICULA
**
******************************************************************************/

// Variáveis do Contrato
// $ref_periodo, $ref_pessoa, $ref_curso, $ref_campus, $ref_contrato, $num_parcelas, $semestre_atual;

// Variáveis da Matrícula
// $code1[]; $ofer1[]; $status_disc[];

CheckFormParameters(array('ref_periodo',
                          'ref_pessoa',
                          'nome_pessoa',
                          'ref_curso',
                          'nome_curso',
                          'ref_contrato',
                          'ref_campus',
                          'num_parcelas',
                          'semestre'));


$sel=0;			  

for ($x=0; $x<count($ofer1_code1); $x++)
{
  $check1 = 'check1_' . $x;
//  echo '$check1 :'.$check1;

  
  if ($check1) //$$check1
  {
	//echo '$check1- :'.$check1;

    list ( $ofer1[$sel],
           $code1[$sel],
           $status_disc[$sel],
           $dia[$sel],
           $turno[$sel],
           $code2[$sel],
           $sem[$sel]) = split('_', $ofer1_code1[$x]);

    if ($code2[$sel])
    {
        $ofer2[$sel] = $ofer1[$sel];
        $ofer1[$sel] = '';
    }
    else
    {
        $code2[$sel] = '';
        $ofer2[$sel] = '';
    }
    $sel++;
  }
}
/*
print_r($ofer1);
echo '<br />';
print_r($code1);
echo '<br />';
print_r($status_disc);

*/
// erro
saguassert( (count($ofer1) > 0 ) && (count($code1) > 0 && count($status_disc) > 0 ),"É preciso selecionar pelo menos uma disciplina!!!");

$conn = new Connection;
$conn->Open();

/**************** CHOQUE DE HORÁRIO ********************/
for($x=0; $x<count($code1); $x++)
{
    $ref_disciplinaX       = $code1[$x];
    $ref_disciplina_oferX  = $ofer1[$x];

    $sql = "SELECT A.dia_semana, " .
           "       B.hora_ini, " .
           "       B.hora_fim " .
           "  FROM disciplinas_ofer_compl A, " .
           "       horarios B " .
           " WHERE A.ref_horario = B.id " .
           "   AND A.ref_disciplina_ofer = '$ref_disciplina_oferX'";

    $queryX = $conn->CreateQuery($sql);

    while ( $queryX->MoveNext() )
    {
        list($dia_semanaX,
             $hora_iniX,
             $hora_finX) = $queryX->GetRowValues();

        //disciplina comparada
        for($y=0; $y<count($code1); $y++)
        {
            $ref_disciplinaY       = $code1[$y];
            $ref_disciplina_oferY  = $ofer1[$y];

            if ( $ref_disciplina_oferY == $ref_disciplina_oferX )
            {
                $y++;
                $ref_disciplinaY       = $code1[$y];
                $ref_disciplina_oferY  = $ofer1[$y];
            }
            $msg = "As disciplinas $ref_disciplinaX e $ref_disciplinaY estão em choque!!!";

            $sql = "SELECT A.dia_semana, " .
                   "       B.hora_ini, " .
                   "       B.hora_fim " .
                   "  FROM disciplinas_ofer_compl A, " .
                   "       horarios B " .
                   " WHERE A.ref_horario = B.id " .
                   "   AND A.ref_disciplina_ofer = '$ref_disciplina_oferY'";

            $queryY = $conn->CreateQuery($sql);

            while ( $queryY->MoveNext() )
            {
                list($dia_semanaY,
                     $hora_iniY,
                     $hora_finY) = $queryY->GetRowValues();

                //se for no mesmo dia da semana
                if ( $dia_semanaY == $dia_semanaX )
                {

                    $h = explode(' às ',$hora_iniX);
                    list ( $hiX, $miX, $siX ) = explode(':',$h[0]);
                    $h = explode(' às ',$hora_finX);
                    list ( $hfX, $mfX, $sfX ) = explode(':',$h[0]);
                    $h = explode(' às ',$hora_iniY);
                    list ( $hiY, $miY, $siY ) = explode(':',$h[0]);
                    $h = explode(' às ',$hora_finY);
                    list ( $hfY, $mfY, $sfY ) = explode(':',$h[0]);

                    $hora_iniX = mktime($hiX, $miX, $siX, 1, 1, 2000);
                    $hora_iniY = mktime($hiY, $miY, $siY, 1, 1, 2000);
                    $hora_finX = mktime($hfX, $mfX, $sfX, 1, 1, 2000);
                    $hora_finY = mktime($hfY, $mfY, $sfY, 1, 1, 2000);

                    //se for no mesmo horário
                    //X:   |              |
                    //Y:   |              |
                    if ( $hora_iniY == $hora_iniX || $hora_finY == $hora_finX)
                    {
                        saguassert(0,$msg);
                    }

                    //testa se Y está contido em X
                    //X:   |              |
                    //Y:        |    |
                    if ( ( $hora_iniX<=$hora_iniY) && ( $hora_finX>=$hora_iniY ) )
                    {
                        saguassert(0,$msg);
                    }

                    //testa se Y começa em X
                    //X:   |         |
                    //Y:        |         |
                    if ( ( $hora_iniX<=$hora_iniY) && ( $hora_iniY<$hora_finX ) )
                    {
                        saguassert(0,$msg);
                    }

                    //testa se X está contido em Y
                    //X:        |    |
                    //Y:   |              |
                    if ( ( $hora_iniY<=$hora_iniX) && ( $hora_finY>=$hora_iniX ) )
                    {
                        saguassert(0,$msg);
                    }

                    //testa se X começa em Y
                    //X:        |         |
                    //Y:   |         |
                    if ( ( $hora_iniY<=$hora_iniX) && ( $hora_iniX<$hora_finY ) )
                    {
                        saguassert(0,$msg);
                    }
                }
            }//segundo while
            $queryY->Close();
        }
    }//primeiro while
    $queryX->Close();
}


$conn->Begin();

/*************** VERIFICA SE JÁ TEM PREVISAO CONTABILIZADA ********************/
/*
$sql = " select count(*) " .
       " from previsao_lcto " .
       " where ref_periodo = '$ref_periodo' and " .
       "       ref_pessoa = '$ref_pessoa' and " .
       "       ref_curso = '$ref_curso' and " .
       "       ref_contrato='$ref_contrato' and" .
       "       dt_contabil is not null and " .
       "       fl_lock='T'";
       
$query = $conn->CreateQuery($sql);
while ( $query->MoveNext() )
{
    $qtde_previsoes = $query->GetValue(1);
}
$query->Close();

$step_by_previsoes = ($qtde_previsoes==0);
  saguassert($step_by_previsoes, "Impedimento: Aluno com previsões já contabilizadas!!!");
*/

/*************** APAGA MATRICULAS E PREVISOES DE LANCAMENTO *******************/
$sql = " delete from matricula " .
       " where ref_periodo = '$ref_periodo' and" .
       "       ref_pessoa = '$ref_pessoa' and " .
       "       ref_curso = '$ref_curso' and " .
       "       ref_contrato='$ref_contrato'";

$conn->Execute($sql);

$sql = " delete from previsao_lcto " .
       " where ref_periodo = '$ref_periodo' and" .
       "       ref_pessoa = '$ref_pessoa' and " .
       "       ref_curso = '$ref_curso' and " .
       "       ref_contrato='$ref_contrato' and" .
       "       fl_prehist='t'";

$conn->Execute($sql);

/*******************  ATUALIZA INFORMACOES NO CONTRATO ************************/
$status = 2;
$sql = " update contratos " .
       " set cod_status='$status', " .
       "     ref_last_periodo='$ref_periodo', " .
       "     semestre='$semestre' " .
       " where id='$ref_contrato'";

$conn->Execute($sql);

/**** COLETA DATA INICIAL DO PERÍODO E VERIFICA SE É PARA GERAR FINANCEIRO ****/
$sql = " select dt_inicial, " .
       "        fl_gera_financeiro " .
       " from periodos " .
       " where id = '$ref_periodo'";

$query = $conn->CreateQuery($sql);

if ( $query->MoveNext() )
{
    list($dt_inicial,
         $fl_gera_financeiro) = $query->GetRowValues();
}
else
{
    saguassert(0,"Período <b>$ref_periodo</b> não cadastrado!!!");
}

$query->Close();

if ($fl_gera_financeiro == 't')	//Gera Financeiro
{
    /******************  COLETA HISTORICOS PADRAO   *******************************/
    $sql = " select ref_historico, " .
           "        ref_historico_dce, " .
           "        tx_dce_normal, " .
           "        tx_dce_vest, " .
           "        ref_status_vest " .
           " from periodos where id='$ref_periodo'";

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )

      list($ref_historico,
           $ref_historico_dce,
           $tx_dce_normal,
           $tx_dce_vest,
           $ref_status_vest) = $query->GetRowValues();

    $query->Close();

    $Percentual_Dce=0;
    if ($ref_status_vest == $status_contrato)
    {  $Percentual_Dce=$tx_dce_vest;  }
    else
    {  $Percentual_Dce=$tx_dce_normal;  }

    /************************* COLETA INCENTIVOS **********************************/
    $sql = " select A.percentual, " .
           "        B.ref_historico, " .
           "        B.descricao " .
           " from bolsas A, aux_bolsas B " .
           " where A.ref_contrato='$ref_contrato' and " .
           "       A.dt_validade>='$dt_inicial' and " .
           "       A.percentual <> 0 and " .
           "       A.ref_tipo_bolsa=B.id";

    $query = $conn->CreateQuery($sql);

    $fl_incentivo = false;

    $aIncentivoPercentual = null;
    $aIncentivoHistorico = null;
    $aIncentivoTipo = null;
    $ValordoHistorico = null;

    while ( $query->MoveNext() )
    {
        $aIncentivoPercentual[] = $query->GetValue(1);
        $aIncentivoHistorico[] = $query->GetValue(2);
        $aIncentivoTipo[] = $query->GetValue(3);
        $fl_incentivo = true;
    }

    $query->Close();

    /************************ COLETA PRECO DO CURSO **************************
    O preço do curso vem do curso do contrato do aluno e não do curso da 
    disciplina oferecida. 
    ************************************************************************/
    $sql = " select preco_hora, " .
           "        preco_credito, " .
           "        preco_semestre " .
           " from   precos_curso " .
           " where  ref_curso='$ref_curso' and " .
           "        ref_campus='$ref_campus' and " .
           "        ref_periodo='$ref_periodo'";

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
    { 
        list($preco_hora,
             $preco_credito,
	     $preco_semestre ) = $query->GetRowValues();
    }

    $query->Close();

    saguassert($preco_semestre||$preco_hora||preco_credito, "Preço para curso<b> $ref_curso </b>não definido no período <b>$ref_periodo</b> em campus <b>$ref_campus</b>!");

} // End if fl_gera_financeiro

/******************************************************************************/
// O formulário de especificação de disciplinas passa 4 arrays como parâmetro
//  ofer1[]       = códigos das oferecidas primárias
//  code1[]       = códigos das disciplinas primárias
//  ofer2[]       = códigos das oferecidas secundárias
//  code2[]       = códigos das disciplinas secundárias
//  status_disc[] = status da disciplina para o aluno
//  sem[] = semestre da disciplina no curso

$ValorTotal = 0;
$ValorTotalDesconto = 0;
$count = count($code1);

/******************* COLETA CARGA HORÁRIA TOTAL E CRÉDITOS DO SEMESTRE ******************/
$sql = "SELECT sum(carga_horaria),sum(num_creditos) from disciplinas where id in (SELECT ref_disciplina from cursos_disciplinas where ref_curso = $ref_curso and ref_campus = $ref_campus and semestre_curso = $semestre_atual)";

$auxq = $conn->CreateQuery($sql); 
if ( $auxq->MoveNext() )
{
    $carga_horaria_total_semestre = $auxq->GetValue(1);
    $num_creditos_total_semestre  = $auxq->GetValue(2);
}
$auxq->close();

/*********** COLETA CARGA HORÁRIA TOTAL E CRÉDITOS DAS DISCIPLINAS SELECIONADAS **********/
for($ii=0; $ii<$count; $ii++)
{
    $ref_disciplinai = $code1[$ii];
    $sqli = "select distinct A.carga_horaria, " .
            "       A.num_creditos, " .
            "       D.desconto " .
            "  from disciplinas A, " .
            "       cursos_disciplinas B, " .
            "       disciplinas_ofer C, " .
            "       disciplinas_ofer_compl D " .
            " where A.id = B.ref_disciplina " .
            "   and C.ref_disciplina = A.id " .
            "   and C.ref_curso = B.ref_curso " .
            "   and C.id = D.ref_disciplina_ofer " .
            "   and A.id='$ref_disciplinai' " .
            "   and B.ref_curso = '$ref_curso' " .
            "   and B.ref_campus = '$ref_campus'";

    $queryi = $conn->CreateQuery($sqli);
    if ( $queryi->MoveNext() )
    {
        $desconto_total  += (float)$queryi->GetValue(2);
        if ( $desconto_total > 0 )
        {
            $a = (float)$queryi->GetValue(1);
            $b = (float)$queryi->GetValue(2);
            $carga_horaria_total += ($a - ($a * ((float)$desconto_total / 100)) );
            $num_creditos_total  += ($b - ($b * ((float)$desconto_total / 100)) );
        }
        else
        {
            $carga_horaria_total += (float)$queryi->GetValue(1);
            $num_creditos_total  += (float)$queryi->GetValue(2);
        }
    }
    $queryi->Close();
}

/******************* PERCORRE AS DISCIPLINAS SELECIONADAS ******************/
for($i=0; $i<$count; $i++)
{
    $ref_curso            = $ref_curso;
    $ref_disciplina       = $code1[$i];
    $ref_curso_subst      = '';
    $ref_disciplina_subst = $code2[$i];
    $status_disciplina    = $status_disc[$i];
    // O status que vale é o da disciplina do currículo.
    // O status da disciplina substituída não vale...

    saguassert($ofer1[$i] || $ofer2[$i],"Inconsistência 1: Disciplina oferecida não definida!!!");
    saguassert(!$ofer1[$i] || $code1[$i],"Inconsistência 2: Disciplina oferecida não definida!!!");
    saguassert(!$ofer2[$i] || $code2[$i],"Inconsistência 3: Disciplina oferecida não definida!!!");

    // se temos uma disciplina secundária, vamos usar esta para a matricular
    $id_disc = $code2[$i] ? $code2[$i] : $code1[$i];
    $id_ofer = $ofer2[$i] ? $ofer2[$i] : $ofer1[$i];

    saguassert($id_disc && $id_ofer,"Código da disciplina #" . ( $i + 1 ) . " está inválido!!!");

    saguassert($id_ofer,"(1) Disciplina '$id_disc ($id_ofer)' não oferecida!!!");

    /************************** VERIFICA VAGAS **********************************/
    $sql = " select count(*), " .
           "        check_matricula_pessoa('$id_ofer', '$ref_pessoa'), " .
           "        num_alunos('$id_ofer') " .
           " from matricula" .
           " where ref_disciplina_ofer = '$id_ofer' and " .
           "       dt_cancelamento is null";

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
    {
        list($num_matriculados,
             $is_matriculado,
             $tot_alunos) = $query->GetRowValues();
    }
    else
    {
        $num_matriculados = -1;
        $tot_alunos = 0;
    }

    $query->Close();

    if ( $is_matriculado )
    {
        saguassert(! (($num_matriculados) > $tot_alunos),"Disciplina '$id_disc ($id_ofer)' excedeu número máximo de alunos.<br>Existem <b>$num_matriculados</b> alunos matriculados para <b>$tot_alunos</b> vagas!!!");
    }
    else
    {
        saguassert(! (($num_matriculados+1) > $tot_alunos),"Disciplina '$id_disc ($id_ofer)' excedeu número máximo de alunos.<br>Existem <b>$num_matriculados</b> alunos matriculados para <b>$tot_alunos</b> vagas!!!");
    }
  
    /***************** COLETA INFORMACOES DA DISCIPLINA OFERECIDA **************/
    $sql = " select A.ref_campus, " .
           "        A.ref_curso, " .
           "        B.dia_semana, " .
           "        B.desconto " .
           " from disciplinas_ofer A, disciplinas_ofer_compl B " .
           " where A.id = B.ref_disciplina_ofer and " .
           "       A.id='$id_ofer'";
  
    $query = $conn->CreateQuery($sql);
  
    if ( $query->MoveNext() )
    {
        list($ref_campus_ofer,
             $ref_curso_ofer,
             $dia_semana,
             $desconto) = $query->GetRowValues();
    }
    $query->Close();

    saguassert($id_ofer,"Disciplina Oferecida <b>$id_ofer</b> não cadastrada!");

    /****************** COLETA carga horaria DA DISCIPLINA ****************/  
    
    if( $code2[$i] )
    {
	$sql = "select A.carga_horaria, A.num_creditos, B.semestre_curso from disciplinas A, cursos_disciplinas B where A.id = B.ref_disciplina and A.id='$ref_disciplina_subst' and ref_curso = '$ref_curso' and ref_campus='$ref_campus'";
    }
    else
    {
	$sql = "select A.carga_horaria, A.num_creditos, B.semestre_curso from disciplinas A, cursos_disciplinas B where A.id = B.ref_disciplina and A.id='$ref_disciplina' and ref_curso = '$ref_curso' and ref_campus = '$ref_campus'";
    }

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
        list($carga_horaria, $num_creditos, $sem_matr) = $query->GetRowValues();
    $query->Close();

    saguassert($ref_disciplina || $ref_disciplina_subst,"Disciplina <b>$ref_disciplina $ref_disciplina_subst</b> não cadastrada!");

    //calcula as cargas_horarias para o financeiro, inclusive calcula os descontos
    if ( $desconto > 0 )
    {
        $carga_horaria_total_matriculada[$sem_matr] += ($carga_horaria - ($carga_horaria * ($desconto / 100)));
        $carga_credito_total_matriculado[$sem_matr] += ($num_creditos - ($num_creditos * ($desconto / 100)));
    }
    else
    {
        $carga_horaria_total_matriculada[$sem_matr] += $carga_horaria;
        $carga_credito_total_matriculado[$sem_matr] += $num_creditos;
    }

    /******* DEPOIS DE FEITOS TODOS OS TESTES DE CONSISTENCIA, INSERE A MATRÍCULA *****/
    $ref_curso_subst = $ref_curso_subst ? $ref_curso_subst : 'NULL';
    $ref_disciplina_subst = $ref_disciplina_subst ? $ref_disciplina_subst : 'NULL';
    $tipo[$id_ofer] = $tipo[$id_ofer] ? $tipo[$id_ofer] : 'NULL';

    $sql = " insert into matricula (" .
           "    ref_contrato," .
           "    ref_pessoa," .
           "    ref_campus," .
           "    ref_curso," .
           "    ref_periodo," .
           "    ref_disciplina," .
           "    ref_curso_subst," .
           "    ref_disciplina_subst," .
           "    ref_disciplina_ofer," .
           "    complemento_disc, " .
           "    fl_exibe_displ_hist, " .
           "    dt_matricula," .
           "    hora_matricula, " .
    	   "    status_disciplina," .
           "    fl_internet, " .
    	   "    ip)" .
           " values (" .
           "    '$ref_contrato'," .
           "    '$ref_pessoa'," .
           "    '$ref_campus_ofer'," .
           "    '$ref_curso'," .
           "    '$ref_periodo'," .
           "    '$ref_disciplina'," .
           "    $ref_curso_subst," .
           "    $ref_disciplina_subst," .
           "    '$id_ofer',"  .
           "    '$tipo[$id_ofer]', " .
           "    'S'," .
           "    date(now())," .
           "    now(), " .
    	   "    '$status_disciplina'," .
           "    't', " . //Matrícula pela internet
    	   "    '$REMOTE_ADDR')";

    $ok = $conn->Execute($sql);
    if ( !$ok )
        saguassert(0,"Problema ao inserir na tabela de matrícula a disciplina <b>$ref_disciplina</b>. Execute o procedimento novamente...<br>$sql");

} // End For disciplinas...

/************************* GRAVA PREVISÕES DE LANÇAMENTO *******************/
if ($fl_gera_financeiro == 't')
{
    $inicio = 1;
    (float)$fim = ($num_parcelas>0) ? $num_parcelas : 6;
    $obs = '';

    //calcula o valor das mensalidades     
    if ( $preco_semestre>0 && count(explode(',',$dependencias))<=2 )
    {
        $ValorTotal = $preco_semestre / $fim;
    }
    elseif ( $preco_semestre>0 && count(explode(',',$dependencias))>2 )
    {
        $dependencias = explode(',',$dependencias);
        if ( $carga_horaria_total_semestre>0 )
        {
            $regra = ($preco_semestre * $carga_horaria_total / $carga_horaria_total_semestre);
            $ValorTotal = $regra / $fim;
        }
        elseif ( $num_creditos_total_semestre>0 );
        {
            $regra = ($preco_semestre * $num_creditos_total / $num_creditos_total_semestre);
            $ValorTotal = $regra / $fim;
        }
    }
    elseif( $preco_hora>0 )
    {
        foreach ( $carga_horaria_total_matriculada as $rowHoras )
        {
            $total_horas += $rowHoras;
        }
        $ValorTotal = ( $preco_hora * $total_horas ) / $fim;
    }
    elseif ( $preco_credito>0 )
    {
	foreach ( $carga_credito_total_matriculado as $rowCreditos )
        {
            $total_creditos += $rowCreditos;
        }
        $ValorTotal = ( $preco_credito * $total_creditos ) / $fim;
    }

    for ( $i=$inicio; $i<=$fim; $i++ )
    {

        $sql = " insert into previsao_lcto (" .
               "    ref_pessoa," .
               "    ref_curso," .
               "    ref_campus," .
               "    ref_periodo," .
               "    ref_historico," .
               "    ref_contrato," .
               "    seq_titulo," .
               "    valor," .
               "    fl_prehist," .
               "    obs, " .
               "    dt_contabil" .
               " ) values (" .
               "    '$ref_pessoa'," .
               "    '$ref_curso'," .
               "    '$ref_campus'," .
               "    '$ref_periodo'," .
               "    '$ref_historico',".
               "    '$ref_contrato'," .
               "    $i,".
               "    $ValorTotal," .
               "    't'," .
               "    '$obs', " .
               "    date(now())" .
               " )";

        $ok = $conn->Execute($sql);
        if ( !$ok )
            saguassert(0,"Problema ao gerar as parcelas financeiras...Execute o procedimento novamente...");

        if ( $fl_incentivo )
        {
            $iTotalIncentivo = count($aIncentivoPercentual);

            for ( $n=0; $n<$iTotalIncentivo; $n++ )
            {
                $ref_historico_incentivo = $aIncentivoHistorico[$n];
                $percentual = $aIncentivoPercentual[$n];
                $ValorDecrescer = (($percentual/100) * $ValordoHistorico);
                $ValorIncentivo = (($percentual/100) * ($ValorTotal - $ValorTotalDesconto)) - $ValorDecrescer;

                $sql = " insert into previsao_lcto (" .
                       "    ref_pessoa," .
                       "    ref_curso," .
                       "    ref_campus," .
                       "    ref_periodo," .
                       "    ref_historico," .
                       "    ref_contrato," .
                       "    seq_titulo," .
                       "    valor," .
                       "    fl_prehist," .
                       "    obs, " .
                       "    dt_contabil" .
                       " ) values (" .
                       "    '$ref_pessoa'," .
                       "    '$ref_curso'," .
                       "    '$ref_campus'," .
                       "    '$ref_periodo'," .
                       "    '$ref_historico_incentivo',".
                       "    '$ref_contrato'," .
                       "    $i,".
                       "    $ValorIncentivo," .
                       "    't'," .
                       "    '$obs', " .
                       "    date(now())" .
                       " )";
      
                $ok = $conn->Execute($sql);
                
                if ( !$ok )
                    saguassert(0,"Problema ao gerar as parcelas financeiras...Execute o procedimento novamente...");
            } //End For
        } // End fl_incentivo
    } // End For

    $link2= "/academico/matricula_aluno_internet.phtml" .
            "?ref_contrato=$ref_contrato" .
            "&ref_periodo=$ref_periodo" .
            "&ref_curso=$ref_curso" .
            "&ref_campus=$ref_campus" .
            "&ref_pessoa=$ref_pessoa" .
            "&mes=1" .
            "&mes_resto=6";

} // End fl_gera_financeiro...


Mostra_Matricula($ref_contrato, $ref_curso, $ref_campus, $ref_periodo, $ref_pessoa, $nome_pessoa, $email, $link2, $conn);

$conn->Finish();
$conn->Close();

?>
<form name="myform" method="post" action="matricula_aluno_internet.phtml">
<table width="760" border="0" cellspacing="0" cellpadding="0" align="center" background="/images/fundo_matricula.png">
  <tr>
    <td align="center">
        <input type="submit" value=" Ok " name="Submit">
    </td>
  </tr>
</table>
<table width="760" height="77" border="0" cellspacing="2" cellpadding="0" align="center" background="/images/fundo_matricula.png">
  <tr> 
    <td align="center" valign="center"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><b>Matrículas <?echo($ref_periodo);?></b></font>
    </td>
  </tr>
</table>

</form>
</head>
<body>
</script>
</body>
</html>
