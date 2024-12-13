<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Calendario</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="fontawesome/css/solid.css" rel="stylesheet">
    <link href="fontawesome/css/brands.css" rel="stylesheet">
    <link href="css/datatables.min.css" rel="stylesheet">
    <link href="css/sweetalert2.min.css" rel="stylesheet">
    <link href="css/calendar.css" rel="stylesheet">
</head>
  <body >
    
<main>
  <div class="container py-4">
    <header class="pb-3 mb-4 border-bottom">
        <div class="row align-items-md-stretch">
            <div class="col-md-6">
                <a href="/" class="d-flex align-items-center text-dark text-decoration-none">
                    <i class="fa-solid fa-calendar-days mx-2" style="font-size: 22px;"></i>
                    <span class="fs-4 ms-4">Calendario</span>
                </a>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <button class="btn btn-primary btn-sm mx-2" type="button" id="btn_printing">
                    <i class="fa-solid fa-print me-2"></i>Print Doc
                </button>
                <button class="btn btn-warning btn-sm mx-2" type="button" id="btn_status">
                    <i class="fa-solid fa-info me-2"></i>Status
                </button>
                <button class="btn btn-danger btn-sm mx-2" type="button" id="config">
                    <i class="fa-solid fa-gears me-2"></i>Config
                </button>
            </div>
        </div>
    </header>

    <div class="p-2 mb-4 bg-light rounded-3">
      <div class="container-fluid">
        <div id="my_calendar"></div>
      </div>
    </div>

    <footer class="pt-3 mt-4 text-muted border-top">
      &copy; 2023
    </footer>
  </div>

    <!-- Modal Add Events -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="#" method="post" id="formEvent">
                <div class="modal-header py-1">
                    <h5 class="modal-title" id="staticBackdropLabel">Novo Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="formGroupTitle" class="form-label">Titulo</label>
                        <input type="text" name="title" class="form-control form-control-sm" id="formGroupTitle" placeholder="titulo de envento">
                    </div>
                    <div class="mb-2">
                        <label for="formGroupDate" class="form-label">Data</label>
                        <input type="date" readonly name="date" class="form-control form-control-sm" id="formGroupDate" placeholder="data de envento">
                    </div>
                    <div class="mb-2">
                        <label for="formGroupColor" class="form-label">Cor de Evento</label>
                        <input type="color" name="color" class="form-control form-control-sm" id="formGroupColor" placeholder="data de envento">
                    </div>
                </div>
                <div class="modal-footer py-1">
                    <button type="reset" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" onclick="saveEvent()" class="btn btn-sm btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit/Delete Events -->
    <div class="modal fade" id="editEvents" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="#" method="post" id="formEditEvent">
                <div class="modal-header py-1">
                    <h5 class="modal-title" id="staticBackdropLabel">Editar/Eliminar Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="formEditId"  /> 
                    <div class="mb-2">
                        <label for="formGroupTitle" class="form-label">Titulo</label>
                        <input type="text" name="title" class="form-control form-control-sm" id="formEditTitle" placeholder="titulo de envento">
                    </div>
                    <div class="mb-2">
                        <label for="formGroupDate" class="form-label">Data</label>
                        <input type="date" readonly name="date" class="form-control form-control-sm" id="formEditDate" placeholder="data de envento">
                    </div>
                    <div class="mb-2">
                        <label for="formGroupColor" class="form-label">Cor de Evento</label>
                        <input type="color" name="color" class="form-control form-control-sm" id="formEditColor" placeholder="data de envento">
                    </div>
                </div>
                <div class="modal-footer py-1">
                    <button type="button" onclick="deleteEvent()" class="btn btn-sm btn-danger mx-2">Eliminar</button>
                    <button type="button" onclick="editEvent()" class="btn btn-sm btn-primary mx-2">Editar</button>
                </div>
            </form>
        </div>
    </div>

</main>

    <script src="js/bootstrap.js"></script>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/datatables.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src='fullcalendar/dist/index.global.js'></script>
    <script src='fullcalendar/packages/core/locales/pt.global.js'></script>
    <script src='fullcalendar/packages/bootstrap5/index.global.js'></script>

    <script>
        let array_agenda = [];
        var calendar;
        load_agenda();
        /* fullcalendar v6 */
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('my_calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                locale: 'pt',
                editable: true,
                events: 'data/events.txt',
                dayMaxEventRows: true, // for all non-TimeGrid views
                views: {
                    timeGrid: {
                    dayMaxEventRows: 3 // adjust to 6 only for timeGridWeek/timeGridDay
                    }
                },
                eventClick: function(info) {
                    let array = array_agenda.find(x => x.id == info.event.id);
                    /* console.log('DataSelected',array); */
                    $("#formEditEvent input[name='id']").val(array.id);
                    $("#formEditEvent input[name='title']").val(array.title);
                    $("#formEditEvent input[name='date']").val(array.date);
                    $("#formEditEvent input[name='color']").val(array.color);

                    // change the border color just for fun
                    info.el.style.borderColor = 'red';
                    $("#editEvents").modal('toggle');

                },
                dateClick: function(info) {
                    $("#staticBackdrop").modal("toggle");
                    $("#formGroupDate").val(info.dateStr);
                }
            });
            calendar.render();
        });

        let toastMixin = Swal.mixin({
            toast: true,
            icon: 'success',
            title: 'General Title',
            animation: false,
            position: 'top-right',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        $("#btn_printing").click(function(){
            try{
                window.open('C:\\app\\silent-printing.exe "printing" "Casa do Cidadão" "B-104" "Criança de colo" "12/12/2023 12:00:01" 4 "\"ticket dv845afe\"" 40');
            }
            catch(error) {
                console.log("Something did not work...\n\n"+error.description);
            }
            /* Swal.fire({
                title: "Printed!",
                text: "Impresso com secesso.",
                icon: "success", 
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Ok"
                }).then((result) => {
                if (result.isConfirmed) {
                    location.reload(true);
                }
            }); */
            $.post("lib/event.php?action=print",function(res,status,xhd) {
                console.log(res);
                
               
            },"json");
        });

        function deleteEvent() { 
            console.log($("#formEditEvent").serialize());
            $.post("lib/event.php?action=delete",$("#formEditEvent").serialize(),function(res,status,xhd) {
                $("#editEvents").modal("toggle");
                $("#editEvents").modal("toggle");
                    Swal.fire({
                    title: "Eliminado!",
                    text: "Evento eliminado com secesso.",
                    icon: "success",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload(true);
                    }
                });
            },"json");
        }

        function editEvent() { 
            console.log($("#formEditEvent").serialize());
            $.post("lib/event.php?action=edit",$("#formEditEvent").serialize(),function(res,status,xhd) {
                $("#editEvents").modal("toggle");
                    Swal.fire({
                    title: "Alterado!",
                    text: "Evento alterado com secesso.",
                    icon: "success",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload(true);
                    }
                });
               
            },"json");
        }

        function saveEvent() {
            console.log($("#formEvent").serialize());
            $.post("lib/event.php?action=post",$("#formEvent").serialize(),function(res,status,xhd) {
                $("#staticBackdrop").modal("toggle");
                Swal.fire({
                    title: "Guardado!",
                    text: "Evento guardado com secesso.",
                    icon: "success",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload(true);
                    }
                });
            },"json");
        }
        
        function load_agenda() {
            $.getJSON("data/events.txt", function(data){
                data?.forEach((element,index) => {
                    array_agenda.push(element);
                });
                console.log(array_agenda);
            }).fail(function(){
                console.log("An error has occurred.");
            });
        }

    </script>
  </body>
</html>


<!-- 
src="https://www.youtube.com/embed/D8zsJTOcZ5I?autoplay=1&mute=1&loop=1&list=PLCdaz-sIgHbiKrlDPISdhjE8lxw_BMB91" 
-->
