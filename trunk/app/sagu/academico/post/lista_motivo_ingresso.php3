<? require("../../../../lib/common.php"); ?>
<?
    CheckFormParameters(array('ref_periodo','ref_motivo'));
?>
<html>
<head>
<title>Listagem por motivo de Entrada</title>

<script language="PHP">
function Lista_Alunos($ref_periodo, $ref_motivo)
{
    $conn = new Connection;

    $conn->open();

    $sql = " select distinct " .
           "        A.id, " .
           "        A.ref_pessoa, " .
           "        pessoa_nome(A.ref_pessoa), " .
           "        A.ref_curso, " .
           "        curso_desc(A.ref_curso), " .
           "        A.ref_campus, " .
           "        get_campus(A.ref_campus), " .
           "        motivo(A.ref_motivo_entrada), " .
           "        motivo('$ref_motivo') " .
           " from contratos A, matricula B " .
           " where A.id = B.ref_contrato and " .
           "       A.ref_pessoa = B.ref_pessoa and " .
           "       A.ref_last_periodo = B.ref_periodo and " .
           "       A.dt_desativacao is null and " .
           "       B.dt_cancelamento is null and " .
           "       A.ref_motivo_ativacao = '$ref_motivo' and " .
           "       A.ref_last_periodo = '$ref_periodo' and " .
           "       B.obs_aproveitamento = '' " .
           " order by A.ref_curso, " .
           "          A.ref_campus, " .
           "          pessoa_nome(A.ref_pessoa);";
   
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   $i=1;
   $aux_ref_curso = -1;
   $aux_ref_campus = -1;
   
   // cores fundo
   $bg0 = "#000000";
   $bg1 = "#EEEEFF";
   $bg2 = "#FFFFEE";
 
   // cores fonte
   $fg0 = "#FFFFFF";
   $fg1 = "#000099";
   $fg2 = "#000099";
  
   while( $query->MoveNext() )
   {
     list ($ref_contrato,
           $ref_pessoa,
           $nome_pessoa,
           $ref_curso,
           $nome_curso,
           $ref_campus,
           $nome_campus,
           $instituicao,
           $motivo) = $query->GetRowValues();
        
        if ($i == 1)
        {
	        echo("<tr>");
            echo ("<td bgcolor=\"#000099\" height=\"30\" colspan=\"4\"><font size=\"3\" face=\"Verdana\" color=\"#FFFFFF\"><center><b>Listagem de Alunos $motivo matriculados em $ref_periodo</b></center></font></td>");
	        echo("</tr>");
            echo("<tr><td colspan=\"4\" align=\"center\">&nbsp;</td></tr>");

        }

        if (($ref_curso != $aux_ref_curso) || ($ref_campus != $aux_ref_campus))
        {
            $aux_ref_curso = $ref_curso;
            $aux_ref_campus = $ref_campus;

            echo("<tr><td colspan=\"4\" align=\"center\"><hr></td></tr>");
            
            echo("<tr bgcolor=\"#000099\">\n");
            echo ("<td colspan=\"4\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"white\"><b>Curso: $ref_curso - $nome_curso - Campus: $nome_campus</b></td>");
            echo("</tr>");
            echo ("<tr bgcolor=\"#000000\">\n");
            echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cont.</b></font></td>");
            echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód.</b></font></td>");
            echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Nome do Aluno</b></font></td>");
            echo ("<td width=\"45%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Instituição</b></font></td>");
            echo ("  </tr>"); 
            
        }

        if ( $i % 2 )
        {
            $bg = $bg1;
            $fg = $fg1;
        }
        else
        {
            $bg = $bg2;
            $fg = $fg2;
        }
                
        echo("<tr><td colspan=\"4\" align=\"center\"><hr></td></tr>");
        echo("<tr bgcolor=\"$bg\">\n");
        echo("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\"><b>$i</b></font></td>");
        echo("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\"><b>$ref_pessoa</b></font>$professor</td>");
        echo("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\"><b>$nome_pessoa</b></font></td>");
        echo("<td width=\"45%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\"><b>$instituicao</b></font></td>");
        echo("</tr>");

        $sql2 = " select ref_curso, " .
                "        curso_desc(ref_curso), " .
                "        get_campus(ref_campus) " . 
                " from contratos " .
                " where ref_pessoa = '$ref_pessoa' and " .
                "       fl_formando = '1' and " .
                "       dt_desativacao is not null and " .
                "       dt_conclusao is not null " . 
                " order by dt_desativacao;";

        $query2 = $conn->CreateQuery($sql2);
        $num_cursos = $query2->GetRowCount();

        $ii = 1;
        if ($num_cursos > 0)
        {
            while( $query2->MoveNext() )
            {
                list($ref_curso2,
                     $nome_curso2,
                     $nome_campus2) = $query2->GetRowValues();

                if ($ii == 1)
                {
                    echo("<tr bgcolor=\"$bg\">\n");
                    echo("<td colspan=\"4\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Cursos em que o aluno já se formou na Instituição:</b></td>");
                    echo("</tr>");
                }
            
                echo("<tr bgcolor=\"$bg\">\n");
                echo("<td colspan=\"4\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_curso2 - $nome_curso2 - $nome_campus2</td>");
                echo("</tr>");
            
                $ii++;
            }
        }
        else
        {
            echo("<tr bgcolor=\"$bg\">\n");
            echo("<td colspan=\"4\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>O aluno ainda não se formou em nenhum curso:</b></td>");
            echo("</tr>");
        }
        $query2->Close();

        $ii = 1;
        $sql3 = " select B.id, " .
                "        B.descricao, " .
                "        A.percentual " .
                " from bolsas A, aux_bolsas B " .
                " where A.ref_tipo_bolsa = B.id and " .
                "       A.dt_validade >= date(now()) and " .
                "       A.percentual <> 0 and " .
                "       A.ref_contrato = '$ref_contrato';";
               
        $query3 = $conn->CreateQuery($sql3);
        $num_incentivos = $query3->GetRowCount();

        $ii = 1;
        if ($num_incentivos > 0)
        {
            while( $query3->MoveNext() )
            {
                list($ref_bolsa,
                     $nome_bolsa,
                     $percentual) = $query3->GetRowValues();
        
                if ($ii == 1)
                {
                    echo("<tr bgcolor=\"$bg\">\n");
                    echo("<td colspan=\"4\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Incentivos que o aluno possui:</b></td>");
                    echo("</tr>");
                }
            
                echo("<tr bgcolor=\"$bg\">\n");
                echo("<td colspan=\"4\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_bolsa - $nome_bolsa - $percentual%</td>");
                echo("</tr>");
            
                $ii++;
            }
        }
        else
        {
            echo("<tr bgcolor=\"$bg\">\n");
            echo("<td colspan=\"4\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>O aluno não possui nenhum incentivo.</b></td>");
            echo("</tr>");
        }
        
        $query3->Close();

        $i++;
        
   }
   
   echo("<tr><td colspan=\"4\" align=\"center\"><hr></td></tr>");

   echo ("<tr><td colspan=\"4\" align=\"center\"><input type=button value=' Voltar ' onClick=\"javascript:history.go(-1)\"></td></tr>\n");
   
   echo("</table></center>");

   @$query->Close();

   @$conn->Close();

}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="" name="myform">
  <script language="PHP">
    Lista_Alunos($ref_periodo, $ref_motivo);
  </script>
</form>
</body>
</html>
