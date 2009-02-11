<? require("../../../lib/common.php"); ?>
<HTML>
<HEAD>
<SCRIPT language=PHP>

CheckFormParameters(array("ref_usuario","ref_pagina"));

$conn = new Connection;
$conn->Open();

$sql = "insert into sagu_header (" .
       "                               ref_usuario," .
       "                               ref_pagina)" . 
       "       values (" .
       "                               '$ref_usuario'," .
       "                               '$ref_pagina')";

$ok = @$conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();
SaguAssert($ok,"Nao foi possivel inserir o registro!");

SuccessPage("Inclusão de Links para usuário",
            "location='/tools.phtml'");
</SCRIPT>
</HEAD>
<BODY>
</BODY>
</HTML>
