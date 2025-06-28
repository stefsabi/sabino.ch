<!DOCTYPE html>
<html>
<head>
  <title>Sine Wave Generator</title>
</head>
<body>
  <h1>Sine Wave Generator</h1>
  <form action="" method="post">
    Amplitude: <input type="text" name="amplitude"><br>
    Frequency: <input type="text" name="frequency"><br>
    <input type="submit" value="Generate">
  </form>
  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form values
    $amplitude = $_POST["amplitude"];
    $frequency = $_POST["frequency"];

    // Generate the sine wave
    echo "<p>y = $amplitude * sin($frequency * x)</p>";
  }
  ?>
</body>
</html>