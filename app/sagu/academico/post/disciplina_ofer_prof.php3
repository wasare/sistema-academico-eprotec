<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">

  CheckFormParameters(array(
                            "ref_disciplina_ofer",
                            "ref_disciplina_compl",
                            "ref_professor"));

$conn = new Connection;

$conn->Open();

$sql = "insert into disciplinas_ofer_prof (" .
       "                               ref_disciplina_ofer," .
       "                               ref_disciplina_compl," .
       "                               ref_professor)" . 
       "       values (" .
       "                               '$ref_disciplina_ofer'," .
       "                               '$ref_disciplina_compl'," .
       "                               '$ref_professor')" ;
       
       
 

$ok = $conn->Execute($sql);

SaguAssert($ok,"Nao foi possivel inserir o registro!");

$conn->Close();

SuccessPage("Inclusao de mais de um professor em uma Disciplina Oferecida",
            "location='../atualiza_disciplina_ofer.phtml?id=$ref_disciplina_ofer'",
            "");

</script>
</HEAD>
<BODY></BODY>
</html>
