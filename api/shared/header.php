<?php
ob_start();
require_once "./common.php";
if (!isSignedIn()) {
  header('Location: ../index.php');
  exit;
}

$empId = $_SESSION['empId'];
$empNumber = $_SESSION['empNumber'];
$userName = $_SESSION['empNm'];
$level = $_SESSION['userLevel'];

if ($level == 1) {
  $level = "Administrator";
} elseif ($level == 2) {
  $level = "Manager";
} else {
  $level = "User";
}
?>

<!-- Loader -->
<link rel="stylesheet" href="./../app/dist/css/loader.css">




<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="./main.php" class="nav-link">Home</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="./signOutProcessor.php" class="nav-link">Sign Out</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">

    <!-- User -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#" style="font-weight: bold;">
        <?php echo $userName; ?>
      </a>
      <div class="dropdown-menu dropdown-menu-lg card-widget widget-user-2 dropdown-menu-center">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="widget-user-header bg-success">
          <div class="widget-user-image">
            <img class="img-circle elevation-2" src="../app/dist/img/user.png" alt="User Avatar">
          </div>
          <h3 class="widget-user-username"><?php echo $userName; ?></h3>
          <h5 class="widget-user-desc"><?php echo $level; ?></h5>
        </div>
        <div class="card-footer p-0">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a href="#" class="nav-link" onClick="chpsUser()">
                <i class="fas fa-key mr-2"></i> Change password
              </a>
            </li>
            <div class="dropdown-divider"></div>
            <li class="nav-item" align="center">
              <a href="./signOutProcessor.php" class="nav-link">Sign Out</a>
            </li>
          </ul>
        </div>
      </div>
    </li>
    <!-- /.user -->

    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>

  </ul>
</nav>
<!-- /.navbar -->




<!-- change password modal -->
<div class="modal fade" id="chpsmodal">
  <div class="modal-dialog">
    <div class="modal-content callout callout-info">
      <div class="modal-header">
        <h4 class="modal-title">Change Password</h4>
        <button id="chps_modal_close_btn" type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- Preloader -->
      <div class="chps-modal-loader"></div>

      <form id="chpsForm" autocomplete="off">
        <div class="modal-body">
          <div id="ChPs_Inputs">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label>New Password</label>
                  <input id="User_Pass" type="text" class="form-control" placeholder="Enter new password" onKeyUp="check(this)">
                  <span id="User_Pass_error" name="error_text" style="color:red;font-size:small"></span>
                </div>
              </div>
              <div class="col-lg-6"></div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Confirm Password</label>
                  <input id="User_Pass_Conf" type="text" class="form-control" placeholder="Confirm password" onKeyUp="check(this)">
                  <span id="User_Pass_Conf_error" name="error_text" style="color:red;font-size:small"></span>
                </div>
              </div>
              <div class="col-lg-6"></div>
            </div>
          </div>
          <div id="ChPs_Texts" align="center" hidden>
            <div id="chps_error_text" class="col-lg-12 alert alert-danger" hidden></div>
            <div id="chps_success_text" class="col-lg-12 alert alert-success" hidden></div>
          </div>
        </div>
        <div id="ChPs_Btns" class="modal-footer justify-content-between">
          <button type="reset" class="btn btn-warning">Cancel</button>
          <button type="button" class="btn btn-primary" onClick="chpsBtn()">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /change password modal -->

<!-- jQuery -->
<script src="../app/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../app/plugins/jquery-ui/jquery-ui.min.js"></script>

<script>
  var regExp = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;

  function chpsUser() {
    $('#chpsmodal').modal('show');
    $('.chps-modal-loader').attr("hidden", true);
  }

  function chpsBtn() {
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
        $('#chps_modal_close_btn').attr("hidden", true);
        $('.chps-modal-loader').removeAttr("hidden");
        $('#chpsForm').attr("hidden", true);
        $.ajax({
          type: 'POST',
          url: 'headerProcessor.php',
          data: {
            method : 'changePassword', 
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
            window.setTimeout(
              "$('#chpsmodal').modal('hide')", 
              1000);
          },
          dataType: "json"
        });
    }
  }

  function check(element) {
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
      $("#newusermodal").modal('hide');
      $("#editusermodal").modal('hide');
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
      if ($('#newusermodal').is(':visible')) {
        addBtn();
      } else if ($('#editusermodal').is(':visible')) {
        editBtn();
      } else if ($('#chpsmodal').is(':visible')) {
        chpsBtn();
      }
    }
  });
</script>