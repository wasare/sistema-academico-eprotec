<? require("../../../../lib/common.php"); ?>
<? require("../../../../lib/config.php"); ?>
<? require("../../lib/GetField.php3"); ?>
<html>
<?
function ListaDisciplinas($ref_periodo=null,$ref_regime=null,$ref_dia=null,$rad_opcoes=null)
{

    $sql = "select" .
           "    A.id, " .
           "    B.id, " .
           "    C.descricao_disciplina, " .
           "    D.id, " .
           "    D.abreviatura " .
           "  from " .
           "    disciplinas_ofer_compl A, " .
           "    disciplinas_ofer B, " .
           "    disciplinas C, " .
           "    cursos D " .
           "  where ";

    if ( $rad_opcoes == "rad_regime" )
        $sql .=  "    A.ref_regime = '$ref_regime' and ";
    elseif ( $rad_opcoes == "rad_dia" )
        $sql .=  "    A.dia_semana = '$ref_dia' and ";
        
    $sql .= "    B.ref_periodo = '$ref_periodo' and " .
            "    A.ref_disciplina_ofer = B.id and " .
            "    B.ref_curso = D.id and " .
            "    B.ref_disciplina = C.id " .
            "  order by " .
            "    D.abreviatura, " .
            "    D.id ";

    $conn = new Connection;
    $conn->open();
    $query = $conn->CreateQuery($sql);

    // cores fundo
    $bg1 = "#DDDDFF";
    $bg2 = "#FFFFEE";
 
    // cores fonte
    $fg1 = "#000099";
    $fg2 = "#000099";

    $i = 0;
    echo "<table width=\"98%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">\n";
    echo "<tr height=\"30\">\n";
    echo "<td bgcolor=\"#000099\" colspan=\"8\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Listagem de Disciplinas Oferecidas</b></font></td>\n";
    echo "<tr>\n";
    // table title
    echo "<tr bgcolor=\"#000000\">\n";
    echo " <td><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Código</b></font></td>\n";
    echo " <td><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Alunos</b></font></td>\n";
    echo " <td><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Disciplina</b></font></td>\n";
    echo " <td><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Curso</b></font></td>\n";
    echo "</tr>\n";
    
    while( $query->MoveNext() )
    {
        list ( $id,
               $ref_disciplina_ofer,
               $nome,
               $ref_curso,
               $curso) = $query->GetRowValues();
   
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
            
        $href  = "<a href=../atualiza_disciplina_ofer.phtml?id=$ref_disciplina_ofer>$id</a>";
        $href2 = "<a href=lista_alunos.php3?disciplina_ofer_compl=$id&ref_periodo=$ref_periodo>$id</a>";
        echo "<tr bgcolor=\"$bg\">\n";
        echo " <td><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$href</td>\n";
        echo " <td><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$href2</td>\n";
        echo " <td nowrap><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$nome</td>\n";
        echo " <td nowrap><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_curso - $curso</td>\n";
        echo "</tr>\n";

        $i++;

    }

    echo "<tr bgcolor=\"#000000\">\n";
    echo " <td colspan=\"8\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Total: </b>" . $i . " registro(s)</font></td>\n";
    echo "</tr>\n";
    echo "<tr><td colspan=\"8\"><hr></td></tr>\n";
    echo "</table>\n";

    $query->Close();
    $conn->Close();
}
?>
<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
  <form method="post" action="">
   <? ListaDisciplinas($periodo_id, $ref_regime, $ref_dia, $rad_opcoes); ?>
   <div align="center"> 
    <input type="button" name="Button" value="  Voltar  " onclick="javascript:history.go(-1)">
   </div>
  </form>
</body>
</html>
