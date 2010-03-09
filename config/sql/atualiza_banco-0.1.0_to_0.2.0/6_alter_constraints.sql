-- adiciona restricoes as tabelas - integridade referencial

-- acesso_aluno
-- Select  * from (select id, "AlunoID" from "AcessoAluno" LEFT OUTER JOIN pessoas ON ("AlunoID" = id)) as T1 where id is null
-- select * from "AcessoAluno" where "AlunoID" IN (2085	,2127	,2175	,2533);
-- delete from "AcessoAluno" where "AlunoID" IN (2085 ,2127   ,2175   ,2533);

ALTER TABLE acesso_aluno ADD CONSTRAINT pessoas_acesso_aluno_fkey FOREIGN KEY (ref_pessoa) REFERENCES pessoas(id) MATCH FULL;
ALTER TABLE acesso_aluno ADD CONSTRAINT acesso_aluno_ref_pessoa_unq UNIQUE (ref_pessoa);
   
-- setor
ALTER TABLE setor ADD CONSTRAINT setor_pkey PRIMARY KEY (id);


--ALTER TABLE distribuidores ADD PRIMARY KEY (dist_id);LTER TABLE acesso_aluno ADD CONSTRAINT cep_chk CHECK (char_length(cod_cep) = 8);
--  fkeyClientesPedido

--ALTER TABLE acesso_aluno ADD CONSTRAINT acesso_aluno_pkey PRIMARY KEY (acesso_aluno_id

-- usuario
ALTER TABLE usuario ADD CONSTRAINT campus_usuario_fkey FOREIGN KEY (ref_campus) REFERENCES campus(id) MATCH FULL;
ALTER TABLE usuario ADD CONSTRAINT setor_usuario_fkey FOREIGN KEY (ref_setor) REFERENCES setor(id) MATCH FULL;

-- campus
ALTER TABLE campus ADD CONSTRAINT campus_sede_fkey FOREIGN KEY (ref_campus_sede) REFERENCES campus(id) MATCH FULL;
-- faz um update na tabela compus para atualizar os registros por causa do novo campo da tabela 
UPDATE campus SET ref_campus_sede = 1;
UPDATE campus SET ref_campus_sede = 3 WHERE id = 3;


