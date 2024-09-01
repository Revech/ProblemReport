<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs List</title>

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
                            <h1 class="m-0">Jobs List</h1>
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
                                                <th>Job</th>
                                                <th>Department</th>
                                                <th>IsActive?</th>
                                                <?php
                                                    if ($_SESSION['canAdd'] == true) {

                                                        $canAdd = '';
                                        
                                                    } else {
                                        
                                                        $canAdd = 'hidden';
                                                    }
                                                ?>

                                                <th>
                                                    <button class="btn btn-success" title="add" onClick="addRow()" <?php echo $canAdd;?>>
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="jobList" align="center"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- add modal -->
                <div class="modal fade" id="addmodal">
                    <div class="modal-dialog">
                        <div class="modal-content callout callout-success">
                            <div class="modal-header">
                                <h4 class="modal-title">New Job</h4>
                                <button id="add_modal_close_btn" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Preloader -->
                            <div class="add-modal-loader"></div>

                            <form id="addForm" hidden autocomplete="off">
                                <div class="modal-body">
                                    <div id="Add_Inputs">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Department</label>
                                                    <select id="Add_Dep_Id" class="form-control select2bs4" style="width: 100%;" onChange="addCheck(this)"></select>
                                                    <span id="Add_Dep_Id_error" name="error_text" style="color:red;font-size:small"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Job</label>
                                                    <textarea id="Add_Job" type="text" class="form-control" placeholder="Enter a Job" onKeyUp="addCheck(this)"></textarea>
                                                    <span id="Add_Job_error" name="error_text" style="color:red;font-size:small"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="Add_Texts" align="center" hidden>
                                        <div id="add_error_text" class="col-lg-12 alert alert-danger" hidden></div>
                                        <div id="add_success_text" class="col-lg-12 alert alert-success" hidden></div>
                                    </div>
                                </div>
                                <div id="Add_Btns" class="modal-footer justify-content-between">
                                    <button type="reset" class="btn btn-warning" onClick="resetAddModal()">Cancel</button>
                                    <button type="button" class="btn btn-primary" onClick="addBtn()">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /add modal -->

                <!-- edit modal -->
                <div class="modal fade" id="editmodal">
                    <div class="modal-dialog">
                        <div class="modal-content callout callout-info">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit Job</h4>
                                <button id="edit_modal_close_btn" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Preloader -->
                            <div class="edit-modal-loader"></div>
                            <!-- Problem id -->
                            <input type="hidden" id="Edit_Job_Id">

                            <form id="editForm" hidden autocomplete="off">
                                <div class="modal-body">
                                    <div id="Edit_Inputs">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Department</label>
                                                    <select id="Edit_Dep_Id" class="form-control select2bs4" style="width: 100%;" onChange="editCheck(this)"></select>
                                                    <span id="Edit_Dep_Id_error" name="error_text" style="color:red;font-size:small"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Job</label>
                                                    <textarea id="Edit_Job_Desc" type="text" class="form-control" placeholder="Enter a job" onKeyUp="editCheck(this)"></textarea>
                                                    <span id="Edit_Job_Desc_error" name="error_text" style="color:red;font-size:small"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <label>IsActive?</label>
                                                    </div>
                                                    <div class="col-lg-9"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <input id="Edit_Job_IsActive" type="checkbox" class="form-control">
                                                    </div>
                                                    <div class="col-lg-9"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6"></div>
                                        </div>
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

                <!-- delete modal -->
                <div class="modal fade" id="deletemodal">
                    <div class="modal-dialog">
                        <div class="modal-content callout callout-danger">
                            <div class="modal-header">
                                <h4 class="modal-title">Delete Job</h4>
                                <button id="delete_modal_close_btn" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Preloader -->
                            <div class="delete-modal-loader"></div>
                            <form id="deleteForm" hidden>
                                <div class="modal-body">
                                    <input id="Delete_Job_Id" type="hidden">
                                    <div class="row" id="text"></div>
                                    <div id="Delete_Texts" align="center" hidden>
                                        <div id="delete_error_text" class="col-lg-12 alert alert-danger" hidden></div>
                                        <div id="delete_success_text" class="col-lg-12 alert alert-success" hidden></div>
                                    </div>
                                </div>
                                <div id="Delete_Btns" class="modal-footer justify-content-between">
                                    <button class="btn btn-success" data-dismiss="modal">No</button>
                                    <button type="button" class="btn btn-danger" onClick="deleteBtn()">Yes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /delete model -->

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

        // completed
        function addRow() {
            $('#addmodal').modal('show');
            $('.add-modal-loader').removeAttr("hidden");
            $('#addForm').attr("hidden", true);
            $('#addForm').trigger("reset");

            $.ajax({
                type: 'POST',
                url: 'jobProcessor.php',
                data: {
                    method: 'addModal',
                },
                success: function(html) {
                    $('#Add_Dep_Id').html(html.department);

                    $('.add-modal-loader').attr("hidden", true);
                    $('#addForm').removeAttr("hidden");
                },
                dataType: "json"
            });
        }

        // completed
        function addBtn() {

            var job = document.getElementById("Add_Job").value;
            var depId = document.getElementById("Add_Dep_Id").value;

            var Status_job = false;
            var Status_depId = false;

            if (job.trim() != '') {
                Status_job = false;
                $("#Add_Job").removeClass("is-invalid");
                text = document.getElementById("Add_Job_error");
                text.textContent = "";
            } else {
                Status_problem = true;
                $("#Add_Job").addClass("is-invalid");
                var text = document.getElementById("Add_Job_error");
                text.textContent = "Enter a Job";
            }

            if (depId != '') {
                Status_depId = false;
                $("#Add_Dep_Id").removeClass("is-invalid");
                text = document.getElementById("Add_Dep_Id_error");
                text.textContent = "";
                
            } else {
                Status_depId = true;
                $("#Add_Dep_Id").addClass("is-invalid");
                var text = document.getElementById("Add_Dep_Id_error");
                text.textContent = "Choose a department";
            }

            if (Status_job == true || Status_depId == true) {
                return;
            } else {
                $('#add_modal_close_btn').attr("hidden", true);
                $('.add-modal-loader').removeAttr("hidden");
                $('#addForm').attr("hidden", true);
                $.ajax({
                    type: 'POST',
                    url: 'jobProcessor.php',
                    data: {
                        method: 'add',
                        Job_Desc: job,
                        Dep_Id: depId
                        
                    },
                    success: function(html) {
                        $('.add-modal-loader').attr("hidden", true);
                        $('#addForm').removeAttr("hidden");
                        $('#Add_Btns').attr("hidden", true);
                        $('#Add_Texts').attr("hidden", false);
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
                        window.setTimeout("location.href='job.php'", 1000);
                    },
                    dataType: "json"
                });
            }
        }
        
        // completed
        function addCheck(element) {
            var id = element.id;
            var value = element.value;
            var text = document.getElementById(id + "_error");

            if (id == "Add_Job") {
                if (value.trim() == '') {
                    $("#" + id).addClass("is-invalid");
                    text.textContent = "Enter a job";
                } else {
                    $("#" + id).removeClass("is-invalid");
                    text.textContent = "";
                }
            } else if (element.tagName == "SELECT") {
                $("#" + id).removeClass("is-invalid");
                text.textContent = "";
            }
        }

        // completed
        function editRow(row) {
            $('#editmodal').modal('show');
            $('.edit-modal-loader').removeAttr("hidden");
            $('#editForm').attr("hidden", true);
            $('#editForm').trigger("reset");

            var Job_Id = row.value;

            $.ajax({
                type: 'POST',
                url: 'jobProcessor.php',
                data: {
                    method : 'editModal',
                    Job_Id: Job_Id
                },
                success: function(html) {
                    $('#Edit_Job_Id').val(Job_Id);
                    $('#Edit_Job_Desc').val(html.job.Job_Desc);
                    $('#Edit_Dep_Id').html(html.department);

                    if (html.job.Job_IsActive == 1) {
                        $("#Edit_Job_IsActive").prop("checked", "checked");
                    } else {
                        $("#Edit_Job_IsActive").removeAttr("checked");
                    }

                    $('.edit-modal-loader').attr("hidden", true);
                    $('#editForm').removeAttr("hidden");
                },
                dataType: "json"
            });
        }

        // completed
        function editBtn() {

            var Job_Id = document.getElementById("Edit_Job_Id").value;
            var Job_Desc = document.getElementById("Edit_Job_Desc").value;
            var Dep_Id = document.getElementById("Edit_Dep_Id").value;
            var status = $('#Edit_Job_IsActive:checked').val();
            var Status_Dep_Desc = false;

            if (Job_Desc.trim() != '') {
                Status_Job_Desc = false;
                $("#Edit_Job_Desc").removeClass("is-invalid");
                text = document.getElementById("Edit_Job_Desc_error");
                text.textContent = "";
                
            } else {
                Status_Job_Desc = true;
                $("#Edit_Job_Desc").addClass("is-invalid");
                var text = document.getElementById("Edit_Job_Desc_error");
                text.textContent = "Enter a job";
            }

            if (Dep_Id != '') {
                Status_Dep_Id = false;
                $("#Edit_Dep_Id").removeClass("is-invalid");
                text = document.getElementById("Edit_Dep_Id_error");
                text.textContent = "";
                
            } else {
                Status_Dep_Id = true;
                $("#Edit_Dep_Id").addClass("is-invalid");
                var text = document.getElementById("Edit_Dep_Id_error");
                text.textContent = "Choose a department";
            }

            if (Status_Job_Desc == true ||  Status_Dep_Id == true ) {
                return;
            } else {
                $('#edit_modal_close_btn').attr("hidden", true);
                $('.edit-modal-loader').removeAttr("hidden");
                $('#editForm').attr("hidden", true);
                $.ajax({
                    type: 'POST',
                    url: 'jobProcessor.php',
                    data: {
                        method: 'edit',
                        Job_Id: Job_Id,
                        Job_Desc: Job_Desc,
                        Dep_Id: Dep_Id,
                        Job_IsActive: status,
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
                        window.setTimeout("location.href='job.php'", 1000);
                    },
                    dataType: "json"
                });
            }

        }

        // completed
        function editCheck(element) {
            var id = element.id;
            var value = element.value;
            var text = document.getElementById(id + "_error");

            if (element.tagName == "TEXTAREA") {
                if (value.trim() == '') {
                    $("#" + id).addClass("is-invalid");
                    text.textContent = "Enter a job";
                } else {
                    $("#" + id).removeClass("is-invalid");
                    text.textContent = "";
                }
            } else if (element.tagName == "SELECT") {
                $("#" + id).removeClass("is-invalid");
                text.textContent = "";
            }

        }

        // completed
        function deleteRow(row) {
            $('#deletemodal').modal('show');
            $('.delete-modal-loader').removeAttr("hidden");
            $('#deleteForm').attr("hidden", true);
            var Job_Id = row.value;
            $.ajax({
                type: 'POST',
                url: 'jobProcessor.php',
                data: {
                    method : 'deleteModal',
                    Job_Id : Job_Id,
                },
                success: function(html) {
                    if (html.success == false) {
                        $('#delete_modal_close_btn').attr("hidden", true);
                        $('.delete-modal-loader').attr("hidden", true);
                        $('#deleteForm').removeAttr("hidden");
                        $('#Delete_Btns').attr("hidden", true);
                        $('#Delete_Texts').attr("hidden", false);
                        $('#text').attr("hidden", true);
                        $('#delete_error_text').attr("hidden", false);
                        var text = document.getElementById("delete_error_text");
                        text.textContent = html.msg;
                        window.setTimeout("location.href='job.php'", 1000);

                    } else {

                        jobName = html.job;
                        $('#Delete_Job_Id').val(Job_Id);
                        var text = document.getElementById("text");
                        text.textContent = "Are you sure you want to delete " + jobName + "?";
                        $('.delete-modal-loader').attr("hidden", true);
                        $('#deleteForm').removeAttr("hidden");

                    }
                    
                },
                dataType: "json"
            });
        }

        // completed
        function deleteBtn() {
            $('#delete_modal_close_btn').attr("hidden", true);
            $('.delete-modal-loader').removeAttr("hidden");
            $('#deleteForm').attr("hidden", true);

            var Job_Id = document.getElementById("Delete_Job_Id").value;
            $.ajax({
                type: 'POST',
                url: 'jobProcessor.php',
                data: {
                    method : 'delete',
                    Job_Id : Job_Id,
                },
                success: function(html) {
                    $('.delete-modal-loader').attr("hidden", true);
                    $('#deleteForm').removeAttr("hidden");
                    $('#Delete_Btns').attr("hidden", true);
                    $('#Delete_Texts').attr("hidden", false);
                    $('#text').attr("hidden", true);
                    if (html.success == true) {
                        $('#delete_success_text').attr("hidden", false);
                        var text = document.getElementById("delete_success_text");
                        text.textContent = html.msg;
                    } else {
                        $('#delete_error_text').attr("hidden", false);
                        var text = document.getElementById("delete_error_text");
                        text.textContent = html.msg;
                    }
                    window.setTimeout("location.href='job.php'", 1000);
                },
                dataType: "json"
            });
        }

        // completed
        $(function() {

            $('.data').addClass("menu-open");
            $('.datatag').addClass("active");
            $('.joblist').addClass("active");

            $('#dataTable').dataTable().fnClearTable();
            $('#dataTable').dataTable().fnDestroy();

            $.ajax({
                type: 'POST',
                url: 'jobProcessor.php',
                data: {
                    method: 'load',
                },
                success: function(html) {
                    $('#jobList').html(html.jobList);
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
                $("#addmodal").modal('hide');
                $("#editmodal").modal('hide');
                $("#deletemodal").modal('hide');
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
                if ($('#addmodal').is(':visible')) {
                    addBtn();
                } else if ($('#editmodal').is(':visible')) {
                    editBtn();
                } else if ($('#deletemodal').is(':visible')) {
                    deleteBtn();
                }
            }
        });
    </script>
</body>

</html>