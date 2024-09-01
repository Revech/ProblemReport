<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees List</title>

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
                            <h1 class="m-0">Employees List</h1>
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
                                                <th>Department</th>
                                                <th>Job</th>
                                                <th>Extension</th>
                                                <th>Email</th>
                                                <th>Created Date</th>
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
                                        <tbody id="employeeList" align="center"></tbody>
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
                                <h4 class="modal-title">New Employee</h4>
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
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Employee Number</label>
                                                    <input id="Add_Emp_Number" type="text" class="form-control" placeholder="Enter employee number" onKeyUp="addCheck(this)">
                                                    <span id="Add_Emp_Number_error" name="error_text" style="color:red;font-size:small"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>First Name</label>
                                                    <input id="Add_Emp_Fnm" type="text" class="form-control" placeholder="Enter first name" onKeyUp="addCheck(this)">
                                                    <span id="Add_Emp_Fnm_error" name="error_text" style="color:red;font-size:small"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Last Name</label>
                                                    <input id="Add_Emp_Lnm" type="text" class="form-control" placeholder="Enter last name" onKeyUp="addCheck(this)">
                                                    <span id="Add_Emp_Lnm_error" name="error_text" style="color:red;font-size:small"></span>
                                                </div>
                                            </div>
                                        </div>
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
                                                    <label>job</label>
                                                    <select id="Add_Job_Id" class="form-control select2bs4" style="width: 100%;" onChange="addCheck(this)"></select>
                                                    <span id="Add_Job_Id_error" name="error_text" style="color:red;font-size:small"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Extension</label>
                                                    <input id="Add_Extension" type="tel" class="form-control" placeholder="Enter extension" onKeyUp="addCheck(this)">
                                                    <span id="Add_Extension_error" name="error_text" style="color:red;font-size:small"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>E-mail</label>
                                                    <input id="Add_Email" type="text" class="form-control" placeholder="Enter email" onKeyUp="addCheck(this)">
                                                    <span id="Add_Email_error" name="error_text" style="color:red;font-size:small"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card card-dark collapsed-card">
                                                    <div class="card-header">
                                                        <h3 class="card-title">Is User ?</h3>
                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-lg-3"></div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label>Password</label>
                                                                    <input id="Add_Generated_Password" type="text" class="form-control" placeholder="Generate a password" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3"></div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-3"></div>
                                                            <div class="col-lg-3">
                                                                <button type="button" id="Add_" class="btn btn-success" onClick="generatePassword(this, 8)">Generate</button>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <button type="button" id="Add" class="btn btn-dark" onClick="copyPassword(this)">Copy</button>
                                                            </div>
                                                            <div class="col-lg-3"></div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label style="margin-left : 35px;">Admin</label>
                                                                    <input name="level" id="Add_Level_1" type="radio" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label style="margin-left : 30px;">Manager</label>
                                                                    <input name="level" id="Add_Level_2" type="radio" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label style="margin-left : 40px;">User</label>
                                                                    <input name="level" id="Add_Level_3" type="radio" class="form-control" checked>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label style="margin-left : 45px;">Add</label>
                                                                    <input name="action" id="Add_Can_Add" type="checkbox" class="form-control" checked>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label style="margin-left : 45px;">Edit</label>
                                                                    <input name="action" id="Add_Can_Edit" type="checkbox" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label style="margin-left : 35px;">Delete</label>
                                                                    <input name="action" id="Add_Can_Delete" type="checkbox" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
                                <h4 class="modal-title">Edit Employee</h4>
                                <button id="edit_modal_close_btn" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Preloader -->
                            <div class="edit-modal-loader"></div>
                            <!-- employee id -->
                            <input type="hidden" id="Edit_Emp_Id">

                            <form id="editForm" hidden autocomplete="off">
                                <div class="modal-body">
                                    <div id="Edit_Inputs">
                                        <div class="row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Employee Number</label>
                                                    <input id="Edit_Emp_Number" type="text" class="form-control" placeholder="Enter employee number" onKeyUp="editCheck(this)">
                                                    <span id="Edit_Emp_Number_error" name="error_text" style="color:red;font-size:small"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>First Name</label>
                                                    <input id="Edit_Emp_Fnm" type="text" class="form-control" placeholder="Enter first name" onKeyUp="editCheck(this)">
                                                    <span id="Edit_Emp_Fnm_error" name="error_text" style="color:red;font-size:small"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Last Name</label>
                                                    <input id="Edit_Emp_Lnm" type="text" class="form-control" placeholder="Enter last name" onKeyUp="editCheck(this)">
                                                    <span id="Edit_Emp_Lnm_error" name="error_text" style="color:red;font-size:small"></span>
                                                </div>
                                            </div>
                                        </div>
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
                                                    <label>job</label>
                                                    <select id="Edit_Job_Id" class="form-control select2bs4" style="width: 100%;" onChange="editCheck(this)"></select>
                                                    <span id="Edit_Job_Id_error" name="error_text" style="color:red;font-size:small"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Extension</label>
                                                    <input id="Edit_Extension" type="tel" class="form-control" placeholder="Enter extension" onKeyUp="editCheck(this)">
                                                    <span id="Edit_Extension_error" name="error_text" style="color:red;font-size:small"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>E-mail</label>
                                                    <input id="Edit_Email" type="text" class="form-control" placeholder="Enter email" onKeyUp="editCheck(this)">
                                                    <span id="Edit_Email_error" name="error_text" style="color:red;font-size:small"></span>
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
                                                        <input id="Edit_Emp_IsActive" type="checkbox" class="form-control">
                                                    </div>
                                                    <div class="col-lg-9"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6"></div>
                                        </div><br>
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="user-card" class="card card-dark">
                                                    <div class="card-header">
                                                        <h3 class="card-title">Is User ?</h3>
                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-lg-3"></div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label>Password</label>
                                                                    <input id="Edit_Generated_Password" type="text" class="form-control" placeholder="Generate a password" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3"></div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-3"></div>
                                                            <div class="col-lg-3">
                                                                <button type="button" id="Edit_" class="btn btn-success" onClick="generatePassword(this, 8)">Reset</button>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <button type="button" id="Edit" class="btn btn-dark" onClick="copyPassword(this)">Copy</button>
                                                            </div>
                                                            <div class="col-lg-3"></div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label style="margin-left : 35px;">Admin</label>
                                                                    <input name="level" id="Edit_Level_1" type="radio" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label style="margin-left : 30px;">Manager</label>
                                                                    <input name="level" id="Edit_Level_2" type="radio" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label style="margin-left : 40px;">User</label>
                                                                    <input name="level" id="Edit_Level_3" type="radio" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label style="margin-left : 45px;">Add</label>
                                                                    <input name="action" id="Edit_Can_Add" type="checkbox" class="form-control" checked>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label style="margin-left : 45px;">Edit</label>
                                                                    <input name="action" id="Edit_Can_Edit" type="checkbox" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label style="margin-left : 35px;">Delete</label>
                                                                    <input name="action" id="Edit_Can_Delete" type="checkbox" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                                <h4 class="modal-title">Delete Employee</h4>
                                <button id="delete_modal_close_btn" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Preloader -->
                            <div class="delete-modal-loader"></div>
                            <form id="deleteForm" hidden>
                                <div class="modal-body">
                                    <input id="Delete_Emp_Id" type="hidden">
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
        function generatePassword(element, stringLength = 12) {
            var id = element.id;
            const characters = "@.!?#$%^&*()-+=<>/~`_0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            let randomString = "";
            const charactersLength = characters.length;
            for ( let i = 0; i < stringLength; i++ ) {
                randomString += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            
            $('#' + id + 'Generated_Password').val(randomString);

            if (id == "Edit_") {

                $('#Edit_Level_3').attr("checked", true);

            }
        }

        // completed
        function copyPassword(element) {
            var id = element.id;
            if (id == "Add") {
                var copyText = document.getElementById("Add_Generated_Password");
            } else {
                var copyText = document.getElementById("Edit_Generated_Password");
            }
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
        }

        // completed
        function resetAddModal() {
            $('#Add_Dep_Id').attr("disabled", true);
            $('#Add_Job_Id').attr("disabled", true);
            $.ajax({
                type: 'POST',
                url: 'employeeProcessor.php',
                data: {
                    method: 'addModal',
                },
                success: function(html) {
                    $('#Add_Dep_Id').html(html.department);
                    $('#Add_Job_Id').html(html.job);
                    $('#Add_Dep_Id').removeAttr("disabled");
                    $('#Add_Job_Id').removeAttr("disabled");
                },
                dataType: "json"
            });
        }

        // completed
        function addRow() {
            $('#addmodal').modal('show');
            $('.add-modal-loader').removeAttr("hidden");
            $('#addForm').attr("hidden", true);
            $('#addForm').trigger("reset");

            $.ajax({
                type: 'POST',
                url: 'employeeProcessor.php',
                data: {
                    method: 'addModal',
                },
                success: function(html) {
                    $('#Add_Dep_Id').html(html.department);
                    $('#Add_Job_Id').html(html.job);

                    $('.add-modal-loader').attr("hidden", true);
                    $('#addForm').removeAttr("hidden");
                },
                dataType: "json"
            });
        }

        // completed
        function addBtn() {
            var employeeNumbers = /^[0-9]+$/;
            var employeeName = /^[a-zA-Z ]+$/;
            var extensionPattern = /^([0-9]{4})$/;
            var emailPattern = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

            var empNumber = document.getElementById("Add_Emp_Number").value;
            var empFnm = document.getElementById("Add_Emp_Fnm").value;
            var empLnm = document.getElementById("Add_Emp_Lnm").value;
            var depId = document.getElementById("Add_Dep_Id").value;
            var jobId = document.getElementById("Add_Job_Id").value;
            var extension = document.getElementById("Add_Extension").value;
            var email = document.getElementById("Add_Email").value;
            var password = document.getElementById("Add_Generated_Password").value;
            var level1 = $('#Add_Level_1:checked').val();
            var level2 = $('#Add_Level_2:checked').val();
            var level3 = $('#Add_Level_3:checked').val();
            var level;
            var actionAdd = $('#Add_Can_Add:checked').val();
            var actionEdit = $('#Add_Can_Edit:checked').val();
            var actionDelete = $('#Add_Can_Delete:checked').val();
            var canAdd;
            var canEdit;
            var canDelete;

            var Status_empNumber = false;
            var Status_empFnm = false;
            var Status_empLnm = false;
            var Status_depId = false;
            var Status_jobId = false;
            var Status_extension = false;
            var Status_email = false;

            if (empNumber.match(employeeNumbers)) {
                Status_empNumber = false;
                $("#Add_Emp_Number").removeClass("is-invalid");
                text = document.getElementById("Add_Emp_Number_error");
                text.textContent = "";
                
            } else {
                Status_empNumber = true;
                $("#Add_Emp_Number").addClass("is-invalid");
                var text = document.getElementById("Add_Emp_Number_error");
                text.textContent = "Enter a valid number";
            }

            if (empFnm.trim() != '') {
                Status_empFnm = false;
                $("#Add_Emp_Fnm").removeClass("is-invalid");
                text = document.getElementById("Add_Emp_Fnm_error");
                text.textContent = "";
            } else {
                Status_empFnm = true;
                $("#Add_Emp_Fnm").addClass("is-invalid");
                var text = document.getElementById("Add_Emp_Fnm_error");
                text.textContent = "Enter a valid name";
            }

            if (empLnm.trim() != '') {
                Status_empLnm = false;
                $("#Add_Emp_Lnm").removeClass("is-invalid");
                text = document.getElementById("Add_Emp_Lnm_error");
                text.textContent = "";
            } else {
                Status_empLnm = true;
                $("#Add_Emp_Lnm").addClass("is-invalid");
                var text = document.getElementById("Add_Emp_Lnm_error");
                text.textContent = "Enter a valid name";
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
                text.textContent = "Enter a department";
            }

            if (jobId != '') {
                Status_jobId = false;
                $("#Add_Job_Id").removeClass("is-invalid");
                text = document.getElementById("Add_Job_Id_error");
                text.textContent = "";
                
            } else {
                Status_jobId = true;
                $("#Add_Job_Id").addClass("is-invalid");
                var text = document.getElementById("Add_Job_Id_error");
                text.textContent = "Enter a job";
            }

            if (extension.match(extensionPattern)) {
                Status_extension = false;
                $("#Add_Extension").removeClass("is-invalid");
                text = document.getElementById("Add_Extension_error");
                text.textContent = "";
                
            } else {
                Status_extension = true;
                $("#Add_Extension").addClass("is-invalid");
                var text = document.getElementById("Add_Extension_error");
                text.textContent = "Please enter 4 numbers!";
            }

            if (email.match(emailPattern)) {
                Status_email = false;
                $("#Add_Email").removeClass("is-invalid");
                text = document.getElementById("Add_Email_error");
                text.textContent = "";
                
            } else {
                Status_email = true;
                $("#Add_Email").addClass("is-invalid");
                var text = document.getElementById("Add_Email_error");
                text.textContent = "Enter a valid email";
            }

            if (level1 != null) {
                level = 1;
            } else if (level2 != null) {
                level = 2;
            } else if (level3 != null) {
                level = 3;
            }

            if (Status_empNumber == true || Status_empFnm == true || Status_empLnm == true || Status_depId == true || Status_jobId == true || Status_extension == true || Status_email == true) {
                return;
            } else {
                $('#add_modal_close_btn').attr("hidden", true);
                $('.add-modal-loader').removeAttr("hidden");
                $('#addForm').attr("hidden", true);
                $.ajax({
                    type: 'POST',
                    url: 'employeeProcessor.php',
                    data: {
                        method: 'add',
                        Emp_Number: empNumber,
                        Emp_Fnm: empFnm,
                        Emp_Lnm: empLnm,
                        Dep_Id: depId,
                        Job_Id: jobId,
                        Emp_Extension: extension,
                        Emp_Email: email,
                        User_Password: password,
                        User_Level: level,
                        User_CanAdd: actionAdd,
                        User_CanEdit: actionEdit,
                        User_CanDelete: actionDelete
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
                        window.setTimeout("location.href='employee.php'", 1000);
                    },
                    dataType: "json"
                });
            }
        }

        // completed
        function addCheck(element) {
            var employeeNumbers = /^[0-9]+$/;
            var employeeName = /^[a-zA-Z ]+$/;
            var extensionPattern = /^([0-9]{4})$/;
            var emailPattern = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            var id = element.id;
            var value = element.value;
            var text = document.getElementById(id + "_error");

            if (element.type == "tel") {
                if (value.match(extensionPattern)) {
                    $("#" + id).removeClass("is-invalid");
                    text.textContent = "";
                } else {
                    $("#" + id).addClass("is-invalid");
                    text.textContent = "Please enter 4 numbers!";
                }
            } else if (element.type == "text") {
                if (value.trim() == '' || !value.match(employeeName)) {
                    $("#" + id).addClass("is-invalid");
                    if (id == "Add_Emp_Fnm" || id == "Add_Emp_Lnm") {
                        text.textContent = "Enter a valid name";
                    }
                } else {
                    $("#" + id).removeClass("is-invalid");
                    text.textContent = "";
                }

                if (id == "Add_Emp_Number") {
                    if (value.match(employeeNumbers)) {
                        $("#" + id).removeClass("is-invalid");
                        text.textContent = "";
                    } else {
                        $("#" + id).addClass("is-invalid");
                        text.textContent = "Enter a valid number";
                    }
                }

                if (id == "Add_Email") {
                    if (value.match(emailPattern)) {
                        $("#" + id).removeClass("is-invalid");
                        text.textContent = "";
                    } else {
                        $("#" + id).addClass("is-invalid");
                        text.textContent = "Please enter a valid email";
                    }
                }
            } else if (element.tagName == "SELECT") {
                if (id == "Add_Dep_Id") {
                    $('#Add_Job_Id').attr("disabled", true);
                    $.ajax({
                        type: 'POST',
                        url: 'employeeProcessor.php',
                        data: {
                            method: 'getJob',
                            depId: value
                        },
                        success: function(html) {
                            $('#Add_Job_Id').html(html.job);
                            $('#Add_Job_Id').removeAttr("disabled");
                        },
                        dataType: "json"
                    });
                }
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

            var Emp_Id = row.value;

            $.ajax({
                type: 'POST',
                url: 'employeeProcessor.php',
                data: {
                    method : 'editModal',
                    Emp_Id: Emp_Id
                },
                success: function(html) {
                    $('#Edit_Emp_Id').val(Emp_Id);
                    $('#Edit_Emp_Number').val(html.employee.Emp_Number);
                    $('#Edit_Emp_Fnm').val(html.employee.Emp_Fnm);
                    $('#Edit_Emp_Lnm').val(html.employee.Emp_Lnm);
                    $('#Edit_Extension').val(html.employee.Emp_Extension);
                    $('#Edit_Email').val(html.employee.Emp_Email);
                    $('#Edit_Dep_Id').html(html.department);
                    $('#Edit_Job_Id').html(html.job);

                    if (html.employee.Emp_IsActive == 1) {
                        $("#Edit_Emp_IsActive").prop("checked", "checked");
                    } else {
                        $("#Edit_Emp_IsActive").removeAttr("checked");
                    }

                    if (html.employee.Emp_IsUser == true) {

                        $('#user-card').removeClass("collapsed-card");
                        $('#Edit_').html("Reset");

                        $('input[name=level]').prop('checked', false);
                        $('input[name=action]').prop('checked', false);

                        if (html.user.User_Level == 1) {
                            $('input[id=Edit_Level_1]').prop("checked", "checked");
                        } else if (html.user.User_Level == 2) {
                            $('input[id=Edit_Level_2]').prop("checked", "checked");
                        } else if (html.user.User_Level == 3) {
                            $('input[id=Edit_Level_3]').prop("checked", "checked");
                        }

                        if (html.user.User_CanAdd == true) {
                            $('input[id=Edit_Can_Add]').prop("checked", "checked");
                        }

                        if (html.user.User_CanEdit == true) {
                            $('input[id=Edit_Can_Edit]').prop("checked", "checked");
                        }

                        if (html.user.User_CanDelete == true) {
                            $('input[id=Edit_Can_Delete]').prop("checked", "checked");
                        }

                    } else {

                        $('#user-card').addClass("collapsed-card");
                        $('#Edit_').html("Generate");

                    }

                    $('.edit-modal-loader').attr("hidden", true);
                    $('#editForm').removeAttr("hidden");
                },
                dataType: "json"
            });
        }

        // completed
        function editBtn() {

            var employeeNumbers = /^[0-9]+$/;
            var employeeName = /^[a-zA-Z ]+$/;
            var extensionPattern = /^([0-9]{4})$/;
            var emailPattern = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

            var empId = document.getElementById("Edit_Emp_Id").value;
            var empNumber = document.getElementById("Edit_Emp_Number").value;
            var empFnm = document.getElementById("Edit_Emp_Fnm").value;
            var empLnm = document.getElementById("Edit_Emp_Lnm").value;
            var depId = document.getElementById("Edit_Dep_Id").value;
            var jobId = document.getElementById("Edit_Job_Id").value;
            var extension = document.getElementById("Edit_Extension").value;
            var email = document.getElementById("Edit_Email").value;
            var password = document.getElementById("Edit_Generated_Password").value;
            var status = $('#Edit_Emp_IsActive:checked').val();
            var level1 = $('#Edit_Level_1:checked').val();
            var level2 = $('#Edit_Level_2:checked').val();
            var level3 = $('#Edit_Level_3:checked').val();
            var level;
            var actionAdd = $('#Edit_Can_Add:checked').val();
            var actionEdit = $('#Edit_Can_Edit:checked').val();
            var actionDelete = $('#Edit_Can_Delete:checked').val();

            var Status_empNumber = false;
            var Status_empFnm = false;
            var Status_empLnm = false;
            var Status_depId = false;
            var Status_jobId = false;
            var Status_extension = false;
            var Status_email = false;

            if (empNumber.match(employeeNumbers) || empNumber.trim() != '') {
                Status_empNumber = false;
                $("#Edit_Emp_Number").removeClass("is-invalid");
                text = document.getElementById("Edit_Emp_Number_error");
                text.textContent = "";
                
            } else {
                Status_empNumber = true;
                $("#Edit_Emp_Number").addClass("is-invalid");
                var text = document.getElementById("Edit_Emp_Number_error");
                text.textContent = "Enter a valid number";
            }

            if (empFnm.trim() != '') {
                Status_empFnm = false;
                $("#Edit_Emp_Fnm").removeClass("is-invalid");
                text = document.getElementById("Edit_Emp_Fnm_error");
                text.textContent = "";
            } else {
                Status_empFnm = true;
                $("#Edit_Emp_Fnm").addClass("is-invalid");
                var text = document.getElementById("Edit_Emp_Fnm_error");
                text.textContent = "Enter a valid name";
            }

            if (empLnm.trim() != '') {
                Status_empLnm = false;
                $("#Edit_Emp_Lnm").removeClass("is-invalid");
                text = document.getElementById("Edit_Emp_Lnm_error");
                text.textContent = "";
            } else {
                Status_empLnm = true;
                $("#Edit_Emp_Lnm").addClass("is-invalid");
                var text = document.getElementById("Edit_Emp_Lnm_error");
                text.textContent = "Enter a valid name";
            }

            if (depId != '') {
                Status_depId = false;
                $("#Edit_Dep_Id").removeClass("is-invalid");
                text = document.getElementById("Edit_Dep_Id_error");
                text.textContent = "";
                
            } else {
                Status_depId = true;
                $("#Edit_Dep_Id").addClass("is-invalid");
                var text = document.getElementById("Edit_Dep_Id_error");
                text.textContent = "Enter a department";
            }

            if (jobId != '') {
                Status_jobId = false;
                $("#Edit_Job_Id").removeClass("is-invalid");
                text = document.getElementById("Edit_Job_Id_error");
                text.textContent = "";
                
            } else {
                Status_jobId = true;
                $("#Edit_Job_Id").addClass("is-invalid");
                var text = document.getElementById("Edit_Job_Id_error");
                text.textContent = "Enter a job";
            }

            if (extension.match(extensionPattern)) {
                Status_extension = false;
                $("#Edit_Extension").removeClass("is-invalid");
                text = document.getElementById("Edit_Extension_error");
                text.textContent = "";
                
            } else {
                Status_extension = true;
                $("#Edit_Extension").addClass("is-invalid");
                var text = document.getElementById("Edit_Extension_error");
                text.textContent = "Please enter 4 numbers!";
            }

            if (email.match(emailPattern)) {
                Status_email = false;
                $("#Edit_Email").removeClass("is-invalid");
                text = document.getElementById("Edit_Email_error");
                text.textContent = "";
                
            } else {
                Status_email = true;
                $("#Edit_Email").addClass("is-invalid");
                var text = document.getElementById("Edit_Email_error");
                text.textContent = "Enter a valid email";
            }

            if (level1 != null) {
                level = 1;
            } else if (level2 != null) {
                level = 2;
            } else if (level3 != null) {
                level = 3;
            }

            if (Status_empNumber == true || Status_empFnm == true || Status_empLnm == true || Status_depId == true || Status_jobId == true || Status_extension == true || Status_email == true) {
                return;
            } else {
                $('#edit_modal_close_btn').attr("hidden", true);
                $('.edit-modal-loader').removeAttr("hidden");
                $('#editForm').attr("hidden", true);
                $.ajax({
                    type: 'POST',
                    url: 'employeeProcessor.php',
                    data: {
                        method: 'edit',
                        Emp_Id: empId,
                        Emp_Number: empNumber,
                        Emp_Fnm: empFnm,
                        Emp_Lnm: empLnm,
                        Dep_Id: depId,
                        Job_Id: jobId,
                        Emp_Extension: extension,
                        Emp_Email: email,
                        Emp_IsActive: status,
                        User_Password: password,
                        User_Level: level,
                        User_CanAdd: actionAdd,
                        User_CanEdit: actionEdit,
                        User_CanDelete: actionDelete
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
                        window.setTimeout("location.href='employee.php'", 1000);
                    },
                    dataType: "json"
                });
            }

        }

        // completed
        function editCheck(element) {
            var employeeNumbers = /^[0-9]+$/;
            var employeeName = /^[a-zA-Z ]+$/;
            var extensionPattern = /^([0-9]{4})$/;
            var emailPattern = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            var id = element.id;
            var value = element.value;
            var text = document.getElementById(id + "_error");

            if (element.type == "tel") {
                if (value.match(extensionPattern)) {
                    $("#" + id).removeClass("is-invalid");
                    text.textContent = "";
                } else {
                    $("#" + id).addClass("is-invalid");
                    text.textContent = "Please enter 4 numbers!";
                }
            } else if (element.type == "text") {
                if (value.trim() == '' || !value.match(employeeName)) {
                    $("#" + id).addClass("is-invalid");
                    if (id == "Edit_Emp_Fnm" || id == "Edit_Emp_Lnm") {
                        text.textContent = "Enter a valid name";
                    }
                } else {
                    $("#" + id).removeClass("is-invalid");
                    text.textContent = "";
                }

                if (id == "Edit_Emp_Number") {
                    if (value.match(employeeNumbers) || value.trim() != '') {
                        $("#" + id).removeClass("is-invalid");
                        text.textContent = "";
                    } else {
                        $("#" + id).addClass("is-invalid");
                        text.textContent = "Enter a valid number";
                    }
                }

                if (id == "Edit_Email") {
                    if (value.match(emailPattern)) {
                        $("#" + id).removeClass("is-invalid");
                        text.textContent = "";
                    } else {
                        $("#" + id).addClass("is-invalid");
                        text.textContent = "Please enter a valid email";
                    }
                }
            } else if (element.tagName == "SELECT") {
                if (id == "Edit_Dep_Id") {
                    $('#Edit_Job_Id').attr("disabled", true);
                    $.ajax({
                        type: 'POST',
                        url: 'employeeProcessor.php',
                        data: {
                            method: 'getJob',
                            depId: value
                        },
                        success: function(html) {
                            $('#Edit_Job_Id').html(html.job);
                            $('#Edit_Job_Id').removeAttr("disabled");
                        },
                        dataType: "json"
                    });
                }
                $("#" + id).removeClass("is-invalid");
                text.textContent = "";
            }
        }

        // completed
        function deleteRow(row) {
            $('#deletemodal').modal('show');
            $('.delete-modal-loader').removeAttr("hidden");
            $('#deleteForm').attr("hidden", true);
            var Emp_Id = row.value;
            $.ajax({
                type: 'POST',
                url: 'employeeProcessor.php',
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
                        $('#text').attr("hidden", true);
                        $('#delete_error_text').attr("hidden", false);
                        var text = document.getElementById("delete_error_text");
                        text.textContent = html.msg;
                        window.setTimeout("location.href='employee.php'", 1000);

                    } else {

                        empName = html.employee;
                        $('#Delete_Emp_Id').val(Emp_Id);
                        var text = document.getElementById("text");
                        text.textContent = "Are you sure you want to delete " + empName + "?";
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
                url: 'employeeProcessor.php',
                data: {
                    method : 'delete',
                    Emp_Id : Emp_Id,
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
                    window.setTimeout("location.href='employee.php'", 1000);
                },
                dataType: "json"
            });
        }

        // completed
        $(function() {

            $('.data').addClass("menu-open");
            $('.datatag').addClass("active");
            $('.employeelist').addClass("active");

            $('#dataTable').dataTable().fnClearTable();
            $('#dataTable').dataTable().fnDestroy();

            $.ajax({
                type: 'POST',
                url: 'employeeProcessor.php',
                data: {
                    method: 'load',
                },
                success: function(html) {
                    $('#employeeList').html(html.employeeList);
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