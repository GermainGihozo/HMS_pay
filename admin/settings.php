<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <title>Settings</title>
</head>
<body>
  <div class="container mt-5">
    <h2>Settings</h2>
    <form id="settings-form">
      <div class="mb-3">
        <label for="email-settings" class="form-label">Email Settings</label>
        <input type="text" class="form-control" id="email-settings" required>
      </div>
      <div class="mb-3">
        <label for="security-settings" class="form-label">Security Settings</label>
        <input type="text" class="form-control" id="security-settings" required>
      </div>
      <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
  </div>

  <script src="settings.js"></script>
</body>
</html>
