<?php
include ('../../webdiario.conf.php');
/////////////////////$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname");

           if ($nomes == '') {
                  print '<script language=javascript>
						 window.alert("Você deve selecionar pelo menos um aluno !");
						javascript:window.history.back(1);
						</script>';
		       		    exit;
                        }  else {
      while ($array_cell = each($nomes)) {
         $valor = $array_cell['value'];
         $sql1 = "UPDATE diario_chamadas SET abono = 'S' WHERE id = $valor";
         $query1 = pg_exec($dbconnect, $sql1);
         

         $sql_pc = "select ref_periodo, ref_disciplina, ra_cnec from diario_chamadas where id = $valor";
         $query_pc = pg_exec($dbconnect, $sql_pc);
         while($linha_pc = pg_fetch_array($query_pc)) {
         $ra = $linha_pc["ra_cnec"];
             }
         falta($periodo, $ra, $disciplina, 1, 'SUB');


         
         print ("Abono registrado para $valor no dia $data_ok, disciplina $disciplina<br>");
                                }         }
                                
                               ?> <link rel="stylesheet" href="../css/gerals.css" type="text/css">
            <center>
                    <a href="abono.php" target="_self">Fazer novo abono</a>
                    </center> <?
                                          
pg_close($dbconnect);
?>
