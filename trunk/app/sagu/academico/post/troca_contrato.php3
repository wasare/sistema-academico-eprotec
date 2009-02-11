<? require("../../../../lib/common.php"); ?>
<? require("../../lib/AutenticaSenha.php3"); ?>
<html>
<head>
<script language="PHP">

CheckFormParameters(array("ref_contrato",
          			      "ref_pessoa",
          			      "ref_curso",
          			      "ref_campus",
          			      "usuario",
                          "senha"));

 AutenticaSenha($usuario, $senha);
 
 $conn = new Connection;

 $conn->Open();

 $sql = " select id, " .
        "        ref_pessoa, " .
        "        ref_curso, " .
        "        ref_campus " .
        " from contratos " .
        " where ref_pessoa = $ref_pessoa and " .
        "       ref_curso = $ref_curso and " .
        "       ref_campus = $ref_campus and " .
        "       dt_desativacao is null;";

 $query = $conn->CreateQuery($sql);
 
 SaguAssert($query && $query->MoveNext(),"Não foi possível concluir o processamento!");

 list ($new_contrato,
       $new_pessoa,
       $new_curso,
       $new_campus) = $query->GetRowValues();
 
 $query->Close();

 $sql = " update matricula set " .
        "       ref_contrato = $new_contrato " .
        " where ref_pessoa = $ref_pessoa and " .
        "       ref_curso = $new_curso and " .
        "       ref_campus = $new_campus;";

 $ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

 $err= $conn->GetError();

 SaguAssert($ok,"Não foi possível concluir o processamento!<BR><BR>$err");

 $conn->Close();

 SuccessPage("Processamento Efetuado",
            "javascript:history.go(-1)",
            "As disciplinas do contrato fechado de código <b>$ref_contrato</b> do curso <b>$ref_curso</b> do campus <b>$ref_campus</b> do aluno <b>$ref_pessoa</b> foram convertidas para o contrato <b>$new_contrato</b> também do curso <b>$new_curso</b> e campus <b>$new_campus</b> deste mesmo aluno.");
</script>
</HEAD>
<BODY></BODY>
</HTML>
