<? require("../../../lib/common.php"); ?>
<? require("../../../lib/config.php"); ?>
<? require("../lib/SQLCombo.php3"); ?>
<html>
<head>
<title>Listagem de Trancamentos por Período</title>

<script language="PHP">
$op_result = SQLArray($sql_periodos_academico);

$sql = " select nome_campus, id from campus" .
       " order by id;" ;

$op_result2 = SQLArray($sql);

</script>

<script language="JavaScript">
function _init()
{
  document.myform.ref_periodo.focus();
}

function ChangeOption(opt,fld)
{
  var i = opt.selectedIndex;

  if ( i != -1 )
    fld.value = opt.options[i].value;
  else
    fld.value = '';
}

function ChangeOp()
{
  ChangeOption(document.myform.op,document.myform.ref_periodo);
}

function ChangeOp1()
{
  ChangeOption(document.myform.op1,document.myform.ref_curso);
}

function ChangeOp2()
{
  ChangeOption(document.myform.op2,document.myform.ref_campus);
}

function ChangeOp3()
{
  ChangeOption(document.myform.op3,document.myform.ref_periodo_atual);
}

function ChangeCode(fld_name,op_name)
{ 
  var field = eval('document.myform.' + fld_name);
  var combo = eval('document.myform.' + op_name);
  var code  = field.value;
  var n     = combo.options.length;
  for ( var i=0; i<n; i++ )
  {
    if ( combo.options[i].value == code )
    {
      combo.selectedIndex = i;
      return;
    }
  }

  alert(code + ' não é um código válido!');

  field.focus();

  return true;
}
</script>

<script language="PHP">
function ListaAlunos($ref_periodo,$ref_periodo_atual,$cursos, $ref_campus,$fl_status,$motivos)
{
   $conn = new Connection;

   $conn->open();

   $total=0;

   if ($motivos)
   {
       $where = '(';
       foreach($motivos as $key => $motivo)
       {
           $where .= " ref_motivo_desativacao = '$motivo' or";
       }
       $where = substr($where, 0, (strlen($where)-2));    
       $where .= ') and ';
   }

   if ($cursos)
   {
       $where2 = '(';
       foreach($cursos as $key => $curso)
       {
           $where2 .= " ref_curso = '$curso' or";
       }
       $where2 = substr($where2, 0, (strlen($where2)-2));    
       $where2 .= ') and ';
   }
   
   $sql = " select id," .
          "        to_char(dt_ativacao,'dd/mm/yyyy')," .
          "        to_char(dt_desativacao,'dd/mm/yyyy')," .
          "        ref_pessoa," .
          "        pessoa_nome(ref_pessoa)," .
          "        ref_curso, " .
          "        ref_motivo_desativacao, " .
          "        motivo(ref_motivo_desativacao), " .
          "        ref_last_periodo " .
          " from contratos " .
          " where dt_desativacao is not null and " .
          "       $where " .
          "       $where2 " .
          "       ref_last_periodo = '$ref_periodo'";
          
          if ($fl_status == 'yes')
          {
            $sql .= " and ref_pessoa not in (select ref_pessoa " .
                    "                        from matricula " .
                    "                        where ref_periodo = '$ref_periodo_atual' and " .
                    "                              dt_cancelamento is null and " .
                    "                              ref_pessoa = contratos.ref_pessoa)";
          }
          if ($ref_campus)
          {
            $sql .= " and ref_campus = '$ref_campus'";
          }
   
   if ($cursos)
   {
       $sql .= " order by ref_curso, pessoa_nome(ref_pessoa)";
   }
   else
   {
       $sql .= " order by pessoa_nome(ref_pessoa)";
   }

   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   // cores fundo
   $bg0 = "#000000";
   $bg1 = "#DDDDFF";
   $bg2 = "#FFFFEE";
 
   // cores fonte
   $fg0 = "#FFFFFF";
   $fg1 = "#000099";
   $fg2 = "#000099";

   echo ("<tr>"); 
   echo ("   <td bgcolor=\"#000099\" colspan=\"6\" height=\"28\" align=\"center\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Listagem de Alunos com Matrícula Trancada $ref_periodo</b></font></td>");
   echo ("</tr>"); 

   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("   <td width=\"10%\" height=\"20\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Ativação</b></font></td>");
   echo ("   <td width=\"10%\" height=\"20\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Desativação</b></font></td>");
   echo ("   <td width=\"10%\" height=\"20\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód. Aluno</b></font></td>");
   echo ("   <td width=\"50%\" height=\"20\" align=\"left\" ><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>&nbsp;&nbsp;Nome</b></font></td>");
   echo ("   <td width=\"10%\" height=\"20\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Curso/<br>Último&nbsp;Período</b></font></td>");
   echo ("   <td width=\"10%\" height=\"20\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>&nbsp;</b></font></td>");
   echo ("</tr>"); 

   $i = 0;
   while( $query->MoveNext() )
   {
     
     $bg = ( $i % 2 ) ? $bg1 : $bg2;
     
     list ( $contrato, 
            $dt_ativ,
            $dt_desat,
            $ref_pessoa,
            $pessoa_nome, 
            $ref_curso,
            $ref_motivo_desativacao,
            $motivo_desativacao,
            $ref_last_periodo) = $query->GetRowValues();
  
     echo("<tr bgcolor=\"$bg\">\n");
     echo("   <td width=\"10%\" height=\"20\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$dt_ativ</td>");
     echo("   <td width=\"10%\" height=\"20\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$dt_desat</td>");
     echo("   <td width=\"10%\" height=\"20\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp;&nbsp;$ref_pessoa</td>");
     echo("   <td width=\"50%\" height=\"20\" align=\"left\" ><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp;&nbsp;$pessoa_nome</td>");
     echo("   <td width=\"10%\" height=\"20\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_curso</td>");
     echo("   <td width=\"10%\"  height=\"20\" align=\"center\"><a href=\"pessoaf_edita.phtml?id=$ref_pessoa\"><img src=\"../images/update.gif\" alt='Alterar Cadastro' border=0></a></td>");
     echo("</tr>\n");
     
     echo("<tr bgcolor=\"$bg\">\n");
     echo("   <td colspan=\"4\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_motivo_desativacao - $motivo_desativacao</td>");
     echo("   <td height=\"20\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_last_periodo</td>");
     echo("   <td height=\"20\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp;</td>");
     echo("</tr>\n");

     echo("<tr bgcolor=\"$bg\">\n");
     echo("<td colspan=\"6\" align=\"center\"><hr></td>");
     echo("</tr>\n");
     
     $i++;

   }
   
   echo("<tr bgcolor=\"#000000\">\n");
   echo("   <td height=\"20\" align=\"right\" colspan=\"6\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Total: $i alunos&nbsp;</b></font></td>");
   echo("</tr>"); 

   echo("</table></center>");

   $query->Close();
   $conn->Close();
}
</script>

</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20" onload="_init()" >
<form method="post" action="listagem_de_trancamentos_periodo.php3" name="myform">
  <table width="90%" border="0" cellspacing="2" cellpadding="0" align="center">
    <tr> 
      <td bgcolor="#000099" colspan="2" height="28" align="center"> <font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#CCCCFF"><b>&nbsp;Consulta Trancamentos por Período</b></font></td>
    </tr>
    <tr> 
      <td bgcolor="#CCCCFF"><font face="Arial, Helvetica, sans-serif" size="2" color="#000099">&nbsp;Per&iacute;odo:</font></td>
      <td> <font color="#000000"> </font> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td> 
              <input type="text" name="ref_periodo" size="8" onChange="ChangeCode('ref_periodo','op')" value="<?echo($ref_periodo)?>">
            </td>
            <td>&nbsp;</td>
            <td width="100%"><font color="#000000"> 
              <script language="PHP">
                  ComboArray("op",$op_result,$ref_periodo,"ChangeOp()");
              </script>
              </font></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td bgcolor="#CCCCFF"><font face="Arial, Helvetica, sans-serif" size="2" color="#000099">&nbsp;Per&iacute;odo Atual:</font></td>
      <td> <font color="#000000"> </font> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td> 
              <input type="text" name="ref_periodo_atual" size="8" onChange="ChangeCode('ref_periodo_atual','op3')" value="<?echo($ref_periodo_atual)?>">
            </td>
            <td>&nbsp;</td>
            <td width="100%"><font color="#000000"> 
              <script language="PHP">
                  ComboArray("op3",$op_result,$ref_periodo_atual,"ChangeOp3()");
              </script>
              </font></td>
          </tr>
        </table>
      </td>
    </tr>

    <tr> 
      <td bgcolor="#CCCCFF"><font face="Arial, Helvetica, sans-serif" size="2" color="#000099">&nbsp;Status:</font></td>
      <td> <font color="#000000"> </font> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td colspan="2" width="100%"><font color="#000000"> 
                <? 
                if ($fl_status == 'no') 
                { 
                ?>
                    <input type="radio" name="fl_status" value="yes">Somente Alunos Totalmente Inativos
                    <input type="radio" name="fl_status" value="no" checked>Alunos com pelo menos um Contrato Desativado
                <?
                } 
                else 
                { 
                ?>
                    <input type="radio" name="fl_status" value="yes" checked>Somente Alunos Totalmente Inativos
                    <input type="radio" name="fl_status" value="no">Alunos com pelo menos um Contrato Desativado
                <?
                }
                ?>
                </font></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td bgcolor="#CCCCFF"><font face="Arial, Helvetica, sans-serif" size="2" color="#000099">&nbsp;Curso:</font></td>
      <td>

         <script language="PHP">
         $conn = new Connection;
         $conn->Open();

         // Query
         $sql = " select id, substr(descricao,0,40) " .
                " from cursos " .
                " where ref_tipo_curso = '1' " . // Somente Graduação
                " order by descricao";

         @$query = $conn->CreateQuery($sql);
         $i = 1;
         </script>
         
         <SELECT multiple name="cursos[]" size="5">
         
         <script language="PHP">
         while ($query->MoveNext())
         {
             list($ref_curso,
                  $curso_desc) = $query->GetRowValues();
         </script>
         
           <OPTION value='<?echo($ref_curso);?>'><?echo("$ref_curso - $curso_desc");?> 

         <script language="PHP">
         $i++;
         }
         $query->Close();
         $conn->Close();
         </script>
         </select>
      </td>
    </tr>
    <tr>
      <td bgcolor="#CCCCFF"><font face="Arial, Helvetica, sans-serif" size="2" color="#000099">&nbsp;Campus:</font></td>
      <td> <font color="#000000"> </font>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <input type="text" name="ref_campus" size="8" onChange="ChangeCode('ref_campus','op2')" value="<?echo($ref_campus)?>">
            </td>
            <td>&nbsp;</td>
            <td width="100%"><font color="#000000">
              <script language="PHP">
                 ComboArray("op2",$op_result2,$ref_campus,"ChangeOp2()");
              </script>
              </font></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td bgcolor="#CCCCFF" width="200">
         <font face="Helvetica" size="2" color="#00009C">&nbsp;Motivos</font>
      </td>
      <td>

         <script language="PHP">
         $conn = new Connection;
         $conn->Open();

         // Query
         $sql = " select id, substr(descricao,0,40) " .
                " from motivos " .
                " where ref_tipo_motivo = 2 " .
                " order by id";
                
         @$query = $conn->CreateQuery($sql);
         $i = 1;
         </script>
         
         <SELECT multiple name="motivos[]" size="5">
         
         <script language="PHP">
         while ($query->MoveNext())
         {
             list($id,
                  $descricao) = $query->GetRowValues();
         </script>
         
           <OPTION value='<?echo($id);?>'><?echo("$id - $descricao");?> 

         <script language="PHP">
         $i ++;
         }
         $query->Close();
         $conn->Close();
         </script>
         </select>
      </td>
    </tr>
    <tr> 
       <td colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr> 
       <td colspan="2"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#AA0000">Obs.: Somente os campos <i>Período</i>, <i>Período Atual</i> e <i>Status</i> são obrigatórios.<br>Os campos Campus, Curso e Motivos não são obrigatórios. Servem somente como filtros...</font>
       </td>
    </tr>
    <tr> 
       <td colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr> 
       <td colspan="2" align="center">
          <input type="submit" name="botao"   value="  Gerar  ">
          <input type="button" name="Button2" value="   Sair   " onClick="javascript:history.go(-1)">
       </td>
    </tr>
    <tr> 
       <td colspan="2" align="center">&nbsp;</td>
    </tr>
 
  </table>
  
  <? ListaAlunos($ref_periodo,$ref_periodo_atual,$cursos,$ref_campus,$fl_status,$motivos); ?>

</form>
</body>
</html>
