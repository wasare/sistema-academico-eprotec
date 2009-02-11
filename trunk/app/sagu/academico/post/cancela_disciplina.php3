<? require("../../../../lib/common.php"); ?>
<? require("../../lib/CheckMesBanco.php3"); ?>
<? require("../../lib/InvData.php3"); ?>

<html>
<head>
</head>
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
         "       A.ref_periodo='$ref_periodo' ".
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

  CheckFormParameters(array("ref_pessoa","ref_contrato","ref_periodo","ref_disciplina_ofer","mes","ref_disciplina","ref_curso","periodo_id","ref_campus","num_parcelas","ref_matricula","ref_motivo","fl_tx_cancel"));
  
  SaguAssert($mes && $num_parcelas, "O mês de seqüência ou o número de parcelas não pode ser nulo!!!");
  
  $id_contrato = $ref_contrato;
  $aluno_id = $ref_pessoa;
  $periodo_id = $ref_periodo;

  $ValorTotal = 0;
  $conn = new Connection;
  $conn->Open();
  $conn->Begin();

  SaguAssert(CheckMesBanco($periodo_id, $mes, $conn), "Mes já fechado contabilmente!!!");

  /****************** VERIFICA SE JA CANCELOU A DISCIPLINA ********************/
  $sql = "select dt_cancelamento from matricula where id='$ref_matricula'";
  
  $query = $conn->CreateQuery($sql);

  if ( $query->MoveNext() )
    list($dt_cancel) = $query->GetRowValues();
  $query->Close();

  SaguAssert(!$dt_cancel, "Disciplina já está cancelada");
  
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

  /************************ COLETA HISTORICOS PADRAO **************************/
  $sql = " Select ref_historico, " .
         "        ref_historico_taxa, " .
         "        ref_historico_cancel, " .
         "        tx_cancel " .
         " from periodos where id='$periodo_id'";
   
  $query = $conn->CreateQuery($sql);

  if ( $query->MoveNext() )
  {
    list($ref_historico,
         $ref_historico_taxa,
         $ref_historico_cancel,
         $tx_cancel) = $query->GetRowValues();
  }
  $query->Close();

  /************************** COLETA INCENTIVOS *******************************/
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
  // Ou ganha desconto fora da sede de todas disciplinas ou não ganha desconto fora da sede nenhuma
  // Por isso puxei este SELECT aqui prá cima, corrigindo o problema - Beto - 28/11/2003

  $sql = " select preco_credito, " .
         "        novo_preco_credito, " .
         "        preco_hora, " .
         "        novo_preco_hora, " .
         "        ref_hist_desc_campus, " .
         "        valor_desc_campus, " .
         "        validade < date(now())" .
         " from precos_curso " .
         " where ref_curso='$ref_curso' and " .
         "       ref_campus='$ref_campus' and  " .
         "       ref_periodo='$periodo_id'";

  $query = $conn->CreateQuery($sql);

  if ( $query->MoveNext() )
    list($preco_credito,
         $novo_preco_credito,
         $preco_hora,
         $novo_preco_hora,
         $ref_hist_desc_campus,
         $valor_desc_campus,
         $passou_prazo) = $query->GetRowValues();

  $query->Close();

  SaguAssert($novo_preco_credito,"Preço para curso <b>$ref_curso</b> não definido no período <b>$periodo_id</b> em campus <b>$ref_campus</b>!");
  
  /***************** COLETA INFORMACOES DA DISCIPLINA OFERECIDA **************/
  $sql = " select A.ref_campus, " .
         "        A.ref_curso, " .
         "        B.dia_semana, " .
         "        B.desconto " .
         " from disciplinas_ofer  A, disciplinas_ofer_compl B" .
         " where A.id = B.ref_disciplina_ofer and " .
         "       A.id='$ref_disciplina_ofer'";

  $query = $conn->CreateQuery($sql);
  if ( $query->MoveNext() )
  {
    list($ref_campus_ofer,
         $ref_curso_ofer,
         $dia_semana,
         $desconto_turma) = $query->GetRowValues();
  }
  $query->Close();

  SaguAssert($ref_disciplina_ofer,"Disciplina Oferecida <b>$ref_disciplina_ofer</b> não cadastrada!");

  /****************** COLETA NUMERO DE CREDITOS DA DISCIPLINA ****************/
  if ( $ref_disciplina_subst )
  {
    $sql = "select num_creditos, carga_horaria from disciplinas where id='$ref_disciplina_subst'";
  }
  else
  {
    $sql = "select num_creditos, carga_horaria from disciplinas where id='$ref_disciplina'";
  }
                    
  $query = $conn->CreateQuery($sql);

  if ( $query->MoveNext() )
    list($num_creditos,
         $carga_horaria) = $query->GetRowValues();
    
  $query->Close();
  
  SaguAssert($ref_disciplina || $ref_disciplina_subst,"Disciplina <b>$ref_disciplina $ref_disciplina_subst</b> não cadastrada!");

  // Faz o teste se a disciplina é oferecida mais de um dia
  $ValordaDisciplina = 0;
  $ValordaDisciplina_novo = 0;

  $sql = " select A.ref_campus, " .
         "        A.ref_curso, " .
         "        B.dia_semana, " .
         "        B.num_creditos_desconto, " .
         "        B.desconto " .
         " from disciplinas_ofer A, disciplinas_ofer_compl B " .
         " where A.id = B.ref_disciplina_ofer and " .
         "       A.id='$ref_disciplina_ofer'";
 
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
  /*if( ($carga_horaria != '') && (!empty($carga_horaria)) && ($carga_horaria != '0') )
  {
     $num_creditos_desconto = ($carga_horaria / 15) / $ocorrencias;
  }*/


    //calculo por horas
    if ( $preco_hora>0 )
    {
      if (($num_creditos_desconto != '') && ($num_creditos_desconto != '0'))
      {
        $regra = $carga_horaria - (($num_creditos_desconto * $carga_horaria) / $num_creditos);
        $ValordaDisciplina = $ValordaDisciplina + (($preco_hora * $regra * $desconto_aplicado) / $num_parcelas);
      }
      else
      {
        $ValordaDisciplina = $ValordaDisciplina + (($preco_hora * $carga_horaria * $desconto_aplicado) / $num_parcelas);
      }
    }
    //calculo por creditos
    elseif ( $preco_credito>0 )
    {
      if (($num_creditos_desconto != '') && ($num_creditos_desconto != '0'))
      {
        $ValordaDisciplina = $ValordaDisciplina + (($preco_credito * $num_creditos_desconto * $desconto_aplicado) / $num_parcelas);
      }
      else
      {
        $ValordaDisciplina = $ValordaDisciplina + (($preco_credito * $num_creditos * $desconto_aplicado) / $num_parcelas);
      }
    }
  }//while
  $query->Close();

  $ValorTotal += $ValordaDisciplina;


  // valor_desc_campus é um percentual, o nome da variável está errado.
  if ($ref_hist_desc_campus != 0)
  {
    $ahist_desc_campus[] = $ref_hist_desc_campus;
    $avalor_desc_campus1[] = ($valor_desc_campus/100) * $ValordaDisciplina;
    $avalor_desc_campus2[] = ($valor_desc_campus/100) * $ValordaDisciplina_novo;
  }

  if ($fl_tx_cancel=='yes') // verifica se deve cancelar mesmo a disciplina
  {
    $i = $mes;
    $sql = "insert into previsao_lcto" .
           "  (" .
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
           "  )" .
           "  values" .
           "  (" .
           "    '$ref_pessoa'," .
           "    '$ref_curso'," .
           "    '$ref_campus'," .
           "    '$ref_periodo'," .
           "    '$ref_historico_taxa'," .
           "    '$ref_contrato'," .
           "    $i,".
           "    $tx_cancel," .
           "    't'," .
           "    date(now())" .
           "  )";

    echo("<!--\n$sql\n-->\n");

    $ok = $conn->Execute($sql);
    if ( !$ok )
        SaguAssert(0,"Problema ao cancelar as parcelas financeiras...Execute o procedimento novamente...");
  }

  for ( $i=$mes; $i<=$ultimo_mes; $i++ )
  {

    $sql = " insert into previsao_lcto" .
           "  (" .
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
           "  )" .
           "  values" .
           "  (" .
           "    '$ref_pessoa'," .
           "    '$ref_curso'," .
           "    '$ref_campus'," .
           "    '$ref_periodo'," .
           "    '$ref_historico_cancel',".
           "    '$ref_contrato'," .
           "    $i,".
           "    $ValorTotal," .
           "    't'," .
           "    date(now())" .
           "  )";
    
    echo("<!--\n$sql\n-->\n");

    $ok = $conn->Execute($sql);
    if ( !$ok )
        SaguAssert(0,"Problema ao cancelar as parcelas financeiras...Execute o procedimento novamente...");
    
    $iTotalHistDesc = count($ahist_desc_campus);

    if ($iTotalHistDesc > 0)
    {
      $CodigodoHistorico = $ahist_desc_campus[0];
      /*
      if ($i==1)
      { $ValordoHistorico = arr_sum($avalor_desc_campus1); }
      else
      { $ValordoHistorico = arr_sum($avalor_desc_campus2); }
      */

      $sql2 = "select ref_campus from contratos where ref_pessoa = $ref_pessoa and ref_curso = $ref_curso";
      $query22 = $conn->CreateQuery($sql2);
      $query22->MoveNext();
      $ref_campus_aux  = $query22->GetValue(1);
      if ($ref_campus_ofer != $ref_campus_aux)
      {

      $ValordoHistorico = arr_sum($avalor_desc_campus2);

      settype($ValordoHistorico, "double");

      $sql = "insert into previsao_lcto" .
             "  (" .
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
             "  )" .
             "  values" .
             "  (" .
             "    '$aluno_id'," .
             "    '$ref_curso'," .
             "    '$ref_campus'," .
             "    '$periodo_id'," .
             "    '$CodigodoHistorico',".
             "    '$id_contrato'," .
             "    $i,".
             "    $ValordoHistorico * (-1)," .
             "    't'," .
             "    'Canc $iTotalHistDesc Cadeiras fora'," .
             "    date(now())" .
             "  )";

      echo("<!--\n$sql\n-->\n");

      $ok = $conn->Execute($sql);
      if ( !$ok )
          SaguAssert(0,"Problema ao cancelar as parcelas financeiras...Execute o procedimento novamente...");
      }
    }

   if ($fl_incentivo)
   {
     $iTotalIncentivo = count($aIncentivoPercentual);

      for ( $n=0; $n<$iTotalIncentivo; $n++ )
      {
        $ref_historico_incentivo = $aIncentivoHistorico[$n];
        $percentual = $aIncentivoPercentual[$n];
        $ValorDecrescer = (($percentual /100) * $ValordoHistorico);
        $ValorIncentivo = (($percentual /100) * $ValorTotal) - $ValorDecrescer;


        $sql = "insert into previsao_lcto" .
               "  (" .
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
               "  )" .
               "  values" .
               "  (" .
               "    '$aluno_id'," .
               "    '$ref_curso'," .
               "    '$ref_campus'," .
               "    '$periodo_id'," .
               "    '$ref_historico_incentivo',".
               "    '$id_contrato'," .
               "    $i,".
               "    $ValorIncentivo * (-1)," .
               "    't'," .
               "    date(now())" .
               "  )";

        echo("<!--\n$sql\n-->\n");

        $ok = $conn->Execute($sql);
        if ( !$ok )
            SaguAssert(0,"Problema ao cancelar as parcelas financeiras...Execute o procedimento novamente...");
      }
    }
  }

  $sql = "update matricula set dt_cancelamento=date(now()), ref_motivo_cancelamento='$ref_motivo' where id='$ref_matricula'";
  
  $ok = $conn->Execute($sql);
  
  //CheckFormParameters(array("ref_pessoa","ref_contrato","ref_periodo","ref_disciplina_ofer","mes","ref_disciplina","ref_curso","periodo_id","ref_campus","num_parcelas","ref_matricula","ref_motivo","fl_tx_cancel"));
  $location_vars = "?ref_contrato=$ref_contrato" .
                   "&ref_pessoa=$ref_pessoa" .
                   "&ref_periodo=$ref_periodo" .
                   "&curso=$ref_curso" .
                   "&ref_campus=$ref_campus" .
                   "&mes_resto=$num_parcelas" .
                   "&mes=$mes" .
                   "&fl_tx_cancel=$fl_tx_cancel";
                   
  SuccessPage("Disciplina Cancelada","location='/academico/cancela_disciplina.phtml$location_vars'");

  Mostra_Matricula($id_contrato, $ref_curso, $ref_campus, $periodo_id, $aluno_id, $conn);

  $conn->Finish();
  $conn->Close();
  
</script>
</html>
