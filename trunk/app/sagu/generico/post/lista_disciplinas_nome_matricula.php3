<? require("../../../../lib/common.php"); ?>
<? require("../../../../lib/config.php"); ?>
<html>
<head>
<title><?echo($title);?></title>
<? 
   CheckFormParameters(array('periodo','curso','ref_pessoa'));
?>

<script language="JavaScript">
function _select(ofer,id,nome,prof,dia,ref_curso,turno,status,creditos)
{
    window.opener.setResult(ofer,id,nome,prof,dia,ref_curso,turno,status,creditos);
    window.close();
}

function pre_requisitos(url)
{
    window.open(url,'contratos','resizable=yes, toolbar=no,width=540,height=236,scrollbars=yes');
}

function _init()
{
  document.myform.id.focus();
}

</script>
</head>
<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20" onLoad="_init()">
<form method="post" name="myform" action="lista_disciplinas_nome_matricula.php3">
  <table width="100%" border="0" cellspacing="2" cellpadding="0" align="center">
    <tr bgcolor="#0066CC"> 
      <td colspan="10" height="28" align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><b>Consulta Disciplinas</b></font></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td colspan="2"><font size="2" face="Arial, Helvetica, sans-serif">C&oacute;digo:</font></td>
      <td colspan="3"><font size="2" face="Arial, Helvetica, sans-serif">Descricao:</font></td>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <input type="text" name="id" size="8" value="<?echo($id);?>">
      </td>
      <td colspan="3"> 
        <input type="text" name="desc" value="<?echo($desc);?>" size="40">
        <input type="hidden" name="periodo" value="<?echo($periodo);?>">
	<input type="hidden" name="curso" value="<?echo($curso);?>">
        <input type="hidden" name="ref_pessoa" value="<?echo($ref_pessoa);?>">
      </td>
      <td colspan="5"> 
        <div align="center"> 
          <input type="submit" name="Submit" value="Consultar">
        </div>
      </td>
    </tr>
    <tr> 
      <td colspan="10"> 
        <hr size="1">
      </td>
    </tr>
    </table>
    <table width="100%" border="0" cellspacing="2" cellpadding="0" align="center">
    <tr bgcolor="#CCCCCC"> 
      <td width="5%" align="left">&nbsp;</td>
      <td width="8%"> <font face="Arial, Helvetica, sans-serif" size="2" color="#000000">C&oacute;digo</font></td>
      <td width="7%" align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Status</font></td>
      <td width="7%" align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Dia</font></td>
      <td width="7%" align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Turno</font></td>
      <td width="35%"> <font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Disciplina / Professor</font></td>
      <td width="9%" align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Matriculados</font></td>
      <td width="7%" align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Curso</font></td>
      <td width="10%" align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Campus</font></td>
      <td width="5%"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">&nbsp;</font></td>
    </tr>

<?
if ( $id != '' || $desc != '' )
{
  
  $conn = new Connection;
  
  $conn->Open();

  // note the parantheses in the where clause !!!
  $sql = " select A.id, " .
      	 "        B.id, " .
    	 "	      B.descricao_disciplina," .
         "        professor_disciplina_ofer_todos(A.id)," .
         "        get_dia_semana_abrv(dia_disciplina_ofer_todos(A.id)), " .
    	 "  	  turno_disciplina_ofer_todos(A.id), " .
    	 "  	  get_turno(turno_disciplina_ofer_todos(A.id)), " .
    	 "  	  A.ref_curso, " .
    	 "  	  get_color_campus(A.ref_campus), " .
    	 "  	  get_campus(A.ref_campus), " .
    	 "        get_status_disciplina('$ref_pessoa','$curso',B.id), " .
         "        get_creditos(A.ref_disciplina), " .
         "        get_num_matriculados(A.id) " .
         "  from disciplinas_ofer A, disciplinas B" .
         "  where A.is_cancelada <> '1' and " .
    	 "        A.ref_periodo = '$periodo' and";
         
         // Hard Code Gestão Imobiliaria - Disciplinas exclusivas do curso 2400
         if ($curso != 2400)
         {
             $sql .= " A.id <> 26118 and A.id <> 26119 and ";
         }
        
  $sql.= "        B.id = A.ref_disciplina";

  $where = '';

  if ( $id != '' )
    $where .= " and B.id = $id";

  if ( $desc != '' )
    $where .= " and upper(B.descricao_disciplina) like upper('$desc%')";

  $sql .= $where . " order by B.id";

  $query = $conn->CreateQuery($sql);

  $count = $query->GetRowCount();

  if ($count == 0)
  {
  ?>
  <script language="JavaScript">
      window.opener.setResult('','','','','','','','','');
      window.close();
      window.opener.alert("Esta disciplina não está sendo oferecida neste período.");
  </script>
  <?
  }

  for ( $i=0; $query->MoveNext(); $i++ )
  {
    list ( $ofer, 
           $id, 
    	   $nome, 
    	   $prof, 
    	   $dia_semana,
    	   $iturno, 
    	   $turno, 
    	   $ref_curso, 
	       $color2,
    	   $campus,
    	   $status,
           $creditos,
           $num_matriculados) = $query->GetRowValues();

    $href1 = "<a href=\"javascript:pre_requisitos('/generico/mostra_pre_requisitos.phtml?ref_disciplina=$id&ref_curso=$curso')\"><img src=\"../images/info.gif\" border=0 title='Ver Pré Requisitos'></a>";

    
    if ($status == 2)
        $color = 'red';
    elseif ($status == 1)
        $color = 'green';
    elseif ($status == '')
        $color = '#000000';
    else
        $color = 'orange';

    if ( $i % 2 == 0)
        $bg = "#EEEEFF";
    else
        $bg = "#FFFFEE";

    if ($count > 1)
    {
        $href = "<a href=\"javascript:_select($ofer,$id,'$nome','$prof','$dia_semana','$ref_curso','$iturno','$status','$creditos')\"><img src=\"../images/select.gif\" border=0 title='Selecionar'></a>";
    }
    else
    {
       if ($status == 1)
       {
       ?>
       <script language="JavaScript">
           window.opener.setResult(<? echo("'$ofer','$id','$nome','$prof','$dia_semana','$ref_curso','$iturno','$status','$creditos'") ?>);
           window.close();
       </script>
       <?
       }
       else
       {
           $href = "<a href=\"javascript:_select($ofer,$id,'$nome','$prof','$dia_semana','$ref_curso','$iturno','$status','$creditos')\"><img src=\"../images/select.gif\" border=0 title='Selecionar'></a>";
       }
    }
    
    ?>
    <tr bgcolor="<?echo($bg);?>" valign="top">
      <td width="5%"><font face="Arial, Helvetica, sans-serif" size="2"><?echo($href);?></font></td>
      <td width="8%"> <font face="Arial, Helvetica, sans-serif" size="2"><?echo($id);?></font></td>
      <td width="7%" align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="<?echo($color);?>"><b><?echo($status_disciplina[$status]); ?></b></font></td>
      <td width="7%" align="center"> <font face="Arial, Helvetica, sans-serif" size="2"><?echo($dia_semana);?></font></td>
      <td width="7%" align="center"><font face="Arial, Helvetica, sans-serif" size="2"><?echo("&nbsp;$turno");?></font></td>
      <td width="35%"> <font face="Arial, Helvetica, sans-serif" size="2"><b><?echo($nome);?></b><br><? echo($prof);?></font></td>
      <td width="9%" align="center"><font face="Arial, Helvetica, sans-serif" size="2"><?echo($num_matriculados); ?></font></td>
      <td width="7%" align="center"><font face="Arial, Helvetica, sans-serif" size="2"><?echo($ref_curso); ?></font></td>
      <td width="10%" align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="<?echo($color2);?>"><b><?echo($campus); ?></b></font></td>
      <td width="5%" align="center"><font face="Arial, Helvetica, sans-serif" size="2"><?echo($href1); ?></font></td>
    </tr>
    <?
  } // for

  $query->Close();
  $conn->Close();
} // if
?>
    <tr> 
      <td colspan="10" align="center"> 
        <hr size="1">
        <input type="button" name="Button" value=" Voltar " onClick="javascript:window.close()">
      </td>
    </tr>
  </table>
</form>
</body>
</html>
