<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>New Status | Status Posting System</title>
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

  <!-- Form Section -->
  <div class="section">
    <div class="container">
      <form action="poststatusprocess.php" method="POST">

        <!-- Status Code & Date -->
        <div class="row">
          <div class="six columns">
            <label for="statusCode">Status Code *</label>
            <input class="u-full-width" type="text" placeholder="eg. S0001" id="statusCode" name="code" pattern="[S]{1}\d{4}" required>
          </div>
          <div class="six columns">
            <label for="dateInput">Date</label>
            <input class="u-full-width" type="text" id="dateInput" name="date" value="<?php echo date('d/m/Y'); ?>" />
          </div>
        </div>

        <!-- Status Message -->
        <label for="statusMessage">Status *</label>
        <input class="u-full-width" type="text" placeholder="My status message..." id="statusMessage" name="message" pattern="^[a-zA-Z0-9,.!? ]*$" required>

        <!-- Share Preference -->
        <div class="row">
          <div class="six columns">
            <label for="shareOptions">Share</label>

            <label class="shareOptions">
              <input type="radio" name="visibility" value="public">
              <span class="label-body">Public</span>
            </label>

            <label class="shareOptions">
              <input type="radio" name="visibility" value="friends">
              <span class="label-body">Friends</span>
            </label>

            <label class="shareOptions">
              <input type="radio" checked name="visibility" value="me">
              <span class="label-body">Only Me</span>
            </label>
          </div>

          <!-- Permission Type -->
          <div class="six columns">
            <label for="exampleMessage">Permission Type</label>

            <label class="example-send-yourself-copy">
              <input type="checkbox" name="permissions[]" value="like">
              <span class="label-body">Allow Like</span>
            </label>

            <label class="example-send-yourself-copy">
              <input type="checkbox" name="permissions[]" value="comment">
              <span class="label-body">Allow Comment</span>
            </label>

            <label class="example-send-yourself-copy">
              <input type="checkbox" name="permissions[]" value="share">
              <span class="label-body">Allow Share</span>
            </label>
          </div>
        </div>

        <hr>

        <!-- Buttons -->
        <div class="row">
          <div class="eight columns">
            <input class="button-primary" type="submit" value="Post">
            <input class="button" type="reset" value="Reset">
          </div>
          <div class="four columns" style="text-align: right;">
            <a href="index.html" class="button">Back to Homepage</a>
          </div>
        </div>

      </form>
    </div>
  </div>
</body>

</html>