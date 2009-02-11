<? require("../../../../lib/common.php"); ?>
<? require("../../../../lib/config.php"); ?>
<? require("../../lib/GetField.php3"); ?>
<html>
<head>
  <title>Lista de Alunos Deficientes</title>
</head>
<?
function ListaAlunos($ref_curso=null, $ref_campus=null)
{

    $sql = "SELECT" .
           "    a.id," .
           "    a.nome," .
           "    get_endereco_pessoa(a.id)," .
           "    pessoa_fone(a.id)," .
           "    a.rg_numero," .
           "    b.ref_curso || ' - ' || get_curso_abrv(b.ref_curso)," .
           "    a.deficiencia_desc," .
           "    b.dt_desativacao," .
           "    b.ref_motivo_desativacao || ' - ' || motivo(b.ref_motivo_desativacao)," .
           "    is_matriculado(b.ref_last_periodo,a.id)" .
           " FROM" .
           "    pessoas a" .
           "    LEFT OUTER JOIN" .
           "    contratos b" .
           "    ON (b.ref_pessoa = a.id AND b.dt_ativacao = (SELECT MAX(dt_ativacao) FROM contratos WHERE ref_pessoa = b.ref_pessoa))" .
           " WHERE a.deficiencia = '1'"; 

    if ( $ref_curso != '' )
    {
        $sql .= " AND   b.ref_curso = $ref_curso";
    }
    if ( $ref_campus != '' )
    {
        $sql .= " AND   b.ref_campus = $ref_campus";
    }
    $sql .= " ORDER BY" .
            "    a.nome";
    
    echo "<!-- $sql -->";
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
    echo "<td bgcolor=\"#000099\" colspan=\"8\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Listagem de Deficientes</b></font></td>\n";
    echo "<tr>\n";
    // table title
    echo "<tr bgcolor=\"#000000\">\n";
    echo " <td><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>\n";
    echo " <td><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>\n";
    echo " <td><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Endereço</b></font></td>\n";
    echo " <td><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Fones</b></font></td>\n";
    echo " <td><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>RG</b></font></td>\n";
    echo " <td><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Curso</b></font></td>\n";
    echo " <td><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Deficiência</b></font></td>\n";
    echo " <td><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Status</b></font></td>\n";
    echo "</tr>\n";
    
    while( $query->MoveNext() )
    {
        list ( $ref_pessoa,
               $nome,
               $endereco,
               $fones, 
               $rg, 
               $curso,
               $deficiencia,
               $dt_desativacao,
               $mot_cancelamento,
               $is_matriculado) = $query->GetRowValues();
   
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
            
        $href = "<a href=../pessoaf_edita.phtml?id=$ref_pessoa>$ref_pessoa</a>";
        echo "<tr bgcolor=\"$bg\">\n";
        echo " <td><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$href</td>\n";
        echo " <td nowrap><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$nome</td>\n";
        echo " <td><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$endereco</td>\n";
        echo " <td><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$fones</td>\n";
        echo " <td><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$rg</td>\n";
        echo " <td><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$curso</td>\n";
        echo " <td><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$deficiencia</td>\n";
        if ( $dt_desativacao )
        {
            echo " <td><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$mot_cancelamento</td>\n";
        }
        elseif ( $is_matriculado == $ref_pessoa )
        {
            echo " <td><Font face=\"Verdana\" size=\"2\" color=\"$fg\">Matriculado</td>\n";
        }
        else
        {
            echo " <td><Font face=\"Verdana\" size=\"2\" color=\"$fg\">n/d</td>\n";
        }
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
   <? ListaAlunos($ref_curso, $ref_campus); ?>
   <div align="center"> 
    <input type="button" name="Button" value="  Voltar  " onclick="javascript:history.go(-1)">
   </div>
  </form>
</body>
</html>
