<script type="text/javascript">
//Funções javascript

	var GLArray = new Array();

	function popup(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'Gráfico','dependent=yes,width=800,height=600,scrollbars=yes,statusbar=no,resizable=no');
		x.moveTo(10,10);

		return false;
	}

	function popupS(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'Gráfico','dependent=yes,width=800,height=600,scrollbars=yes,statusbar=no,resizable=no');
		x.moveTo(10,10);

		return false;
	}

	function popupWH(pagina,larg,altur)	{ //Exibe uma janela popUP
		x = window.open(pagina,'Gráfico','dependent=yes,width='+(larg+20)+',height='+(altur+20)+',scrollbars=no,statusbar=no,resizable=no');
		x.moveTo(10,10);

		return false;
	}


	function popup_alerta(pagina)	{ //Exibe uma janela popUP

		x = window.open(pagina,'_blank','dependent=yes,width=600,height=400,scrollbars=yes,statusbar=no,resizable=yes');

		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false;
	}

	function popup_consulta(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'_blank','dependent=yes,width=600,height=550,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false;
	}
	
	function popup_doc(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'_blank','dependent=yes,width=830,height=600,scrollbars=yes,statusbar=no,resizable=no');

		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false;
	}
	
	function popup_wide(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'_blank','dependent=yes,width=600,height=200,scrollbars=yes,statusbar=no,resizable=yes');

		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false;
	}

	function mini_popup(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'_blank','dependent=yes,width=400,height=250,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false;
	}

	function popup_alerta_mini(pagina)	{ //Exibe uma janela popUP
		x=window.open(pagina,'_blank','dependent=yes,width=400,height=250,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(100,100);
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false;
	}


	function popup_alerta_wide(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'_blank','dependent=yes,width=800,height=400,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false;
	}


	function mensagem(msg){
		alert(msg);
		return false;
	}


	function redirect(url){
		window.location.href=url;
	}

	function redirectLoad(url, id){
		var obj = document.getElementById(id);
		window.location.href=url+obj.value;
	}

	function submitForm (obj) {
		obj.form.submit();
	}

	function reloadUrl(url, param){
		var obj = document.getElementById(id);
		window.location.href=url+param;
	}

	//criar acesso ao submit de excluir
	function confirma(msg,url){
		if (confirm(msg)){
			redirect(url);
		}
	}


	function confirmaAcao (msg, url, param){ //variavel php
		if (confirm(msg)){
			url += '?'+param;
			redirect(url);
		}
		return false;
	}


	function cancelLink () {
		return false;
	}

	function disableLink (link) {
		if (link.onclick)
			link.oldOnClick = link.onclick;
		link.onclick = cancelLink;
		if (link.style)
			link.style.cursor = 'default';
	}

	function enableLink (link) {
		link.onclick = link.oldOnClick ? link.oldOnClick : null;
		if (link.style)
			link.style.cursor =
			document.all ? 'hand' : 'pointer';
	}
	function toggleLink (link) {
	  if (link.disabled)
		enableLink (link);
	  else
		disableLink (link);
	  link.disabled = !link.disabled;
	}

	function desabilitaLinks(permissao){
		if (permissao!=1) {
			for (i=0; i<(document.links.length); i++) {
				toggleLink (document.links[i]);
			}
		}
	}

	function par(n) {
		var na = n;
		var nb = (na / 2);
		nb = Math.floor(nb);
		nb = nb * 2;
		if ( na == nb ) {
			return(1);
		} else {
			return(0);
		}
	}


	function corNatural(id) {//F8F8F1
		var obj = document.getElementById(id);

		var args = corNatural.arguments.length;
		//var id = destaca.arguments[0];
		if (args==1){
			//var color = "#CCCCFF";
			var color = "";
		} else
		if (args == 2)
			var color = corNatural.arguments[1];
		else
		if (args == 3){
			var color = corNatural.arguments[1];
			var color2 = corNatural.arguments[2];
		}


		//obj.style.background = obj.getAttributeNode('cN').value; /* Para ser usado lendo propriedade cN='cor' do objeto */
		if (navigator.userAgent.indexOf('MSIE') !=-1){ //M$ IE
			var classe = obj.getAttributeNode('class').value;
			obj.style.background = color;
			//var classe = obj.className;
		} else {
			//var classe ='';
			var classe = obj.getAttributeNode('class').value;
		}

		if ( classe != '') {
			//if ( classe == 'lin_par'  ) {  obj.style.background = 'url("../../includes/css/header_bar.gif")';  } else //'#EAE6D0'//

			//if ( classe == 'lin_par'  ) {  obj.style.background = '#E3E1E1';  } else
			//if ( classe == 'lin_impar' ) { obj.style.background = '#F6F6F6' ;}

			if ( classe == 'lin_par'  ) {  obj.style.background = color;  } else
			if ( classe == 'lin_impar' ) { obj.style.background = color2 ;}


		}
		//else { obj.style.background = '' }
		else { obj.style.background = color; }
	}

		function listItems()
		{
			var items = listItems.arguments.length;
			document.write("<UL>\n");
			for (i = 0;i < items;i++)
			{
				document.write("<LI>" + listItems.arguments[i] + "\n");
			}
			document.write("</UL>\n");
		}

		function setBGColor(id){
			var obj = document.getElementById(id);

			if (obj.value!="IMG_DEFAULT")
				obj.style.background="";
			obj.style.backgroundColor = obj.value;

			return false;
		}

		function destaca(){

			var args = destaca.arguments.length;
			var id = destaca.arguments[0];

			if (args==1){
				//var color = "#CCCCFF";
				var color = "";
			} else
				var color = destaca.arguments[1];

			if ( verificaArray('', id) == false ) {
				var obj = document.getElementById(id);
				//obj.style.background = '#CCCCFF';// #CCFFCC #C7C8C6 #A3A352 '#D5D5D5'  #CCFFCC   #FDFED8
				obj.style.background = color;
			}
		}

		function libera(id){

			var args = libera.arguments.length;
			//var id = destaca.arguments[0];
			if (args==1){
				var color = "";
			} else
			if (args == 2)
			{
				var color = libera.arguments[1];
			} else
			if (args == 3) {
				var color = libera.arguments[1];
				var color2 = libera.arguments[2];
			} else
				var color2 = '';


			if ( verificaArray('', id) == false ) {
				var obj = document.getElementById(id);
				//obj.style.background = '';
				corNatural(id,color,color2); /* retorna à cor natural */
			}
		}


		function marca(){
			var args = marca.arguments.length;
			var id = marca.arguments[0];

			var obj = document.getElementById(id);
			if (args==1){
				//var color = "#FFCC99";
				var color = "";
			} else
				var color = marca.arguments[1];


			if ( verificaArray('', id) == false ) {
				verificaArray('marca', id);

				//obj.style.background = '#FFCC99';
				obj.style.background = color;
			} else {
				verificaArray('desmarca', id);
				//obj.style.background = '';
				destaca(id);
			}

		}

		function verificaArray(acao, id) {
			var i;
			var tamArray = GLArray.length;
			var existe = false;

			for(i=0; i<tamArray; i++) {
				if ( GLArray[i] == id ) {
					existe = true;
					break;
				}
			}

			if ( (acao == 'marca') && (existe==false) ) {
				GLArray[tamArray] = id;
			} else if ( (acao == 'desmarca') && (existe==true) ) {
				var temp = new Array(tamArray-1); //-1
				var pos = 0;
				for(i=0; i<tamArray; i++) {
					if ( GLArray[i] != id ) {
						temp[pos] = GLArray[i];
						pos++;
					}
				}

				GLArray = new Array();
				var pos = temp.length;
				for(i=0; i<pos; i++) {
					GLArray[i] = temp[i];
				}
			}

			return existe;
		}

	function loadDefaultValue(id, valor){
		var obj = document.getElementById(id);
		obj.value = valor;
		return false;
	}



function validaForm(id,tipo,campo,obrigatorio){
	var regINT = /^[1-9]\d*$/; //expressão para validar numeros inteiros não iniciados com zero
	var regINTFULL = /^\d*$/; //expressão para validar numeros inteiros quaisquer
	var regDATA = /^((0?[1-9]|[12]\d)\/(0?[1-9]|1[0-2])|30\/(0?[13-9]|1[0-2])|31\/(0?[13578]|1[02]))\/(19|20)?\d{2}$/;
	var regDATA_ = /^((0?[1-9]|[12]\d)\-(0?[1-9]|1[0-2])|30\-(0?[13-9]|1[0-2])|31\-(0?[13578]|1[02]))\-(19|20)?\d{2}$/;
	var regDATAHORA = /^(((0?[1-9]|[12]\d)\/(0?[1-9]|1[0-2])|30\/(0?[13-9]|1[0-2])|31\/(0?[13578]|1[02]))\/(19|20)?\d{2})[ ]([0-1]\d|2[0-3])+:[0-5]\d:[0-5]\d$/;
	var regEMAIL = /^[\w!#$%&'*+\/=?^`{|}~-]+(\.[\w!#$%&'*+\/=?^`{|}~-]+)*@(([\w-]+\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/;

	var regMULTIEMAIL = /^([\w!#$%&'*+\/=?^`{|}~-]+(\.[\w!#$%&'*+\/=?^`{|}~-]+)*@(([\w-]+\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\]))(\,\s?([\w!#$%&'*+\/=?^`{|}~-]+(\.[\w!#$%&'*+\/=?^`{|}~-]+)*@(([\w-]+\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\]))+)*$/;

	var regMOEDA = /^\d{1,3}(\.\d{3})*\,\d{2}$/;
	var regMOEDASIMP = /^\d*\,\d{2}$/;
	var regETIQUETA = /^[1-9]\d*(\,\d+)*$/; //expressão para validar consultas separadas por vírgula;
	var regALFA = /^[A-Z]|[a-z]([A-Z]|[a-z])*$/;
	var regALFANUM = /^([A-Z]|[a-z]|[0-9])([A-Z]|[a-z]|[0-9])*\.?([A-Z]|[a-z]|[0-9])([A-Z]|[a-z]|[0-9])*$/; //Valores alfanumérias aceitando separação com no máximo um ponto.
	var regALFAFULL = /^[\w!#$%&'*+\/=?^`{|}~-]+(\.[\w!#$%&'*+\/=?^`{|}~-]+)*$/;
	//var regFone = /^([\d]*([-]|[\s])?[\d]+)+([,][\s][\d]*([-]|[\s])?[\d]+)*$/;
	var regFone = /^(([+][\d]{2,2})?([-]|[\s])?[\d]*([-]|[\s])?[\d]+)+([,][\s]([+][\d]{2,2})?([-]|[\s])?[\d]*([-]|[\s])?[\d]+)*$/;
	var regCor = /^([#]([A-F]|[a-f]|[\d]){6,6})|([I][M][G][_][D][E][F][A][U][L][T])$/;
	//var d = document.cadastro;

	var obj = document.getElementById(id);
	var valor = obj.getAttributeNode('name').value;

	//alert (obj);

	//verificar se está preenchido


	if ((obj.value == "")&&(obrigatorio==1)){
		alert("O campo [" + campo + "] deve ser preenchido!");
		obj.focus();
		return false;
	}



	if ((tipo == "INTEIRO")&&(obj.value != "")) {
		//validar dados numéricos
		if (!regINT.test(obj.value)){
			alert ("O campo "+ campo +" deve conter apenas numeros inteiros não iniciados por ZERO!");
			obj.focus();
			return false;
		}
	} else

	if ((tipo == "COMBO")&&(obj.value != "")) {
		//validar dados numéricos
		if (!regINT.test(obj.value)){
			alert ("O campo "+ campo +" deve ser selecionado!");
			obj.focus();
			return false;
		}
	} else

	if ((tipo == "INTEIROFULL")&&(obj.value != "")) {
		//validar dados numéricos
		if (!regINTFULL.test(obj.value)){
			alert ("O campo "+ campo +" deve conter apenas numeros inteiros!");
			obj.focus();
			return false;
		}
	} else

	if ((tipo == "DATA")&&(obj.value != "")) {
		//validar data
		if (!regDATA.test(obj.value)){
			alert("Formato de data invalido! dd/mm/aaaa");
			obj.focus();
			return false;
			}
	} else

	if ((tipo == "DATA-")&&(obj.value != "")) {
		//validar data
		if (!regDATA_.test(obj.value)){
			alert("Formato de data invalido! dd-mm-aaaa");
			obj.focus();
			return false;
			}
	} else
	if ((tipo == "DATAHORA")&&(obj.value != "")) {
		//validar data
		if (!regDATAHORA.test(obj.value)){
			alert("Formato de data invalido! dd/mm/aaaa HH:mm:ss");
			obj.focus();
			return false;
			}
	} else


	if ((tipo == "EMAIL")&&(obj.value != "")){
		//validar email(verificao de endereco eletrônico)
		if (!regEMAIL.test(obj.value)){
			alert("Formato de e-mail inválido!");
			obj.focus();
			return false;
		}
	} else

	if ((tipo == "MULTIEMAIL")&&(obj.value != "")){
		//validar email(verificao de endereco eletrônico)
		if (!regMULTIEMAIL.test(obj.value)){
			alert("Formato de e-mail inválido! \"E-MAIL, E-MAIL\"");
			obj.focus();
			return false;
		}
	} else


	if ((tipo == "MOEDA")&&(obj.value != "")){
		//validar valor monetário
		if (!regMOEDA.test(obj.value)){
			alert("Formato de moeda inválido!");
			obj.focus();
			return false;
		}
	} else

	if ((tipo == "MOEDASIMP")&&(obj.value != "")){
		//validar valor monetário
		if (!regMOEDASIMP.test(obj.value)){
			alert("Formato de moeda inválido! XXXXXX,XX");
			obj.focus();
			return false;
		}
	} else

	if ((tipo == "ETIQUETA")&&(obj.value != "")){
		//validar valor monetário
		if (!regETIQUETA.test(obj.value)){
			alert("o Formato deve ser de valores inteiros não iniciados por Zero e separados por vírgula!");
			obj.focus();
			return false;
		}
	}	else

	if ((tipo == "ALFA")&&(obj.value != "")){
		//validar valor monetário
		if (!regALFA.test(obj.value)){
			alert("Esse campo só aceita carateres do alfabeto sem espaços!");
			obj.focus();
			return false;
		}
	}	else

	if ((tipo == "ALFANUM")&&(obj.value != "")){
		//validar valor monetário
		if (!regALFANUM.test(obj.value)){
			alert("Esse campo só aceita valores alfanuméricos sem espaços ou separados por um ponto(no máximo um)!");
			obj.focus();
			return false;
		}
	}

	if ((tipo == "ALFAFULL")&&(obj.value != "")){
		//validar valor monetário
		if (!regALFAFULL.test(obj.value)){
			alert("Esse campo só aceita valores alfanuméricos sem espaços!");
			obj.focus();
			return false;
		}
	}

	if ((tipo == "FONE")&&(obj.value != "")){
		//validar valor monetário
		if (!regFone.test(obj.value)){
			alert("Esse campo só aceita valores formatados para telefones (algarismos, traços e espaços) separados por vírgula.");
			obj.focus();
			return false;
		}
	}
	if ((tipo == "COR")&&(obj.value != "")){
		//validar valor monetário
		if (!regCor.test(obj.value)){
			alert("Esse campo só aceita valores formatados para cores HTML! Ex: #FFCC99");
			obj.focus();
			return false;
		}
	}


	return true;
}

	function exibeEscondeImg(obj) {
		var item = document.getElementById(obj);
		if (item.style.display=='none'){
			item.style.display='';
		} else {
			item.style.display='none';
		}
	}

	function exibeEscondeHnt(obj) {

/*		if (document.all) {
			document.this.x.value=window.event.clientX;
			document.this.y.value=window.event.clientY;
		}
		else if (document.layers) {
			document.this.x.value=e.pageX;
			document.this.y.value=e.pageY;
		}*/


		if (document.all) {
			var x = window.event.clientX;
			var y = window.event.clientY;
		} else if (document.layers) {
			var x = pageX;
			var y = pageY;
		}

		var item = document.getElementById(obj);
		if (item.style.display=='none'){
			item.style.display='';
			item.style.top = y;
		} else {
			item.style.display='none';
		}
	}


	function invertView(id) {
		var element = document.getElementById(id);
		var elementImg = document.getElementById('img'+id);
		var address = '../../includes/icons/';

		if (element.style.display=='none'){
			element.style.display='';
			elementImg.src = address+'close.png';
		} else {
			element.style.display='none';
			elementImg.src = address+'open.png';
		}
	}




	function addEvent( id, type, fn ) {
		var obj = document.getElementById(id);

		if ( obj.attachEvent ) {
			obj['e'+type+fn] = fn;
			obj[type+fn] = function(){obj['e'+type+fn]( window.event );}
			obj.attachEvent( 'on'+type, obj[type+fn] );
		} else
			obj.addEventListener( type, fn, false );
	}

	function removeEvent( id, type, fn ) {
		var obj = document.getElementById(id);
		if ( obj.detachEvent ) {
			obj.detachEvent( 'on'+type, obj[type+fn] );
			obj[type+fn] = null;
		} else
			obj.removeEventListener( type, fn, false );
	}


	function Mouse() {
		var isIE = document.all;
		var ns6  = document.getElementById && !document.all;
		var ieTB = (document.compatMode && document.compatMode!="BackCompat")?document.documentElement:document.body;
		var px = null;
		var py = null;


		this.setEvent = function(e) {
			px = (ns6)?e.pageX:event.clientX+ieTB.scrollLeft;
			py = (ns6)?e.pageY:event.clientY+ieTB.scrollTop;
		}

		this.x = function() { return px; }

		this.y = function() { return py; }
	}

	function mouseMoveManager(e) {
		mouse.setEvent(e);
		//document.title = "Cursor_x: "+mouse.x()+" | Cursor_y: "+mouse.y();
	}

	function fecha()
	{
// 		if (history.back){
// 			return history.back();
// 		} else
// 			window.close();

		if (window.opener){
			return window.close();
		} else
			return history.back();
	}


	function showToolTip(e,text,id1, id2){
		if(document.all)e = event;

		var obj = document.getElementById(id1);
		var obj2 = document.getElementById(id2);
		obj2.innerHTML = text;
		obj.style.display = 'block';
		var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
		if(navigator.userAgent.toLowerCase().indexOf('safari')>=0)st=0;
		var leftPos = e.clientX - 100;
		if(leftPos<0)leftPos = 0;
		obj.style.left = leftPos + 'px';
		obj.style.top = e.clientY - obj.offsetHeight -1 + st + 'px';
	}

	function hideToolTip(id)
	{
		document.getElementById(id).style.display = 'none';

	}

	function replaceAll( str, from, to ) {
		var idx = str.indexOf( from );
		while ( idx > -1 ) {
			str = str.replace( from, to );
			idx = str.indexOf( from );
		}
		return str;
	}

	function trim(str) {
		return str.replace(/^\s+|\s+$/g,"");
	}

	function foco(id){
		obj = document.getElementById(id);
		obj.focus();
		return true;
	}

	function ajaxFunction(div,script,divLoad){
		var ajaxRequest;  // The variable that makes Ajax possible!

		try{
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
		} catch (e){
			// Internet Explorer Browsers
			try{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e){
					// Something went wrong
					alert("Your browser broke!");
					return false;
				}
			}
		}
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				document.getElementById(divLoad).style.display = 'none';
				var ajaxDisplay = document.getElementById(div);
				ajaxDisplay.innerHTML = ajaxRequest.responseText;
			} else {
				document.getElementById(divLoad).style.display = '';
			}
		}

		var args = ajaxFunction.arguments.length;
		var i;
		var j;
		var array = new Array();

		for (i=3; i<args; i++){//Jogando os argumentos (apartir do terceiro pois os tres primeiros sao fixos) para um array
			j = i-3;
			array[j] = ajaxFunction.arguments[i];
		}

		var queryString = MontaQueryString(array);

		ajaxRequest.open("GET", script + queryString, true);
		ajaxRequest.send(null);
	}

	function MontaQueryString (array) {
		var i;
		var size = array.length;
		var queryString = '?';

		for (i=0; i<size; i++){
			var param = array[i].split('=');
			param[1] = document.getElementById(param[1]).value;

			queryString += param[0] + "=" + param[1] + "&";
		}
		return queryString;
	}


	function check_all(valor){
		
		with(document)
		{
			var d;
			d=document.getElementsByTagName("input");
			
			for(i=0;i<d.length;i++)
			{
				if(d[i].type=="checkbox")
				{
					d[i].checked=valor;
				}
			}
		}
	}

	/**   
	 * Função para aplicar máscara em campos de texto
	 * Copyright (c) 2008, Dirceu Bimonti Ivo - http://www.bimonti.net  
	 * All rights reserved.  * @constructor   */  /* Version 0.27 *//**    
	 * Função Principal   
	 * @param w - O elemento que será aplicado (normalmente this).  
	 * @param e - O evento para capturar a tecla e cancelar o backspace.  
	 * @param m - A máscara a ser aplicada.  
	 * @param r - Se a máscara deve ser aplicada da direita para a esquerda. Veja Exemplos. 
	 * @param a -   * @returns null    
	 */
function maskIt(w,e,m,r,a){               
// Cancela se o evento for Backspace        
		if (!e) var e = window.event;      
		if (e.keyCode) code = e.keyCode;        
		else if (e.which) code = e.which;                
		// Variáveis da função        
		var txt  = (!r) ? w.value.replace(/[^\d]+/gi,'') : w.value.replace(/[^\d]+/gi,'').reverse();
		var mask = (!r) ? m : m.reverse();
		var pre  = (a ) ? a.pre : "";
		var pos  = (a ) ? a.pos : "";
		var ret  = "";
		
		if(code == 9 || code == 8 || txt.length == mask.replace(/[^#]+/g,'').length) return false;
		
		// Loop na máscara para aplicar os caracteres       
		for(var x=0,y=0, z=mask.length;x<z && y<txt.length;){                
			if(mask.charAt(x)!='#'){                        
				ret += mask.charAt(x); x++;                
				} else{                       
				ret += txt.charAt(y); y++; x++;
				}       
			}                
		// Retorno da função        
		ret = (!r) ? ret : ret.reverse();                
		w.value = pre+ret+pos;
		
		}

	// Novo método para o objeto 'String'
	String.prototype.reverse = function(){        
		return this.split('').reverse().join('');
		};
// Formata números decimais		
function FormataValor(campo,tammax,teclapres) {
	var tecla = teclapres.keyCode;
	vr = document.form[campo].value;
	vr = vr.replace( "/", "" );
	vr = vr.replace( "/", "" );
	vr = vr.replace( ",", "" );
	vr = vr.replace( ".", "" );
	vr = vr.replace( ".", "" );
	vr = vr.replace( ".", "" );
	vr = vr.replace( ".", "" );
	tam = vr.length;
	if (tam < tammax && tecla != 8){ tam = vr.length; }
	if (tecla == 8 ){ tam = tam - 1; }
	if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){
	if ( tam <= 2 ){
	document.form[campo].value = vr; }
	if ( (tam > 2) && (tam <= 5) ){
	document.form[campo].value = vr.substr( 0, tam - 2 ) + ',' + vr.substr( tam - 2, tam ); }
	if ( (tam >= 6) && (tam <= 8) ){
	document.form[campo].value = vr.substr( 0, tam - 5 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ); }
	if ( (tam >= 9) && (tam <= 11) ){
	document.form[campo].value = vr.substr( 0, tam - 8 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ); }
	if ( (tam >= 12) && (tam <= 14) ){
	document.form[campo].value = vr.substr( 0, tam - 11 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ); }
	if ( (tam >= 15) && (tam <= 17) ){
	document.form[campo].value = vr.substr( 0, tam - 14 ) + '.' + vr.substr( tam - 14, 3 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam );}
	}
	}

function somente_numero(campo){  
	var digits="0123456789";  
	var campo_temp;   
	    for (var i=0;i<campo.value.length;i++){  
	        campo_temp=campo.value.substring(i,i+1);   
	        if (digits.indexOf(campo_temp)==-1){  
	            campo.value = campo.value.substring(0,i);  
	        }  
	    }  
	}  

function formatar_moeda(campo, separador_milhar, separador_decimal, tecla) {
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	var whichCode = (window.Event) ? tecla.which : tecla.keyCode;
	if(whichCode == 13 || whichCode == 8 || whichCode == 0) return true;
	key = String.fromCharCode(whichCode); // Pegando o valor digitado
	if (strCheck.indexOf(key) == -1) return false; // Valor inválido (não inteiro)
	len = campo.value.length;
	for(i = 0; i < len; i++)
	if ((campo.value.charAt(i) != '0') && (campo.value.charAt(i) != separador_decimal)) break;
	aux = '';
	for(; i < len; i++)
	if (strCheck.indexOf(campo.value.charAt(i))!=-1) aux += campo.value.charAt(i);
	aux += key;
	len = aux.length;
	if (len == 0) campo.value = '';
	if (len == 1) campo.value = '0'+ separador_decimal + '0' + aux;
	if (len == 2) campo.value = '0'+ separador_decimal + aux;

	if (len > 2) {
		aux2 = '';

		for (j = 0, i = len - 3; i >= 0; i--) {
			if (j == 3) {
				aux2 += separador_milhar;
				j = 0;
			}
			aux2 += aux.charAt(i);
			j++;
		}

		campo.value = '';
		len2 = aux2.length;
		for (i = len2 - 1; i >= 0; i--)
		campo.value += aux2.charAt(i);
		campo.value += separador_decimal + aux.substr(len - 2, len);
	}

	return false;
}

// Habilita, desabilita botão
function ab_des(botao)
{
        var botoes
        botoes = document.getElementsByTagName("input");

        for (var i = 0; i < botoes.length; i++)
        {
  botoes[i].disabled == true?(botoes[i].disabled = false):0;
        }

        botao.disabled = "disabled";
}

function valida_msg(){
	if(valida_cpf(document.getElementById('idcpf').value))
		return true;
		//alert('CPF Válido');
	else
		alert('CPF Inválido');
}

function valida_cpf(cpf){

	var numeros, digitos, soma, i, resultado, digitos_iguais;
    digitos_iguais = 1;
    if (cpf.length < 11)
          return false;
    for (i = 0; i < cpf.length - 1; i++)
          if (cpf.charAt(i) != cpf.charAt(i + 1))
                {
                digitos_iguais = 0;
                break;
                }
    if (!digitos_iguais)
          {
          numeros = cpf.substring(0,9);
          digitos = cpf.substring(9);
          soma = 0;
          for (i = 10; i > 1; i--)
                soma += numeros.charAt(10 - i) * i;
          resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
          if (resultado != digitos.charAt(0))
                return false;
          numeros = cpf.substring(0,10);
          soma = 0;
          for (i = 11; i > 1; i--)
                soma += numeros.charAt(11 - i) * i;
          resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
          if (resultado != digitos.charAt(1))
                return false;
          return true;
          }
    else
    	return false;
}

function diaSemana(){
    $dia = $_[0];$mes = $_[1];$ano = $_[2];$d_semana=0;
    $d_semana=$ano+$dia+3*($mes-1)-1;
   if($mes<3){$ano--;}
 else{$d_semana-=int(0.4*$mes+2.3);}
 $d_semana+=int(($ano/4)-int((($ano/100+1)*0.75))
);
 $d_semana%=7;
 return $d_semana;       
}

function consultacep(cep,form){

    cep = cep.replace(/\D/g,"");
    url="http://cep.correiocontrol.com.br/"+cep+".js";
    s=document.createElement('script');
    s.setAttribute('charset','utf-8');
    s.src=url;
    document.querySelector('head').appendChild(s);
  }

  function correiocontrolcep(valor){
    if (valor.erro) {
      alert('Cep não encontrado');

      document.getElementById('idendereco').value='';
      document.getElementById('idbairro').value='';
      document.getElementById('idcidade').value='';
      document.getElementById('iduf').value='';
      document.getElementById('idcep').value='';
      return;
    };
    document.getElementById('idendereco').value=valor.logradouro;
    document.getElementById('idbairro').value=valor.bairro;
    document.getElementById('idcidade').value=valor.localidade;
    document.getElementById('iduf').value=valor.uf;
    
    return;
  }

  

function validarCPF(cpf,form){

	var obj 	= form;
	var filtro = /^\d{3}.\d{3}.\d{3}-\d{2}$/i;

	if(cpf.length == 0) {
	 return true;
	}
		
	if(!filtro.test(cpf))
	{
			window.alert("CPF inválido. Tente novamente.1");
			obj.idcpf.focus;

		// Função pra resolver problema no FireFox
		if (obj.idcpf.value != "") {
			setTimeout(function() {obj.idcpf.focus();}, 0);
		}		
		return false;
	}
   
	cpf = remove(cpf, ".");
	cpf = remove(cpf, "-");
	
	if(cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" ||
		cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" ||
		cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" ||
		cpf == "88888888888" || cpf == "99999999999")
	{
		window.alert("CPF inválido. Tente novamente.2");
		obj.idcpf.focus();

		// Função pra resolver problema no FireFox
		if (obj.idcpf.value != "") {
			setTimeout(function() {obj.idcpf.focus();}, 0);
		}		

		return false;
   }

	soma = 0;
	for(i = 0; i < 9; i++)
	{
		soma += parseInt(cpf.charAt(i)) * (10 - i);
	}
	
	resto = 11 - (soma % 11);
	if(resto == 10 || resto == 11)
	{
		resto = 0;
	}
	if(resto != parseInt(cpf.charAt(9))){
		window.alert("CPF inválido. Tente novamente.3");
		obj.idcpf.focus();

		// Função pra resolver problema no FireFox
		setTimeout(function() {obj.idcpf.focus();}, 0);
		return false;
	}
	
	soma = 0;
	for(i = 0; i < 10; i ++)
	{
		soma += parseInt(cpf.charAt(i)) * (11 - i);
	}
	resto = 11 - (soma % 11);
	if(resto == 10 || resto == 11)
	{
		resto = 0;
	}
	
	if(resto != parseInt(cpf.charAt(10))){
		window.alert("CPF inválido. Tente novamente.4");
		obj.idcpf.focus();

		// Função pra resolver problema no FireFox
		setTimeout(function() {obj.idcpf.focus();}, 0);
		return false;
	}
	
	return true;
 }
 
function remove(str, sub) {
	i = str.indexOf(sub);
	r = "";
	if (i == -1) return str;
	{
		r += str.substring(0,i) + remove(str.substring(i + sub.length), sub);
	}
	
	return r;
}

/**
   * MASCARA ( mascara(o,f) e execmascara() ) CRIADAS POR ELCIO LUIZ
   * elcio.com.br
   */

function mascaracep2(src, mask){
	var i = src.value.length;
	var saida = mask.substring(0,1);
	var texto = mask.substring(i);
	if (texto.substring(0,1) != saida)
	{
	src.value += texto.substring(0,1);
	}
} 

function mascara(o,f){
	v_obj=o;
	v_fun=f;
	setTimeout("execmascara()",1);
}

function execmascara(){
	v_obj.value=v_fun(v_obj.value);
}

function cpf_mask(v){
	v=v.replace(/\D/g,"");                 //Remove tudo o que não é dígito
	v=v.replace(/(\d{3})(\d)/,"$1.$2");    //Coloca ponto entre o terceiro e o quarto dígitos
	v=v.replace(/(\d{3})(\d)/,"$1.$2");    //Coloca ponto entre o setimo e o oitava dígitos
	v=v.replace(/(\d{3})(\d)/,"$1-$2");   //Coloca ponto entre o decimoprimeiro e o decimosegundo dígitos
	return v;
}

//Função para retirar os espaços em branco do início e do fim da string.
function Trim(strTexto) {
        // Substitúi os espaços vazios no inicio e no fim da string por vazio.
        return strTexto.replace(/^s+|s+$/g, '');
    }

//JavaScript Document
//adiciona mascara de cnpj
function MascaraCNPJ(cnpj){
	if(mascaraInteiro(cnpj)==false){
		event.returnValue = false;
	}	
	return formataCampo(cnpj, '00.000.000/0000-00', event);
}

//adiciona mascara de cep
function mascaracep(cep){
		if(mascaraInteiro(cep)==false){
		event.returnValue = false;
	}	
	return formataCampo(cep, '00.000-000', event);
}

//adiciona mascara de data
function MascaraData(data){
	if(mascaraInteiro(data)==false){
		event.returnValue = false;
	}	
	return formataCampo(data, '00/00/0000', event);
}

//adiciona mascara ao telefone
function MascaraTelefone(tel){	
	if(mascaraInteiro(tel)==false){
		event.returnValue = false;
	}	
	return formataCampo(tel, '(00) 0000-0000', event);
}

//adiciona mascara ao CPF
function MascaraCPF(cpf){
	if(mascaraInteiro(cpf)==false){
		event.returnValue = false;
	}	
	return formataCampo(cpf, '000.000.000-00', event);
}

//valida telefone
function ValidaTelefone(tel){
	exp = /\(\d{2}\)\ \d{4}\-\d{4}/;
	if(!exp.test(tel.value))
		alert('Numero de Telefone Invalido!');
}

//valida CEP
function ValidaCep(cep){
	exp = /\d{2}\.\d{3}\-\d{3}/;
	if(!exp.test(cep.value))
		alert('Numero de Cep Invalido!');		
}


		
/*
 * Outra função pra validar CEP
 */
function IsCEP(strCEP, blnVazio, form)
{
	var obj 	= form;

	// Caso o CEP não esteja nesse formato ele é inválido!
    var objER = /^[0-9]{2}.[0-9]{3}-[0-9]{3}$/;
    
    strCEP = Trim(strCEP);
    if(strCEP.length > 0)
        {
            if(objER.test(strCEP))
                return true;
            else
        		window.alert('Numero de Cep Inválido!');
            	obj.cep.focus;
            	// Função pra resolver problema no FireFox
            	if (obj.cep.value != "") {
            		setTimeout(function() {obj.cep.focus();}, 0);
            	}

            	return false;
        }
    else
        return blnVazio;
}

//valida data
function ValidaData(data){
	exp = /\d{2}\/\d{2}\/\d{4}/;
	if(!exp.test(data.value))
		alert('Data Invalida!');			
}

//valida o CPF digitado
function ValidarCPF(Objcpf){
	var cpf = Objcpf.value;
	exp = /\.|\-/g;
	cpf = cpf.toString().replace( exp, "" ); 
	var digitoDigitado = eval(cpf.charAt(9)+cpf.charAt(10));
	var soma1=0, soma2=0;
	var vlr =11;
	
	for(i=0;i<9;i++){
		soma1+=eval(cpf.charAt(i)*(vlr-1));
		soma2+=eval(cpf.charAt(i)*vlr);
		vlr--;
	}	
	soma1 = (((soma1*10)%11)==10 ? 0:((soma1*10)%11));
	soma2=(((soma2+(2*soma1))*10)%11);
	
	var digitoGerado=(soma1*10)+soma2;
	if(digitoGerado!=digitoDigitado)	
		alert('CPF Invalido!');		
}

//valida numero inteiro com mascara
function mascaraInteiro(){
	if (event.keyCode < 48 || event.keyCode > 57){
		event.returnValue = false;
		return false;
	}
	return true;
}

//valida o CNPJ digitado
function ValidarCNPJ(ObjCnpj){
	var cnpj = ObjCnpj.value;
	var valida = new Array(6,5,4,3,2,9,8,7,6,5,4,3,2);
	var dig1= new Number;
	var dig2= new Number;
	
	exp = /\.|\-|\//g;
	cnpj = cnpj.toString().replace( exp, "" ); 
	var digito = new Number(eval(cnpj.charAt(12)+cnpj.charAt(13)));
		
	for(i = 0; i<valida.length; i++){
		dig1 += (i>0? (cnpj.charAt(i-1)*valida[i]):0);	
		dig2 += cnpj.charAt(i)*valida[i];	
	}
	dig1 = (((dig1%11)<2)? 0:(11-(dig1%11)));
	dig2 = (((dig2%11)<2)? 0:(11-(dig2%11)));
	
	if(((dig1*10)+dig2) != digito)	
		alert('CNPJ Invalido!');
		
}

//formata de forma generica os campos
function formataCampo(campo, Mascara, evento) { 
	var boleanoMascara; 
	
	var Digitato = evento.keyCode;
	exp = /\-|\.|\/|\(|\)| /g;
	campoSoNumeros = campo.value.toString().replace( exp, "" ); 
 
	var posicaoCampo = 0;	 
	var NovoValorCampo="";
	var TamanhoMascara = campoSoNumeros.length;; 
	
	if (Digitato != 8) { // backspace 
		for(i=0; i<= TamanhoMascara; i++) { 
			boleanoMascara  = ((Mascara.charAt(i) == "-") || (Mascara.charAt(i) == ".")	|| (Mascara.charAt(i) == "/"));
			boleanoMascara  = boleanoMascara || ((Mascara.charAt(i) == "(") || (Mascara.charAt(i) == ")") || (Mascara.charAt(i) == " ")); 
			if (boleanoMascara) { 
				NovoValorCampo += Mascara.charAt(i); 
				  TamanhoMascara++;
			}else { 
				NovoValorCampo += campoSoNumeros.charAt(posicaoCampo); 
				posicaoCampo++; 
			  }	   	 
		  }	 
		campo.value = NovoValorCampo;
		  return true; 
	}else { 
		return true; 
	}
}

/*
 * ParÂmetro
 * ('##/##/####',nomedocampo)
 */	
function formatarcampo(mascara, documento){
	  var i = documento.value.length;
	  var saida = mascara.substring(0,1);
	  var texto = mascara.substring(i);
	  
	  if (texto.substring(0,1) != saida){
	            documento.value += texto.substring(0,1);
	  }
	  
}

function proximofocus(campo,form) {
	var obj = form;
	
	if(campo==1) {
		obj.datainativo.disabled = false;
		obj.datainativo.focus();

		// Função pra resolver problema no FireFox
		setTimeout(function() {obj.datainativo.focus();}, 0);
	} else {
		obj.datainativo.value = '';
		obj.datainativo.disabled = true;
		obj.submit.focus();
	}
}

function adjustFrame(frame){  
	var frmTemp;  
	if (document.all) {  
	var w = frame.document.body.scrollWidth;  
	var h = frame.document.body.scrollHeight;  
	  
	if(frame.document.body.scrollWidth > frame.document.body.offsetWidth) {  
	document.all[frame.name].height = h + 30;  
	}  
	else {  
	document.all[frame.name].height = h;  
	}  
	}  
	else if (document.getElementById) {  
	  
	var w = frame.document.width;  
	var h = frame.document.height;  
	  
	if(frame.document.body.scrollWidth > frame.document.body.offsetWidth) {  
	document.getElementById(frame.name).height = h;  
	}  
	else {  
	document.getElementById(frame.name).height = h;  
	}  
	}  
	return false;  
	}

function calcular_idade(data){ 

// Antes dessa funçao é necessário chamar antes outra funcçao para
// validação de DATA
	
   	//calculo a data de hoje 
   	hoje=new Date(); 

   	//calculo a data que recebo 
   	//descomponho a data em um array 
   	var array_data = data.split("/");

   	//comprovo que o ano, mes, dia são corretos 
   	var ano;
   	ano = parseInt(array_data[2]); 

   	var mes;
   	mes = parseInt(array_data[1]); 

   	var dia;
   	dia = parseInt(array_data[0]);	

   	//subtraio os anos das duas datas 
   	idade=hoje.getFullYear() - ano - 1; //-1 porque ainda nao fez anos durante este ano 

   	//se subtraio os meses e for menor que 0 entao nao cumpriu anos. Se for maior sim ja cumpriu 
   	if (hoje.getMonth() + 1 - mes < 0) //+ 1 porque os meses comecam em 0
   	{
      	 return idade;
   	}
   	
   	if (hoje.getMonth() + 1 - mes > 0)
   	{
   		return idade+1;
   	}

   	//entao eh porque sao iguais. Vejo os dias 
   	//se subtraio os dias e der menor que 0 entao nao cumpriu anos. Se der maior ou igual sim que já cumpriu 
   	if (hoje.getUTCDate() - dia >= 0)
   	{
   		return idade + 1;
   	}
   	
   	return idade;

	//função responsável pelo nome dos elementos do form
	function gE(ID) {
	return document.getElementById(ID);
	}
   	
}

function MesAno(digData,id) 
{
	var bissexto = 0;
	var data = '01/'+digData; 
	var tam = data.length;
	var obj = document.getElementById(id);
	if (tam == 10) 
	{
		var dia = data.substr(0,2);
		var mes = data.substr(3,2);
		var ano = data.substr(6,4);
		if ((ano > 1900)||(ano < 2100))
		{
			switch (mes) 
			{
				case '01':
				case '03':
				case '05':
				case '07':
				case '08':
				case '10':
				case '12':
					if  (dia <= 31) 
					{
						return true;
					}
					break;
				
				case '04':		
				case '06':
				case '09':
				case '11':
					if  (dia <= 30) 
					{
						return true;
					}
					break;
				case '02':
					/* Validando ano Bis---to / fevereiro / dia */ 
					if ((ano % 4 == 0) || (ano % 100 == 0) || (ano % 400 == 0)) 
					{ 
						bissexto = 1; 
					} 
					if ((bissexto == 1) && (dia <= 29)) 
					{ 
						return true;				 
					} 
					if ((bissexto != 1) && (dia <= 28)) 
					{ 
						return true; 
					}			
					break;						
			}
						
			
		}
		
		if(ano < 1900) 
		{
			alert("Mês e Ano "+digData+" é inválido!");
			obj.focus();				
			return false;						
		}
		
	}	
	alert("Mês e Ano "+digData+" é inválido!");
	obj.focus();
	return false;
}

</script>

</script>
