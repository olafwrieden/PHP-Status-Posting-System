<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Processing | Status Posting System</title>
    <meta name="description" content="Web Development Status Posting System." />
    <meta name="author" content="Olaf Wrieden" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="style/normalize.css" />
    <link rel="stylesheet" href="style/skeleton.css" />
    <link rel="stylesheet" href="style.css" />

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="icon" type="image/png" href="images/favicon.png" />
  </head>

  <body>
    <div class="section" style="margin-top: 100px">
      <div class="container">
        <h3 class="section-heading">Status Posting System</h3>
        <hr />
      </div>
    </div>

    <!-- Status Post Section -->
    <div class="section">
      <div class="container">
        <?php

        /**
         * Creates the `posts` table in the database.
         * @return bool status of table creation
         */
        function createTable($conn)
        {
          $sql = "CREATE TABLE posts (
              id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
              status VARCHAR(5) NOT NULL UNIQUE,
              message VARCHAR(255) NOT NULL,
              date DATE NOT NULL,
              visibility ENUM('public', 'friends', 'me') NOT NULL DEFAULT 'me',
              allow_like TINYINT(1) NOT NULL DEFAULT '0',
              allow_comment TINYINT(1) NOT NULL DEFAULT '0',
              allow_share TINYINT(1) NOT NULL DEFAULT '0'
              )";
          return $conn->query($sql);
        }

        /**
         * Checks that a given status code is unique.
         * @return bool `true` if unique, else `false`
         */
        function isUniqueStatus($conn, $status)
        {
          $sql = "SELECT * FROM `posts` WHERE `status` = '$status'";
          return $conn->query($sql)->num_rows === 0;
        }

        /**
         * Inserts post data into the database.
         * @return bool status of record insertion
         */
        function insertData($conn, $status, $message, $date, $visibility, $like, $comment, $share)
        {
          $sql = "INSERT INTO posts (`status`, `message`, `date`, `visibility`, `allow_like`, `allow_comment`, `allow_share`) VALUES ('$status', '$message', '$date', '$visibility', $like, $comment, $share)";
          return $conn->query($sql);
        }

        // Extract Form Data
        $status = trim($_POST["code"]);
        $message = trim($_POST["message"]);
        $date = trim($_POST["date"]);
        $visibility = trim($_POST["visibility"]);
        $permissions = $_POST["permissions"];

        // Database Credentials
        $servername = "[YOUR_DB_SERVER_STRING]";
        $username = "[YOUR_DB_USERNAME]";
        $password = "[YOUR_DB_PASSWORD]";
        $dbname = "[YOUR_DB_NAME]";

        // Process Form Data
        if (empty($status) || empty($message) || empty($date) || empty($visibility)) {
          // Incomplete Form Data
          echo "<p>Oops, one or more fields are empty, please ensure you supply all details for the post.</p>";
        } else if (preg_match('/^[S]{1}\d{4}$/', $status) === FALSE) {
          // Incorrect Status Code Format
          echo "<p>Oops, the Status Code must start with 'S', be followed by four digits, and be unique.</p>";
        } else if (preg_match('/^[a-zA-Z0-9,.!? ]*$/', $message) === FALSE) {
          // Incorrect Status Message Format
          echo "<p>Oops, the Status Message can only contain alphanumeric characters including spaces, comma, period (full stop), exclamation point, and question mark. Other characters or symbols are not allowed!</p>";
        } else {
          // Format Input Date for MySQL
          $date = date('Y-m-d', strtotime(str_replace('/', '-', $date)));

          // Default Permissions
          $like = 0;
          $comment = 0;
          $share = 0;

          // Set Permissions
          if (!empty($_POST['permissions'])) {
            foreach ($_POST['permissions'] as $permission) {
              if ($permission === 'like') $like = 1;
              if ($permission === 'comment') $comment = 1;
              if ($permission === 'share') $share = 1;
            }
          }

          // Used to assess outcome and render respective buttons
          $success = FALSE;

          // Establish Database Connection
          $conn = new mysqli($servername, $username, $password, $dbname);
          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          } else {
            // Check if `posts` Table Exists
            $sql = "SELECT * FROM information_schema.tables WHERE `TABLE_NAME` = 'posts'";
            if ($conn->query($sql) === FALSE) {
              // Create `posts` Table
              if (createTable($conn) === FALSE) {
                echo "<p>Oops, the post could not be saved at this time (failed to create table).</p>";
              }
            }

            // Check Status Code Uniqueness
            if (isUniqueStatus($conn, $status) === FALSE) {
              echo "<p>Oops, please enter a unique status code, <strong><i>$status</i></strong> is already taken.</p>";
            } else {
              // Save Post to Database
              if (insertData($conn, $status, $message, $date, $visibility, $like, $comment, $share)) {
                echo "<p>Your post was successfully saved.</p>";
                $success = TRUE;
              } else {
                echo "<p>Oops, the post could not be saved at this time.</p>";
              }
            }
          }

          $conn->close();
        }
        ?>

        <!-- Buttons -->
        <div class="row">
          <?= !$success ? "<a href=\"poststatusform.php\" class=\"button button-primary\">Try Again</a>" : '' ?>
          <?= "<a href=\"index.html\" class=\"button " . ($success ? "button-primary" : "") . "\">" . (!$success ? "Cancel Post" : "Return Home") . "</a>" ?>
        </div>
      </div>
    </div>
  </body>
</html>