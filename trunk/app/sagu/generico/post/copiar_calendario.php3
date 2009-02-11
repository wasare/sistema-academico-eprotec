<? require("../../../../lib/common.php"); ?>
<html>
<head>
<script language="PHP">

CheckFormParameters(array(
                            "periodo_de",
                            "periodo_para"
                            ));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " select count(*) " .
       " from calendario_academico " .
       " where ref_periodo = '$periodo_para'";

$query = $conn->CreateQuery($sql);

while ( $query->MoveNext() )
{
    $qtde_registros = $query->GetValue(1);
}
$query->Close();

$step_by_copia = ($qtde_registros==0);
SaguAssert($step_by_copia, "Impedimento: O período <b>$periodo_para</b> já tem registros inseridos!!!");

$sql = " insert into calendario_academico (" .
       "	    ref_periodo, " .
       "        dia_semana, " .
       "        data_aula) " .
       " (select '$periodo_para', " .
       "         dia_semana, " .
       "         data_aula " .
       " from calendario_academico where ref_periodo = '$periodo_de')";

$ok = $conn->Execute($sql);

$err= $conn->GetError();

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível inserir o registro!<BR><BR>$err");

SuccessPage("Registros copiados com sucesso!!!",
            "location='../consulta_inclui_calendario_academico.phtml'",
            "Cópia feita do período <b>$periodo_de</b> para o <b>$periodo_para</b>.");
</script>
</HEAD>
<BODY></BODY>
</HTML>
