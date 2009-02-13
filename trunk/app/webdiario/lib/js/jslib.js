function openWin(winName, urlLoc, w, h)
{

   var l, t, jw, jh;

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

   return window.open(urlLoc,winName,features);
}

function open_win(url)
{
   //var url;
   //busca = opcao;
   //url = 'processa.php?id=pesquisa' +
   //         '&b=' + opcao +
   //         '&tipo=pesquisa';
   window.open(url,"popWindow","status=no,scrollbars=yes,width=500,height=500,top=0,left=0")
}
