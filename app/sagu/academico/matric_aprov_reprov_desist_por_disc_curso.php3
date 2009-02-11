<? require("../../../lib/common.php"); ?>
<? require("../../../lib/config.php"); ?>
<? require("../lib/GetField.php3"); ?>
<? require("../lib/SQLCombo.php3"); ?>

<html>
<head>
<title>Alunos matriculados, aprovados, reprovados e desistentes por disciplina por curso</title>
<script language="JavaScript">
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
function QueryExecute($sql)
{
  $conn = new Connection;

  $conn->Open();

  $query = $conn->CreateQuery($sql);

  if ( $query->MoveNext() )
    $obj = $query->GetRowValues();

  $query->Close();

  $conn->Close();

  return $obj;
}

if ( $ref_periodo == '' )
{
  list ( $ref_periodo ) = QueryExecute($sql_periodos_academico);
  $ref_periodo = trim(substr($ref_periodo, 0, strpos($ref_periodo, "/")));
}
$op_result = SQLArray($sql_periodos_academico);

</script>

<?
function ListaAlunos($ref_periodo)
{

   echo("<center><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   // cores fundo
   $bg0 = "#000000";
   $bg1 = "#DDDDFF";
   $bg2 = "#FFFFEE";
 
   // cores fonte
   $fg0 = "#FFFFFF";
   $fg1 = "#000099";
   $fg2 = "#000099";

   $sql = "select id," .
          "       descricao" .
          " from  cursos" .
          " where id in (select distinct ref_curso" .
          "              from  matricula" .
          "              where ref_periodo = '$ref_periodo'" .
          "              and   dt_cancelamento is null)" .
          " order by 2";
   
   $conn = new Connection;
   $conn->open();
   $cursos = $conn->CreateQuery($sql);
   
   while( $cursos->MoveNext() )
   {

      list ( $ref_curso,
             $curso ) = $cursos->GetRowValues();
             
      echo ("<tr bgcolor=\"#000099\">\n");
      echo ("   <td colspan=\"6\" height=\"30\" align=\"left\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>$ref_curso - $curso</b></font></td>");
      echo ("</tr>\n"); 
      
      echo ("<tr bgcolor=\"#000000\">\n");
      echo ("   <td width=\"30%\" height=\"20\" align=\"left\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
      echo ("   <td width=\"30%\" height=\"20\" align=\"left\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Professor</b></font></td>");
      echo ("   <td width=\"10%\" height=\"20\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Matriculados</b></font></td>");
      echo ("   <td width=\"10%\" height=\"20\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Aprovados</b></font></td>");
      echo ("   <td width=\"10%\" height=\"20\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Reprovados</b></font></td>");
      echo ("   <td width=\"10%\" height=\"20\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Desistentes</b></font></td>");
      echo ("</tr>\n");

      $sql = "select descricao_disciplina(A.ref_disciplina) as disciplina," .
             "       professor_disciplina_ofer(A.ref_disciplina_ofer)," .
             "       count(A.ref_pessoa) as matriculados," .
             "       sum(case when A.nota_final>=5 and A.fl_liberado!='2' then 1 else 0 end) as aprovados," .
             "       sum(case when A.nota_final<5 and A.fl_liberado!='2' then 1 else 0 end) as reprovados," .
             "       sum(case when A.fl_liberado='2' then 1 else 0 end) as desistentes" .
             " from  matricula A" .
             " where A.ref_periodo = '$ref_periodo'" .
             " and   A.ref_curso   = $ref_curso" .
             " and   A.dt_cancelamento is null" . 
             " group by 1,2" .
             " order by 1,2";
   
      $disciplinas = $conn->CreateQuery($sql);
      
      $cnt_disciplinas = 0;
      $sum_matric = 0;
      $sum_aprov  = 0;
      $sum_reprov = 0;
      $sum_desist = 0;
      while( $disciplinas->MoveNext() )
      {
     
         list ( $disciplina,
                $professor,
                $matriculados,
                $aprovados,
                $reprovados,
                $desistentes ) = $disciplinas->GetRowValues();
         
         $bg = ( ($cnt_disciplinas + 1) % 2 ) ? $bg1 : $bg2;
         
         echo("<tr bgcolor=\"$bg\">\n");
         echo("   <td width=\"30%\" height=\"20\" align=\"left\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$disciplina</td>");
         echo("   <td width=\"30%\" height=\"20\" align=\"left\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$professor</td>");
         echo("   <td width=\"10%\" height=\"20\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$matriculados</td>");
         echo("   <td width=\"10%\" height=\"20\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$aprovados</td>");
         echo("   <td width=\"10%\" height=\"20\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$reprovados</td>");
         echo("   <td width=\"10%\" height=\"20\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$desistentes</td>");
         echo("</tr>\n");

         $cnt_disciplinas++;
         $sum_matric += $matriculados;
         $sum_aprov  += $aprovados;
         $sum_reprov += $reprovados;
         $sum_desist += $desistentes;
         
      }

      $disciplinas->Close();

      echo("<tr bgcolor=\"#000000\">\n");
      echo("   <td width=\"30%\" height=\"20\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>$cnt_disciplinas disciplinas</b></font></td>");
      echo("   <td width=\"30%\" height=\"20\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>&nbsp;</b></font></td>");
      echo("   <td width=\"10%\" height=\"20\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>$sum_matric&nbsp;</b></font></td>");
      echo("   <td width=\"10%\" height=\"20\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>$sum_aprov&nbsp;</b></font></td>");
      echo("   <td width=\"10%\" height=\"20\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>$sum_reprov&nbsp;</b></font></td>");
      echo("   <td width=\"10%\" height=\"20\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>$sum_desist&nbsp;</b></font></td>");
      echo("</tr>");
      
      echo("<tr>"); 
      echo("   <td bgcolor=\"#ffffff\" colspan=\"7\" height=\"5\">&nbsp;</td>");
      echo("</tr>"); 
   
   }
   
   echo("</table></center>");

   $cursos->Close();
   $conn->Close();
}
</script>

</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<div align="center">
<form method="post" action="" name="myform">
<table width="90%" border="0" cellspacing="2" cellpadding="0" align="center">
  <tr bgcolor="#0066CC"> 
    <td bgcolor="#000099" colspan="7" height="35" align="center"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#CCCCFF"><b>Alunos matriculados, aprovados, reprovados e desistentes por disciplina por curso</b></font></td>
  </tr> 
  <tr> 
    <td width="81" bgcolor="#CCCCFF"><font face="Arial, Helvetica, sans-serif" size="2" color="#000099">&nbsp;Per&iacute;odo:</font></td>
    <td width="296" colspan="3"> <font color="#000000"> </font> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td> 
            <input type="text" name="ref_periodo" size="8" onChange="ChangeCode('ref_periodo','op')" value="<?echo($ref_periodo);?>">
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
    <td colspan="4" align="center"> 
      <hr>
    </td>
  </tr>
  <tr>
    <td colspan="4" align="center">
        <input type="submit" name="botao"  value="  Gerar  ">
        <input type="button" name="voltar" value="  Sair  " onClick="javascript:history.go(-1)">
    </td>
  </tr>
  <tr> 
    <td colspan="4" align="center"> 
      <hr>
    </td>
  </tr>
  <tr> 
    <td colspan="4" align="center"> 
       <? ListaAlunos($ref_periodo); ?>
    </td>
  </tr>
  <tr> 
    <td colspan="4" align="center">
      <hr size="1" width="100%">
    </td>
  </tr>
</table>
</form>
</div>
</body>
</html>
