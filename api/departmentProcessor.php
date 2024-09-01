<?php
    require_once "common.php";
    pdoConnect();

    // completed
    if ($_POST['method'] == 'add') {

        $Dep_Id = pdoNextValue($pdo, "Department", "Dep_Id");

        if ($Dep_Id == null) {

            $Dep_Id = 1;

        }

        $Dep_Desc = trim($_POST['Dep_Desc']);

        $sql = 'INSERT INTO Department(Dep_Id, Dep_Desc)
                VALUES (?, ?)';

        $result = pdoExecute2($pdo, $sql, array($Dep_Id, $Dep_Desc));

        if (!$result) {

            $response = json_encode(array('success' => false, 'msg' => 'Wrong credintials. Please try again'));

        }   else {

            $response = json_encode(array('success' => true, 'msg' => 'Department added successfully'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'edit') {

        $Dep_Id = $_POST['Dep_Id'];
        $Dep_Desc = trim($_POST['Dep_Desc']);

        if (isset($_POST['Dep_IsActive']) && $_POST['Dep_IsActive'] == "on") {

            $Dep_IsActive = true;

        } else {

            $Dep_IsActive = false;

        }

        $sql = 'UPDATE Department
                SET Dep_Desc = ?, Dep_IsActive = ?
                WHERE Dep_Id = ?';

        $result = pdoExecute2($pdo, $sql, array($Dep_Desc, $Dep_IsActive, $Dep_Id));

        if (!$result) {

            $response = json_encode(array('success' => false, 'msg' => 'Wrong credintials. Please try again'));

        }   else {

            $response = json_encode(array('success' => true, 'msg' => 'Successfully edited'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'delete') {

        $Dep_Id = $_POST["Dep_Id"];

        $sql = "DELETE FROM Department
                WHERE Dep_Id = ?";

        $resultSet = pdoExecute2($pdo, $sql, array($Dep_Id));

        if ($resultSet) {

            $response = json_encode(array('success' => true, 'msg' => 'Department deleted successfully'));

        } else {

            $response = json_encode(array('success' => false, 'msg' => 'Can not delete'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'load') {

        $sql = "SELECT Dep_Id, Dep_Desc, Dep_IsActive
                FROM Department
                WHERE Dep_Id != ?";

        $resultDepartment = pdoFetch($pdo, $sql, array(0), "", true);

        $departmentList = "";

        // display department list
        if ($resultDepartment) {

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

            foreach ($resultDepartment as $row) {

                if ($row["Dep_IsActive"] == true) {

                    $row["Dep_IsActive"] = '<i class="fas fa-check"></i>';

                } else {

                    $row["Dep_IsActive"] = '<i class="fas fa-times"></i>';

                }

                $departmentList = $departmentList . '   <tr>
                                                            <td>' . $row["Dep_Desc"] . '</td>
                                                            <td>' . $row["Dep_IsActive"] . '</td>
                                                            <td>
                                                                <button value="' . $row["Dep_Id"] . '" class="btn btn-info" title="edit" onClick="editRow(this)" ' . $canEdit . '>
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button value="' . $row["Dep_Id"] . '" class="btn btn-danger" title="delete" onClick="deleteRow(this)" ' . $canDelete . '>
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </td>
                                                        </tr>';

            }

        }

        $response = json_encode(array('departmentList' => $departmentList));

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
    elseif ($_POST['method'] == 'editModal') {

        $Dep_Id = $_POST['Dep_Id'];

        $sql = "SELECT Dep_Desc, Dep_IsActive
                FROM Department
                WHERE Dep_Id = ?";

        $resultDepartment = pdoFetch($pdo, $sql, array($Dep_Id), "");

        $response = json_encode(array('department' => $resultDepartment));

    }

    // completed
    elseif ($_POST['method'] == 'deleteModal') {

        $Dep_Id = $_POST['Dep_Id'];

        $sql = 'SELECT Dep_Desc
                FROM Department
                WHERE Dep_Id = ?';

        $resultSet = pdoFetch($pdo, $sql, array($Dep_Id), '');

        $response = json_encode(array('success' => true, 'department' => $resultSet['Dep_Desc']));

    }

    echo $response;
?>
