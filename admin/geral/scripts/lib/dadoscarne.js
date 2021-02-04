// Esta função instancia o objeto XMLHttpRequest
function openAjax() {
	var ajax;
	try {
	ajax = new XMLHttpRequest();
	} catch(ee) {
	try {
	ajax = new ActiveXObject("Msxml2.XMLHTTP");
	} catch(e) {
	try {
	ajax = new ActiveXObject("Microsoft.XMLHTTP");
	} catch(E) {
	ajax = false;
	}
	}
	}
	return ajax;
	}

function buscarCarne(COD) {
		
	var ajax=openAjax();
	
	var idcliente=gE('idcliente');
	var nomecliente=gE('idnomecliente');
	
	ajax.open('GET','dadospagto.php?cod=' + COD,true);
	
	ajax.onreadystatechange=function(){
		
	if(ajax.readyState==1){
	
	cliente.value='carregando...';		
	nomecliente.value='carregando...';
	
	}
	
	if(ajax.readyState==4){
		
	if(ajax.status==200){
		
	r=ajax.responseText;
	rcliente= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rnomecliente = r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rnrocarne = r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rprontuario = r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rplano = r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	
	cliente.value=rcliente;
	if(cliente.value.length > 0 ) {
		cliente.readOnly = 'true';
	}

	nomecliente.value=rnomecliente;
	if(nomecliente.value.length > 0 ) {
		nomecliente.readOnly = 'true';
	}


	plano.value=rplano;
	if(plano.value.length > 0 ) {
		plano.readOnly = 'true';
	}
	
	cliente.value=ridcliente;

	alert(rplano);

	removerDivs();
	
	}
	}
	}

	ajax.send(null);
	return false;
	
	}

	// Função
	function gE(ID) {
	return document.getElementById(ID);
	}
	