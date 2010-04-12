<?php

require("../../common.php");
require_once '../../../../core/login/acl.php';

$conn = new connection_factory($param_conn);

$acl = new acl();
if(!$acl->has_access(__FILE__, $conn)) {
    exit ('Voc� n�o tem permiss�o para acessar este formul�rio!');
}


$id                  = $_POST['id'];
$descricao           = $_POST['descricao'];
$abreviatura         = $_POST['abreviatura'];
$sigla               = $_POST['sigla'];
$total_creditos      = $_POST['total_creditos'];
$total_carga_horaria = $_POST['total_carga_horaria'];
$total_semestres     = $_POST['total_semestres'];
$grau_academico      = $_POST['grau_academico'];
$exigencias          = $_POST['exigencias'];
$agrupo_curso        = $_POST['agrupo_curso'];
$ref_area            = $_POST['ref_area'];
$reconhecimento      = $_POST['reconhecimento'];
$autorizacao         = $_POST['autorizacao'];
$turno               = $_POST['turno'];
$ref_tipo_curso      = $_POST['ref_tipo_curso'];
$historico           = $_POST['historico'];


CheckFormParameters(array("id",
                          "descricao",
                          "abreviatura",
                          "agrupo_curso",
            			  "ref_tipo_curso",
			              "turno"));

$conn = new Connection;

$conn->Open();

$turno = substr($turno, 0, 1);

$sql = "update cursos set " .
       "    id = '$id'," .
       "    descricao = '$descricao'," .
       "    abreviatura = '$abreviatura'," .
       "    sigla = '$sigla'," .
       "    total_creditos = '$total_creditos'," .
       "    total_carga_horaria = '$total_carga_horaria'," .
       "    total_semestres = '$total_semestres'," .
       "    grau_academico = '$grau_academico'," .
       "    exigencias = '$exigencias'," .
       "    agrupo_curso = '$agrupo_curso'," .
       "    ref_area = '$ref_area'," .
       "    reconhecimento = '$reconhecimento'," .
       "    autorizacao = '$autorizacao'," .
       "    turno = '$turno'," .
       "    ref_tipo_curso = '$ref_tipo_curso'," .
       "    historico = '$historico'" .
       "  where id = '$id'";

$ok = @$conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"N�o foi poss�vel alterar o registro!");
SuccessPage("Altera��o de Curso",
            "location='../consulta_cursos.phtml'",
            "Curso alterado com sucesso.");

?>

<html>
<head>