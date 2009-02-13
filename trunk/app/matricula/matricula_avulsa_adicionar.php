<?php

/**
* Seleciona as disciplinas para matricular
* @author Santiago Silva Pereira
* @version 1
* @since 04-02-2009
**/

//Arquivos de configuracao e biblioteca
header("Cache-Control: no-cache");
require("../../lib/common.php");
require("../../lib/config.php");
require("../../configuracao.php");
require("../../lib/adodb/adodb.inc.php");


//Criando a classe de conexao ADODB
$Conexao = NewADOConnection("postgres");

//Setando como conexao persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

//
$aluno_id = $_SESSION['sa_aluno_id'];
//
$cod_diario = $_GET['cod_diario'];
//
$msg = '';



if($cod_diario == '')
{
	
		$msg = '<p><div align="center"><b><font color="#CC0000">'.
	    'Entre com um c&oacute;digo de di&aacute;rio!'.
		'</font></b></div></p>';
	
}
else
{

		$sqlDiarioMatricular = "
		SELECT DISTINCT 
  			A.id,
			A.ref_disciplina,
			descricao_disciplina(A.ref_disciplina),
			professor_disciplina_ofer_todos(A.id),
			get_campus(A.ref_campus),
			get_num_matriculados(A.id)
		FROM 
			disciplinas_ofer A, cursos_disciplinas B
		WHERE 
			A.ref_disciplina = B.ref_disciplina AND
			A.ref_periodo = '".$_SESSION['sa_periodo_id']."' AND
			A.id = $cod_diario AND
			A.is_cancelada <> '1' 
		ORDER BY 2";

		$RsDiarioMatricular = $Conexao->Execute($sqlDiarioMatricular);


		while(!$RsDiarioMatricular->EOF)
		{


    		$ofer             = $RsDiarioMatricular->fields[0];
		    $id               = $RsDiarioMatricular->fields[1];
		    $nome             = $RsDiarioMatricular->fields[2];
		    $prof             = $RsDiarioMatricular->fields[3];
		    $campus           = $RsDiarioMatricular->fields[4];
		    $num_matriculados = $RsDiarioMatricular->fields[5];
	
	


    		// -- Verifica se o aluno ja foi aprovado nesta disciplina ou em disciplina equivalente
		    // CONSIDERA SOMENTE SOMATORIO FINAL DE NOTA E FALTAS
        	$sqlEquivalente = "
	        SELECT DISTINCT
    	    	COUNT(d.id)
        	FROM
                matricula m, disciplinas d, pessoas p, disciplinas_ofer o, periodos s
        	WHERE
                m.ref_pessoa = p.id AND
                p.id = '$aluno_id' AND
                m.ref_disciplina_ofer = o.id AND
                d.id = o.ref_disciplina AND
                o.is_cancelada = 0 AND
                s.id = o.ref_periodo AND
                d.id IN (
                  select distinct ref_disciplina_equivalente
                        from disciplinas_equivalentes
                        where ref_disciplina IN ( select get_disciplina_de_disciplina_of('$ofer') )
                ) AND
                m.nota_final >= 60 AND
                m.num_faltas <= ( d.carga_horaria * 0.25); ";

        	$RsEquivalente = $Conexao->Execute($sqlEquivalente);
        	$equivalentes = $RsEquivalente->fields[0];

        	$txt_equivalente = '';
        	if ($equivalentes > 0 )
			{
            	$txt_equivalente =  ' - <a href="#">[EQUIVALENTE JÁ CURSADA]</a>';
			}

      		// -- Verifica se foi aprovado nesta mesma disciplina a qualquer tempo
        	$sqlDisciplina = "
	        SELECT DISTINCT
    	    COUNT(d.id)
        	FROM
                matricula m, disciplinas d, pessoas p, disciplinas_ofer o, periodos s
	        WHERE
                m.ref_pessoa = p.id AND
                p.id = '$aluno_id' AND
                m.ref_disciplina_ofer = o.id AND
                d.id = o.ref_disciplina AND
                o.is_cancelada = 0 AND
                s.id = o.ref_periodo AND
                d.id IN ( select get_disciplina_de_disciplina_of('$ofer') ) AND
                m.nota_final >= 60 AND
                m.num_faltas <= ( d.carga_horaria * 0.25); ";

    	    //echo $sqlDisciplina; die;
        	$RsDisciplina = $Conexao->Execute($sqlDisciplina);
	        $cursadas = $RsDisciplina->fields[0];
        
    	    $txt_cursada = '';
        	
			if ($cursadas > 0 )
			{
            	$txt_cursada =  ' - <font color="orange"><strong>[ CURSADA ]</strong></font>';
            }      

		    // ^ Verifica se o aluno ja foi aprovado nesta disciplina ou em disciplina equivalente ^ //
			
		    // -- Verifica se o aluno ja eliminou os pré-requisitos
		    // CONSIDERA SOMENTE SOMATORIO FINAL DE NOTA E FALTAS
        	
			$sqlPreRequisito = "
	        SELECT DISTINCT
    		    COUNT(d.id)
        	FROM
            	matricula m, disciplinas d, pessoas p, disciplinas_ofer o, periodos s
	        WHERE
             	m.ref_pessoa = p.id AND
                p.id = '$aluno_id' AND
                m.ref_disciplina_ofer = o.id AND
                d.id = o.ref_disciplina AND
                o.is_cancelada = 0 AND
                s.id = o.ref_periodo AND
                d.id IN (
                  select distinct ref_disciplina_pre
                        from pre_requisitos
                        where ref_disciplina IN ( select get_disciplina_de_disciplina_of('$ofer') )
                ) AND
                ( m.nota_final < 60 OR
                m.num_faltas > ( d.carga_horaria * 0.25) ); ";

        	// echo $sqlPreRequisito; //die();
	        $RsPreRequisito = $Conexao->Execute($sqlPreRequisito);
			
    	    $requisitos = $RsPreRequisito->fields[0];
      
        	$txt_pre_requisito = '';
	
	        if ($requisitos > 0 ) 
			{
            	$txt_pre_requisito =  ' - <a href="consulta_pre_requisito.php?o='.$ofer.
				'" target="_blank" title="Consultar pr&eacute;-requisito" >[ FALTA PR&Eacute;-REQUISITO ]</a>';
        	}

		    // -- Verifica se o aluno ja eliminou os pré-requisitos ^ //



    		// CONFERE SE JA ESTA MATRICULADO
		    $sqlConfereDiario = "
		    SELECT EXISTS(
        		SELECT
            		id
        		FROM
            		matricula
        		WHERE
            		ref_disciplina_ofer = $ofer AND
            		ref_pessoa = $aluno_id
    		);";

		    $RsConfereDiario = $Conexao->Execute($sqlConfereDiario);

		    if ($RsConfereDiario)
			{
        		$ConfereDiario = $RsConfereDiario->fields[0];
    		}



		    if($ConfereDiario == 'f') {

				
    	    	if ( $requisitos == 0 )  
				{
        			$DiarioMatricular .= "<input type=\"checkbox\" name=\"id_diarios[]\" ".
                   "id=\"id_diarios[]\" value=\"$ofer\" onclick=\"Exibe('matricular')\" />";
		   		}
				
				$DiarioMatricular .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        		$DiarioMatricular .= "<strong>$ofer - $nome </strong>($id) $txt_equivalentes".
											" $txt_pre_requisito $txt_cursada - $prof<br />";
											
				$disciplinas_liberadas++;
				
    		}//-- FIM CONFERE MATRICULA


    		$RsDiarioMatricular->MoveNext();

		}//FIM WHILE




		if($DiarioMatricular == '')
		{
		    $msg = '<p><div align="center"><b><font color="#CC0000">'.
	    	'Nenhum di&aacute;rio dispon&iacute;vel ou o aluno j&aacute; esta matriculado neste di&aacute;rio!'.
			'</font></b></div></p>';
		}
		else
		{
			$_SESSION['sa_diarios_matricula_avulsa'][$ofer] = $DiarioMatricular;
		}

}//else


//Retorno
echo $msg;
//echo $_SESSION['sa_diarios_matricula_avulsa'][$ofer];

foreach( $_SESSION['sa_diarios_matricula_avulsa'] as $diario)
{
	echo $diario;
}

?>