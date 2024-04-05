<!-- 
Name: Rithikun Sen
ID: 103800533 
Email: 103800533@student.swin.edu
Main function: for admin to view all unassigned booking requests with a pick-up time within 3 hours and assign a taxi to a booking request 
-->

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Admin Page</title>
</head>

<body>
    <h1>Admin Page of CabsOnline</h1>
    <h4>1. Click below button to search for all unassigned booking requests with a pick-up time within 3 hours.</h4>
    <form method="post" action="admin.php">
        <input type="submit" name="search" value="List all">
    </form>
    <?php
    if (isset($_POST["search"])) {
        $host = "feenix-mariadb.swin.edu.au";
        $user = "s103800533"; // your user name
        $pwd = "251099"; // your password (date of birth ddmmyy unless changed)
        $sql_db = "s103800533_db"; // your database

        $connect = @mysqli_connect($host, $user, $pwd, $sql_db);

        if (!$connect) {
            echo "<p>Database connection failure</p>";
        } else {
            $query = "SELECT * FROM bookings WHERE status = 'unassigned' AND pickup_datetime BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 3 HOUR)";
            $result = mysqli_query($connect, $query);
            if (!$result) {
                echo "<p>Something is wrong with ", $query, "</p>";
            } else {
                echo "<table border=\"1\">";
                echo "<tr>";
                echo "<th>Reference #</th>";
                echo "<th>Customer Name</th>";
                echo "<th>Passenger Name</th>";
                echo "<th>Phone Number</th>";
                echo "<th>Pick Up Address</th>";
                echo "<th>Destination Suburb</th>";
                echo "<th>Pick Up Date and Time</th>";
                echo "<th>Status</th>";
                echo "</tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>", $row["booking_number"], "</td>";
                    echo "<td>", $row["passenger_name"], "</td>";
                    echo "<td>", $row["passenger_name"], "</td>";
                    echo "<td>", $row["passenger_phone"], "</td>";
                    echo "<td>", $row["unit_number"], ", ", $row["street_number"], " ", $row["street_name"], ", ", $row["suburb"], "</td>";
                    echo "<td>", $row["destination_suburb"], "</td>";
                    $pickup_datetime = date("d M H:i", strtotime($row["pickup_datetime"]));
                    echo "<td>", $pickup_datetime, "</td>";
                    echo "<td>", $row["status"], "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
        }
    }
    ?>
    <h4>2. Input a reference number below and click "update" button to assign a taxi to that request.</h4>
    <form method="post" action="admin.php">
        Reference Number: <input type="text" name="ref_number" />
        <input type="submit" name="update" value="Update">
    </form>
    <?php
    if (isset($_POST["update"])) {
        $host = "feenix-mariadb.swin.edu.au";
        $user = "s103800533"; // your user name
        $pwd = "251099"; // your password (date of birth ddmmyy unless changed)
        $sql_db = "s103800533_db"; // your database

        $connect = @mysqli_connect($host, $user, $pwd, $sql_db);

        if (!$connect) {
            echo "<p>Database connection failure</p>";
        } else {
            $ref_number = $_POST["ref_number"];
            $query = "SELECT * FROM bookings WHERE booking_number = '$ref_number'";
            $result = mysqli_query($connect, $query);
            if (!$result) {
                echo "<p>Something is wrong with ", $query, "</p>";
            } else {
                $row = mysqli_fetch_assoc($result);
                if ($row["status"] == "unassigned") {
                    $query = "UPDATE bookings SET status = 'assigned' WHERE booking_number = '$ref_number'";
                    $result = mysqli_query($connect, $query);
                    if (!$result) {
                        echo "<p>Something is wrong with ", $query, "</p>";
                    } else {
                        echo "<p>Booking request ", $ref_number, " has been properly assigned.</p>";
                    }
                } else {
                    echo "<p>Booking request ", $ref_number, " has already been assigned.</p>";
                }
            }
        }
    }
    ?>
</body>


</html>