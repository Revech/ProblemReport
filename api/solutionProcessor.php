<?php
    require_once "common.php";
    pdoConnect();

    // completed
    if ($_POST['method'] == 'add') {

        $Sol_Id = pdoNextValue($pdo, "Solution", "Sol_Id");

        if ($Sol_Id == null) {

            $Sol_Id = 1;

        }

        $Sol_Desc = trim($_POST['Sol_Desc']);
        $Prob_Id = $_POST['Prob_Id'];

        $sql = 'INSERT INTO Solution(Sol_Id, Sol_Desc, Prob_Id)
                VALUES (?, ?, ?)';

        $result = pdoExecute2($pdo, $sql, array($Sol_Id, $Sol_Desc, $Prob_Id));

        if (!$result) {

            $response = json_encode(array('success' => false, 'msg' => 'Wrong credintials. Please try again'));

        }   else {

            $response = json_encode(array('success' => true, 'msg' => 'Solution added successfully'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'edit') {

        $Sol_Id = $_POST['Sol_Id'];
        $Sol_Desc = trim($_POST['Sol_Desc']);
        $Prob_Id = $_POST['Prob_Id'];

        if (isset($_POST['Sol_IsActive']) && $_POST['Sol_IsActive'] == "on") {

            $Sol_IsActive = true;

        } else {

            $Sol_IsActive = false;

        }

        $sql = 'UPDATE Solution
                SET Sol_Desc = ?, Prob_Id = ?, Sol_IsActive = ?
                WHERE Sol_Id = ?';

        $result = pdoExecute2($pdo, $sql, array($Sol_Desc, $Prob_Id, $Sol_IsActive, $Sol_Id));

        if (!$result) {

            $response = json_encode(array('success' => false, 'msg' => 'Wrong credintials. Please try again'));

        }   else {

            $response = json_encode(array('success' => true, 'msg' => 'Successfully edited'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'delete') {

        $Sol_Id = $_POST["Sol_Id"];

        $sql = "DELETE FROM Solution
                WHERE Sol_Id = ?";

        $resultSet = pdoExecute2($pdo, $sql, array($Sol_Id));

        if ($resultSet) {

            $response = json_encode(array('success' => true, 'msg' => 'Solution deleted successfully'));

        } else {

            $response = json_encode(array('success' => false, 'msg' => 'Can not delete'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'load') {

        $sql = "SELECT Sol_Id, Prob_Desc, Sol_Desc, Sol_IsActive
                FROM Problem P, Solution S
                WHERE P.Prob_Id = S.Prob_Id AND S.Sol_Id != ?";

        $resultSolution = pdoFetch($pdo, $sql, array(0), "", true);

        $solutionList = "";

        // display solution list
        if ($resultSolution) {

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

            foreach ($resultSolution as $row) {

                if ($row["Sol_IsActive"] == true) {

                    $row["Sol_IsActive"] = '<i class="fas fa-check"></i>';

                } else {

                    $row["Sol_IsActive"] = '<i class="fas fa-times"></i>';

                }

                $solutionList = $solutionList . ' <tr>
                                                      <td>' . $row["Sol_Desc"] . '</td>
                                                      <td>' . $row["Prob_Desc"] . '</td>
                                                      <td>' . $row["Sol_IsActive"] . '</td>
                                                      <td>
                                                        <button value="' . $row["Sol_Id"] . '" class="btn btn-info" title="edit" onClick="editRow(this)" ' . $canEdit . '>
                                                          <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button value="' . $row["Sol_Id"] . '" class="btn btn-danger" title="delete" onClick="deleteRow(this)" ' . $canDelete . '>
                                                          <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                      </td>
                                                  </tr>';

            }

        }

        $response = json_encode(array('solutionList' => $solutionList));

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

        $Sol_Id = $_POST['Sol_Id'];

        $sql = 'SELECT Sol_Desc, Prob_Id, Sol_IsActive
                FROM Solution
                WHERE Sol_Id = ?';

        $resultSetSol = pdoFetch($pdo, $sql, array($Sol_Id), '');

        $sql = "SELECT Prob_Id, Prob_Desc
                FROM Problem
                WHERE Prob_IsActive = ?";

        $resultProblem = pdoFetch($pdo, $sql, array(true), "", true);

        // display problem list
        if ($resultProblem) {

            $problemList = '<option value="" selected disabled>Choose A Problem</option>';

            foreach ($resultProblem as $row) {

                $selected = "";
                if ($row['Prob_Id'] == $resultSetSol['Prob_Id']) $selected = "selected";

                $problemList = $problemList . '<option value="' . $row['Prob_Id'] . '" ' . $selected . '>' . $row['Prob_Desc'] . '</option>';

            }

        } else {

            $problemList = '<option value="" selected disabled>No Data</option>';

        }

        $response = json_encode(array('solution' => $resultSetSol, 'problem' => $problemList));

    }

    echo $response;
?>
