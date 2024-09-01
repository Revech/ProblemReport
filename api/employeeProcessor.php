<?php
    require_once "common.php";
    pdoConnect();

    // completed
    if ($_POST['method'] == 'add') {

        $Emp_Id = pdoNextValue($pdo, "Employee", "Emp_Id");

        if ($Emp_Id == null) {

            $Emp_Id = 1;

        }

        $Emp_Number = trim($_POST['Emp_Number']);
        $Emp_Fnm = trim($_POST['Emp_Fnm']);
        $Emp_Lnm = trim($_POST['Emp_Lnm']);
        $Dep_Id = $_POST['Dep_Id'];
        $Job_Id = $_POST['Job_Id'];
        $Emp_Extension = trim($_POST['Emp_Extension']);
        $Emp_Email = trim($_POST['Emp_Email']);
        $Emp_CreatedDate = Date('Y-m-d');

        $sql = 'INSERT INTO Employee(Emp_Id, Emp_Number, Dep_Id, Job_Id, Emp_Fnm, Emp_Lnm, Emp_Extension, Emp_Email, Emp_CreatedDate)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $result = pdoExecute2($pdo, $sql, array($Emp_Id, $Emp_Number, $Dep_Id, $Job_Id, $Emp_Fnm, $Emp_Lnm, $Emp_Extension, $Emp_Email, $Emp_CreatedDate));

        if (!$result) {

            $response = json_encode(array('success' => false, 'msg' => 'Wrong credintials. Please try again'));

        }   else {

            if ($_POST['User_Password'] != null) {

                $User_Password = $_POST['User_Password'];
                $User_Level = $_POST['User_Level'];

                if (isset($_POST['User_CanAdd']) && $_POST['User_CanAdd'] == "on") {

                    $User_CanAdd = true;

                } else {

                    $User_CanAdd = false;

                }

                if (isset($_POST['User_CanEdit']) && $_POST['User_CanEdit'] == "on") {

                    $User_CanEdit = true;

                } else {

                    $User_CanEdit = false;

                }

                if (isset($_POST['User_CanDelete']) && $_POST['User_CanDelete'] == "on") {

                    $User_CanDelete = true;

                } else {

                    $User_CanDelete = false;

                }

                $sql = 'INSERT INTO User(Emp_Id, User_Password, User_Level, User_CanAdd, User_CanEdit, User_CanDelete)
                        VALUES (?, SHA2(?, 512), ?, ?, ?, ?)';

                $result = pdoExecute2($pdo, $sql, array($Emp_Id, $User_Password, $User_Level, $User_CanAdd, $User_CanEdit, $User_CanDelete));

                if (!$result) {

                    $response = json_encode(array('success' => false, 'msg' => 'Wrong user credintials. Please try again'));

                }   else {

                    $sql = 'UPDATE Employee
                            SET Emp_IsUser = ?
                            WHERE Emp_Id = ?';

                    $result = pdoExecute2($pdo, $sql, array(true, $Emp_Id));

                    if (!$result) {

                        $response = json_encode(array('success' => false, 'msg' => 'Wrong user credintials. Please try again'));

                    }   else {

                        $response = json_encode(array('success' => true, 'msg' => 'Successfully added as user'));

                    }

                }

            } else {

                $response = json_encode(array('success' => true, 'msg' => 'Successfully added as employee'));

            }

        }

    }

    // completed
    elseif ($_POST['method'] == 'edit') {

        $Emp_Id = $_POST['Emp_Id'];

        $sql = 'SELECT Emp_IsUser
                FROM Employee
                WHERE Emp_Id = ?';

        $resultSetIsUser = pdoFetch($pdo, $sql, array($Emp_Id), '');

        $Emp_Number = trim($_POST['Emp_Number']);
        $Emp_Fnm = trim($_POST['Emp_Fnm']);
        $Emp_Lnm = trim($_POST['Emp_Lnm']);
        $Dep_Id = $_POST['Dep_Id'];
        $Job_Id = $_POST['Job_Id'];
        $Emp_Extension = trim($_POST['Emp_Extension']);
        $Emp_Email = trim($_POST['Emp_Email']);

        if (isset($_POST['Emp_IsActive']) && $_POST['Emp_IsActive'] == "on") {

            $Emp_IsActive = true;

        } else {

            $Emp_IsActive = false;

        }

        $sql = 'UPDATE Employee
                SET Emp_Number = ?, Dep_Id = ?, Job_Id = ?, Emp_Fnm = ?, Emp_Lnm = ?, Emp_Extension = ?, Emp_Email = ?, Emp_IsActive = ?
                WHERE Emp_Id = ?';

        $result = pdoExecute2($pdo, $sql, array($Emp_Number, $Dep_Id, $Job_Id, $Emp_Fnm, $Emp_Lnm, $Emp_Extension, $Emp_Email, $Emp_IsActive, $Emp_Id));

        if (!$result) {

            $response = json_encode(array('success' => false, 'msg' => 'Wrong credintials. Please try again'));

        }   else {

            if (isset($_POST['User_CanAdd']) && $_POST['User_CanAdd'] == "on") {

                $User_CanAdd = true;

            } else {

                $User_CanAdd = false;

            }

            if (isset($_POST['User_CanEdit']) && $_POST['User_CanEdit'] == "on") {

                $User_CanEdit = true;

            } else {

                $User_CanEdit = false;

            }

            if (isset($_POST['User_CanDelete']) && $_POST['User_CanDelete'] == "on") {

                $User_CanDelete = true;

            } else {

                $User_CanDelete = false;

            }

            if ($resultSetIsUser['Emp_IsUser'] == true) {

                $User_Level = $_POST['User_Level'];
                $User_Password = $_POST['User_Password'];

                if ($_POST['User_Password'] != null) {

                    $sql = 'UPDATE User
                            SET User_Level = ?, User_Password = SHA2(?, 512), User_CanAdd = ?, User_CanEdit = ?, User_CanDelete = ?
                            WHERE Emp_Id = ?';

                    $result = pdoExecute2($pdo, $sql, array($User_Level, $User_Password, $User_CanAdd, $User_CanEdit, $User_CanDelete, $Emp_Id));

                    if (!$result) {

                        $response = json_encode(array('success' => false, 'msg' => 'Wrong user credintials. Please try again'));

                    } else {

                        $response = json_encode(array('success' => true, 'msg' => 'Successfully edited as user'));

                    }

                } else {

                    $sql = 'UPDATE User
                            SET User_Level = ?, User_CanAdd = ?, User_CanEdit = ?, User_CanDelete = ?
                            WHERE Emp_Id = ?';

                    $result = pdoExecute2($pdo, $sql, array($User_Level, $User_CanAdd, $User_CanEdit, $User_CanDelete, $Emp_Id));

                    if (!$result) {

                        $response = json_encode(array('success' => false, 'msg' => 'Wrong user credintials. Please try again'));

                    } else {

                        $response = json_encode(array('success' => true, 'msg' => 'Successfully edited as user'));

                    }

                }

            } else {

                if ($_POST['User_Password'] != null) {

                    $sql = 'INSERT INTO User(Emp_Id, User_Password, User_Level, User_CanAdd, User_CanEdit, User_CanDelete)
                            VALUES (?, SHA2(?, 512), ?, ?, ?, ?)';

                    $result = pdoExecute2($pdo, $sql, array($Emp_Id, $User_Password, $User_Level, $User_CanAdd, $User_CanEdit, $User_CanDelete));

                    if (!$result) {

                        $response = json_encode(array('success' => false, 'msg' => 'Wrong user credintials. Please try again'));

                    }   else {

                        $sql = 'UPDATE Employee
                                SET Emp_IsUser = ?
                                WHERE Emp_Id = ?';

                        $result = pdoExecute2($pdo, $sql, array(true, $Emp_Id));

                        if (!$result) {

                            $response = json_encode(array('success' => false, 'msg' => 'Wrong user credintials. Please try again'));

                        }   else {

                            $response = json_encode(array('success' => true, 'msg' => 'Successfully edited as user'));

                        }

                    }

                }   else {

                    $response = json_encode(array('success' => true, 'msg' => 'Successfully edited as employee'));

                }

            }

        }

    }

    // completed
    elseif ($_POST['method'] == 'delete') {

        $Emp_Id = $_POST["Emp_Id"];

        $sql = 'SELECT Emp_IsUser
                FROM Employee
                WHERE Emp_Id = ?';

        $resultSet = pdoFetch($pdo, $sql, array($Emp_Id), '');

        if ($resultSet) {

            if ($resultSet['Emp_IsUser'] == true) {

                $sql = "DELETE FROM User
                        WHERE Emp_Id = ?";

                $result = pdoExecute2($pdo, $sql, array($Emp_Id));

            }

            $sql = "DELETE FROM Employee
                    WHERE Emp_Id = ?";

            $result = pdoExecute2($pdo, $sql, array($Emp_Id));

            $response = json_encode(array('success' => true, 'msg' => 'Employee deleted successfully'));

        } else {

            $response = json_encode(array('success' => false, 'msg' => 'Can not delete'));

        }

    }

    // completed
    elseif ($_POST['method'] == 'load') {

        $sql = "SELECT Emp_Id, Emp_Number, Dep_Desc, Job_Desc, CONCAT(Emp_Fnm, ' ', Emp_Lnm) AS Emp_Name, Emp_Extension, Emp_Email, Emp_CreatedDate, Emp_IsUser, Emp_IsActive
                FROM Employee E, Department D, Job J
                WHERE E.Dep_Id = D.Dep_Id AND E.Job_Id = J.Job_Id AND E.Emp_Id != ?";

        $resultEmployee = pdoFetch($pdo, $sql, array(0), "", true);

        $employeeList = '';

        // display employee list
        if ($resultEmployee) {

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

            foreach ($resultEmployee as $row) {

                if ($row["Emp_IsActive"] == true) {

                    $row["Emp_IsActive"] = '<i class="fas fa-check"></i>';

                } else {

                    $row["Emp_IsActive"] = '<i class="fas fa-times"></i>';

                }

                if ($row["Emp_Id"] == $_SESSION['empId']) {

                    $employeeList = $employeeList . '   <tr>
                                                            <td><b>' . $row["Emp_Number"] . '</b></td>
                                                            <td>' . $row["Emp_Name"] . '</td>
                                                            <td>' . $row["Dep_Desc"] . '</td>
                                                            <td>' . $row["Job_Desc"] . '</td>
                                                            <td>' . $row["Emp_Extension"] . '</td>
                                                            <td>' . $row["Emp_Email"] . '</td>
                                                            <td>' . $row["Emp_CreatedDate"] . '</td>
                                                            <td>' . $row["Emp_IsActive"] . '</td>
                                                            <td></td>
                                                        </tr>';

                } else {

                    $employeeList = $employeeList . '   <tr>
                                                            <td><b>' . $row["Emp_Number"] . '</b></td>
                                                            <td>' . $row["Emp_Name"] . '</td>
                                                            <td>' . $row["Dep_Desc"] . '</td>
                                                            <td>' . $row["Job_Desc"] . '</td>
                                                            <td>' . $row["Emp_Extension"] . '</td>
                                                            <td>' . $row["Emp_Email"] . '</td>
                                                            <td>' . $row["Emp_CreatedDate"] . '</td>
                                                            <td>' . $row["Emp_IsActive"] . '</td>
                                                            <td>
                                                                <button value="' . $row["Emp_Id"] . '" class="btn btn-info" title="edit" onClick="editRow(this)" ' . $canEdit . '>
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button value="' . $row["Emp_Id"] . '" class="btn btn-danger" title="delete" onClick="deleteRow(this)" ' . $canDelete . '>
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </td>
                                                        </tr>';

                }

            }

        }

        $response = json_encode(array('employeeList' => $employeeList));

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

        // display job list
        $jobList = '<option value="" selected disabled>No Data</option>';

        $response = json_encode(array('department' => $departmentList, 'job' => $jobList));

    }

    // completed
    elseif ($_POST['method'] == 'editModal') {

        $Emp_Id = $_POST['Emp_Id'];

        $sql = 'SELECT Emp_Number, Dep_Id, Job_Id, Emp_Fnm, Emp_Lnm, Emp_Extension, Emp_Email, Emp_IsUser, Emp_IsActive
                FROM Employee
                WHERE Emp_Id = ?';

        $resultSetEmp = pdoFetch($pdo, $sql, array($Emp_Id), '');

        $sql = "SELECT Dep_Id, Dep_Desc
                FROM Department
                WHERE Dep_IsActive = ?";

        $resultDepartment = pdoFetch($pdo, $sql, array(true), "", true);

        // display department list
        if ($resultDepartment) {

            $departmentList = '<option value="" selected disabled>Choose A Department</option>';

            foreach ($resultDepartment as $row) {

                $selected = "";
                if ($row['Dep_Id'] == $resultSetEmp['Dep_Id']) $selected = "selected";

                $departmentList = $departmentList . '<option value="' . $row['Dep_Id'] . '" ' . $selected . '>' . $row['Dep_Desc'] . '</option>';

            }

        } else {

            $departmentList = '<option value="" selected disabled>No Data</option>';

        }

        $sql = "SELECT Job_Id, Job_Desc
                FROM Job
                WHERE Job_IsActive = ?";

        $resultJob = pdoFetch($pdo, $sql, array(true), "", true);

        // display job list
        if ($resultJob) {

            $jobList = '<option value="" selected disabled>Choose A Job</option>';

            foreach ($resultJob as $row) {

                $selected = "";
                if ($row['Job_Id'] == $resultSetEmp['Job_Id']) $selected = "selected";

                $jobList = $jobList . '<option value="' . $row['Job_Id'] . '" ' . $selected . '>' . $row['Job_Desc'] . '</option>';

            }

        } else {

            $jobList = '<option value="" selected disabled>No Data</option>';

        }

        if ($resultSetEmp['Emp_IsUser'] == true) {

            $sql = 'SELECT User_Level, User_CanAdd, User_CanEdit, User_CanDelete
                    FROM User
                    WHERE Emp_Id = ?';

            $resultSetUser = pdoFetch($pdo, $sql, array($Emp_Id), '');

            $response = json_encode(array('employee' => $resultSetEmp, 'department' => $departmentList, 'job' => $jobList, 'user' => $resultSetUser));

        } else {

            $response = json_encode(array('employee' => $resultSetEmp, 'department' => $departmentList, 'job' => $jobList));

        }

    }

    // completed
    elseif ($_POST['method'] == 'deleteModal') {

        $Emp_Id = $_POST['Emp_Id'];

        if ($Emp_Id == $_SESSION['empId']) {

            $response = json_encode(array('success' => false, 'msg' => 'Wrong action!'));
            echo $response;
            exit;
        }

        $sql = 'SELECT CONCAT(Emp_Fnm, " ", Emp_Lnm) AS Emp_Name
                FROM Employee
                WHERE Emp_Id = ?';

        $resultSet = pdoFetch($pdo, $sql, array($Emp_Id), '');

        $response = json_encode(array('success' => true, 'employee' => $resultSet['Emp_Name']));

    }

    // completed
    elseif ($_POST['method'] == 'getJob') {

        $Dep_Id = $_POST['depId'];

        $sql = "SELECT Job_Id, Job_Desc
                FROM Job
                WHERE Dep_Id = ? AND Job_IsActive = ?";

        $resultJob = pdoFetch($pdo, $sql, array($Dep_Id, true), "", true);

        // display job list
        if ($resultJob) {

            $jobList = '<option value="" selected disabled>Choose A Job</option>';

            foreach ($resultJob as $row) {

                $jobList = $jobList . '<option value="' . $row['Job_Id'] . '">' . $row['Job_Desc'] . '</option>';

            }

        } else {

            $jobList = '<option value="" selected disabled>No Data</option>';

        }

        $response = json_encode(array('job' => $jobList));

    }

    echo $response;
?>
