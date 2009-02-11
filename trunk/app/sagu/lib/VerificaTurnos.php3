<script language="PHP">
//--------------------------------------------------------------------------------------
// Retorna o número de créditos diurno se o curso for diurno. Caso contrário, retorna 0.
// Paulo Roberto Mallmann - 16/12/2002
// Variável $status: 1 - Acrescimo
//                   2 - Cancelamentos
//                   3 - Trocas
//--------------------------------------------------------------------------------------
function VerificaTurnos($ref_contrato, $ref_curso, $ref_periodo)
{
  $conn_verifica = new Connection;
  $conn_verifica->Open();
 
  $sql = " select ref_disciplina_ofer, " .
         "        ref_disciplina, ".
         "        ref_disciplina_subst, " .
         "        ref_curso_subst " .
         " from matricula " .
         " where ref_contrato = '$ref_contrato' and " .
         "       ref_periodo = '$ref_periodo' and " .
         "       dt_cancelamento is null ";

  $query_matricula = $conn_verifica->CreateQuery($sql);

  $x = 0;

  $code1 = null;
  $ofer1 = null;
  $code2 = null;
  $ofer2 = null;

  while ( $query_matricula->MoveNext() )
  {
    list($ref_disciplina_ofer,
         $ref_disciplina,
         $ref_disciplina_subst, 
         $ref_curso_subst) = $query_matricula->GetRowValues();

    if (($ref_disciplina_subst != 0) && ($ref_disciplina_subst != ''))
    {
        $ofer1[$x] = '';
        $code1[$x] = $ref_disciplina;
        $ofer2[$x] = $ref_disciplina_ofer;
        $code2[$x] = $ref_disciplina_subst;
    }
    else
    {
        $ofer1[$x] = $ref_disciplina_ofer;
        $code1[$x] = $ref_disciplina;
        $ofer2[$x] = '';
        $code2[$x] = '';
    }
 
    $x++;
  }       

  $query_matricula->Close();

  $cred_diurno = 0;
  $cred_noturno = 0;

  for ($x=0; $x<count($ofer1); $x++)
  {
    if ( $code2[$x] != '' )
    {
      $sql = " select turno_curso($ref_curso), " .
             "        get_creditos($code2[$x]), " .
             "        turno_disciplina_ofer($ofer2[$x]);";
    }
    else
    {
      $sql = " select turno_curso($ref_curso), " .
             "        get_creditos($code1[$x]), " .
             "        turno_disciplina_ofer($ofer1[$x]);";
    }
  
    $query = $conn_verifica->CreateQuery($sql);
  
    if ( $query->MoveNext() )
    {
        list ($turno_curso,
              $num_creditos,
              $turno_ofer) = $query->GetRowValues();

        if ($turno_ofer != 'N')
        {
            $cred_diurno += $num_creditos;
        }
        else
        {
            $cred_noturno += $num_creditos;
        }
    }
    $query->Close();

  } // for

  $conn_verifica->Close();

  $turno_creditos = $cred_diurno . '-' . $cred_noturno;
  
  return $turno_creditos;
}
</script>
