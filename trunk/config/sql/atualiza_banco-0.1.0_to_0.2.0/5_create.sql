-- tabela nova: sessao 
CREATE TABLE sessao(
 sesskey VARCHAR( 64 ) NOT NULL DEFAULT '',
 expiry TIMESTAMP NOT NULL ,
 expireref VARCHAR( 250 ) DEFAULT '',
 created TIMESTAMP NOT NULL ,
 modified TIMESTAMP NOT NULL ,
 sessdata TEXT DEFAULT '',
 PRIMARY KEY ( sesskey )
 );

create INDEX sessao_expiry_idx on sessao( expiry );
create INDEX sessao_expireref_idx on sessao ( expireref );

REVOKE ALL ON TABLE sessao FROM PUBLIC;
REVOKE ALL ON TABLE sessao FROM pgmaster;
GRANT INSERT,SELECT,UPDATE,DELETE,REFERENCES,TRIGGER ON TABLE sessao TO pgmaster;
GRANT INSERT,SELECT,UPDATE,DELETE,REFERENCES ON TABLE sessao TO "access";
GRANT INSERT,SELECT,UPDATE,DELETE,REFERENCES ON TABLE sessao TO "admin";
GRANT INSERT,SELECT,UPDATE,DELETE,REFERENCES ON TABLE sessao TO admin_matriz;

-- função get_turno_
CREATE FUNCTION get_turno_(character varying) RETURNS character varying
    AS $_$select nome from turno where id = $1$_$
    LANGUAGE sql;

-- função get_turno
CREATE FUNCTION get_turno(character varying) RETURNS character varying
    AS $_$select case when strpos($1,'/') > 0 then trim(get_turno_(substr($1,0,strpos($1,'/'))) || '/' || get_turno(substr($1,strpos($1,'/')+1))) else trim(get_turno_($1)) end;$_$
    LANGUAGE sql;

-- função periodo_disciplina_ofer
CREATE FUNCTION periodo_disciplina_ofer(integer) RETURNS character varying
    AS $_$select ref_periodo from disciplinas_ofer where id = $1$_$
    LANGUAGE sql;

-- função get_motivo
CREATE FUNCTION get_motivo(integer) RETURNS character varying
    AS $_$select descricao from motivo where id = $1$_$
    LANGUAGE sql;

-- função nota_distribuida
CREATE FUNCTION nota_distribuida(integer) RETURNS integer
    AS $_$select cast(sum(nota_distribuida) as integer) from diario_formulas where grupo ilike '%-' || $1$_$
    LANGUAGE sql;

-- função campus_disciplina_ofer
CREATE FUNCTION campus_disciplina_ofer(integer) RETURNS integer
    AS $_$select cast(ref_campus as integer) from disciplinas_ofer where id = $1$_$
    LANGUAGE sql;

-- função instituicao_nome
CREATE FUNCTION instituicao_nome(integer) RETURNS character varying
    AS $_$select nome_atual from instituicoes where id = $1$_$
    LANGUAGE sql;

-- função descricao_periodo
CREATE FUNCTION descricao_periodo(character varying) RETURNS character varying
    AS $_$select descricao from periodos where id = $1$_$
    LANGUAGE sql;
