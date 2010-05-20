--
-- PostgreSQL database dump
--

SET client_encoding = 'LATIN1';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

--
-- Name: papel_papel_id_seq; Type: SEQUENCE SET; Schema: public; Owner: usrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('papel', 'papel_id'), 4, true);


--
-- Data for Name: papel; Type: TABLE DATA; Schema: public; Owner: usrsa
--

INSERT INTO papel VALUES (3, 'Professor', 'Professor');
INSERT INTO papel VALUES (2, 'Usu�rio com privil�gio total', 'Administrador');
INSERT INTO papel VALUES (1, 'Operadores do departamento de Registros Acad�micos', 'Secretaria');
INSERT INTO papel VALUES (4, 'Administra��o de matrizes', 'Administra��o de matrizes');


--
-- PostgreSQL database dump complete
--

