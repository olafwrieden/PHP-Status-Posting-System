<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Status Information | Status Posting System</title>
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
      <h3 class="section-heading">Status Information</h3>
      <hr />
    </div>
  </div>

  <!-- Results Section -->
  <div class="section">
    <div class="container">
      <?php
      // Extract Search Form
      if (!isset($_GET["search"])) {
        echo "<p>Oops, please use the search field provided.</p>";
      } else if (preg_match('/^[a-zA-Z0-9,.!? ]*$/', trim($_GET["search"])) === FALSE) {
        // Incorrect Search String Format
        echo "<p>Oops, the search string can only contain alphanumeric characters including spaces, comma, period (full stop), exclamation point, and question mark. Other characters or symbols are not allowed!</p>";
      } else {
        // Database Credentials
        $servername = "[YOUR_DB_SERVER_STRING]";
        $username = "[YOUR_DB_USERNAME]";
        $password = "[YOUR_DB_PASSWORD]";
        $dbname = "[YOUR_DB_NAME]";

        // Establish Database Connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        } else {
          // Check if `posts` Table Exists
          $sql = "SELECT * FROM information_schema.tables WHERE `TABLE_NAME` = 'posts'";
          if ($conn->query($sql) === FALSE) {
            echo "<p>Oops, looks like the database table cannot be found. Simply create a new post to create a new table as well.</p>";
          } else {
            // Search Database
            $search = trim($_GET["search"]);
            $sql = "SELECT * FROM posts WHERE `message` LIKE '%$search%' ORDER BY `date` ASC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              // Table Head
              echo "<table class=\"u-full-width\" style=\"table-layout:fixed;\">";
              echo "<thead>";
              echo "<tr>";
              echo "<th style=\"min-width: 50px; max-width: 10px; word-wrap:break-word;\">Status</th><th>Share</th><th>Date</th><th>Permissions</th>";
              echo "</tr>";
              echo "</thead>";
              echo "<tbody>";

              // Fill Results in Table
              while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><strong>" . $row["status"] . "</strong><p>" . $row["message"] . "</p></td>";
                echo "<td>" . ucfirst($row["visibility"]) . "</td>";
                echo "<td>" . date("d M Y", strtotime($row["date"])) . "</td>";
		

                // Extract Post Permissions
                $permissions = array();
                $like = $row["allow_like"] ? array_push($permissions, "Allow Like") : "";
		$comment = $row["allow_comment"] ? array_push($permissions, "Allow Comment") : "";
                $share = $row["allow_share"] ? array_push($permissions, "Allow Share") : "";
                echo "<td>" . (empty($permissions) ? "-" : implode(", ", $permissions)) . "</td>";

                echo "</tr>";
              }

              echo "</tbody></table>";
            } else {
              echo "<p>No results found.</p>";
            }
          }
        }

        $conn->close();
      }
      ?>

      <!-- Buttons -->
      <div class="row">
        <div class="eight columns">
          <a href="searchstatusform.html" class="button button-primary">Search for another status</a>
        </div>
        <div class="four columns" style="text-align: right;">
          <a href="index.html" class="button">Back to Homepage</a>
        </div>
      </div>
    </div>
  </div>
</body>

</html>