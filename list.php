<?php
// Start PHP's built-in server to host the form locally
$host = "localhost";
$port = 8000;

echo "Starting server on http://$host:$port...\n";
echo "You can access the form in your browser.\n\n";

// Use shell_exec to start the PHP built-in server in the background
shell_exec("php -S $host:$port > /dev/null 2>&1 &");

// The location of your form (form.php)
$form_file = "index.php";

// Check if form file exists
if (!file_exists($form_file)) {
  die("Error: The form.php file is missing. Please create the form file.\n");
}

// Start listening for changes in the input file
$file = "input.txt";

echo "Listening for form input...\n";

while (true) {
  clearstatcache();
  if (file_exists($file)) {
    $input = file_get_contents($file);

    if (!empty($input)) {
      echo "Input received: $input\n";

      // Clear the file after reading
      file_put_contents($file, "");
    }
  }

  // Pause for 2 seconds before checking again
  sleep(2);
}
