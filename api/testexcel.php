<?php

require_once "common.php";
pdoConnect();

require '../vendor/autoload.php';

$sql = "SELECT Inc_Id, Dep_Desc, Prob_Desc, Sol_Desc, Cl_Desc, Evaluation_Loss, Caused_Date, Caused_Time, Solved_Cost, Solved_Date, Solved_Time, Ist_Desc
        FROM Incident I, Problem P, Solution S, Department D, CriticalLevel CL, IncidentStatus IST
        WHERE P.Dep_Id = D.Dep_Id AND I.Prob_Id = P.Prob_Id AND I.Sol_Id = S.Sol_Id AND I.Cl_Id = CL.Cl_Id AND I.Inc_Status = IST.Ist_Id";

$resultIncident = pdoFetch($pdo, $sql, null, "", true);


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();
$activeWorksheet->setCellValue('A1', 'Incident Report');

$html = '
<body>
    <h1>Incident Report</h1>
    <table>
        <thead>
            <tr>
                <th>Incident ID</th>
                <th>Department</th>
                <th>Problem Description</th>
                <th>Solution Description</th>
                <th>Critical Level</th>
                <th>Evaluation Loss</th>
                <th>Caused Date</th>
                <th>Caused Time</th>
                <th>Solved Cost</th>
                <th>Solved Date</th>
                <th>Solved Time</th>
                <th>Incident Status</th>
                <th>Caused By</th>
            </tr>
        </thead>
        <tbody>';

foreach ($resultIncident as $row) {
    $sql = "SELECT CONCAT(Emp_Fnm, ' ', Emp_Lnm) AS Caused_By_Emp_Name
            FROM IncidentCausedBy ICB, Employee E
            WHERE ICB.Emp_Id = E.Emp_Id AND Inc_Id = ?";
    $resultCausedByEmployee = pdoFetch($pdo, $sql, array($row["Inc_Id"]), "", true);
    $causedByEmployee = !empty($resultCausedByEmployee) ? $resultCausedByEmployee[0]['Caused_By_Emp_Name'] : '';

    $html .= '<tr>
        <td>' . $row["Inc_Id"] . '</td>
        <td>' . $row["Dep_Desc"] . '</td>
        <td>' . $row["Prob_Desc"] . '</td>
        <td>' . $row["Sol_Desc"] . '</td>
        <td>' . $row["Cl_Desc"] . '</td>
        <td>' . $row["Evaluation_Loss"] . '</td>
        <td>' . $row["Caused_Date"] . '</td>
        <td>' . $row["Caused_Time"] . '</td>
        <td>' . $row["Solved_Cost"] . '</td>
        <td>' . $row["Solved_Date"] . '</td>
        <td>' . $row["Solved_Time"] . '</td>
        <td>' . $row["Ist_Desc"] . '</td>
        <td>' . $causedByEmployee . '</td>
    </tr>';
}

$html .= '</tbody></table></body>';

$spreadsheet->getActiveSheet()->fromArray(
    ['Incident ID', 'Department', 'Problem Description', 'Solution Description', 'Critical Level', 'Evaluation Loss', 'Caused Date', 'Caused Time', 'Solved Cost', 'Solved Date', 'Solved Time', 'Incident Status', 'Caused By'],
    NULL,
    'A2'
);

$dataArray = [];
$dom = new DOMDocument();
$dom->loadHTML($html);
$rows = $dom->getElementsByTagName('tr');

foreach ($rows as $row) {
    $rowData = [];
    $columns = $row->getElementsByTagName('td');
    foreach ($columns as $column) {
        $rowData[] = $column->textContent;
    }
    $dataArray[] = $rowData;
}

$spreadsheet->getActiveSheet()->fromArray(
    $dataArray,
    NULL,
    'A3'
);

$writer = new Xlsx($spreadsheet);
$excelFileName = 'IncidentExcel.xlsx';
$writer->save($excelFileName);

// Set the appropriate headers for Excel file download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $excelFileName . '"');

// Send the Excel file to the user
readfile($excelFileName);
?>
