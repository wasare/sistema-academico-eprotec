//FUN��O PARA SUBMETER FORMUL�RIO--------------------------------------------------
function submitForm(nome, txtMetodo,txtCodigo, txtNome)
{
	$('txtMetodo').value = txtMetodo;
	$('txtCodigo').value = txtCodigo;
	$(nome).submit();
}

