<? require("../../../lib/common.php"); ?>
<html>
<head>
</head>
<body bgcolor="#FFFFFF" marginwidth=20 marginheight=20 leftmargin=20 topmargin=20>
<CENTER>


<TABLE border="0" cellpadding="2" cellspacing="1" width="550">
  <TBODY><TR><TD><script language="PHP">

$conn = new Connection;

$conn->Open();

$sql = "select " .
         "ref_curso," .
         "curso_desc(ref_curso), " .
         "ref_campus," .
         "get_campus(ref_campus), ".
         "ref_disciplina," .
         "descricao_disciplina(ref_disciplina), ".
         "semestre_curso," .
         "curriculo_mco," .
         "pre_requisito_hora" .
         "  from cursos_disciplinas " .
         "  where ref_curso = 440 and " .
         "        ref_campus = 1 " .
         "  order by semestre_curso, ref_disciplina";

$query = $conn->CreateQuery($sql);

SaguAssert($query,"Nao foi possivel executar a consulta!");

for ( $row=0; $query->MoveNext(); $row++ )
{
  list ($ref_curso,
        $curso,
        $ref_campus,
        $campus,
        $ref_disciplina,
        $disciplina,
        $semestre_curso,
        $curriculo_mco,
        $pre_requisito_hora) = $query->GetRowValues();
  if($row == 0)
  {
</script></TD></TR><tr>
      <TD height="32" bgcolor="#000099" colspan="5"><font face="Verdana" size="3" color="#ffffff">&nbsp;<b>Currículo do Curso: <?echo($curso);?>&nbsp;</b></font>
  <BR>
      <FONT face="Verdana" size="2" color="#ffffff"><B>&nbsp;Campus: <?echo($campus);?> </B></FONT>;
    
    </tr>
    <tr bgcolor="#000000">
      <TD width="20"><font face="Verdana" size="2" color="#ffffff"><b>Cod</b></font></td>
      <TD width="280"><FONT face="Verdana" size="2" color="#ffffff"><B>Disciplina</B></FONT></TD>
      <td><font face="Verdana" size="2" color="#ffffff"><b>Sem</b></font></td>
      <td><font face="Verdana" size="2" color="#ffffff"><b>MCO</b></font></td>
      <td><font face="Verdana" size="2" color="#ffffff"><b>Pre/hora</b></font>

<script language="PHP">
  }
  if ( ( $row % 2 ) == 0 )
  {
</script>
</td>
    </tr>
    <tr bgcolor="#FFFFEE">
      <TD width="20"><font face="Verdana" size="2" color="#000099"><?echo($ref_disciplina);?>&nbsp;</font></td>
      <TD width="280"><FONT face="Verdana" size="2" color="#000099"><?echo($disciplina);?>&nbsp;</FONT></TD>
      <td><font face="Verdana" size="2" color="#000099"><?echo($semestre_curso);?>&nbsp;</font></td>
      <td><font face="Verdana" size="2" color="#000099"><?echo($curriculo_mco);?>&nbsp;</font></td>
      <td><font face="Verdana" size="2" color="#000099"><?echo($pre_requisito_hora);?>&nbsp;</font>

<script language="PHP">
  }
  else
  {
</script>

</td>
    </tr>
    <tr bgcolor="#EEEEFF">
      <TD width="20"><font face="Verdana" size="2" color="#000099"><?echo($ref_disciplina);?>&nbsp;</font></td>
      <TD width="280"><FONT face="Verdana" size="2" color="#000099"><?echo($disciplina);?>&nbsp;</FONT></TD>
      <td><font face="Verdana" size="2" color="#000099"><?echo($semestre_curso);?>&nbsp;</font></td>
      <td><font face="Verdana" size="2" color="#000099"><?echo($curriculo_mco);?>&nbsp;</font></td>
      <td><font face="Verdana" size="2" color="#000099"><?echo($pre_requisito_hora);?>&nbsp;</font>

<script language="PHP">
  }
}

$query->Close();

$conn->Close();
</script>
</td>
    </tr>
  
</TBODY>

    </table>
</CENTER>
</body>
</html>
