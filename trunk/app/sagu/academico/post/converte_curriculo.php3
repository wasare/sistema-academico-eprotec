<? header("Cache-Control: no-cache"); ?>
<? require("../../../../lib/common.php"); ?>
<HTML>
<HEAD>
<TITLE>Converte Currículo</TITLE>
<?

    CheckFormParameters(array("curso_antigo","curso_novo","opcao"));
    
    $conn = new Connection;
    $conn->Open();
    $conn->Begin();
   
    $sql = " select id, " .
           "        ref_pessoa, " .
           "        pessoa_nome(ref_pessoa), " .
           "        ref_curso, " .
           "        ref_campus " .
           " from contratos " .
           " where ref_curso = '$curso_antigo' ";
           
    if ($aluno_id)
    {
        $sql .= " and ref_pessoa = '$aluno_id'";
    }
    
    if ($opcao == '2')
    {
        $sql .= " and dt_desativacao is null";
    }

    $sql .= " order by pessoa_nome(ref_pessoa) ";
    
    $query = $conn->CreateQuery($sql);  
  
    $i = 1;
   
    while( $query->MoveNext() )
    {
       list($id_contrato,
            $ref_pessoa,
            $pessoa_nome,
            $ref_curso,
            $ref_campus ) = $query->GetRowValues();

        echo("<br><b>$ref_pessoa - $pessoa_nome</b><br>");
        
        $sql_busca = " select A.id, " .
                     "        A.ref_disciplina, " .
                     "        A.ref_disciplina_subst, " .
                     "        B.ref_disciplina_equivalente " .
                     " from matricula A, disciplinas_equivalentes B " .
                     " where A.ref_disciplina = B.ref_disciplina and " .
                     "       A.ref_curso = B.ref_curso and " .
                     "       A.ref_curso = '$ref_curso' and " .
                     "       A.ref_pessoa = '$ref_pessoa';";
      
        $query_busca = $conn->CreateQuery($sql_busca); 
  
        $ii = 1;
   
        while( $query_busca->MoveNext() )
        {
            list($id_matricula,
                 $ref_disciplina,
                 $ref_disciplina_subst,
                 $ref_disciplina_equivalente) = $query_busca->GetRowValues();

            echo("<b>$ii</b> -> Disciplina: $ref_disciplina - Substituída: $ref_disciplina_subst - Equivalente: $ref_disciplina_equivalente<br>");
     
            if ( $ref_disciplina_subst )
            {
                $sql_atualiza = " update matricula set " .
                                "       ref_curso = '$curso_novo', " .
                                "       ref_disciplina = '$ref_disciplina_equivalente' " .
                                " where id = '$id_matricula'";
            }
            else
            {
                $sql_atualiza = " update matricula set " .
                                "       ref_curso = '$curso_novo', " .
                                "       ref_disciplina = '$ref_disciplina_equivalente', " .
                                "       ref_disciplina_subst = ref_disciplina " .
                                " where id = '$id_matricula'";
            }

            $ok = $conn->Execute($sql_atualiza);

            if (!$ok)
                break 2;  // Sai do primeiro e do segundo while

            flush();
            
            $ii++;
        }

        $query_busca->Close();

        $sql_matricula = " update matricula set " .
                         "    ref_curso = '$curso_novo' " .
                         " where ref_contrato = '$id_contrato' and " .
                         "       ref_curso = '$curso_antigo' and " .
                         "       ref_pessoa = '$ref_pessoa'";
        
        $ok = $conn->Execute($sql_matricula);
       
        if ( !$ok )
            break 1; // Sai do segundo while
            
        $sql_contrato = " update contratos set " .
                        "     ref_curso = '$curso_novo' " .
                        " where id = '$id_contrato'";
        
        $ok = $conn->Execute($sql_contrato);

        if ( !$ok )
            break 1; // Sai do segundo while
        
        flush();
        
        $i++;
        
    }
      
    $query->Close();
    
    $conn->Finish();
    $conn->Close();
    
    SaguAssert($ok,"Conversão de currículo falhou!!!");

    SuccessPage("Sucesso",
                "javascript:history.go(-1)",
                "Processo executado com sucesso!!!");
				    
?>
</HEAD>
<BODY></BODY>
</HTML>
