@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

    <div class=container>
        <div id='calendar'></div>
    </div>
    <div id="DetalleModal" class="modal fade">
    </div>
    <div id="newServiceModal" class="modal fade">
    </div>

@stop

@section('css')
    <link rel='stylesheet' type='text/css' href='/vendor/fullcalendar/main.css'/>
    <link rel='stylesheet' type='text/css' href='/vendor/fontawesome-free/css/fontawesome.css'/>
    <link rel='stylesheet' type='text/css' href='/vendor/select2/dist/css/select2.css'/>
    <link rel='stylesheet' type='text/css' href='/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css'/>
    <link rel='stylesheet' type='text/css' href='vendor/bootstrap-toggle-master/css/bootstrap-toggle.min.css'>
    <style>
        .select2 {
            width: 100% !important;
        }

        .select2-selection {
            -webkit-box-shadow: 0;
            box-shadow: 0;
            background-color: #fff;
            border: 0;
            border-radius: 0;
            color: #000000;
            font-size: 14px;
            outline: 0;
            min-height: 25px;
            text-align: left;
        }

        .select2-selection__rendered {
            margin: 10px;
            line-height: 33px !important;
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
        }

        .select2-selection__arrow {
            /*margin: 10px;*/
            height: 37px !important;

        }

        .container {
            max-width: 80%;
        }

        .tabla-trabajos {
            max-width: 100% !important;
        }

        .modal-ku {
            width: 30%;
            margin: auto;
        }

        .fa-minus-square {
            color: darkred;
            font-size: 24px;
        }

        /*th{*/
        /*    padding-top: 0px !important;*/
        /*    padding-bottom: 0px !important;*/
        /*}*/

        /*td{*/
        /*    vertical-align: baseline !important;*/
        /*    !*padding: 6px !important;*!*/
        /*}*/

        .btn-toolbar {
            display: flex;
            justify-content: space-between;
        }

        .left-group {
            display: flex;
            flex: 1;
            justify-content: flex-start;
        }

        .right-group {
            display: flex;
            flex: 1;
            justify-content: flex-end;
        }

        .client-group {
            display: flex;
            justify-content: space-between;
        }

        .client-left-group {
            display: flex;
            flex: 1;
            justify-content: flex-start;
            flex-grow: 9;
        }

        .client-right-group {
            display: flex;
            flex: 1;
            justify-content: flex-end;
            flex-grow: 1;
        }

        .cara-cliente {
            margin: auto;
            font-size: 30px;
        }

        .lista-detalle-trabajos-consumibles {
            display: flex;
            justify-content: space-between;
        }

        .lista-detalle-trabajos-consumibles-izquierda {
            display: flex;
            justify-content: flex-start;
            flex-grow: 6;

        }

        .lista-detalle-trabajos-consumibles-izquierda-sangria {
            display: flex;
            justify-content: flex-start;
            flex-grow: 0.5;

        }

        .lista-detalle-trabajos {
            display: flex;

        }

        .lista-detalle-trabajos-descripcion {
            flex-grow: 8;
        }

        .lista-detalle-trabajos-precio {
            border-color: red;
            flex-grow: 1;
        }

        .lista-detalle-trabajos-eliminar {
            flex-grow: 1;
        }


        .main {
            width: 100%;
            /*margin: 50px auto;*/
        }

        .form-group .form-control {
            padding-left: 2.375rem;
        }

        .form-group .form-control-icon {
            position: absolute;
            z-index: 2;
            display: block;
            width: 2.375rem;
            height: 2.375rem;
            line-height: 2.375rem;
            text-align: center;
            pointer-events: none;
            color: #aaa;
        }
    </style>




@stop

@section('js')
    <script type='text/javascript' src='/vendor/jquery/jquery.js'></script>
    <script type='text/javascript' src='/vendor/fullcalendar/main.js'></script>
    <script type='text/javascript' src='/vendor/bootstrap/js/bootstrap.js'></script>
    <script type='text/javascript' src='/vendor/select2/dist/js/select2.js'></script>
    <script type='text/javascript' src='/vendor/bootstrap-datetimepicker/js/moment-with-locales.js'></script>
    <script type='text/javascript' src='/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'></script>
    <script type='text/javascript' src='/vendor/bootstrap-toggle-master/js/bootstrap-toggle.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var calendarEl = document.getElementById('calendar');
            window.calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                aspectRatio: 2,
                editable: true,
                expandRows: false,
                slotMinTime:'09:00',
                slotMaxTime:'21:00',
                businessHours: [ // specify an array instead
                    {
                        daysOfWeek: [1],
                        startTime: '10:00',
                        endTime: '14:00'
                    },
                    {
                        daysOfWeek: [ 2, 3, 4, 5 ], // Monday, Tuesday, Wednesday
                        startTime: '10:00',
                        endTime: '20:00'
                    },
                    {
                        daysOfWeek: [6], // Thursday, Friday
                        startTime: '10:00', // 10am
                        endTime: '14:00' // 4pm
                    }
                ],
                initialView: 'timeGridWeek',
                headerToolbar: {
                    center: 'dayGridMonth,timeGridWeek,timeGrid',
                    end: 'today prev,next'
                },
                footerToolbar: {
                    start: 'nuevoServicio',
                },
                customButtons: {
                    nuevoServicio: {
                        text: 'Nuevo Servicio',
                        click: nuevoServio,
                    }
                },
                events: {
                    url: '/eventos',
                    method: 'GET',
                    // extraParams: {
                    //     custom_param1: 'something',
                    //     custom_param2: 'somethingelse'
                    // },
                    failure: function() {
                        alert('there was an error while fetching events!');
                    },
                    // color: 'orange',   // a non-ajax option
                    // textColor: 'black' // a non-ajax option
                },
                eventDrop: function(eventoDetalle) {

                    // var fechaInicio = eventoDetalle.event.startStr;
                    fechaInicio = moment(eventoDetalle.event.startStr).format("YYYY-MM-DD HH:mm");
                    // var fechaFin = eventoDetalle.event.start;
                    fechaFin = moment(eventoDetalle.event.endStr).format("YYYY-MM-DD HH:mm");

                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        type: 'put',
                        dataType: 'json',
                        url: '{{ route("ActualizarHorasServicio") }}',
                        data: {
                            '_token': $('input[name=_token]').val(),
                            "id_servicio": eventoDetalle.event.id,
                            "fecha-inicio": fechaInicio,
                            "fecha-fin": fechaFin

                        },
                        beforeSend: function () {
                            console.log('bloqueo botones');
                        },
                        complete: function () {
                            calendar.refetchEvents();
                            console.log('desbloqueo botones');
                        },
                        success: function (response) {

                            $('#DetalleModal').modal('hide');


                        },
                        error: function (jqXHR) {
                            console.log('boo!');
                        }
                    });

                },
                dateClick: function(info){
                    console.log(info.date.toISOString());
                    console.log(info.dateStr);
                    console.log(info.allDay);
                    console.log(info.dayEl);
                    console.log(info.jsEvent);
                    nuevoServio(info.dateStr);
                },
                eventClick:  function(event, jsEvent, view) {

                    var modal = $('#DetalleModal');
                    modal.empty();
                    var modalDetalleContent =   '<div class="modal-dialog modal-lg">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>' +
                        '<h4 id="modalTitle" class="modal-title"></h4>' +
                        '</div>' +
                        '<div id="modalBody" class="modal-body">' +
                        '<form method="post" action="">' +
                        '<meta name="csrf-token" content="{{ csrf_token() }}">' +
                        '<div class="row">' +
                        '<div class="col-lg-12">' +
                        '<div id="inputFormRow">' +
                        '<fieldset class="border p-2">' +
                        '<legend  class="w-auto">Datos del cliente</legend>' +
                        '<label for="eventoDetalleCliente">Nombre :</label>' +
                        '<input id="eventoDetalleCliente" class="form-control m-input" type="text" readonly>' +
                        '</fieldset>' +
                        '<br>' +
                        '<fieldset class="border p-2">' +
                        '<legend  class="w-auto">Trabajos del servicio</legend>' +
                        '<div class="container tabla-trabajos">' +
                        '<div class="table-responsive">' +
                        '<table class="table" id="tabla-servicios">' +
                        '<thead>' +
                        '<tr>' +
                        '<th class="w-75" >Servicio</th>' +
                        '<th class="w-10">Precio</th>' +
                        '<th class="w-10"></th>' +
                        '</tr>' +
                        '</thead>' +
                        '<tbody id="tbody">' +
                        '</tbody>' +
                        '</table>' +
                        '</div>' +
                        '</div>' +
                        '<div id="newRow"></div>' +
                        '</fieldset>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</form>' +
                        '</div>' +
                        '<div class="modal-footer buttons-toolbar" id="detalle_evento_modal_footer">' +
                        '</div>' +
                        '</div>' +
                        '</div>';

                    modal.append(modalDetalleContent);

                    $('#modalTitle').html(event.title);
                    $('#modalBody').html(event.description);

                    $.ajax(
                        {
                            type:'get',
                            url:'/eventoDetalle?id='+event.event._def.publicId,
                            success:function(result)
                            {
                                var eventoDetalle = result[0];
                                $('#eventoDetalleCliente').val(eventoDetalle.cliente.nombre + ' ' + eventoDetalle.cliente.apellido_1 + ' ' + eventoDetalle.cliente.apellido_2);

                                var rowIdx = 0;
                                $(eventoDetalle.trabajos_eventos).each(function( trabajo, elemt ) {


                                        // Adding a row inside the tbody.
                                        $('#tbody').append(`<tr id="row-${rowIdx}">
                                                        <td class="row-index text-center">
<!--                                                            <select class="select-trabajo-id&#45;&#45;${rowIdx} form-control" id="trabajos-${rowIdx}" name="select-trabajo-id&#45;&#45;${rowIdx}"></select>-->
                                                            <input type="text" name="title[]" id="trabajos-${rowIdx} class="class="select-trabajo-id--${rowIdx} form-control m-input" value="${this.trabajo.descripcion}">
                                                        </td>
                                                        <td>
                                                            <div class="main">
                                                              <div class="form-group">
                                                                <span class="fas fa-euro-sign form-control-icon"></span>
                                                                <input type="float" class="form-control" name="precio_estiamado-row-${rowIdx} " id="precio_estiamado-row-${rowIdx}" value="${this.trabajo.precio_estimado}">
                                                              </div>
                                                            </div>
                                                        </td>
                                                     </tr>`);


                                    if(eventoDetalle.estado_id === 2){
                                        $(`tr[id="row-${rowIdx}"]`).append(`<td class="text-center">
                                                                        <i class="far fa-minus-square remove" aria-hidden="true"></i>
                                                                  </td>`);
                                        if (this.trabajo.admite_consumibles == 1){

                                            $(`#tbody`).append(`
                                                        <tr id="row-${rowIdx}.${rowIdx}">
                                                            <td>
                                                                 <label for="id="trabajos-${rowIdx}"">Seleccione un producto:</label>
                                                                 <select class="select-trabajo-id&#45;&#45;${rowIdx} form-control" id="trabajos-${rowIdx}" name="select-trabajo-id&#45;&#45;${rowIdx}"></select>
                                                            </td>
                                                        </tr>`);

                                        }
                                    }
                                    rowIdx++;
                                });

                                var botones = calculaBotonesFooter(eventoDetalle.estado_id);

                                $('#detalle_evento_modal_footer').append(botones);

                                $('#no_show').on('click', function () {

                                    $.ajax({
                                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                        type: 'post',
                                        dataType: 'json',
                                        url: '{{ route("noShowService") }}',
                                        data: {
                                            '_token': $('input[name=_token]').val(),
                                            "id_servicio": eventoDetalle.id
                                        },
                                        beforeSend: function () {
                                            console.log('bloqueo botones');
                                        },
                                        complete: function () {
                                            calendar.refetchEvents();
                                            console.log('desbloqueo botones');
                                        },
                                        success: function (response) {

                                            $('#DetalleModal').modal('hide');


                                        },
                                        error: function (jqXHR) {
                                            console.log('boo!');
                                        }
                                    });

                                });

                                $('#cancel').on('click', function () {

                                    $.ajax({
                                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                        type: 'post',
                                        dataType: 'json',
                                        url: '{{ route("cancelEvento") }}',
                                        data: {
                                            '_token': $('input[name=_token]').val(),
                                            "id_servicio": eventoDetalle.id
                                        },
                                        beforeSend: function () {
                                            console.log('bloqueo botones');
                                        },
                                        complete: function () {
                                            calendar.refetchEvents();
                                            console.log('desbloqueo botones');
                                        },
                                        success: function (response) {

                                            $('#DetalleModal').modal('hide');


                                        },
                                        error: function (jqXHR) {
                                            console.log('boo!');
                                        }
                                    });

                                });

                                $('#Iniciar_servicio').on('click', function () {

                                    $.ajax({
                                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                        type: 'post',
                                        dataType: 'json',
                                        url: '{{ route("iniciarServicio") }}',
                                        data: {
                                            '_token': $('input[name=_token]').val(),
                                            "id_servicio": eventoDetalle.id
                                        },
                                        beforeSend: function () {
                                            console.log('bloqueo botones');
                                        },
                                        complete: function () {
                                           calendar.refetchEvents();
                                        },
                                        success: function (response) {

                                            $('#DetalleModal').modal('hide');


                                        },
                                        error: function (jqXHR) {
                                            console.log('boo!');
                                        }
                                    });

                                });

                                $('#finalizar_servicio').on('click', function () {

                                    $.ajax({
                                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                        type: 'post',
                                        dataType: 'json',
                                        url: '{{ route("finalizarServicio") }}',
                                        data: {
                                            '_token': $('input[name=_token]').val(),
                                            "id_servicio": eventoDetalle.id
                                        },
                                        beforeSend: function () {
                                            console.log('bloqueo botones');
                                        },
                                        complete: function () {
                                            calendar.refetchEvents();
                                            console.log('desbloqueo botones');
                                        },
                                        success: function (response) {

                                            $('#DetalleModal').modal('hide');


                                        },
                                        error: function (jqXHR) {
                                            console.log('boo!');
                                        }
                                    });

                                });
                                $('#DetalleModal').on('hidden.bs.modal', function() {
                                    $('#DetalleModal').empty();
                                })
                                $('#DetalleModal').modal("show");
                            }
                        }
                    );
                }
            });


        calendar.render();
    });

        function calculaBotonesFooter(estado_id){
            var botones ='';

            switch (estado_id) {
                case 1:
                    botones = '<button type="button" class="btn btn-warning" id="no_show" data-dismiss="modal">No Show</button>' +
                        '<button type="button" class="btn btn-success" id="Iniciar_servicio" data-dismiss="modal">Inicio Servicio</button>' +
                        '<button type="button" class="btn btn-danger" id="cancel" data-dismiss="modal">Cancelado</button>';
                    break;
                case 2:
                    botones =   '<div class="left-group">'+
                                    '<button class="btn btn-md btn-primary" id="addBtn">Añadir trabajo</button>'+
                                '</div>' +
                                '<div class="right-group">'+
                                    '<button type="button" class="btn btn-success" id="finalizar_servicio" data-dismiss="modal">Finalizar Servicio</button>' +
                                '</div>';
                    break;
                case 3:

                    break;
                case 4:
                    break;

            };

            return botones;

        }

        function calculaFechaFin(){

            var fechaInicio = $('#fecha-inicio').val();
            var sumaTiempos = 0
            // var tabla = $(event.currentTarget.parentElement.parentElement.parentElement);
            var tabla = $('#tabla-servicios');
            var tiempos = tabla.find('input[name^="duracion_estiamada"]' ).serializeArray();
            var valorFechaInicio = moment(fechaInicio, 'YYYY-MM-DD HH:mm')

            for (let i = 0; i < tiempos.length; i++) {
                sumaTiempos = sumaTiempos + Number(tiempos[i].value);
            }
            var valorFechaFin = valorFechaInicio.add(sumaTiempos, 'minutes').format('YYYY-MM-DD HH:mm');

            return valorFechaFin;
        }

    function nuevoServio(fechaInicioClick){

            var modal = $('#newServiceModal');
            modal.empty();
            var modalContent =  '<div class="modal-dialog modal-lg">' +
                '<div class="modal-content">' +
                '<div id="modalBody" class="modal-body">' +
                '<div class="row">' +
                '<div class="col-lg-12">' +
                '<div id="inputFormRow">' +
                '<fieldset class="border p-2" id="cuadro-datos-cliente">' +
                '<legend  class="w-auto">Datos del cliente</legend>' +
                '<label for="eventoDetalleCliente">Nombre :</label>' +
                '<div class="modal-footer client-group">' +
                '<div class="client-left-group">'+
                '<select class="select_clientes form-control" id="select_clientes"></select>' +
                '</div>' +
                '<div class="client-right-group" id="client-right-group">' +
                '</div>' +
                '</div>' +
                '</fieldset>' +
                '<br>' +
                '<fieldset class="border p-2">' +
                '<legend  class="w-auto">Datos del servicio</legend>' +
                '<label for="fecha-inicio">Fecha y Hora :</label>' +
                '<input type="text" class="form-control" id="fecha-inicio">' +
                '<label for="fecha-fin">Fecha y Hora :</label>' +
                '<input type="text" class="form-control" id="fecha-fin" readonly>' +
                '</fieldset>' +
                '<br>' +
                '<fieldset class="border p-2">' +
                '<legend  class="w-auto">Trabajos del servicio</legend>' +
                '<div class="container tabla-trabajos">' +
                '<div class="table-responsive">' +
                '<table class="table" id="tabla-servicios">' +
                '<thead>' +
                '<tr>' +
                '<th class="w-75" >Servicio</th>' +
                '<th class="w-10">Tiempo</th>' +
                '<th class="w-10"></th>' +
                '</tr>' +
                '</thead>' +
                '<tbody id="tbody">' +
                '</tbody>' +
                '</table>' +
                '</div>' +
                '</div>' +
                '<div id="newRow"></div>' +
                '</fieldset>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="modal-footer buttons-toolbar">' +
                '<div class="left-group">'+
                '<button class="btn btn-md btn-primary" id="addBtn">Add new Row</button>' +
                '</div>' +
                '<div class="right-group">'+
                '<button type="button" class="btn btn-success" id="submitNewEvent">Guardar</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>';

            modal.append(modalContent);





            // Denotes total number of rows
            var rowIdx = 0;

            // jQuery button click event to add a row
            $('#addBtn').on('click', function () {

                // Adding a row inside the tbody.
                $('#tbody').append(`<tr id="row-${rowIdx}">
                                                        <td class="row-index text-center">
                                                            <select class="select-trabajo-id--${rowIdx} form-control" id="trabajos-${rowIdx}" name="select-trabajo-id--${rowIdx}"></select>
                                                        </td>
                                                        <td>
<!--                                                            <input name="duracion_estiamada-row-${rowIdx} " id="duracion_estiamada-row-${rowIdx}" type="number">-->
                                                            <div class="main">
                                                              <div class="form-group">
                                                                <span class="far fa-clock form-control-icon"></span>
                                                                <input type="text" class="form-control" name="duracion_estiamada-row-${rowIdx} " id="duracion_estiamada-row-${rowIdx}" placeholder="Tiempo">
                                                              </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <i class="far fa-minus-square remove" aria-hidden="true"></i>

<!--                                                            <button class="btn btn-danger remove" type="button">Remove</button>-->
                                                        </td>
                                                    </tr>`);

                $.ajax(
                    {
                        type:'get',
                        url:'/trabajos',
                        success:function(trabajos)
                        {
                            var html = '';
                            html += '<option value=""></option>';
                            $(trabajos).each(function(elemt, trabajo ) {
                                html += '<option value="'+ trabajo.id +'">'+ trabajo.descripcion +'</option>';
                            });
                            $(`#trabajos-${rowIdx}`).append(html);
                            $(`.select-trabajo-id--${rowIdx}`).select2();
                            ++rowIdx;
                        }
                    }
                );

                $(`.select-trabajo-id--${rowIdx}`).on('change', function(event) {

                    var a =  $(event.currentTarget).find(':selected');
                    var parentRowName = $(event.currentTarget.parentElement.parentElement).attr('id');

                    $.ajax(
                        {
                            type:'get',
                            url:'/trabajoDetalle?id=' + a[0].value,
                            success:function(trabajoDetalle)
                            {
                                $(`#duracion_estiamada-${parentRowName}`).val(trabajoDetalle.duracion_estiamada).trigger('change');
                                $(`#precio_estimado-${parentRowName}`).val(trabajoDetalle.precio_estimado).trigger('change');
                            }
                        });

                    $(`#duracion_estiamada-${parentRowName}`).on('change', function(event){

                        var valorFechaFin = calculaFechaFin();

                        $('#fecha-fin').val(valorFechaFin).trigger('change');

                    })




                })

            });

            // jQuery button click event to remove a row.
            $('#tbody').on('click', '.remove', function () {
                // Decreasing total number of rows by 1.
                rowIdx--;
                // Getting all the rows next to the row
                // containing the clicked button
                var child = $(this).closest('tr').nextAll();

                // Iterating across all the rows
                // obtained to change the index
                child.each(function () {

                    // Getting <tr> id.
                    var id = $(this).attr('id');

                    // Getting the <p> inside the .row-index class.
                    // var idx = $(this).children('.row-index').children('input');

                    // Gets the row number from <tr> id.
                    var dig = parseInt(id.substring(4));

                    // Modifying row index.
                    // idx.html(`row ${dig - 1}`);

                    // Modifying row id.
                    $(this).attr('id', `row-${dig - 1}`);


                    $(this).find(`#duracion_estiamada-row-${dig}`).attr('id', `duracion_estiamada-row-${dig - 1}`);
                    $(this).find(`#trabajos-${dig}`).attr('id', `trabajos-${dig - 1}`);


                });

                // Removing the current row.
                $(this).closest('tr').remove();

                var valorFechaFin = calculaFechaFin();

                $('#fecha-fin').val(valorFechaFin).trigger('change');

            });

            $.ajax(
                {
                    type:'get',
                    url:'/clientes',
                    success:function(clientes)
                    {

                        var html = '';
                        html += '<option value=""></option>';

                        // $('#eventoDetalleCliente').val(eventoDetalle.cliente.nombre + ' ' + eventoDetalle.cliente.apellido_1 + ' ' + eventoDetalle.cliente.apellido_2);
                        $(clientes).each(function(elemt, cliente ) {

                            html += '<option value="'+ cliente.id +'">'+ cliente.nombre +'</option>';

                        });
                        $('#select_clientes').select2({
                            placeholder: "select option",
                            selectOnClose: true,
                            maximumResultsForSearch: 2
                        }).on('select2:open', () => {
                            $(".select2-results:not(:has(a))").append('<a href="#" id="nuevoCliente" style="padding: 6px;height: 20px;display: inline-table;">Añadir nuevo cliente</a>');

                            $('#nuevoCliente:not(:has())').click(function(event) {

                                $('#select_clientes').select2('close');


                                $('#cuadro-datos-cliente').empty().append(
                                    '<legend  class="w-auto">Datos del cliente</legend>' +
                                    '<div class="form-group">'+
                                    '<label for="nombre">Nombre :</label>' +
                                    '<input type="text" class="form-control" id="nombre" name="nombre">'+
                                    '<label for="apellido_1">Apellido 1:</label>' +
                                    '<input type="text" class="form-control" id="apellido_1" name="apellido_1">'+
                                    '<label for="apellido_2">Apellido 2 :</label>' +
                                    '<input type="text" class="form-control" id="apellido_2" name="apellido_2">'+
                                    '<label for="alias">Alias :</label>' +
                                    '<input type="text" class="form-control" id="alias" name="alias">'+
                                    '</div>'
                                );

                            });
                        });

                        $('#select_clientes').append(html);


                        $('#toggle-one').bootstrapToggle();

                        if (typeof fechaInicioClick === "string"){

                            fechaInicioClick = fechaInicioClick.substring(0, fechaInicioClick.length - 9).replace('T', ' ');
                            $('#fecha-inicio').val(fechaInicioClick);

                        }

                        $('#newServiceModal').modal('show');

                    }

                });

            $('#fecha-inicio').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
            });

            $('#fecha-fin').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
            });


            $('#submitNewEvent').click(function(event) {


                var tabla = $('#tabla-servicios');

                var servicios = tabla.find('select[name^="select-trabajo-id--"]').serializeArray();


                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    type: 'post',
                    dataType: 'json',
                    url: '{{ route("nuevoServicio") }}',
                    data: {
                        '_token': $('input[name=_token]').val(),
                        "id_cliente": $('#select_clientes').val(),
                        "fecha-inicio": $('#fecha-inicio').val(),
                        "fecha-fin": $('#fecha-fin').val(),
                        "nombre": $('#nombre').val(),
                        "apellido_1": $('#apellido_1').val(),
                        "apellido_2": $('#apellido_2').val(),
                        "alias": $('#alias').val(),
                        servicios
                    },
                    beforeSend: function () {
                        console.log('bloqueo botones');
                    },
                    complete: function () {

                        calendar.refetchEvents()
                        // $('#calendar').fullCalendar('refetchEvents');
                        console.log('desbloqueo botones');
                    },
                    success: function (response) {

                        $('#newServiceModal').modal('hide');


                    },
                    error: function (jqXHR) {
                        console.log('boo!');
                    }
                });



            });

            $('#fecha-inicio').on('dp.change', function(event){

                var fechaInicio = $('#fecha-inicio').val();
                var sumaTiempos = 0
                var tabla = $(event.currentTarget.parentElement.parentElement.parentElement);
                var tiempos = tabla.find('input[name^="duracion_estiamada"]' ).serializeArray();
                var valorFechaInicio = moment(fechaInicio, 'YYYY-MM-DD HH:mm')

                for (let i = 0; i < tiempos.length; i++) {
                    sumaTiempos = sumaTiempos + Number(tiempos[i].value);
                }

                var valorFechaFin = valorFechaInicio.add(sumaTiempos, 'minutes').format('YYYY-MM-DD HH:mm');
                $('#fecha-fin').val(valorFechaFin).trigger('change');

            });

            $('#select_clientes').on('change', function(event) {

                $('#client-right-group').empty();

                var cliente =  $(event.currentTarget).find(':selected');


                $.ajax({
                    type:'get',
                    url:'/compromisoCliente?id=' + cliente[0].value
                    ,
                    beforeSend: function () {

                    },
                    complete: function () {

                    },
                    success: function (response) {

                        response = parseInt(response)

                        var face = '';


                        if (response !== 0) {
                            if (response >= 1 && response <= 60) {
                                var face = '<i class="far fa-frown cara-cliente" title="Acude a menos del 60% de las citas" style="color:darkred;"></i>';
                            } else if (response >= 61 && response <= 80) {
                                var face = '<i class="far fa-surprise cara-cliente" title="Acude entre el 61% y el 80% de las citas" style="color:orange;"></i>';
                            } else if (response >= 81 && response <= 99) {
                                var face = '<i class="far fa-smile cara-cliente" title="Acude entre el 99% y el 80% de las citas" style="color:green;"></i>';
                            } else {
                                var face = '<i class="far fa-grin-hearts cara-cliente" title="Acude entre el 99% y el 80% de las citas" style="color:palevioletred;"></i>';
                            }
                        } else {
                            var face = '<i class="far fa-meh-blank cara-cliente" title="Sin datos de historico" style="color:green;"></i>';
                        }


                        $('#client-right-group').append(face);

                    },
                    error: function (jqXHR) {

                    }
                });


                // alert('1');

            });

        }

    </script>

@stop
