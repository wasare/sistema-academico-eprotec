<?php

require_once('../../../app/setup.php');
require_once($BASE_DIR .'core/search.php');

$conn = new connection_factory($param_conn);

$Result1 = $conn->Execute("SELECT descricao, id FROM periodos ORDER BY 1 DESC;");

$busca1  = new search('periodo','periodo_id','periodos_list', 'form1', '../../relatorios/periodo_lista.php');
$busca2  = new search('curso','curso_id','cursos_list', 'form1', '../../relatorios/curso_lista.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title><?=$IEnome?></title>
        <link href="../../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
        <script src="../../../lib/SpryAssets/SpryValidationCheckbox.js" type="text/javascript"></script>
        <link href="../../../lib/SpryAssets/SpryValidationCheckbox.css" rel="stylesheet" type="text/css" />
        <link href="../../../lib/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
        <script src="../../../lib/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
        <script language="javascript" src="../../../lib/prototype.js"></script>
    </head>

    <body>
        <h2>Consulta Di&aacute;rios</h2>
        <form name="form1" id="form1" action="lista_diarios_secretaria.php" method="get" target="_blank">
            <input type="image" name="voltar"
                   src="../../../public/images/icons/back.png"
                   alt="Voltar"
                   title="Voltar"
                   id="bt_voltar"
                   name="bt_voltar"
                   class="botao"
                   onclick="history.back(-1);return false;" />
            <div class="panel">
		Per&iacute;odo:<br />
                &nbsp;&nbsp;<span class="comentario">Comece digitando o ano para listar os per&iacute;odos ou informe o c&oacute;digo do per&iacute;odo no primeiro campo.</span><br />
                <span id="sprytextfield0">
                    <?php
                    echo $busca1->input_text_retorno("5");
                    echo $busca1->input_text_consulta("30");
                    echo '<span class="textfieldRequiredMsg">Obrigat&oacute;rio.</span>';
                    echo $busca1->area_lista();
                    ?>
                </span>
                <br />
		Curso:<br />
                &nbsp;&nbsp;<span class="comentario">Comece digitando o nome do curso para list&aacute;-los ou informe o c&oacute;digo do curso no primeiro campo.</span><br />
                <span id="sprytextfield1">
                    <?php
                    echo $busca2->input_text_retorno("5");
                    echo $busca2->input_text_consulta("40");
                    echo '<span class="textfieldRequiredMsg">Obrigat&oacute;rio.</span>';
                    echo $busca2->area_lista();
                    ?>
                </span>
                <br />
                <h4>Ou</h4>
                C&oacute;digo do di&aacute;rio:<br />
                &nbsp;&nbsp;<span class="comentario">Se preenchido os campos anteriores ser&atilde;o ignorados.</span><br />
                <input name="diario_id" type="text" id="diario_id" size="10" />
                <br /><br />
                <input name="lista_diarios" type="submit" id="lista_diarios" value="Listar di&aacute;rios" />
            </div>
        </form>
        <!--<script type="text/javascript">
		var sprytextfield0 = new Spry.Widget.ValidationTextField("sprytextfield0");
		var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");

        </script>-->
    </body>
</html>
