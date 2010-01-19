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
   features += ",dependent";  // close the parent, close the popup, omit if you want otherwise
   features += ",height=" + (h?h:jh);
   features += ",width=" + (w?w:jw);
   features += ",left=" + l;
   features += ",top=" + t;

   winName = winName.replace(/[^a-z]/gi,"_");

	myWin = window.open(urlLoc,winName,features);
	myWin.focus();
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

function enviar_diario(action,ofer,encerrado) {

  if (encerrado == 1 && (action == 'notas' || action == 'chamada' || action == 'altera_chamada' || action == 'exclui_chamada' || action == 'marca_diario' )) {
    alert("ERRO! Este di�rio est� finalizado e n�o pode ser alterado!");
    return false;
  }

  if (action != 0) {
    switch (action) {
      case 'marca_diario':
        if (concluido(ofer)) {
          abrir("<?=$IEnome?>" + '- web di�rio', 'requisita.php?do=' + action + '&id=' + ofer);
        }
        break;
      case 'marca_finalizado':
        if (finalizado(ofer)) {
          abrir("<?=$IEnome?>" + '- web di�rio', 'requisita.php?do=' + action + '&id=' + ofer);
        }
        break;
      case 'finaliza_todos':
        if (finaliza_todos(ofer)) {
          abrir("<?=$IEnome?>" + '- web di�rio', 'requisita.php?do=' + action + '&id=' + ofer);
        }
        break;
      default:
        abrir("<?=$IEnome?>" + '- web di�rio', 'requisita.php?do=' + action + '&id=' + ofer);
    }
  }
  return false;
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

    if (pane.trim() == 'pane_coordenacao')
        thePane.load_page('pane_coordenacao');
}

reload_parent_pane = function(pane) {
    $('pane_overlay').show();

    if (pane.trim() == 'pane_diarios') {
        thePane.load_page('pane_diarios');
        $('pane_coordenacao').removeClassName('active');
        $('pane_diarios').addClassName('active');
    }

    if (pane.trim() == 'pane_coordenacao') {
        thePane.load_page('pane_coordenacao');
        $('pane_diarios').removeClassName('active');
        $('pane_coordenacao').addClassName('active');
    }
}


