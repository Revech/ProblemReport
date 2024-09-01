<?php
    require_once "common.php";
    pdoConnect();

    // completed
    if ($_POST['method'] == 'load') {

        $sql = "SELECT E.Emp_Id, Emp_Number, CONCAT(Emp_Fnm, ' ', Emp_Lnm) AS Emp_Name, User_Level, User_CanAdd, User_CanEdit, User_CanDelete
                FROM Employee E, User U
                WHERE E.Emp_Id = U.Emp_Id AND E.Emp_Id != ?";

        $resultUser = pdoFetch($pdo, $sql, array(0), "", true);

        $userList = "";

        // display User list
        if ($resultUser) {

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

            foreach ($resultUser as $row) {

                if ($row["User_CanAdd"] == true) {

                    $row["User_CanAdd"] = '<i class="fas fa-check"></i>';

                } else {

                    $row["User_CanAdd"] = '<i class="fas fa-times"></i>';

                }

                if ($row["User_CanEdit"] == true) {

                    $row["User_CanEdit"] = '<i class="fas fa-check"></i>';

                } else {

                    $row["User_CanEdit"] = '<i class="fas fa-times"></i>';

                }

                if ($row["User_CanDelete"] == true) {

                    $row["User_CanDelete"] = '<i class="fas fa-check"></i>';

                } else {

                    $row["User_CanDelete"] = '<i class="fas fa-times"></i>';

                }

                if ($row["User_Level"] == 1) {

                    $row["User_Level"] = 'Administrator';

                } elseif ($row["User_Level"] == 2) {

                    $row["User_Level"] = 'Manager';

                } elseif ($row["User_Level"] == 3) {

                    $row["User_Level"] = 'User';

                }

                if ($row["Emp_Id"] == $_SESSION['empId']) {

                    $userList = $userList . '   <tr>
                                                    <td><b>' . $row["Emp_Number"] . '</b></td>
                                                    <td>' . $row["Emp_Name"] . '</td>
                                                    <td>' . $row["User_Level"] . '</td>
                                                    <td>' . $row["User_CanAdd"] . '</td>
                                                    <td>' . $row["User_CanEdit"] . '</td>
                                                    <td>' . $row["User_CanDelete"] . '</td>
                                                    <td></td>
                                                </tr>';

                } else {

                    $userList = $userList . '   <tr>
                                                    <td><b>' . $row["Emp_Number"] . '</b></td>
                                                    <td>' . $row["Emp_Name"] . '</td>
                                                    <td>' . $row["User_Level"] . '</td>
                                                    <td>' . $row["User_CanAdd"] . '</td>
                                                    <td>' . $row["User_CanEdit"] . '</td>
                                                    <td>' . $row["User_CanDelete"] . '</td>
                                                    <td>
                                                        <button value="' . $row["Emp_Id"] . '" class="btn btn-info" title="reset" onClick="resetRow(this)" ' . $canEdit . '>
                                                            <i class="fas fa-sync fa-spin"></i>
                                                        </button>
                                                        <button value="' . $row["Emp_Id"] . '" class="btn btn-danger" title="delete" onClick="deleteRow(this)" ' . $canDelete . '>
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>';

                }

            }

        }

        $response = json_encode(array('userList' => $userList));

    }

    // completed
    elseif ($_POST['method'] == 'reset') {

        $Emp_Id = $_POST["Emp_Id"];
        $User_Password = $_POST["User_Password"];

        $sql = 'UPDATE User
                SET User_Password = SHA2(?, 512), User_IsReseted = ?
                WHERE Emp_Id = ?';

        $result = pdoExecute2($pdo, $sql, array($User_Password, true, $Emp_Id));

        if (!$result) {

            $response = json_encode(array('success' => false, 'msg' => 'Error in reset password'));

        } else {

            $response = json_encode(array('success' => true, 'msg' => $User_Password));

        }

    }

    // completed
    elseif ($_POST['method'] == 'resetModal') {

        $Emp_Id = $_POST['Emp_Id'];

        $sql = 'SELECT CONCAT(Emp_Fnm, " ", Emp_Lnm) AS Emp_Name
                FROM Employee
                WHERE Emp_Id = ?';

        $resultSet = pdoFetch($pdo, $sql, array($Emp_Id), '');

        $response = json_encode(array('success' => true, 'user' => $resultSet['Emp_Name']));

    }

    // completed
    elseif ($_POST['method'] == 'delete') {

        $Emp_Id = $_POST["Emp_Id"];

        $sql = "DELETE FROM User
                WHERE Emp_Id = ?";

        $result = pdoExecute2($pdo, $sql, array($Emp_Id));

        $sql = 'UPDATE Employee
                SET Emp_IsUser = ?
                WHERE Emp_Id = ?';

        $result = pdoExecute2($pdo, $sql, array(false, $Emp_Id));

        if (!$result) {

            $response = json_encode(array('success' => false, 'msg' => 'Error in delete User'));

        } else {

            $response = json_encode(array('success' => true, 'msg' => 'User deleted successfully'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'deleteModal') {

        $Emp_Id = $_POST['Emp_Id'];

        $sql = 'SELECT CONCAT(Emp_Fnm, " ", Emp_Lnm) AS Emp_Name
                FROM Employee
                WHERE Emp_Id = ?';

        $resultSet = pdoFetch($pdo, $sql, array($Emp_Id), '');

        $response = json_encode(array('success' => true, 'user' => $resultSet['Emp_Name']));

    }

    echo $response;


?>
