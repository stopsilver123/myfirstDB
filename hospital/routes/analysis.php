<?php
require_once('path/to/jpgraph/src/jpgraph.php');
require_once('path/to/jpgraph/src/jpgraph_bar.php');
require_once('path/to/mysql-connector-php/autoload.php');

use PhpMyAdmin\SqlParser\Utils\Query;

// MariaDB database connection settings
$host = 'localhost';
$user = 'web';
$password = 'web_admin';
$database = 'company';

// Connect to MariaDB
$conn = new \mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle button click
if (isset($_POST['action'])) { //if the button is not null
    $action = $_POST['action'];

    // Generate chart
    if ($action === 'chart') {
        generateChart($conn);
    }
    // Generate grid
    elseif ($action === 'grid') {
        generateGrid($conn);
    }
    // Generate diagnosis date chart
    elseif ($action === 'diagnosis_date_chart') {
        generateDiagnosisDateChart($conn);
    }
}

// Function to generate the chart
function generateChart($conn) {
    // Execute SQL query to get the count of patients by age and sex
    $query = "
        SELECT sex, SUBSTRING(birthdate, 1, 2) AS year, SUBSTRING(birthdate, 3, 2) AS month, SUBSTRING(birthdate, 5, 2) AS day, COUNT(*) AS count
        FROM patient
        GROUP BY sex, year
        ORDER BY sex, year
    ";
    $result = $conn->query($query);

    // Prepare data for plotting
    $maleData = array();  //make array
    $femaleData = array();
    $ageLabels = array();
    while ($row = $result->fetch_assoc()) {
        $sex = $row['sex'];
        $year = $row['year'];
        $month = $row['month'];
        $day = $row['day'];
        $count = $row['count'];

        // Calculate age based on birthdate
        $currentDate = date('Y-m-d');
        $birthdate = $year . '-' . $month . '-' . $day;
        $age = date_diff(date_create($birthdate), date_create($currentDate))->y;

        if ($sex == 'M') {
            $maleData[] = $count;  
        } else {
            $femaleData[] = $count;
        }

        $ageLabels[] = strval($age);  //change to string
    }

    // Create a new bar graph instance
    $graph = new Graph(600, 400);
    $graph->SetScale('textlin');

    // Create a new bar plot for male data
    $malePlot = new BarPlot($maleData);
    $malePlot->SetFillColor('white');
    $malePlot->SetLegend('Male');

    // Create a new bar plot for female data
    $femalePlot = new BarPlot($femaleData);
    $femalePlot->SetFillColor('green');
    $femalePlot->SetLegend('Female');

    // Add the bar plots to the graph
    $graph->Add($malePlot);
    $graph->Add($femalePlot);

    // Set labels for the x-axis
    $graph->xaxis->SetTickLabels($ageLabels);

    // Set titles for the graph and axes
    $graph->title->Set('Number of Patients by Age and Sex');
    $graph->xaxis->title->Set('Age');
    $graph->yaxis->title->Set('Count');

    // Create a legend for the bar plots
    $graph->legend->Pos(0.5, 0.99, 'center', 'bottom');

    // Output the graph
    $graph->Stroke();
}

// Function to generate the grid
function generateGrid($conn) {
    // Execute SQL query to get the count of patients by age and sex
    $query = "
        SELECT sex, SUBSTRING(birthdate, 1, 2) AS year, SUBSTRING(birthdate, 3, 2) AS month, SUBSTRING(birthdate, 5, 2) AS day, COUNT(*) AS count
        FROM patient
        GROUP BY sex, year, month, day
        ORDER BY sex, year, month, day
    ";
    $result = $conn->query($query);

    // Prepare data for displaying in grid
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $sex = $row['sex'];
        $year = $row['year'];
        $month = $row['month'];
        $day = $row['day'];
        $count = $row['count'];

        // Calculate age based on birthdate
        $currentDate = date('Y-m-d');
        $birthdate = $year . '-' . $month . '-' . $day;
        $age = date_diff(date_create($birthdate), date_create($currentDate))->y;

        $data[] = array('Sex' => $sex, 'Age' => $age, 'Count' => $count);
    }

    // Display the data in a grid
    echo "<table>";
    echo "<tr><th>Sex</th><th>Age</th><th>Count</th></tr>";
    foreach ($data as $row) {
        echo "<tr>";
        echo "<td>" . $row['Sex'] . "</td>";
        echo "<td>" . $row['Age'] . "</td>";
        echo "<td>" . $row['Count'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Function to generate the diagnosis date chart
function generateDiagnosisDateChart($conn) {
    // Execute SQL query to get the count of patients by diagnosis date
    $query = "
        SELECT 진료날짜, COUNT(*) AS count
        FROM patient
        GROUP BY 진료날짜 
        ORDER BY 진료날짜
    ";
    $result = $conn->query($query);

    // Prepare data for plotting
    $dates = array();
    $counts = array();
    while ($row = $result->fetch_assoc()) {
        $date = $row['진료날짜'];
        $count = $row['count'];

        $dates[] = $date;
        $counts[] = $count;
    }

    // Create a new line graph instance
    $graph = new Graph(600, 400);
    $graph->SetScale('textlin');

    // Create a new line plot
    $linePlot = new LinePlot($counts);

    // Add the line plot to the graph
    $graph->Add($linePlot);

    // Set labels for the x-axis
    $graph->xaxis->SetTickLabels($dates);

    // Set titles for the graph and axes
    $graph->title->Set('Number of Patients by Diagnosis Date');
    $graph->xaxis->title->Set('Date');
    $graph->y

   
