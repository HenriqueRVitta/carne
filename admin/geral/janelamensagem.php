<script language="JavaScript" type="text/JavaScript">
<!--
function MM_showHideLayers() {
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
} 
function MM_findObj(n, d) {
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) { 
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n]; 
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x; 
}
//-->
</script>
<noscript>
width = largura da janela
height = altura da janela
left = distância da esquerda
top = distância do topo
</noscript>
<style type="text/css">
#botao{cursor:hand;color:#696969;border:0px;font-face:arial,verdana;font-size:7pt;font-weight:bold;}
</style>
<div id="fechada" style="visibility:visible;filter:Alpha(Opacity=90,FinishOpacity=80,Style=2,StartX=100,StartY=100,FinishX=100,FinishY=1);position:absolute;left:15px;top:20px;"> 
<table width="600" height="50" bgcolor="#d1ead7" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="center"><h2><br><?php echo $_GET['mensa'];?> em fase de desenvolvimento.</h2></td>
<td align="right" valign="top"><input type="button" id="botao" value=" X " onClick="MM_showHideLayers('fechada','','hide')" title="Fechar!!!"></td>
</tr>
</table>
</div>