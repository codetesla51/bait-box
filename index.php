<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bait Box</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8; /* Light background for contrast */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
            margin: 0;
        }

        .container {
            background-color: #ffffff; /* White background for the form */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
            width: 300px; /* Fixed width */
            text-align: center;
        }

        h2 {
            margin-bottom: 20px; /* Space below the heading */
            color: #333; /* Darker color for the heading */
        }

        label {
            display: block; /* Block display for labels */
            margin-bottom: 5px; /* Space below labels */
            color: #555; /* Grey color for labels */
            font-weight: bold; /* Bold labels */
        }

        input[type="text"],
        input[type="password"] {
            width: 100%; /* Full width input */
            padding: 10px; /* Padding inside inputs */
            margin-bottom: 15px; /* Space below inputs */
            border: 1px solid #ccc; /* Light grey border */
            border-radius: 5px; /* Rounded corners */
            font-size: 14px; /* Font size */
            transition: border-color 0.3s; /* Smooth border transition */
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #007BFF; /* Blue border on focus */
            outline: none; /* Remove outline */
        }

        button {
            width: 100%; /* Full width button */
            padding: 10px; /* Padding inside button */
            background-color: #007BFF; /* Blue background */
            color: white; /* White text */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners */
            font-size: 16px; /* Font size */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s; /* Smooth background transition */
        }

        button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .error-message {
            color: red; /* Red for error messages */
            margin-top: 10px; /* Space above the error message */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bait Box </h2>
        <form method="POST" action="response/process.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="user_name" placeholder="Enter Username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter Password" required>
            
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>
</body>
</html>
