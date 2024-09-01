<?php
ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

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

    <!-- Back To Top -->
    <link rel="stylesheet" href="../app/dist/css/backToTop.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../app/dist/img/SLCB.png" alt="Logo" width="200" height="200">
        </div>

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
                            <h1 class="m-0">Home</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-exclamation"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Incidents</span>
                                    <span id="incidents" class="info-box-number"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-cog"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Active</span>
                                    <span id="activeIncidents" class="info-box-number"></span>
                                </div>
                            </div>
                        </div>

                        <!-- fix for small devices only -->
                        <div class="clearfix hidden-md-up"></div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-thumbs-up"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Solved</span>
                                    <span id="solvedIncidents" class="info-box-number"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-times"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Cancelled</span>
                                    <span id="cancelledIncidents" class="info-box-number"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="far fa-chart-bar"></i>
                                        Monthly Incidents Tracking
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div id="monthly-bar-chart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card card-success card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="far fa-chart-bar"></i>
                                        Yearly Incidents Tracking
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div id="yearly-bar-chart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- check modal -->
                <div class="modal fade" id="checkmodal">
                    <div class="modal-dialog">
                        <div class="modal-content callout callout-danger">
                            <div class="modal-header">
                                <h4 class="modal-title">Active Incidents</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row" id="text" style="font-weight:bold; font-size:20px;"></div>
                            </div>
                            <div id="Check_Btns" class="modal-footer justify-content-between">
                                <button class="btn btn-danger" data-dismiss="modal">Ignore</button>
                                <button type="button" class="btn btn-success" onClick="goToActiveIncidentsList()">Check Them</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /check model -->

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
    <!-- FLOT CHARTS -->
    <script src="../app/plugins/flot/jquery.flot.js"></script>
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
    <script src="../app/plugins/flot/plugins/jquery.flot.resize.js"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
    <script src="../app/plugins/flot/plugins/jquery.flot.pie.js"></script>

    <!-- Back To Top -->
    <script src="../app/dist/js/backToTop.js"></script>

    <script>

        var incidents;
        var activeIncidents;
        var cancelledIncidents;
        var solvedIncidents;

        // completed
        function goToActiveIncidentsList() {
            window.location.href = 'activeIncidentList.php';
        }

        // completed
        $(function() {

            $('.maintag').addClass("active");

            $.ajax({
                type: 'POST',
                url: 'mainProcessor.php',
                data: {
                    method: 'load',
                },
                success: function(html) {

                    var incidents = document.getElementById("incidents");
                    incidents.innerHTML = html.incidents;

                    var activeIncidents = document.getElementById("activeIncidents");
                    activeIncidents.innerHTML = html.active;

                    var solvedIncidents = document.getElementById("solvedIncidents");
                    solvedIncidents.innerHTML = html.solved;

                    var cancelledIncidents = document.getElementById("cancelledIncidents");
                    cancelledIncidents.innerHTML = html.cancelled;

                    const monthly = html.monthly;
                    const yearly = html.yearly;
                    const years = html.years;

                    if (html.success == true) {

                        $('#checkmodal').modal('show');
                        var text = document.getElementById("text");

                        if (html.active == 1) {

                            text.innerHTML = `You have&nbsp;<div style='color:red;'>${html.active}</div>&nbsp;active problem!!`;

                        } else {

                            text.innerHTML = `You have&nbsp;<div style='color:red;'>${html.active}</div>&nbsp;active problems!!`;

                        }

                    }

                    var monthly_bar_data = {
                        data: [[1, monthly[0]], [2, monthly[1]], [3, monthly[2]], [4, monthly[3]], [5, monthly[4]], [6, monthly[5]], [7, monthly[6]], [8, monthly[7]], [9, monthly[8]], [10, monthly[9]], [11, monthly[10]], [12, monthly[11]]],
                        bars: { show: true }
                    }
                    $.plot('#monthly-bar-chart', [monthly_bar_data], {
                        grid: {
                            borderWidth: 1,
                            borderColor: '#f3f3f3',
                            tickColor: '#f3f3f3'
                        },
                        series: {
                            bars: {
                            show: true, barWidth: 0.5, align: 'center',
                            },
                        },
                        colors: ['#007bff'],
                        xaxis: {
                            ticks: [[1, 'Jan'], [2, 'Feb'], [3, 'Mar'], [4, 'Apr'], [5, 'May'], [6, 'Jun'], [7, 'Jul'], [8, 'Aug'], [9, 'Sep'], [10, 'Oct'], [11, 'Nov'], [12, 'Dec']]
                        }
                    });

                    var yearly_bar_data = {
                        data: [[1, yearly[0]], [2, yearly[1]], [3, yearly[2]], [4, yearly[3]], [5, yearly[4]]],
                        bars: { show: true }
                    }
                    $.plot('#yearly-bar-chart', [yearly_bar_data], {
                        grid: {
                            borderWidth: 1,
                            borderColor: '#f3f3f3',
                            tickColor: '#f3f3f3'
                        },
                        series: {
                            bars: {
                            show: true, barWidth: 0.5, align: 'center',
                            },
                        },
                        colors: ['#28a745'],
                        xaxis: {
                            ticks: [[1, JSON.stringify(years[0])], [2, JSON.stringify(years[1])], [3, JSON.stringify(years[2])], [4, JSON.stringify(years[3])], [5, JSON.stringify(years[4])]]
                        }
                    });
                    
                },
                dataType: "json"
            });
        });

    </script>
</body>

</html>