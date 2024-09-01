<?php
    require_once "common.php";
    pdoConnect();

    // completed
    if ($_POST['method'] == 'load') {

        $sql = "SELECT Inc_Id, CONCAT(Emp_Fnm, ' ', Emp_Lnm) AS Created_By_User_Name, Dep_Desc, Prob_Desc, Cl_Desc, Evaluation_Loss, Caused_Date, Caused_Time, Ist_Desc
                FROM Incident I, Employee E, Problem P, Department D, CriticalLevel CL, IncidentStatus IST
                WHERE I.Created_By_Emp_Id = E.Emp_Id AND P.Dep_Id = D.Dep_Id AND I.Prob_Id = P.Prob_Id AND I.Cl_Id = CL.Cl_Id AND I.Inc_Status = IST.Ist_Id AND Inc_Status = ?";

        $resultIncident = pdoFetch($pdo, $sql, array(1), "", true);

        $activeIncidentsList = "";

        // display incidents list
        if ($resultIncident) {

            foreach ($resultIncident as $row) {

                $sql = "SELECT CONCAT(Emp_Fnm, ' ', Emp_Lnm) AS Caused_By_Emp_Name
                        FROM IncidentCausedBy ICB, Employee E
                        WHERE ICB.Emp_Id = E.Emp_Id AND Inc_Id = ?";

                $resultCausedByEmployee = pdoFetch($pdo, $sql, array($row["Inc_Id"]), "", true);

                $CausedBy = '';

                $CausedNames = [];

                foreach ($resultCausedByEmployee as $value) {

                    if ($value['Caused_By_Emp_Name'] == "Test Test") {

                        $CausedBy = "-----";

                    } else {

                        array_push($CausedNames, $value['Caused_By_Emp_Name']);

                        $CausedBy = implode(" - ", $CausedNames);

                    }

                }

                $activeIncidentsList = $activeIncidentsList . ' <tr>
                                                                    <td><b>' . $row["Inc_Id"] . '</b></td>
                                                                    <td>' . $row["Created_By_User_Name"] . '</td>
                                                                    <td>' . $row["Dep_Desc"] . '</td>
                                                                    <td>' . $row["Prob_Desc"] . '</td>
                                                                    <td>' . $row["Cl_Desc"] . '</td>
                                                                    <td style="color:red; font-weight:bold;">' . number_format($row["Evaluation_Loss"]) . '</td>
                                                                    <td>' . $CausedBy . '</td>
                                                                    <td>' . $row["Caused_Date"] . ' ' . $row["Caused_Time"] . '</td>
                                                                    <td style="color:red; font-weight:bold;"><b>' . $row["Ist_Desc"] . '</b></td>
                                                                </tr>';

            }

        }

        $response = json_encode(array('activeIncidentsList' => $activeIncidentsList));

    }

    echo $response;
?>