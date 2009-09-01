-- altera tabelas existentes

-- funcionarios
--ALTER TABLE funcionarios DROP CONSTRAINT unq_siape;
--ALTER TABLE funcionarios RENAME TO funcionario;
--ALTER TABLE funcionario ADD CONSTRAINT funcionario_siape_unq UNIQUE (siape);


-- acesso_aluno
ALTER TABLE "AcessoAluno" DROP CONSTRAINT "AcessoAluno_pkey";
ALTER TABLE "AcessoAluno" RENAME TO acesso_aluno;

ALTER TABLE acesso_aluno RENAME COLUMN "AlunoID" TO ref_pessoa;
ALTER TABLE acesso_aluno RENAME COLUMN "cvSenha" TO senha;
   
-- setor
ALTER TABLE sagu_setores RENAME TO setor;

-- usuario
ALTER TABLE sagu_usuarios RENAME TO usuario;
ALTER TABLE seq_sagu_usuarios RENAME TO usuario_id_seq;
ALTER TABLE usuario ALTER COLUMN id SET DEFAULT NEXTVAL('public.usuario_id_seq');
-- GRANT ALL ON usuario_id_seq TO admin;


-- cargo
ALTER TABLE aux_cargos RENAME TO cargo;
--ALTER TABLE cargo ADD CONSTRAINT cargo_pkey PRIMARY KEY (id);
-- adicionar chave estrangeira em funcionarios para primaria em cargo


-- cidade
ALTER TABLE aux_cidades RENAME TO cidade;
ALTER TABLE seq_aux_cidades RENAME TO cidade_id_seq;
ALTER TABLE cidade ALTER COLUMN id SET DEFAULT NEXTVAL('public.cidade_id_seq');


-- estado
ALTER TABLE aux_estados RENAME TO estado;

-- pais
ALTER TABLE aux_paises RENAME TO pais;
ALTER TABLE seq_aux_paises RENAME TO pais_id_seq;
ALTER TABLE pais ALTER COLUMN id SET DEFAULT NEXTVAL('public.pais_id_seq');

-- turno
ALTER TABLE turnos RENAME TO turno;
DROP FUNCTION get_turno(character varying);
DROP FUNCTION get_turno_(character varying);

CREATE FUNCTION get_turno_(character varying) RETURNS character varying
    AS $_$select nome from turno where id = $1$_$
    LANGUAGE sql;

CREATE FUNCTION get_turno(character varying) RETURNS character varying
    AS $_$select case when strpos($1,'/') > 0 then trim(get_turno_(substr($1,0,strpos($1,'/'))) || '/' || get_turno(substr($1,strpos($1,'/')+1))) else trim(get_turno_($1)) end;$_$
    LANGUAGE sql;


