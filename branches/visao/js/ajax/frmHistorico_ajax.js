//FUN��O PARA SUBMETER FORMUL�RIO--------------------------------------------------
function submitForm(nome, txtMetodo)
{
	$('txtMetodo').value = txtMetodo;
	$('txtCodigo').value = txtCodigo;
	$(nome).submit();
}