<? require("../lib/common_not_login.php3"); ?>
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

function Mostra_Matricula($id_contrato, $ref_curso, $ref_campus, $ref_periodo, $aluno_id, $link2, $conn)
{

  echo("<center><table width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");
  echo("<tr>");
  echo("    <td height=\"32\" colspan=\"5\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\"><font color=\"#000000\">Início :: Selecione Contrato :: Selecione Disciplinas :: Confirmação :: <b>Finalização</b></font></font>");
  echo("    </td>");
  echo("</tr>");

  echo("<tr bgcolor=\"#000099\">\n");
  echo ("<td height=\"32\" colspan=\"5\" align=\"center\"><Font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"3\" color=\"#CCCCFF\"><b>Matrícula efetuada com Sucesso</b></td>");
  echo("  </tr>");

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

  $sql = " select A.ref_disciplina,  " .
         "        descricao_disciplina(A.ref_disciplina),".
         "        A.ref_disciplina_subst,".
         "        descricao_disciplina(A.ref_disciplina_subst),".
         "        get_dia_semana(dia_disciplina_ofer_todos(B.id)), " .
         "        turno_disciplina_ofer_todos(B.id), " .
         "        num_sala_disciplina_ofer_todos(B.id), " .
         "        professor_disciplina_ofer_todos(B.id), " .
         "        A.dt_cancelamento is null, " .
         "        A.ref_campus " .
         " from matricula A, disciplinas_ofer B ".
         " where A.ref_disciplina_ofer=B.id and ".
         "       A.ref_contrato='$id_contrato' and ".
         "       A.ref_periodo='$ref_periodo' ".
         " order by dia_disciplina_ofer_todos(B.id); ";
  
  $query = $conn->CreateQuery($sql);

  echo("<tr>"); 
  echo ("<td colspan=\"5\" align=\"center\"><hr></td>");
  echo("</tr>"); 
 
  echo("<tr bgcolor=\"#000099\">\n");
  echo ("<td colspan=\"5\" align=\"center\"><Font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"3\" color=\"#CCCCFF\"><b>Aluno: $ref_pessoa - $pessoa_nome<br>Curso: $ref_curso - $curso_desc - $campus_desc<br>Disciplinas Matriculadas em $ref_periodo</b></td>");
  echo("  </tr>"); 

  echo("<tr bgcolor=\"#000000\">\n");
  echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód.</b></font></td>");
  echo ("<td width=\"65%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
  echo ("<td width=\"9%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Dia da Semana</b></font></td>");
  echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Turno</b></font></td>");
  echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Sala</b></font></td>");
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
    	 $num_sala,
         $professor,
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


    if ($dt_cancelamento!='t')
    { $cancelada = "[C]"; }
    else
    { $cancelada = ""; }

    $cancelada = "<font color=red> $cancelada </font>";

    echo("<tr bgcolor=\"$bg\">\n");
    echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_disciplina</td>");
    echo ("<td width=\"65%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg\">");
    if ($ref_campus != '1')
    {  echo("<img src=\"../images/checkoff.gif\" alt=\"fora sede\">");  }
    else
    {  echo("<img src=\"../images/checkon.gif\" alt=\"na da sede\">");  }

    echo("$cancelada" . "$desc_disciplina <i>($professor)</i></td>");
    echo ("<td width=\"9%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$dia_semana</td>");
    echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$turno</td>");
    echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$num_sala&nbsp;</td>");
    echo("  </tr>");

    $i++;

  }
  
  echo("<tr>"); 
  echo ("<td colspan=\"5\" align=\"center\"><hr></td>");
  echo("</tr>");
  echo("<tr>"); 
  echo ("<td colspan=\"5\" align=\"center\">$link2<br></td>");
  echo("</tr>");
  
  echo("<tr>"); 
  echo ("<td colspan=\"5\" align=\"center\"><hr></td>");
  echo("</tr>");


  
  echo("</table></center>");
}

// Variáveis do Contrato
// $periodo_id, $aluno_id, $curso_id, $ref_campus, $id_contrato, $num_parcelas;

// Variáveis da Matrícula
// $code1[]; $code2[]; $ofer1[]; $ofer2[]; $status_disc[];

CheckFormParameters(array("periodo_id","aluno_id","curso_id","id_contrato","ref_campus","num_parcelas","status_contrato"));

SaguAssert((count($ofer1)>0) && (count($code1)>0 && count($status_disc)>0),"É preciso selecionar pelo menos uma disciplina!!!");

$conn = new Connection;
$conn->Open();
$conn->Begin();

/*************** VERIFICA SE JÁ TEM PREVISAO CONTABILIZADA ********************/
/*
$sql = " select count(*) " .
       " from previsao_lcto " .
       " where ref_periodo = '$periodo_id' and " .
       "       ref_pessoa = '$aluno_id' and " .
       "       ref_curso = '$curso_id' and " .
       "       ref_contrato='$id_contrato' and" .
       "       dt_contabil is not null and " .
       "       fl_lock='T'";

$query = $conn->CreateQuery($sql);
while ( $query->MoveNext() )
{
    $qtde_previsoes = $query->GetValue(1);
}
$query->Close();

$step_by_previsoes = ($qtde_previsoes==0);
SaguAssert($step_by_previsoes, "Impedimento: Aluno com previsões já contabilizadas!!!");
*/
/*************** APAGA MATRICULAS E PREVISOES DE LANCAMENTO *******************/
$sql = " delete from matricula " .
       " where ref_periodo = '$periodo_id' and" .
       "       ref_pessoa = '$aluno_id' and " .
       "       ref_curso = '$curso_id' and " .
       "       ref_contrato ='$id_contrato'";

$conn->Execute($sql);

$sql = " delete from previsao_lcto " .
       " where ref_periodo = '$periodo_id' and" .
       "       ref_pessoa = '$aluno_id' and " .
       "       ref_curso = '$curso_id' and " .
       "       ref_contrato = '$id_contrato'";
//     "       fl_prehist = 't'";

$conn->Execute($sql);

/*******************  ATUALIZA INFORMACOES NO CONTRATO ************************/
$sql = " update contratos " .
       " set cod_status='$status_contrato', " .
       "     ref_last_periodo='$periodo_id' " .
       " where id='$id_contrato'";

$conn->Execute($sql);

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
if ($ref_status_vest == $status_contrato)
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
// Por isso puxei este SELECT prá cima, corrigindo o problema.

$sql = " select preco_credito, " .
       "        novo_preco_credito, " .
       "        ref_hist_desc_campus, " .
       "        valor_desc_campus, " .
       "        validade < date(now()), " .
	   "        ref_historico_da, " .
	   "        tx_da" .	   
       " from precos_curso " .
       " where ref_curso='$curso_id' and " .
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

SaguAssert($novo_preco_credito,"Preço para curso <b>$curso_id</b> não definido no período <b>$periodo_id</b> em campus <b>$ref_campus</b>!");

/******************************************************************************/
// O formulário de especificação de disciplinas passa 4 arrays para nos:
//  ofer1[]       = códigos das oferecidas primárias
//  code1[]       = códigos das disciplinas primárias
//  ofer2[]       = códigos das oferecidas secundárias
//  code2[]       = códigos das disciplinas secundárias
//  status_disc[] = status da disciplina para o aluno

$count = count($code1);

$ValorTotalNovo = 0;
$ValorTotal = 0;

$ahist_desc_campus = null;
$avalor_desc_campus = null;

for ( $i=0; $i<$count; $i++ )
{
  $ref_curso            = $curso_id;
  $ref_disciplina       = $code1[$i];
  $ref_curso_subst      = '';
  $ref_disciplina_subst = $code2[$i];
  
  $status_disciplina    = $status_disc[$i];
  // O status que vale é o da disciplina do currículo.
  // O status da disciplina substituída não vale...

  SaguAssert($ofer1[$i] || $ofer2[$i],"Inconsistência 1: Disciplina oferecida não definida!!!");
  SaguAssert(!$ofer1[$i] || $code1[$i],"Inconsistência 2: Disciplina oferecida não definida!!!");
  SaguAssert(!$ofer2[$i] || $code2[$i],"Inconsistência 3: Disciplina oferecida não definida!!!");

  // Se temos uma disciplina secundária, vamos usar esta para a matricular
  $id_disc = $code2[$i] ? $code2[$i] : $code1[$i];
  $id_ofer = $ofer2[$i] ? $ofer2[$i] : $ofer1[$i];

  SaguAssert($id_disc && $id_ofer,"Código da disciplina #" . ( $i + 1 ) . " está inválido!!!");

  SaguAssert($id_ofer,"(1) Disciplina '$id_disc ($id_ofer)' não oferecida!!!");

  /************************** VERIFICA VAGAS **********************************/

  $sql = " select count(*), " .
         "        check_matricula_pessoa('$id_ofer', '$aluno_id'), " .
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

  if ($is_matriculado)
  {
    SaguAssert(! (($num_matriculados) > $tot_alunos),"Disciplina '$id_disc ($id_ofer)' excedeu número máximo de alunos.<br>Existem <b>$num_matriculados</b> alunos matriculados para <b>$tot_alunos</b> vagas!!!");
  }
  else
  {
    SaguAssert(! (($num_matriculados+1) > $tot_alunos),"Disciplina '$id_disc ($id_ofer)' excedeu número máximo de alunos.<br>Existem <b>$num_matriculados</b> alunos matriculados para <b>$tot_alunos</b> vagas!!!");
  }

  /***************** COLETA INFORMACOES DA DISCIPLINA SUBSTITUTA **************/
  if ( $code2[$i] )
  {
    $sql = " select ref_curso, " .
           "        ref_disciplina " .
           " from disciplinas_ofer " .
           " where id = '$ofer2[$i]'";

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

  /******* DEPOIS DE FEITOS TODOS OS TESTES DE CONSISTENCIA, INSERE A MATRÍCULA *****/
  
  $sql = "insert into matricula (" .
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
    	 "    status_disciplina)" .
         "  values (" .
         "    '$id_contrato'," .
         "    '$aluno_id'," .
         "    '$ref_campus_ofer'," .
         "    '$ref_curso'," .
         "    '$periodo_id'," .
         "    '$ref_disciplina'," .
         "    '$ref_curso_subst'," .
         "    '$ref_disciplina_subst'," .
         "    '$id_ofer',"  .
         "    get_complemento_ofer('$id_ofer'), " .
         "    'S'," .
         "    date(now())," .
         "    now(), " .
	     "    '$status_disciplina')";

  echo("<!--\n$sql\n-->\n");

  $ok = $conn->Execute($sql);
  if ( !$ok )
    SaguAssert(0,"Problema ao inserir na tabela de matrícula a disciplina <b>$ref_disciplina</b>. Execute o procedimento novamente...");

  /********** FAZ O TESTE SE A DISCIPLINA É OFERECIDA MAIS DE UM DIA ********/

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
    $ValordaDisciplina_novo = $ValordaDisciplina_novo + (($novo_preco_credito * $num_creditos_desconto * $desconto_aplicado) / $num_parcelas);
  }
  else
  {
    $ValordaDisciplina_novo = $ValordaDisciplina_novo + (($novo_preco_credito * $num_creditos * $desconto_aplicado) / $num_parcelas);
  }
  
  }
  $query->Close();

  $ValorTotalNovo += $ValordaDisciplina_novo;

  if ($ref_hist_desc_campus != 0)
  {
    $ahist_desc_campus[] = $ref_hist_desc_campus;
    $avalor_desc_campus[] = ($valor_desc_campus/100) * $ValordaDisciplina_novo;
  }
}

/************************     END GERA FINANCEIRO    **************************/

if ($num_parcelas == 6)
{
    $inicio = 1;
    $fim = 6;
}
else
{
    $inicio = 2;
    $fim = 6;
}

//for ( $i=1; $i<=$num_parcelas; $i++ )

for ( $i=$inicio; $i<=$fim; $i++ )
{
  // Taxa do DCE
  if ($i == $inicio)
  {
     $ValorDce = ($novo_preco_credito * $Percentual_Dce)/100;
    
     if (($ValorDce > 0) && ($ref_historico_dce))
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
               "    '$aluno_id'," .
               "    '$ref_curso'," .
               "    '$ref_campus'," .
               "    '$periodo_id'," .
               "    '$ref_historico_dce',".
               "    '$id_contrato'," .
               "    '$inicio',".
               "    '$ValorDce'," .
               "    't'," .
               "    date(now())" .
               " )";

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
  
  // taxa de Diretório Acadêmico
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
    $ValordoHistorico = arr_sum($avalor_desc_campus);
    
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

$sql = " select desconto_mes " .
       " from origens " .
       " where id = (select ref_origem " .
       "             from periodos " .
       "             where id='$periodo_id') ";

$query = $conn->CreateQuery($sql);
$query->MoveNext();
$desconto = $query->GetValue(1);
$query->Close();

//$seq_titulo = 1;
$seq_titulo = $inicio;
$dt_vencimento = date("d/m/Y");
$dt_lcto = date("d/m/Y");
$dt = getdate();

$sql = " select conteudo from conf_finan_cont where chave = 'SEQ_TITULO'";

$query = $conn->CreateQuery($sql);
$query->MoveNext();
$sequencia_geral = $query->GetValue(1);
$query->Close();

$mes = substr(date("m"),1,1); // Mês somente com um algarismo

$cod_titulo = substr($dt["year"],2,2) . "S" . $mes . "/" . $sequencia_geral;

$sequencia_geral = $sequencia_geral + 1;
$sql = " update conf_finan_cont set conteudo = '$sequencia_geral' where chave = 'SEQ_TITULO';";
$conn->Execute($sql);

//estes links são para impressão de boleto do sicredi
$link = "/financeiro/post/gera_titulo_previsao_bixos.php3" .
        "?ref_contrato=$id_contrato" .
        "&ref_periodo=$periodo_id" .
        "&ref_curso=$ref_curso" .
        "&ref_pessoa=$aluno_id" .
        "&seq_titulo=$seq_titulo" .
        "&dt_vencimento=$dt_vencimento" .
        "&dt_lcto=$dt_lcto" .
        "&desconto=$desconto" .
        "&cod_titulo=$cod_titulo" .
        "&boleto=yes";

$link2 = "<a href=\"/financeiro/post/gera_titulo_previsao_bixos.php3" .
        "?ref_contrato=$id_contrato" .
        "&ref_periodo=$periodo_id" .
        "&ref_curso=$ref_curso" .
        "&ref_pessoa=$aluno_id" .
        "&seq_titulo=$seq_titulo" .
        "&dt_vencimento=$dt_vencimento" .
        "&dt_lcto=$dt_lcto" .
        "&desconto=$desconto" .
        "&cod_titulo=$cod_titulo" .
        "&boleto=yes\">Caso o Boleto não tenha sido aberto na tela clique aqui</a>";

Mostra_Matricula($id_contrato, $curso_id, $ref_campus, $periodo_id, $aluno_id, $link2, $conn);

echo("<script language=\"JavaScript\">\n");
echo("  window.open('$link','Impressao_de_Boleto','toolbar=no,status=no,scrollbars=yes,location=no,menubar=yes,height=560,width=750');");
echo("</script>\n");

$conn->Finish();
$conn->Close();

</script>
<form name="myform" method="post" action="matricula_aluno_bixo.phtml">
  <center>
    <input type="submit" value=" Volta ao Início " name="Submit">
  </center>
</form>
</HEAD>
<BODY></BODY>
</HTML>
