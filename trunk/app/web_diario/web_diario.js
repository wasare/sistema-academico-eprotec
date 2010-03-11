/* 
 * fun��es javascript usadas pelo web di�rio
 */


function abrir(winName, urlLoc, w, h) {
   var l, t, jw, jh, myWin;

   jw = screen.width;
   jh = screen.height;

   l = ((screen.availWidth-jw)/2);
   t = ((screen.availHeight-jh)/2);

   features  = "toolbar=no";      // yes|no
   features += ",location=no";    // yes|no
   features += ",directories=no"; // yes|no
   features += ",status=no";  // yes|no
   features += ",menubar=no";     // yes|no
   features += ",scrollbars=yes";   // auto|yes|no
   features += ",resizable=no";   // yes|no
 //  features += ",dependent";  // close the parent, close the popup, omit if you want otherwise
   features += ",height=" + (h?h:jh);
   features += ",width=" + (w?w:jw);
   features += ",left=" + l;
   features += ",top=" + t;

   winName = winName.replace(/[^a-z]/gi,"_");

	myWin = window.open(urlLoc,winName,features);
	//myWin.focus();
}

function concluido(diario_id) {
    if (! diario_id == "") {
        if (! confirm('Voc� deseja marcar / desmarcar como conclu�do o di�rio ' + diario_id + '?' + '\n\n Como conclu�do o di�rio poder� ser "Finalizado" pela coordena��o ficando\n bloqueado para altera��es!')) {
            return false;
        }
        else {
            return true;
        }
    }
    return false;
}


function finalizado(diario_id) {
    if (! diario_id == "") {
        if (! confirm('Voc� deseja realmente finalizar o di�rio ' + diario_id + '?' + '\n Depois de finalizado o professor n�o poder� fazer altera��es!\n')) {
            return false;
        }
        else {
            return true;
        }
    }
    return false;
}

function finaliza_todos(diario_id) {
	if (! diario_id == "") {
    	if (! confirm('Voc� deseja realmente finalizar todos os di�rios no per�odo/curso corrente?\n Depois de finalizados o professor n�o poder� fazer altera��es e \n somente a secretaria poder� abri-los novamente!')) {
            return false;
        }
        else {
            return true;
        }
    }
    return false;
}

function finalizado_secretaria(diario_id, wname) {
    if (typeof wname == "undefined") {
      wname = "";
    }
    if (! diario_id == "") {
        if (! confirm('Voc� deseja realmente finalizar o di�rio ' + diario_id + '?' + '\n Depois de finalizado o professor n�o poder� fazer altera��es!\n')) {
            return false;
        }
        else {
            abrir(wname,'../coordenacao/marca_finalizado.php?diario_id=' + diario_id)
            return true;
        }
    }
    return false;
}

function reaberto_secretaria(diario_id, wname) {
    if (typeof wname == "undefined") {
      wname = "";
    }
    if (! diario_id == "") {
        if (! confirm('Voc� deseja realmente reabrir o di�rio ' + diario_id + '?' + '\n Depois de aberto o professor poder� fazer altera��es!\n')) {
            return false;
        }
        else {

            abrir(wname,'marca_aberto.php?diario_id=' + diario_id);
            return true;
        }
    }
    return false;
}

function finaliza_todos_secretaria(diario_id, wname) {
    if (typeof wname == "undefined") {
      wname = "";
    }
	if (! diario_id == "") {
    	if (! confirm('Voc� deseja realmente finalizar todos os di�rios no per�odo/curso corrente?\n Depois de finalizados o professor n�o poder� fazer altera��es e \n somente a secretaria poder� abri-los novamente!')) {
            return false;
        }
        else {
            abrir(wname,'../coordenacao/finaliza_todos.php?diario_id=' + diario_id);
            return true;
        }
    }
    return false;
}



function enviar_diario(action,ofer,encerrado,base_url,wname) {

  if (typeof base_url == "undefined") {
    base_url = "";
  }
  if (typeof wname == "undefined") {
    wname = "";
  }
  
  if (encerrado == 1 && (action == 'notas' || action == 'chamada' || action == 'altera_chamada' || action == 'exclui_chamada' || action == 'marca_diario' )) {
    alert("ERRO! Este di�rio est� finalizado e n�o pode ser alterado!");
    return false;
  }

  if (action != 0) {
    switch (action) {
      case 'marca_diario':
        if (concluido(ofer)) {
          abrir( wname + ' web di�rio', base_url + 'app/web_diario/requisita.php?do=' + action + '&id=' + ofer);
        }
        break;
      case 'marca_finalizado':
        if (finalizado(ofer)) {
           abrir( wname + ' web di�rio', base_url + 'app/web_diario/requisita.php?do=' + action + '&id=' + ofer);
        }
        break;
      case 'marca_aberto':
        if (reaberto(ofer)) {
           abrir( wname + ' web di�rio', base_url + 'app/web_diario/requisita.php?do=' + action + '&id=' + ofer);
        }
        break;
      case 'finaliza_todos':
        if (finaliza_todos(ofer)) {
           abrir( wname + ' web di�rio', base_url + 'app/web_diario/requisita.php?do=' + action + '&id=' + ofer);
        }
        break;
      case 'pesquisa_diario_coordenacao':
          diario_id = $F('diario_id');
          abrir( wname + ' web di�rio', base_url + 'app/web_diario/requisita.php?do=' + action + '&id=' + diario_id);
        break;
      case 'pesquisa_aluno':
          campo_aluno = $F('campo_aluno');
           abrir( wname + ' web di�rio', base_url + 'app/web_diario/requisita.php?do=' + action + '&id=&aluno=' + campo_aluno);
        break;
      case 'diarios_secretaria':
          diario_id = $F('diario_id');
          periodo_id = $F('periodo_id');
          curso_id = $F('curso_id');
          abrir( wname + ' web di�rio', base_url + 'app/web_diario/requisita.php?do=diarios_secretaria&id=' + diario_id + '&periodo=' + periodo_id + '&curso=' + curso_id);
        break;
      default:
         abrir( wname + ' web di�rio', base_url + 'app/web_diario/requisita.php?do=' + action + '&id=' + ofer);
    }
  }
  return false;
}

function consulta_diarios_secretaria(base_url,wname) {
    diario_id = $F('diario_id');
    periodo_id = $F('periodo_id');
    curso_id = $F('curso_id');
    abrir( wname + ' web di�rio', base_url + 'app/web_diario/requisita.php?do=diarios_secretaria&id=' + diario_id + '&periodo=' + periodo_id + '&curso=' + curso_id);
}


set_periodo = function(data,pane) {
	$('pane_overlay').show();
    var parametro = data;
    var objAjax = new Ajax.Request('seleciona_periodo.php', {method: 'post', evalJS: true, parameters: parametro, onSuccess: reload_pane});
}


String.prototype.trim = function() { return this.replace(/^\s+|\s+$/, ''); };

reload_pane = function(resposta) {
    var pane = unescape(resposta.responseText);

    if (pane.trim() == 'pane_diarios')
        thePane.load_page('pane_diarios');
   else
		if (pane.trim() == 'pane_coordenacao')
			thePane.load_page('pane_coordenacao');
}

reload_parent_pane = function(pane) {
    $('pane_overlay').show();

    if (pane.trim() == 'pane_diarios') {
        thePane.load_page('pane_diarios');
        
        if ($('pane_coordenacao') != null) {
			$('pane_coordenacao').removeClassName('active');
		}

        $('pane_diarios').addClassName('active');
    }
    else {
		if (pane.trim() == 'pane_coordenacao') {
			thePane.load_page('pane_coordenacao');
			$('pane_diarios').removeClassName('active');
			$('pane_coordenacao').addClassName('active');
		}
    }
}


