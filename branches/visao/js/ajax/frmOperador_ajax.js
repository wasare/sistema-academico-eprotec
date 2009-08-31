//FUNÇÃO PARA SUBMETER FORMULÁRIO--------------------------------------------------
function submitForm(nome, txtMetodo,txtCodigo, txtNome)
{
	$('txtMetodo').value = txtMetodo;
	$('txtCodigo').value = txtCodigo;
	$(nome).submit();
}

