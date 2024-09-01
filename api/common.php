<?php
session_start();

define("USER_NAME", "root");

define("USER_PASSWORD", '');

define("DB_NAME", "incident_db");

define("SERVER_NAME", "localhost");

//---------------------------------------------------------------------------
// displayError
//
// display an error message in the document
//---------------------------------------------------------------------------
function displayError($errorText) {
	print("<h3 class = 'ErrorMessage'> $errorText </h3>");
} // end displayError

//---------------------------------------------------------------------------
// stop
//
// display an error message in the document, and then stop the program execution
//---------------------------------------------------------------------------
function stop($errorText) {
	displayError($errorText);
	die;	// <==> exit
} // end stop

//------------------------------------------------------------------------------
// isSignedIn
//
// return true if there is a currently signed in user and false otherwise
//------------------------------------------------------------------------------
function isSignedIn() {
	if (isset($_SESSION['empId']))
		return true;
	else
		return false;
} // end isSignedIn

//------------------------------------------------------------------------------
// isAdmin
//
// return true if the currently signed in user is an admin and false otherwise
//------------------------------------------------------------------------------
function isAdmin() {
	if (!isSignedIn()) return false;

	if ($_SESSION['userLevel'] == 1)
		return true;
	else
		return false;
} // end isAdmin

//------------------------------------------------------------------------------
// isManager
//
// return true if the currently signed in user is a manager and false otherwise
//------------------------------------------------------------------------------
function isManager() {
	if (!isSignedIn()) return false;

	if ($_SESSION['userLevel'] == 2)
		return true;
	else
		return false;
} // end isManager

//---------------------------------------------------------------------------
// randomString
//
// randomly generate a string of characters
//
// Note: the $stringLength function parameter/argument is optional and
//		 it defaults to 12. That means if randomString function is called
//		 with no parameters/arguments, a string of 12 characters will be
//		 randomly generated.
//
// return $randomString
//---------------------------------------------------------------------------
function randomString($stringLength = 12) {
	$characters = "_0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $randomString = "";

    for ($i = 0; $i < $stringLength; $i++)
        $randomString .= $characters[rand(0, strlen($characters) - 1)];

    return $randomString;
} // end randomString

//--------------------------------------------------------------------------------
// pdoConnect
//
// connect to MySQL database server using PDO and then open the specified database
//
// Notes: 1. this function will set the global variable $pdo
//
//		  2. set the character set to UTF-8 for Arabic support
// 			 UTF stands for Unicode Transformation Format
// 			 Unicode is a character set. UTF-8 is encoding.
//
//		  3. By default, set the time zone of the REMOTE server to Asia/Beirut
//---------------------------------------------------------------------------------
function pdoConnect() {
	// global variable declaration
	global $pdo;

	//
	// using PDO, connect to MySQL database server & open the specified DB
	//
	try {
		$pdo = new PDO("mysql:host=" . SERVER_NAME . ";dbname=" . DB_NAME . ";charset=utf8",
					   USER_NAME, USER_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
	catch(PDOException $e) {
		stop("<br />DB Error: Could not connect to MySQL database server! <br /><br />" .
				$e->getMessage());
    }
} // end pdoConnect

//---------------------------------------------------------------------------
// pdoExecute
//
// execute a SQL query (insert, update, or delete) and handle exception
//
// Note: Don't use this function to send sql select. Use pdoFetch instead.
//---------------------------------------------------------------------------
function pdoExecute($pdo, $sqlQuery, $arrayData, $errorText, $showDBServerError = true) {
	try {
		$stmt = $pdo->prepare($sqlQuery);
		$stmt->execute($arrayData);
	}
	catch (Exception $e) {
		$msg = "<h3 class='ErrorMessage'>Error: $errorText</h3>";
		if ($showDBServerError) $msg .= "<h3 class='ErrorMessage'>" . $e->getMessage() . "</h3>";
		stop($msg);
	}
} // end pdoExecute

//---------------------------------------------------------------------------
// pdoExecute2
//
// execute a SQL query (insert, update, or delete) and handle exception
//
// return true if execution successful and false otherwise
//
// Notes: 1. Don't use this function to send sql select. Use pdoFetch instead.
//        2. The error handling should be done in the calling function/script
//---------------------------------------------------------------------------
function pdoExecute2($pdo, $sqlQuery, $arrayData) {
	try {
		$stmt = $pdo->prepare($sqlQuery);
		$stmt->execute($arrayData);
		return true;
	}
	catch (Exception $e) {
		return false;
	}
} // end pdoExecute2

//---------------------------------------------------------------------------
// pdoFetch
//
// execute a SQL SELECT and handle exception
//
/// return 0, 1, or many rows
//---------------------------------------------------------------------------
function pdoFetch($pdo, $sqlQuery, $arrayData, $errorText, $fetchAll = false) {
	try {
		$stmt = $pdo->prepare($sqlQuery);
		$stmt->execute($arrayData);

		if ($fetchAll) $resultSet = $stmt->fetchAll();
		else $resultSet = $stmt->fetch();
		return $resultSet;
	}
	catch (Exception $e) {
		stop("<h3 class='ErrorMessage'>Error: $errorText</h3><h3 class='ErrorMessage'>" .
			 $e->getMessage() . "</h3>");
	}
} // end pdoFetch

//---------------------------------------------------------------------------
// pdoFetch2
//
// execute a SQL SELECT without parameters and handle exception
//
/// return 1 value
//---------------------------------------------------------------------------
function pdoFetch2($pdo, $sqlQuery, $errorText) {
	try {
		$stmt = $pdo->query($sqlQuery);
		$resultSet = $stmt->fetch();
		return $resultSet[0];
	}
	catch (Exception $e) {
		stop("<h3 class='ErrorMessage'>Error: $errorText</h3><h3 class='ErrorMessage'>" .
			  $e->getMessage() . "</h3>");
	}
} // end pdoFetch2

//---------------------------------------------------------------------------
// pdoNextValue
//
// return the next value of a numeric column
//---------------------------------------------------------------------------
function pdoNextValue($pdo, $tableName, $columnName, $errorText = "") {
	if ($errorText == "") $errorText = "Can't read from $tableName table";
	$sqlQuery = "SELECT MAX($columnName) + 1 FROM $tableName";
	return pdoFetch2($pdo, $sqlQuery, $errorText);
} // end pdoNextValue

//-------------------------------------------------------------------------------
// isParam
//
// return true if $parName is valid in the Parameter table and false otherwise
//-------------------------------------------------------------------------------
function isParam($pdo, $parName, $table = "Parameter") {
	$parName = trim($parName);

	$query = "SELECT count(*) FROM $table WHERE Par_Parameter = ?";
	$res = pdoFetch($pdo, $query, array($parName), "Can't read from the table: $parName");
	if ($res[0] == 0) return false;
	else return true;
} // end isParam

//-------------------------------------------------------------------------------
// getParamValue
//
// return the value of the parameter $parName from the Parameter table if found
// and an empty string if $parName is invalid
//-------------------------------------------------------------------------------
function getParamValue($pdo, $parName, $table = "Parameter") {
	$parName = trim($parName);
	if (! isParam($pdo, $parName)) return "";

	$query = "SELECT Par_Value FROM $table WHERE Par_Parameter = ?";
	$res = pdoFetch($pdo, $query, array(trim($parName)), "Invalid parameter name: $parName");
	return $res["Par_Value"];
} // end getParamValue

//-------------------------------------------------------------------------------
// setParamValue
//
// set the value of the parameter $parName from the Parameter table if found
//
// return true if set and false if $parName is invalid
//-------------------------------------------------------------------------------
function setParamValue($pdo, $parName, $parValue, $table = "Parameter") {
	$parName = trim($parName);
	if (! isParam($pdo, $parName)) return false;

	$query = "UPDATE $table SET Par_Value = ? WHERE Par_Parameter = ?";
	$res = pdoExecute2($pdo, $query, array(trim($parValue), $parName));
	return $res;
} // end setParamValue
?>
