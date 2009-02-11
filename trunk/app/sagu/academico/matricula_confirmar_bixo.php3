<? require("../lib/common_not_login.php3"); ?>
<? require("../lib/InvData.php3"); ?>

<html>
<head>
<script language="Javascript">
function PedeConfirmacao()
{
  if (confirm('Você conferiu se selecionou as disciplinas que realmente deseja cursar? \nEsta operação não poderá ser refeita...\nPara finalizar a matrícula, clique OK, senão Cancel.'))
  {
          document.myform.submit();
  }
  else
  {
     alert("Matricula Cancelada... \nClique no botão Voltar para selecionar \nas disciplinas corretas!!! ");
  }
}
</script>

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

function Mostra_Matricula($periodo_id, $ofer1, $code1, $ofer2, $code2, $conn)
{

  echo("<center><table width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

  echo("<tr>");
  echo("    <td height=\"32\" colspan=\"5\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\"><font color=\"#000000\">Início :: Selecione Contrato :: Selecione Disciplinas :: <b>Confirmação</b> :: Finalização</font></font>");
  echo("    </td>");
  echo("</tr>");
              
  echo("<tr bgcolor=\"#000099\">\n");
  echo ("<td height=\"32\" colspan=\"5\" align=\"center\"><Font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"3\" color=\"#CCCCFF\"><b>Disciplinas Selecionadas para $periodo_id</b></td>");
  echo("  </tr>"); 


  echo("<tr bgcolor=\"#000000\">\n");
  echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód.</b></font></td>");
  echo ("<td width=\"65%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
  echo ("<td width=\"9%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Dia da Semana</b></font></td>");
  echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Turno</b></font></td>");
  echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Horário</b></font></td>");
  echo("  </tr>"); 

  $i=1;

  for ($x=0; $x<count($ofer1); $x++)
  {
 
  if ($ofer1[$x])
  {
    $ref_disciplina_ofer = $ofer1[$x];
  }
  else
  {
    $ref_disciplina_ofer = $ofer2[$x];
  }

  $sql = " select descricao_disciplina('$code1[$x]'),".
         "        descricao_disciplina('$code2[$x]'),".
         "        get_dia_semana(dia_disciplina_ofer_todos(id)), " .
         "        turno_disciplina_ofer_todos(id), " .
         "        get_horarios_todos(id), " .
         "        professor_disciplina_ofer_todos(id), " .
         "        ref_campus " .
         " from disciplinas_ofer " .
         " where id = '$ref_disciplina_ofer' ".
         " order by dia_disciplina_ofer_todos(id); ";
 
  $query = $conn->CreateQuery($sql);

  while( $query->MoveNext() )
  {
  list ( $desc_disciplina,
         $desc_disciplina_subst,
         $dia_semana,
         $turno,
    	 $horario,
         $professor,
    	 $campus_id) = $query->GetRowValues();
    
    if ($desc_disciplina_subst)
    {
        $desc_disciplina = $desc_disciplina . "( " . $code2[$x] . " - "  .$desc_disciplina_subst . ")";
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

    echo("<tr bgcolor=\"$bg\">\n");
    echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$code1[$x]</td>");
    echo ("<td width=\"55%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg\">");
    if ($campus_id != '1')
    {  echo("<img src=\"../images/checkoff.gif\" alt=\"fora sede\">");  }
    else
    {  echo("<img src=\"../images/checkon.gif\" alt=\"na da sede\">");  }

    echo("$desc_disciplina <i>($professor)</i></td>");
    echo ("<td width=\"9%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$dia_semana</td>");
    echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$turno</td>");
    echo ("<td width=\"18%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$horario&nbsp;</td>");
    echo("  </tr>");

    $i++;

  }  // while
  }  // for
  echo("<tr>"); 
  echo ("<td colspan=\"5\" align=\"center\"><hr></td>");
  echo("</tr>"); 
  
  echo("</table></center>\n");
}

function Mostra_Parcelas($periodo_id, $curso_id, $aluno_id, $id_contrato, $ref_campus, $ofer1, $code1, $ofer2, $code2, $num_parcelas, $status_contrato, $aluno_nome, $conn)
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
//  ofer1[] = códigos das oferecidas primárias
//  code1[] = códigos das disciplinas primárias
//  ofer2[] = códigos das oferecidas secundárias
//  code2[] = códigos das disciplinas secundárias

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

  SaguAssert($ofer1[$i] || $ofer2[$i],"Inconsistência 1: Disciplina oferecida não definida!!!");
  SaguAssert(!$ofer1[$i] || $code1[$i],"Inconsistência 2: Disciplina oferecida não definida!!!");
  SaguAssert(!$ofer2[$i] || $code2[$i],"Inconsistência 3: Disciplina oferecida não definida!!!");

  // se temos uma disciplina secundária, vamos usar esta para a matricular
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

echo("<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" align=\"center\" width=\"90%\">");
echo("  <tr>");
echo("     <td height=\"32\" bgcolor=\"#000099\" colspan=\"5\" align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"3\" color=\"#CCCCFF\"><b>Previsões de Lançamento (mensalidades) de $aluno_nome para $periodo_id</font></td>");
echo("  </tr>");
echo("  <tr bgcolor=\"#000000\">");
echo("     <td colspan=\"2\" width=\"50%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\">&nbsp;<b>Historico</b></font></td>");
echo("     <td width=\"10%\" align=\"center\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\">&nbsp;<b>M&ecirc;s</b></font></td>"); 
echo("     <td width=\"15%\" align=\"center\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\">&nbsp;<b>Vencimento</b></font></td>");
echo("     <td width=\"25%\" align=\"right\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\">&nbsp;<b>Valor (R$)</b></font></td>");
echo("  </tr>");

$Saldo = 0;

$month = date("n");

if ($month > 6)
{
    if ($num_parcelas == 6)
    {
        $inicio = 7;
        $fim = 12;
    }
    else
    {
        $inicio = 8;
        $fim = 12;
    }
}
else
{
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
}

for ($x=$inicio; $x<=$fim; $x++)
{
    if ($month > 6)
        $seq = $x - 6;
    else
        $seq = $x;
    
    if ($x==$inicio)
    {
        $date = date("d-m-Y");
    }
    else
    {
        $mes = str_pad("$x", "2", "0", STR_PAD_LEFT); 
        $date = date("10-$mes-Y");
    }
    // Valor da Mensalidade
    $sql = " select id, " .
         "        descricao, " .
         "        operacao " .
         " from historicos " .
         " where id = '$ref_historico'" ; 
    
    $query = $conn->CreateQuery($sql);
  
    if ( $query->MoveNext() )
    {
      list($codigo_do_historico,
           $historico_desc,
           $operacao) = $query->GetRowValues();
    
      if ($operacao=='C')
      {
          $Saldo -= $ValorTotalNovo;
          $cor='#000099';
      }
      else
      {
          $Saldo += $ValorTotalNovo;
          $cor='#ff0033';
      }

      $ValorTotalNovo = $ValorTotalNovo*100;
      $ValorTotalNovo = round($ValorTotalNovo);
      settype($ValorTotalNovo, "integer");
      $ValorTotalNovo = $ValorTotalNovo/100;
      $ValorTotalNovo = sprintf("%.2f", $ValorTotalNovo);
      
      echo("<tr bgcolor=\"#EEEEFF\">");
      echo("    <td colspan=\"2\" width=\"50%\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$codigo_do_historico - $historico_desc</font></td>");
      echo("    <td width=\"10%\" align=\"center\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$seq</font></td>");
      echo("    <td width=\"15%\" align=\"center\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$date</font></td>");
      echo("<td width=\"25%\" align=\"right\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$ValorTotalNovo</font></td>");
      echo("</tr>\n");
    
    }
    
    $query->Close();

    // Taxa do DCE
    if ($x==$inicio)
    {
        $sql = " select id, " .
               "        descricao, " .
               "        operacao " .
               " from historicos " .
               " where id = '$ref_historico_dce'" ; 
    
        $query = $conn->CreateQuery($sql);
  
        if ( $query->MoveNext() )
        {
          list($codigo_do_historico,
               $historico_desc,
               $operacao) = $query->GetRowValues();
        
          $ValorDce = ($novo_preco_credito * $Percentual_Dce)/100;
          
          if ($operacao=='C')
          {
              $Saldo -= $ValorDce;
              $cor='#000099';
          }
          else
          {
              $Saldo += $ValorDce;
              $cor='#ff0033';
          }
      
          $ValorDce = $ValorDce*100;
          $ValorDce = round($ValorDce);
          settype($ValorDce, "integer");
          $ValorDce = $ValorDce/100;
          $ValorDce = sprintf("%.2f", $ValorDce);
          
          if (($ValorDce > 0) && ($ref_historico_dce))
          {
              echo("<tr bgcolor=\"#EEEEFF\">");
              echo("    <td colspan=\"2\" width=\"50%\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$codigo_do_historico - $historico_desc</font></td>");
              echo("    <td width=\"10%\" align=\"center\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$seq</font></td>");
              echo("    <td width=\"15%\" align=\"center\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$date</font></td>");
              echo("<td width=\"25%\" align=\"right\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$ValorDce</font></td>");
              echo("</tr>\n");
          }
        }
    
        $query->Close();
    }
    
    // Valor taxa DA
    if (($ref_historico_da) && ($tx_da > 0))
    {
        $sql = " select id, " .
               "        descricao, " .
               "        operacao " .
               " from historicos " .
               " where id = '$ref_historico_da'" ; 
    
        $query = $conn->CreateQuery($sql);
  
        if ( $query->MoveNext() )
        {
           list($codigo_do_historico,
                $historico_desc,
                $operacao) = $query->GetRowValues();
    
           if ($operacao=='C')
           {
               $Saldo -= $tx_da;
               $cor='#000099';
           }
           else
           {
               $Saldo += $tx_da;
               $cor='#ff0033';
           }
           
           $tx_da = $tx_da*100;
           $tx_da = round($tx_da);
           settype($tx_da, "integer");
           $tx_da = $tx_da/100;
           $tx_da = sprintf("%.2f", $tx_da);
           
           echo("<tr bgcolor=\"#EEEEFF\">");
           echo("    <td colspan=\"2\" width=\"50%\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$codigo_do_historico - $historico_desc</font></td>");
           echo("    <td width=\"10%\" align=\"center\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$seq</font></td>");
           echo("    <td width=\"15%\" align=\"center\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$date</font></td>");
           echo("<td width=\"25%\" align=\"right\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$tx_da</font></td>");
           echo("</tr>\n");
        }
        
        $query->Close();
    } 

    $iTotalHistDesc = count($ahist_desc_campus);

    if ($iTotalHistDesc > 0)
    {
        $ref_historico_campus = $ahist_desc_campus[0];
        $ValordoHistorico = arr_sum($avalor_desc_campus);
    
        settype($ValordoHistorico, "double");
        
        $sql = " select id, " .
               "        descricao, " .
               "        operacao " .
               " from historicos " .
               " where id = '$ref_historico_campus'" ; 
    
        $query = $conn->CreateQuery($sql);
  
        if ( $query->MoveNext() )
        {
           list($codigo_do_historico,
                $historico_desc,
                $operacao) = $query->GetRowValues();
    
           if ($operacao=='C')
           {
               $Saldo -= $ValordoHistorico;
               $cor='#000099';
           }
           else
           {
               $Saldo += $ValordoHistorico;
               $cor='#ff0033';
           }
           
           $ValordoHistorico = $ValordoHistorico*100;
           $ValordoHistorico = round($ValordoHistorico);
           settype($ValordoHistorico, "integer");
           $ValordoHistorico = $ValordoHistorico/100;
           $ValordoHistorico = sprintf("%.2f", $ValordoHistorico);
           
           echo("<tr bgcolor=\"#EEEEFF\">");
           echo("    <td colspan=\"2\" width=\"50%\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$codigo_do_historico - $historico_desc</font></td>");
           echo("    <td width=\"10%\" align=\"center\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$seq</font></td>");
           echo("    <td width=\"15%\" align=\"center\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$date</font></td>");
           echo("<td width=\"25%\" align=\"right\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$ValordoHistorico</font></td>");
           echo("</tr>\n");
        }
        
        $query->Close();
    }

    if ($fl_incentivo)
    {
        $iTotalIncentivo = count($aIncentivoPercentual);

        for ( $n=0; $n<$iTotalIncentivo; $n++ )
        {
            $ref_historico_incentivo = $aIncentivoHistorico[$n];
            $percentual = $aIncentivoPercentual[$n];
            $ValorDecrescer = (($percentual /100) * $ValordoHistorico);
            $ValorIncentivo = (($percentual /100) * $ValorTotalNovo) - $ValorDecrescer;
            $sql = " select id, " .
                   "        descricao, " .
                   "        operacao " .
                   " from historicos " .
                   " where id = '$ref_historico_incentivo'" ; 
    
            $query = $conn->CreateQuery($sql);
  
            if ( $query->MoveNext() )
            {
               list($codigo_do_historico,
                    $historico_desc,
                    $operacao) = $query->GetRowValues();
    
               if ($operacao=='C')
               {
                   $Saldo -= $ValorIncentivo;
                   $cor='#000099';
               }
               else
               {
                   $Saldo += $ValorIncentivo;
                   $cor='#ff0033';
               }
               
               $ValorIncentivo = $ValorIncentivo*100;
               $ValorIncentivo = round($ValorIncentivo);
               settype($ValorIncentivo, "integer");
               $ValorIncentivo = $ValorIncentivo/100;
               $ValorIncentivo = sprintf("%.2f", $ValorIncentivo);

               echo("<tr bgcolor=\"#EEEEFF\">");
               echo("    <td colspan=\"2\" width=\"50%\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$codigo_do_historico - $historico_desc</font></td>");
               echo("    <td width=\"10%\" align=\"center\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$seq</font></td>");
               echo("    <td width=\"15%\" align=\"center\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$date</font></td>");
               echo("<td width=\"25%\" align=\"right\"><font face=\"Verdana\" size=\"2\" color=\"$cor\">$ValorIncentivo</font></td>");
               echo("</tr>\n");
            }
        
            $query->Close();
        }
    }
    $Saldo = $Saldo*100;
    $Saldo = round($Saldo);
    settype($Saldo, "integer");
    $Saldo = $Saldo/100;
    $Saldo = sprintf("%.2f", $Saldo);
    
    echo("<tr>");
    echo("<td colspan=\"4\" bgcolor=\"#c0c0c0\" height=\"10\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>Saldo</b></font></td>");
    echo("<td align=\"right\" bgcolor=\"#c0c0c0\" height=\"10\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>$Saldo</b> </font></td>");
    echo("</tr>\n");
    $Saldo = 0;
}    
echo("<tr>"); 
echo ("<td colspan=\"5\" align=\"center\"><hr></td>");
echo("</tr>"); 

echo("</table>");

}
</script>

<script language="PHP">

// Variáveis da Matrícula
// $code1[]; $code2[]; $ofer1[]; $ofer2[];

// Quando tem ref_disciplina_subst....
// $ofer1 vem vazio ()                       $code1 tem valor (ref_disciplina)
// $ofer2 tem valor (ref_disciplina_ofer)    $code2 tem valor (ref_disciplina_subst)

// Quando não tem disciplina_subst....
// $ofer1 tem valor (ref_disciplina_ofer)    $code1 tem valor (ref_disciplina)
// $ofer2 vem vazio ()                       $code2 vem vazio ()

CheckFormParameters(array("periodo_id",
                          "aluno_id",
                          "curso_id",
                          "id_contrato",
                          "ref_campus",
                          "num_parcelas",
                          "status_contrato",
                          "num_creditos_min",
                          "fl_limite_turno"));

$conn = new Connection;
$conn->Open();
$conn->Begin();

$code1 = null;
$ofer1 = null;
$code2 = null;
$ofer2 = null;
$dia   = null;
$turno = null;
$status_disc = null;
// O status que vale no status da disciplina é o da 
// disciplina do currículo e não da substituída

// MONTA O VETOR DE DISCIPLINAS PRIMÁRIAS...
$sel = 0;
for ($x=0; $x<count($ofer1_code1); $x++)
{
  $check1 = 'check1_' . $x;
  if ($$check1)
  {
    list ( $ofer1[$sel], 
           $code1[$sel], 
           $status_disc[$sel], 
           $dia[$sel], 
           $turno[$sel],
           $code2[$sel]) = split('_', $ofer1_code1[$x]);

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

// MONTA O VETOR DE DISCIPLINAS SECUNDÁRIAS...
for ($x=0; $x<count($ofer2_ele); $x++)
{
  $check2 = 'check2_' . $x;
  
  if ((($ofer2_ele[$x]) || ($code2_ele[$x])) && (!$$check2))
  {
    SaguAssert(0, "Marque o checkbox da respectiva disciplina <br>eletiva no formulário anterior!!!");
  }
  if ($$check2)
  {
    $ofer1[$sel]       = '';
    $code1[$sel]       = $code1_ele[$x];
    $ofer2[$sel]       = $ofer2_ele[$x];
    $code2[$sel]       = $code2_ele[$x];
    $dia[$sel]         = $dia_ele[$x];
    $turno[$sel]       = $turno_ele[$x];
    $status_disc[$sel] = 'f';
   
    if ((!$ofer2[$sel]) || (!$code2[$sel]) || (!$code1[$sel]))
    {
    SaguAssert(0, "Selecione a disciplina eletiva que você quer se matricular <br>clicando no respectivo botão <input type=\"button\" value=\"...\"> no formulário anterior!!!");
    }
    $sel++;
  }
}

SaguAssert(count($ofer1)>0,"É preciso selecionar pelo menos uma disciplina!!!");

// Verifica se a disciplina foi selecionada mais de uma vez...
$j = count($code1);
$k = 0;
$l = 0;
for ( $i=0; $i<$j-1; $i++ )
{
  for ( $n=$i+1; $n<$j; $n++ )
  {
    if (($code1[$i] == $code1[$n]) && ($code1[$i] !=''))
    {
      SaguAssert(0, "A disciplina de código <b>$code1[$i]</b> foi selecionada mais de uma vez!!!");
    }

    if (($code2[$i] == $code2[$n]) && ($code2[$i] !=''))
    {
      SaguAssert(0, "A disciplina de código <b>$code2[$i]</b> foi selecionada mais de uma vez!!!");
    }
    
    if (($code1[$i] == $code2[$n]) && ($code2[$n] !=''))
    {
      SaguAssert(0, "A disciplina de código <b>$code1[$i]</b> foi selecionada mais de uma vez!!!");
    }
  }
}

// Verifica choque de horários e períodos.
for ($sel=0; $sel<count($ofer1); $sel++)
{
    if ($ofer1[$sel])
    {
    $sql = " select dia_disciplina_ofer_todos('$ofer1[$sel]'), " .
           "        turno_disciplina_ofer_todos('$ofer1[$sel]'), " .
           "        get_horarios_todos('$ofer1[$sel]')," .
           "        get_periodos_todos('$ofer1[$sel]')";
    }
    else
    {
    $sql = " select dia_disciplina_ofer_todos('$ofer2[$sel]'), " .
           "        turno_disciplina_ofer_todos('$ofer2[$sel]'), " .
           "        get_horarios_todos('$ofer2[$sel]')," .
           "        get_periodos_todos('$ofer2[$sel]')";

    
    }

    $query = $conn->CreateQuery($sql);
    
    $query->MoveNext();
    
    list ($dia[$sel],
          $turno[$sel],
          $horario[$sel],
          $periodo[$sel]) = $query->GetRowValues();
   
    $dias     = explode('/',$dia[$sel]);
    $turnos   = explode('/',$turno[$sel]);
    $horarios = explode('/',$horario[$sel]);
    $periodos = explode('/',$periodo[$sel]);
    
    for ($x=0; $x<count($dias); $x++)
    {
        list ($hora_ini[$x],
              $hora_fim[$x]) = split(' às ', $horarios[$x]);

        list ($periodo_ini[$x],
              $periodo_fim[$x]) = split(' até ', $periodos[$x]);
     
        $array[$dias[$x]  . "_" . $turnos[$x] . "_" . $hora_ini[$x] . "_" . $periodo_ini[$x]][] = $hora_fim[$x] . "_" . $periodo_fim[$x];
    }
    
    $query->Close();
}

ksort($array);

// Consistência por disciplinas em horários
foreach($array as $index => $array_hora_periodo_fim)
{
    list ($dias,
          $turnos,
          $hora_ini,
          $periodo_ini) = split('_', $index);
          
    list ($hora_fim,
          $periodo_fim) = split('_', $array_hora_periodo_fim[0]);

    // Se a disciplina é no mesmo dia/turno/hora_inicial
    // O array (array_hora_fim) se chama assim porque contém a hora final da disciplina
    // que ocorre no mesmo dia/turno/hora_inicial
    if (count($array_hora_periodo_fim)>1)
    {
        $sql = "select nome from dias where id = '$dias'";
        $query = $conn->CreateQuery($sql);
        $query->MoveNext();
        $dia_semana = $query->GetValue(1);
        $query->Close();
       
        $sql = "select nome from turnos where id = '$turnos'";
        $query = $conn->CreateQuery($sql);
        $query->MoveNext();
        $turno = $query->GetValue(1);
        $query->Close();

        SaguAssert(0, "Você selecionou disciplinas no mesmo horário no dia <b>$dia_semana</b>, turno <b>$turno</b>*.");
    }
    
    // Se a disciplina é no mesmo dia/turno
    if (($dias == $dias1) && ($turnos == $turnos1))
    {
       // Teste do horário
       list($hora, $min) = split(':', $hora_ini);
       $hora_ini = mktime($hora,$min);
       
       list($hora1, $min1) = split(':', $hora_fim1);
       $hora_fim1 = mktime($hora1,$min1);
       
       $ctrl_horario = $hora_ini - $hora_fim1; 
       if ($ctrl_horario < 0)
       {
            // Teste do período
            list($ano, $mes, $dia) = split('-', $periodo_ini);
            $periodo_ini = mktime(0,0,0,$mes,$dia);

            list($ano1, $mes1, $dia1) = split('-', $periodo_fim1);
            $periodo_fim1 = mktime(0,0,0,$mes1,$dia1);

            $ctrl_periodo = $periodo_ini - $periodo_fim1; 
            if ($ctrl_periodo < 0)
            {
                $sql = "select nome from dias where id = '$dias'";
                $query = $conn->CreateQuery($sql);
                $query->MoveNext();
                $dia_semana = $query->GetValue(1);
                $query->Close();
       
                $sql = "select nome from turnos where id = '$turnos'";
                $query = $conn->CreateQuery($sql);
                $query->MoveNext();
                $turno = $query->GetValue(1);
                $query->Close();
        
                SaguAssert(0, "Você selecionou disciplinas no mesmo horário no dia <b>$dia_semana</b>, turno <b>$turno</b> **.");
            }
       }

    }
    list ($dias1,
          $turnos1,
          $hora_ini1,
          $periodo_ini1) = split('_', $index);
          
    list ($hora_fim1,
          $periodo_fim1) = split('_', $array_hora_fim[0]);
}

// Verifica limite de 16 créditos.
$cred_diurno = 0;
$cred_noturno = 0;
$cred_bonus = 0;

for ($x=0; $x<count($ofer1); $x++)
{
  if ( $code2[$x] != '' )
  {
    $ref_disciplina      = $code2[$x];
    $ref_disciplina_ofer = $ofer2[$x];
  }
  else
  {
    $ref_disciplina      = $code1[$x];
    $ref_disciplina_ofer = $ofer1[$x];
  }
 
  // Hard Code do Curso de Direito Manhã (3062)
  // Eles poderão fazer uma disciplina a noite sem ter se matriculado em 16 créditos na manhã.
  if (($code1[$x] == 3062) && ($curso_id == 300))
  {
     $cred_noturno = $cred_noturno - 4;
     $cred_bonus   = $cred_bonus + 4;
  }
  
  $sql = " select turno_curso('$curso_id'), " .
         "        get_creditos('$ref_disciplina'), " .
         "        turno, " .
         "        num_creditos_desconto " .
         " from disciplinas_ofer_compl " .
         " where ref_disciplina_ofer = '$ref_disciplina_ofer'";

  $query = $conn->CreateQuery($sql);
  
  while ( $query->MoveNext() )
  {
    list ($turno_curso,
          $num_creditos,
          $turno_ofer,
          $num_creditos_compl) = $query->GetRowValues();

    if ((float) $num_creditos_compl)
    {
        $num_creditos = $num_creditos_compl;
    }
    
    if ($turno_ofer != 'N')
    {
       $cred_diurno += $num_creditos;
    }
    else
    {
       $cred_noturno += $num_creditos;
    }
  }

} // for

$total_creditos = $cred_diurno + $cred_noturno + $cred_bonus;

$query->Close();

// Alunos do diurno deve, estar matriculados em pelo menos 16 créditos
// diúrnos para poderem se matricular em disciplinas noturno.
if ($fl_limite_turno == 'f')  // Sem autorização
{
    if ( ($turno_curso != 'N') && ($cred_noturno > 0) )
    {
      // Hard Code para os cursos 331 e 250...
      if (($cred_diurno < 16) && ($curso_id != 331) && ($curso_id != 250))
      {
          SaguAssert(0, "Aluno de curso diurno matriculado em <b>$cred_noturno</b> créditos noturnos e não matriculado em pelo menos <b>16</b> créditos no diurno");
      }
    }
}

// Teste das disciplinas que podem ser cursadas uma pela outra...
for ($x=0; $x<count($ofer1); $x++)
{
    // A consistência é feita pela disciplina do currículo...
    $sql = " select cursa_outra_disciplina, " .
           "        fl_soma_curriculo, " .
           "        ref_disciplina " .
           " from cursos_disciplinas " .
           " where ref_curso = '$curso_id' and " .
           "       ref_campus = '$ref_campus' and " .
           "       ref_disciplina = '$code1[$x]' and " .
           "       cursa_outra_disciplina = '1';";

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
    {
        list ($cursa_outra_disciplina, 
              $fl_soma_curriculo,
              $ref_disciplina) = $query->GetRowValues();

        if ($fl_soma_curriculo == 't')
        {
            $sql_disc = " select distinct " .
                        "        ref_disciplina_equivalente " .
                        " from cursos_disciplinas_compl " .
                        " where ref_curso = '$curso_id' and  " .
                        "       ref_campus = '$ref_campus' and " .
                        "       ref_disciplina = '$ref_disciplina';";
        }
        else
        {
            $sql_disc = " select distinct " .
                        "        ref_disciplina " .
                        " from cursos_disciplinas_compl " .
                        " where ref_curso = '$curso_id' and  " .
                        "       ref_campus = '$ref_campus' and " .
                        "       ref_disciplina_equivalente = '$ref_disciplina' " .
                        " UNION " .
                        " select distinct " .
                        "        ref_disciplina_equivalente " .
                        " from cursos_disciplinas_compl " .
                        " where ref_curso = '$curso_id' and  " .
                        "       ref_campus = '$ref_campus' and " .
                        "       ref_disciplina_equivalente <> '$ref_disciplina' " .
                        " order by 1; ";

        }
   
        $query_disc = $conn->CreateQuery($sql_disc);
        
        while ( $query_disc->MoveNext() )
        {
            list ($ref_disciplina_equiv) = $query_disc->GetRowValues();

            if (in_array($ref_disciplina_equiv, $code1))
            {
                SaguAssert(0, "Você selecionou as disciplinas <b>$ref_disciplina</b> e a <b>$ref_disciplina_equiv</b>. Você não deve fazer as duas. Escolha somente uma delas.");
            }
        }
    }
}

// Se o aluno se matriculou em menos créditos do que o limite de 8 créditos.
if ($total_creditos < $num_creditos_min)
{
    SaguAssert(0, "Você deve se matricular em pelo menos <b>$num_creditos_min</b> créditos. As disciplinas que você selecionou totalizam somente <b>$total_creditos</b> créditos.");
}

Mostra_Matricula($periodo_id, $ofer1, $code1, $ofer2, $code2, $conn);

Mostra_Parcelas($periodo_id, $curso_id, $aluno_id, $id_contrato, $ref_campus, $ofer1, $code1, $ofer2, $code2, $num_parcelas, $status_contrato, $aluno_nome, $conn);

$conn->Finish();
$conn->Close();

</script>
</head>
<body bgcolor="#FFFFFF">
<br>
<form name="myform" method="post" action="matricula_inserir_bixo.php3">
  <center>    
    <input type="hidden" name="periodo_id" value="<?echo($periodo_id);?>">
    <input type="hidden" name="curso_id" value="<?echo($curso_id);?>">
    <input type="hidden" name="curso_nome" value="<?echo($curso_nome);?>">
    <input type="hidden" name="aluno_id" value="<?echo($aluno_id);?>">
    <input type="hidden" name="aluno_nome" value="<?echo($aluno_nome);?>">
    <input type="hidden" name="id_contrato" value="<?echo($id_contrato);?>">
    <input type="hidden" name="ref_campus" value="<?echo($ref_campus);?>">
    <input type="hidden" name="num_parcelas" value="<?echo($num_parcelas);?>">
    <input type="hidden" name="status_contrato" value="<?echo($status_contrato);?>">
    <script language="PHP">
    for ( $i=0; $i<count($ofer1); $i++ )  //Início FOR
    {
    </script>
        <input type="hidden" name="ofer1[]" value="<?echo($ofer1[$i]);?>">
        <input type="hidden" name="code1[]" value="<?echo($code1[$i]);?>">
        <input type="hidden" name="ofer2[]" value="<?echo($ofer2[$i]);?>">
        <input type="hidden" name="code2[]" value="<?echo($code2[$i]);?>">
        <input type="hidden" name="status_disc[]" value="<?echo($status_disc[$i]);?>">
    <script language="PHP">
    }
    </script>
                    
    <input type="button" value=" << Voltar " name="Button1" onClick="javascript:history.go(-1)">
    <input type="button" value=" Finalizar " name="Submit" onClick="PedeConfirmacao()">
  </center>
</form>
</body>
</HTML>
