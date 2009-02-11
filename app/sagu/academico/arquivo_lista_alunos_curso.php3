<? require("../../../lib/common.php"); ?>
<? require("../../../lib/config.php"); ?>
<? require("../lib/vestibular/common.php3"); ?>
<html>
<head>
<title>UNIVATES</title>
<script language="PHP">
//=========================================================
// criar o arquivo
//=========================================================
   $nome_arq = 'lista_aluno.txt';

   $myfile = fopen ($nome_arq, "w");

   if (!$myfile)
   {
      echo("Não foi possível criar o arquivo. Verifique!");
      exit;
   }
//=========================================================

  $conn = new Connection;

  $conn->Open();

  $sql = "select distinct nome from pessoas A, matricula B where (B.ref_curso = '$curso') and (A.id = B.ref_pessoa) order by nome";

  $query = $conn->CreateQuery($sql);

  while ( $query->MoveNext() )
  {

     list($nome) = $query->GetRowValues();

     fputs($myfile, "$nome\n");

  }
</script>
</head>
<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="arquivo_lista_alunos_curso.php3" name="myform">
  <div align="center">
    <table width="90%" border="0" cellspacing="0" cellpadding="0">
      <tr bgcolor="#000099">
        <td colspan="3" height="28" align="center"><font color="#000066"><b><font color="#CCCCFF" face="Verdana, Arial, Helvetica, sans-serif"> Lista de Alunos </font></font></font></font></b></font></td>
      </tr>
      <tr bgcolor="#EEEEFF">
        <td width="96" height="28"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b> Curso: </b></font>
        </td>
        <td width="174" height="28">
          <script language="PHP">
            echo("<input type=\"text\" name=\"curso\" size=\"10\" maxlength=\"10\" value=\"$curso\">");
          </script>
        </td>
        <td width="78" height="28">
          <input type="submit" name="Submit" value="Gera Arquivo">
        </td>
      </tr>
    </table>
  </div>
</form>
<script language = "PHP">
  //============================================================
  // Fim da geração mostrar para download.
  //============================================================

  echo("<center><a href=\"$nome_arq\">Visualizar Arquivo</a></center><br>");
  echo("<center><a href=\"#\" onClick=\"javascript:history.go(-1)\"><b>Voltar</b></a></center>");

</script>
</body>
</html>

