<? require("../../../../lib/common.php"); ?>
<? require("../../../../lib/config.php"); ?>
<html>
<head>
<title><?echo($title);?></title>
<? 
CheckFormParameters(array('ref_periodo'));
?>

<script language="JavaScript">
function _select(ref_disciplina_ofer, descricao_disciplina, ref_disciplina_ofer_compl)
{
  window.opener.setResult(ref_disciplina_ofer, descricao_disciplina, ref_disciplina_ofer_compl);
  window.close();
}

function _init()
{
  document.myform.id.focus();
}

</script>
</head>
<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20" onLoad="_init()">
<form method="post" name="myform" action="lista_disciplinas_ofer.php3">
  <table width="100%" border="0" cellspacing="2" cellpadding="0" align="center">
    <tr bgcolor="#0066CC"> 
      <td colspan="7" height="28" align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><b>Consulta Disciplinas Oferecidas <?=$ref_periodo?></b></font></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td colspan="2"><font size="2" face="Arial, Helvetica, sans-serif">C&oacute;digo:</font></td>
      <td colspan="3"><font size="2" face="Arial, Helvetica, sans-serif">Descricao:</font></td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <input type="text" name="id" size="8" value="<?echo($id);?>">
      </td>
      <td colspan="3">&nbsp;
        <input type="text" name="desc" value="<?echo($desc);?>" size="40">
        <input type="hidden" name="ref_periodo" value="<?echo($ref_periodo);?>">
      </td>
      <td colspan="2"> 
        <div align="center"> 
          <input type="submit" name="Submit" value="Consultar">
        </div>
      </td>
    </tr>
    <tr> 
      <td colspan="7"> 
        <hr size="1">
      </td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="2" cellpadding="0" align="center">
    <tr bgcolor="#CCCCCC"> 
      <td width="5%" align="left">&nbsp;</td>
      <td width="10%"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Código</font></td>
      <td width="45%"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Descricao Disciplina</font></td>
      <td width="10%"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Dia Semana</font></td>
      <td width="10%"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Turno</font></td>
      <td width="10%"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Ofer</font></td>
      <td width="10%"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Compl</font></td>
    </tr>

<?
if ( $id != '' || $desc != '' )
{
  $conn = new Connection;
  
  $conn->Open();

  // note the parantheses in the where clause !!!
  $sql = " select A.id, " .
         "        A.ref_disciplina, " .
    	 "   	  descricao_disciplina(A.ref_disciplina)," .
         "        B.id, ".
         "        get_dia_semana_abrv(B.dia_semana), " .
         "        get_turno_abrv(B.turno) " .
         " from disciplinas_ofer A, disciplinas_ofer_compl B" .
         " where A.id = B.ref_disciplina_ofer and " .
         "       A.is_cancelada <> '1' and " .
    	 "       A.ref_periodo = '$ref_periodo' ";
  $where = '';

  if ( $id != '' )
    $where .= " and A.ref_disciplina = '$id' ";

  if ( $desc != '' )
    $where .= " and upper(descricao_disciplina(A.ref_disciplina)) like upper('$desc%')";

  $sql .= $where . " order by descricao_disciplina(A.ref_disciplina)";

  $query = $conn->CreateQuery($sql);

  for ( $i=0; $query->MoveNext(); $i++ )
  {
    list ( $ref_disciplina_ofer, 
           $ref_disciplina,
           $descricao_disciplina,
           $ref_disciplina_ofer_compl,
           $dia_semana,
           $turno) = $query->GetRowValues();

    $href = "<a href=\"javascript:_select('$ref_disciplina_ofer','$descricao_disciplina','$ref_disciplina_ofer_compl')\"><img src=\"../images/select.gif\" border=0 title='Selecionar'></a>";
 
    if ( $i % 2 == 0)
       $bg = "#EEEEFF";
    else
       $bg = "#FFFFEE";
    
    ?>
    <tr bgcolor="<?echo($bg);?>" valign="top"> 
      <td width="5%"><font face="Arial, Helvetica, sans-serif" size="2"><?echo($href);?></font></td>
      <td width="10%"> <font face="Arial, Helvetica, sans-serif" size="2"><?echo($ref_disciplina);?></font></td>
      <td width="45%"><font face="Arial, Helvetica, sans-serif" size="2"><?echo($descricao_disciplina); ?></font></td>
      <td width="10%"><font face="Arial, Helvetica, sans-serif" size="2"><?echo($dia_semana);?></font></td>
      <td width="10%"><font face="Arial, Helvetica, sans-serif" size="2"><?echo($turno);?></font></td>
      <td width="10%"><font face="Arial, Helvetica, sans-serif" size="2"><?echo($ref_disciplina_ofer);?></font></td>
      <td width="10%"><font face="Arial, Helvetica, sans-serif" size="2"><?echo($ref_disciplina_ofer_compl);?></font></td>
    </tr>
    <?
  } // for

  $query->Close();
  $conn->Close();
} // if
?>
    <tr> 
      <td colspan="7" align="center"> 
        <hr size="1">
        <input type="button" name="Button" value=" Voltar " onClick="javascript:window.close()">
      </td>
    </tr>
  </table>
</form>
</body>
</html>
