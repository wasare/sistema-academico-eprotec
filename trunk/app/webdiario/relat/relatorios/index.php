<?php
/*   This program is free software; you can redistribute it and/or modify 
          it under the terms of the GNU General Public License version 2 as 
          published by the Free Software Foundation. 
          This program is distributed in the hope that it will be useful, 
          but WITHOUT ANY WARRANTY; without even the implied warranty of 
          MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
          GNU General Public License for more details.
          
          You should have received a copy of the GNU General Public License 
          along with this program; if not, write to the Free Software 
          Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

include ('../webdiario.conf.php');
/////////////////////$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname");
?>
<html>
<head>
<title>imprime  boletim</title>
<link rel="stylesheet" href="../css/forms.css" type="text/css">
</head>
<body bgcolor='#FFFFFF'>
<table width='760'>
  <tr>
     <td with='150' align='left' valign='top'><img src='../img/logocefet.png'></td>
     <td with='640' align='center' valign='top'><h1><b><i>FACULDADE CENECISTA DE CAPIVARI</i></b></h1><font size='2'>Reconhecida pelo Decreto Federal N 82779, de 30/11/1978<br>Mantenedora: CAMPANHA NACIONAL DE ESCOLAS DA COMUNIDADE<BR><BR>RUA BARAO DO RIO BRANCO, 374 - CEP 13.360-000 - CAPIVARI/SP<br>E-mail: cnec@cneccapivari.br - Portal: www.cneccapivari.br<br>Telefone: (19) 3492-8888 - Fax: (19) 3492-8801</td>
  </tr>
</table>
<center><h3><b>Ficha Individual</b><br><i>Ano de <? print Date(Y); ?></i></h3></center>
<br>
Aluno: imprime nome da pessoa<br><br>
<table width='760'>
<tr>
<td width='350'><b>CURSO DE ???</b></td>
<td width='80'>NOTURNO</td>
<td width='100'><b>Serie:</b> ??</td>
<td width='100'><b>Ano:</b> ??</td>
<td width='100'><b>N</b> ??</td>
</tr>
</table>
<? 
# espaco para a tabela de notas e faltas...

?>
<br><br>
<center><font size='3'><? print 'Capivari, ' . Date(d . ' \de ' . M . ' \de ' . Y); ?></font></center><br><br><br><br>
<table width='760'>
<tr>
<td width='280' align='center'>MARIA JOSE FRASSETO FORNAZIERO<br>Secretaria Interina - RG 7.466.288</td>
<td width='280' align='center'>LUIS DONISETE CAMPACI<br>Diretor - RG 6.279.669</td>
</tr></table>

</body>
</html>
<? pg_close($dbconnect); ?>
