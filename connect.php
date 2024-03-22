<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Arduino Connection Status</title>
</head>
<body>
  <h1>Arduino Connection Status</h1>
  <p id="status">Status: Unknown</p>

  <script>
    function checkStatus() {
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);
          document.getElementById('status').innerText = 'Status: ' + response.status;
        }
      };
      xhr.open('GET', 'arduino_connection.php', true); // Use the proxy script URL
      xhr.send();
    }

    setInterval(checkStatus, 5000); // Check status every 3 seconds
  </script>
</body>
</html>
