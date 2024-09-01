<?php
    require_once "common.php";
    pdoConnect();

    // completed
    if ($_POST['method'] == 'add') {

        $Emp_Id = $_SESSION['empId'];
        $Inc_Id = pdoNextValue($pdo, "Incident", "Inc_Id");

        if ($Inc_Id == null) {

            $Inc_Id = 1;

        }

        $Dep_Id = $_POST['Dep_Id'];
        $Evaluation_Loss = $_POST['Evaluation_Loss'];
        $Cl_Id = $_POST['Cl_Id'];
        $CD = $_POST['CD'];
        $CT = $_POST['CT'];
        $Emp_Numbers = $_POST['Emp_Numbers'];

        if (isset($_POST['Prob_Id_Check']) && $_POST['Prob_Id_Check'] == "on") {

            $Prob_Id = $_POST['Prob_Id'];

        } elseif (isset($_POST['New_Prob_Desc_Check']) && $_POST['New_Prob_Desc_Check'] == "on") {

            $Prob_Id = pdoNextValue($pdo, "Problem", "Prob_Id");
            $Prob_Desc = trim($_POST['New_Prob_Desc']);

            $sql = 'INSERT INTO Problem(Prob_Id, Prob_Desc, Dep_Id)
                    VALUES (?, ?, ?)';

            $result = pdoExecute2($pdo, $sql, array($Prob_Id, $Prob_Desc, $Dep_Id));

        }

        $sql = 'INSERT INTO Incident(Inc_Id, Created_By_Emp_Id, Prob_Id, Sol_Id, Cl_Id, Evaluation_Loss, Caused_Date, Caused_Time, Updated_By_Emp_Id, Inc_Status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $result = pdoExecute2($pdo, $sql, array($Inc_Id, $Emp_Id, $Prob_Id, 0, $Cl_Id, $Evaluation_Loss, $CD, $CT, 0, 1));

        foreach ($Emp_Numbers as $row) {

            $sql = 'INSERT INTO IncidentCausedBy(Inc_Id, Emp_Id)
                    VALUES (?, ?)';

            $result = pdoExecute2($pdo, $sql, array($Inc_Id, $row));

        }

        if (!$result) {

            $response = json_encode(array('success' => false, 'msg' => 'Wrong credintials. Please try again'));

        }   else {

            $response = json_encode(array('success' => true, 'msg' => 'Incident added successfully'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'load') {

        $sql = "SELECT Dep_Id, Dep_Desc
                FROM Department
                WHERE Dep_IsActive = ?";

        $resultDepartment = pdoFetch($pdo, $sql, array(true), "", true);

        // display department list
        if ($resultDepartment) {

            $departmentList = '<option value="" selected disabled>Choose A Department</option>';

            foreach ($resultDepartment as $row) {

                $departmentList = $departmentList . '<option value="' . $row['Dep_Id'] . '">' . $row['Dep_Desc'] . '</option>';

            }

        } else {

            $departmentList = '<option value="" selected disabled>No Data</option>';

        }

        $sql = "SELECT Cl_Id, Cl_Desc
                FROM CriticalLevel
                WHERE Cl_IsActive = ?";

        $resultCritical = pdoFetch($pdo, $sql, array(true), "", true);

        // display critical list
        if ($resultCritical) {

            $criticalList = '<option value="" selected disabled>Choose A Critical Level</option>';

            foreach ($resultCritical as $row) {

                $criticalList = $criticalList . '<option value="' . $row['Cl_Id'] . '">' . $row['Cl_Desc'] . '</option>';

            }

        } else {

            $criticalList = '<option value="" selected disabled>No Data</option>';

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

        $problemList = '<option value="" selected disabled>No Data</option>';

        $response = json_encode(array('department' => $departmentList, 'problem' => $problemList, 'critical' => $criticalList, 'employee' => $employeeList, 'countEmployee' => $count));

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
