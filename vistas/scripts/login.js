$("#frmAcceso").on('submit', function(e) {
        e.preventDefault();
        
        var logina = $("#logina").val();
        var clavea = $("#clavea").val();
    
        $.post("../ajax/usuario.php?op=verificar",
            {"logina": logina, "clavea": clavea},
            function(data) {
                if (data.trim() !== "null") {
                    // Redirige al usuario al escritorio
                    $(location).attr("href", "escritorio.php");
                    // Muestra una alerta de éxito
                    bootbox.alert("¡Logeado correctamente!");
                } else {
                    // Muestra una alerta de error cuando las credenciales son incorrectas
                    bootbox.alert("Usuario y/o Password incorrectos");
                }
            })
            .fail(function() {
                // Muestra una alerta de error genérica cuando ocurre un fallo en la solicitud AJAX
                bootbox.alert("Error al intentar acceder. Por favor, inténtalo de nuevo más tarde.");
            });
    });