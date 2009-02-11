<? require("../../../lib/common.php"); ?>
<? require("../lib/InvData.php3"); ?>

<HTML><HEAD>

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

// REGRAS E VARIÁVEIS DO PROGRAMA
// Variáveis do Contrato
// $periodo_id, $aluno_id, $curso_id, $ref_campus, $id_contrato, $num_parcelas;

// Variáveis da Matrícula
// $code1[]; $code2[]; $ofer1[]; $ofer2[];

// Quando tem ref_disciplina_subst.... 
// $ofer1 vem vazio ()                       $code1 tem valor (ref_disciplina)
// $ofer2 tem valor (ref_disciplina_ofer)    $code2 tem valor (ref_disciplina_subst)

// Quando não tem disciplina_subst....
// $ofer1 tem valor (ref_disciplina_ofer)    $code1 tem valor (ref_disciplina)
// $ofer2 vem vazio ()                       $code2 vem vazio ()

// EXCEÇÕES DO PROGRAMA... beto - 06-12-2002

// Nos cursos técnicos tem alguns alunos que se matricularam em 8 vezes e como o programa gerou
// somente a primeira parcela, temos que gerar mais 7 para estes alunos. Códigos: 202540, 202537, 
// 200211, 202542, 200204, 202550, 202645, 202547, 202555, no período 2003ATE.

// No Ensino Médio, período 20032G, também foi gerado somente a primeira parcela e temos que gerar
// mais 11 parcelas para os alunos matriculados.

$conn = new Connection;
$conn->Open();
$conn->Begin();

set_time_limit(0);

$num_parcelas = 6;

$sql = " select ref_last_periodo, " .
       "        ref_pessoa, " .
       "        ref_curso, " .
       "        ref_campus, " .
       "        id, " .
       "        cod_status " .
       " from contratos " .
       " where ref_last_periodo = '2004X' and " .
       "       is_matriculado('2004X', ref_pessoa) = ref_pessoa and " . //Verifica se tem matrícula
       "       dt_desativacao is null " .
       " order by ref_curso, ref_campus";

$query_contrato = $conn->CreateQuery($sql);

$contador = 1;

while ( $query_contrato->MoveNext() )
{
  list($periodo_id,
       $aluno_id,
       $curso_id,
       $ref_campus, 
       $id_contrato,
       $cod_status) = $query_contrato->GetRowValues();

$sql = " select ref_disciplina_ofer, " .
       "        ref_disciplina, ".
       "        ref_curso, " .
       "        ref_disciplina_subst, " .
       "        ref_curso_subst, " .
       "        carga_horaria_aprov " .
       " from matricula " .
       " where ref_contrato = '$id_contrato' and " .
       "       ref_periodo = '$periodo_id' and " .
       "       dt_cancelamento is null ";

$query_matricula = $conn->CreateQuery($sql);

$x = 0;

$code1 = null;
$ofer1 = null;
$code2 = null;
$ofer2 = null;
$carga_horaria = null;

while ( $query_matricula->MoveNext() )
{
  list($ref_disciplina_ofer,
       $ref_disciplina,
       $ref_curso,
       $ref_disciplina_subst, 
       $ref_curso_subst,
       $carga_horaria_aprov) = $query_matricula->GetRowValues();

  if (($ref_disciplina_subst != 0) && ($ref_disciplina_subst != ''))
  {
    $ofer1[$x] = '';
    $code1[$x] = $ref_disciplina;
    $ofer2[$x] = $ref_disciplina_ofer;
    $code2[$x] = $ref_disciplina_subst;
    $carga_horaria[$x] = $carga_horaria_aprov;
  }
  else
  {
    $ofer1[$x] = $ref_disciplina_ofer;
    $code1[$x] = $ref_disciplina;
    $ofer2[$x] = '';
    $code2[$x] = '';
    $carga_horaria[$x] = $carga_horaria_aprov;
  }
 
 $x++;
}

$query_matricula->Close();

if ($x != 0)  // if dos alunos com contratos abertos e mas com matrículas canceladas
{
/******************  COLETA HISTORICOS PADRAO   *******************************/
$sql = " select ref_historico, " .
       "        ref_historico_dce, " .
       "        tx_dce_normal, " .
       "        tx_dce_vest, " .
       "        ref_status_vest, " .
       "        tx_banco " .
       " from periodos where id='$periodo_id'";
       
$query = $conn->CreateQuery($sql);

if ( $query->MoveNext() )

  list($ref_historico,
       $ref_historico_dce,
       $tx_dce_normal,
       $tx_dce_vest,
       $ref_status_vest, 
       $tx_banco) = $query->GetRowValues();

$query->Close();

$Percentual_Dce=0;
if ($ref_status_vest == $cod_status)
{  $Percentual_Dce=$tx_dce_vest;  }
else
{  $Percentual_Dce=$tx_dce_normal;  }

/************************* COLETA INCENTIVOS **********************************/
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

$aIncentivoPercentual = null;
$aIncentivoHistorico = null;
$ValordoHistorico = null;

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
// Ou ganha desconto fora da sede de todas disciplinas ou não ganha desconto fora da sede de nenhuma
// Por isso puxei este SELECT aqui prá cima, corrigindo o problema - Beto - 28/11/2003

$sql = " select preco_credito, " .
       "        novo_preco_credito, " .
       "        ref_hist_desc_campus, " .
       "        valor_desc_campus, " .
       "        validade < date(now()), " .
	   "        ref_historico_da, " .
	   "        tx_da" .	   
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
     $passou_prazo,
	 $ref_historico_da,
	 $tx_da) = $query->GetRowValues();

$query->Close();

SaguAssert($novo_preco_credito,"Preço para o curso <b>$ref_curso</b> não definido no período <b>$periodo_id</b> em campus <b>$ref_campus</b>!");

/******************************************************************************/
// O formulário de especificação de disciplinas passa 4 arrays para nos:
//  code1[] = códigos das disciplinas primárias
//  desc1[] = descrições das disciplinas acima
//  code2[] = códigos das disciplinas secundárias
//  desc2[] = descrições das disciplinas acima

$count = count($code1);

$ValorTotalVelho = 0;
$ValorTotalNovo = 0;
$ValorTotal = 0;

$ahist_desc_campus = null;
$avalor_desc_campus1 = null;
$avalor_desc_campus2 = null;

for ( $i=0; $i<$count; $i++ )
{
  $ref_curso            = $curso_id;
  $ref_disciplina       = $code1[$i];
  $ref_curso_subst      = '';
  $ref_disciplina_subst = $code2[$i];

  SaguAssert($ofer1[$i] || $ofer2[$i],"Inconsistência 1: Disciplina oferecida não definida!!!");
  SaguAssert(!$ofer1[$i] || $code1[$i],"Inconsistência 2: Disciplina oferecida não definida!!!");
  SaguAssert(!$ofer2[$i] || $code2[$i],"Inconsistência 3: Disciplina oferecida não definida!!!");

  // se temos uma disciplina secundária, vamos usar esta para a matricular
  $id_disc = $code2[$i] ? $code2[$i] : $code1[$i];
  $id_ofer = $ofer2[$i] ? $ofer2[$i] : $ofer1[$i];

  SaguAssert($id_disc && $id_ofer,"Código da disciplina #" . ( $i + 1 ) . " está inválido!!!");

  $query->Close();

  SaguAssert($id_ofer,"(1) Disciplina '$id_disc ($id_ofer)' não oferecida!!!");

  /***************** COLETA INFORMACOES DA DISCIPLINA SUBSTITUTA **************/
  if ( $code2[$i] )
  {
    $sql = " select ref_curso, " .
           "        ref_disciplina " .
           " from disciplinas_ofer " .
           " where id = $ofer2[$i]";

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
    {
        list($ref_curso_subst,
             $ref_disciplina_subst) = $query->GetRowValues();
    }
    $query->Close();
  }
  else
  {
    $ref_curso_subst = 0;
    $ref_disciplina_subst = 0;
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
         $desconto_turma) = $query->GetRowValues();
  }
  $query->Close();

  SaguAssert($id_ofer,"Disciplina Oferecida <b>$id_ofer</b> não cadastrada!");

  /***********************    BEGIN GERA FINANCEIRO    ***********************/
  /****************** COLETA NUMERO DE CREDITOS DA DISCIPLINA ****************/
  
  if ( $code2[$i] )
  {
    $sql = "select num_creditos from disciplinas where id='$ref_disciplina_subst'";
  }
  else
  {
    $sql = "select num_creditos from disciplinas where id='$ref_disciplina'";
  } 
  $query = $conn->CreateQuery($sql);

  if ( $query->MoveNext() )
    list($num_creditos) = $query->GetRowValues();
  $query->Close();
  
  SaguAssert($ref_disciplina || $ref_disciplina_subst,"Disciplina <b>$ref_disciplina $ref_disciplina_subst</b> não cadastrada!");

  /********** FAZ O TESTE SE A DISCIPLINA É OFERECIDA MAIS DE UM DIA ********/

  $ValordaDisciplina = 0;
  $ValordaDisciplina_novo = 0;

  $sql = " select A.ref_campus, " .
         "        A.ref_curso, " .
         "        B.dia_semana, " .
         "        B.num_creditos_desconto, " .
         "        B.desconto " .
         " from disciplinas_ofer A, disciplinas_ofer_compl B " .
         " where A.id = B.ref_disciplina_ofer and " .
         "   A.id='$id_ofer'";
  
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
  if( ($carga_horaria[$i] != '') && (!empty($carga_horaria[$i])) && ($carga_horaria[$i] != '0') )
  {
    $num_creditos_desconto = ($carga_horaria[$i] / 15) / $ocorrencias;
  } 

  if (($num_creditos_desconto != '') && ($num_creditos_desconto != '0'))
  {
    $ValordaDisciplina = $ValordaDisciplina + (($preco_credito * $num_creditos_desconto * $desconto_aplicado) / $num_parcelas);
    $ValordaDisciplina_novo = $ValordaDisciplina_novo + (($novo_preco_credito * $num_creditos_desconto * $desconto_aplicado) / $num_parcelas);
  }
  else
  {
    $ValordaDisciplina = $ValordaDisciplina + (($preco_credito * $num_creditos * $desconto_aplicado) / $num_parcelas);
    $ValordaDisciplina_novo = $ValordaDisciplina_novo + (($novo_preco_credito * $num_creditos * $desconto_aplicado) / $num_parcelas);
  }
  
  }
  $query->Close();

  $ValorTotalVelho += $ValordaDisciplina;
  $ValorTotalNovo += $ValordaDisciplina_novo;

  if ($ref_hist_desc_campus != 0)
  {
    $ahist_desc_campus[] = $ref_hist_desc_campus;
    $avalor_desc_campus1[] = ($valor_desc_campus/100) * $ValordaDisciplina;
    $avalor_desc_campus2[] = ($valor_desc_campus/100) * $ValordaDisciplina_novo;
  }
}

/************************     END GERA FINANCEIRO    **************************/

for ( $i=1; $i<=$num_parcelas; $i++ )
{
  // Taxa do DCE
  if ($i==1)
  {
      $ValorDce = ($preco_credito * $Percentual_Dce)/100;

      if (($ValorDce > 0) && ($ref_historico_dce))
      {
          $sql = "insert into previsao_lcto (" .
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
                 "    '$ref_historico_dce',".
                 "    '$id_contrato'," .
                 "    '1',".
                 "    '$ValorDce'," .
                 "    't'," .
                 "    date(now())" .
                 "  )";

          echo("<!--\n$sql\n-->\n");

          $ok = $conn->Execute($sql);
          if ( !$ok )
              SaguAssert(0,"Problema ao gerar as parcelas financeiras...Execute o procedimento novamente...");
      }
  }
  
  $ValorTotal = $ValorTotalNovo;

  $ValorFinal = $ValorTotal;

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
         "    '$ref_historico',".
         "    '$id_contrato'," .
         "    $i,".
         "    $ValorFinal," .
         "    't'," .
         "    date(now())" .
         "  )";

  echo("<!--\n$sql\n-->\n");
  
  $ok = $conn->Execute($sql);
  if ( !$ok )
     SaguAssert(0,"Problema ao gerar as parcelas financeiras...Execute o procedimento novamente...");
     
  // Taxa de Diretório Acadêmico
  if (($ref_historico_da) && ($tx_da > 0))
  {
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
           "    '$ref_historico_da',".
           "    '$id_contrato'," .
           "    '$i',".
           "    '$tx_da'," .
           "    't'," .
           "    date(now())" .
           "  )";

    echo("<!--\n$sql\n-->\n");

    $ok = $conn->Execute($sql);
    if ( !$ok )
        SaguAssert(0,"Problema ao gerar as parcelas financeiras...Execute o procedimento novamente...");
  }

  $iTotalHistDesc = count($ahist_desc_campus);

  if ($iTotalHistDesc > 0)
  {
    $CodigodoHistorico = $ahist_desc_campus[0];
    
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
           "    '$ValordoHistorico'," .
           "    't'," .
           "    '$iTotalHistDesc Cadeiras fora'," .
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
             "    $ValorIncentivo," .
             "    't'," .
             "    date(now())" .
             "  )";
      
      echo("<!--\n$sql\n-->\n");
      
      $ok = $conn->Execute($sql);
      if ( !$ok )
        SaguAssert(0,"Problema ao gerar as parcelas financeiras...Execute o procedimento novamente...");
    }
  }
}

echo("<b>$contador</b> - Aluno: = <b>$aluno_id</b> - Curso = <b>$ref_curso</b> Valor Final: <b>$ValorFinal</b><br>");

$contador++;

} // End if dos alunos com contratos abertos e mas com matrículas canceladas

flush();

} // End while contratos

$query_contrato->Close();

$conn->Finish();
$conn->Close();

</script>
</HEAD>
<BODY></BODY>
</HTML>
