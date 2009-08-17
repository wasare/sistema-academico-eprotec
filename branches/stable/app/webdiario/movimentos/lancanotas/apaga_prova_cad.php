<?php
require_once('../../webdiario.conf.php');
         $sql1 = "DELETE FROM diario_avaliacao WHERE id = $id";
         $query1 = pg_exec($dbconnect, $sql1);
         print '<script language=javascript>
				 window.alert("Prova excluída com sucesso !!");
                 </script>';
         print '<html>
                <body>
                <SCRIPT LANGUAGE="JavaScript">
              	self.location.href = "cadprovas.php?id=' . $flag. '&getcurso=' . $getcurso. '&getdisciplina=' . $getdisciplina. '&getperiodo=' . $getperiodo. '"
             	</script>
                </body>
                </html>';
       		    exit;
                                          
pg_close($dbconnect);
?>
