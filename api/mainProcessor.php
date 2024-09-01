<?php
    require_once "common.php";
    pdoConnect();

    // completed
    if ($_POST['method'] == 'load') {

        $sql = "SELECT COUNT(Inc_Id) AS incidents
                FROM Incident";

        $incidents = pdoFetch($pdo, $sql, null, ""); // all incidents

        $sql = "SELECT COUNT(Inc_Id) AS activeIncidents
                FROM Incident
                WHERE Inc_Status = ?";

        $active = pdoFetch($pdo, $sql, array(1), ""); // active incidents

        $sql = "SELECT COUNT(Inc_Id) AS cancelledIncidents
                FROM Incident
                WHERE Inc_Status = ?";

        $cancelled = pdoFetch($pdo, $sql, array(2), ""); // cancelled incidents

        $sql = "SELECT COUNT(Inc_Id) AS solvedIncidents
                FROM Incident
                WHERE Inc_Status = ?";

        $solved = pdoFetch($pdo, $sql, array(3), ""); // solved incidents

        // display active
        if ($active['activeIncidents'] > 0) {

            $status = true;

        } else {

            $status = false;
        }

        $months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $monthlyIncidents = [];
        $i = 0;

        foreach ($months as $value) {

            $sql = "SELECT COUNT(Inc_Id) AS monthlyIncidents 
                    FROM Incident 
                    WHERE MONTH(Caused_Date) = $value";
            
            $monthlyIncidents[$i] = pdoFetch2($pdo, $sql, "");
            $i++;

        }

        // get current year
        $year = Date('Y');
        // $y = parse_str($year);
        // get 4 years before
        // $fourYearsBefore = $year - 4;
        $years = [2022, 2023, 2024, 2025, 2026];
        // for ($i = 0; $i < 5; $i++) {

        //     array_push($years, $y);
        //     $y++;

        // }

        $yearlyIncidents = [];
        $i = 0;

        foreach ($years as $value) {

            $sql = "SELECT COUNT(Inc_Id) AS yearlyIncidents 
                    FROM Incident 
                    WHERE YEAR(Caused_Date) = $value";
            
            $yearlyIncidents[$i] = pdoFetch2($pdo, $sql, "");
            $i++;

        }

        $response = json_encode(array('success' => $status, 'incidents' => number_format($incidents['incidents']), 'active' => number_format($active['activeIncidents']), 
                                      'cancelled' => number_format($cancelled['cancelledIncidents']), 'solved' => number_format($solved['solvedIncidents']), 
                                      'monthly' => $monthlyIncidents, 'yearly' => $yearlyIncidents, 'years' => $years));

    }

    echo $response;

?>