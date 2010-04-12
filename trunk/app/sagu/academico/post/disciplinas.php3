<?php

require("../../common.php");
require_once '../../../../core/login/acl.php';

$conn = new connection_factory($param_conn);

$acl = new acl();
if(!$acl->has_access(__FILE__, $conn)) {
    exit ('Voc� n�o tem permiss�o para acessar este formul�rio!');
}

require("../../lib/VerificaChaveUnica.php3");


$id                   = $_POST['id'];
$ref_grupo            = $_POST['ref_grupo'];
$ref_departamento     = $_POST['ref_departamento'];
$descricao_disciplina = $_POST['descricao_disciplina'];
$descricao_extenso    = $_POST['descricao_extenso'];
$num_creditos         = $_POST['num_creditos'];
$carga_horaria        = $_POST['carga_horaria'];

CheckFormParameters(array("id","descricao_disciplina"));

SaguAssert(VerificaChaveUnica("disciplinas", "id", "$id"), "C�digo j� existente");

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " insert into disciplinas ( " .
       "        id," .
       "        ref_grupo," .
       "        ref_departamento," .
       "        descricao_disciplina," .
       "        descricao_extenso," .
       "        num_creditos," .
       "        carga_horaria " . 
       " ) values (" .
       "        '$id'," .
       "        '$ref_grupo'," .
       "        '$ref_departamento'," .
       "        '$descricao_disciplina'," .
       "        '$descricao_extenso'," .
       "        '$num_creditos'," .
       "        '$carga_horaria')";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel inserir o registro!");

SuccessPage("Inclus�o de Disciplinas",
            "location='../disciplinas.phtml'",
            "Disciplina inclu�da com sucesso!!!",
            "location='../consulta_disciplinas.phtml'");
?>