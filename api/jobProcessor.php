<?php
    require_once "common.php";
    pdoConnect();

    // completed
    if ($_POST['method'] == 'add') {

        $Job_Id = pdoNextValue($pdo, "Job", "Job_Id");

        if ($Job_Id == null) {

            $Job_Id = 1;

        }

        $Job_Desc = trim($_POST['Job_Desc']);
        $Dep_Id = $_POST['Dep_Id'];

        $sql = 'INSERT INTO Job(Job_Id, Job_Desc, Dep_Id)
                VALUES (?, ?, ?)';

        $result = pdoExecute2($pdo, $sql, array($Job_Id, $Job_Desc, $Dep_Id));

        if (!$result) {

            $response = json_encode(array('success' => false, 'msg' => 'Wrong credintials. Please try again'));

        }   else {

            $response = json_encode(array('success' => true, 'msg' => 'Job added successfully'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'edit') {

        $Job_Id = $_POST['Job_Id'];
        $Job_Desc = trim($_POST['Job_Desc']);
        $Dep_Id = $_POST['Dep_Id'];

        if (isset($_POST['Job_IsActive']) && $_POST['Job_IsActive'] == "on") {

            $Job_IsActive = true;

        } else {

            $Job_IsActive = false;

        }

        $sql = 'UPDATE Job
                SET Job_Desc = ?, Dep_Id = ?, Job_IsActive = ?
                WHERE Job_Id = ?';

        $result = pdoExecute2($pdo, $sql, array($Job_Desc, $Dep_Id, $Job_IsActive, $Job_Id));

        if (!$result) {

            $response = json_encode(array('success' => false, 'msg' => 'Wrong credintials. Please try again'));

        }   else {

            $response = json_encode(array('success' => true, 'msg' => 'Successfully edited'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'delete') {

        $Job_Id = $_POST["Job_Id"];

        $sql = "DELETE FROM Job
                WHERE Job_Id = ?";

        $resultSet = pdoExecute2($pdo, $sql, array($Job_Id));

        if ($resultSet) {

            $response = json_encode(array('success' => true, 'msg' => 'Job deleted successfully'));

        } else {

            $response = json_encode(array('success' => false, 'msg' => 'Can not delete'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'load') {

        $sql = "SELECT Job_Id, Job_Desc, Dep_Desc, Job_IsActive
                FROM Department D, Job J
                WHERE D.Dep_Id = J.Dep_Id AND J.Job_Id != ?";

        $resultJob = pdoFetch($pdo, $sql, array(0), "", true);

        $jobList = "";

        // display job list
        if ($resultJob) {

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

            foreach ($resultJob as $row) {

                if ($row["Job_IsActive"] == true) {

                    $row["Job_IsActive"] = '<i class="fas fa-check"></i>';

                } else {

                    $row["Job_IsActive"] = '<i class="fas fa-times"></i>';

                }

                $jobList = $jobList . ' <tr>
                                            <td>' . $row["Job_Desc"] . '</td>
                                            <td>' . $row["Dep_Desc"] . '</td>
                                            <td>' . $row["Job_IsActive"] . '</td>
                                            <td>
                                                <button value="' . $row["Job_Id"] . '" class="btn btn-info" title="edit" onClick="editRow(this)" ' . $canEdit . '>
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button value="' . $row["Job_Id"] . '" class="btn btn-danger" title="delete" onClick="deleteRow(this)" ' . $canDelete . '>
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>';

            }

        }

        $response = json_encode(array('jobList' => $jobList));

    }

    // completed
    elseif ($_POST['method'] == 'addModal') {

        $sql = "SELECT Dep_Id, Dep_Desc
                FROM Department
                WHERE Dep_IsActive = ?";

        $resultDepartment = pdoFetch($pdo, $sql, array(true), "", true);

        // display solution list
        if ($resultDepartment) {

            $DepartmentList = '<option value="" selected disabled>Choose A department</option>';

            foreach ($resultDepartment as $row) {

                $DepartmentList = $DepartmentList . '<option value="' . $row['Dep_Id'] . '">' . $row['Dep_Desc'] . '</option>';

            }

        } else {

            $DepartmentList = '<option value="" selected disabled>No Data</option>';

        }

        $response = json_encode(array('department' => $DepartmentList));

    }

    // completed
    elseif ($_POST['method'] == 'editModal') {

        $Job_Id = $_POST['Job_Id'];

        $sql = 'SELECT Job_Desc, Dep_Id, Job_IsActive
                FROM Job
                WHERE Job_Id = ?';

        $resultSetJob = pdoFetch($pdo, $sql, array($Job_Id), '');

        $sql = "SELECT Dep_Id, Dep_Desc
                FROM Department
                WHERE Dep_IsActive = ?";

        $resultDepartment = pdoFetch($pdo, $sql, array(true), "", true);

        // display department list
        if ($resultDepartment) {

            $departmentList = '<option value="" selected disabled>Choose A Department</option>';

            foreach ($resultDepartment as $row) {

                $selected = "";
                if ($row['Dep_Id'] == $resultSetJob['Dep_Id']) $selected = "selected";

                $departmentList = $departmentList . '<option value="' . $row['Dep_Id'] . '" ' . $selected . '>' . $row['Dep_Desc'] . '</option>';

            }

        } else {

            $departmentList = '<option value="" selected disabled>No Data</option>';

        }

        $response = json_encode(array('job' => $resultSetJob, 'department' => $departmentList));

    }

    // completed
    elseif ($_POST['method'] == 'deleteModal') {

        $Job_Id = $_POST['Job_Id'];

        $sql = 'SELECT Job_Desc
                FROM Job
                WHERE Job_Id = ?';

        $resultSet = pdoFetch($pdo, $sql, array($Job_Id), '');

        $response = json_encode(array('success' => true, 'job' => $resultSet['Job_Desc']));

    }

    echo $response;
?>
