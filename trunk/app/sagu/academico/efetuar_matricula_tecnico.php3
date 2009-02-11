<? require("../../../lib/common.php"); ?>
<? require("../lib/GetField.php3"); ?>
<? require("../lib/InvData.php3"); ?>

<HTML><HEAD>
<?

CheckFormParameters(array("periodo_id","id_contrato","curso_id","ref_campus","aluno_id","num_parcelas","cod_status"));

$conn = new Connection;
$conn->Open();
$conn->Begin();

/**************** VERIFICA SE JÁ TEM PREVISAO CONTABILIZADA *******************/

$sql = " select count(*) " .
       " from previsao_lcto " .
       " where ref_periodo = '$periodo_id' and" .
       "       ref_pessoa = $aluno_id and " .
       "       ref_curso = $curso_id and " .
       "       ref_contrato='$id_contrato' and " .
       "       dt_contabil is not null and " .
       "       fl_lock='T'";

$query = $conn->CreateQuery($sql);

while ( $query->MoveNext() )
{
  $qtde_previsoes = $query->GetValue(1);
}
$query->Close();

$step_by_previsoes = ($qtde_previsoes==0);

SaguAssert($step_by_previsoes, "Impedimento: Aluno com previsões já contabilizadas");

/*************** APAGA MATRICULAS E PREVISOES DE LANCAMENTO ******************/

$sql = " delete from previsao_lcto " .
       " where ref_periodo = '$periodo_id' and" .
       "       ref_pessoa = $aluno_id and " .
       "       ref_curso = $curso_id and " .
       "       ref_contrato='$id_contrato' and" .
       "       fl_prehist='t';";

$conn->Execute($sql);

$sql = " delete from matricula " .
       " where ref_periodo = '$periodo_id' and " .
       "       ref_pessoa = $aluno_id and " .
       "       ref_curso = $curso_id and " .
       "       ref_contrato='$id_contrato';";

$conn->Execute($sql);

/*****************  ATUALIZA INFORMACOES NO CONTRATO **************************/

$sql = " update contratos SET " .
       "     cod_status='$cod_status', " .
       "     ref_last_periodo='$periodo_id' " .
       " where id='$id_contrato'";

$conn->Execute($sql);

/*************************  COLETA HISTORICOS PADRAO   ************************/

$sql = " select ref_historico, " .
       "        tx_banco " .
       " from periodos " .
       " where id='$periodo_id'";

$query = $conn->CreateQuery($sql);

if ( $query->MoveNext() )

  list($ref_historico,
       $tx_banco) = $query->GetRowValues();

$query->Close();

/**************************** COLETA INCENTIVOS *******************************/
$sql = " select A.percentual, " .
       "        B.ref_historico " .
       " from bolsas A, aux_bolsas B " .
       " where A.ref_contrato='$id_contrato' and " .
       "       A.dt_validade>=date(now()) and ".
       "       A.percentual <> 0 and " .  
       "       A.ref_tipo_bolsa=B.id ";

$query = $conn->CreateQuery($sql);

$fl_incentivo = false;
while ( $query->MoveNext() )
{
  $aIncentivoPercentual[] = $query->GetValue(1);
  $aIncentivoHistorico[] = $query->GetValue(2);
  $fl_incentivo = true;
}

$query->Close();

/********************** Disciplinas que o aluno vai cursar *****************/

$sql = " select id, " .
       "        ref_disciplina " .
       " from disciplinas_ofer " .
       " where ref_periodo = '$periodo_id' and " .
       "       ref_curso = '$curso_id' and " .
       "       ref_campus = '$ref_campus';";

$query_disciplinas_ofer = $conn->CreateQuery($sql);

$i = 0;
while ( $query_disciplinas_ofer->MoveNext() )
{
  list($ref_disciplina_ofer, 
       $ref_disciplina) = $query_disciplinas_ofer->GetRowValues();

  $i++;
  
/***************************** INSERE A MATRÍCULA *************************/
  $sql = " insert into matricula (" .
         "    ref_contrato," .
         "    ref_pessoa," .
         "    ref_curso," .
         "    ref_campus, " .
         "    ref_periodo," .
         "    ref_disciplina," .
         "    ref_disciplina_ofer," .
         "    complemento_disc, " .
         "    fl_exibe_displ_hist," .
         "    dt_matricula," .
         "    hora_matricula" .
         " ) values (" .
         "    '$id_contrato'," .
         "    '$aluno_id'," .
         "    '$curso_id'," .
         "    '$ref_campus', " .
         "    '$periodo_id'," .
         "    '$ref_disciplina'," .
         "    '$ref_disciplina_ofer',"  .
         "    get_complemento_ofer('$ref_disciplina_ofer'), " .
         "    'S',"  .
         "    date(now())," .
         "    now()" .
         " )";

  $ok = $conn->Execute($sql);
  if ( !$ok )
      SaguAssert(0,"Problema ao inserir na tabela de matrícula a disciplina <b>$ref_disciplina</b>. Execute o procedimento novamente...");

}

$query_disciplinas_ofer->Close();

/***********************  BEGIN GERA FINANCEIRO ***************************/

/************************ COLETA PRECO DO CURSO ***************************/
  $sql = " select preco_credito, " .
         "        novo_preco_credito, " .
         "        validade < date(now()) " .
    	 " from precos_curso " .
         " where ref_curso='$curso_id' and " .
         "       ref_periodo='$periodo_id' and " .
         "       ref_campus='$ref_campus'";

  $query = $conn->CreateQuery($sql);
  $preco_credito = 0;

  if ( $query->MoveNext() )
  
    list($valor_mens_velho,
         $valor_mens,
    	 $passou_prazo) = $query->GetRowValues();
  
  $query->Close();

  SaguAssert($valor_mens_velho || $valor_mens,"Preço para curso $curso_id não definido no período $periodo_id ");

/*************************** END GERA FINANCEIRO ****************************/

for($x=1; $x<=$num_parcelas; $x++)
{
  /*
  if ( ($x==1) && ($passou_prazo == 'f') )
  {
      $valor = $valor_mens_velho;
  }
  else
  {
      $valor = $valor_mens;
  }
  
  $valor = ($valor+$tx_banco);
  */

  $valor = $valor_mens;
  
  $sql = " insert into previsao_lcto (" .
         "    ref_pessoa," .
         "    ref_curso," .
         "    ref_campus, " .
         "    ref_periodo," .
         "    ref_historico," .
         "    ref_contrato," .
         "    seq_titulo," .
         "    valor," .
         "    fl_prehist," .
         "    dt_contabil" .
         " ) values (" .
         "    '$aluno_id'," .
         "    '$curso_id'," .
         "    '$ref_campus', " .
         "    '$periodo_id'," .
         "    '$ref_historico',".
         "    '$id_contrato'," .
         "    '$x',".
         "    '$valor'," .
         "    't'," .
         "    date(now())" .
         " )";
  
  $ok = $conn->Execute($sql);
  if ( !$ok )
      SaguAssert(0,"Problema ao gerar as parcelas financeiras...Execute o procedimento novamente...");

  if ($fl_incentivo)
  {
    $iTotalIncentivo = count($aIncentivoPercentual);

    for ( $n=0; $n<$iTotalIncentivo; $n++ )
    {
      $ref_historico_incentivo = $aIncentivoHistorico[$n];
      $percentual = $aIncentivoPercentual[$n];
      $ValorIncentivo = (($percentual /100) * $valor);

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
             "    '$curso_id'," .
             "    '$ref_campus'," .
             "    '$periodo_id'," .
             "    '$ref_historico_incentivo',".
             "    '$id_contrato'," .
             "    '$x',".
             "    '$ValorIncentivo'," .
             "    't'," .
             "    date(now())" .
             " )";

      $ok = $conn->Execute($sql);
      if ( !$ok )
          SaguAssert(0,"Problema ao gerar as parcelas financeiras...Execute o procedimento novamente...");

    }
  }
}

$err = $conn->GetError();
SaguAssert($ok,"Nao foi possivel inserir o registro!<br><br>$err");

$sql = " select desconto_mes " .
       " from origens " .
       " where id = ( select ref_origem " .
       "              from periodos " .
       "              where id='$periodo_id') ";

$query = $conn->CreateQuery($sql);
$query->MoveNext();
$desconto = $query->GetValue(1);
$query->Close();

$seq_titulo = 1;
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

//estes links sao para impressao de boleto do sicredi
$link = "<a href=\"/financeiro/post/gera_titulo_previsao.php3" .
        "?ref_contrato=$id_contrato" .
        "&ref_periodo=$periodo_id" .
        "&ref_curso=$curso_id" .
        "&ref_pessoa=$aluno_id" .
        "&seq_titulo=$seq_titulo" .
        "&dt_vencimento=$dt_vencimento" .
        "&dt_lcto=$dt_lcto" .
        "&desconto=$desconto" .
        "&cod_titulo=$cod_titulo" .
	"&boleto=yes\">Imprimir título<br>para Aluno</a>";

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel inserir o registro!<br><br>$err");
SuccessPage("Gravação de Matrícula","location='matricula_aluno_tecnico.phtml'","$link");
?>
</HEAD>
<BODY></BODY>
</HTML>
