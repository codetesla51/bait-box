<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form to Terminal</title>
</head>
<body>
    <h2>Enter Something</h2>
    <form method="POST">
        <input type="text" name="user_name" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit" name="submit">Submit</button>
    </form>

    <?php 
    if (isset($_POST["submit"])) {
        $user_name = $_POST["user_name"];
        $user_pass = $_POST["password"];

        // Concatenate user data properly
        $data = "Data Fetched \n";
        $data .= "Username: $user_name\n";
        $data .= "Password: $user_pass\n";
        $data .= "-------------------------------------\n";

        // Write the data to input.txt
        file_put_contents("input.txt", $data, FILE_APPEND);

        echo "<p>Saved to input.txt</p>";
    }
    ?>
</body>
</html>
