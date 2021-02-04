$(document).ready( function() {
   /* Executa a requisição quando o campo CEP perder o foco */
   $('#cep').blur(function(){
           /* Configura a requisição AJAX */
           $.ajax({
                url : 'consultar_cep.php', /* URL que será chamada */ 
                type : 'POST', /* Tipo da requisição */ 
                data: 'cep=' + $('#cep').val(), /* dado que será enviado via POST */
                dataType: 'json', /* Tipo de transmissão */
                success: function(data){
                    if(data.sucesso == 1){
                        $('#idendereco').val(data.rua);
                        $('#idbairro').val(data.bairro);
                        $('#idcidade').val(data.cidade);
                        $('#idestado').val(data.estado);
 
                        $('#idnumero').focus();
                    }
                }
           });   
   return false;    
   });
});