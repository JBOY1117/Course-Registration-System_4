<?php
// Start session to store user data across pages
session_start();

// ===== COLLECT DATA FROM FORM =====
// Get user input from the registration form using null coalescing operator
$fname = $_POST['fname'] ?? '';
$lname = $_POST['lname'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['pass'] ?? '';
$program = $_POST['program'] ?? '';


// Get uploaded files
$profilepic = $_FILES['profilepic'] ?? null;
$doc = $_FILES['doc'] ?? null;

// ===== VALIDATE INPUT =====
// Check if all required fields are filled
if (empty($fname) || empty($lname) || empty($email) || empty($password) || empty($program)) {
  die("Error: Please fill in all required fields.");
}

// Check if program is valid (not the default "none" option)
if ($program === 'none') {
  die("Error: Please select a valid program.");
}

// Check if files were uploaded without errors
if ($profilepic === null || $profilepic['error'] !== 0) {
  die("Error: Profile picture upload failed.");
}

if ($doc === null || $doc['error'] !== 0) {
  die("Error: Document upload failed.");
}

// ===== VALIDATE FILE TYPES =====
// Get file extensions
$profileExt = strtolower(pathinfo($profilepic['name'], PATHINFO_EXTENSION));
$docExt = strtolower(pathinfo($doc['name'], PATHINFO_EXTENSION));

// Check profile picture extension
$allowedImages = ['jpg', 'jpeg', 'png'];
if (!in_array($profileExt, $allowedImages)) {
  die("Error: Only JPG, JPEG, or PNG files allowed for profile picture.");
}

// Check document extension
$allowedDocs = ['pdf', 'doc', 'docx'];
if (!in_array($docExt, $allowedDocs)) {
  die("Error: Only PDF, DOC, or DOCX files allowed for documents.");
}

// ===== SAVE UPLOADED FILES =====
// Create unique filenames using timestamp to avoid conflicts
$profilepicname = time() . "_" . basename($profilepic['name']);
$profilepicpath = "profiles/" . $profilepicname;

// Move profile picture to the profiles folder
if (!move_uploaded_file($profilepic['tmp_name'], $profilepicpath)) {
  die("Error: Failed to save profile picture.");
}

// Create unique filename for document
$docname = time() . "_" . basename($doc['name']);
$docpath = "docs/" . $docname;

// Move document to the docs folder
if (!move_uploaded_file($doc['tmp_name'], $docpath)) {
  die("Error: Failed to save document.");
}

// ===== STORE DATA IN SESSION =====
// Save user information in session for use on other pages
$_SESSION['fname'] = $fname;
$_SESSION['lname'] = $lname;
$_SESSION['email'] = $email;
$_SESSION['password'] = $password;  // Note: storing plaintext password is not secure for production
$_SESSION['program'] = $program;
$_SESSION['profile'] = $profilepicpath;
$_SESSION['doc'] = $docpath;

// ===== SET COOKIES =====
// Save basic info in cookies (optional, for convenience)
setcookie("student_email", $email, time() + (86400 * 30), "/");  // 30 days
setcookie("student_name", "$fname $lname", time() + (86400 * 30), "/");

// ===== CONVERT CODES TO FULL NAMES =====
// Map program codes to readable program names
$programNames = [
  'bsc-cs' => 'Bachelor of Science in Computer Science',
  'bsc-it' => 'Bachelor of Science in Information Technology',
  'bsc-se' => 'Bachelor of Science in Software Engineering',
  'assoc-it' => 'Associate Degree in IT Support'
];

$_SESSION['programName'] = isset($programNames[$program]) ? $programNames[$program] : 'Unknown';

// ===== REDIRECT TO LOGIN PAGE =====
// Send user to login page after successful registration
header('Location: login.php');
exit();
?>
