<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incidents List</title>

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

    <!-- DataTables -->
    <link rel="stylesheet" href="../app/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../app/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../app/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

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
                            <h1 class="m-0">Incidents List</h1>
                            <button type="button" onclick="downloadPDF()">Download PDF</button>
                            <script>
                                function downloadPDF() {
                                   window.location.href = 'testpdf.php';
                                     }
                            </script>
                            <button type="button" onclick="downloadExcel()">Download Excel</button>
                            <script>
                                function downloadExcel() {
                                   window.location.href = 'testexcel.php';
                                     }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">

                                <!-- Preloader -->
                                <div class="card-header loader"></div>

                                <div class="card-body" hidden>
                                    <table id="dataTable" class="table table-bordered table-striped">
                                        <thead align="center">
                                            <tr>
                                                <th>#</th>
                                                <th>Created By</th>
                                                <th>Department</th>
                                                <th>Problem</th>
                                                <th>Solution</th>
                                                <th>Critical Level</th>
                                                <th>Evaluation Loss</th>
                                                <th>Caused By</th>
                                                <th>Caused Date - Time</th>
                                                <th>Updated By</th>
                                                <th>Solved Cost</th>
                                                <th>Solved By</th>
                                                <th>Solved Date - Time</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="incidentsList" align="center"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- edit modal -->
                <div class="modal fade" id="editmodal">
                    <div class="modal-dialog">
                        <div class="modal-content callout callout-info">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit An Incident</h4>
                                <button id="edit_modal_close_btn" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Preloader -->
                            <div class="edit-modal-loader"></div>

                            <form id="editForm" hidden autocomplete="off">
                                <div class="modal-body">
                                    <div id="Edit_Inputs">
                                        <div class="row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>ID</label>
                                                    <input id="Edit_Inc_Id" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Department</label>
                                                    <select id="Edit_Dep_Id" class="form-control select2bs4" style="width: 100%;" onChange="check(this)"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Problem</label>
                                                    <select id="Edit_Prob_Id" class="form-control select2bs4" style="width: 100%;" onChange="check(this)"></select>
                                                    <span id="Edit_Prob_Id_error" name="error_text" style="color:red;font-size:small"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Critical Level</label>
                                                    <select id="Edit_Cl_Id" class="form-control select2bs4" style="width: 100%;" onChange="check(this)"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Evaluation Loss In L.L</label>
                                                    <input type="number" id="Edit_Evaluation_Loss" min="0" step="1000" class="form-control" value="0" onChange="check(this)">
                                                    <span id="Edit_Evaluation_Loss_error" name="error_text" style="color:red;font-size:small"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3"></div>
                                        </div>
                                        <!-- <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
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
                                                                <td class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <button value="0" class="btn btn-danger removeRowBtn" title="delete row" onClick="removeRow(this, event)">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                                <td class="col-lg-9">
                                                                    <div class="form-group">
                                                                        <select id="Emp_Number_0" class="form-control select2bs4 selects" style="width: 100%;" onChange="onChooseEmployee(this)"></select>
                                                                    </div>
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                    <div id="Edit_Texts" align="center" hidden>
                                        <div id="edit_error_text" class="col-lg-12 alert alert-danger" hidden></div>
                                        <div id="edit_success_text" class="col-lg-12 alert alert-success" hidden></div>
                                    </div>
                                </div>
                                <div id="Edit_Btns" class="modal-footer justify-content-between">
                                    <button class="btn btn-warning" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-primary" onClick="editBtn()">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /edit model -->

                <!-- cancel modal -->
                <div class="modal fade" id="cancelmodal">
                    <div class="modal-dialog">
                        <div class="modal-content callout callout-danger">
                            <div class="modal-header">
                                <h4 class="modal-title">Cancel An Incident</h4>
                                <button id="cancel_modal_close_btn" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Preloader -->
                            <div class="delete-modal-loader"></div>
                            <form id="cancelForm" hidden>
                                <div class="modal-body">
                                    <input id="Cancel_Inc_Id" type="hidden">
                                    <div class="row" id="canceltext"></div>
                                    <div id="Cancel_Texts" align="center" hidden>
                                        <div id="cancel_error_text" class="col-lg-12 alert alert-danger" hidden></div>
                                        <div id="cancel_success_text" class="col-lg-12 alert alert-success" hidden></div>
                                    </div>
                                </div>
                                <div id="Cancel_Btns" class="modal-footer justify-content-between">
                                    <button class="btn btn-success" data-dismiss="modal">No</button>
                                    <button type="button" class="btn btn-danger" onClick="cancelBtn()">Yes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /cancel model -->

                <!-- active modal -->
                <div class="modal fade" id="activemodal">
                    <div class="modal-dialog">
                        <div class="modal-content callout callout-info">
                            <div class="modal-header">
                                <h4 class="modal-title">Active An Incident</h4>
                                <button id="active_modal_close_btn" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Preloader -->
                            <div class="delete-modal-loader"></div>
                            <form id="activeForm" hidden>
                                <div class="modal-body">
                                    <input id="Active_Inc_Id" type="hidden">
                                    <div class="row" id="activetext"></div>
                                    <div id="Active_Texts" align="center" hidden>
                                        <div id="active_error_text" class="col-lg-12 alert alert-danger" hidden></div>
                                        <div id="active_success_text" class="col-lg-12 alert alert-success" hidden></div>
                                    </div>
                                </div>
                                <div id="Active_Btns" class="modal-footer justify-content-between">
                                    <button class="btn btn-success" data-dismiss="modal">No</button>
                                    <button type="button" class="btn btn-danger" onClick="activeBtn()">Yes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /active model -->

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

    <!-- DataTables  & Plugins -->
    <script src="../app/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../app/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../app/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../app/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../app/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../app/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../app/plugins/jszip/jszip.min.js"></script>
    <script src="../app/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../app/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../app/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../app/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../app/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

    <!-- Select2 -->
    <script src="../app/plugins/select2/js/select2.full.min.js"></script>

    <!-- Back To Top -->
    <script src="../app/dist/js/backToTop.js"></script>

    <script>

        // var postDataLoad;
        // var postDataProgress;
        // var row;
        // const table = document.getElementById("Table");
        // var countRows = table.rows.length;
        // var indexRows = countRows - 1;
        // var Emp_Numbers = [];

        // // completed
        // function addRow(e) {
        //     e.preventDefault();
        //     indexRows = countRows - 1;

        //     var id = "Emp_Number_" + indexRows;
        //     var value = document.getElementById(id).value;

        //     var countEmp = postDataLoad.countEmployee;

        //     if (value == '') {

        //         toastr.error('Choose An Employee');

        //     } else if (indexRows == countEmp - 1) {

        //         toastr.error('No More Employees');

        //     } else {

        //         countRows++;
        //         indexRows++;
        //         row = ` <tr>
        //                     <td class="col-3">
        //                         <div class="form-group">
        //                             <button value="${indexRows}" class="btn btn-danger removeRowBtn" title="delete row" onClick="removeRow(this, event)">
        //                                 <i class="fas fa-trash-alt"></i>
        //                             </button>
        //                         </div>
        //                     </td>

        //                     <td class="col-9">
        //                         <div class="form-group">
        //                             <select id="Emp_Number_${indexRows}" class="form-control select2bs4 selects" style="width: 100%;" onChange="onChooseEmployee(this)"></select>
        //                         </div>
        //                     </td>
        //                     <td></td>
        //                 </tr>`;

        //         table.insertRow().innerHTML = row;

        //         $('.select2bs4').select2({
        //             theme: 'bootstrap4'
        //         });

        //         $("#Emp_Number_" + indexRows).html(postDataLoad.employee);

        //     }
        // }

        // // completed
        // function removeRow(element, e) {

        //     e.preventDefault();

        //     var elementValue = element.value;
        //     var value = $("#Emp_Number_" + elementValue).val();
        //     var index = Emp_Numbers.indexOf(value);
        //     Emp_Numbers.splice(index, 1);

        //     if (indexRows == 0) {

        //         $("#Emp_Number_" + indexRows).html(postDataLoad.employee);
        //         $("#Emp_Number_" + indexRows).removeAttr("disabled");

        //     } else {

        //         var row = table.deleteRow(element.value);

        //         var employeesElemets = document.querySelectorAll(".removeRowBtn");
        //         var selects = document.querySelectorAll(".selects");
        //         var i = 0;
        //         var j = 0;
        //         employeesElemets.forEach(row => {
        //             row.value = i;
        //             i++;
        //         });
        //         selects.forEach(row => {
        //             row.id = "Emp_Number_" + j;
        //             j++;
        //         });
        //         countRows--;
        //         indexRows--;

        //     }

        // }

        // completed
        function resetAddModal() {
            $('#Add_Prob_Id').attr("disabled", true);
            $.ajax({
                type: 'POST',
                url: 'solutionProcessor.php',
                data: {
                    method: 'addModal',
                },
                success: function(html) {
                    $('#Add_Prob_Id').html(html.problem);
                    $('#Add_Prob_Id').removeAttr("disabled");
                },
                dataType: "json"
            });
        }

        // completed
        function editRow(row) {
            $('#editmodal').modal('show');
            $('.edit-modal-loader').removeAttr("hidden");
            $('#editForm').attr("hidden", true);
            $('#editForm').trigger("reset");

            var Inc_Id = row.value;

            $.ajax({
                type: 'POST',
                url: 'incidentListProcessor.php',
                data: {
                    method : 'editModal',
                    Inc_Id: Inc_Id
                },
                success: function(html) {
                    // postDataLoad = html;
                    $('#Edit_Inc_Id').val(Inc_Id);
                    $('#Edit_Dep_Id').html(html.department);
                    $('#Edit_Prob_Id').html(html.problem);
                    $('#Edit_Cl_Id').html(html.critical);
                    $('#Edit_Evaluation_Loss').val(html.eval);
                    // $('#Emp_Number_0').html(html.employee);

                    $('.edit-modal-loader').attr("hidden", true);
                    $('#editForm').removeAttr("hidden");
                },
                dataType: "json"
            });
        }

        // completed
        function editBtn() {
            var Inc_Id = document.getElementById("Edit_Inc_Id").value;
            var Prob_Id = document.getElementById("Edit_Prob_Id").value;
            var Cl_Id = document.getElementById("Edit_Cl_Id").value;
            var Evaluation_Loss = document.getElementById("Edit_Evaluation_Loss").value;
            var EL = Math.abs(Evaluation_Loss);
            var Status_Prob_Id = false;
            var Status_Evaluation_Loss = false;

            if (Prob_Id != '') {
                Status_Prob_Id = false;
                $("#Edit_Prob_Id").removeClass("is-invalid");
                text = document.getElementById("Edit_Prob_Id_error");
                text.textContent = "";

            } else {
                Status_Prob_Id = true;
                $("#Edit_Prob_Id").addClass("is-invalid");
                var text = document.getElementById("Edit_Prob_Id_error");
                text.textContent = "Enter a problem";
            }

            if (Evaluation_Loss != '') {
                Status_Evaluation_Loss = false;
                $("#Edit_Evaluation_Loss").removeClass("is-invalid");
                text = document.getElementById("Edit_Evaluation_Loss_error");
                text.textContent = "";

            } else {
                Status_Evaluation_Loss = true;
                $("#Edit_Evaluation_Loss").addClass("is-invalid");
                var text = document.getElementById("Edit_Evaluation_Loss_error");
                text.textContent = "Enter an evaluation loss";
            }

            if (Status_Prob_Id == true || Status_Evaluation_Loss == true) {
                return;
            } else {
                $('#edit_modal_close_btn').attr("hidden", true);
                $('.edit-modal-loader').removeAttr("hidden");
                $('#editForm').attr("hidden", true);
                $.ajax({
                    type: 'POST',
                    url: 'incidentListProcessor.php',
                    data: {
                        method: 'edit',
                        Inc_Id: Inc_Id,
                        Prob_Id: Prob_Id,
                        Cl_Id: Cl_Id,
                        Evaluation_Loss: EL
                    },
                    success: function(html) {
                        $('.edit-modal-loader').attr("hidden", true);
                        $('#editForm').removeAttr("hidden");
                        $('#Edit_Btns').attr("hidden", true);
                        $('#Edit_Texts').attr("hidden", false);
                        $('#Edit_Inputs').attr("hidden", true);
                        if (html.success == true) {
                            $('#edit_success_text').attr("hidden", false);
                            var text = document.getElementById("edit_success_text");
                            text.textContent = html.msg;
                        } else {
                            $('#edit_error_text').attr("hidden", false);
                            var text = document.getElementById("edit_error_text");
                            text.textContent = html.msg;
                        }
                        window.setTimeout("location.href='incidentList.php'", 1000);
                    },
                    dataType: "json"
                });
            }

        }

        // completed
        function check(element) {
            var id = element.id;
            var value = element.value;
            var text = document.getElementById(id + "_error");

            if (id == "Edit_Evaluation_Loss") {
                if (value == '') {
                    $("#" + id).addClass("is-invalid");
                    text.textContent = "Enter an evaluation loss";
                } else {
                    $("#" + id).removeClass("is-invalid");
                    text.textContent = "";
                }
            } else if (element.tagName == "SELECT") {
                if (id == 'Edit_Dep_Id') {
                    onChooseDepartment();
                } else if (id == 'Edit_Prob_Id') {
                    $("#" + id).removeClass("is-invalid");
                    text.textContent = "";
                }

            }

        }

        // completed
        function cancelRow(row) {
            $('#cancelmodal').modal('show');
            $('.delete-modal-loader').removeAttr("hidden");
            $('#cancelForm').attr("hidden", true);
            var Inc_Id = row.value;

            $('#Cancel_Inc_Id').val(Inc_Id);
            var text = document.getElementById("canceltext");
            text.innerHTML = "Do you want to cancel the incident with number&nbsp<b>" + Inc_Id + "</b>?";
            $('.delete-modal-loader').attr("hidden", true);
            $('#cancelForm').removeAttr("hidden");
        }

        // completed
        function cancelBtn() {
            $('#cancel_modal_close_btn').attr("hidden", true);
            $('.delete-modal-loader').removeAttr("hidden");
            $('#cancelForm').attr("hidden", true);

            var Inc_Id = document.getElementById("Cancel_Inc_Id").value;
            $.ajax({
                type: 'POST',
                url: 'incidentListProcessor.php',
                data: {
                    method : 'cancel',
                    Inc_Id : Inc_Id,
                },
                success: function(html) {
                    $('.delete-modal-loader').attr("hidden", true);
                    $('#cancelForm').removeAttr("hidden");
                    $('#Cancel_Btns').attr("hidden", true);
                    $('#Cancel_Texts').attr("hidden", false);
                    $('#canceltext').attr("hidden", true);
                    if (html.success == true) {
                        $('#cancel_success_text').attr("hidden", false);
                        var text = document.getElementById("cancel_success_text");
                        text.textContent = html.msg;
                    } else {
                        $('#cancel_error_text').attr("hidden", false);
                        var text = document.getElementById("cancel_error_text");
                        text.textContent = html.msg;
                    }
                    window.setTimeout("location.href='incidentList.php'", 1000);
                },
                dataType: "json"
            });
        }

        // completed
        function activeRow(row) {
            $('#activemodal').modal('show');
            $('.delete-modal-loader').removeAttr("hidden");
            $('#activeForm').attr("hidden", true);
            var Inc_Id = row.value;

            $('#Active_Inc_Id').val(Inc_Id);
            var text = document.getElementById("activetext");
            text.innerHTML = "Do you want to activate the incident with number&nbsp<b>" + Inc_Id + "</b>?";
            $('.delete-modal-loader').attr("hidden", true);
            $('#activeForm').removeAttr("hidden");
        }

        // completed
        function activeBtn() {
            $('#active_modal_close_btn').attr("hidden", true);
            $('.delete-modal-loader').removeAttr("hidden");
            $('#activeForm').attr("hidden", true);

            var Inc_Id = document.getElementById("Active_Inc_Id").value;
            $.ajax({
                type: 'POST',
                url: 'incidentListProcessor.php',
                data: {
                    method : 'active',
                    Inc_Id : Inc_Id,
                },
                success: function(html) {
                    $('.delete-modal-loader').attr("hidden", true);
                    $('#activeForm').removeAttr("hidden");
                    $('#Active_Btns').attr("hidden", true);
                    $('#Active_Texts').attr("hidden", false);
                    $('#activetext').attr("hidden", true);
                    if (html.success == true) {
                        $('#active_success_text').attr("hidden", false);
                        var text = document.getElementById("active_success_text");
                        text.textContent = html.msg;
                    } else {
                        $('#active_error_text').attr("hidden", false);
                        var text = document.getElementById("active_error_text");
                        text.textContent = html.msg;
                    }
                    window.setTimeout("location.href='incidentList.php'", 1000);
                },
                dataType: "json"
            });
        }

        // completed
        function onChooseDepartment() {
            var Dep_Id = document.getElementById('Edit_Dep_Id').value;
            $('#Edit_Prob_Id').prop('disabled');
            $.ajax({
                type: 'POST',
                url: 'incidentListProcessor.php',
                data: {
                    method: 'onChooseDepartment',
                    Dep_Id: Dep_Id
                },
                success: function(html) {
                    $('#Edit_Prob_Id').html(html.problem);
                    $('#Edit_Prob_Id').removeProp('disabled');
                },
                dataType: "json"
            });
        }

        // completed
        $(function() {

            $('.incident').addClass("menu-open");
            $('.incidenttag').addClass("active");
            $('.incidenttag').addClass("bg-danger");
            $('.incidentslist').addClass("active");

            $('#dataTable').dataTable().fnClearTable();
            $('#dataTable').dataTable().fnDestroy();

            $.ajax({
                type: 'POST',
                url: 'incidentListProcessor.php',
                data: {
                    method: 'load',
                },
                success: function(html) {
                    $('#incidentsList').html(html.incidentsList);
                    $('.loader').attr("hidden", true);
                    $('.card-body').removeAttr("hidden");

                    $("#dataTable").DataTable({
                        "paging": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": false,
                        "autoWidth": false,
                        "responsive": true,
                    });
                },
                dataType: "json"
            });

            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
        });

        // completed
        $(document).keyup(function(e) {
            if (e.key === "Escape") {
                $("#editmodal").modal('hide');
                $("#cancelmodal").modal('hide');
                $("#activemodal").modal('hide');
            }
        });

        // completed
        $(document).click(function(e) {
            if (!$(e.target).closest('.modal').length) {

                allElements = document.querySelectorAll('*');
                texts = document.getElementsByName("error_text");

                for (element of allElements) {
                    element.classList.remove("is-invalid");
                }

                for (text of texts) {
                    text.textContent = "";
                }
            }
        });

        // completed
        $(document).keypress(function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                if ($('#editmodal').is(':visible')) {
                    editBtn();
                } else if ($('#cancelmodal').is(':visible')) {
                    cancelBtn();
                } else if ($('#activemodal').is(':visible')) {
                    activeBtn();
                }
            }
        });


    </script>
</body>

</html>
