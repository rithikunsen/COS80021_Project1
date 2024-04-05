<!-- 
Name: Rithikun Sen
ID: 103800533 
Email: 103800533@student.swin.edu
Main function: for new user to register to CabsOnline 
-->

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Register to CabsOnline</title>
</head>

<body>
  <h1>Reister to CabsOnline</h1>
  <p>Please fill the field below to complete your registration</p>
  <form method="post" action="register.php">
    <label for="customer_name">Customer Name: </label>
    <input type="text" name="customer_name" id="customer_name"><br><br>
    <label for="password">Password: </label>
    <input type="password" name="password" id="password"><br><br>
    <label for="confirm_password">Confirm Password: </label>
    <input type="password" name="confirm_password" id="confirm_password"><br><br>
    <label for="phone_number">Phone Number: </label>
    <input type="text" name="phone_number" id="phone_number"><br><br>
    <label for="email_address">Email Address: </label>
    <input type="text" name="email_address" id="email_address"><br><br>
    <input type="submit" value="Register" name="register" id="register">
  </form>
  <p>Already have an account? <a href="login.php">Login</a></p>
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
  if (isset($_POST["register"])) {
    $customer_name = $_POST["customer_name"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $phone_number = $_POST["phone_number"];
    $email_address = $_POST["email_address"];
    if ($customer_name == "" || $password == "" || $confirm_password == "" || $phone_number == "" || $email_address == "") {
      echo "<p>Please fill all the fields</p>";
    } else if ($password != $confirm_password) {
      echo "<p>Passwords do not match</p>";
    } else {
      $query = "SELECT * FROM customers WHERE email_address = '$email_address'";
      $result = mysqli_query($connect, $query);
      if (mysqli_num_rows($result) > 0) {
        echo "<p>Email address already exists</p>";
      } else {
        $query = "INSERT INTO customers (customer_name, password, phone_number, email_address) VALUES ('$customer_name', '$password', '$phone_number', '$email_address')";
        $result = mysqli_query($connect, $query);
        if (!$result) {
          echo "<p>Something is wrong with ", $query, "</p>";
        } else {
          echo "<p>Successfully registered</p>";
        }
      }
    }
  }
}

?>

</html>