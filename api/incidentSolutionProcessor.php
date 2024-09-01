<?php
    require_once "common.php";
    pdoConnect();

    // completed
    if ($_POST['method'] == 'add') {

        $Emp_Id = $_SESSION['empId'];
        $Inc_Id = $_POST['Inc_Id'];
        $Solved_Cost = $_POST['Solved_Cost'];
        $SD = $_POST['SD'];
        $ST = $_POST['ST'];
        $Emp_Numbers = $_POST['Emp_Numbers'];

        if (isset($_POST['Sol_Id_Check']) && $_POST['Sol_Id_Check'] == "on") {

            $Sol_Id = $_POST['Sol_Id'];

        } elseif (isset($_POST['New_Sol_Desc_Check']) && $_POST['New_Sol_Desc_Check'] == "on") {

            $Sol_Id = pdoNextValue($pdo, "Solution", "Sol_Id");
            $Sol_Desc = trim($_POST['New_Sol_Desc']);

            $sql = "SELECT Prob_Id
                    FROM Incident
                    WHERE Inc_Id = ?";

            $resultProb = pdoFetch($pdo, $sql, array($Inc_Id), "");

            $sql = 'INSERT INTO Solution(Sol_Id, Prob_Id, Sol_Desc)
                    VALUES (?, ?, ?)';

            $result = pdoExecute2($pdo, $sql, array($Sol_Id, $resultProb['Prob_Id'], $Sol_Desc));

        }

        $sql = 'UPDATE Incident
                SET Sol_Id = ?, Updated_By_Emp_Id = ?, Solved_Cost = ?, Solved_Date = ?, Solved_Time = ?, Inc_Status = ?
                WHERE Inc_Id = ?';

        $result = pdoExecute2($pdo, $sql, array($Sol_Id, $Emp_Id, $Solved_Cost, $SD, $ST, 3, $Inc_Id));

        foreach ($Emp_Numbers as $row) {

            $sql = 'INSERT INTO IncidentSolvedBy(Inc_Id, Emp_Id)
                    VALUES (?, ?)';

            $result = pdoExecute2($pdo, $sql, array($Inc_Id, $row));

        }

        if (!$result) {

            $response = json_encode(array('success' => false, 'msg' => 'Wrong credintials. Please try again'));

        }   else {

            $response = json_encode(array('success' => true, 'msg' => 'Incident solved successfully'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'load') {

        $sql = "SELECT DISTINCT(Inc_Id), I.Prob_Id, Prob_Desc
                FROM Incident I, Problem P
                WHERE I.Prob_Id = P.Prob_Id And Inc_Status = ?";

        $resultIncident = pdoFetch($pdo, $sql, array(1), "", true);

        // display incident list
        if ($resultIncident) {

            $incidentList = '<option value="" selected disabled>Choose An Incident</option>';

            foreach ($resultIncident as $row) {

                $incidentList = $incidentList . '<option value="' . $row['Inc_Id'] . '">' . $row['Inc_Id'] . ' | ' . $row['Prob_Desc'] . '</option>';

            }

        } else {

            $incidentList = '<option value="" selected disabled>No Data</option>';

        }

        $sql = "SELECT Emp_Id, CONCAT(Emp_Fnm, ' ', Emp_Lnm) AS Emp_Name
                FROM Employee
                WHERE Emp_Id != ? AND Emp_IsActive = ?";

        $resultEmployee = pdoFetch($pdo, $sql, array(0, true), "", true);

        $count = 0;

        // display employee list
        if ($resultEmployee) {

            $employeeList = '<option value="" selected disabled>Choose An Employee</option>';

            foreach ($resultEmployee as $row) {

                $count++;

                $employeeList = $employeeList . '<option value="' . $row['Emp_Id'] . '">' . $row['Emp_Name'] . '</option>';

            }

        } else {

            $employeeList = '<option value="" selected disabled>No Data</option>';

        }

        $solutionList = '<option value="" selected disabled>No Data</option>';

        $response = json_encode(array('incident' => $incidentList, 'solution' => $solutionList, 'employee' => $employeeList, 'countEmployee' => $count));

    }

    // completed
    elseif ($_POST['method'] == 'addModal') {

        $sql = "SELECT Prob_Id, Prob_Desc
                FROM Problem
                WHERE Prob_IsActive = ?";

        $resultProblem = pdoFetch($pdo, $sql, array(true), "", true);

        // display solution list
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

    // completed
    elseif ($_POST['method'] == 'onChooseIncident') {

        $Inc_Id = $_POST['Inc_Id'];

        $sql = "SELECT Prob_Id, Caused_Date
                FROM Incident
                WHERE Inc_Id = ?";

        $resultProblem = pdoFetch($pdo, $sql, array($Inc_Id), "");

        $sql = "SELECT Sol_Id, Sol_Desc
                FROM Solution
                WHERE Prob_Id = ? AND Sol_IsActive = ?";

        $resultSolution = pdoFetch($pdo, $sql, array($resultProblem['Prob_Id'], true), "", true);

        // display solution list
        if ($resultSolution) {

            $solutionList = '<option value="" selected disabled>Choose A Solution</option>';

            foreach ($resultSolution as $row) {

                $solutionList = $solutionList . '<option value="' . $row['Sol_Id'] . '">' . $row['Sol_Desc'] . '</option>';

            }

        } else {

            $solutionList = '<option value="" selected disabled>No Data</option>';

        }

        $response = json_encode(array('solution' => $solutionList, 'causedDate' => $resultProblem['Caused_Date']));

    }

    echo $response;
?>
