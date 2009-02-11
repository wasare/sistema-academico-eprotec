// JavaScript Document


//Abre o relatorio em um nova janela - obsoleto
function relatorios() {
	window.open("relatorios/menu.php",'relatorios_sagu','resizable=yes, toolbar=no,width=700,height=541,scrollbars=yes,top=0,left=0');
}


//Abre janela de avisos
function avisos() {
	window.open("app/avisos/cadastrar.php",'Avisos','resizable=yes, toolbar=no,width=550,height=350,scrollbars=yes,top=0,left=0');
}

//SOLUCAO DO PROBLEMA DE 2 BARRAS DE ROLAGEM - IFRAME
//Redimenciona o iframe de acordo com o conteudo
function iframeAutoHeight(quem){

    //by Micox - elmicox.blogspot.com - elmicox.com - webly.com.br  
	//ie sucks
	if(navigator.appName.indexOf("Internet Explorer")>-1)
	{ 
	
        var func_temp = function(){
            var val_temp = quem.contentWindow.document.body.scrollHeight + 30
            quem.style.height = val_temp + "px";
        }
        setTimeout(function() { func_temp() },100) //ie sucks
		
    }else
	{
        var val = quem.contentWindow.document.body.parentNode.offsetHeight + 30
        quem.style.height= val + "px";
    }
}