<?php


require_once('aluno.conf.php');

include_once('topo.htm');


function getNumeric2Real($nNumeric)
{

     setlocale(LC_CTYPE,"pt_BR");

     $Real = explode('.',$nNumeric);

     $Inteiro = $Real[0];
     $Centavo = substr($Real[1], 0, 2);

     if ( strlen($Centavo) < 2 ) {

        $Centavo = str_pad($Centavo, 2, "0", STR_PAD_RIGHT);

     }

     $InteiroComMilhar = number_format($Inteiro, 0, '.', '.');

     $Real = $InteiroComMilhar.','.$Centavo;

     return $Real;

}



// RECUPERA A LISTA DE CURSOS E PERÍODOS ATUAIS PARA O ALUNO
$qryCurso = 'SELECT
   DISTINCT
   a.ref_curso, e.abreviatura, a.ref_periodo, d.descricao
   FROM matricula a, pessoas b, disciplinas c, periodos d, cursos e
   WHERE
      a.ref_disciplina
            IN (
                  SELECT
                     DISTINCT
                        a.ref_disciplina
                     FROM matricula a, disciplinas b
                     WHERE
                        a.ref_disciplina = b.id AND
                        a.ref_motivo_matricula = 0 AND
                        a.ref_pessoa = %s
             ) AND
      d.dt_final >= \'%s\' AND
      a.ref_curso = e.id AND
      a.ref_periodo = d.id AND
      a.ref_disciplina = c.id AND
      a.ref_pessoa = b.id AND
      a.ref_pessoa = %s;';

$aluno = $user;
$data = $DataInicial;

//echo sprintf($qryCurso,$aluno, $data, $aluno);

$RES = $conn->getAll(sprintf($qryCurso,$aluno, $data, $aluno));

//$var = $conn->getAll($qryCursos);
	
if ($conn === false)
{
   echo $conn->ErrorMsg() . '<br/>';
}
else
{

   $sql_aluno = "SELECT
             p.nome,
             rua,
             complemento,
             bairro,
             p.cep,
             c.nome || ' - ' || ref_estado AS cidade,
             fone_particular,
             fone_profissional,
             fone_celular,
             fone_recado,
             email,
             dt_nascimento
             FROM
             pessoas p, aux_cidades c
             WHERE p.id = $aluno AND p.ref_cidade = c.id ;";

 
   $DadosAluno = $conn->getAll($sql_aluno);

   $Aluno = $DadosAluno['0'];

   $SaldoCA = $conn->getOne('SELECT saldo_usuario FROM financeiro.tb_saldo where "FKid_usuario"= '.$aluno.';');

   //echo $conn->ErrorMsg() . '<br/>';
   if ($SaldoCA < 0 )
         $cfont = "red";
   else
	     $cfont = "green";

   
   $curso = '';
   echo '<table align="center" border="0" cellpadding="0" cellspacing="0" width="750"><tbody>
    <tr><td class="login"><h2 class="title">Minhas Informa&ccedil;&otilde;es <font color="red" size="-2">(<strong>*</strong>)</font></h2></td></tr><tr>';
   echo '<td><b>N&ordm; Registro:  </b>'.str_pad($aluno, 5, "0", STR_PAD_LEFT).'<td/><tr>';
   echo '<td><b>Nome: </b>'. $Aluno['nome'] .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Data de nascimento:  </b> '. aluno_br_date($Aluno['dt_nascimento'])  .'<td/><tr>';
   
   echo '<td><b>Endere&ccedil;o: </b>'. $Aluno['rua'] .'&nbsp;&nbsp;&nbsp;&nbsp;<strong>Bairro: </strong>'. $Aluno['bairro']. '<td/><tr>';
   echo '<td><strong>CEP: </strong>'. $Aluno['cep']. '&nbsp;&nbsp;&nbsp; <b>Cidade: </b>'. $Aluno['cidade'] .'<td/><tr>';
   echo '<td><strong>Telefone: </strong>'. $Aluno['fone_particular']. '&nbsp;&nbsp;&nbsp;&nbsp;<b>E-mail:  </b>'. $Aluno['email'].'<td/><tr>';
   echo '<tr><td>&nbsp;</td></tr>';
   echo '<tr><td><font color="red" size="-2">(<strong>*</strong>) para corrigir ou atualizar seus dados procure o setor de registros escolares.</font></td></tr>';
   echo '<tr><td>&nbsp;&nbsp;</td></tr>';
   
   echo  '<tr><td class="login"><h2 class="title">Consultar aproveitamento:</h2></td></tr>';
   
   

   for($i = 0; $i < count($RES) ; $i++)
   {
    
      echo '<tr><td><b>';
      echo $RES[$i]["abreviatura"].'</b><br> |  <a href=lista_notas.php?c='.$RES[$i]["ref_curso"].'&p='.$RES[$i]["ref_periodo"].'>'.$RES[$i]["descricao"].'</a>';

      if ($RES[$i]["ref_curso"] == $RES[$i + 1]["ref_curso"] )
      {
         echo ' |   <a href=lista_notas.php?c='.$RES[$i]["ref_curso"].'&p='.$RES[$i + 1]["ref_periodo"].'>'.$RES[$i + 1]["descricao"].'</a> | <br />';
         echo ' <br></td></tr> ';
         $i++;
      }
      else
      {
         echo ' | <br><br></td></tr>';
      }

   }
   
   
   echo '<tr><td>&nbsp;&nbsp;</td></tr></tbody></table>';

   echo '<br /><br /><br />';
   echo '<h2 class="title">&nbsp;&nbsp;Conta Acad&ecirc;mica:</h2>&nbsp;&nbsp;Saldo&nbsp;&nbsp;R$&nbsp;&nbsp;<font color="'.$cfont.'">'.getNumeric2Real($SaldoCA).'</font>';

?>

<div align="left">
   <form id="frmAltera" name="frmAltera" method="post" action="extrato_conta_academica/visao/frmExtrato_exec.php">
<input type="hidden" name="txtCodigo" id="txtCodigo" value="<?php echo $aluno; ?>"/>

<table width="416" height="130" border="0" cellpadding="0" cellspacing="0" align="left" >
  <tr>
    <td width="17" valign="top">&nbsp;</td>
    <td width="385" valign="middle" align="center">

    <table width="82%" border="0" cellspacing="0" cellpadding="0" class="tabelaDeTipos">
      <tr>
        <td width="71">&nbsp;</td>
        <td width="245">
        <h2 class="title">Consulta Extrato por per&iacute;odo</h2>    
       </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>

      <tr>
        <td>Data Inicial:</td>
        <td><input type="text" name="txtDataInicial" id="txtDataInicial" class="caixaPequena" value="01-01-2000"/>
          exemplo: dd-mm-aaaa</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Data Final</td>
        <td><input type="text" name="txtDataFinal" id="txtDataFinal" class="caixaPequena" value="01-01-2010"/>
          exemplo: dd-mm-aaaa</td>
      </tr> 

      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>
        <input id="cmdConsultar" type="submit" value="Consultar" class="botao" name="cmdConsultar" />       </td>
      </tr>
    </table></td>
    <td width="14" valign="top">&nbsp;</td>
  </tr>
</table>
</form>
</div>
<?php
}


echo "<br /><br /><br /><br />";

include_once('rodape.htm');
?>
