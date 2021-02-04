<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php 
	session_start();
	
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	include ("../../includes/classes/paging.class.php");

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" type="text/css" href="http://c632769.r69.cf2.rackcdn.com/hcc_015.css" />

	<title>Códigos de Cores HTML</title>
	<meta name="description" content="O website Códigos de Cores HTML fornece ferramentas de cor grátis para encontrar cores HTML para o seu website. As grandes ferramentas Gráfico de cores HTML e Escolha de Cores HTML vão tornar isto simples como dar uma volta no parque." />
	<META NAME="keywords" content="html, color, codes, chart, picker, hex">
	<link rel="shortcut icon" href="http://c632769.r69.cf2.rackcdn.com/favicon.ico" type="image/x-icon" />
	<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-814714-1']);
  _gaq.push(['_trackPageview']);
  
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
var rndnum = Math.random();  
</script>
</head>
<body>

<!-- BelowColorChart -->
<div ID="TD" class="wrapper"></div>
<!-- color picker -->	
<a name="HTML_Color_Picker"></a>
<h2>Escolha de Cores HTML</h2>
<p>Movimente o slider vertical para escolher a cor e de seguida clique no quadrado da cor à esquerda para ter o código de cor HTML da cor desejada.</p>
<p>Pode começar com a sua própria cor escrevendo o seu código no logo abaixo.</p>

<div id="insertcolor">
Insira o seu código de cor:
<input type="text" maxlength="7" value="FFFFFF" id="startcolor" name="startcolor">
<button id="newcolor">Mostrar</button>
</div>
<div id="container"></div>
<!-- javaskripti -->
<script type="text/javascript" src="http://c632769.r69.cf2.rackcdn.com/cpcc_002.js"></script>
<script type="text/javascript" language="javascript">
(function() {
 var Event = YAHOO.util.Event, picker, hexcolor;

 Event.onDOMReady(function() {
 picker = new YAHOO.widget.ColorPicker("container", {
 showhsvcontrols: true,
 showhexcontrols: true,
 showwebsafe: false });
			picker.skipAnim=true;	
			var onRgbChange = function(o) {				setTimeout ("document.getElementById('yui-picker-hex').select()", 50);			}
			picker.on("rgbChange", onRgbChange);
			Event.on("newcolor", "click", function(e) {
				hexcolor = cc(document.getElementById('startcolor').value);
				picker.setValue([HexToR(hexcolor), HexToG(hexcolor), HexToB(hexcolor)], false); 
			});
 });
})();
</script>
<div id="insertcolor">
Clique para por a cor na lista em baixo:
<button id="newcolor" onclick="CPklik()">Click</button>
</div>
<p>Em baixo está uma lista das cores escolhidas.</p>
<div ID="CP" class="wrapper"></div>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- BelowColorPicker -->
</body>
</html>