GRANT SELECT ON TABLE pessoas TO aluno;
GRANT SELECT,UPDATE,REFERENCES ON TABLE acesso_aluno TO aluno;
GRANT SELECT ON TABLE contratos TO aluno;
GRANT SELECT ON TABLE matricula TO aluno;
GRANT SELECT,REFERENCES ON TABLE cidade TO aluno;
GRANT SELECT ON TABLE cursos TO aluno;
GRANT SELECT ON TABLE diario_formulas TO aluno;
GRANT SELECT ON TABLE diario_notas TO aluno;
GRANT SELECT ON TABLE disciplinas TO aluno;
GRANT SELECT ON TABLE disciplinas_ofer TO aluno;
GRANT SELECT ON TABLE funcionario TO aluno;
GRANT SELECT ON TABLE periodos TO aluno;
GRANT SELECT,REFERENCES ON TABLE pessoas_fotos TO aluno;