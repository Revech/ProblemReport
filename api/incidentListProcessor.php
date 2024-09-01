<?php
    require_once "common.php";
    pdoConnect();

    // completed
    if ($_POST['method'] == 'edit') {

        $Inc_Id = $_POST['Inc_Id'];
        $Prob_Id = $_POST['Prob_Id'];
        $Cl_Id = $_POST['Cl_Id'];
        $Evaluation_Loss = $_POST['Evaluation_Loss'];

        $sql = 'UPDATE Incident
                SET Prob_Id = ?, Cl_Id = ?, Evaluation_Loss = ?
                WHERE Inc_Id = ?';

        $result = pdoExecute2($pdo, $sql, array($Prob_Id, $Cl_Id, $Evaluation_Loss, $Inc_Id));

        if (!$result) {

            $response = json_encode(array('success' => false, 'msg' => 'Wrong credintials. Please try again'));

        }   else {

            $response = json_encode(array('success' => true, 'msg' => 'Successfully edited'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'cancel') {

        $Inc_Id = $_POST['Inc_Id'];

        $sql = 'UPDATE Incident
                SET Inc_Status = ?
                WHERE Inc_Id = ?';

        $result = pdoExecute2($pdo, $sql, array(2, $Inc_Id)); // Cancel

        if (!$result) {

            $response = json_encode(array('success' => false, 'msg' => 'Cant cancel this incident'));

        }   else {

            $response = json_encode(array('success' => true, 'msg' => 'Cancelled successfully'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'active') {

        $Inc_Id = $_POST['Inc_Id'];

        $sql = 'UPDATE Incident
                SET Inc_Status = ?
                WHERE Inc_Id = ?';

        $result = pdoExecute2($pdo, $sql, array(1, $Inc_Id)); // Active

        if (!$result) {

            $response = json_encode(array('success' => false, 'msg' => 'Cant active this incident'));

        }   else {

            $response = json_encode(array('success' => true, 'msg' => 'Activated successfully'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'load') {

        $sql = "SELECT Inc_Id, Dep_Desc, Prob_Desc, Sol_Desc, Cl_Desc, Evaluation_Loss, Caused_Date, Caused_Time, Solved_Cost, Solved_Date, Solved_Time, Ist_Desc
                FROM Incident I, Problem P, Solution S, Department D, CriticalLevel CL, IncidentStatus IST
                WHERE P.Dep_Id = D.Dep_Id AND I.Prob_Id = P.Prob_Id AND I.Sol_Id = S.Sol_Id AND I.Cl_Id = CL.Cl_Id AND I.Inc_Status = IST.Ist_Id";

        $resultIncident = pdoFetch($pdo, $sql, null, "", true);

        $incidentsList = "";

        // display incidents list
        if ($resultIncident) {

            if ($_SESSION['canEdit'] == true) {

                $canEdit = '';

            } else {

                $canEdit = 'hidden';
            }

            if ($_SESSION['canDelete'] == true) {

                $canDelete = '';

            } else {

                $canDelete = 'hidden';
            }

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

                $sql = "SELECT CONCAT(Emp_Fnm, ' ', Emp_Lnm) AS Solved_By_Emp_Name
                        FROM IncidentSolvedBy ISB, Employee E
                        WHERE ISB.Emp_Id = E.Emp_Id AND Inc_Id = ?";

                $resultSolvedByEmployee = pdoFetch($pdo, $sql, array($row["Inc_Id"]), "", true);

                $SolvedBy = '';

                $SolvedNames = [];

                foreach ($resultSolvedByEmployee as $value) {

                    if ($value['Solved_By_Emp_Name'] == "Test Test") {

                        $SolvedBy = "-----";

                    } else {

                        array_push($SolvedNames, $value['Solved_By_Emp_Name']);

                        $SolvedBy = implode(" - ", $SolvedNames);

                    }

                }

                $sql = "SELECT CONCAT(Emp_Fnm, ' ', Emp_Lnm) AS Created_By_User_Name
                        FROM Incident I, Employee E
                        WHERE I.Created_By_Emp_Id = E.Emp_Id AND Inc_Id = ?";

                $resultCraetedByUser = pdoFetch($pdo, $sql, array($row["Inc_Id"]), "");

                $sql = "SELECT CONCAT(Emp_Fnm, ' ', Emp_Lnm) AS Updated_By_User_Name
                        FROM Incident I, Employee E
                        WHERE I.Updated_By_Emp_Id = E.Emp_Id AND Inc_Id = ?";

                $resultUpdatedByUser = pdoFetch($pdo, $sql, array($row["Inc_Id"]), "");

                if ($row["Ist_Desc"] == "Solved") {

                    $incidentsList = $incidentsList . ' <tr>
                                                            <td><b>' . $row["Inc_Id"] . '</b></td>
                                                            <td>' . $resultCraetedByUser["Created_By_User_Name"] . '</td>
                                                            <td>' . $row["Dep_Desc"] . '</td>
                                                            <td>' . $row["Prob_Desc"] . '</td>
                                                            <td>' . $row["Sol_Desc"] . '</td>
                                                            <td>' . $row["Cl_Desc"] . '</td>
                                                            <td style="color:red; font-weight:bold;">' . number_format($row["Evaluation_Loss"]) . '</td>
                                                            <td>' . $CausedBy . '</td>
                                                            <td>' . $row["Caused_Date"] . ' ' . $row["Caused_Time"] . '</td>
                                                            <td>' . $resultUpdatedByUser["Updated_By_User_Name"] . '</td>
                                                            <td style="color:red; font-weight:bold;">' . number_format($row["Solved_Cost"]) . '</td>
                                                            <td>' . $SolvedBy . '</td>
                                                            <td>' . $row["Solved_Date"] . ' ' . $row["Solved_Time"] . '</td>
                                                            <td style="color:#28a745; font-weight:bold;"><b>' . $row["Ist_Desc"] . '</b></td>
                                                            <td></td>
                                                        </tr>';

                } elseif ($row["Ist_Desc"] == "Cancelled") {

                    $incidentsList = $incidentsList . ' <tr>
                                                            <td><b>' . $row["Inc_Id"] . '</b></td>
                                                            <td>' . $resultCraetedByUser["Created_By_User_Name"] . '</td>
                                                            <td>' . $row["Dep_Desc"] . '</td>
                                                            <td>' . $row["Prob_Desc"] . '</td>
                                                            <td>-----</td>
                                                            <td>' . $row["Cl_Desc"] . '</td>
                                                            <td style="color:red; font-weight:bold;">' . number_format($row["Evaluation_Loss"]) . '</td>
                                                            <td>' . $CausedBy . '</td>
                                                            <td>' . $row["Caused_Date"] . ' ' . $row["Caused_Time"] . '</td>
                                                            <td>-----</td>
                                                            <td>-----</td>
                                                            <td>-----</td>
                                                            <td>-----</td>
                                                            <td style="color:#17a2b8; font-weight:bold;"><b>' . $row["Ist_Desc"] . '</b></td>
                                                            <td>
                                                                <button value="' . $row["Inc_Id"] . '" class="btn btn-info" title="edit" onClick="activeRow(this)"' . $canEdit . '>
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                            </td>
                                                        </tr>';

                } elseif ($row["Ist_Desc"] == "Active") {

                    if ($row["Sol_Desc"] == "Test") {

                        $incidentsList = $incidentsList . ' <tr>
                                                                <td><b>' . $row["Inc_Id"] . '</b></td>
                                                                <td>' . $resultCraetedByUser["Created_By_User_Name"] . '</td>
                                                                <td>' . $row["Dep_Desc"] . '</td>
                                                                <td>' . $row["Prob_Desc"] . '</td>
                                                                <td>-----</td>
                                                                <td>' . $row["Cl_Desc"] . '</td>
                                                                <td style="color:red; font-weight:bold;">' . number_format($row["Evaluation_Loss"]) . '</td>
                                                                <td>' . $CausedBy . '</td>
                                                                <td>' . $row["Caused_Date"] . ' ' . $row["Caused_Time"] . '</td>
                                                                <td>-----</td>
                                                                <td>-----</td>
                                                                <td>-----</td>
                                                                <td>-----</td>
                                                                <td style="color:red; font-weight:bold;"><b>' . $row["Ist_Desc"] . '</b></td>
                                                                <td>
                                                                    <button value="' . $row["Inc_Id"] . '" class="btn btn-info" title="edit" onClick="editRow(this)"' . $canEdit . '>
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button value="' . $row["Inc_Id"] . '" class="btn btn-danger" title="cancel" onClick="cancelRow(this)"' . $canDelete . '>
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>';

                    } else {

                        $incidentsList = $incidentsList . ' <tr>
                                                                <td><b>' . $row["Inc_Id"] . '</b></td>
                                                                <td>' . $resultCraetedByUser["Created_By_User_Name"] . '</td>
                                                                <td>' . $row["Dep_Desc"] . '</td>
                                                                <td>' . $row["Prob_Desc"] . '</td>
                                                                <td>' . $row["Sol_Desc"] . '</td>
                                                                <td>' . $row["Cl_Desc"] . '</td>
                                                                <td style="color:red; font-weight:bold;">' . number_format($row["Evaluation_Loss"]) . '</td>
                                                                <td>' . $CausedBy . '</td>
                                                                <td>' . $row["Caused_Date"] . ' ' . $row["Caused_Time"] . '</td>
                                                                <td>' . $resultUpdatedByUser["Updated_By_User_Name"] . '</td>
                                                                <td style="color:red; font-weight:bold;">' . number_format($row["Solved_Cost"]) . '</td>
                                                                <td>' . $SolvedBy . '</td>
                                                                <td>' . $row["Solved_Date"] . ' ' . $row["Solved_Time"] . '</td>
                                                                <td style="color:red; font-weight:bold;"><b>' . $row["Ist_Desc"] . '</b></td>
                                                                <td>
                                                                    <button value="' . $row["Inc_Id"] . '" class="btn btn-info" title="edit" onClick="editRow(this)"' . $canEdit . '>
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button value="' . $row["Inc_Id"] . '" class="btn btn-danger" title="cancel" onClick="cancelRow(this)"' . $canDelete . '>
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>';

                    }

                }

            }

        }

        $response = json_encode(array('incidentsList' => $incidentsList));

    }

    // completed
    elseif ($_POST['method'] == 'editModal') {

        $Inc_Id = $_POST['Inc_Id'];

        $sql = 'SELECT Prob_Id, Cl_Id, Evaluation_Loss
                FROM Incident
                WHERE Inc_Id = ?';

        $resultSetInc = pdoFetch($pdo, $sql, array($Inc_Id), '');

        $sql = 'SELECT Dep_Id
                FROM Problem
                WHERE Prob_Id = ?';

        $resultSetProb = pdoFetch($pdo, $sql, array($resultSetInc['Prob_Id']), '');

        $sql = "SELECT Dep_Id, Dep_Desc
                FROM Department
                WHERE Dep_IsActive = ?";

        $resultDepartment = pdoFetch($pdo, $sql, array(true), "", true);

        // display department list
        if ($resultDepartment) {

            $departmentList = '<option value="" selected disabled>Choose A Department</option>';

            foreach ($resultDepartment as $row) {

                $selected = "";
                if ($row['Dep_Id'] == $resultSetProb['Dep_Id']) $selected = "selected";

                $departmentList = $departmentList . '<option value="' . $row['Dep_Id'] . '" ' . $selected . '>' . $row['Dep_Desc'] . '</option>';

            }

        } else {

            $departmentList = '<option value="" selected disabled>No Data</option>';

        }

        $sql = "SELECT Prob_Id, Prob_Desc
                FROM Problem
                WHERE Prob_IsActive = ?";

        $resultProblem = pdoFetch($pdo, $sql, array(true), "", true);

        // display problem list
        if ($resultProblem) {

            $problemList = '<option value="" selected disabled>Choose A Problem</option>';

            foreach ($resultProblem as $row) {

                $selected = "";
                if ($row['Prob_Id'] == $resultSetInc['Prob_Id']) $selected = "selected";

                $problemList = $problemList . '<option value="' . $row['Prob_Id'] . '" ' . $selected . '>' . $row['Prob_Desc'] . '</option>';

            }

        } else {

            $problemList = '<option value="" selected disabled>No Data</option>';

        }

        $sql = "SELECT Cl_Id, Cl_Desc
                FROM CriticalLevel
                WHERE Cl_IsActive = ?";

        $resultCritical = pdoFetch($pdo, $sql, array(true), "", true);

        // display critical list
        if ($resultCritical) {

            $criticalList = '<option value="" selected disabled>Choose A Critical Level</option>';

            foreach ($resultCritical as $row) {

                $selected = "";
                if ($row['Cl_Id'] == $resultSetInc['Cl_Id']) $selected = "selected";

                $criticalList = $criticalList . '<option value="' . $row['Cl_Id'] . '" ' . $selected . '>' . $row['Cl_Desc'] . '</option>';

            }

        } else {

            $criticalList = '<option value="" selected disabled>No Data</option>';

        }

       

        $response = json_encode(array('department' => $departmentList, 'problem' => $problemList, 'critical' => $criticalList, 'eval' => $resultSetInc['Evaluation_Loss']));

    }

    // completed
    elseif ($_POST['method'] == 'onChooseDepartment') {

        $Dep_Id = $_POST['Dep_Id'];

        $sql = "SELECT Prob_Id, Prob_Desc
                FROM Problem
                WHERE Dep_Id = ? AND Prob_IsActive = ?";

        $resultProblem = pdoFetch($pdo, $sql, array($Dep_Id, true), "", true);

        // display problem list
        if ($resultProblem) {

            $problemList = '<option value="" selected disabled>Choose A Problem</option>';

            foreach ($resultProblem as $row) {

                $problemList = $problemList . '<option value="' . $row['Prob_Id'] . '">' . $row['Prob_Desc'] . '</option>';

            }

        } else {

            $problemList = '<option value="" selected disabled>No Data</option>';

        }

        $response = json_encode(array('problem' => $problemList));

    }

    echo $response;
?>
