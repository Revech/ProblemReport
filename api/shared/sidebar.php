<?php
    require_once "./common.php";

    if ($_SESSION['canAdd'] == false && $_SESSION['canEdit'] == false && $_SESSION['canDelete'] == false) {

        $canSee = 'hidden';

    } else {

        $canSee = '';
    }

    if ($_SESSION['canAdd'] == true) {

        $canAdd = '';

    } else {

        $canAdd = 'hidden';
    }
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="./main.php" class="brand-link">
        <img src="../app/dist/img/liu_logo.png" alt="LIU Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">LIU</span>
    </a>

    <div class="sidebar">

        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="./main.php" class="nav-link maintag">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Home</p>
                    </a>
                </li>

                <li class="nav-header">Incident Section</li>
                <li class="nav-item incident">
                    <a href="#" class="nav-link incidenttag">
                        <i class="fas fa-exclamation-triangle nav-icon"></i>
                        <p>
                            Incident
                            <i class="right fas fa-angle-left"></i>
                            <span class="badge badge-primary right bdg"></span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item" <?php echo $canAdd;?>>
                            <a href="./incident.php" class="nav-link newincident">
                                <i class="fas fa-angle-right nav-icon"></i>
                                <p>
                                    New Incident
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./incidentList.php" class="nav-link incidentslist">
                                <i class="fas fa-angle-right nav-icon"></i>
                                <p>
                                    Incidents List
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./activeIncidentList.php" class="nav-link activeincidentslist">
                                <i class="fas fa-angle-right nav-icon"></i>
                                <p>
                                    Active Incidents
                                    <span class="badge badge-danger right bdg"></span>
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item STag" <?php echo $canAdd;?>>
                    <a href="./incidentSolution.php" class="nav-link solution">
                        <i class="fas fa-lightbulb nav-icon"></i>
                        <p>
                            Solution
                        </p>
                    </a>
                </li>

                <?php
                    if (isAdmin()) {
                ?>
                    <li class="nav-header" <?php echo $canSee;?>>Data Tables Section</li>
                    <li class="nav-item data" <?php echo $canSee;?>>
                        <a href="#" class="nav-link datatag">
                            <i class="nav-icon fas fa-database"></i>
                            <p>
                                Data Tables
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="./problem.php" class="nav-link problemlist">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>
                                        Problems
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="solution.php" class="nav-link solutionlist">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>
                                        Solutions
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="./employee.php" class="nav-link employeelist">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>
                                        Employees
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="./department.php" class="nav-link departmentlist">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>
                                        Departments
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="./job.php" class="nav-link joblist">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>
                                        Jobs
                                    </p>
                                </a>
                            </li>
                                <li class="nav-item">
                                    <a href="./user.php" class="nav-link userlist">
                                        <i class="fas fa-angle-right nav-icon"></i>
                                        <p>
                                            Users
                                        </p>
                                    </a>
                                </li>
                        </ul>
                    </li>
                <?php
                    }
                ?>

                <!-- <li class="nav-header">Questions Section</li>
                <li class="nav-item">
                    <a href="./faq.php" class="nav-link questiontag">
                        <i class="nav-icon fas fa-question"></i>
                        <p>FAQ</p>
                    </a>
                </li> -->

            </ul>
        </nav>

    </div>

</aside>

<aside class="control-sidebar control-sidebar-dark"></aside>

<script>

    // completed
    $(function() {

        $.ajax({
            type: 'POST',
            url: './mainProcessor.php',
            data: {
                method: 'load',
            },
            success: function(html) {

                if (html.success == true) {

                    var active = document.querySelectorAll('.bdg');

                    for (element of active) {
                        element.innerHTML = html.active;
                    }

                } else {

                    $('.STag').attr('hidden', true);

                }

            },
            dataType: "json"
        });
    });

</script>
