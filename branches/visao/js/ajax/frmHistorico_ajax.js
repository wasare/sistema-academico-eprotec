//FUNÇÃO PARA SUBMETER FORMULÁRIO--------------------------------------------------
function submitForm(nome, txtMetodo)
{
	$('txtMetodo').value = txtMetodo;
	$('txtCodigo').value = txtCodigo;
	$(nome).submit();
}