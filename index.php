<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form to Terminal</title>
</head>
<body>
    <h2>Enter Something</h2>
    <form method="POST" action="process.php">
        <input type="text" name="user_name" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit" name="submit">Submit</button>
    </form>

</body>
</html>
