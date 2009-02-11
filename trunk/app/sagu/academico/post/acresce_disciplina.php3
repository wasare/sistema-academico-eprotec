<? require("../../../../lib/common.php"); ?>
<? require("../../lib/CheckMesBanco.php3"); ?>
<? require("../../lib/InvData.php3"); ?>
<? require("../../lib/VerificaCursadas.php3"); ?>

<html>
<head>
<script language="PHP">

function Mostra_Matricula($id_contrato, $ref_curso, $ref_campus, $ref_periodo, $aluno_id, $conn )
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
  echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>C�d.</b></font></td>");
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


      $href = "<a href=\"matricula_altera.phtml?id=$id\"><img src=\"../images/select.gif\" alt='Ver Matr�cula' align='absmiddle' border=0></a>";

      if ($dt_cancelamento!='t')
      { $cancelada = "[C]"; }
      else
      { $cancelada = ""; }

      $cancelada = "<font color=red> $cancelada </font>";

      echo("<tr bgcolor=\"$bg\">\n");
      echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$href</td>");
      echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_disciplina</td>");
      echo ("<td width=\"60%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg\">");
 
      $sql = "select ref_campus from contratos where ref_pessoa = $ref_pessoa and ref_curso = $ref_curso";
      $query22 = $conn->CreateQuery($sql);
      $query22->MoveNext();
      $ref_campus_aux  = $query22->GetValue(1);
      if ($ref_campus != $ref_campus_aux )
      {
         echo("<img src=\"../images/checkoff.gif\" alt=\"fora sede\">");  
      }
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
 
  if (!empty($ref_disciplina_ofer))  
  {
  CheckFormParameters(array("ref_pessoa","ref_contrato","ref_periodo","ref_disciplina_ofer","mes","ref_disciplina","ref_curso","ref_campus","mes_resto"));
  }
  else
  {
  CheckFormParameters(array("ref_pessoa","ref_contrato","ref_periodo","ref_disciplina_ofer_ele","mes","ref_disciplina_ele","ref_curso","ref_campus","mes_resto"));
  }
  SaguAssert($mes && $mes_resto, "Preencha o m�s de seq��ncia e o numero de parcelas!!!");
  flush();
  $periodo_id = $ref_periodo;
  $id_contrato = $ref_contrato;
  $ValorTotal = 0;

  /************ VERIFICA SE O ALUNO J� CURSOU A DISCIPLINA ********************/

  VerificaCursadas("$ref_disciplina_ofer","$ref_disciplina_ofer_ele","$ref_pessoa","$periodo_id","1");

  $conn = new Connection;
  $conn->Open();
  $conn->Begin();
  
  SaguAssert(CheckMesBanco($periodo_id, $mes, $conn), "Mes j� fechado contabilmente!!!");

  $sql = " select max(seq_titulo) " .
         " from previsao_lcto " .
         " where ref_contrato='$ref_contrato' and " .
         "       ref_periodo='$periodo_id'";

  $query = $conn->CreateQuery($sql);
  
  if ($query->MoveNext())
  {
    $ultimo_mes = $query->GetValue(1);

    if (!$ultimo_mes)
    {
        SaguAssert(0, "Aluno sem financeiro (previs�es) no <br>contrato <b>$ref_contrato</b> no per�odo <b>$periodo_id</b>.");
    }
  }
  
  $query->Close();

  $num_parcelas = $mes_resto;

  /*********************  COLETA HISTORICOS PADRAO   **************************/
  $sql = " select ref_historico, " .
         "        ref_historico_taxa " .
         " from periodos where id='$periodo_id'";
  
  $query = $conn->CreateQuery($sql);

  if ( $query->MoveNext() )
  
    list($ref_historico,
         $ref_historico_taxa) = $query->GetRowValues();
         
  $query->Close();

  /************************* COLETA INCENTIVOS ********************************/
  $sql = " select dt_inicial from periodos where id = '$periodo_id'";

  $query = $conn->CreateQuery($sql);

  if ( $query->MoveNext() )
     $dt_inicial = $query->GetValue(1);
  else
     SaguAssert(0,"Periodo <b>$periodo_id</b> n�o cadastrado!!!");

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
  // O pre�o do curso vem do curso do contrato do aluno e n�o do curso da disciplina oferecida.
  // S� ganha desconto fora da sede se o contrato � fora da sede (Lajeado).
  // Segundo conversa com a Andr�ia dia 28/11/2003 s� ganha desconto fora da sede alunos que tem 
  // CONTRATO fora da sede e n�o alunos que simplesmente cursam disciplinas fora da sede, ou seja
  // o desconto � dado sobre o valor da parcela e n�o sobre o valor de disciplina por disciplina.
  // Ou ganha desconto fora da sede de todas disciplinas ou n�o ganha desconto fora da sede nenhuma
  // Por isso puxei este SELECT aqui pr� cima, corrigindo o problema - Beto - 28/11/2003
  
  $sql = " select preco_credito, " .
         "        novo_preco_credito, " .
         "        preco_hora, " .
         "        novo_preco_hora, " .
         "        ref_hist_desc_campus, " .
         "        valor_desc_campus, " .
         "        validade < date(now())" .
         " from precos_curso " .
         " where ref_curso='$ref_curso' and " .
         "       ref_campus='$ref_campus' and " .
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

  SaguAssert($novo_preco_credito,"Pre�o para curso <b>$ref_curso</b> n�o definido no per�odo <b>$periodo_id</b> em campus <b>$ref_campus</b>!");

  /**************** VERIFICA SE TEM ELETIVA OU SUBSTITUIDA *******************/
  
  SaguAssert($ref_disciplina_ofer || $ref_disciplina_ofer_ele,"Inconsist�ncia 1: Disciplina oferecida n�o definida");
  SaguAssert(!$ref_disciplina_ofer || $ref_disciplina,"Inconsist�ncia 2: Disciplina oferecida n�o definida");
  SaguAssert(!$ref_disciplina_ofer_ele || $ref_disciplina_ele,"Inconsist�ncia 3: Disciplina oferecida n�o definida");
  
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

  /***************** COLETA INFORMACOES DA DISCIPLINA OFERECIDA **************/
  if (empty($ref_disciplina_ofer_ele))
    $cod_disciplina_ofer = $ref_disciplina_ofer;
  else
    $cod_disciplina_ofer = $ref_disciplina_ofer_ele;

  $sql = " select A.ref_campus, " .
        "         A.ref_curso, " .
        "         B.dia_semana, " .
        "         B.desconto " .
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

  SaguAssert($cod_disciplina_ofer,"Disciplina Oferecida <b>$cod_disciplina_ofer</b> n�o cadastrada!");

  /************************** VERIFICA VAGAS **********************************/
  $sql =  " select count(*), " .
          "    num_alunos('$cod_disciplina_ofer') " .
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

  SaguAssert(! ($num_matriculados+1 > $tot_alunos),"Disciplina oferecida '$cod_disciplina_ofer' excedeu n�mero m�ximo de alunos! $num_matriculados $tot_alunos");

  /*********************     BEGIN GERA FINANCEIRO    ************************/

  /****************** COLETA NUMERO DE CREDITOS DA DISCIPLINA ****************/
  if ( $ref_disciplina_ofer_ele )
  {
    $sql = " select num_creditos, carga_horaria from disciplinas where id='$ref_disciplina_subst'";
  }
  else
  {
    $sql = " select num_creditos, carga_horaria from disciplinas where id='$ref_disciplina'";
  }

  $query = $conn->CreateQuery($sql);

  if ( $query->MoveNext() )
    list($num_creditos,
         $carga_horaria) = $query->GetRowValues();
  $query->Close();
  
  SaguAssert($ref_disciplina || $ref_disciplina_subst,"Disciplina <b>$ref_disciplina $ref_disciplina_subst</b> n�o cadastrada!");

  // Faz o teste se a disciplina � oferecida mais de um dia
  $ValordaDisciplina = 0;

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

    // Caso tenha uma carga horaria diferente para a disciplina
    // � usado para casos de complementa��o de Carga Horaria.
    /*if( ($carga_horaria != '') && (!empty($carga_horaria)) && ($carga_horaria != '0') )
    {
      if (!$integral)
      {
        $num_creditos_desconto = ($carga_horaria / 15) / $ocorrencias;
      }
      else
      {
        $num_creditos_desconto = $carga_horaria / $ocorrencias;
      }
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
  } //while

  $query->Close();

  $ValorTotal += $ValordaDisciplina;

  // valor_desc_campus � um percentual, o nome da vari�vel est� errado.
  if ($ref_hist_desc_campus != 0)
  {
    $ahist_desc_campus[] = $ref_hist_desc_campus;
    $avalor_desc_campus1[] = ($valor_desc_campus/100) * $ValordaDisciplina;
    $avalor_desc_campus2[] = ($valor_desc_campus/100) * $ValordaDisciplina_novo;
  }
  
  /**********************     END GERA FINANCEIRO    **************************/
 
  $vezes = $ultimo_mes;
  for ( $i=$mes; $i<=$vezes; $i++ )
  {

  /********************  GRAVA INFORMA��ES NO FINANCEIRO **********************/

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
           "    obs," .
           "    dt_contabil" .
           "  )" .
           "  values" .
           "  (" .
           "    '$ref_pessoa'," .
           "    '$ref_curso'," .
           "    '$ref_campus'," .
           "    '$periodo_id'," .
           "    '$ref_historico',".
           "    '$id_contrato'," .
           "    $i,".
           "    $ValorTotal," .
           "    't'," .
           "    'Acrescimo 1 Disciplina'," .
           "    date(now()) " .
           "  )";
    
    echo("<!--\n$sql\n-->\n");

    $ok = $conn->Execute($sql);
    if ( !$ok )
       SaguAssert(0,"Problema ao gerar as parcelas financeiras...Execute o procedimento novamente...");
    
    $iTotalHistDesc = count($ahist_desc_campus);

    if ($iTotalHistDesc > 0)
    {
      $CodigodoHistorico = $ahist_desc_campus[0];

      $sql2 = "select ref_campus from contratos where ref_pessoa = $ref_pessoa and ref_curso = $ref_curso";
      $query22 = $conn->CreateQuery($sql2);
      $query22->MoveNext();
      $ref_campus_aux  = $query22->GetValue(1);
      if ($ref_campus_ofer != $ref_campus_aux)
      {

      /*
      if ($i==1)
      {  $ValordoHistorico = arr_sum($avalor_desc_campus1); }
      else
      {  $ValordoHistorico = arr_sum($avalor_desc_campus2);  }
      */

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
             "    '$ref_pessoa'," .
             "    '$ref_curso'," .
             "    '$ref_campus'," .
             "    '$periodo_id'," .
             "    '$CodigodoHistorico',".
             "    '$id_contrato'," .
             "    $i,".
             "    '$ValordoHistorico'," .
             "    't'," .
             "    'Acrescimo 1 Disciplina'," .
             "    date(now())" .
             "  )";
      
      echo("<!--\n$sql\n-->\n");

      $ok = $conn->Execute($sql);
      if ( !$ok )
        SaguAssert(0,"Problema ao gerar as parcelas financeiras...Execute o procedimento novamente...");
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
               "    obs," .
               "    dt_contabil" .
               "  )" .
               "  values" .
               "  (" .
               "    '$ref_pessoa'," .
               "    '$ref_curso'," .
               "    '$ref_campus'," .
               "    '$periodo_id'," .
               "    '$ref_historico_incentivo',".
               "    '$id_contrato'," .
               "    $i,".
               "    $ValorIncentivo," .
               "    't'," .
               "    'Acrescimo 1 Disciplina'," .
               "    date(now())" .
               "  )";

        echo("<!--\n$sql\n-->\n");

        $ok = $conn->Execute($sql);
        if ( !$ok )
           SaguAssert(0,"Problema ao gerar as parcelas financeiras...Execute o procedimento novamente...");
      }
    }
  }
  
  /***********************GRAVA INFORMA��ES DA MATR�CULA **********************/
  
  $status_disciplina = $status1;
  
  if ($carga_horaria == '') 
  {
     $carga_horaria_aprov = '0';
  }
  else
  {
     $carga_horaria_aprov = $carga_horaria;
  }
  
  if (!$integral)
  {
    $creditos_aprov = $carga_horaria / 15;
  }
  else
  {
    $creditos_aprov = $carga_horaria;
  }
  
  $sql = "insert into matricula" .
         "  (" .
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
         "    creditos_aprov," .
         "    carga_horaria_aprov," .
         "    status_disciplina" .
         "  )" .
         "  values" .
         "  (" .
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
         "    '$creditos_aprov'," .
         "    '$carga_horaria_aprov'," .
         "    '$status_disciplina'" .
         "  )";
         
  echo("<!--\n$sql\n-->\n");

  $ok = $conn->Execute($sql);
  if ( !$ok )
      SaguAssert(0,"Problema ao inserir na tabela de matr�cula a disciplina <b>$ref_disciplina</b>. Execute o procedimento novamente...");
  
  $location_vars = "?ref_pessoa=$ref_pessoa" .
                   "&ref_contrato=$id_contrato" .
                   "&ref_curso=$ref_curso" .
                   "&ref_campus=$ref_campus" .
                   "&ref_periodo=$ref_periodo" .
                   "&mes=$mes" .
                   "&mes_resto=$mes_resto" .
                   "&carga_horaria=$carga_horaria";

  SuccessPage("Disciplina Acrescentada",
              "location='/academico/acresce_disciplina.phtml$location_vars'");
  
  Mostra_Matricula($id_contrato, $ref_curso, $ref_campus, $periodo_id, $ref_pessoa, $conn);

  $conn->Finish();
  $conn->Close();

</script>
</head>
<BODY></BODY>
</html>
