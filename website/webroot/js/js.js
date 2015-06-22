function siguientePregunta(id) {
    // si es mayor que cero, ocultar la actual
    if (preguntaActual!=0) {
        $('#pregunta'+preguntaActual).css('display', 'none');
    }
    // pasar a la siguiente pregunta
    ++preguntaActual;
    // mostrar pregunta si existe
    if (preguntaActual<=preguntasTotales) {
        $('#pregunta'+preguntaActual).css('display', 'block');
    }
    // si se acabaron las preguntas mostrar boton para enviar formulario
    else {
        $('input[type="submit"]').css('display', 'block');
    }
}

function validarGeneracionPrueba() {
    // verificaciones generales
    if(!Form.check()) return false;
    // verificar que los porcentajes sumen 100
    var suma = parseInt($('#tipo_1Field').val())+parseInt($('#tipo_2Field').val())+parseInt($('#tipo_3Field').val());
    if(suma != 100) {
        alert('¡Porcentajes deben sumar 100, no '+suma+'!');
        $('#tipo_1Field').focus();
        return false;
    }
    // verificar que se haya seleccionado al menos una prueba
    var pruebasSeleccionadas = 0;
    $('input[name="prueba_id[]"]').each(function () {
        if (this.checked)
            ++pruebasSeleccionadas;
    });
    if(pruebasSeleccionadas==0) {
        alert('¡Debe seleccionar al menos una prueba\ndesde donde sacar las preguntas!');
        return false;
    }
    // todo ok
    return true;
}

function validarEdicionPrueba() {
    // verificaciones generales
    if(!Form.check()) return false;
    // permitir abandonar la página
    needToConfirmToExit = false;
    // verificar que se quiera enviar el formulario
    if(!Form.checkSend('¿Desea guardar la prueba?')) {
        needToConfirmToExit = true;
        return false;
    }
    // todo ok
    return true;
}

function agregarPregunta() {
    $('#preguntas').append(
        '<div>'+
            '<input type="hidden" name="preguntas[]" value="'+preguntaId+'" />'+
            '<div>'+
                '<a href="#" onclick="$(this).parent().parent().remove(); return false" title="Eliminar" class="fright"><img src="'+_base+'/img/icons/16x16/actions/delete.png" alt="del"></a>'+
                'Activa: <input type="checkbox" name="activa'+preguntaId+'" checked="checked" /> '+
                'Pública: <input type="checkbox" name="publica'+preguntaId+'" checked="checked" /> '+
                'Tipo: <select name="tipo'+preguntaId+'" class="tipo">'+
                    '<option value="1">Fácil</option>'+
                    '<option value="2" selected="selected">Normal</option>'+
                    '<option value="3">Difícil</option>'+
                '</select> '+
                'Imagen: <input type="file" name="imagen'+preguntaId+'" />'+
            '</div>'+
            '<div><textarea name="pregunta'+preguntaId+'" placeholder="Pregunta" class="pregunta"></textarea></div>'+
            '<div class="respuestas">'+
                '<a href="javascript:agregarRespuesta('+preguntaId+')" title="Agregar alternativa">[+] Agregar alternativa</a>'+
                '<div id="respuestas'+preguntaId+'"></div>'+
                '<span>Respuesta(s) correcta(s) inicia(n) con un *</span>'+
            '</div>'+
            '<div class="clear"><textarea name="explicacion'+preguntaId+'" placeholder="Explicación" class="explicacion"></textarea></div>'+
        '</div>'
    );
    agregarRespuesta(preguntaId);
    agregarRespuesta(preguntaId);
    preguntaId++;
}

function agregarRespuesta(preguntaId) {
    $('#respuestas'+preguntaId).append(
        '<div>'+
            '<input type="text" name="respuesta'+preguntaId+'[]" placeholder="Alternativa" class="respuesta" />'+
            ' <a href="#" onclick="$(this).parent().remove(); return false" title="Eliminar"><img src="'+_base+'/img/icons/16x16/actions/delete.png" alt="del"></a>'+
        '</div>'
    );
}
