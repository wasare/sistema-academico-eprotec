/* 
 * funções javascript usadas pelo web diário
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
        if (! confirm('Você deseja marcar / desmarcar como concluído o diário ' + diario_id + '?' + '\n\n Como concluído o diário poderá ser "Finalizado" pela coordenação ficando\n bloqueado para alterações!')) {
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
        if (! confirm('Você deseja realmente finalizar o diário ' + diario_id + '?' + '\n Depois de finalizado o professor não poderá fazer alterações!\n')) {
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
    	if (! confirm('Você deseja realmente finalizar todos os diários no período/curso corrente?\n Depois de finalizados o professor não poderá fazer alterações e \n somente a secretaria poderá abri-los novamente!')) {
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
    alert("ERRO! Este diário está finalizado e não pode ser alterado!");
    return false;
  }

  if (action != 0) {
    switch (action) {
      case 'marca_diario':
        if (concluido(ofer)) {
          abrir("<?=$IEnome?>" + '- web diário', 'requisita.php?do=' + action + '&id=' + ofer);
        }
        break;
      case 'marca_finalizado':
        if (finalizado(ofer)) {
          abrir("<?=$IEnome?>" + '- web diário', 'requisita.php?do=' + action + '&id=' + ofer);
        }
        break;
      case 'finaliza_todos':
        if (finaliza_todos(ofer)) {
          abrir("<?=$IEnome?>" + '- web diário', 'requisita.php?do=' + action + '&id=' + ofer);
        }
        break;
      default:
        abrir("<?=$IEnome?>" + '- web diário', 'requisita.php?do=' + action + '&id=' + ofer);
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


