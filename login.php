<!-- PHP Session Start -->
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Login - Course Registration</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body class="loginbody">
  <?php
  // Initialize variables
  $error = '';
  $email_value = '';
  
  // Check if form was submitted
  if ($_SERVER['REQUEST_METHOD'] === 'POST') { //This checks if the form was submitted using POST method
    $email = $_POST['email'] ?? ''; // "??" is the null coalescing operator, if the value doesn't exist it will return an empty string 
    $password = $_POST['password'] ?? '';
    $email_value = $email;  // Keep email in input field
    
    // Validate to check if user has registered and credentials match
    if (
      isset($_SESSION['email']) && // this line checks if email exists in session
      $email === $_SESSION['email'] &&
      $password === ($_SESSION['password'] ?? '')
    ) {
      // if credentials are correct, redirect to dashboard
      header('Location: dashboard.php');
      exit();
    } else {
      // if credentials are wrong
      $error = 'Invalid email or password. Please try again.';
    }
  }
  ?>

  <form action="" method="post" class="loginform">

    <div class="logincon">

      <h2 class="logincon_h2">CODM University</h2>
      
      <!-- Display error message if login failed -->
      <?php if ($error !== ''): ?>
        <div class="login-error">
          <p><?php echo htmlspecialchars($error); ?></p>
        </div>
       <?php endif; ?> <!--"endif" is used to close the if statement in PHP when using html inside the if block -->
      
      <label for="email" class="logincon_label">Email:</label>
      <input type="email" id="email" name="email" required class="logincon_input" value="<?php echo htmlspecialchars($email_value); ?>"> 

      <label for="password" class="logincon_label">Password:</label>
      <input type="password" id="password" name="password" required class="logincon_input"> 
        
      <!-- login button -->
      <div class="logincon-butt">
        <input type="submit" value="Login" class="logincon_submit">
      </div>
      
      <p class="login-help">Don't have an account? <a href="index.html">Register here</a></p>
    </div>

  </form>
</body>

</html>