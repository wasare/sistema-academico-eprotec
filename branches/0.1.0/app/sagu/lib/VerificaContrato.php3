<?
// ----------------------------------------------------------
// Verifica o motivo de desativa��o do contrato
// ----------------------------------------------------------
function VerificaContrato($ref_motivo_desativacao)
{
?>
  <script language="JavaScript">

  var ref_motivo_desativacao;
  
  <? echo "ref_motivo_desativacao=$ref_motivo_desativacao;\n";  ?>
  
  
  if (ref_motivo_desativacao == '105' ||
      ref_motivo_desativacao == '152' ||
      ref_motivo_desativacao == '550' ||
      ref_motivo_desativacao == '6' ||
      ref_motivo_desativacao == '10' ||
      ref_motivo_desativacao == '16' ||
      ref_motivo_desativacao == '14'
     )
  {
    url = "javascript:history.go(-1)";
    
    if (confirm("O contrato deste aluno foi desativado por um dos seguintes motivos:\n" +
                "105 - Transfer�ncia para outra Institui��o\n" +
                "152 - Guia de Tranfer�ncia Expedida\n" +
                "550 - �bito\n" +
                "6 - Tranfer�ncia interna para outro Curso\n" +
                "10 - Reingresso com transfer�ncia para outro curso\n" +
                "16 - Vestibulando desistente de vaga\n" +
                "14 - Conclus�o de todas as disciplinas do curso\n" +
                "Deseja alterar o contrato mesmo assim?"))
    {
         alert("N�o esque�a de mudar o Motivo de Ativa��o e o \nStatus do Livro Matr�cula do Contrato do Aluno.");
    }
    else
    {
        location=(url);
    }
  }
</script>
<?
}
?>
