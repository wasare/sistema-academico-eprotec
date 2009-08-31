-- exclui 97 tabelas nao utilizadas
TRUNCATE TABLE acordos_obs; DROP TABLE acordos_obs RESTRICT;
TRUNCATE TABLE atestados; DROP TABLE atestados RESTRICT;
TRUNCATE TABLE autorizacao; DROP TABLE autorizacao RESTRICT;
TRUNCATE TABLE aux_bairros; DROP TABLE aux_bairros RESTRICT;
TRUNCATE TABLE aux_bolsas; DROP TABLE aux_bolsas RESTRICT;
TRUNCATE TABLE aux_empresas; DROP TABLE aux_empresas RESTRICT;
TRUNCATE TABLE aux_ruas; DROP TABLE aux_ruas RESTRICT;
TRUNCATE TABLE banco; DROP TABLE banco RESTRICT;
TRUNCATE TABLE bolsas; DROP TABLE bolsas RESTRICT;
TRUNCATE TABLE cadastro_duplo; DROP TABLE cadastro_duplo RESTRICT;
TRUNCATE TABLE cadastros_log; DROP TABLE cadastros_log RESTRICT;
TRUNCATE TABLE calendario_academico; DROP TABLE calendario_academico RESTRICT;
TRUNCATE TABLE calendario_academico_compl; DROP TABLE calendario_academico_compl RESTRICT;
TRUNCATE TABLE carga_horaria_bimestre; DROP TABLE carga_horaria_bimestre RESTRICT;
TRUNCATE TABLE ccusto; DROP TABLE ccusto RESTRICT;
TRUNCATE TABLE cmn_grupo; DROP TABLE cmn_grupo RESTRICT;
TRUNCATE TABLE cmn_pessoas; DROP TABLE cmn_pessoas RESTRICT;
TRUNCATE TABLE cmn_vinculo; DROP TABLE cmn_vinculo RESTRICT;
TRUNCATE TABLE cobranca_pessoa; DROP TABLE cobranca_pessoa RESTRICT;
TRUNCATE TABLE cobrancas; DROP TABLE cobrancas RESTRICT;
TRUNCATE TABLE codexterno; DROP TABLE codexterno RESTRICT;
TRUNCATE TABLE competencias_cursos; DROP TABLE competencias_cursos RESTRICT;
TRUNCATE TABLE conf_finan_cont; DROP TABLE conf_finan_cont RESTRICT;
TRUNCATE TABLE conteudos_programaticos; DROP TABLE conteudos_programaticos RESTRICT;
TRUNCATE TABLE convenios_medicos; DROP TABLE convenios_medicos RESTRICT;
TRUNCATE TABLE ctrl_bancos; DROP TABLE ctrl_bancos RESTRICT;
TRUNCATE TABLE cursos_disciplinas_compl; DROP TABLE cursos_disciplinas_compl RESTRICT;
TRUNCATE TABLE diario_avaliacao; DROP TABLE diario_avaliacao RESTRICT;
TRUNCATE TABLE diario_conteudo; DROP TABLE diario_conteudo RESTRICT;
TRUNCATE TABLE diario_id; DROP TABLE diario_id RESTRICT;
TRUNCATE TABLE disciplinas_todos_alunos; DROP TABLE disciplinas_todos_alunos RESTRICT;
TRUNCATE TABLE dt_exames_periodos; DROP TABLE dt_exames_periodos RESTRICT;
TRUNCATE TABLE eleicoes; DROP TABLE eleicoes RESTRICT;
TRUNCATE TABLE email_confirmacao; DROP TABLE email_confirmacao RESTRICT;
TRUNCATE TABLE fies; DROP TABLE fies RESTRICT;
TRUNCATE TABLE fies_titulos; DROP TABLE fies_titulos RESTRICT;
TRUNCATE TABLE frequencias; DROP TABLE frequencias RESTRICT;
TRUNCATE TABLE gnuteca_pessoas; DROP TABLE gnuteca_pessoas RESTRICT;
TRUNCATE TABLE historicos; DROP TABLE historicos RESTRICT;
TRUNCATE TABLE horarios; DROP TABLE horarios RESTRICT;
TRUNCATE TABLE integra_lancamentos; DROP TABLE integra_lancamentos RESTRICT;
TRUNCATE TABLE integra_previsoes; DROP TABLE integra_previsoes RESTRICT;
TRUNCATE TABLE lancamentos_cr; DROP TABLE lancamentos_cr RESTRICT;
TRUNCATE TABLE lideres; DROP TABLE lideres RESTRICT;
TRUNCATE TABLE limites_contabeis; DROP TABLE limites_contabeis RESTRICT;
TRUNCATE TABLE livro_matricula; DROP TABLE livro_matricula RESTRICT;
TRUNCATE TABLE locais_pgto; DROP TABLE locais_pgto RESTRICT;
TRUNCATE TABLE log_titulos; DROP TABLE log_titulos RESTRICT;
TRUNCATE TABLE mensagens; DROP TABLE mensagens RESTRICT;
TRUNCATE TABLE mensagens_financeiro; DROP TABLE mensagens_financeiro RESTRICT;
TRUNCATE TABLE mot_ret_banco; DROP TABLE mot_ret_banco RESTRICT;
TRUNCATE TABLE mov_rec_banco; DROP TABLE mov_rec_banco RESTRICT;
TRUNCATE TABLE notas_bimestre; DROP TABLE notas_bimestre RESTRICT;
TRUNCATE TABLE ocorrencia_titulos; DROP TABLE ocorrencia_titulos RESTRICT;
TRUNCATE TABLE ocorr_locais_pgto; DROP TABLE ocorr_locais_pgto RESTRICT;
TRUNCATE TABLE origens; DROP TABLE origens RESTRICT;
TRUNCATE TABLE pconta; DROP TABLE pconta RESTRICT;
TRUNCATE TABLE pedagogia; DROP TABLE pedagogia RESTRICT;
TRUNCATE TABLE precos_curso; DROP TABLE precos_curso RESTRICT;
TRUNCATE TABLE previsao_lcto; DROP TABLE previsao_lcto RESTRICT;
TRUNCATE TABLE previsoes_vcto; DROP TABLE previsoes_vcto RESTRICT;
TRUNCATE TABLE regimes_disciplinas; DROP TABLE regimes_disciplinas RESTRICT;
TRUNCATE TABLE rel_curso_cc; DROP TABLE rel_curso_cc RESTRICT;
TRUNCATE TABLE sagu_header; DROP TABLE sagu_header RESTRICT;
TRUNCATE TABLE sagu_modulos; DROP TABLE sagu_modulos RESTRICT;
TRUNCATE TABLE sagu_paginas; DROP TABLE sagu_paginas RESTRICT;
TRUNCATE TABLE salarios; DROP TABLE salarios RESTRICT;
TRUNCATE TABLE saldos_contabeis; DROP TABLE saldos_contabeis RESTRICT;
TRUNCATE TABLE sdw_account_balance; DROP TABLE sdw_account_balance RESTRICT;
TRUNCATE TABLE sdw_person_balance; DROP TABLE sdw_person_balance RESTRICT;
TRUNCATE TABLE sequencial_banco; DROP TABLE sequencial_banco RESTRICT;
TRUNCATE TABLE sequencial_banco_avulso; DROP TABLE sequencial_banco_avulso RESTRICT;
TRUNCATE TABLE sequencial_banco_vest; DROP TABLE sequencial_banco_vest RESTRICT;
TRUNCATE TABLE siga_vinculos; DROP TABLE siga_vinculos RESTRICT;
TRUNCATE TABLE status_matricula; DROP TABLE status_matricula RESTRICT;
TRUNCATE TABLE tipos_cobr; DROP TABLE tipos_cobr RESTRICT;
TRUNCATE TABLE tipos_motivos; DROP TABLE tipos_motivos RESTRICT;
TRUNCATE TABLE tipos_pgto; DROP TABLE tipos_pgto RESTRICT;
TRUNCATE TABLE titulos_cr; DROP TABLE titulos_cr RESTRICT;
TRUNCATE TABLE tmp_boletos_duplicados; DROP TABLE tmp_boletos_duplicados RESTRICT;
TRUNCATE TABLE tmp_titulos_cr_dup; DROP TABLE tmp_titulos_cr_dup RESTRICT;
TRUNCATE TABLE tmp_titulos_cr_dup2; DROP TABLE tmp_titulos_cr_dup2 RESTRICT;
TRUNCATE TABLE turmas_2g; DROP TABLE turmas_2g RESTRICT;
TRUNCATE TABLE vest_ciente; DROP TABLE vest_ciente RESTRICT;
TRUNCATE TABLE vest_cursos_disp; DROP TABLE vest_cursos_disp RESTRICT;
TRUNCATE TABLE vest_cursos_inscr; DROP TABLE vest_cursos_inscr RESTRICT;
TRUNCATE TABLE vest_datas; DROP TABLE vest_datas RESTRICT;
TRUNCATE TABLE vest_gabarito; DROP TABLE vest_gabarito RESTRICT;
TRUNCATE TABLE vestibular; DROP TABLE vestibular RESTRICT;
TRUNCATE TABLE vest_inscricoes; DROP TABLE vest_inscricoes RESTRICT;
TRUNCATE TABLE vest_lingua; DROP TABLE vest_lingua RESTRICT;
TRUNCATE TABLE vest_locais; DROP TABLE vest_locais RESTRICT;
TRUNCATE TABLE vest_notas; DROP TABLE vest_notas RESTRICT;
TRUNCATE TABLE vest_provas; DROP TABLE vest_provas RESTRICT;
TRUNCATE TABLE vest_salas; DROP TABLE vest_salas RESTRICT;
TRUNCATE TABLE vest_settings; DROP TABLE vest_settings RESTRICT;
TRUNCATE TABLE vest_socio_economico; DROP TABLE vest_socio_economico RESTRICT;
