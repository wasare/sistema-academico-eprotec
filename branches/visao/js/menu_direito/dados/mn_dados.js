fixMozillaZIndex=true; //Fixes Z-Index problem  with Mozilla browsers but causes odd scrolling problem, toggle to see if it helps
_menuCloseDelay=500;
_menuOpenDelay=150;
_subOffsetTop=2;
_subOffsetLeft=-2;




with(contextStyle=new mm_style()){
bordercolor="#999999";
borderstyle="solid";
borderwidth=1;
fontfamily="arial, verdana, tahoma";
fontsize="75%";
fontstyle="normal";
headerbgcolor="#4F8EB6";
headerborder=1;
headercolor="#ffffff";
offbgcolor="#ffffff";
offcolor="#000000";
onbgcolor="#ECF4F9";
onborder="1px solid #316AC5";
oncolor="#000000";
outfilter="randomdissolve(duration=0.4)";
overfilter="Fade(duration=0.2);Shadow(color=#777777', Direction=135, Strength=3)";
padding=3;
pagebgcolor="#eeeeee";
pageborder="1px solid #ffffff";
//pageimage="http://www.milonic.com/menuimages/db_red.gif";
separatorcolor="#999999";
//subimage="http://www.milonic.com/menuimages/black_13x13_greyboxed.gif";
}

with(milonic=new menuname("contextMenu")){
margin=3;
style=contextStyle;
top="offset=2";
aI("image=imagens/home.gif;text=Principal;url=frmAdmin.php;");
aI("image=imagens/back.gif;text=Voltar;url=javascript:history.go(-1);");
aI("image=imagens/print.gif;separatorsize=1;text=Imprimir;url=javascript:window.print();");
aI("image=imagens/ico_usuarios.jpg;text=Usu&aacute;rios;url=frmUsuario.php;");
aI("image=imagens/ico_comprovacao.jpg;text=Configura&ccedil;&otilde;es;o;url=frmConfiguracoes.php;");
aI("image=imagens/ico_tipos.jpg;text=Grupos;url=frmGrupo.php;");
aI("image=imagens/ico_relatorios.jpg;text=Relat&oacute;rios;url=frmRelatorios.php;");
aI("image=imagens/ico_administrativo.jpg;text=Operadores;url=frmOperador.php;");
aI("image=imagens/ico_atividades.jpg;separatorsize=1;text=Refei&ccedil;&otilde;es;url=frmRefeicao.php;");
aI("image=imagens/ico_logout.jpg;separatorsize=1;text=Sair;url=frmInicial.php;");
}



drawMenus();

