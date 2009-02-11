-- RECUPERA DISCIPLINAS OFERECIDAS
--- SELECIONA DISCIPLINA OFERECIDA POR CURSO E PERIODO

SELECT o.id as diario, d.descricao_disciplina || ' (' || d.id || ')' as disciplina, turma, observacao as subturma, num_alunos  FROM
disciplinas_ofer o, disciplinas d, disciplinas_ofer_compl c
WHERE o.ref_curso = 508 AND
     o.ref_periodo = '0801' AND
     o.is_cancelada = 0 AND
     o.ref_disciplina = d.id AND
     o.id = c.ref_disciplina_ofer
ORDER BY 2,3,4

SELECT
   DISTINCT
     a.id, a.nome, c.ref_curso, c.dt_ativacao, c.id as contrato, c.turma
  FROM
     pessoas a, contratos c
  WHERE
        a.id IN (
                 SELECT
                    DISTINCT
                       ref_pessoa
                    FROM
                        contratos
                    WHERE
                       ref_curso = 301 AND
                       turma IN (
                               SELECT
                                  DISTINCT
                                       turma
                                  FROM
                                       disciplinas_ofer o
                                   WHERE
                                       o.ref_curso = 301 AND
                                       o.ref_periodo = '0801' AND
                                             o.is_cancelada = 0
                       )
        )  AND
     a.id = c.ref_pessoa AND
     ref_curso = 301 AND
     c.dt_desativacao IS NULL
  ORDER BY  turma, nome

-- RECUPERA ALUNOS QUE PODEM SER MATRICULADOS DE ACORDO COM OS CRITÉRIOS
-- CURSO, PERIODO E TURMA