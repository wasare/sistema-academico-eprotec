
SELECT 
     DISTINCT
      p.id as "Matrícula",
      p.nome as "Nome Completo",
      p.dt_nascimento as "Data Nasc.",
      p.sexo
   FROM
      pessoas p
   WHERE
         p.id > 3717
   ORDER BY p.nome

-- ALUNOS MATRICULADOS NOS PERIODOS by San
SELECT 
    DISTINCT 
      a.nome as "Nome Completo",
      a.id as "Matrícula",
      curso_desc(c.ref_curso)
   FROM
      pessoas a, matricula b, contratos c
   WHERE
         a.id IN (
                  SELECT 
                     DISTINCT
                        A.ref_pessoa
                     FROM 
                        matricula A
                     WHERE
                        ref_periodo ILIKE '08%'
                     ORDER BY A.ref_pessoa
         )  AND
      a.id = b.ref_pessoa AND
      c.ref_pessoa = a.id AND
      c.id = b.ref_contrato
   ORDER BY  a.nome


-- ALUNOS MATRICULADOS NOS PERIODOS E CURSOS ESPECIFICADOS
SELECT 
    DISTINCT 
      a.nome as "Nome Completo",
      a.id as "Matr�cula",
      b.ref_curso, c.ref_periodo_turma
   FROM
      pessoas a, matricula b, contratos c
   WHERE
         a.id IN (
                  SELECT 
                     DISTINCT
                        A.ref_pessoa
                     FROM 
                        matricula A
                     WHERE
                        ref_periodo ILIKE '07%'
                     ORDER BY A.ref_pessoa
         )  AND
      a.id = b.ref_pessoa AND
      c.ref_pessoa = a.id AND
      c.id = b.ref_contrato AND
      b.ref_curso IN (106,107,108,110,113,301,304,506,603,605,606)
   ORDER BY  nome, ref_curso




-- ALUNOS MATRICULADOS NOS PERIODOS E CURSOS ESPECIFICADOS
SELECT 
    DISTINCT 
      a.nome, a.id, b.ref_curso, d.abreviatura
   FROM
      pessoas a, matricula b, contratos c, cursos d
   WHERE
         a.id IN (
                  SELECT 
                     DISTINCT
                        a.ref_pessoa
                     FROM 
                        matricula a
                     WHERE
                        ref_periodo IN ('07','07021','07022','0702')
                     ORDER BY a.ref_pessoa
         )  AND
      a.id = b.ref_pessoa AND
      c.ref_pessoa = a.id AND
      c.id = b.ref_contrato AND
      b.ref_curso = d.id AND
      c.ref_curso = d.id AND
      LOWER(to_ascii(a.nome)) SIMILAR TO LOWER(to_ascii('jos%'))
   ORDER BY  a.nome LIMIT 20 OFFSET -1;




SELECT 
    DISTINCT 
        registro_id, nota_diario, nota_extra, nota_final, num_faltas, faltas_diario
FROM

(---NOTA DIARIO N1 A N6
SELECT 
  DISTINCT 
    CAST(b.id AS INTEGER) AS registro_id, SUM(c.nota) AS nota_diario 
  FROM 
    matricula a, pessoas b, diario_notas c 
  WHERE 
    a.ref_periodo = '0701' AND 
    a.ref_disciplina_ofer = '1932' AND 
    b.ra_cnec = c.ra_cnec AND 
    c.d_ref_disciplina_ofer = '1932' AND 
    a.ref_pessoa = b.id AND 
    b.ra_cnec IN (
          SELECT DISTINCT a.ref_pessoa FROM matricula a WHERE a.ref_periodo = '0701' AND a.ref_disciplina_ofer = '1932'

    )  AND 
    ref_diario_avaliacao < 7 
  GROUP BY b.id 
) AS T1

INNER JOIN 

(---NOTA DIARIO NOTA EXTRA
SELECT 
  DISTINCT 
    CAST(b.id AS INTEGER) AS registro_id, SUM(c.nota) AS nota_extra 
  FROM 
    matricula a, pessoas b, diario_notas c 
  WHERE 
    a.ref_periodo = '0701' AND 
    a.ref_disciplina_ofer = '1932' AND 
    b.ra_cnec = c.ra_cnec AND 
    c.d_ref_disciplina_ofer = '1932' AND 
    a.ref_pessoa = b.id AND 
    b.ra_cnec IN (
          SELECT DISTINCT a.ref_pessoa FROM matricula a WHERE a.ref_periodo = '0701' AND a.ref_disciplina_ofer = '1932'

    )  AND 
    ref_diario_avaliacao = 7 
  GROUP BY b.id 
) AS T2

USING (registro_id)
INNER JOIN

(-- NOTA E FALTA SAGU
SELECT 
    DISTINCT 
      CAST(a.ref_pessoa AS INTEGER) AS registro_id, a.nota_final, a.num_faltas 
    FROM 
      matricula a
    WHERE 
      a.ref_periodo = '0701' AND 
      a.ref_disciplina_ofer = '1932' 
) AS T3

USING (registro_id)
INNER JOIN

(-- FALTAS DIARIO
SELECT 
      CAST(a.ra_cnec AS INTEGER) AS registro_id, count(a.ra_cnec) as faltas_diario 
    FROM 
      diario_chamadas a 
    WHERE 
      (a.ref_periodo = '0701') AND 
      (a.ref_disciplina_ofer = '1932') AND
      a.ra_cnec IN (
          SELECT DISTINCT a.ref_pessoa FROM matricula a WHERE a.ref_periodo = '0701' AND a.ref_disciplina_ofer = '1932'

    ) 
    GROUP BY ra_cnec
) AS T4

USING (registro_id)



SELECT 
    DISTINCT 
      matricula.ordem_chamada, pessoas.nome, pessoas.id, SUM(d.nota) AS notaparcial 
  FROM 
      matricula INNER JOIN 
      pessoas ON (matricula.ref_pessoa = pessoas.id) INNER JOIN 
      diario_notas d ON (id_ref_pessoas = pessoas.id AND 
      d.ra_cnec = matricula.ref_pessoa AND 
      d.id_ref_periodos = '0701' AND 
      d.d_ref_disciplina_ofer = '1666' AND 
      d.rel_diario_formulas_grupo = '2483-0701-501002-1666' AND 
      d.ref_diario_avaliacao <> '1' AND d.ref_diario_avaliacao <> '7') 
  WHERE 
      (matricula.ref_disciplina_ofer = '1666') AND 
      (matricula.dt_cancelamento is null) 
GROUP BY matricula.ordem_chamada, pessoas.nome, pessoas.id, pessoas.ra_cnec 
ORDER BY pessoas.nome



-- LIBERA OS ALUNOS DO CURSO/GRADE PARA MATRICULAS 
-- NO PERIODO SEGUINTE
UPDATE 
   matricula 
SET 
   fl_liberado = NULL 
WHERE 
   ref_curso = 109

-- VERIFICA SALA/TURNO/DIA PADRAO PARA AS DISCIPLINAS OFERECIDAS
SELECT 
   * 
FROM 
   disciplinas_ofer_compl 
WHERE 
   num_sala IS NULL OR 
   turno IS NULL OR 
   dia_semana IS NULL


-- CONFIGURA SALA/TURNO/DIA PADRAO PARA AS DISCIPLINAS
-- IMPORTANTE PARA LIBERAR A IMPRESSAO DO CADERNO DE CHAMADAS
UPDATE 
   disciplinas_ofer_compl 
SET 
   num_sala = 1 , 
   turno = 0, 
   dia_semana = -1


-- VERIFICA QUEM ESTA COM O REGISTRO PENDENTE (RA_CNEC P/ O DIARIO)
SELECT 
   * 
FROM 
   pessoas 
WHERE 
   ra_cnec IS NULL 

-- RECUPERA ALUNOS MATRICULADOS E SEUS CONTRATOS POR DISCIPLINA OFERECIDA
-- BOM PARA RECUPERAR INFORMA��ES COM VISTAS A MATRICULAR ESTES ALUNOS EM OUTRA DISCIPLINA
SELECT 
   p.nome, m.ref_pessoa || ',' || m.ref_contrato AS Matriculas 
FROM 
   matricula m, pessoas p
WHERE 
   p.id = m.ref_pessoa AND
   m.ref_disciplina_ofer = 1081

-- RECUPERA OS IDS DOS ALUNOS E CONTRATOS BASEADO EM DISCIPLINA NO QUAL OS ALUNOS J� POSSUEM MATRICULA
SELECT 
   ref_pessoa  || ',' || ref_contrato as "Matr�cula" 
FROM 
    matricula 
WHERE 
    ref_disciplina_ofer = 1014 

-- RECUPERA PERIODOS ONDE O ALUNO EST� MATRICULADO
SELECT 
   DISTINCT 
      a.ref_periodo
   FROM matricula a
   WHERE
      a.ref_pessoa = '2064'


-- RECUPERA TODAS AS DISCIPLINAS ONDE O ALUNO EST� MATRICULADO POR PER�ODO
SELECT 
   DISTINCT 
      a.ref_disciplina, b.descricao_disciplina
   FROM matricula a, disciplinas b 
   WHERE 
      a.ref_periodo = '0601' AND 
      a.ref_disciplina = b.id AND
      a.ref_pessoa = '2064'
   ORDER BY
         b.descricao_disciplina



-- RECUPERA NOTAS E FALTAS DO ALUNO POR DISCIPLINA E POR PERIODO
SELECT 
   DISTINCT 
      b.nome, b.ra_cnec, a.ordem_chamada, a.nota_final, a.num_faltas 
   FROM matricula a, pessoas b 
   WHERE 
      a.ref_periodo = '0601' AND 
      a.ref_disciplina = '102013' AND 
      a.ref_pessoa = b.id AND
      a.ref_pessoa = '2064'
      
-- RECUPERA TODAS AS NOTAS E FALTAS PARA TODAS AS DISCIPLINAS DE DETERMINADO PER�ODO POR ALUNO
SELECT 
   DISTINCT 
     c.descricao_disciplina, b.ra_cnec, a.ordem_chamada, a.nota_final, a.num_faltas 
   FROM matricula a, pessoas b, disciplinas c 
   WHERE 
      a.ref_periodo = '0601' AND 
      a.ref_disciplina 
            IN ( 
                  SELECT 
                     DISTINCT 
                        a.ref_disciplina
                     FROM matricula a, disciplinas b 
                     WHERE
                        a.ref_periodo = '0601' AND 
                        a.ref_disciplina = b.id AND
                        a.ref_pessoa = '2064'                      
             ) AND 
      a.ref_disciplina = c.id AND
      a.ref_pessoa = b.id AND
      a.ref_pessoa = '2064'
      ORDER BY
         c.descricao_disciplina

-- RECUPERA AS NOTAS PARCIAIS POR ALUNO
SELECT DISTINCT 
    b.nome, c.nota, ref_diario_avaliacao
  FROM 
    matricula a, pessoas b, diario_notas c 
  WHERE 
    a.ref_periodo = '0602' AND 
    a.ref_disciplina = '101015' AND 
    a.ref_disciplina_ofer = '1317' AND
    b.ra_cnec = c.ra_cnec AND
    c.d_ref_disciplina_ofer = '1317' AND
    a.ref_pessoa = b.id AND
    b.ra_cnec = 2408
  ORDER BY 3;

-- RECUPERA AS NOTAIS TOTAIS (SEM CONSIDERAR A NOTA EXTRA) DO DI�RIO E POR DISCIPLINA OFERECIDA
SELECT * FROM
(
SELECT DISTINCT 
    a.ref_disciplina_ofer, c.ra_cnec,  b.nome, SUM(CAST(c.nota AS NUMERIC)) AS nota, CAST(a.nota_final AS NUMERIC), a.ref_curso
  FROM 
    matricula a, pessoas b, diario_notas c 
  WHERE 
    a.ref_disciplina_ofer = c.d_ref_disciplina_ofer AND
    b.ra_cnec = c.ra_cnec AND
    a.ref_pessoa = c.ra_cnec AND
    c.d_ref_disciplina_ofer IN (

SELECT 
   DISTINCT
      d.id as idof      
   FROM 
      matricula m,
      disciplinas_ofer d
   WHERE 
      m.ref_disciplina_ofer = d.id AND
      m.ref_disciplina = d.ref_disciplina AND
      d.ref_periodo = '0701' AND
      m.ref_curso = 501 AND
      d.is_cancelada = 0
   ORDER BY d.id
) AND
    ref_diario_avaliacao < 7 AND    
    a.ref_pessoa = b.id
  GROUP BY c.ra_cnec, b.nome, a.ref_disciplina_ofer, a.nota_final, a.ref_curso
  ORDER BY B.nome
) as T1
WHERE
nota > nota_final





-- FALTAS TOTAIS


SELECT * FROM
(
SELECT DISTINCT
   c.ra_cnec,  c.nome, a.ref_disciplina_ofer, CAST(count(a.ra_cnec) AS INTEGER) AS faltas, CAST(b.num_faltas AS INTEGER)
FROM 
    diario_chamadas a, matricula b, pessoas c
WHERE 
c.ra_cnec = a.ra_cnec AND
b.ref_pessoa = a.ra_cnec AND
a.ref_disciplina_ofer = b.ref_disciplina_ofer AND
 a.ref_disciplina_ofer IN (

SELECT 
   DISTINCT
      d.id as idof      
   FROM 
      matricula m,
      disciplinas_ofer d
   WHERE 
      m.ref_disciplina_ofer = d.id AND
      m.ref_disciplina = d.ref_disciplina AND
      d.ref_periodo = '0701' AND
      m.ref_curso = 502 AND
      d.is_cancelada = 0
   ORDER BY d.id
)
GROUP BY c.ra_cnec, c.nome, a.ref_disciplina_ofer, num_faltas
ORDER BY c.nome
) AS T1
WHERE
num_faltas < faltas

-- < //




-- RECUPERA A CARGA HORARIA REALIZADA PARA UMA DETERMINADA DISCIPLINA

SELECT 
      SUM(CAST(flag AS INTEGER)) AS carga
   FROM 
      diario_seq_faltas 
   WHERE 
      periodo = '0601' AND 
      disciplina = 603010 AND 
      ref_disciplina_ofer = 978 


SELECT
      B.id as idof,
      A.ref_disciplina,
      A.ref_pessoa, 
      A.ref_contrato, 
      A.ref_curso,
      A.ref_disciplina_ofer
   FROM 
      matricula A,
      disciplinas_ofer B
   WHERE 
      A.ref_pessoa IN (2049, 2050, 1436, 2051, 2052, 486, 2053, 463, 2054, 2056, 2057, 1332, 2058, 2059, 2060, 1264, 2061, 2062, 2063 ) AND
      A.ref_disciplina_ofer = 987 AND
      A.ref_disciplina = B.ref_disciplina AND
      A.ref_curso = B.ref_curso AND
      A.ref_pessoa = 2049 AND
      B.is_cancelada = 0 AND
      B.id <> 987 AND
      ( B.ref_curso = 301 OR B.ref_curso = 505 ) 
   ORDER BY A.ref_disciplina, B.id, A.ref_pessoa, A.ref_contrato
   


--- SELECIONA DISCIPLINA OFERECIDA POR PROFESSOR E DISCIPLINA
select d.id from
disciplinas_ofer d, disciplinas_ofer_prof dp
where d.ref_disciplina = 601017 AND
      d.ref_curso = 601 AND
      d.ref_periodo = '06011' AND
      d.is_cancelada = 0 AND
      dp.ref_professor = 2512 AND
      dp.ref_disciplina_ofer = d.id



-- ATUALIZA O CODIGO DA DISCIPLINA OFERECIDA NO DIARIO
UPDATE 
      diario_seq_faltas 
   SET 
      ref_disciplina_ofer = 119
   WHERE 
      disciplina = 503005 AND
      curso = 505 AND
      periodo = '0601' AND
      id_prof = 2469
   
UPDATE 
      matricula 
   SET 
      ref_disciplina_ofer = 55
   WHERE
      ref_pessoa = 2469  AND
      ref_disciplina = 301040   AND
      ref_curso = 301   AND
      ref_disciplina_ofer = 55


UPDATE 
      matricula 
   SET 
      ref_disciplina_ofer = 1376
   WHERE
      ref_disciplina_ofer = 1081 AND
      ref_pessoa IN (2049,
2050,
1436,
2051,
2060,
1264,
2061,
2062,
2063 )

       
       


--- LISTA ALUNOS MATRICULADOS NO PER�ODO

SELECT 
      id as "Matrícula",
      nome as "Nome Completo",
      dt_nascimento as "Data Nasc."
      ref_curso
   FROM
      pessoas, matricula
   WHERE
         id IN (
                  SELECT 
                     DISTINCT
                        A.ref_pessoa
                     FROM 
                        matricula A
                     WHERE
                        ref_periodo = '0602' OR ref_periodo = '06021' OR ref_periodo = '06022'
                     ORDER BY A.ref_pessoa
         )  
   ORDER BY nome


--- LISTA ALUNOS MATRICULADOS NO PER�ODO COM O RESPECTIVO CURSO
SELECT 
    DISTINCT
      a.id as "Matrícula",
      a.nome as "Nome Completo",
      a.dt_nascimento as "Data Nasc.",
      b.ref_curso, c.ref_periodo_turma, c.dt_ativacao
   FROM
      pessoas a, matricula b, contratos c
   WHERE
         a.id IN (
                  SELECT 
                     DISTINCT
                        A.ref_pessoa
                     FROM 
                        matricula A
                     WHERE
                        ref_periodo = '0602' OR ref_periodo = '06021' OR ref_periodo = '06022'
                     ORDER BY A.ref_pessoa
         )  AND
      a.id = b.ref_pessoa AND
      c.ref_pessoa = a.id AND
      c.id = b.ref_contrato AND
      b.ref_curso IN (106,107,103,108,104)
   ORDER BY  nome, dt_ativacao


SELECT 
     DISTINCT
      p.id as "Matrícula",
      p.nome as "Nome Completo",
      p.dt_nascimento as "Data Nasc.",
      p.sexo
   FROM
      pessoas p
   WHERE
         p.id IN (
                  SELECT 
                     DISTINCT
                        A.ref_pessoa
                     FROM 
                        matricula A
                     WHERE
                        (ref_periodo = '0602' OR ref_periodo = '06021' OR ref_periodo = '06022') AND
      ( ref_curso IN (601,103,108,610,901,608,302,303,109,611,101,609,503,504,505,105,107,301,604,605,606) )
                     ORDER BY A.ref_pessoa
         )
   ORDER BY p.nome



--- LISTA ALUNOS MATRICULADOS NO PER�ODO COM CURSO (LIVRO MATR�CULA) 
--- POR CURSO / GERAR ETIQUETAS
SELECT 
     DISTINCT
      p.id as "Matrícula",
      p.nome as "Nome Completo",
      p.dt_nascimento as "Data Nasc.",
      p.sexo, 
      c.nome || ' - ' ||  c.ref_estado as cidade,
      f.mae_nome,
      p.complemento,
      p.rua, p.bairro, p.cep, f.pai_nome
      
   FROM
      pessoas p
   LEFT OUTER JOIN
      aux_cidades c
         ON (p.ref_cidade = c.id)
   LEFT OUTER JOIN
      filiacao f
         ON (p.ref_filiacao = f.id)
   LEFT OUTER JOIN
      contratos ctr
         ON (p.id = ctr.ref_pessoa)
   LEFT OUTER JOIN
      cursos crs
         ON (ctr.ref_curso = crs.id)
   WHERE
         p.id IN (
                  SELECT 
                     DISTINCT
                        A.ref_pessoa
                     FROM 
                        matricula A
                     WHERE
                        ref_periodo = '0601' AND
			ref_curso IN (501,502)
                     ORDER BY A.ref_pessoa
         )
   ORDER BY f.mae_nome, p.nome


--- POR ALUNO / GERAR ETIQUETAS
SELECT 
     DISTINCT
      p.id as "Matrícula",
      p.nome as "Nome Completo",
      p.dt_nascimento as "Data Nasc.",
      p.sexo, 
      c.nome || ' - ' ||  c.ref_estado as cidade,
      f.mae_nome,
      p.complemento,
      p.rua, p.bairro, p.cep, f.pai_nome
      
   FROM
      pessoas p
   LEFT OUTER JOIN
      aux_cidades c
         ON (p.ref_cidade = c.id)
   LEFT OUTER JOIN
      filiacao f
         ON (p.ref_filiacao = f.id)
   LEFT OUTER JOIN
      contratos ctr
         ON (p.id = ctr.ref_pessoa)
   LEFT OUTER JOIN
      cursos crs
         ON (ctr.ref_curso = crs.id)
   WHERE
          p.id IN (83 ,
1002  ,
1014  ,
1017  ,
3241  ,
3235  
)
   ORDER BY f.mae_nome, p.nome


SELECT 
  DISTINCT
      ref_pessoa, ref_curso
  FROM
      contratos
    WHERE
      turma IN ('71C','61C','51C','71S','62S','61S');
   

-- Dados do cadastro
SELECT 
     DISTINCT
      p.id as "Matrícula",
      p.nome as "Nome Completo",ro
      p.dt_nascimento as "Data Nasc.",
      p.sexo, 
      c.nome || ' - ' ||  c.ref_estado as cidade,
      p.rua, p.complemento, p.bairro, p.cep, p.estado_civil, p.rg_numero AS "RG", p.cod_cpf_cgc AS "CPF", p.fone_particular AS "FONE"
      
   FROM
      pessoas p
   LEFT OUTER JOIN
      aux_cidades c
         ON (p.ref_cidade = c.id)
   LEFT OUTER JOIN
      filiacao f
         ON (p.ref_filiacao = f.id)
   LEFT OUTER JOIN
      contratos ctr
         ON (p.id = ctr.ref_pessoa)
   LEFT OUTER JOIN
      cursos crs
         ON (ctr.ref_curso = crs.id)
   WHERE
          p.id IN (795	,
663	,
819	,
820	,
822	,
245	,
824


)
   ORDER BY p.nome

-- DADOS CADASTRAIS DO SAGU PARA LAN�AMENTO NO SISTEMA PRADO
SELECT 
     DISTINCT
      p.id as "Matrícula",
      p.nome as "Nome Completo",
      p.dt_nascimento as "Data Nasc.",
      p.sexo, 
      p.cod_cpf_cgc AS "CPF", p.fone_particular AS "FONE"
      
   FROM
      pessoas p
   LEFT OUTER JOIN
      aux_cidades c
         ON (p.ref_cidade = c.id)
   LEFT OUTER JOIN
      filiacao f
         ON (p.ref_filiacao = f.id)
   LEFT OUTER JOIN
      contratos ctr
         ON (p.id = ctr.ref_pessoa)
   LEFT OUTER JOIN
      cursos crs
         ON (ctr.ref_curso = crs.id)
   WHERE
          p.id IN (83 ,
1002  ,
2781	,
2782	,
2783	,
2784	,
2785	,
2786	,
2787

)
   ORDER BY p.nome



-- RECUPERA OS ALUNOS QUE N�O POSSUEM OS REGISTROS NECESS�RIOS AO LAN�AMENTO DE NOTAS EM DETERMINADA DISCIPLINA OFERECIDA
SELECT m.ref_pessoa, id_ref_pessoas FROM matricula m LEFT JOIN (SELECT DISTINCT d.id_ref_pessoas FROM diario_notas d WHERE d.d_ref_disciplina_ofer = 1069) tmp ON (m.ref_pessoa = id_ref_pessoas) WHERE m.ref_disciplina_ofer = 1069 AND id_ref_pessoas IS NULL ORDER BY id_ref_pessoas;



SELECT 
      p.id as "Matrícula",
      p.nome as "Nome Completo",
      p.dt_nascimento as "Data Nasc.",
      p.sexo, 
      c.nome || ' - ' ||  c.ref_estado as cidade,
      f.pai_nome,
      f.mae_nome,
      crs.id,
      crs.abreviatura,
      crs.descricao
      
   FROM
      pessoas p
   LEFT OUTER JOIN
      aux_cidades c
         ON (p.ref_naturalidade = c.id)
   LEFT OUTER JOIN
      filiacao f
         ON (p.ref_filiacao = f.id)
   LEFT OUTER JOIN
      contratos ctr
         ON (p.id = ctr.ref_pessoa)
   LEFT OUTER JOIN
      cursos crs
         ON (ctr.ref_curso = crs.id)
   WHERE
         p.id IN (
                  SELECT 
                     DISTINCT
                        A.ref_pessoa
                     FROM 
                        matricula A
                     WHERE
                        ref_periodo = '0601'
                     ORDER BY A.ref_pessoa
         )
   ORDER BY crs.id, crs.descricao, p.nome



-- ATUALIZAR LANCAMENTO DE NOTAS NO DIARIO P/ ALUNOS ATRASADOS
--$grupo = ($idPROF . "-" . $getperiodo . "-" . $getdisciplina . "-" . $getofer);

 "INSERT INTO diario_formulas (ref_prof, ref_periodo, ref_disciplina, prova, descricao, formula, grupo) values('$id','$getperiodo','$getdisciplina','$num_prova','$descricao_prova','$frm','$grupo')";
 
 SELECT 
   * 
   FROM
         diario_formulas
   WHERE
        grupo ILIKE '%-709'
        
 SELECT 
   * 
   FROM
         diario_notas
   WHERE
         rel_diario_formulas_grupo ILIKE '%-709'

-- SELECIONA CIDADES DOS ALUNOS DO PERIODO INDICADO
SELECT 
      DISTINCT 
        a.nome || ' - ' || a.ref_estado AS "Cidades de Origem - 1� Sem. 2006"
   FROM
      aux_cidades a, pessoas b, matricula c 
   WHERE
        a.id = b.ref_cidade AND
        b.id = c.ref_pessoa 



-- RECUPERA ID ALUNO E CONTRATO POR CURSO E ID DO ALUNO

SELECT 
         ref_pessoa AS "Pessoa", id AS "Contrato"
      FROM
         contratos
      WHERE
         ref_curso = 605 AND
         ref_pessoa IN (
1413  ,
1414  ,
1416  ,
1417  ,
1420  ,
1421  ,
1422  ,
1423  ,
1424  ,
1425  ,
1426  ,
1427  ,
1302  ,
1429  ,
1431  ,
1432  ,
1433  ,
1434  ,
956 ,
1435  ,
1437  ,
988 ,
1439  ,
1440  ,
1442  ,
1443  ,
1444  ,
1262  ,
1445  ,
1447  ,
1449  ,
453 
	)


-- EXCLUI ALUNO MATRICULADO INDEVIDAMENTE
BEGIN;
DELETE
   FROM
     matricula
   WHERE
	ref_pessoa = 95 AND
        ref_disciplina_ofer = 1216;

DELETE
   FROM
     diario_notas
   WHERE
	id_ref_pessoas = 95 AND
        d_ref_disciplina_ofer = 1216;

DELETE
   FROM
     diario_chamadas
   WHERE
	ra_cnec = 95 AND
        ref_disciplina_ofer = 1216;
COMMIT;


INSERT INTO 
   diario_notas (
         ra_cnec, 
         ref_diario_avaliacao, 
         nota, 
         peso, 
         id_ref_pessoas, 
         id_ref_periodos, 
         id_ref_curso, 
         d_ref_disciplina_ofer, 
         rel_diario_formulas_grupo
   ) 
   VALUES (
      '2373',
      '1',
      '0','0',
      '2373',
      '0601',
      '505',
      '987',
      '2469-0601-503005-987');



--$grupo = ($idPROF . "-" . $getperiodo . "-" . $getdisciplina . "-" . $getofer);



-- CONTA MATRICULAS DO(S) PERIODO(S) ESPECIFICADO
SELECT
      count(*)
   FROM 
      matricula A
   WHERE 
      A.ref_periodo IN ( '0602', '06021', '06022')

SELECT
      count(*)
   FROM 
      matricula A
   WHERE 
      A.ref_periodo ILIKE '07%'


SELECT
      B.id as idof,
      A.ref_disciplina,
      A.ref_pessoa, 
      A.ref_contrato, 
      A.ref_curso
   FROM 
      matricula A,
      disciplinas_ofer B
   WHERE 
      A.ref_pessoa IN (2049, 2050, 1436, 2051, 2052, 486, 2053, 463, 2054, 2056, 2057, 1332, 2058, 2059, 2060, 1264, 2061, 2062, 2063 ) AND
      A.ref_disciplina_ofer = 987 AND
      A.ref_disciplina = B.ref_disciplina AND
      A.ref_contrato = B.ref_contrato AND
      ( B.ref_curso = 301 OR B.ref_curso = 505 ) 
   order by A.ref_disciplina, B.id, A.ref_pessoa, A.ref_contrato




--- chamadas
SELECT 
  DISTINCT 
      id, id_prof, periodo, curso, disciplina, dia, ref_disciplina_ofer AS idof
  FROM 
      diario_seq_faltas 
  WHERE 
    id_prof = '2472' AND 
    periodo = '0701' AND 
    disciplina = '107001' AND 
    ref_disciplina_ofer = '1715';

-- chamadas com faltas
SELECT 
    c.dia, count(t.dia) 
  FROM 
    diario_chamadas a, pessoas b, diario_seq_faltas c, (
SELECT 
  DISTINCT 
      id, id_prof, periodo, curso, disciplina, dia, ref_disciplina_ofer AS idof
  FROM 
      diario_seq_faltas 
  WHERE 
    id_prof = '2472' AND 
    periodo = '0701' AND 
    disciplina = '107001' AND 
    ref_disciplina_ofer = '1715'
) AS t
  WHERE 
    a.data_chamada = c.dia AND 
    a.ref_professor = 2472 AND 
    a.ref_periodo = '0701' AND 
    a.ref_disciplina = 107001 AND 
    a.ref_disciplina_ofer = 1715 AND 
    c.ref_disciplina_ofer = 1715
  GROUP BY c.dia
  ORDER BY c.dia;



SELECT 
  DISTINCT 
    t.idof, c.dia, count(t.dia) 
  FROM 
    diario_chamadas a, pessoas b, diario_seq_faltas c, (
SELECT 
  DISTINCT
      id, id_prof, periodo, curso, disciplina, dia, ref_disciplina_ofer AS idof
  FROM 
      diario_seq_faltas 
  WHERE 
    id_prof = '2472' AND 
    periodo = '0701' AND 
    disciplina = '107001' AND 
    ref_disciplina_ofer = '1715'
) AS t
  WHERE 
     a.ref_professor = 2472 AND 
    a.ref_periodo = '0701' AND
    a.ref_disciplina_ofer = 1715 AND 
    c.ref_disciplina_ofer = 1715
  GROUP BY c.dia, t.idof
  ORDER BY c.dia;


SELECT 
  idof, dia, flag, count(T2.dia) AS num
FROM
  (
    SELECT 
      DISTINCT
        dia, ref_disciplina_ofer AS idof, flag
      FROM 
        diario_seq_faltas 
      WHERE 
        id_prof = '2472' AND 
        periodo = '0701' AND 
        disciplina = '107001' AND 
        ref_disciplina_ofer = '1715' 
  ) AS T1 
  
LEFT OUTER JOIN
  ( 
    SELECT 
      DISTINCT
        a.data_chamada AS dia, a.ref_disciplina_ofer
      FROM 
        diario_chamadas a, pessoas b, diario_seq_faltas c
      WHERE 
        a.ref_professor = 2472 AND 
        a.ref_periodo = '0701' AND 
        a.ref_disciplina = 107001 AND 
        a.ref_disciplina_ofer = 1715 AND 
        c.ref_disciplina_ofer = 1715
  ) AS T2
USING(dia)
GROUP BY idof, dia, flag
ORDER BY dia


SELECT 
          a.ref_disciplina_ofer, a.ra_cnec, COUNT(a.ra_cnec)
      FROM 
         pessoas b
       LEFT OUTER JOIN
         diario_chamadas a
          ON (b.id = a.ra_cnec)
      WHERE 
        a.ref_professor = 2472 AND 
        a.ref_periodo = '0701' AND 
        a.ref_disciplina = 107001 AND
        a.data_chamada = '05/02/2007' AND
        a.ref_disciplina_ofer = 1715 
GROUP BY a.ref_disciplina_ofer, a.ra_cnec



SELECT DISTINCT
  p.nome,
  p.id,a.ref_disciplina_ofer, a.ra_cnec, COUNT(a.ra_cnec) AS faltas
FROM  
  diario_chamadas a, pessoas p
WHERE
  (a.ref_periodo = '0701') AND
  (a.ref_disciplina_ofer = '1715') AND
  a.ra_cnec IN 
  (
    SELECT DISTINCT
  p.id
FROM
  matricula m
  INNER JOIN pessoas p ON (m.ref_pessoa = p.id)
WHERE
  (m.ref_periodo = '0701') AND
  (m.ref_disciplina_ofer = '1715') AND
  (m.dt_cancelamento is null)
)
GROUP BY p.nome, p.id, a.ref_disciplina_ofer, a.ra_cnec


SELECT DISTINCT
  p.id
FROM
  matricula m
  INNER JOIN pessoas p ON (m.ref_pessoa = p.id)
WHERE
  (m.ref_periodo = '0701') AND
  (m.ref_disciplina_ofer = '1715') AND
  (m.dt_cancelamento is null)



SELECT DISTINCT
  p.nome,
  p.id,
  p.ra_cnec
FROM
  matricula m
  INNER JOIN pessoas p ON (m.ref_pessoa = p.id)
WHERE
  (m.ref_periodo = '0701') AND
  (m.ref_disciplina_ofer = '1715') AND
  (m.dt_cancelamento is null)


SELECT 
  T1.nome, T1.id, T1.ra_cnec, COUNT(T2.ra_cnec) AS faltas
FROM
  (
    SELECT DISTINCT
  p.nome,
  p.id,
  p.ra_cnec
FROM
  matricula m
  INNER JOIN pessoas p ON (m.ref_pessoa = p.id)
WHERE
  (m.ref_periodo = '0701') AND
  (m.ref_disciplina_ofer = '1715') AND
  (m.dt_cancelamento is null)
 
  ) AS T1 
  
LEFT OUTER JOIN
  ( 
    SELECT
  a.ra_cnec
FROM  
  diario_chamadas a
  LEFT OUTER JOIN pessoas p ON (p.id = a.ra_cnec)
WHERE
  (a.ref_periodo = '0701') AND
  (a.ref_disciplina_ofer = '1715') AND
   (p.id = a.ra_cnec) 
  ) AS T2
USING(ra_cnec)
GROUP BY T1.nome, T1.id, T1.ra_cnec
ORDER BY T1.nome

SELECT
  *
FROM  
  diario_chamadas a
  LEFT OUTER JOIN pessoas p ON (p.id = a.ra_cnec)
WHERE
  (a.ref_periodo = '0701') AND
  (a.ref_disciplina_ofer = '1715') AND
  (p.id = a.ra_cnec) AND
  (a.data_chamada = '05/02/2007')



SELECT
  T1.nome, T1.id, T1.ra_cnec, COUNT(T2.ra_cnec) AS faltas
FROM
  (
    SELECT DISTINCT
  p.nome,
  p.id,
  p.ra_cnec
FROM
  matricula m
  INNER JOIN pessoas p ON (m.ref_pessoa = p.id)
WHERE
  (m.ref_periodo = '0701') AND
  (m.ref_disciplina_ofer = '1715') AND
  (m.dt_cancelamento is null)

  ) AS T1

LEFT OUTER JOIN
  (
    SELECT
  a.ra_cnec
FROM
  diario_chamadas a
  LEFT OUTER JOIN pessoas p ON (p.id = a.ra_cnec)
WHERE
  (a.ref_periodo = '0701') AND
  (a.ref_disciplina_ofer = '1715') AND
  (a.data_chamada = '05/02/2007') AND
   (p.id = a.ra_cnec)
  ) AS T2
USING(ra_cnec)
GROUP BY T1.nome, T1.id, T1.ra_cnec
ORDER BY T1.nome;


SELECT ra_cnec, a.ref_disciplina_ofer, count(ra_cnec) AS faltas
FROM diario_chamadas a 
WHERE (a.ref_periodo = '0701') AND
 (a.ref_disciplina_ofer = '1896')
GROUP BY ra_cnec, a.ref_disciplina_ofer

SELECT 
  DISTINCT 
    c.descricao_disciplina, b.ra_cnec, a.ordem_chamada, a.nota_final, c.carga_horaria, a.ref_curso, a.num_faltas 
  FROM 
    matricula a, pessoas b, disciplinas c 
  WHERE 
      a.ref_periodo = '07' AND
      a.ref_disciplina IN ( SELECT DISTINCT a.ref_disciplina FROM matricula a, disciplinas b WHERE a.ref_disciplina = b.id AND a.ref_periodo = '07' AND a.ref_pessoa = 3391 ) AND a.ref_disciplina = c.id AND a.ref_pessoa = b.id AND a.ref_curso = 113 AND a.ref_pessoa = 3391 ORDER BY b.ra_cnec, c.descricao_disciplina; 


SELECT DISTINCT 
c.descricao_disciplina, b.ra_cnec, a.ordem_chamada, a.nota_final, c.carga_horaria, a.ref_curso, a.num_faltas 
FROM matricula a, pessoas b, disciplinas c 
WHERE a.ref_periodo = '07' AND a.ref_disciplina IN ( SELECT DISTINCT a.ref_disciplina FROM matricula a, disciplinas b WHERE a.ref_disciplina = b.id AND a.ref_periodo = '07' AND a.ref_pessoa = 3391 ) AND a.ref_disciplina = c.id AND a.ref_pessoa = b.id AND a.ref_curso = 113 AND a.ref_pessoa = 3391 ORDER BY b.ra_cnec, c.descricao_disciplina;


SELECT DISTINCT p.nome, pe.descricao, d.descricao_disciplina, m.nota_final, m.num_faltas, pe.media_final, d.carga_horaria FROM pessoas p, matricula m, disciplinas d, cursos c, periodos pe WHERE m.ref_disciplina = d.id AND m.ref_curso = 113 AND m.ref_pessoa = 3391 AND p.id = 3391 AND m.ref_periodo = '07' AND m.ref_pessoa = p.id AND m.ref_periodo = pe.id AND c.id = m.ref_curso ORDER BY pe.descricao, d.descricao_disciplina


INSERT INTO diario_notas(ra_cnec, ref_diario_avaliacao,nota,peso,id_ref_pessoas, id_ref_periodos,id_ref_curso,d_ref_disciplina_ofer, rel_diario_formulas_grupo) VALUES(2318,'1','0','0',2318,'0701',, 1641,'3699-0701-501014-1641');


SELECT
  a.ra_cnec, b.nome, data_chamada, count(a.ra_cnec) as faltas
  FROM
    diario_chamadas a, pessoas b
    WHERE
      (a.ref_periodo = '0701') AND
      (a.ra_cnec = b.id) AND
        (a.ref_disciplina_ofer = '1641') AND
          (a.data_chamada IN
(
    SELECT dia FROM diario_seq_faltas WHERE id_prof = '3699' AND periodo = '0701' AND disciplina = '501014' AND ref_disciplina_ofer = '1641' ORDER BY dia desc
) 
  ) AND
  a.ra_cnec IN (
      SELECT DISTINCT
         b.nome, b.ra_cnec, a.ordem_chamada, a.nota_final, a.num_faltas
         FROM matricula a, pessoas b
         WHERE
            a.ref_periodo = '$periodo' AND
            a.ref_disciplina = '$disciplina' AND
            a.ref_disciplina_ofer = '$oferecida' AND
            a.ref_pessoa = b.id
         ORDER BY 3;" ;


)

          GROUP BY a.ra_cnec,b.nome, data_chamada
order by data_chamada


SELECT dia FROM diario_seq_faltas WHERE id_prof = '3699' AND periodo = '0701' AND disciplina = '501014' AND ref_disciplina_ofer = '1641' ORDER BY dia desc

SELECT dia FROM diario_seq_faltas WHERE id_prof = '3699' AND periodo = '0701' AND disciplina = '501014' AND ref_disciplina_ofer = '1641' ORDER BY dia desc ;


SELECT DISTINCT
          b.id, b.nome, data_chamada, count(c.ra_cnec) as faltas, a.num_faltas
         FROM matricula a, pessoas b, diario_chamadas c
         WHERE
            a.ref_periodo = '0701' AND
           a.ref_disciplina_ofer = '1641' AND
            a.ref_pessoa = b.id AND
           a.ref_disciplina_ofer = c.ref_disciplina_ofer
 GROUP BY b.id,b.nome, data_chamada



SELECT dia, CASE 
                        WHEN faltas IS NULL THEN '0' 
                        ELSE faltas
                    END AS faltas
FROM
(
SELECT DISTINCT
          c.ra_cnec, data_chamada, count(c.ra_cnec) as faltas          FROM diario_chamadas c
         WHERE
            c.ref_periodo = '0701' AND
           c.ref_disciplina_ofer = '1641' AND
           c.ra_cnec = 2235
        GROUP BY c.ra_cnec, data_chamada
) AS T1
FULL OUTER JOIN
(
SELECT DISTINCT dia FROM diario_seq_faltas WHERE id_prof = '3699' AND periodo = '0701' AND ref_disciplina_ofer = '1641' ORDER BY dia
) AS T2 ON (data_chamada = dia)

ORDER BY dia



$sql5 = "SELECT dia, CASE
                        WHEN faltas IS NULL THEN '0'
                        ELSE faltas
                    END AS faltas
FROM
(
SELECT DISTINCT
          c.ra_cnec, data_chamada, count(c.ra_cnec) as faltas          FROM diario_chamadas c
         WHERE
            c.ref_periodo = '$getperiodo' AND
           c.ref_disciplina_ofer = '$getofer' AND
           c.ra_cnec = %s
        GROUP BY c.ra_cnec, data_chamada
) AS T1
FULL OUTER JOIN
(
SELECT DISTINCT dia FROM diario_seq_faltas WHERE id_prof = '$id' AND periodo = '$getperiodo' AND ref_disciplina_ofer = '$getofer' ORDER BY dia
) AS T2 ON (data_chamada = dia)

ORDER BY dia;";

