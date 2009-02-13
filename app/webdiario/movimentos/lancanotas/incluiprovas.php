<?php
include_once('../../conf/webdiario.conf.php');
// CONECT NO BANCO

if(($descricao != "") && ($descres != "") && ($peso != ""))
{
   $sql1 = "INSERT INTO diario_avaliacao (descricao_resumida, descricao, peso, id_ref_pessoas, id_ref_periodos, id_ref_curso, id_ref_disciplina) VALUES('$descres','$descricao','$peso','$id','$getperiodo','$getcurso','$getdisciplina')";
   $query1 = pg_exec($dbconnect, $sql1);
               /* print '<script language=javascript>
				 window.alert("Prova incluída com sucesso !!");
                 </script>'; */
                 print '<html>
                <body>
                <SCRIPT LANGUAGE="JavaScript">
              	self.location.href = "cadprovas.php?id=' . $id. '&getcurso=' . $getcurso. '&getdisciplina=' . $getdisciplina. '&getperiodo=' . $getperiodo. '"
             	</script>
                </body>
                </html>';
       		    exit;
                           } else {
                                         print '<script language=javascript>
						                 window.alert("Você deve preencher todas as opções.");
						                 javascript:window.history.back(1);
						                 </script>';
		       		                     exit;   }
   pg_close($dbconnect);

?>
