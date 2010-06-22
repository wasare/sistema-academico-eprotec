<?php
	include_once('topo.htm'); 
?>
        <p align="center" class="style2"><br>
        </p>
        <table width="507" border="0" align="center" height="46">
          <tr>
            <td width="497" height="20" class="login" align="center"><div align="center" class="caixa">
                <form name="form1" method="post" action="lista_cursos.php">
                  <table width="340" border="0">
                    <tr>
                      <td height="30" colspan="2" class="title"><div align="center">Consultas de Notas, Faltas e Saldo da Conta Acad&ecirc;mica</div></td>
                    </tr>
                    <tr>
                      <td colspan="2" class="title">&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="109" class="login" >Registro Escolar:</td>
                      <td width="226" class="login" ><input type="text" name="user"></td>
                    </tr>
                    <tr>
                      <td class="login" >Data Nascimento:</td>
                      <td class="login" ><input type="text" name="nasc" maxsize="10">
                        <span class="style1">(DD/MM/AAAA)</span></td>
                    </tr>
                    <tr>
                      <td class="login" >Senha:</td>
                      <td><input type="password" name="senha"></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td><input type="submit" name="submit" target="_parent" value="Entrar"></td>
                    </tr>
                  </table>
                  <input type="hidden" name="btnOK" value="true">
                </form>
              </div></td>
          </tr>
          <tr>
            <td height="20" class="login"><p>&nbsp;</p>
              <p><strong>Como Consultar:</strong> </p>
              <ul>
                <li> No Registro Escolar utilizar o n&uacute;mero da sua carteira de estudante; <br>
                </li>
                <li> Na data de nascimento informe a data no formato DD/MM/AAAA.<br>
                </li>
                <li> Se ocorrer algum problema de usu&aacute;rio ou senha inv&aacute;lido, verfique os dados. Caso persista o erro consulte no Setor de Registros Escolares se a sua data de nascimento est&aacute; correta no sistema. <br>
                </li>
                <li> Na senha utilize o seu Registro Escolar + zeros a esquerda at&eacute; completar 5 algarismos. <b>Exemplo:</b> para registro n&ordm; 135 a senha ser&aacute; 00135. </li>
                <li><b>Qualquer diverg&ecirc;ncia em notas e faltas informe-se com o seu professor.</b></li>
              </ul></td>
          </tr>
        </table>
        <br>
<?php

	include_once('rodape.htm');    
?>
