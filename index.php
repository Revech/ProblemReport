<?php
    ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="icon" href="app/dist/img/liu_logo.png"/>

    <!-- CSS only -->
    <link rel="stylesheet" href="app/dist/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="app/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="app/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="app/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

    <!-- Loader -->
    <link rel="stylesheet" href="app/dist/css/loader.css">
</head>
<body>
    <?php
        require_once "api/common.php";
        pdoConnect();
    ?>
    <main class="form-signin">
        <form id="signInForm" autocomplete="off">
            <img class="mb-4 img" src="app/dist/img/liu_logo.png" alt="" height="250" width="250" style="border-radius: 5%;">
            <p class="h5 mb-3" style="color : darkblue;">Lebanese International University</p>
            <p class="h6 mb-3" style="color : darkblue;">Incidents Tracking / Reporting System</p>

            <div class="form-floating">
                <input type="text" class="form-control" id="usernumber" placeholder="Enter User number">
                <label for="floatingInput">User Number</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="userpassword" placeholder="Enter password">
                <label for="floatingPassword">Password</label>
            </div>
            <div id="errormsg" class="alert alert-danger errormsg" role="alert" align="center" hidden></div>
            <button type="button" class="w-100 btn btn-lg btn-primary" onClick="signIn()">Sign In</button>
            <p class="mt-5 mb-3 text-muted">&copy; <?php echo date("Y");?></p>
        </form>
    </main>

    <!-- change password modal -->
    <div class="modal fade" id="chpsmodal">
        <div class="modal-dialog">
            <div class="modal-content callout callout-info">
                <div class="modal-header">
                    <h4 class="modal-title">Change Password</h4>
                </div>
                <!-- Preloader -->
                <div class="chps-modal-loader"></div>

                <form id="chpsForm" autocomplete="off">
                    <div class="modal-body">
                        <div id="ChPs_Inputs">
                            <div class="row">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>New Password</label>
                                        <input id="User_Pass" type="text" class="form-control" placeholder="Enter new password" onKeyUp="check(this)">
                                        <span id="User_Pass_error" name="error_text" style="color:red;font-size:small"></span>
                                    </div>
                                </div>
                                <div class="col-lg-3"></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input id="User_Pass_Conf" type="text" class="form-control" placeholder="Confirm password" onKeyUp="check(this)">
                                        <span id="User_Pass_Conf_error" name="error_text" style="color:red;font-size:small"></span>
                                    </div>
                                </div>
                                <div class="col-lg-3"></div>
                            </div>
                        </div>
                        <div id="ChPs_Texts" align="center" hidden>
                            <div id="chps_error_text" class="col-lg-12 alert alert-danger" hidden></div>
                            <div id="chps_success_text" class="col-lg-12 alert alert-success" hidden></div>
                        </div>
                    </div>
                    <div id="ChPs_Btns" class="modal-footer justify-content-between">
                        <button type="reset" class="btn btn-warning" onClick="dismisModal()">Cancel</button>
                        <button type="button" class="btn btn-primary" onClick="chpsBtn()">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /change password modal -->

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <!-- icon -->
    <script src="app/dist/js/icon.js"></script>
    <!-- jQuery -->
    <script src="app/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="app/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="app/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="app/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="app/dist/js/adminlte.min.js"></script>

    <script>
        var empId;
        var empNumber;
        var regExp = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;

        function dismisModal () {
            $("#chpsmodal").modal('hide');
        }

        function signIn () {

            empNumber = document.getElementById('usernumber').value;
            var password = document.getElementById('userpassword').value;

            $.ajax({
                type: 'POST',
                url: 'api/signInProcessor.php',
                data: {
                    method: 'signIn',
                    Emp_Number: empNumber,
                    User_Password: password
                },
                success: function(html) {
                    if (html.success == false) {
                        if (html.empId != null) {
                            empId = html.empId;
                            $('#errormsg').attr("hidden", true);
                            $('#chpsmodal').modal('show');
                            $('.chps-modal-loader').attr("hidden", true);
                        } else {
                            $('#errormsg').attr("hidden", false);
                            var text = document.getElementById("errormsg");
                            text.textContent = html.msg;
                        }
                    } else {
                        window.location.replace(html.location);
                    }
                },
                dataType: "json"
            });
        }

        function chpsBtn () {
            var User_Pass = document.getElementById("User_Pass").value;
            var User_Pass_Conf = document.getElementById("User_Pass_Conf").value;

            var Status_User_Pass = false;
            var Status_User_Pass_Conf = false;

            if (User_Pass == '' || User_Pass.trim() == '') {
                Status_User_Pass = true;
                $("#User_Pass").addClass("is-invalid");
                var text = document.getElementById("User_Pass_error");
                text.textContent = "New password must not be empty!";
            } else if (User_Pass.length > 0 && User_Pass.length < 6) {
                Status_User_Pass = true;
                $("#User_Pass").addClass("is-invalid");
                var text = document.getElementById("User_Pass_error");
                text.textContent = "At least 6 or more characters!";
            } else if (!User_Pass.match(regExp)) {
                Status_User_Pass = true;
                $("#User_Pass").addClass("is-invalid");
                var text = document.getElementById("User_Pass_error");
                text.textContent = "At least 1 upper letter, 1 lower letter, 1 number and 1 special character!";
            } else {
                Status_User_Pass = false;
                $("#User_Pass").removeClass("is-invalid");
                var text = document.getElementById("User_Pass_error");
                text.textContent = "";
            }

            if (User_Pass_Conf == '' || User_Pass_Conf.trim() == '') {
                Status_User_Pass_Conf = true;
                $("#User_Pass_Conf").addClass("is-invalid");
                var text = document.getElementById("User_Pass_Conf_error");
                text.textContent = "Confirm password must not be empty!";
            } else if (User_Pass_Conf != User_Pass) {
                Status_User_Pass_Conf = true;
                $("#User_Pass_Conf").addClass("is-invalid");
                var text = document.getElementById("User_Pass_Conf_error");
                text.textContent = "Password not match!";
            } else {
                Status_User_Pass_Conf = false;
                $("#User_Pass_Conf").removeClass("is-invalid");
                text = document.getElementById("User_Pass_Conf_error");
                text.textContent = "";
            }

            if (Status_User_Pass == true || Status_User_Pass_Conf == true) {
                return;
            } else {
                $('.chps-modal-loader').removeAttr("hidden");
                $('#chpsForm').attr("hidden", true);
                $.ajax({
                    type: 'POST',
                    url: 'api/headerProcessor.php',
                    data: {
                        method : 'changePassword',
                        empId : empId,
                        empNumber : empNumber,
                        userPass : User_Pass
                    },
                    success: function(html) {
                        $('.chps-modal-loader').attr("hidden", true);
                        $('#chpsForm').removeAttr("hidden");
                        $('#ChPs_Btns').attr("hidden", true);
                        $('#ChPs_Texts').attr("hidden", false);
                        $('#ChPs_Inputs').attr("hidden", true);
                        if (html.success == true) {
                            $('#chps_success_text').attr("hidden", false);
                            var text = document.getElementById("chps_success_text");
                            text.textContent = html.msg;
                        } else {
                            $('#chps_error_text').attr("hidden", false);
                            var text = document.getElementById("chps_error_text");
                            text.textContent = html.msg;
                        }
                        window.setTimeout("location.href='api/main.php'", 1000);
                    },
                    dataType: "json"
                });
            }
        }

        function check (element) {
            var inputId = element.id;
            var inputValue = element.value;

            if (inputId == "User_Pass") {
            if (inputValue == '' || inputValue.trim() == '') {
                $("#User_Pass").addClass("is-invalid");
                var text = document.getElementById("User_Pass_error");
                text.textContent = "New password must not be empty!";
            } else if (inputValue.length > 0 && inputValue.length < 6) {
                $("#User_Pass").addClass("is-invalid");
                var text = document.getElementById("User_Pass_error");
                text.textContent = "At least 6 or more characters!";
            } else if (!inputValue.match(regExp)) {
                $("#User_Pass").addClass("is-invalid");
                var text = document.getElementById("User_Pass_error");
                text.textContent = "At least 1 upper letter, 1 lower letter, 1 number and 1 special character!";
            } else {
                $("#User_Pass").removeClass("is-invalid");
                var text = document.getElementById("User_Pass_error");
                text.textContent = "";
            }
            } else {
            var User_Pass = document.getElementById("User_Pass").value;
            if (inputValue == '' || inputValue.trim() == '') {
                $("#User_Pass_Conf").addClass("is-invalid");
                var text = document.getElementById("User_Pass_Conf_error");
                text.textContent = "Confirm password must not be empty!";
            } else if (inputValue != User_Pass) {
                $("#User_Pass_Conf").addClass("is-invalid");
                var text = document.getElementById("User_Pass_Conf_error");
                text.textContent = "Password not match!";
            } else {
                $("#User_Pass_Conf").removeClass("is-invalid");
                text = document.getElementById("User_Pass_Conf_error");
                text.textContent = "";
            }
            }
        }

        $(document).keyup(function(e) {
            if (e.key === "Escape") {
                $("#chpsmodal").modal('hide');
            }
        });

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

        $(document).keypress(function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                if ($('#chpsmodal').is(':visible')) {
                    chpsBtn();
                } else {
                    signIn();
                }
            }
        });
    </script>
</body>
</html>
