<? require("../../../lib/common.php"); ?>
<?
    $conn = new Connection;
    $conn->Open();
    $conn->Begin();

    $ref_periodo = '2005A';
    $num_alunos_turma = 60;
    $ref_curso = 251;
    $ref_campus = 1;
    
    //Insere dados de Disciplinas Oferecidas
    $sql = " insert into disciplinas_ofer( ".
           "        ref_curso ".
           "        ref_campus ".
           "        ref_periodo ".
           "        ref_disciplina ".
           "        num_alunos) values ".
           " (SELECT ref_curso, ".
           "        ref_campus, ".
           "        '$ref_periodo', ".
           "        ref_disciplina, ".
           "        '$num_alunos_turma' ".
           "  from cursos_disciplinas ".
           "  where ref_curso='$ref_curso' ".
           "    and ref_campus='$ref_campus')";

    $ok = $conn->Execute($sql);
?>
