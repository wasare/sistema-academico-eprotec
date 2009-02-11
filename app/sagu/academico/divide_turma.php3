<? require("../../../lib/common.php"); ?>
<html>
<head>
<title>Divisão de Turmas</title>
<?
Function Divide($id, $numero, $num_turmas)
{
    $conn = new Connection;
    $conn->Open();
    $conn->Begin();

    $num 	= ($numero / $num_turmas);
    $resto 	= ($numero % $num_turmas);

    if($resto)
        $num = intval($num)+1;	// numero de alunos por turma (97/3=32,33) => Turmas de 33 alunos
    else
        $num = intval($num);

    $limite = 0;

    for($x=1; $x<$num_turmas; $x++)
    {
       	$new_id = GetIdentity('seq_disciplinas_ofer');

	    $limite = $num;

	    $sql= "select tablename from pg_tables where tablename = 'matricula_temp'";
  	    
        $query = $conn->CreateQuery($sql);
	    
        if($query->MoveNext())
	    {
	    	$sql = " drop table matricula_temp;";
       		$ok = $conn->Execute($sql);
	    }

	    $sql = 	" insert into disciplinas_ofer ( ".   // Disciplina Oferecida
   	  	        "	id, ".
         		"  	ref_campus, ".
   	         	"	ref_curso, ".
           		"	ref_periodo, ".
           		"	ref_disciplina, ".
           		"	num_alunos, ".
           		"	fixar_num_sala, ".
           		"	is_cancelada, ".
           		"	conteudo, ".
           		"	num_matriculados) ".
        		" select ".
           		"	$new_id, ".
           		"	ref_campus, ".
           		"	ref_curso, ".
           		"	ref_periodo, ".
           		"	ref_disciplina, ".
           		"	num_alunos, ".
           		"	fixar_num_sala, ".
           		"	is_cancelada, ".
           		"	conteudo, ".
       		    "	num_matriculados ".
	        	" from disciplinas_ofer ".
        		" where id = '$id'; ";

        $ok = $conn->Execute($sql);
        SaguAssert($ok, "Não foi possível oferecer uma nova disciplina.");

       	$new_id_compl = GetIdentity('seq_disciplinas_ofer_compl_id');

    	$sql = 	" insert into disciplinas_ofer_compl ( ".  // Disciplina Oferecida Complementar
	            "   id, " .
    	    	"	ref_disciplina_ofer, ".
     		    "	dia_semana, ".
         		" 	turno, ".
         		"	desconto, ".
     	    	"	num_creditos_desconto, ".
       		    " 	observacao, ".
         		"	num_sala, " .
                "   ref_professor_aux, "   .
                "   num_sala_aux, "   .
                "   turno_aux, "   .
                "   ref_horario, "   .
                "   ref_horario_aux, "   .
                "   dia_semana_aux) "   .
        		" select ".
       	    	"	$new_id_compl, ".
     		    "	$new_id, ".
         		"	dia_semana, ".
         		"	turno, ".
     	    	"	desconto, ".
           		"	num_creditos_desconto, ".
           		"	observacao, ".
         		"	num_sala, " .
                "   ref_professor_aux, "   .
                "   num_sala_aux, "   .
                "   turno_aux, "   .
                "   ref_horario, "   .
                "   ref_horario_aux, "   .
                "   dia_semana_aux "   .
    	    	" from disciplinas_ofer_compl ".
    	    	" where ref_disciplina_ofer = '$id' ";

        $ok = $conn->Execute($sql);
        SaguAssert($ok, "Não foi possível oferecer a complementação das disciplinas.");

        $sql = 	" insert into disciplinas_ofer_prof ( ". // Professor Disciplina Oferecida
        		"	ref_disciplina_ofer, ".
        		"	ref_disciplina_compl, ".
        		"	ref_professor) ".
        		" select ".
        		"	'$new_id', ".
        		"	'$new_id_compl', ".
        		"	ref_professor ".
        		" from disciplinas_ofer_prof ".
        		" where ref_disciplina_ofer = '$id' " . 
        		" limit 1 ";

        $ok = $conn->Execute($sql);
        SaguAssert($ok, "Não foi possível oferecer a complementação das disciplinas.");

        $sql = " select ref_curso, " .
               "        count(ref_curso) " .
               " from matricula " .
               " where ref_disciplina_ofer = '$id' and " .
               "       dt_cancelamento is null " .
               " group by ref_curso " .
               " order by count(ref_curso) desc;";

        $query = $conn->CreateQuery($sql);
	    
        $order = '';
        
        while ($query->MoveNext())
        {
          list ( $ref_curso,
                 $count) = $query->GetRowValues();
            
          $order .= " ref_curso = '$ref_curso', ";
        }



	    $sql = " select ref_pessoa ".
               " into matricula_temp ".
               " from matricula ".
               " where ref_disciplina_ofer = '$id' and ".
               "      dt_cancelamento is null ".
               " order by $order dt_matricula desc, hora_matricula desc ". 
               // Pediram para agrupar por curso como regra principal - Beto - 18-10-2002
               " limit $limite; ";

        $ok = $conn->Execute($sql);
        SaguAssert($ok, "Não foi possível inserir matricula temporária.");

    	$sql = " update matricula ".
	           " set ref_disciplina_ofer = '$new_id' ".
               " where ref_disciplina_ofer = '$id' and ".
               "       dt_cancelamento is null and ".
               "       ref_pessoa in (select ref_pessoa ".
               "                        from matricula_temp); ";
               
        $ok = $conn->Execute($sql);
        SaguAssert($ok, "Não foi possível dividir a turma.");

        $sql = "update disciplinas_ofer set num_alunos='$num' where id='$new_id';";
        $ok = $conn->Execute($sql);

	echo("<br>Turma <b>$x</b> gerada com sucesso!<br>Código da Disciplina Oferecida: <b>$new_id</b><br>Número de Alunos: <b>$num</b><hr>");
	flush();
    }  // for

    $sql = " update disciplinas_ofer set num_alunos='$num' where id='$id'; ";
    $ok = $conn->Execute($sql);

    $sql = " drop table matricula_temp;";
    $ok = $conn->Execute($sql);

    echo("<br><h1>Divisão OK!</h1>");

    $conn->Finish();
    $conn->Close();

}
?>
</head>

<body bgcolor="#FFFFFF">
<form method="post" action="">
  <?php 
     Divide($id, $numero, $num_turmas);
  ?> 
</form>
</body>
</html>
