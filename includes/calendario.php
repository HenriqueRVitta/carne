

		<!-- Começa aqui o calendário -->
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
			<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
			<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
		<script>
		$(function() {
		    $( "#calendario1" ).datepicker({
		        showOn: "button",
		        buttonImage: "../../includes/icons/calendario.png",	// imagem pra chamar o calendário
		        buttonImageOnly: true,	 // Mostrando ícone para ativar o calendário
		        showButtonPanel: false,	 // Calendário com barra de botões de ação true ou false
				changeMonth: true,		 // Permitindo selecionar outros meses e anos
				changeYear: true,		 // Permitindo selecionar outros meses e anos
				showOtherMonths: true,	 // Mostrando datas dos meses seguinte e anterior
				selectOtherMonths: true, // Mostrando datas dos meses seguinte e anterior
				numberOfMonths: 1,		 // Exibindo mais de um mês
				dateFormat: 'dd/mm/yy',	 // Definindo o formato da data
				dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
				dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
				dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
				monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
				monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']
		    });
		});
		</script>
		
		<script>
		$(function() {
		    $( "#calendario2" ).datepicker({
		        showOn: "button",
		        buttonImage: "../../includes/icons/calendario.png",	// imagem pra chamar o calendário
		        buttonImageOnly: true,	 // Mostrando ícone para ativar o calendário
		        showButtonPanel: false,	 // Calendário com barra de botões de ação true ou false
				changeMonth: true,		 // Permitindo selecionar outros meses e anos
				changeYear: true,		 // Permitindo selecionar outros meses e anos
				showOtherMonths: true,	 // Mostrando datas dos meses seguinte e anterior
				selectOtherMonths: true, // Mostrando datas dos meses seguinte e anterior
				numberOfMonths: 1,		 // Exibindo mais de um mês
				dateFormat: 'dd/mm/yy',	 // Definindo o formato da data
				dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
				dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
				dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
				monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
				monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']
		    });
		});
		</script>
		
<?php
