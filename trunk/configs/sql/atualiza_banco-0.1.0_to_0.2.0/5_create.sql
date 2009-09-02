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

