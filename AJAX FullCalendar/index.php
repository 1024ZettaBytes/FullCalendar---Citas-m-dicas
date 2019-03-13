<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="au" content="">

    <title>Inicio</title>

    <link href="css/bm.css" rel="stylesheet">
    <link href='css/fullcalendar.css' rel='stylesheet'/>
    <link href='css/fullcalendar.min.css' rel='stylesheet'/>
    <link href='css/custom.css' rel='stylesheet'/>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src='js/moment.min.js'></script>
    <script src='js/fullcalendar/fullcalendar.min.js'></script>
    <script src='js/fullcalendar/fullcalendar.js'></script>
    <script src='js/fullcalendar/locale/es.js'></script>
    <script>
        $(document).ready(function () {
            var jsonData = [];

            function getter() {
                var get =
                    $.ajax({
                        async: false,
                        url: 'https://simuel.pythonanywhere.com/api_calendar/rest/get',
                        method: 'GET',
                        dataType: 'json',
                        success: function (get) {
                            for (var i = 0; i < get.length; i++) {
                                var fn = moment(get[i].Fecha + ' ' + get[i].Hora).add(get[i].Duracion, 'm').toDate();
                                var fnFormat = moment(fn).format('YYYY-MM-DD HH:mm:ss');
                                jsonData.push({
                                    title: get[i].Titulo,
                                    start: get[i].Fecha + ' ' + get[i].Hora,
                                    end: fnFormat,
                                    medico: get[i].Medico,
                                    sala: get[i].Sala,
                                    estudio: get[i].Estudio,
                                    gabinete: get[i].Gabinete,
                                    estatus: get[i].Estatus,
                                    ordenServicio: get[i].OrdenServicio,
                                    nota: get[i].Nota,
                                });
                            }
                        }

                    });
            }

            getter();
            $('#calendar').fullCalendar({
                header: {
                    language: 'es',
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,basicWeek,listDay,listWeek',

                },
                editable: false,
                eventLimit: true,
                selectable: true,
                selectHelper: true,
                events: jsonData,
                // Al dar Click en un día
                select: function (start) {
                    $('#ModalAdd #fecha').val(moment(start).format('L'));
                    $('#ModalAdd').modal('show');
                },

                // Al dar Click en una cita
                eventClick: function (calEvent) {
                    $('#ModalView #paciente').val(calEvent.title);
                    $('#ModalView #medico').val(calEvent.medico);
                    $('#ModalView #sala').val(calEvent.sala);
                    $('#ModalView #fecha').val(moment(calEvent.start).format('L'));
                    $('#ModalView #hora').val(moment(calEvent.start).format('HH:mm') + 'hrs - ' + moment(calEvent.end).format('HH:mm') + 'hrs');
                    $('#ModalView #estudio').val(calEvent.estudio);
                    $('#ModalView #gabinete').val(calEvent.gabinete);
                    $('#ModalView #estatus').val(calEvent.estatus);
                    $('#ModalView #ordenServicio').val(calEvent.ordenServicio);
                    $('#ModalView #nota').html(calEvent.nota);
                    $('#ModalView').modal('show');
                }
            });
        });
    </script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <!-- Full Calendar generado-->
            <div id="calendar" class="col-centered">
            </div>
            <!---->
        </div>

    </div>

    <!-- Modal para agregar cita nueva-->
    <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" method="POST" action="addEvent.php">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Agregar cita</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="paciente" class="col-sm-2 control-label">Paciente:</label>
                            <div class="col-sm-10">
                                <input type="text" name="paciente" class="form-control" id="paciente"
                                       placeholder="Nombre del paciente">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="medicos" class="col-sm-2 control-label">Médico:</label>
                            <div class="col-sm-10">
                                <select name="medicos" class="form-control" id="medico">
                                    <option value="">Seleccionar</option>
                                    <option> Médico 1</option>
                                    <option> Médico 2</option>
                                    <option> Médico 3</option>
                                    <option> Médico 4</option>
                                    <option> Médico 5</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="salas" class="col-sm-2 control-label">Sala:</label>
                            <div class="col-sm-10">
                                <select name="salas" class="form-control" id="sala">
                                    <option value="">Seleccionar</option>
                                    <option> Sala 1</option>
                                    <option> Sala 2</option>
                                    <option> Sala 3</option>
                                    <option> Sala 4</option>
                                    <option> Sala 5</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fecha" class="col-sm-2 control-label">Fecha:</label>
                            <div class="col-sm-10">
                                <input type="text" name="fecha" class="form-control" id="fecha" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hora" class="col-sm-2 control-label">Hora:</label>
                            <div class="col-sm-10">
                                <input type="text" name="hora" class="form-control" id="hora"
                                       placeholder="Hora en formato HH:MM 24 hrs. Ej: 14:00 ">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="duracion" class="col-sm-2 control-label">Duración:</label>
                            <div class="col-sm-10">
                                <input type="text" name="duracion" class="form-control" id="duracion"
                                       placeholder="Duración en minutos">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="estudio" class="col-sm-2 control-label">Estudio:</label>
                            <div class="col-sm-10">
                                <input type="text" name="estudio" class="form-control" id="estudio">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gabinete" class="col-sm-2 control-label">Gabinete:</label>
                            <div class="col-sm-10">
                                <input type="text" name="gabinete" class="form-control" id="gabinete">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="estatus" class="col-sm-2 control-label">Estatus:</label>
                            <div class="col-sm-10">
                                <input type="text" name="estatus" class="form-control" id="estatus">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ordenServicio" class="col-sm-2 control-label">Orden de servicio:</label>
                            <div class="col-sm-10">
                                <input type="text" name="ordenServicio" class="form-control" id="ordenServicio">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nota" class="col-sm-2 control-label">Nota:</label>
                            <div class="col-sm-10">
                                <textarea name="nota" cols="40" rows="5" class="form-control" id="nota"
                                          placeholder="Notas extra"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal para mostrar detalle de cita-->
    <div class="modal fade" id="ModalView" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Detalle de cita</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">Paciente:</label>
                            <div class="col-sm-10">

                                <input type="text" name="paciente" class="form-control" id="paciente" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="medico" class="col-sm-2 control-label">Médico:</label>
                            <div class="col-sm-10">

                                <input type="text" name="medico" class="form-control" id="medico" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sala" class="col-sm-2 control-label">Sala:</label>
                            <div class="col-sm-10">

                                <input type="text" name="sala" class="form-control" id="sala" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="start" class="col-sm-2 control-label">Fecha:</label>
                            <div class="col-sm-10">
                                <input type="text" name="fecha" class="form-control" id="fecha" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="end" class="col-sm-2 control-label">Hora:</label>
                            <div class="col-sm-10">
                                <input type="text" name="hora" class="form-control" id="hora" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="estudio" class="col-sm-2 control-label">Estudio:</label>
                            <div class="col-sm-10">
                                <input type="text" name="estudio" class="form-control" id="estudio" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gabinete" class="col-sm-2 control-label">Gabinete:</label>
                            <div class="col-sm-10">
                                <input type="text" name="gabinete" class="form-control" id="gabinete" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="estatus" class="col-sm-2 control-label">Estatus:</label>
                            <div class="col-sm-10">
                                <input type="text" name="estatus" class="form-control" id="estatus" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ordenServicio" class="col-sm-2 control-label">Orden de servicio:</label>
                            <div class="col-sm-10">
                                <input type="text" name="ordenServicio" class="form-control" id="ordenServicio"
                                       readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nota" class="col-sm-2 control-label">Nota:</label>
                            <div class="col-sm-10">
                                <textarea name="nota" cols="40" rows="5" class="form-control" id="nota"
                                          readonly></textarea>
                            </div>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
