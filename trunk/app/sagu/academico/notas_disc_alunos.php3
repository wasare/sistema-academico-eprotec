<?php 
   require("../../../lib/common.php"); 
?>
<html>
<head>
<title>Cadastro de Notas</title>
</head>
<body bgcolor="#FFFFFF">
<?php

    CheckFormParameters(array('curso_id','periodo_id'));

    SaguAssert(count($matricula)>0,"Campos obrigatórios id matricula não fornecidos!");
    SaguAssert(count($nota)>0,"Campos obrigatórios notas não fornecidos!");
    SaguAssert(count($faltas)>0,"Campos obrigatórios faltas não fornecidos!");
    SaguAssert(count($ref_pessoa)>0,"Campos obrigatórios código aluno não fornecidos!");
    SaguAssert(count($pessoa_nome)>0,"Campos obrigatórios nome aluno não fornecidos!");

    $conn = new Connection;

    $conn->Open();

    $conn->Begin();

    for ( $i=0; $i<count($nota); $i++ )
    {
      $nota[$i] = str_replace(',','.', $nota[$i]);

      $fl_liberado = '';
      
      if (${'desist_' . $i} != '')
      {  
	    $fl_liberado = '2';
      }
      else
      {
      //$faltas[$i] && 
      
      if ($nota[$i] == '') 
      { 
         $nota[$i] = 'NULL';
      }
         
        SaguAssert($nota[$i], "Faltou digitar a nota ou as faltas de <b>$ref_pessoa[$i] - $pessoa_nome[$i]</b>");
        /*
        // Se a frequencia do aluno não chega a 75%
        if (($faltas[$i]) < (($frequencia_maxima * 75) / 100) )
        {
            $fl_liberado = '1';
        }
        */
         // Se a frequencia do aluno não chega a 75%
         if (($faltas[$i]) > (($frequencia_maxima * 75) / 100) )
         {
            $fl_liberado = '1';
         }
        
      }

      $sql = " update matricula set " . 
             "      nota_final = $nota[$i], " .
             "      num_faltas = '$faltas[$i]', " .
             "      fl_liberado = '$fl_liberado' " .
             " where id = $matricula[$i];";

      $ok = $conn->Execute($sql);
  
      if ( !$ok )
        break;
    }

    $conn->Finish();

    $conn->Close();

    SaguAssert($ok,"Inclusão das notas falhou!");

    $url = "notas_disc.phtml" .
           "?periodo_id=" . urlencode($periodo_id) .
           "&curso_id="   . urlencode($curso_id);

    SuccessPage("Cadastramento de Notas",$url);
?>
</body>
</html>
