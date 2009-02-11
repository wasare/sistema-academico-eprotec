<? require("../../../../lib/common.php"); ?>
<? require("../../lib/CheckMesBanco.php3"); ?>
<? require("../../lib/GetPessoaNome.php3"); ?>

<html>
<head>
</head>
<body bgcolor="#FFFFFF">
<script language="PHP">
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

  CheckFormParameters(array("ref_pessoa","ref_contrato","ref_periodo","mes","ref_curso","ref_campus","ref_motivo"));

  $conn = new Connection;
  $conn->Open();
  $conn->Begin();

  // Verifica se o aluno tem algum incentivo que necessite de autorização para efetuar esta operação
  $sql = " select date(now()) > A.dt_limite_autorizacao " .
       " from bolsas A, aux_bolsas B " .
       " where A.ref_tipo_bolsa=B.id and " .
       "       A.ref_contrato='$ref_contrato' and " .
       "       A.dt_validade>date(now()) and " .
       "       B.fl_autorizacao = 't' and " .
       "       A.percentual <> 0;";

  $query = $conn->CreateQuery($sql);

  $passou_prazo = 'f';
  if ( $query->MoveNext() )
  {
    $passou_prazo = $query->GetValue(1);
  }

  if ($passou_prazo!='f')
  {
    </script>
    <script language="JavaScript">
        alert("Atenção: Este aluno possui algum tipo de benefício, portanto se ele alterar sua matrícula terá que entrar em contato com o Setor de Atendimento ao Aluno.");
    </script>
    <script language="PHP">
  }

  $query->Close();
  
  SaguAssert(CheckMesBanco($ref_periodo, $mes, $conn), "Mês $mes já fehado");

  // Descobre a quantidade de previsões de lançamento
  $sql = " select max(seq_titulo) " .
         " from previsao_lcto " .
         " where ref_contrato='$ref_contrato' and " .
         "       ref_periodo='$ref_periodo'";

  $query = $conn->CreateQuery($sql);
  
  if ($query->MoveNext())
  {
    $ultimo_mes = $query->GetValue(1);
  }
  $query->Close();

  /************************ COLETA HISTORICOS PADRAO **************************/
  $sql = " select ref_historico, " .
         "        ref_historico_cancel " .
         " from periodos where id='$ref_periodo'";
   
  $query = $conn->CreateQuery($sql);

  if ( $query->MoveNext() )
  {
    list($ref_historico_mensalidade,
         $ref_historico_trancamento) = $query->GetRowValues();
  }
  $query->Close();

  $sql = " update matricula set " .
         "        dt_cancelamento=date(now()), " .
         "        ref_motivo_cancelamento='$ref_motivo'" .
         " where ref_pessoa='$ref_pessoa' and " .
         "       ref_contrato='$ref_contrato' and" .
         "       ref_periodo='$ref_periodo' and " .
    	 "       dt_cancelamento is null";

  $ok = $conn->Execute($sql);

  $sql = " update contratos set " .
         "        dt_desativacao=date(now()), " .
         "        ref_motivo_desativacao='$ref_motivo' " .
         " where id='$ref_contrato'";
         
  $ok = $conn->Execute($sql);

  $num_parcelas = $ultimo_mes;
  
  for ( $i=$mes; $i<=$num_parcelas; $i++ )
  {
    $sql = " select ref_contrato, " .
           "        ref_pessoa, " .
           "        ref_historico, " .
           "        sum(valor), " .
           "        count(*) " .
           " from previsao_lcto " .
           " where ref_periodo='$ref_periodo' and " .
           "       ref_pessoa='$ref_pessoa' and " .
           "       ref_contrato='$ref_contrato' and " .
           "       seq_titulo='$i'" .
           " group by ref_contrato, " .
           "          ref_pessoa, " .
           "          ref_historico " .
           " order by ref_contrato, " .
           "          ref_pessoa, " .
           "          ref_historico desc";

    $query = $conn->CreateQuery($sql);

    while ($query->MoveNext())
    {
      list($ref_contrato, 
           $ref_pessoa, 
           $ref_historico, 
           $valor, 
           $count) = $query->GetRowValues();

          $sql = " insert into previsao_lcto (" .
                 "    ref_pessoa," .
                 "    ref_curso," .
                 "    ref_campus," .
                 "    ref_periodo," .
                 "    ref_contrato," .
                 "    seq_titulo," .
                 "    fl_prehist," .
                 "    dt_contabil," .
                 "    ref_historico," .
                 "    valor " .
                 " ) values (" .
                 "    '$ref_pessoa'," .
                 "    '$ref_curso'," .
                 "    '$ref_campus'," .
                 "    '$ref_periodo'," .
                 "    '$ref_contrato'," .
                 "    $i," .
                 "    't'," .
                 "    date(now()),";
             
                 if ($ref_historico == $ref_historico_mensalidade)
                 {
                    $sql .= "  '$ref_historico_trancamento',".
                            "  $valor )";
                 }
                 else
                 {
                    $sql .= "  '$ref_historico',".
                            "  $valor * (-1) )";
                 }
             
      $ok = $conn->Execute($sql);
      if ( !$ok )
        SaguAssert(0,"Problema ao cancelar as parcelas financeiras...Execute o procedimento novamente...");
    }
  }

  // variables to be passed back to ../tranca_matricula.phtml
  $location_vars = "?ref_pessoa=$ref_pessoa" .
                   "&ref_contrato=$ref_contrato" .
                   "&ref_periodo=$ref_periodo" .
                   "&mes=$mes" .
                   "&curso=$ref_curso" .
                   "&campus=$ref_campus" .
                   "&ref_motivo=$ref_motivo";

  SuccessPage("Matrícula Trancada","location='/academico/tranca_matricula.phtml$location_vars'");

  $sql = " select ref_curso, " .
         "        curso_desc(ref_curso), " .
         "        get_campus(ref_campus), " .
         "        pessoa_nome(ref_pessoa) " .
         " from contratos " .
         " where id = '$ref_contrato'";

  $query = $conn->CreateQuery($sql);

  if ($query->MoveNext())
  {
      list ( $ref_curso,
             $curso_desc,
             $campus_desc,
             $pessoa_nome) = $query->GetRowValues();
  }

  @$query->Close();
  
  $sql = " select ref_disciplina, " .
         "        descricao_disciplina(ref_disciplina), ".
         "        ref_periodo, " .
         "        dt_cancelamento " .
    	 " from matricula " .
         " where ref_pessoa='$ref_pessoa' and" .
         "       ref_contrato='$ref_contrato' and".
         "       ref_periodo='$ref_periodo'";

  $query = $conn->CreateQuery($sql);
 
  echo("<table align=\"center\">");

  echo("<tr bgcolor=\"#000099\">\n");
  echo ("<td colspan=\"4\" align=\"center\"><Font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"3\" color=\"#CCCCFF\"><b>Aluno: $ref_pessoa - $pessoa_nome<br>Curso: $ref_curso - $curso_desc - $campus_desc<br>Trancamento de Matricula $ref_periodo</b></td>");
  echo("  </tr>"); 

  echo("<tr bgcolor=\"#CCCCFF\"><td><font face=\"Helvetica\">Cod. Disciplina</font></td> <td><font face=\"Helvetica\">Disciplina</font></td> <td><font face=\"Helvetica\">Periodo</font></td> <td><font face=\"Helvetica\">Data Cancelamento</font> </td></tr>");

  while ($query->MoveNext())
  {
    list($ref_disciplina, 
         $disciplina, 
    	 $ref_periodo, 
    	 $dt_cancelamento) = $query->GetRowValues();

	 echo("<tr><td><font face=\"Helvetica\">$ref_disciplina</font></td> <td><font face=\"Helvetica\">$disciplina</font></td> <td><font face=\"Helvetica\">$ref_periodo</font></td> <td><font face=\"Helvetica\">$dt_cancelamento</font> </td></tr>");
  }
 
  $query->Close();
  
  echo("</table>");

  echo("</center>");
  
  $conn->Finish();
  $conn->Close();
   
</script>
</body>
</html>