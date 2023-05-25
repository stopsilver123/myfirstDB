<?php
// Database connection details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Establish the database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$searchCondition = '';
$searchValue = '';

// Check if the search form is submitted
if (isset($_POST['submit'])) {
    $searchCondition = $_POST['condition'];
    $searchValue = $_POST['search'];

    // Prepare the SQL statement based on the selected condition
    $sql = "SELECT * FROM CHART c JOIN DIAGNOSIS d ON c.chart_id = d.chart_id ";

    if ($searchCondition === 'name') {
        $sql .= "WHERE c.�ǻ�ID LIKE '%$searchValue%'";
    } elseif ($searchCondition === 'date') {
        $sql .= "WHERE c.���ᳯ¥ = '$searchValue'";
    } elseif ($searchCondition === 'id') {
        $sql .= "WHERE c.��Ʈ��ȣ = '$searchValue'";
    }

    // Execute the query
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Search</h1>
    <form method="post" action="">
        <label for="condition">Search Condition:</label>
        <select name="condition" id="condition">
            <option value="name" <?php if ($searchCondition === '�ǻ�ID') echo 'selected'; ?>>doct_ID</option>
            <option value="date" <?php if ($searchCondition === '���ᳯ¥') echo 'selected'; ?>>Date</option>
            <option value="id" <?php if ($searchCondition === 'ȯ��ID') echo 'selected'; ?>>patient_ID</option>
        </select>
        <br>
        <label for="search">Search Value:</label>
        <input type="text" name="search" id="search" value="<?php echo $searchValue; ?>" required>
        <input type="submit" name="submit" value="Search">
    </form>

    <?php
    // Display search results if available
    if (isset($result) && $result->num_rows > 0) {
        echo "<h2>Search Results:</h2>";
        echo "<table>";
        echo "<tr><th>Chart ID</th><th>Name</th><th>Date</th><th>Diagnosis</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['chart_no'] . "</td>";
            echo "<td>" . $row['ȯ��id'] . "</td>";
            echo "<td>" . $row['��ȣ��id'] . "</td>";
            echo "<td>" . $row['�ǻ�id'] . "</td>";
            echo "<td>" . $row['���ᳯ¥'] . "</td>";
            echo "<td>" . $row['�ǻ��'] . "</td>";
            echo "<td>" . $row['���᳻��'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    ?>
</body>
</html>