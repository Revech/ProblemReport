<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident</title>

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
                            <h1 class="m-0">Incident</h1>
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

                                <form id="incForm" hidden autocomplete="off">
                                
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3"></div>
                                            <div class="form-group col-6">
                                                <label>Department</label>
                                                <select id="Dep_Id" class="form-control select2bs4" style="width: 100%;" onChange="check(this)"></select>
                                            </div>
                                            <div class="col-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label></label>
                                                    <input type="radio" name="problem" id="problem_exists" class="form-control" onChange="chooseProblem(this)" checked>
                                                </div>
                                            </div>
                                            <div class="form-group col-6">
                                                <label>Problem</label>
                                                <select id="Prob_Id" class="form-control select2bs4" style="width: 100%;" onChange="check(this)"></select>
                                            </div>
                                            <div class="col-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label></label>
                                                    <input type="radio" name="problem" id="problem_not_exists" class="form-control" onChange="chooseProblem(this)">
                                                </div>
                                            </div>
                                            <div class="form-group col-6">
                                                <label>New Problem</label>
                                                <textarea id="New_Prob_Desc" class="form-control" placeholder="New Problem" onKeyUp="check(this)" disabled></textarea>
                                            </div>
                                            <div class="col-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3"></div>
                                            <div class="form-group col-6">
                                                <label>Evaluation Loss In L.L</label>
                                                <input type="number" id="Evaluation_Loss" min="0" step="1000" class="form-control" value="0" onChange="check(this)">
                                            </div>
                                            <div class="col-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3"></div>
                                            <div class="form-group col-6">
                                                <label>Critical Level</label>
                                                <select id="Cl_Id" class="form-control select2bs4" style="width: 100%;" onChange="check(this)"></select>
                                            </div>
                                            <div class="col-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3"></div>
                                            <div class="card col-6">
                                                <div class="card-header">
                                                    <label class="card-title">Caused By</label>
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
                                                <label>Caused Date</label>
                                                <input type="date" id="CD" class="form-control" onChange="check(this)">
                                            </div>
                                            <div class="col-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3"></div>
                                            <div class="form-group col-6">
                                                <label>Caused Time</label>
                                                <input type="time" id="CT" class="form-control" onChange="check(this)">
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

            $('#Dep_Id').html(postDataLoad.department);
            $('#Prob_Id').html(postDataLoad.problem);
            $('#Prob_Id').removeAttr("disabled");
            $('#New_Prob_Desc').attr("disabled", true);
            $('#Cl_Id').html(postDataLoad.critical);
            $('#Emp_Number_0').html(postDataLoad.employee);
            $('#Emp_Number_0').removeAttr("disabled");

            while (indexRows >= 1) {
                table.deleteRow(indexRows);
                indexRows--;
            }
            countRows = 1;
            indexRows = 0;
            Emp_Numbers = [];

        }

        function submitBtn() {
            var Dep_Id = document.getElementById("Dep_Id").value;
            var Prob_Id_Check = $('#problem_exists:checked').val();
            var Prob_Id = document.getElementById("Prob_Id").value;
            var New_Prob_Desc_Check = $('#problem_not_exists:checked').val();
            var New_Prob_Desc = document.getElementById("New_Prob_Desc").value;
            var Evaluation_Loss = document.getElementById("Evaluation_Loss").value;
            var EL = Math.abs(Evaluation_Loss);
            var Cl_Id = document.getElementById("Cl_Id").value;
            var Emp_Number_0 = document.getElementById("Emp_Number_0").value;
            var CD = document.getElementById("CD").value;
            var CT = document.getElementById("CT").value;

            var Status_Dep_Id = false;
            var Status_Prob_Id = false;
            var Status_New_Prob_Desc = false;
            var Status_Evaluation_Loss = false;
            var Status_Cl_Id = false;
            var Status_CD = false;
            var Status_CT = false;

            if (Dep_Id != '') {
                Status_Dep_Id = false;
            } else {
                Status_Dep_Id = true;
                toastr.error('Choose a department');
            }

            if (Evaluation_Loss != '') {
                Status_Evaluation_Loss = false;
            } else {
                Status_Evaluation_Loss = true;
                toastr.error('Enter an evaluation loss');
            }

            if (Cl_Id != '') {
                Status_Cl_Id = false;
            } else {
                Status_Cl_Id = true;
                toastr.error('Choose a critical level');
            }

            if (CD != '') {
                Status_CD = false;
            } else {
                Status_CD = true;
                toastr.error('Enter an caused date');
            }

            if (CT != '') {
                Status_CT = false;
            } else {
                Status_CT = true;
                toastr.error('Enter an caused time');
            }

            if (Prob_Id_Check == "on") {

                if (Prob_Id != '') {
                    Status_Prob_Id = false;
                } else {
                    Status_Prob_Id = true;
                    toastr.error('Choose a problem');
                }

            } else if (New_Prob_Desc_Check == "on") {

                if (New_Prob_Desc.trim() != '') {
                    Status_New_Prob_Desc = false;
                } else {
                    Status_New_Prob_Desc = true;
                    toastr.error('Enter an new problem');
                }

            }

            if (Status_Dep_Id == true || Status_Prob_Id == true || Status_New_Prob_Desc == true || Status_Evaluation_Loss == true || Status_Cl_Id == true || Status_CD == true || Status_CT == true) {
                return;
            } else {
                $('#addmodal').modal('show');
                $('.modal-body').attr("hidden", true);
                
                if (Emp_Number_0 == '') {
                    Emp_Numbers.push(0);
                }

                $.ajax({
                    type: 'POST',
                    url: 'incidentProcessor.php',
                    data: {
                        method: 'add',
                        Emp_Numbers: Emp_Numbers,
                        Dep_Id: Dep_Id,
                        Prob_Id_Check: Prob_Id_Check,
                        Prob_Id: Prob_Id,
                        New_Prob_Desc_Check: New_Prob_Desc_Check,
                        New_Prob_Desc: New_Prob_Desc,
                        Evaluation_Loss: EL,
                        Cl_Id: Cl_Id,
                        CD: CD,
                        CT: CT
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
                        window.setTimeout("location.href='incident.php'", 1000);
                    },
                    dataType: "json"
                });
            }
        }

        // completed
        function chooseProblem(element) {

            var id = element.id;

            if (id == "problem_exists") {

                $("#Prob_Id").removeAttr("disabled");
                $("#New_Prob_Desc").attr("disabled", true);
                $('#New_Prob_Desc').val("");

            } else if (id == "problem_not_exists"){

                $("#Prob_Id").attr("disabled", true);
                if (postDataProgress) {
                    $('#Prob_Id').html(postDataProgress.problem);
                } else {
                    $('#Prob_Id').html(postDataLoad.problem);
                }
                
                $("#New_Prob_Desc").removeAttr("disabled");

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

            if (id == 'Dep_Id') {

                onChooseDepartment();

            }

        }

        // completed
        function onChooseDepartment() {
            var Dep_Id = document.getElementById('Dep_Id').value;
            $('#Prob_Id').prop('disabled');
            $.ajax({
                type: 'POST',
                url: 'incidentProcessor.php',
                data: {
                    method: 'onChooseDepartment',
                    Dep_Id: Dep_Id
                },
                success: function(html) {
                    postDataProgress = html;
                    $('#Prob_Id').html(html.problem);
                    $('#Prob_Id').removeProp('disabled');
                },
                dataType: "json"
            });
        }

        // completed
        $(function() {
            
            $('.incident').addClass("menu-open");
            $('.incidenttag').addClass("active");
            $('.incidenttag').addClass("bg-danger");
            $('.newincident').addClass("active");

            $.ajax({
                type: 'POST',
                url: 'incidentProcessor.php',
                data: {
                    method: 'load',
                },
                success: function(html) {
                    postDataLoad = html;
                    $('#Dep_Id').html(html.department);
                    $('#Prob_Id').html(html.problem);
                    $('#Cl_Id').html(html.critical);
                    $('#Emp_Number_0').html(html.employee);
                    $('.loader').attr("hidden", true);
                    $('#incForm').removeAttr("hidden");
                },
                dataType: "json"
            });

            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            // max date input
            var maxDate = new Date();
            var maxDay = maxDate.getDate();
            var maxMonth = maxDate.getMonth() + 1; // January is 0!
            var maxYear = maxDate.getFullYear();
            if (maxDay < 10) {
                maxDay = '0' + maxDay;
            }
            if (maxMonth < 10) {
                maxMonth = '0' + maxMonth;
            }   
            maxDate = maxYear + '-' + maxMonth + '-' + maxDay;
            $("#CD").attr("max", maxDate);
            $("#CD").val(maxDate);
        });

    </script>
</body>

</html>