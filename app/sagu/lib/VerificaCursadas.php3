<script language="PHP">
//----------------------------------------------------------------------------
// Retorna o se a disciplina já foi cursada pelo aluno
// Paulo Roberto Mallmann - 03/06/2002
//----------------------------------------------------------------------------
Function VerificaCursadas($ref_disciplina_ofer, $ref_disciplina_ofer_ele, $ref_pessoa, $periodo_id, $status)
{
  $conn_verifica = new Connection;
  $conn_verifica->Open();
 
  $sql = " select ref_anterior from periodos where id = '$periodo_id'";

  $query_periodos = $conn_verifica->CreateQuery($sql);
  
  if ( @$query_periodos->MoveNext() )
  {
    $ref_periodo_anterior = $query_periodos->GetValue(1);
  }
  else
  {
      SaguAssert(0,"Código do período anterior não cadastrado para o periodo <b>$periodo_id</b>.");
  }

  $sql = " select A.ref_disciplina, " .
         "        A.ref_curso, " .
         "        A.ref_campus, " .
         "        B.dia_semana, " .
         "        B.turno, " .
         "        C.ref_professor " .
         " from disciplinas_ofer A, disciplinas_ofer_compl B, disciplinas_ofer_prof C " .
         " where A.id = B.ref_disciplina_ofer and " .
         "       A.id = C.ref_disciplina_ofer and " .
         "       B.id = C.ref_disciplina_compl and " .
         "       B.ref_disciplina_ofer = C.ref_disciplina_ofer and " .
         "       ((A.id = '$ref_disciplina_ofer') or (A.id = '$ref_disciplina_ofer_ele'))";
  
  $query_oferecidas = $conn_verifica->CreateQuery($sql);

  while ( $query_oferecidas->MoveNext() )                  // Dados da disciplina a se matricular
  {
     list ($aux_ref_disciplina,
           $aux_cod_curso,
           $aux_cod_campus,
           $aux_dia_semana,
           $aux_turno,
           $aux_ref_professor) = $query_oferecidas->GetRowValues();

      $sql  = " select ref_disciplina_ofer, " .
              "        ref_disciplina_subst, " .
              "        descricao_disciplina(ref_disciplina_subst), " .
              "        nota_final, " .
        	  "        conceito, " .
        	  "        ref_periodo " .
              " from matricula " .
              " where ref_pessoa = '$ref_pessoa' and " .
              "       dt_cancelamento is null and " .
              "       fl_liberado <> '1' and " . //Reprovado por excesso de faltas
    	      "       fl_liberado <> '2' and " . //Reprovado por desistencia
              "       ((nota_final >= get_media_final(ref_periodo)) or ";
             
              // Status passado por parâmetro - 0 -> Matrícula
              //                                1 -> Transações - Acres/Troca/Cancel
              if ($status == '0')
              { $sql .= " (nota_final = 0 and ref_periodo = '$ref_periodo_anterior') or "; }
              else
              { $sql .= " (nota_final = 0 and ref_periodo = '$periodo_id') or "; }
              
              $sql .= "   (conceito <> '') or " .
                      "   (fl_liberado = '3') or " .
                      "   (fl_liberado = '4') ) ";
      
      $query_verifica = $conn_verifica->CreateQuery($sql);

      while ( $query_verifica->MoveNext() )
      {
        list ($ref_disciplina_oferecida,
              $ref_disciplina_subst,
              $disciplina_subst,
              $nota_disciplina,
        	  $conceito,
              $ref_periodo) = $query_verifica->GetRowValues(); // Disciplinas Cursadas
     
            $sql = " select A.ref_disciplina, " .
	               "        descricao_disciplina(A.ref_disciplina), " .
    	           "        A.ref_campus, " .
        	       "        B.dia_semana, " .
        	       "        B.turno, " .
    	           "        C.ref_professor " .
    	           " from disciplinas_ofer A, disciplinas_ofer_compl B, disciplinas_ofer_prof C " .
                   " where A.id = B.ref_disciplina_ofer and " .
                   "       A.id = C.ref_disciplina_ofer and " .
                   "       B.id = C.ref_disciplina_compl and " .
                   "       B.ref_disciplina_ofer = C.ref_disciplina_ofer and " .
        	       "       A.id = '$ref_disciplina_oferecida'";
 
            $query_verifica2 = $conn_verifica->CreateQuery($sql);

            if ($query_verifica2->MoveNext())
	        {
              list ($aux_ref_disciplina1,       // Dados das disciplinas já cursadas
	                $aux_desc_disciplina1,
	                $aux_cod_campus1,
                    $aux_dia_semana1,
                    $aux_turno1,
                    $aux_ref_professor1) = $query_verifica2->GetRowValues();
	        }
            $query_verifica2->Close();

            // Está Matriculado na disciplina este semestre
            if (($nota_disciplina == '0') || (empty($nota_disciplina)))
            {
                if (($aux_ref_disciplina1 == $aux_ref_disciplina) && ($aux_cod_campus1 == $aux_cod_campus) && ($aux_dia_semana1 == $aux_dia_semana) && ($aux_turno1 == $aux_turno) && ($aux_ref_professor1 == $aux_ref_professor))
                {
                    SaguAssert(0,"Inconsistência: Na disciplina <b>$aux_ref_disciplina1 - $aux_desc_disciplina1 </b> o aluno já está matriculado este período.");
                }
            }
            else
            {
                // Já cursou a disciplina com nota ou conceito
                if ($aux_ref_disciplina == $aux_ref_disciplina1)
                {
                    SaguAssert(0,"Inconsistência: A disciplina <b>$aux_ref_disciplina1 - $aux_desc_disciplina1 </b>já foi cursada pelo aluno no período <b>$ref_periodo</b>.");
                }
                if ($aux_ref_disciplina == $ref_disciplina_subst)
                {
                    SaguAssert(0,"Inconsistência: A disciplina <b>$ref_disciplina_subst - $disciplina_subst </b>já foi cursada pelo aluno no período <b>$ref_periodo</b>. em substituição a disciplina <b>$aux_ref_disciplina1 - $aux_desc_disciplina1 </b>");
                }
            }
      }
  }
  $query_oferecidas->Close();

  $conn_verifica->Close();
}
</script>
