<?php

/**
* Seleciona as disciplinas para matricular
* @author Santiago Silva Pereira
* @version 1
* @since 23-01-2009
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

/**
 * @var string 
 */
$sa_periodo_id = $_POST['periodo_id'];
/**
 * @var string 
 */
$aluno_id = $_POST['codigo_pessoa'];
/**
 * @var string 
 */
$id_contrato = $_POST['id_contrato'];
/**
 * @var string 
 */
$first = $_POST['first'];
/**
 * @var integer   
 */
$checar_turma = $_POST['checar_turma'];

$_SESSION['sa_periodo_id'] = $sa_periodo_id;

$sqlCurso = "
SELECT 
  cursos.id,
  cursos.descricao,
  contratos.ref_campus,
  contratos.turma
FROM
  contratos, cursos
WHERE
  cursos.id = contratos.ref_curso AND
  contratos.id = $id_contrato;";

//Exibindo a descricao do curso caso setado
$RsCurso = $Conexao->Execute($sqlCurso);


/**
 * @var integer
 */
$curso_id   = $RsCurso->fields[0];
/**
 * @var string   
 */
$curso_nome = $RsCurso->fields[1];
/**
 * @var integer   
 */
$ref_campus = $RsCurso->fields[2];
/**
 * @var string   
 */
$turma = $RsCurso->fields[3];


$sqlCampus = "SELECT get_campus($ref_campus)";
$RsCampus = $Conexao->Execute($sqlCampus);
$ref_campus = $RsCurso->fields[2];
/**
 * @var string Descricao no campus
 */
$campus_nome = $RsCampus->fields[0];

$sqlAluno = "SELECT nome FROM pessoas WHERE id = $aluno_id;";
$RsAluno = $Conexao->Execute($sqlAluno);
$ref_campus = $RsCurso->fields[2];
/**
 * @var string Nome do aluno
 */
$aluno_nome = $RsAluno->fields[0];

$disciplinas_liberadas = 0;


//EXIBE AS DISCIPLINAS MATRICULADAS
//Primeiro acesso na pagina

if ($first){

    $sqlDisciplinas = "
    SELECT
        A.ref_curso,
        A.ref_disciplina_ofer,
        B.ref_disciplina,
        descricao_disciplina(B.ref_disciplina),
        A.ref_curso_subst,
        B.ref_curso,
        A.ref_disciplina_subst,
        descricao_disciplina(A.ref_disciplina_subst),
        professor_disciplina_ofer_todos(B.id),
        get_dia_semana_abrv(dia_disciplina_ofer_todos(B.id)),
        turno_disciplina_ofer_todos(B.id),
        A.status_disciplina
    FROM
        matricula A, disciplinas_ofer B
    WHERE
        A.ref_periodo = '$sa_periodo_id' AND
        A.ref_pessoa  = $aluno_id AND
        A.ref_curso   = '$curso_id' AND
        A.ref_contrato = '$id_contrato' AND
        B.id = A.ref_disciplina_ofer AND
        A.dt_cancelamento IS NULL
    ORDER BY A.id";

    $RsDisciplinas = $Conexao->Execute($sqlDisciplinas);

    while(!$RsDisciplinas->EOF){

        $ref_curso            = $RsDisciplinas->fields[0];
        $ref_disciplina_ofer  = $RsDisciplinas->fields[1];
        $ref_disciplina       = $RsDisciplinas->fields[2];
        $nome1                = $RsDisciplinas->fields[3];
        $ref_curso_subst      = $RsDisciplinas->fields[4];
        $ref_curso_ofer       = $RsDisciplinas->fields[5];
        $ref_disciplina_subst = $RsDisciplinas->fields[6];
        $nome2                = $RsDisciplinas->fields[7];
        $prof                 = $RsDisciplinas->fields[8];
        $dia_semana           = $RsDisciplinas->fields[9];
        $turno                = $RsDisciplinas->fields[10];
        $status_disciplina    = $RsDisciplinas->fields[11];

        $code1[] = $ref_disciplina;
        $code2[] = $ref_disciplina_subst == 0 ? '' : $ref_disciplina_subst;
        $desc2[] = $nome2;

        if ( !$ref_disciplina_subst ){

            $desc1[] = $ref_disciplina_ofer.' - '.$nome1;
            $ofer1[]   = $ref_disciplina_ofer;
            $ofer2[]   = '';
            $curso1[]  = $ref_curso_ofer;
            $curso2[]  = '';
            $prof1[]   = $prof;
            $prof2[]   = '';
            $day1[]    = $dia_semana;
            $day2[]    = '';
            $turno1[]  = $turno;
            $turno2[]  = '';
            $status1[] = $status_disciplina;
            $status2[] = '';

        }
        else {

            $desc1[] = $ref_disciplina_ofer.' - '.$nome1;

            $ofer1[]   = '';
            $ofer2[]   = $ref_disciplina_ofer;
            $curso1[]  = '';
            $curso2[]  = $ref_curso_ofer;
            $prof1[]   = '';
            $prof2[]   = $prof;
            $day1[]    = '';
            $day2[]    = $dia_semana;
            $turno1[]  = '';
            $turno2[]  = $turno;
            $status1[] = $status_disciplina;
            $status2[] = $status_disciplina;
        }

        $RsDisciplinas->MoveNext();

    }
}

$count = count($code1); //soma quantos diarios

//se existir diarios
if ( $count != 0 ) {

    //Percorre os diarios
    for ( $i=0; $i<$count; $i++ ) {

        if ( $code1[$i] == '' )
        {
            continue;
        }

        $DisciplinasMatriculadas .= "<strong>".$desc1[$i]."</strong> (".$code1[$i].") - ".$prof1[$i]."<br />";
    }
    $autorizado = 'true';
}
else 
{
    $DisciplinasMatriculadas = '
   <div align="center">
       <b><font color="#CC0000">Nenhuma disciplina matriculada</font></b>
   </div>';
}


// Gera as disciplinas para matricular

$sqlDiarioMatricular = "
SELECT DISTINCT 
  A.id,
  A.ref_disciplina,
  descricao_disciplina(A.ref_disciplina),
  professor_disciplina_ofer_todos(A.id),
  get_dia_semana_abrv(dia_disciplina_ofer_todos(A.id)),
  turno_disciplina_ofer_todos(A.id),
  get_turno(turno_disciplina_ofer_todos(A.id)),
  A.ref_curso,
  get_color_campus(A.ref_campus),
  get_campus(A.ref_campus),
  get_status_disciplina('$aluno_id', '$curso_id', A.ref_disciplina),
  get_creditos(A.ref_disciplina),
  get_num_matriculados(A.id)
FROM 
  disciplinas_ofer A, cursos_disciplinas B
WHERE 
  A.ref_disciplina = B.ref_disciplina and
  A.ref_periodo = '$sa_periodo_id' and
  A.ref_curso = $curso_id and
  A.is_cancelada <> '1' ";
// B.ref_curso
if(!empty($turma)){
    if($checar_turma == 1){
        $sqlDiarioMatricular .= " and A.turma = '$turma' ";
    }
}

$sqlDiarioMatricular .= "
ORDER BY 2,
    get_color_campus(A.ref_campus),
    turno_disciplina_ofer_todos(A.id),
    get_dia_semana_abrv(dia_disciplina_ofer_todos(A.id));";


$RsDiarioMatricular = $Conexao->Execute($sqlDiarioMatricular);


while(!$RsDiarioMatricular->EOF){


    $ofer             = $RsDiarioMatricular->fields[0];
    $id               = $RsDiarioMatricular->fields[1];
    $nome             = $RsDiarioMatricular->fields[2];
    $prof             = $RsDiarioMatricular->fields[3];
    $dia_semana       = $RsDiarioMatricular->fields[4];
    $iturno           = $RsDiarioMatricular->fields[5];
    $turno            = $RsDiarioMatricular->fields[6];
    $ref_curso        = $RsDiarioMatricular->fields[7];
    $color2           = $RsDiarioMatricular->fields[8];
    $campus           = $RsDiarioMatricular->fields[9];
    $status           = $RsDiarioMatricular->fields[10];
    $creditos         = $RsDiarioMatricular->fields[11];
    $num_matriculados = $RsDiarioMatricular->fields[12];



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

    //echo '$ConfereDiario: '.$ConfereDiario;

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
            $txt_equivalente =  ' - <a href="#">[EQUIVALENTE JÁ CURSADA]</a>';

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
            $txt_cursada =  ' - <font color="orange"><strong>[ CURSADA ]</strong></font>';
                   

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
            $txt_pre_requisito =  ' - <a href="consulta_pre_requisito.php?o='.$ofer.'" target="_blank" title="Consultar pr&eacute;-requisito" >[ FALTA PR&Eacute;-REQUISITO ]</a>';
        

    // -- Verifica se o aluno ja eliminou os pré-requisitos ^ //


    if($ConfereDiario == 'f') {

        // AND $cursadas == 0
        if ( $requisitos == 0 )  {
        	$DiarioMatricular .= "<input type=\"checkbox\" name=\"id_diarios[]\" ".
                   "id=\"id_diarios[]\" value=\"$ofer\" onclick=\"Exibe('matricular')\" />";
             $disciplinas_liberadas++;
        }
        else 
   		$DiarioMatricular .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $DiarioMatricular .= "<strong>$ofer - $nome </strong>($id) $txt_equivalentes $txt_pre_requisito $txt_cursada - $prof<br />";
    }
    //-- FIM CONFERE MATRICULA

    $RsDiarioMatricular->MoveNext();

}//FIM WHILE

$MarcaDisciplina = '';

if($DiarioMatricular == '')
{
    $DiarioMatricular = '<p><div align="center"><b><font color="#CC0000">'.
	    'Nenhum di&aacute;rio dispon&iacute;vel!</font></b></div></p>';
}
else {

    if ( $disciplinas_liberadas > 0 )
    	$MarcaDisciplina = '<a href="#" onclick="selecionar_tudo();Exibe(\'matricular\');" >Marcar todas</a>&nbsp;&nbsp;'.
            '<a href="#" onclick="deselecionar_tudo();Oculta(\'matricular\');" >Desmarcar todas</a>';	
}



?>
