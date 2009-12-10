<?php
require_once('../../webdiario.conf.php');
                               print '<script language=javascript>
                               if(window.confirm("ATENÇÃO !! Este processo apaga a formula e as notas referentes , pressione (OK) para continuar  ou (CANCELAR) para voltar !")){
                               window.alert(" ATENÇÃO !!!   ESTE PROCESSO ESCLUIRÁ A FORMULA E TODAS AS NOTAS REFERENTES !!!");
                               } else { javascript:window.history.back(1); }
                               </script>';
                               $sqldel = "delete from diario_formulas where grupo='$grupo'";
                               $querydel =  pg_exec($dbconnect, $sqldel);
                               $sqldel1 = "delete from diario_notas where rel_diario_formulas_grupo ='$grupo'";
                               $querydel1 =  pg_exec($dbconnect, $sqldel1);
                               $sqlupdatematricula = "UPDATE matricula SET nota_final = '0' WHERE ref_curso = '$getcurso' and ref_periodo = '$getperiodo' and ref_disciplina = '$getdisciplina' ";
                               pg_exec($dbconnect, $sqlupdatematricula);
                               print '<script language=javascript>
				               window.alert("Prova excluída com sucesso !!");
                               </script>';

                               print '<html>
                               <body>
                               <SCRIPT LANGUAGE="JavaScript">
              	               self.location.href = "lanca1.php"
             	               </script>
                               </body>
                               </html>';
       		                   exit;
       		                   ////self.location.href = "lanca1.php?id=' . $flag. '&getcurso=' . $getcurso. '&getdisciplina=' . $getdisciplina. '&getperiodo=' . $getperiodo. '"
                               pg_close($dbconnect);
?>
