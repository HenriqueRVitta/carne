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
	
function buscaidade(COD) {

	var ajax=openAjax();
	var idade=gE('ididade');

	ajax.open('GET','/carne/admin/geral/dadosagenda.php?cod='+COD,true);
	
	ajax.onreadystatechange=function(){
		
	if(ajax.readyState==1){
		
	idade.value='carregando...';
	
	}

	if(ajax.readyState==4){
		
	if(ajax.status==200){
	r=ajax.responseText;
	ridade = r.substring(0, (i = r.indexOf(',')));
   	alert('Teste'+ridade);
   	
	idade.value=ridade;
	if(idade.value.length > 0 ) {
		idade.readOnly = 'true';
	}

	removerDivs();
	
	}
	}
	}

	ajax.send(null);
	return false;
	
	}

	//função responsável pelo nome dos elementos do form
	function gE(ID) {
	return document.getElementById(ID);
	}