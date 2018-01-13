$(function() {
    $("#calendar").fullCalendar({
        header : {
            left: "",
            center: "title",
            right: "prev next today"

        },
        editable : false,
        dayClick: function(date, allDay, jsEvent, view) {
            var today = new Date();
            /* si no es una fecha valida entonces sólo notificarle */
            if (date.getTime() > today.getTime()) {
                var cajaNotificacion = document.createElement("div");
                cajaNotificacion.style.position = "absolute";
                cajaNotificacion.style.top = "0";
                cajaNotificacion.style.width = "100%";
                cajaNotificacion.style.height = "100%";
                cajaNotificacion.style.backgroundColor = "#F2DEDE";
                cajaNotificacion.style.display = "none";
                var parrafo = document.createElement("p");
                parrafo.style["text-align"] = "center";
                parrafo.style["font-size"] = "1em";
                parrafo.style["letter-spacing"] = "0.1em";
                var texto = document.createTextNode("Fecha futura, aún no válida");
                parrafo.appendChild(texto);
                cajaNotificacion.appendChild(parrafo);
                this.appendChild(cajaNotificacion);
                $(cajaNotificacion).fadeIn("slow", function() {
                    // console.log("delay 3 seconds started");
                    $(cajaNotificacion).delay(1600).fadeOut("slow", function() {
                        cajaNotificacion.parentElement.removeChild(cajaNotificacion);
                    });
                });
                return;
            }
            var form = document.createElement("form");
            form.setAttribute("action", base_url() + "pauta/listado");
            form.setAttribute("method", "post");
            form.style.display = "none";
            input = document.createElement("input");
            input.setAttribute("type", "hidden");
            input.setAttribute("name", "fecha");
            input.setAttribute("value", date.getTime() / 1000);
            form.appendChild(input);
            this.appendChild(form);
            form.submit();
            // $(this).css("background-color", "red");
        }
    });
});
