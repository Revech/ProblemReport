<?php
    require_once "common.php";
    pdoConnect();

    // completed
    if ($_POST['method'] == 'add') {

        $Prob_Id = pdoNextValue($pdo, "Problem", "Prob_Id");

        if ($Prob_Id == null) {

            $Prob_Id = 1;

        }

        $Prob_Desc = trim($_POST['Prob_Desc']);
        $Dep_Id = $_POST['Dep_Id'];

        $sql = 'INSERT INTO Problem(Prob_Id, Prob_Desc, Dep_Id)
                VALUES (?, ?, ?)';

        $result = pdoExecute2($pdo, $sql, array($Prob_Id, $Prob_Desc, $Dep_Id));

        if (!$result) {

            $response = json_encode(array('success' => false, 'msg' => 'Wrong credintials. Please try again'));

        }   else {

            $response = json_encode(array('success' => true, 'msg' => 'Problem added successfully'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'edit') {

        $Prob_Id = $_POST['Prob_Id'];
        $Prob_Desc = trim($_POST['Prob_Desc']);
        $Dep_Id = $_POST['Dep_Id'];

        if (isset($_POST['Prob_IsActive']) && $_POST['Prob_IsActive'] == "on") {

            $Prob_IsActive = true;

        } else {

            $Prob_IsActive = false;

        }

        $sql = 'UPDATE Problem
                SET Prob_Desc = ?, Dep_Id = ?, Prob_IsActive = ?
                WHERE Prob_Id = ?';

        $result = pdoExecute2($pdo, $sql, array($Prob_Desc, $Dep_Id, $Prob_IsActive, $Prob_Id));

        if (!$result) {

            $response = json_encode(array('success' => false, 'msg' => 'Wrong credintials. Please try again'));

        }   else {

            $response = json_encode(array('success' => true, 'msg' => 'Successfully edited'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'delete') {

        $Prob_Id = $_POST["Prob_Id"];

        $sql = "DELETE FROM Problem
                WHERE Prob_Id = ?";

        $resultSet = pdoExecute2($pdo, $sql, array($Prob_Id));

        if ($resultSet) {

            $response = json_encode(array('success' => true, 'msg' => 'Problem deleted successfully'));

        } else {

            $response = json_encode(array('success' => false, 'msg' => 'Can not delete'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'load') {

        $sql = "SELECT Prob_Id, Prob_Desc, Dep_Desc, Prob_IsActive
                FROM Problem P, Department D
                WHERE P.Dep_Id = D.Dep_Id AND P.Prob_Id != ?";

        $resultProblem = pdoFetch($pdo, $sql, array(0), "", true);

        $problemList = "";

        // display problem list
        if ($resultProblem) {

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

            foreach ($resultProblem as $row) {

                if ($row["Prob_IsActive"] == true) {

                    $row["Prob_IsActive"] = '<i class="fas fa-check"></i>';

                } else {

                    $row["Prob_IsActive"] = '<i class="fas fa-times"></i>';

                }

                $problemList = $problemList . ' <tr>
                                                    <td>' . $row["Prob_Desc"] . '</td>
                                                    <td>' . $row["Dep_Desc"] . '</td>
                                                    <td>
                                                          <i class="fas fa-check"></i>
                                                    </td>
                                                    <td>
                                                        <button value="' . $row["Prob_Id"] . '" class="btn btn-info" title="edit" onClick="editRow(this)" ' . $canEdit . '>
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button value="' . $row["Prob_Id"] . '" class="btn btn-danger" title="delete" onClick="deleteRow(this)" ' . $canDelete . '>
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>';

            }

        }

        $response = json_encode(array('problemList' => $problemList));

    }

    // completed
    elseif ($_POST['method'] == 'addModal') {

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

        $response = json_encode(array('department' => $departmentList));

    }

    // completed
    elseif ($_POST['method'] == 'editModal') {

        $Prob_Id = $_POST['Prob_Id'];

        $sql = 'SELECT Prob_Desc, Dep_Id, Prob_IsActive
                FROM Problem
                WHERE Prob_Id = ?';

        $resultSetProb = pdoFetch($pdo, $sql, array($Prob_Id), '');

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

        $response = json_encode(array('problem' => $resultSetProb, 'department' => $departmentList));

    }

    echo $response;
?>
