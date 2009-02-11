<?
  function GetPreRequisito($ref_disciplina, $ref_curso)
  {
    
    $con = new Connection;

    $con->Open();

    $sql = " select ref_disciplina_pre " .
           " from pre_requisitos " .
	   " where ref_disciplina = '$ref_disciplina' and " .
	   "       ref_curso = '$ref_curso' and " .
	   "       ref_disciplina_pre <> '';";

    $query1 = @$con->CreateQuery($sql);
    
    $n = $query1->GetRowCount();
    $count = 1;
 
    while ( $query1->MoveNext() )
    {
      list ( $ref_disciplina_pre) = $query1->GetRowValues();
	       
      $obj .= $ref_disciplina_pre . ",";
	    
     $count++;
    }
    
    $query1->Close();

    $con->Close();

    return $obj;
  }
