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

function buscarProduto(COD) {
		
	var ajax=openAjax();
	var nomecompleto=gE('idnomecompleto');
	var endereco=gE('idendereco');
	var numero=gE('idnumero');
	var cep=gE('cep');
	var complemento=gE('idcomplemento');
	var matricula=gE('idmatricula');
	var bairro=gE('idbairro');
	var cidade=gE('idcidade');
	var estado=gE('idestado');
	var ddd1=gE('idddd1');
	var fone1=gE('idfone1');
	var ddd2=gE('idddd2');
	var fone2=gE('idfone2');
	var email=gE('idemail');
	var responsa=gE('idresponsa');
	var ddd3=gE('idddd3');
	var fone3=gE('idfone3');
	var estadocivil=gE('idestadocivil');
	var sexo=gE('idsexo');
	var mesnasc=gE('idmesnasc');
	var dianasc=gE('iddianasc');
	var anonasc=gE('idanonasc');
	var prontuario=gE('idprontuario');
	
	ajax.open('GET','dadosagenda.php?cod='+COD,true);

	ajax.onreadystatechange=function(){
		
	if(ajax.readyState==1){
		
	nomecompleto.value='carregando...';
	endereco.value='carregando...';
	numero.value='carregando...';
	cep.value='carregando...';
	complemento.value='carregando...';
	matricula.value='carregando...';
	bairro.value='carregando...';
	cidade.value='carregando...';
	estado.value='carregando...';
	ddd1.value='carregando...';
	fone1.value='carregando...';
	ddd2.value='carregando...';
	fone2.value='carregando...';
	email.value='carregando...';
	responsa.value='carregando...';
	ddd3.value='carregando...';
	fone3.value='carregando...';
	estadocivil.value='carregando...';
	sexo.value='carregando...';
	mesnasc.value='carregando...';
	dianasc.value='carregando...';
	anonasc.value='carregando...';
	prontuario.value='carregando...';	
	
	}

	if(ajax.readyState==4){
		
	if(ajax.status==200){
	r=ajax.responseText;
	rproduto= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rvalor = r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rnumero = r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rcep = r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rcomplemento = r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rmatricula= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rbairro= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rcidade= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	restado= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rddd1= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rfone1= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rddd2= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rfone2= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	remail= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rresponsa= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rddd3= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rfone3= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	restadocivil= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rsexo= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rmesnasc= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rdianasc= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	ranonasc= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);
	rprontuario= r.substring(0, (i = r.indexOf(',')));
	r = r.substring(++i);

	
	nomecompleto.value=rproduto;
	if(nomecompleto.value.length > 0 ) {
		nomecompleto.readOnly = 'true';
	}

	endereco.value=rvalor;
	if(endereco.value.length > 0 ) {
		endereco.readOnly = 'true';
	}

	numero.value=rnumero;
	if(numero.value.length > 0 ) {
		numero.readOnly = 'true';
	}

	cep.value=rcep;
	if(cep.value.length > 0 ) {
		cep.readOnly = 'true';
	}

	complemento.value=rcomplemento;	
	if(complemento.value.length > 0 ) {
		complemento.readOnly = 'true';
	}

	matricula.value=rmatricula;
	if(matricula.value.length > 0 ) {
		matricula.readOnly = 'true';
	}

	bairro.value=rbairro;
	if(bairro.value.length > 0 ) {
		bairro.readOnly = 'true';
	}

	cidade.value=rcidade;
	if(cidade.value.length > 0 ) {
		cidade.readOnly = 'true';
	}

	estado.value=restado;
	if(estado.value.length > 0 ) {
		estado.readOnly = 'true';
	}

	ddd1.value=rddd1;
	if(ddd1.value.length > 0 ) {
		ddd1.readOnly = 'true';
	}

	fone1.value=rfone1;
	if(fone1.value.length > 0 ) {
		fone1.readOnly = 'true';
	}

	ddd2.value=rddd2;
	if(ddd2.value.length > 0 ) {
		ddd2.readOnly = 'true';
	}

	fone2.value=rfone2;
	if(fone2.value.length > 0 ) {
		fone2.readOnly = 'true';
	}

	email.value=remail;
	if(email.value.length > 0 ) {
		email.readOnly = 'true';
	}

	responsa.value=rresponsa;	
//	if(responsa.value.length > 0 ) {
//		responsa.readOnly = 'true';
//	}

	ddd3.value=rddd3;
	if(ddd3.value.length > 0 ) {
		ddd3.readOnly = 'true';
	}

	fone3.value=rfone3;
	if(fone3.value.length > 0 ) {
		fone3.readOnly = 'true';
	}


	estadocivil.value=restadocivil;
//	if(estadocivil.value.length > 0 ) {
//		estadocivil.disabled = '0';
//	}

	sexo.value=rsexo;
//	if(sexo.value.length > 0 ) {
//		sexo.disabled = 'true';
//	}

	mesnasc.value=rmesnasc;
	if(mesnasc.value.length > 0 ) {
		mesnasc.disabled = 'true';
	}

	dianasc.value=rdianasc;
	if(dianasc.value.length > 0 ) {
		dianasc.disabled = 'true';
	}

	anonasc.value=ranonasc;
	if(anonasc.value.length > 0 ) {
		anonasc.disabled = 'true';
	}
	
	prontuario.value=rprontuario;

	removerDivs();
	
	}
	}
	}

	ajax.send(null);
	return false;
	
	}

//função
	
	
	function gE(ID) {
	return document.getElementById(ID);
	}