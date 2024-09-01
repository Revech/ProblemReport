<?php
    require_once "common.php";
    pdoConnect();

    if ($_POST['method'] == 'changePassword') {

        if (isset($_SESSION['empId'])) {

            $Emp_Id = $_SESSION['empId'];

        } else {

            $Emp_Id = $_POST['empId'];
            $Emp_Number = $_POST['empNumber'];

            $sql = "SELECT CONCAT(Emp_Fnm, ' ', Emp_Lnm) AS Emp_Nm, User_Level, User_CanAdd, User_CanEdit, User_CanDelete
                    FROM User U, Employee E
                    WHERE U.Emp_Id = E.Emp_Id AND E.Emp_Id= ?";

            $resultSet = pdoFetch($pdo, $sql, array($Emp_Id), "");

            if (!$resultSet) {

                $response = json_encode(array('success' => false));
    
            } else {
    
                $response = json_encode(array('success' => true));

                $_SESSION['empId'] = $Emp_Id;
                $_SESSION['empNumber'] = $Emp_Number;
                $_SESSION['empNm'] = $resultSet['Emp_Nm'];
                $_SESSION['userLevel'] = $resultSet['User_Level'];
                $_SESSION['canAdd'] = $resultSet['User_CanAdd'];
                $_SESSION['canEdit'] = $resultSet['User_CanEdit'];
                $_SESSION['canDelete'] = $resultSet['User_CanDelete'];
    
            }

        }

        $userPassword = trim($_POST['userPass']);

        $sql = "UPDATE User
                SET User_Password = SHA2(?, 512), User_IsReseted = ?
                WHERE Emp_Id = ?";

        $resultSet = pdoExecute2($pdo, $sql, array($userPassword, false, $Emp_Id));

        if (!$resultSet) {

            $response = json_encode(array('success' => false, 'msg' => 'Internet connection failed, please try again later!'));

        } else {

            $response = json_encode(array('success' => true, 'msg' => 'Password updated successfully!'));

        }
    }

    echo $response;
?>