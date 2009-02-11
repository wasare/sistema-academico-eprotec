<? require("../../../lib/common.php"); ?>

<?
function ListaDisciplinas($ref_periodo)
{
    $conn = new Connection;
    $conn->open();

    $sql = "SELECT" .
           "    ref_professor," .
           "    nome," .
           "    count(ref_disciplina)" .
           " FROM" .
           "    (SELECT DISTINCT" .
           "         a.ref_professor," .
           "         c.nome," .
           "         b.ref_disciplina," .
           "         d.descricao_disciplina" .
           "     FROM" .
           "         disciplinas_ofer_prof a," .
           "         disciplinas_ofer b," .
           "         pessoas c," .
           "         disciplinas d" .
           "     WHERE" .
           "         b.id = a.ref_disciplina_ofer" .
           "     AND" .
           "         c.id = a.ref_professor" .
           "     AND" .
           "         d.id = b.ref_disciplina" .
           "     AND" .
           "         b.ref_periodo = '$ref_periodo'" .
           "     ORDER BY" .
           "         c.nome," .
           "         d.descricao_disciplina) a" .
           " GROUP BY" .
           "    ref_professor," .
           "    nome" .
           " ORDER BY" .
           "    nome";
                                       
    $query = $conn->CreateQuery($sql);
 
    echo "<table width=\"75%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n";
 
    // cores fundo
    $bg0 = "#DDFFDD";
    $bg1 = "#EEEEFF";
    $bg2 = "#FFFFEE";
 
    // cores fonte
    $fg0 = "#000000";
    $fg1 = "#000099";
    $fg2 = "#000099";
    
    for ( $i=1; $query->MoveNext(); $i++ )
    {
        list ( $ref_professor,
               $nome,
               $count ) = $query->GetRowValues();     
      
        if ($i == 1)
        {
            echo " <tr>\n";
            echo "  <td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Número de disciplinas por professor</b></font></td>\n";
            echo " </tr>\n";
            echo " <tr>\n";
            echo "  <td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $ref_periodo . "</b></font></td>\n";
            echo " </tr>\n"; 
            echo " <tr bgcolor=\"#000000\">\n";
            echo "  <td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Código</b></font></td>\n";
            echo "  <td width=\"60%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Professor</b></font></td>\n";
            echo "  <td width=\"30%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Disciplinas</b></font></td>\n";
            echo " </tr>\n"; 
          
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
        
        echo " <tr bgcolor=\"$bg\">\n";
        echo "  <td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_professor</td>\n";
        echo "  <td width=\"60%\" ><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$nome</td>\n";
        echo "  <td width=\"30%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$count</td>\n";
        echo " </tr>\n";

    }
 
    echo " <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" colspan=\"4\"><hr></td>\n";
    echo " </tr>\n"; 
        
    echo "</table>\n";
 
    $query->Close();
    $conn->Close();
}
?>
<html>
<head>
  <title>Número de Disciplinas por Professor</title>
</head>

<body bgcolor="#FFFFFF">
  <div align="center">
    <p><? ListaDisciplinas($ref_periodo); ?></p>
    <p><input type="button" value="Voltar" onClick="javascript:history.go(-1)"></p>
  </div>
  
</body>
</html>
