<? require("../../../../lib/common.php"); ?>
<? require("../../../../lib/config.php"); ?>
<html>
<head>
<title><?echo($title);?></title>
<?
CheckFormParameters(array('periodo','curso','ref_disciplina','ref_pessoa'));
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

</script>
</head>
<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
  <tr align="center" bgcolor="#0066CC"> 
    <td colspan="10" height="28"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><b>Disciplinas Oferecidas</b></font></td>
  </tr>
  <tr bgcolor="#CCCCCC" valign=middle> 
    <td width="5%" align="left">&nbsp;</td>
    <td width="8%" align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">C&oacute;digo </font></td>
    <td width="7%" align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Status</font></td>
    <td width="7%" align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Dia</font></td>
    <td width="7%" align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Turno</font></td>
    <td width="35%"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Disciplina / Professor</font></td>
    <td width="9%" align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Matriculados</font></td>
    <td width="7%" align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Curso</font></td>
    <td width="10%" align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Campus</font></td>
    <td width="5%" align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#000000">&nbsp;</font></td>
  </tr>
  <?

  $conn = new Connection;
  
  $conn->Open();

  if ($nao_oferecida == 'true')
  {
    $sql_nao_ofer = " select ref_disciplina, " .
   	        	    " 	     descricao_disciplina(ref_disciplina), " .
        		    "        get_status_disciplina('$ref_pessoa','$curso','$ref_disciplina') " .
          	  	    " from cursos_disciplinas " .
        		    " where ref_disciplina = $ref_disciplina and " .
        		    "       ref_curso = $curso ";

    $query_nao_ofer = $conn->CreateQuery($sql_nao_ofer);
  
    if ($query_nao_ofer->MoveNext())
    {
      list( $cod_disciplina,
     	    $desc_disciplina,
	        $status) = $query_nao_ofer->GetRowValues();
      ?>
      <script language="JavaScript">
      	window.opener.setResult(<? echo("'','$cod_disciplina','$desc_disciplina','','','$curso','','$status'") ?>);
      	window.close();
      </script>
      <?
    }
    else
    {
      ?>
      <script language="JavaScript">
      	window.opener.setResult('','','','','','','','');
      	window.close();
      	window.opener.alert("Esta disciplina não é do currículo do curso <?echo($curso);?>.");
      </script>
      <?
    }
  }
  else
  {
    $sql = " select distinct A.id, ".
           "         A.ref_disciplina, ".
           "         descricao_disciplina(A.ref_disciplina), ".
           "         professor_disciplina_ofer_todos(A.id), ".
           "         get_dia_semana_abrv(dia_disciplina_ofer_todos(A.id)), ".
           "         turno_disciplina_ofer_todos(A.id), ".
           "         get_turno(turno_disciplina_ofer_todos(A.id)), ".
           "         A.ref_curso, ".
           "         get_color_campus(A.ref_campus), " .
           "         get_campus(A.ref_campus), " .
	       "         get_status_disciplina('$ref_pessoa','$curso','$ref_disciplina'), " .
           "         get_creditos(A.ref_disciplina), " .
           "         get_num_matriculados(A.id) || '/' || A.num_alunos " .           
           " from disciplinas_ofer A, cursos_disciplinas B ".
           " where A.ref_disciplina = B.ref_disciplina and ".
           "       A.ref_periodo = '$periodo' and ".
           "       B.ref_curso = '$curso' and ".
           "       A.ref_disciplina = '$ref_disciplina' and ";

           // Hard Code Gestão Imobiliaria - Disciplinas exclusivas do curso 2400
           if ($curso != 2400)
           {
               $sql .= " A.id <> 26118 and A.id <> 26119 and ";
           }
        
    $sql.= "       A.is_cancelada <> '1' " .
           " order by turno_disciplina_ofer_todos(A.id), " .
           "          get_dia_semana_abrv(dia_disciplina_ofer_todos(A.id));";

  $query = $conn->CreateQuery($sql);
  
  $count = $query->GetRowCount();
  
  if ($count == 0) 
  { 
  ?>
  <script language="JavaScript">
      window.opener.setResult('','','','','','','','','');
      window.close();
      window.opener.alert("Esta disciplina não é do currículo do curso <?echo($curso);?> ou não está sendo oferecida neste período.");
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
    
     $href1 = "<a href=\"javascript:pre_requisitos('/generico/mostra_pre_requisitos.phtml?ref_disciplina=$ref_disciplina&ref_curso=$curso')\"><img src=\"../images/info.gif\" border=\"0\" title=\"Ver Pré Requisitos\"></a>";
     
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
      $href = "<a href=\"javascript:_select($ofer,$id,'$nome','$prof','$dia_semana','$ref_curso','$iturno','$status','$creditos')\"><img src=\"../images/select.gif\" border=\"0\" title=\"Selecionar\"></a>";
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
        	$href = "<a href=\"javascript:_select($ofer,$id,'$nome','$prof','$dia_semana','$ref_curso','$iturno','$status','$creditos')\"><img src=\"../images/select.gif\" border=\"0\" title=\"Selecionar\"></a>";
	    }
    }

    ?>
  <tr bgcolor="<?echo($bg);?>" valign=middle>  
    <td width="5%" align="left"><? echo($href);?></td>
    <td width="8%" align="center"><font face="Arial, Helvetica, sans-serif" size="2"><? echo($id);?></font></td>
    <td width="7%" align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="<? echo($color);?>"><b><?echo($status_disciplina[$status]);?></b></font></td>
    <td width="7%" align="center"><font face="Arial, Helvetica, sans-serif" size="2"><? echo($dia_semana);?></font></td>
    <td width="7%" align="center"><font face="Arial, Helvetica, sans-serif" size="2"><? echo($turno);?></font></td>
    <td width="35%"> <font face="Arial, Helvetica, sans-serif" size="2"><b><? echo($nome);?></b><br><? echo($prof);?></font></td>
    <td width="9%" align="center"><font face="Arial, Helvetica, sans-serif" size="2"><? echo($num_matriculados);?></font></td>
    <td width="7%" align="center"><font face="Arial, Helvetica, sans-serif" size="2"><? echo($ref_curso);?></font></td>
    <td width="10%" align="center"><font face="Arial, Helvetica, sans-serif" size="2"color="<? echo($color2);?>"><b><?echo($campus);?></b></font></td>
    <td width="5%" align="center"><font face="Arial, Helvetica, sans-serif" size="2"><?echo($href1);?></font></td>
  </tr>
  <?
   } // for
  } //fim do else
  ?>
  <tr> 
    <td align=center colspan="10"> 
      <form method="post" action="">
        <hr size="1" width="100%">
        <input type="button" name="Button" value=" Voltar " onClick="javascript:window.close()">
      </form>
    </td>
  </tr>
</table>
<div align="center"> </div>
</body>
</html>
