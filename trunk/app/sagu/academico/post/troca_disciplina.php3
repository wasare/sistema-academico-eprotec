<? require("../../../../lib/common.php"); ?>
<? require("../../lib/CheckMesBanco.php3"); ?>
<? require("../../lib/InvData.php3"); ?>
<? require("../../lib/VerificaCursadas.php3"); ?>

<html>
<head>
<script language="PHP">
function Mostra_Matricula($id_contrato, $ref_curso, $ref_campus, $ref_periodo, $aluno_id, $conn)
{
    $sql = " select ref_curso, " .
           "        curso_desc(ref_curso), " .
           "        get_campus(ref_campus), " .
           "        ref_pessoa, " .
           "        pessoa_nome(ref_pessoa) " .
           " from contratos " .
           " where id = '$id_contrato'";

    $query = $conn->CreateQuery($sql);

    if ($query->MoveNext())
    {
        list ( $ref_curso,
               $curso_desc,
               $campus_desc,
	           $ref_pessoa,
       	       $pessoa_nome) = $query->GetRowValues();
    }

    @$query->Close();

    $sql = " select A.id, " .
           "        A.ref_disciplina,".
           "        descricao_disciplina(A.ref_disciplina),".
           "        A.ref_disciplina_subst,".
           "        descricao_disciplina(A.ref_disciplina_subst),".
           "        get_dia_semana(dia_disciplina_ofer_todos(B.id)), " .
           "        turno_disciplina_ofer_todos(B.id), " .
           "        num_sala_disciplina_ofer_todos(B.id), " .
           "        A.dt_cancelamento is null, " .
           "        A.ref_campus " .
           " from matricula A, disciplinas_ofer B ".
           " where A.ref_disciplina_ofer=B.id and ".
           "       A.ref_contrato='$id_contrato' and ".
           "        A.ref_periodo='$ref_periodo' ".
           " order by dia_disciplina_ofer_todos(B.id); ";
  
    $query = $conn->CreateQuery($sql);

    echo("<center><table width=\"80%%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

    echo("<tr>"); 
    echo ("<td colspan=\"6\" align=\"center\"><hr></td>");
    echo("</tr>"); 

    echo("<tr bgcolor=\"#000099\">\n");
    echo ("<td colspan=\"6\" align=\"center\"><Font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"3\" color=\"#CCCCFF\"><b>Aluno: $ref_pessoa - $pessoa_nome<br>Curso: $ref_curso - $curso_desc - $campus_desc<br>Disciplinas Matriculadas em $ref_periodo</b></td>");
    echo("  </tr>"); 

    echo("<tr bgcolor=\"#000000\">\n");
    echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>&nbsp;</b></font></td>");
    echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód.</b></font></td>");
    echo ("<td width=\"60%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
    echo ("<td width=\"9%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Dia</b></font></td>");
    echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Turno</b></font></td>");
    echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Sala</b></font></td>");
    echo("  </tr>"); 

    $i=1;

    while( $query->MoveNext() )
    {
        list ( $id,
               $ref_disciplina,
               $desc_disciplina,
               $ref_disciplina_subst,
               $desc_disciplina_subst,
               $dia_semana,
               $turno,
               $num_sala,
    	       $dt_cancelamento,
               $ref_campus) = $query->GetRowValues();
    
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


        $href = "<a href=\"matricula_altera.phtml?id=$id\"><img src=\"../images/select.gif\" alt='Ver Matrícula' align='absmiddle' border=0></a>";

        if ($dt_cancelamento!='t')
        { $cancelada = "[C]"; }
        else
        { $cancelada = ""; }

        $cancelada = "<font color=red> $cancelada </font>";

        echo("<tr bgcolor=\"$bg\">\n");
        echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$href</td>");
        echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_disciplina</td>");
        echo ("<td width=\"60%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg\">");
        if ($ref_campus != '1')
        {  echo("<img src=\"../images/checkoff.gif\" alt=\"fora sede\">");  }
        else
        {  echo("<img src=\"../images/checkon.gif\" alt=\"na da sede\">");  }

        echo("$cancelada" . "$desc_disciplina</td>");
        echo ("<td width=\"9%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$dia_semana</td>");
        echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$turno</td>");
        echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$num_sala&nbsp;</td>");
        echo("  </tr>");

        $i++;

    }

    echo("<tr>"); 
    echo ("<td colspan=\"6\" align=\"center\"><hr></td>");
    echo("</tr>"); 

    echo("</table></center>");
}

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

</script>

<?
 
    if (!empty($ref_disciplina_ofer))  
    {
        CheckFormParameters(array("ref_pessoa","ref_contrato","ref_curso","ref_campus","ref_periodo","ref_disciplina_ofer","ref_matricula","mes","ref_disciplina","num_parcelas","dividir","ref_motivo"));
    }
    else
    {
        CheckFormParameters(array("ref_pessoa","ref_contrato","ref_curso","ref_campus","ref_periodo","ref_disciplina_ofer_ele","ref_matricula","mes","ref_disciplina_ele","num_parcelas","dividir","ref_motivo"));
    }
    SaguAssert($mes && $dividir && $num_parcelas, "Preencha os campos mês de sequência, dividir por e numero de parcelas!!!");
    flush();
    $periodo_id = $ref_periodo;
    $aluno_id = $ref_pessoa;
    $id_contrato = $ref_contrato;

    /************ VERIFICA SE O ALUNO JÁ CURSOU A DISCIPLINA ********************/
  
    VerificaCursadas("$ref_disciplina_ofer","$ref_disciplina_ofer_ele","$ref_pessoa","$periodo_id","1");
 
    /*************** VERIFICA SE A DISCIPLINA NÃO ESTÁ CANCELADA *****************/

    $conn = new Connection;
    $conn->Open();
    $conn->Begin();

    $sql = "select dt_cancelamento from matricula where id='$ref_matricula'";
  
    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
        list($dt_cancel) = $query->GetRowValues();
    $query->Close();

    SaguAssert(!$dt_cancel, "Disciplina está cancelada");
  
    SaguAssert(CheckMesBanco($periodo_id, $mes, $conn), "Mes já fechado contabilmente!!!");

    $sql = " select max(seq_titulo) " .
           " from previsao_lcto " .
       	   " where ref_contrato='$ref_contrato' and " .
           "       ref_periodo='$periodo_id'";

    $query = $conn->CreateQuery($sql);
    if ($query->MoveNext())
    {
        $ultimo_mes = $query->GetValue(1);
    }
    $query->Close();

    /*********************  COLETA HISTORICOS PADRAO   **************************/
    $sql = " select ref_historico, " .
           "        ref_historico_cancel, " .
           "        tx_cancel " .
           " from periodos where id='$periodo_id'";
	 
    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
  
    list($ref_historico,
    	 $ref_historico_cancel,
         $tx_cancel) = $query->GetRowValues();
   
    $query->Close();

    /************************* COLETA INCENTIVOS ********************************/
    $sql = " select dt_inicial from periodos where id = '$periodo_id'";

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
        $dt_inicial = $query->GetValue(1);
    else
        SaguAssert(0,"Periodo <b>$periodo_id</b> não cadastrado!!!");

    $query->Close();
  
    $sql = " select A.percentual, " .
           "        B.ref_historico " .
           " from bolsas A, aux_bolsas B " .
           " where A.ref_contrato='$id_contrato' and " .
           "       A.dt_validade>='$dt_inicial' and " .
           "       A.percentual <> 0 and " .
           "       A.ref_tipo_bolsa=B.id";

    $query = $conn->CreateQuery($sql);

    $fl_incentivo = false;
    while ( $query->MoveNext() )
    {
        $aIncentivoPercentual[] = $query->GetValue(1);
        $aIncentivoHistorico[] = $query->GetValue(2);
        $fl_incentivo = true;
    }
    $query->Close();

    /************************ COLETA PRECO DO CURSO ***************************/
    // O preço do curso vem do curso do contrato do aluno e não do curso da disciplina oferecida.
    // Só ganha desconto fora da sede se o contrato é fora da sede (Lajeado).
    // Segundo conversa com a Andréia dia 28/11/2003 só ganha desconto fora da sede alunos que tem 
    // CONTRATO fora da sede e não alunos que simplesmente cursam disciplinas fora da sede, ou seja
    // o desconto é dado sobre o valor da parcela e não sobre o valor de disciplina por disciplina.
    // Ou ganha desconto fora da sede de todas disciplinas ou não ganha desconto fora da sede de 
    // nenhuma. Por isso puxei este SELECT aqui prá cima, corrigindo o problema - Beto - 28/11/2003

    $sql = " select preco_credito, " .
           "        novo_preco_credito, " .
           "        ref_hist_desc_campus, " .
           "        valor_desc_campus, " .
           "        validade < date(now()) " .
           " from precos_curso " .
           " where ref_curso='$ref_curso' and " .
           "       ref_campus='$ref_campus' and " .
           "       ref_periodo='$periodo_id'";

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
    list($preco_credito,
         $novo_preco_credito,
         $ref_hist_desc_campus,
         $valor_desc_campus,
    	 $passou_prazo) = $query->GetRowValues();

    $query->Close();

    SaguAssert($novo_preco_credito,"Preço para curso <b>$ref_curso</b> não definido no período <b>$periodo_id</b> em campus <b>$ref_campus</b>!"); 

    /**************** VERIFICA SE TEM ELETIVA OU SUBSTITUIDA *******************/
  
    SaguAssert($ref_disciplina_ofer || $ref_disciplina_ofer_ele,"Inconsistência 1: Disciplina oferecida não definida");
    SaguAssert(!$ref_disciplina_ofer || $ref_disciplina,"Inconsistência 2: Disciplina oferecida não definida");
    SaguAssert(!$ref_disciplina_ofer_ele || $ref_disciplina_ele,"Inconsistência 3: Disciplina oferecida não definida");

    if (empty($ref_disciplina_ofer_ele))
    {
        $cod_disciplina_ofer = $ref_disciplina_ofer;
    }
    else
    {
        $cod_disciplina_ofer = $ref_disciplina_ofer_ele;
    }

    /************************ VERIFICA VAGAS - ACRÉSCIMO ***********************/
    $sql =  " select count(*), " .
            "        num_alunos('$cod_disciplina_ofer') " .
            " from matricula" .
            " where dt_cancelamento is null and " .
            "       ref_disciplina_ofer='$cod_disciplina_ofer'";

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
    {
    list($num_matriculados,
         $tot_alunos) = $query->GetRowValues();
    }
    else
    {
        $num_matriculados = -1;
        $tot_alunos = 0;
    }
    $query->Close();

    SaguAssert(! ($num_matriculados+1 > $tot_alunos),"Disciplina oferecida '$cod_disciplina_ofer' excedeu número máximo de alunos! $num_matriculados $tot_alunos");
 
    /******* COLETA INFORMACOES DA DISCIPLINA OFERECIDA - CANCELAMENTO **********/
    $sql = " select A.ref_campus, " .
           "        A.ref_curso, " .
           "        B.dia_semana, " .
           "        B.desconto " .
           " from disciplinas_ofer  A, disciplinas_ofer_compl B" .
           " where A.id = B.ref_disciplina_ofer and " .
           "       A.id='$ref_disciplina_ofer_mat'";

    $query = $conn->CreateQuery($sql);
    if ( $query->MoveNext() )
    {
    list($ref_campus_ofer,
         $ref_curso_ofer,
         $dia_semana,
         $desconto_turma) = $query->GetRowValues();
    }
    $query->Close();

    SaguAssert($ref_disciplina_ofer_mat,"Disciplina Oferecida <b>$ref_disciplina_ofer_mat</b> não cadastrada!");

    /********* COLETA NUMERO DE CREDITOS DA DISCIPLINA - CANCELAMENTO ***********/
    if ( $ref_disciplina_subst_mat )
    {
        $sql = "select num_creditos from disciplinas where id='$ref_disciplina_subst_mat'";
    }
    else
    {
        $sql = "select num_creditos from disciplinas where id='$ref_disciplina_mat'";
    }
  
    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
        list($num_creditos) = $query->GetRowValues();
    $query->Close();
  
    SaguAssert($ref_disciplina_mat || $ref_disciplina_subst_mat,"Disciplina <b>$ref_disciplina_mat $ref_disciplina_subst_mat</b> não cadastrada!");
  
    $ValordaDisciplinaCancel = 0;
    $ValordaDisciplinaCancel_novo = 0;
 
    // Faz o teste se a disciplina é oferecida mais de um dia
    $sql = " select A.ref_campus, " .
           "        A.ref_curso, " .
           "        B.dia_semana, " .
           "        B.num_creditos_desconto, " .
           "        B.desconto " .
           " from disciplinas_ofer A, disciplinas_ofer_compl B " .
           " where A.id = B.ref_disciplina_ofer and " .
           "       A.id='$ref_disciplina_ofer_mat'";

    $query = $conn->CreateQuery($sql);
    $ocorrencias = $query->GetRowCount();

    while ( $query->MoveNext() )
    {
    list($ref_campus_ofer,
         $ref_curso_ofer,
         $dia_semana,
         $num_creditos_desconto,
         $desconto_turma) = $query->GetRowValues();

        $desconto_aplicado = (100 - $desconto_turma) / 100;

        // Caso tenha uma carga horaria diferente para a disciplina
        // é usado para casos de complementação de Carga Horaria.
        if( ($carga_horaria != '') && (!empty($carga_horaria)) && ($carga_horaria != '0') )
        {
            $num_creditos_desconto = ($carga_horaria / 15) / $ocorrencias;
        }

        if (($num_creditos_desconto != '') && ($num_creditos_desconto != '0'))
        {
            $ValordaDisciplinaCancel = $ValordaDisciplinaCancel + ($preco_credito * $num_creditos_desconto * $desconto_aplicado);
            $ValordaDisciplinaCancel_novo = $ValordaDisciplinaCancel_novo + ($novo_preco_credito * $num_creditos_desconto * $desconto_aplicado);
        }
        else
        {
            $ValordaDisciplinaCancel = $ValordaDisciplinaCancel + ($preco_credito * $num_creditos * $desconto_aplicado);
            $ValordaDisciplinaCancel_novo = $ValordaDisciplinaCancel_novo + ($novo_preco_credito * $num_creditos * $desconto_aplicado);
        }
    }
  
    $query->Close();
  
    /************************ INÍCIO PARTE DE ACRÉSCIMO ************************/

    /**************** VERIFICA SE TEM ELETIVA OU SUBSTITUIDA *******************/
  
    if ( $ref_disciplina_ofer_ele )
    {
        $sql = " select ref_curso, " .
               "        ref_disciplina " .
               " from disciplinas_ofer " .
               " where id = '$ref_disciplina_ofer_ele'";
            
        $query = $conn->CreateQuery($sql);

        if ( $query->MoveNext() )
     
        list($ref_curso_subst,
             $ref_disciplina_subst) = $query->GetRowValues();
     
        $query->Close();
    }
    else
    {
        $ref_curso_subst = 0;
        $ref_disciplina_subst = 0;
    }

    /********* COLETA INFORMACOES DA DISCIPLINA OFERECIDA - ACRÉSCIMO ***********/

    $sql = " select A.ref_campus, " .
           "        A.ref_curso, " .
           "        B.dia_semana, " .
           "        B.desconto " .
           " from disciplinas_ofer A, disciplinas_ofer_compl B " .
           " where A.id = B.ref_disciplina_ofer and " .
           "       A.id='$cod_disciplina_ofer'";

    $query = $conn->CreateQuery($sql);
    if ( $query->MoveNext() )
    {
    list( $ref_campus_ofer,
          $ref_curso_ofer,
          $dia_semana,
          $desconto_turma) = $query->GetRowValues();
    }
    $query->Close();

    SaguAssert($cod_disciplina_ofer,"Disciplina Oferecida <b>$cod_disciplina_ofer</b> não cadastrada!");

    /********** COLETA NUMERO DE CREDITOS DA DISCIPLINA - ACRÉSCIMO ************/
  
    if ( $ref_disciplina_ofer_ele)
    {
        $sql = "select num_creditos from disciplinas where id='$ref_disciplina_ele'";
    }
    else
    {
        $sql = "select num_creditos from disciplinas where id='$ref_disciplina'";
    }
  
    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
        list($num_creditos) = $query->GetRowValues();
    $query->Close();
  
    SaguAssert($ref_disciplina || $ref_disciplina_ele,"Disciplina <b>$ref_disciplina $ref_disciplina_ele</b> não cadastrada!");

    $ValordaDisciplinaAcres = 0;
    $ValordaDisciplinaAcres_novo = 0;

    // Faz o teste se a disciplina é oferecida mais de um dia
    $sql = " select A.ref_campus, " .
           "        A.ref_curso, " .
           "        B.dia_semana, " .
           "        B.num_creditos_desconto, " .
           "        B.desconto " .
           " from disciplinas_ofer A, disciplinas_ofer_compl B " .
           " where A.id = B.ref_disciplina_ofer and " .
           "       A.id='$cod_disciplina_ofer'";

    $query = $conn->CreateQuery($sql);
    $ocorrencias = $query->GetRowCount();

    while ( $query->MoveNext() )
    {
        list($ref_campus_ofer,
             $ref_curso_ofer,
             $dia_semana,
             $num_creditos_desconto,
             $desconto_turma) = $query->GetRowValues();

        $desconto_aplicado = (100 - $desconto_turma) / 100;

        if (($num_creditos_desconto != '') && ($num_creditos_desconto != '0'))
        {
            $ValordaDisciplinaAcres = $ValordaDisciplinaAcres + ($preco_credito * $num_creditos_desconto * $desconto_aplicado);
            $ValordaDisciplinaAcres_novo = $ValordaDisciplinaAcres_novo + ($novo_preco_credito * $num_creditos_desconto * $desconto_aplicado);
        }
        else
        {
            $ValordaDisciplinaAcres = $ValordaDisciplinaAcres + ($preco_credito * $num_creditos * $desconto_aplicado);
            $ValordaDisciplinaAcres_novo = $ValordaDisciplinaAcres_novo + ($novo_preco_credito * $num_creditos * $desconto_aplicado);
        }
    }

    $query->Close();

    $parcelas_resto = ($ultimo_mes - $mes) + 1;
    
    $ValordoAjuste = ($ValordaDisciplinaAcres - $ValordaDisciplinaCancel) / $parcelas_resto;
    $ValordoAjuste_novo = ($ValordaDisciplinaAcres_novo - $ValordaDisciplinaCancel_novo) / $parcelas_resto;

    if ($ValordoAjuste_novo != 0)  // Cancelamentos ou Acréscimos
    {
        // valor_desc_campus é um percentual, o nome da variável está errado.
        if ($ref_hist_desc_campus != 0)
        {
            $ahist_desc_campus[] = $ref_hist_desc_campus;
            $avalor_desc_campus1[] = ($valor_desc_campus/100) * $ValordoAjuste;
            $avalor_desc_campus2[] = ($valor_desc_campus/100) * $ValordoAjuste_novo;  
        }

        for ( $i=$mes; $i<=$ultimo_mes; $i++ )
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
                   "    dt_contabil" .
                   " ) values (" .
                   "    '$ref_pessoa'," .
                   "    '$ref_curso'," .
                   "    '$ref_campus'," .
                   "    '$ref_periodo'," .
                   "    '$ref_historico',".
                   "    '$ref_contrato'," .
                   "    $i,".
                   "    $ValordoAjuste_novo," .
                   "    't'," .
                   "    date(now())" .
                   " )";
    
            echo("<!--\n$sql\n-->\n");

            $ok = $conn->Execute($sql);
            if ( !$ok )
                SaguAssert(0,"Problema ao gerar as parcelas financeiras...Execute o procedimento novamente...");

            $iTotalHistDesc = count($ahist_desc_campus);
    
            if ($iTotalHistDesc > 0)
            {
                $CodigodoHistorico = $ahist_desc_campus[0];
    
                $ValordoHistorico = arr_sum($avalor_desc_campus2);

                settype($ValordoHistorico, "double");

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
                       "    '$aluno_id'," .
                       "    '$ref_curso'," .
                       "    '$ref_campus'," .
                       "    '$periodo_id'," .
                       "    '$CodigodoHistorico',".
                       "    '$id_contrato'," .
                       "    $i,".
                       "    $ValordoHistorico," .
                       "    't'," .
                       "    'Troca $iTotalHistDesc Cadeiras fora'," .
                       "    date(now())" .
                       "  )";

                echo("<!--\n$sql\n-->\n");
    
                $ok = $conn->Execute($sql);
                if ( !$ok )
                    SaguAssert(0,"Problema ao gerar as parcelas financeiras...Execute o procedimento novamente...");

            }
    
            if ($fl_incentivo)
            {
                $iTotalIncentivo = count($aIncentivoPercentual);
    
                for ( $n=0; $n<$iTotalIncentivo; $n++ )
                {
                    $ref_historico_incentivo = $aIncentivoHistorico[$n];
                    $percentual = $aIncentivoPercentual[$n];
                    $ValorDecrescer = (($percentual /100) * $ValordoHistorico);
                    $ValorIncentivo = (($percentual /100) * $ValordoAjuste_novo) - $ValorDecrescer;
    
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
                           "    dt_contabil" .
                           " ) values (" .
                           "    '$aluno_id'," .
                           "    '$ref_curso'," .
                           "    '$ref_campus'," .
                           "    '$periodo_id'," .
                           "    '$ref_historico_incentivo',".
                           "    '$id_contrato'," .
                           "    $i,".
                           "    $ValorIncentivo," .
                           "    't'," .
                           "    date(now())" .
                           " )";
            
                    echo("<!--\n$sql\n-->\n");
    
                    $ok = $conn->Execute($sql);
                    if ( !$ok )
                        SaguAssert(0,"Problema ao gerar as parcelas financeiras...Execute o procedimento novamente...");
                }   
            }
        }
    }
    else
    {
        echo("<center><h3>Este ajuste de matrícula não alterou as suas parcelas financeiras, pois o valor da disciplina cancelada é o mesmo que o da disciplina acrescentada.</h3></center>");
    }
  
    /********************** GRAVA INFORMAÇÕES DA MATRÍCULA **********************/
 
    $sql = "update matricula set dt_cancelamento=date(now()), ref_motivo_cancelamento='$ref_motivo' where id='$ref_matricula'";
    
    $ok = $conn->Execute($sql);

    $status_disciplina = $status1;

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
           "    hora_matricula," .
           "    status_disciplina" .
           " ) values (" .
           "    '$id_contrato'," .
           "    '$ref_pessoa'," .
           "    '$ref_campus_ofer'," .
           "    '$ref_curso'," .
           "    '$periodo_id'," .
           "    '$ref_disciplina'," .
           "    '$ref_curso_subst'," .
           "    '$ref_disciplina_subst'," .
           "    '$cod_disciplina_ofer',"  .
           "    get_complemento_ofer('$cod_disciplina_ofer'), " .
           "    'S',"  .
           "    date(now())," .
           "    now()," .
           "    '$status_disciplina'" .
           " )";
 
    echo("<!--\n$sql\n-->\n");

    $ok = $conn->Execute($sql);
    if ( !$ok )
        SaguAssert(0,"Problema ao inserir na tabela de matrícula a disciplina <b>$ref_disciplina</b>. Execute o procedimento novamente...");

    $location_vars = "?ref_pessoa=$ref_pessoa" .
                     "&ref_contrato=$ref_contrato" .
                     "&curso=$ref_curso" .
                     "&ref_campus=$ref_campus" .
                     "&ref_periodo=$ref_periodo" .
                     "&mes=$mes" .
                     "&dividir=$dividir" .
                     "&mes_resto=$num_parcelas";

    SuccessPage("Disciplina Trocada",
                "location='/academico/troca_disciplina.phtml$location_vars'");
                  
    Mostra_Matricula($id_contrato, $ref_curso, $ref_campus, $periodo_id, $ref_pessoa, $conn);

    $conn->Finish();
    $conn->Close();
    
</script>
</head>
<BODY></BODY>
</html>
