<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>

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
                            <h1 class="m-0">Users List</h1>
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
                                                <th>Number</th>
                                                <th>Full Name</th>
                                                <th>User Level</th>
                                                <th>CanAdd?</th>
                                                <th>CanEdit?</th>
                                                <th>CanDelete?</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="userList" align="center"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- reset modal -->
                <div class="modal fade" id="resetmodal">
                    <div class="modal-dialog">
                        <div class="modal-content callout callout-info">
                            <div class="modal-header">
                                <h4 class="modal-title">Reset User Password</h4>
                                <button id="reset_modal_close_btn" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Preloader -->
                            <div class="reset-modal-loader"></div>
                            <form id="resetForm" hidden>
                                <div class="modal-body">
                                    <input id="Reset_Emp_Id" type="hidden">
                                    <input id="Generate_Password" type="hidden">
                                    <div class="row" id="text"></div>
                                    <div id="Reset_Texts" align="center" hidden>
                                        <div id="reset_error_text" class="col-lg-12 alert alert-danger" hidden></div>
                                        <div id="reset_success_text" class="col-lg-12 alert alert-success" hidden></div>
                                    </div>
                                </div>
                                <div id="Reset_Btns" class="modal-footer justify-content-between">
                                    <button class="btn btn-success" data-dismiss="modal">No</button>
                                    <button type="button" class="btn btn-danger" onClick="resetBtn()">Yes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /reset model -->

                <!-- delete modal -->
                <div class="modal fade" id="deletemodal">
                    <div class="modal-dialog">
                        <div class="modal-content callout callout-danger">
                            <div class="modal-header">
                                <h4 class="modal-title">Delete User</h4>
                                <button id="delete_modal_close_btn" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Preloader -->
                            <div class="delete-modal-loader"></div>
                            <form id="deleteForm" hidden>
                                <div class="modal-body">
                                    <input id="Delete_Emp_Id" type="hidden">
                                    <div class="row" id="sentence"></div>
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
        function generatePassword(stringLength = 12) {
            const characters = "@.!?#$%^&*()-+=<>/~`_0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            let randomString = "";
            const charactersLength = characters.length;
            for ( let i = 0; i < stringLength; i++ ) {
                randomString += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            
            $('#Generate_Password').val(randomString);
        }

        // completed
        function resetRow(row) {
            $('#resetmodal').modal('show');
            $('.reset-modal-loader').removeAttr("hidden");
            $('#resetForm').attr("hidden", true);

            $('#reset_modal_close_btn').attr("hidden", true);
            $('#Reset_Btns').removeAttr("hidden");
            $('#Reset_Texts').attr("hidden", true);
            $('#text').removeAttr("hidden");
            
            var Emp_Id = row.value;
            $.ajax({
                type: 'POST',
                url: 'userProcessor.php',
                data: {
                    method : 'resetModal',
                    Emp_Id : Emp_Id
                },
                success: function(html) {
                    if (html.success == false) {
                        $('.reset-modal-loader').attr("hidden", true);
                        $('#resetForm').removeAttr("hidden");
                        $('#Reset_Btns').attr("hidden", true);
                        $('#Reset_Texts').removeAttr("hidden");
                        $('#text').attr("hidden", true);
                        $('#reset_error_text').removeAttr("hidden");
                        var text = document.getElementById("reset_error_text");
                        text.textContent = html.msg;
                        window.setTimeout("location.href='user.php'", 1000);

                    } else {

                        empName = html.user;
                        $('#Reset_Emp_Id').val(Emp_Id);
                        generatePassword(stringLength = 8);
                        var text = document.getElementById("text");
                        text.textContent = "Are you sure you want to reset " + empName + "'s password?";
                        $('.reset-modal-loader').attr("hidden", true);
                        $('#resetForm').removeAttr("hidden");

                    }
                    
                },
                dataType: "json"
            });
        }

        // completed
        function resetBtn() {
            $('#reset_modal_close_btn').attr("hidden", true);
            $('.reset-modal-loader').removeAttr("hidden");
            $('#resetForm').attr("hidden", true);

            var Emp_Id = document.getElementById("Reset_Emp_Id").value;
            var User_Password = document.getElementById("Generate_Password").value;
            $.ajax({
                type: 'POST',
                url: 'userProcessor.php',
                data: {
                    method : 'reset',
                    Emp_Id : Emp_Id,
                    User_Password : User_Password
                },
                success: function(html) {
                    $('.reset-modal-loader').attr("hidden", true);
                    $('#reset_modal_close_btn').removeAttr("hidden");
                    $('#resetForm').removeAttr("hidden");
                    $('#Reset_Btns').attr("hidden", true);
                    $('#Reset_Texts').attr("hidden", false);
                    $('#text').attr("hidden", true);
                    if (html.success == true) {
                        $('#reset_success_text').attr("hidden", false);
                        var text = document.getElementById("reset_success_text");
                        text.textContent = html.msg;
                    } else {
                        $('#reset_error_text').attr("hidden", false);
                        var text = document.getElementById("reset_error_text");
                        text.textContent = html.msg;
                    }
                },
                dataType: "json"
            });
        }

        // completed
        function deleteRow(row) {
            $('#deletemodal').modal('show');
            $('.delete-modal-loader').removeAttr("hidden");
            $('#deleteForm').attr("hidden", true);
            var Emp_Id = row.value;
            $.ajax({
                type: 'POST',
                url: 'userProcessor.php',
                data: {
                    method : 'deleteModal',
                    Emp_Id : Emp_Id,
                },
                success: function(html) {
                    if (html.success == false) {
                        $('#delete_modal_close_btn').attr("hidden", true);
                        $('.delete-modal-loader').attr("hidden", true);
                        $('#deleteForm').removeAttr("hidden");
                        $('#Delete_Btns').attr("hidden", true);
                        $('#Delete_Texts').attr("hidden", false);
                        $('#sentence').attr("hidden", true);
                        $('#delete_error_text').attr("hidden", false);
                        var text = document.getElementById("delete_error_text");
                        text.textContent = html.msg;
                        window.setTimeout("location.href='user.php'", 1000);

                    } else {

                        userName = html.user;
                        $('#Delete_Emp_Id').val(Emp_Id);
                        var text = document.getElementById("sentence");
                        text.textContent = "Are you sure you want to delete " + userName + "?";
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

            var Emp_Id = document.getElementById("Delete_Emp_Id").value;
            $.ajax({
                type: 'POST',
                url: 'userProcessor.php',
                data: {
                    method : 'delete',
                    Emp_Id : Emp_Id,
                },
                success: function(html) {
                    $('.delete-modal-loader').attr("hidden", true);
                    $('#deleteForm').removeAttr("hidden");
                    $('#Delete_Btns').attr("hidden", true);
                    $('#Delete_Texts').attr("hidden", false);
                    $('#sentence').attr("hidden", true);
                    if (html.success == true) {
                        $('#delete_success_text').attr("hidden", false);
                        var text = document.getElementById("delete_success_text");
                        text.textContent = html.msg;
                    } else {
                        $('#delete_error_text').attr("hidden", false);
                        var text = document.getElementById("delete_error_text");
                        text.textContent = html.msg;
                    }
                    window.setTimeout("location.href='user.php'", 1000);
                },
                dataType: "json"
            });
        }

        // completed
        $(function() {

            $('.data').addClass("menu-open");
            $('.datatag').addClass("active");
            $('.userlist').addClass("active");

            $('#dataTable').dataTable().fnClearTable();
            $('#dataTable').dataTable().fnDestroy();

            $.ajax({
                type: 'POST',
                url: 'userProcessor.php',
                data: {
                    method: 'load',
                },
                success: function(html) {
                    $('#userList').html(html.userList);
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
                $("#deletemodal").modal('hide');
                $("#resetmodal").modal('hide');
            }
        });


        // completed
        $(document).keypress(function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                if ($('#deletemodal').is(':visible')) {
                    deleteBtn();
                } else if ($('#resetmodal').is(':visible')) {
                    resetBtn();
                }
            }
        });
    </script>
</body>

</html>