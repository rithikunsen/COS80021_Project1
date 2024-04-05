<!-- 
Name: Rithikun Sen
ID: 103800533 
Email: 103800533@student.swin.edu
Main function: for user to book a taxi
-->

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Booking a cab</title>
</head>

<body>
    <h1>Booking a cab</h1>
    <p>Please fill the fields below to book a taxi</p>
    <form method="POST" action="booking.php">
        <label for="customer_email">Passenger Email</label>
        <input type="text" name="customer_email" id="customer_email" value="<?php echo $_GET["email"]; ?>" readonly><br><br>
        <label for="passenger_name">Passenger Name: </label>
        <input type="text" name="passenger_name" id="passenger_name"><br><br>
        <label for="passenger_phone">Contact phone of the passenger: </label>
        <input type="text" name="passenger_phone" id="passenger_phone"><br><br>
        <label for="">Pick up address: </label>
        &emsp;<label for="unit_number">Unit Number: </label>
        <input type="text" name="unit_number" id="unit_number"><br><br>
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<label for="street_number">Street Number: </label>
        <input type="text" name="street_number" id="street_number"><br><br>
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<label for="street_name">Street Name: </label>
        <input type="text" name="street_name" id="street_name"><br><br>
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<label for="suburb">Suburb: </label>
        <input type="text" name="suburb" id="suburb"><br><br>
        <label for="destination_suburb">Destination Suburb: </label>
        <input type="text" name="destination_suburb" id="destination_suburb"><br><br>
        <label for="pickup_datetime">Pickup Date and Time:</label>
        <input type="datetime-local" name="pickup_datetime" required><br><br>
        <input type="submit" value="Book" name="book" id="book">
    </form>
</body>
<?php
$host = "feenix-mariadb.swin.edu.au";
$user = "s103800533"; // your user name
$pwd = "251099"; // your password (date of birth ddmmyy unless changed)
$sql_db = "s103800533_db"; // your database

$connect = @mysqli_connect($host, $user, $pwd, $sql_db);

if (!$connect) {
    echo "<p>Database connection failure</p>";
} else {
    if (isset($_POST["book"])) {
        // Gather user input from the form
        $customer_email = $_GET["email"];
        $passenger_name = $_POST["passenger_name"];
        $passenger_phone = $_POST["passenger_phone"];
        $street_number = $_POST["street_number"];
        $unit_number = $_POST["unit_number"];
        $street_name = $_POST["street_name"];
        $suburb = $_POST["suburb"];
        $destination_suburb = $_POST["destination_suburb"];
        $pickup_datetime = $_POST["pickup_datetime"];

        // Validate the pickup date/time (at least 40 minutes in the future)
        $current_datetime = new DateTime();
        $pickup_datetime_obj = new DateTime($pickup_datetime);
        $pickup_datetime_obj->modify("-40 minutes");
        if ($pickup_datetime_obj < $current_datetime) {
            echo "<p>Pickup date/time must be at least 40 minutes in the future</p>";
            exit();
        }

        // Generate a unique booking ref number
        $booking_number = uniqid();

        // Prepare and insert the booking request into the database
        $query = "INSERT INTO bookings (customer_email, passenger_name, passenger_phone, street_number, unit_number, street_name, suburb, destination_suburb, pickup_datetime, booking_number) VALUES ('$customer_email', '$passenger_name', '$passenger_phone', '$street_number', '$unit_number', '$street_name', '$suburb', '$destination_suburb', '$pickup_datetime', '$booking_number')";
        $result = mysqli_query($connect, $query);
        if ($result) {
            // Send confirmation email
            $to = $customer_email;
            $subject = "Your booking request with CabsOnline!";
            $message = "Dear $passenger_name, Thanks for booking with CabsOnline! Your booking reference number is $booking_number. We will pick up the passengers in front of your provided address at " . date("h:i:sa", strtotime($pickup_datetime)) . " on " . date("Y-m-d", strtotime($pickup_datetime)) . ".";
            $headers = "From: booking@cabsonline.com.au";
            $envelope_sender = "-r 103800533@student.swin.edu.au";

            mail($to, $subject, $message, $headers, $envelope_sender);

            echo "Dear $passenger_name, Thanks for booking with CabsOnline! Your booking reference number is $booking_number. We will pick up the passengers in front of your provided address at " . date("h:i:sa", strtotime($pickup_datetime)) . " on " . date("Y-m-d", strtotime($pickup_datetime)) . ".";
        } else {
            echo "<p>Something is wrong with ", $query, "</p>";
        }
        mysqli_close($connect);
    }
}
?>

</html>