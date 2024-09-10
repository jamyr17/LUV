$(document).ready(function() {
    // Establece la fuente de autocompletado
    $('#nombre').autocomplete({
      source: function(request, response) {
        $.ajax({
          url: '../action/autocomplete.php',
          type: 'GET',
          dataType: 'json',
          data: {
            term: request.term,
            type: $('#type').val()  // Obtén el tipo del campo oculto
          },
          success: function(data) {
            response(data);
          },
          error: function() {
            response([]);
          }
        });
      },
      minLength: 2  // Número mínimo de caracteres para activar el autocompletado
    });
  });
  