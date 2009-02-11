<? require("../../../lib/common.php"); ?>
<html>
<head>
<title>Livro Matrícula</title>
<script language="JavaScript">
function Select_Sexo(ref_periodo)
{
  var sexo = prompt("Digite o sexo? ( M ou F )\n\nPS.: Se não informar nada o relatório\nserá de ambos os sexos...","");
  
  var url = "livro_matricula_idade.php3" +
            "?ref_periodo=" + escape(ref_periodo) + "&sexo=" + escape(sexo);   
  location = url;
}

function Select_Data(ref_periodo)
{
  var dt_limite = prompt("Qual a data limite? (dd/mm/aaaa)","");
  if (dt_limite == "" || dt_limite == 0 || dt_limite==null)
  {
     alert("Cancelar OK");
  }
  else
  {
    var url = "livro_matricula.php3" +
              "?ref_periodo=" + escape(ref_periodo) + 
              "&dt_limite=" + escape(dt_limite); 
            
    location = url;
  }
}

function Select_Data2(ref_periodo)
{
  var data_geracao = prompt("Digite a Data de Geração do Livro Matrícula? (dd-mm-aaaa)","");
  
  var num_semanas = prompt("Digite a número de semanas do semestre?\nEsse parâmetro servirá para calcular o número de\nhoras-aula semanais","16");
 
  if (data_geracao == "" || data_geracao == 0 || data_geracao == null || num_semanas == "" || num_semanas == 0 || num_semanas == null)
  {
    alert("Listagem Cancelada!!!");
  }
  else
  {
     var url = "listagem_num_alunos_por_num_creditos_e_curso.php3" +
               "?ref_periodo=" + escape(ref_periodo) +
               "&data_geracao=" + escape(data_geracao) +
               "&num_semanas=" + escape(num_semanas);
     location = url;
  } 
}

function Select_Data31(ref_periodo)
{
  var data_geracao = prompt("Digite a Data de Geração do Livro Matrícula? (dd-mm-aaaa)","");
 
  if (data_geracao == "" || data_geracao == 0 || data_geracao == null)
  {
    alert("Listagem Cancelada!!!");
  }
  else
  {
     var url = "numalu_ocorrencia.php3" +
               "?ref_periodo=" + escape(ref_periodo) + "&data_geracao=" + escape(data_geracao);
     location = url;
  } 
}

function Select_Data32(ref_periodo)
{
  var data_geracao = prompt("Digite a Data de Geração do Livro Matrícula? (dd-mm-aaaa)","");
 
  if (data_geracao == "" || data_geracao == 0 || data_geracao == null)
  {
    alert("Listagem Cancelada!!!");
  }
  else
  {
     var url = "numalu_turma.php3" +
               "?ref_periodo=" + escape(ref_periodo) + "&data_geracao=" + escape(data_geracao);
     location = url;
  } 
}


function Select_Data4(ref_periodo)
{
  var data_geracao = prompt("Digite a Data de Geração do Livro Matrícula? (dd-mm-aaaa)","");
 
  if (data_geracao == "" || data_geracao == 0 || data_geracao == null)
  {
    alert("Listagem Cancelada!!!");
  }
  else
  {
     var url = "num_disciplinas_professor.php3" +
               "?ref_periodo=" + escape(ref_periodo) + "&data_geracao=" + escape(data_geracao);
     location = url;
  } 
}

function Pede_Motivos()
{
  var ref_motivo = prompt("Informe os códigos dos motivos?\n\nPS.: Obrigatoriamente deve ser \ninformado um motivo e caso for mais\nde um, separá-los por vírgula...","105, 152");
  
  if (ref_motivo == "" || ref_motivo == 0 || ref_motivo < 0 || ref_motivo == null) 
  { 
    alert("Geração Cancelada!!!");
  }
  else
  {
    var url = "lista_transferencias.php3" +
              "?ref_motivo=" + escape(ref_motivo);   
    location = url;
  }
}

function Config_Exibicao()
{
  var url = "livro_matricula_config_exibicao.phtml" +
            "?ref_periodo=<? echo($ref_periodo); ?>";
  location = url;
}

function Livro_Matricula(ref_periodo)
{
  var paginas_sumario = prompt("Digite o número de páginas do sumário?","");
  if (paginas_sumario == "" || paginas_sumario==null)
  {
     alert("Cancelar OK");
  }
  else
  {
    var url = "../relat/livro_matricula_ps.php3" +
              "?ref_periodo=" + escape(ref_periodo) + 
              "&paginas_sumario=" + escape(paginas_sumario); 
            
    location = url;
  }
}
</script>
</head>
<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<table width="90%" border="1" align="center">
  <tr bgcolor="#000099"> 
    <input type="hidden" name="ref_periodo" value="<?  echo("$ref_periodo"); ?>">
    <td><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF">A&ccedil;&atilde;o - Período <?echo($ref_periodo);?></font></b></td>
    <td><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF">Executar 
      A&ccedil;&atilde;o</font></b></td>
  </tr>
  <tr> 
    <td width="60%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Gera&ccedil;&atilde;o do Livro Matr&iacute;cula</font></td>
    <td width="40%"> 
      <div align="center"> 
        <input type="button" name="Submit1" value="Executar" onClick="javascript:Select_Data('<? echo($ref_periodo); ?>')">
      </div>
    </td>
  </tr>
  <tr> 
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Visualizar 
      o Resumo do Livro Matr&iacute;cula</font></td>
    <td> 
      <div align="center"> 
        <input type="button" name="Submit2" value="Executar" onClick="location='livro_matricula_resumo.php3?ref_periodo=<? echo($ref_periodo); ?>'">
      </div>
    </td>
  </tr>
  <tr> 
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Livro Matr&iacute;cula por Idade</font></td>
    <td> 
      <div align="center"> 
        <input type="button" name="Submit3" value="Executar" onClick="javascript:Select_Sexo('<? echo($ref_periodo); ?>')">
      </div>
    </td>
  </tr>
  
  <tr> 
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Listagem 
      do N&uacute;mero de Alunos por Cidade</font></td>
    <td> 
      <div align="center"> 
        <input type="button" name="Submit4" value="Executar" onClick="location='numalu_cidade.php3?ref_periodo=<? echo($ref_periodo); ?>'">
      </div>
    </td>
  </tr>
  <tr> 
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Listagem do N&uacute;mero de Alunos por Turma</font></td>
    <td> 
      <div align="center"> 
        <input type="button" name="Submit51" value="Ocorrências" onClick="javascript:Select_Data31('<? echo($ref_periodo); ?>')">
        <input type="button" name="Submit52" value="  Ofertas  " onClick="javascript:Select_Data32('<? echo($ref_periodo); ?>')">
      </div>
    </td>
  </tr>
  
  <tr> 
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Listagem do N&uacute;mero de Turmas por Professor</font></td>
    <td> 
      <div align="center"> 
        <input type="button" name="Submit6" value="Executar" onClick="javascript:Select_Data4('<? echo($ref_periodo); ?>')">
      </div>
    </td>
  </tr>
  
  <tr> 
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Listagem do N&uacute;mero de Disciplinas por Professor</font></td>
    <td> 
      <div align="center"> 
        <input type="button" name="Submit7" value="Executar" onClick="javascript:location='num_disciplinas_professor_geral.php3?ref_periodo=<? echo $ref_periodo; ?>'">
      </div>
    </td>
  </tr>
  
  <tr> 
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Listagem 
      do N&uacute;mero de Alunos por Cidade e Curso</font></td>
    <td> 
      <div align="center"> 
        <input type="button" name="Submit81" value="Executar" onClick="location='numalu_cidade_curso.php3?ref_periodo=<? echo($ref_periodo); ?>'">
        <input type="button" name="Submit82" value="Disciplinas" onClick="location='numalu_cidade_curso_disc.php3?ref_periodo=<? echo($ref_periodo); ?>'">
      </div>
    </td>
  </tr>
  <tr> 
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Listagem 
      do N&uacute;mero de Alunos por Curso e Bolsa</font></td>
    <td> 
      <div align="center"> 
        <input type="button" name="Submit9" value="Executar" onClick="location='listagem_alunos_curso_bolsa.php3?ref_periodo=<? echo($ref_periodo); ?>'">
      </div>
    </td>
  </tr>
  <tr> 
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Listagem do N&uacute;mero de Alunos por N&uacute;mero de Cr&eacute;ditos e Curso</font></td>
    <td> 
      <div align="center"> 
        <input type="button" name="Submit10" value="Executar" onClick="javascript:Select_Data2('<? echo($ref_periodo); ?>')">
      </div>
    </td>
  </tr>
  <tr> 
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Listagem do Número de Alunos por Curso e Faixa Etária</font></td>
    <td> 
      <div align="center"> 
        <input type="button" name="Submit111" value="Executar" onClick="location='lista_alunos_faixa_etaria.phtml?ref_periodo=<? echo($ref_periodo); ?>'">
        <input type="button" name="Submit112" value="Disciplinas" onClick="location='lista_alunos_faixa_etaria_disciplina.phtml?ref_periodo=<? echo($ref_periodo); ?>'">
      </div>
    </td>
  </tr>
  <tr> 
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Alunos Transfência para outra Instituição ou Guia de Transferência Expedida</font></td>
    <td> 
      <div align="center"> 
        <input type="button" name="Submit12" value="Executar" onClick="javascript:Pede_Motivos()">
      </div>
    </td>
  </tr>
  <tr> 
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Configurar modo de exibição dos cursos</font></td>
    <td> 
      <div align="center"> 
        <input type="button" name="Submit13" value="Executar" onClick="javascript:Config_Exibicao()">
      </div>
    </td>
  </tr>
  <tr> 
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Imprime Livro de Matrícula Completo</font></td>
    <td> 
      <div align="center"> 
        <input type="button" name="Submit14" value="Imprimir" onClick="javascript:Livro_Matricula('<?echo($ref_periodo);?>')">
      </div>
    </td>
  </tr>
</table>
<div align="center">
  <input type="button" name="Button" value="  Voltar  " onClick="location='livro_matricula.phtml'">
</div>
</form>
</body>
</html>
