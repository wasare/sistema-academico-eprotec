//FUNÇÃO PARA SUBMETER FORMULÁRIO--------------------------------------------------
function submitForm(nome, txtMetodo, txtCodigo)
{
	$('txtMetodo').value = txtMetodo;
	$('txtCodigo').value = txtCodigo;
	$(nome).submit();
}


//CARREGAMENTO DE FORMULARIO
function CarregaDadosRefeicao()
{
   var parametro = null;
   var objAjax = null;
	
   var carregando = null;
   carregando = '&nbsp;&nbsp;&nbsp; <center><img src="imagens/carregando.gif" title="" alt="" border="0"></img></center><BR><center><b>Carregando..</b></center>';

  	$('spanQuant').innerHTML = carregando;
	$('spanCusto').innerHTML = carregando;
	
	objAjax = new Ajax.Request('frmVenda_post.php?metodo=carregarefquant&codigo=' + $F('dpdRefeicao'), {method: 'post', parameters: parametro, onComplete: PreencheSpanQuant});
	objAjax = new Ajax.Request('frmVenda_post.php?metodo=carregarefcusto&codigo=' + $F('dpdRefeicao'), {method: 'post', parameters: parametro, onComplete: PreencheSpanCusto});

}

//PREENCHEDOR DO SPAN DROP
function PreencheSpanQuant(resposta)
{
   var s = unescape(resposta.responseText);
   $('spanQuant').innerHTML = s;
}

//PREENCHEDOR DO SPAN DROP
function PreencheSpanCusto(resposta)
{
   var s = unescape(resposta.responseText);
   $('spanCusto').innerHTML = s;
}

//CARREGAMENTO DE FORMULARIO
function CarregaInfoUsuario()
{
   var parametro = null;
   var objAjax = null;
	
   var carregando = null;
   carregando = '&nbsp;&nbsp;&nbsp; <center><img src="imagens/carregando.gif" title="" alt="" border="0"></img></center><BR><center><b>Carregando..</b></center>';

  	$('spanUsuario').innerHTML = carregando;
	
	objAjax = new Ajax.Request('frmVenda_post.php?metodo=carregausuario&codigo=' + $F('usuario'), {method: 'post', parameters: parametro, onComplete: PreencheSpanUsuario});

}

//PREENCHEDOR DO SPAN DROP
function PreencheSpanUsuario(resposta)
{
   var s = unescape(resposta.responseText);
   $('spanUsuario').innerHTML = s;
}

function capturaPeso()    
{
	try {
			var trg = document.getElementById("peso");
			var ev = document.createEvent("Events");
			ev.initEvent("CapturaPesoEvent", true, false);
			trg.dispatchEvent(ev);
	} catch (ex) {
		alert("Exception: "+ex);
	}
}

function captura()
{
	/*if (document.getElementById("usuario")) {
		document.getElementById("usuario").focus();
	}*/
	capturaPeso();
	t = setTimeout("captura()",1000);
}
