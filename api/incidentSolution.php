<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Solution</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../app/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../app/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../app/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="../app/plugins/toastr/toastr.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="../app/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../app/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <!-- Loader -->
    <link rel="stylesheet" href="../app/dist/css/loader.css">
    <!-- Back To Top -->
    <link rel="stylesheet" href="../app/dist/css/backToTop.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <?php
        // Header
        require_once "shared/header.php";
        // Sidebar
        require_once "shared/sidebar.php";
        // DB Connection
        pdoConnect();
        ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Incident Solution</h1>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            <div class="card card-primary card-outline">

                                <!-- Preloader -->
                                <div class="card-header loader"></div>

                                <form id="solForm" hidden autocomplete="off">
                                
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3"></div>
                                            <div class="form-group col-6">
                                                <label>Active Incidents</label>
                                                <select id="Inc_Id" class="form-control select2bs4" style="width: 100%;" onChange="check(this)"></select>
                                            </div>
                                            <div class="col-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label></label>
                                                    <input type="radio" name="solution" id="solution_exists" class="form-control" onChange="chooseSolution(this)" checked>
                                                </div>
                                            </div>
                                            <div class="form-group col-6">
                                                <label>Solution</label>
                                                <select id="Sol_Id" class="form-control select2bs4" style="width: 100%;" onChange="check(this)"></select>
                                            </div>
                                            <div class="col-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label></label>
                                                    <input type="radio" name="solution" id="solution_not_exists" class="form-control" onChange="chooseSolution(this)">
                                                </div>
                                            </div>
                                            <div class="form-group col-6">
                                                <label>New Solution</label>
                                                <textarea id="New_Sol_Desc" class="form-control" placeholder="New Solution" onKeyUp="check(this)" disabled></textarea>
                                            </div>
                                            <div class="col-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3"></div>
                                            <div class="card col-6">
                                                <div class="card-header">
                                                    <label class="card-title">Solved By</label>
                                                    <div class="card-tools">
                                                        <button class="btn btn-success" title="new row" onClick="addRow(event)">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <table id="Table">
                                                        <tr>
                                                            <td class="col-3">
                                                                <div class="form-group">
                                                                    <button value="0" class="btn btn-danger removeRowBtn" title="delete row" onClick="removeRow(this, event)">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                            <td class="col-9">
                                                                <div class="form-group">
                                                                    <select id="Emp_Number_0" class="form-control select2bs4 selects" style="width: 100%;" onChange="onChooseEmployee(this)"></select>
                                                                </div>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3"></div>
                                            <div class="form-group col-6">
                                                <label>Solved Cost In L.L</label>
                                                <input type="number" id="Solved_Cost" min="0" step="1000" class="form-control" value="0" onChange="check(this)">
                                            </div>
                                            <div class="col-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3"></div>
                                            <div class="form-group col-6">
                                                <label>Solved Date</label>
                                                <input type="date" id="SD" class="form-control" onChange="check(this)">
                                            </div>
                                            <div class="col-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3"></div>
                                            <div class="form-group col-6">
                                                <label>Solved Time</label>
                                                <input type="time" id="ST" class="form-control" onChange="check(this)">
                                            </div>
                                            <div class="col-3"></div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-lg-4"></div>
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <button type="reset" class="btn btn-block btn-outline-danger btn-lg" onClick="resetBtn()">
                                                        <i class="fas fa-exclamation"></i> Cancel
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-block btn-outline-primary btn-lg" onClick="submitBtn()">
                                                        <i class="fas fa-save"></i> Save
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-lg-4"></div>
                                        </div>
                                    </div>

                                </form>

                            </div>
                        </div>
                        <div class="col-3"></div>
                    </div>
                </div>

                <!-- add modal -->
                <div class="modal fade" id="addmodal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">New Incident</h4>
                            </div>
                            <!-- Preloader -->
                            <div class="add-modal-loader"></div>

                            <div class="modal-body" hidden>
                                <div id="Add_Texts" align="center">
                                    <div id="add_error_text" class="col-lg-12 alert alert-danger" hidden></div>
                                    <div id="add_success_text" class="col-lg-12 alert alert-success" hidden></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /add modal -->

            </section>
        </div>

        <!-- Top To Bottom-->
        <a id="back-to-top" href="#" class="btn btn-light btn-lg back-to-top" role="button">
            <i class="fas fa-chevron-up"></i>
        </a>
        <!-- End Top To Bottom-->
    </div>

    <!-- icon -->
    <script src="../app/dist/js/icon.js"></script>
    <!-- jQuery -->
    <script src="../app/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="../app/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../app/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../app/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../app/dist/js/adminlte.min.js"></script>
    <!-- Toastr -->
    <script src="../app/plugins/toastr/toastr.min.js"></script>

    <!-- Select2 -->
    <script src="../app/plugins/select2/js/select2.full.min.js"></script>

    <!-- Back To Top -->
    <script src="../app/dist/js/backToTop.js"></script>
    <script>

        var postDataLoad;
        var postDataProgress;
        var row;
        const table = document.getElementById("Table");
        var countRows = table.rows.length;
        var indexRows = countRows - 1;
        var Emp_Numbers = [];

        // completed
        function addRow(e) {
            e.preventDefault();
            indexRows = countRows - 1;
            
            var id = "Emp_Number_" + indexRows;
            var value = document.getElementById(id).value;

            var countEmp = postDataLoad.countEmployee;

            if (value == '') {

                toastr.error('Choose An Employee');

            } else if (indexRows == countEmp - 1) {

                toastr.error('No More Employees');

            } else {

                countRows++;
                indexRows++;
                row = ` <tr>
                            <td class="col-3">
                                <div class="form-group">
                                    <button value="${indexRows}" class="btn btn-danger removeRowBtn" title="delete row" onClick="removeRow(this, event)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>

                            <td class="col-9">
                                <div class="form-group">
                                    <select id="Emp_Number_${indexRows}" class="form-control select2bs4 selects" style="width: 100%;" onChange="onChooseEmployee(this)"></select>
                                </div>
                            </td>
                            <td></td>
                        </tr>`;

                table.insertRow().innerHTML = row;

                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                });

                $("#Emp_Number_" + indexRows).html(postDataLoad.employee);

            }
        }

        // completed
        function removeRow(element, e) {

            e.preventDefault();

            var elementValue = element.value;
            var value = $("#Emp_Number_" + elementValue).val();
            var index = Emp_Numbers.indexOf(value);
            Emp_Numbers.splice(index, 1);

            if (indexRows == 0) {

                $("#Emp_Number_" + indexRows).html(postDataLoad.employee);
                $("#Emp_Number_" + indexRows).removeAttr("disabled");

            } else {

                var row = table.deleteRow(element.value);

                var employeesElemets = document.querySelectorAll(".removeRowBtn");
                var selects = document.querySelectorAll(".selects");
                var i = 0;
                var j = 0;
                employeesElemets.forEach(row => {
                    row.value = i;
                    i++;
                });
                selects.forEach(row => {
                    row.id = "Emp_Number_" + j;
                    j++;
                });
                countRows--;
                indexRows--;

            }

        }

        // completed
        function resetBtn() {

            $('#Inc_Id').html(postDataLoad.incident);
            $('#Sol_Id').html(postDataLoad.solution);
            $('#Sol_Id').removeAttr("disabled");
            $('#New_Sol_Desc').attr("disabled", true);
            $('#Emp_Number_0').html(postDataLoad.employee);
            $('#Emp_Number_0').removeAttr("disabled");

            $('#SD').removeAttr("min");
            $('#SD').removeAttr("max");

            while (indexRows >= 1) {
                table.deleteRow(indexRows);
                indexRows--;
            }
            countRows = 1;
            indexRows = 0;
            Emp_Numbers = [];

        }

        function submitBtn() {
            var Inc_Id = document.getElementById("Inc_Id").value;
            var Sol_Id_Check = $('#solution_exists:checked').val();
            var Sol_Id = document.getElementById("Sol_Id").value;
            var New_Sol_Desc_Check = $('#solution_not_exists:checked').val();
            var New_Sol_Desc = document.getElementById("New_Sol_Desc").value;
            var Emp_Number_0 = document.getElementById("Emp_Number_0").value;
            var Solved_Cost = document.getElementById("Solved_Cost").value;
            var SC = Math.abs(Solved_Cost);
            var SD = document.getElementById("SD").value;
            var ST = document.getElementById("ST").value;

            var Status_Inc_Id = false;
            var Status_Sol_Id = false;
            var Status_New_Sol_Desc = false;
            var Status_SC = false;
            var Status_SD = false;
            var Status_ST = false;

            if (Inc_Id != '') {
                Status_Inc_Id = false;
            } else {
                Status_Inc_Id = true;
                toastr.error('Choose an incident');
            }

            if (Solved_Cost != '') {
                Status_SC = false;
            } else {
                Status_SC = true;
                toastr.error('Enter a solved cost');
            }

            if (SD != '') {
                Status_SD = false;
            } else {
                Status_SD = true;
                toastr.error('Enter an solved date');
            }

            if (ST != '') {
                Status_ST = false;
            } else {
                Status_ST = true;
                toastr.error('Enter an solved time');
            }

            if (Sol_Id_Check == "on") {

                if (Sol_Id != '') {
                    Status_Sol_Id = false;
                } else {
                    Status_Sol_Id = true;
                    toastr.error('Choose a solution');
                }

            } else if (New_Sol_Desc_Check == "on") {

                if (New_Sol_Desc.trim() != '') {
                    Status_New_Sol_Desc = false;
                } else {
                    Status_New_Sol_Desc = true;
                    toastr.error('Enter an new solution');
                }

            }

            if (Status_Inc_Id == true || Status_Sol_Id == true || Status_New_Sol_Desc == true || Status_SC == true || Status_SD == true || Status_ST == true) {
                return;
            } else {
                $('#addmodal').modal('show');
                $('.modal-body').attr("hidden", true);

                if (Emp_Number_0 == '') {
                    Emp_Numbers.push(0);
                }
                
                $.ajax({
                    type: 'POST',
                    url: 'incidentSolutionProcessor.php',
                    data: {
                        method: 'add',
                        Emp_Numbers: Emp_Numbers,
                        Inc_Id: Inc_Id,
                        Sol_Id_Check: Sol_Id_Check,
                        Sol_Id: Sol_Id,
                        New_Sol_Desc_Check: New_Sol_Desc_Check,
                        New_Sol_Desc: New_Sol_Desc,
                        Solved_Cost: SC,
                        SD: SD,
                        ST: ST
                    },
                    success: function(html) {
                        $('.add-modal-loader').attr("hidden", true);
                        $('.modal-body').removeAttr("hidden");
                        $('#Add_Inputs').attr("hidden", true);
                        if (html.success == true) {
                            $('#add_success_text').attr("hidden", false);
                            var text = document.getElementById("add_success_text");
                            text.textContent = html.msg;
                        } else {
                            $('#add_error_text').attr("hidden", false);
                            var text = document.getElementById("add_error_text");
                            text.textContent = html.msg;
                        }
                        window.setTimeout("location.href='incidentSolution.php'", 1000);
                    },
                    dataType: "json"
                });
            }
        }

        // completed
        function chooseSolution(element) {

            var id = element.id;

            if (id == "solution_exists") {

                $("#Sol_Id").removeAttr("disabled");
                $("#New_Sol_Desc").attr("disabled", true);
                $('#New_Sol_Desc').val("");

            } else if (id == "solution_not_exists"){

                $("#Sol_Id").attr("disabled", true);
                if (postDataProgress) {
                    $('#Sol_Id').html(postDataProgress.solution);
                } else {
                    $('#Sol_Id').html(postDataLoad.solution);
                }
                
                $("#New_Sol_Desc").removeAttr("disabled");

            }
        }

        // completed
        function onChooseEmployee(element) {

            var id = element.id;
            var value = element.value;

            var empIsExists = false;

            Emp_Numbers.forEach(row => {
                
                if (row == value) {
                    toastr.error('Employee Already Choosen!');
                    empIsExists = true;
                    $("#Emp_Number_" + indexRows).html(postDataLoad.employee);
                }

            });

            if (empIsExists == false) {
                Emp_Numbers.push(value);
                $("#Emp_Number_" + indexRows).attr("disabled", true);
            }

        }

        function check(element) {

            var id = element.id;
            var value = element.value;

            if (id == 'Inc_Id') {

                onChooseIncident();

            }

        }

        // completed
        function onChooseIncident() {
            var Inc_Id = document.getElementById('Inc_Id').value;
            $('#Sol_Id').prop('disabled');
            $.ajax({
                type: 'POST',
                url: 'incidentSolutionProcessor.php',
                data: {
                    method: 'onChooseIncident',
                    Inc_Id: Inc_Id
                },
                success: function(html) {
                    postDataProgress = html;
                    $('#Sol_Id').html(html.solution);
                    $('#Sol_Id').removeProp('disabled');
                    $('#SD').val("");

                    // min date input
                    $("#SD").attr("min", html.causedDate);

                    // max date input
                    var maxDate = new Date();
                    var maxDay = maxDate.getDate();
                    var maxMonth = maxDate.getMonth() + 1; //January is 0!
                    var maxYear = maxDate.getFullYear();
                    if (maxDay < 10) {
                        maxDay = '0' + maxDay;
                    }
                    if (maxMonth < 10) {
                        maxMonth = '0' + maxMonth;
                    }   
                    maxDate = maxYear + '-' + maxMonth + '-' + maxDay;
                    $("#SD").attr("max", maxDate);
                    $("#SD").val(maxDate);

                },
                dataType: "json"
            });
        }

        // completed
        $(function() {
            
            $('.solution').addClass("bg-success");

            $.ajax({
                type: 'POST',
                url: 'incidentSolutionProcessor.php',
                data: {
                    method: 'load',
                },
                success: function(html) {
                    postDataLoad = html;
                    $('#Inc_Id').html(html.incident);
                    $('#Sol_Id').html(html.solution);
                    $('#Emp_Number_0').html(html.employee);
                    $('.loader').attr("hidden", true);
                    $('#solForm').removeAttr("hidden");
                },
                dataType: "json"
            });

            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
        });

    </script>
</body>

</html>