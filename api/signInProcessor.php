<?php
    require_once "common.php";
    pdoConnect();

    if ($_POST['method'] == 'signIn') {

        if (empty($_POST['Emp_Number']) || empty($_POST['User_Password'])) {

            $response = json_encode(array('success' => false, 'msg' => 'Empty User Number and/or Password'));
            echo $response;
            exit;
        }
        
        $Emp_Number = !empty($_POST['Emp_Number']) ? trim($_POST['Emp_Number']) : null;
        $User_Password = !empty($_POST['User_Password']) ? trim($_POST['User_Password']) : null;
    
        $sql = "SELECT E.Emp_Id, CONCAT(Emp_Fnm, ' ', Emp_Lnm) AS Emp_Nm, User_Level, User_CanAdd, User_CanEdit, User_CanDelete, User_IsReseted
                FROM User U, Employee E
                WHERE U.Emp_Id = E.Emp_Id AND Emp_Number = ? AND User_Password = SHA2(?, 512) AND Emp_IsUser = ? AND Emp_IsActive = ?";

        $resultSet = pdoFetch($pdo, $sql, array($Emp_Number, $User_Password, true, true), "");
    
        if (!$resultSet) {

            $response = json_encode(array('success' => false, 'msg' => 'Incorrect User Number and/or Password'));
            echo $response;
            exit;

        }

        if ($resultSet['User_IsReseted'] == 1) {

            $response = json_encode(array('success' => false, 'empId' => $resultSet['Emp_Id']));
            echo $response;
            exit;

        }

        //
        // set the session variables
        //
        $_SESSION['empId'] = $resultSet['Emp_Id'];
        $_SESSION['empNumber'] = $Emp_Number;
        $_SESSION['empNm'] = $resultSet['Emp_Nm'];
        $_SESSION['userLevel'] = $resultSet['User_Level'];
        $_SESSION['canAdd'] = $resultSet['User_CanAdd'];
        $_SESSION['canEdit'] = $resultSet['User_CanEdit'];
        $_SESSION['canDelete'] = $resultSet['User_CanDelete'];

        $response = json_encode(array('success' => true, 'location' => 'api/main.php'));
            
    }

    // elseif ($_POST['method'] == 'load') {

    //     $currentSessionId = session_id();
    
    //     $sql = "SELECT S_Id
    //             FROM SessionsIds
    //             WHERE S_Id = ?";

    //     $resultSet = pdoFetch($pdo, $sql, array($currentSessionId), "");

    //     $status = 'new';
    
    //     if ($resultSet) {

    //         $old_sessionid = session_id();
    //         $new_sessionid = session_regenerate_id();
    //         // session_id();

    //         $currentSessionId = session_id();
    //         $status = 'not new';

    //     }

    //     $sql = 'INSERT INTO SessionsIds(S_Id)
    //             VALUES (?)';

    //     $result = pdoExecute2($pdo, $sql, array($currentSessionId));

    //     $response = json_encode(array('status' => $status, 'id' => session_id()));
            
    // }

    echo $response;
?>