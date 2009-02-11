<? header("Cache-Control: no-cache"); ?>
<? require("../../../lib/common.php"); ?>
<HTML>
<HEAD>
<TITLE>Processa Unificação de Disciplinas</TITLE>
</HEAD>
<BODY>
<?
   $conn = new Connection;
   $conn->open();
  
   $conn->Begin();

   $sql = " select ref_disciplina, " .
          "        ref_disciplina_equivalente " .
          " from disciplinas_equivalentes " .
          " order by ref_disciplina ";

   $query = $conn->CreateQuery($sql);
 
   while( $query->MoveNext() )
   {
      list($ref_disciplina,
           $ref_disciplina_equivalente) = $query->GetRowValues();

     //Cursos Disciplinas
       $update = " update cursos_disciplinas " .
                 " set ref_disciplina = $ref_disciplina " .
                 " where ref_disciplina = $ref_disciplina_equivalente";
       $ok = $conn->Execute($update); 
       SaguAssert($ok, "Não foi possível alterar o registro Cursos Disciplinas 1!");

       $update = " update cursos_disciplinas " .
                 " set equivalencia_disciplina = $ref_disciplina " .
                 " where equivalencia_disciplina = $ref_disciplina_equivalente";
       $ok = $conn->Execute($update); 
       SaguAssert($ok,"Não foi possível alterar o registro Cursos Disciplinas 2!");

     //Discisciplinas Oferecidas
       $update = " update disciplinas_ofer " .
                 " set ref_disciplina = $ref_disciplina " .
                 " where ref_disciplina = $ref_disciplina_equivalente";
       $ok = $conn->Execute($update); 
       SaguAssert($ok,"Não foi possível alterar o registro Discisciplinas Oferecidas!");

     //Matricula
       $update = " update matricula " .
                 " set ref_disciplina = $ref_disciplina " .
                 " where ref_disciplina = $ref_disciplina_equivalente";

       $ok = $conn->Execute($update); 
       SaguAssert($ok,"Não foi possível alterar o registro Matricula 1!");

       $update = " update matricula " .
                 " set ref_disciplina_subst = $ref_disciplina " .
                 " where ref_disciplina_subst = $ref_disciplina_equivalente";
       $ok = $conn->Execute($update); 
       SaguAssert($ok,"Não foi possível alterar o registro Matricula 2!");

     //Disciplinas de todos os Alunos 
       $update = " update disciplinas_todos_alunos ".
                 " set ref_disciplina = $ref_disciplina " .
                 " where ref_disciplina = $ref_disciplina_equivalente";

       $ok = $conn->Execute($update); 
       SaguAssert($ok,"Não foi possível alterar o registro Disciplinas de todos os Alunos!");

     //Pre-Requisitos
       $update = " update pre_requisitos " .
                 " set ref_disciplina = $ref_disciplina " .
                 " where ref_disciplina = $ref_disciplina_equivalente";
 
       $ok = $conn->Execute($update); 
       SaguAssert($ok,"Não foi possível alterar o registro Pre-Requisitos 1!");

       $update = " update pre_requisitos " .
                 " set ref_disciplina_pre = $ref_disciplina " .
                 " where ref_disciplina_pre = $ref_disciplina_equivalente";
 
       $ok = $conn->Execute($update); 
       SaguAssert($ok,"Não foi possível alterar o registro Pre-Requisitos 2!");

   }

   $conn->Finish();

   $query->Close();
   $conn->Close();

?>
</BODY>
</HTML>
