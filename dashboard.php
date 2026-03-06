<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
  header('Location: login.php');
  exit();}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body class="dashboard-body">
  <div class="dashboard-container">
    <div class="dashboard-header">
      <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name'] ?? $_SESSION['fname'] ?? 'Guest'); ?>!</h1> 
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>
    
    <div class="dashboard-content">
      <div class="profile-section">
        <h2>Your Profile</h2>
        
        <?php if (!empty($_SESSION['profile']) && file_exists($_SESSION['profile'])): ?>
          <img src="<?php echo htmlspecialchars($_SESSION['profile']); ?>" alt="Profile Picture" class="profile-pic">
        <?php else: ?>
          <p class="no-profile">No profile picture uploaded</p>
        <?php endif; ?>
      </div>

      
      <!-- create a table for displaying registration details -->
      <div class="details-section">
        <h2>Registration Details</h2>
        <table class="details-table">
          <tr>
            <td><strong>First Name:</strong></td>
            <td><?php echo htmlspecialchars($_SESSION['fname'] ?? 'N/A'); ?></td>
          </tr>
          <tr>
            <td><strong>Last Name:</strong></td>
            <td><?php echo htmlspecialchars($_SESSION['lname'] ?? 'N/A'); ?></td>
          </tr>
          <tr>
            <td><strong>Email:</strong></td>
            <td><?php echo htmlspecialchars($_SESSION['email'] ?? 'N/A'); ?></td>
          </tr>
          <tr>
            <td><strong>Program:</strong></td>
            <td><?php echo htmlspecialchars($_SESSION['programName'] ?? 'Unknown'); ?></td>
          </tr>
          <?php if (!empty($_SESSION['doc'])): ?>
          <tr>
            <td><strong>Document:</strong></td>
            <td><a href="<?php echo htmlspecialchars($_SESSION['doc']); ?>" download class="download-link">Download Document</a></td>
          </tr>
          <?php endif; ?>
        </table>
      </div>

      <!-- Display courses for the selected program -->
      <div class="courses-section">
        <h2>Your Courses</h2>
        <?php
          // Define programs and their courses
          $programCourses = [
            'bsc-cs' => [
              'name' => 'Bachelor of Science in Computer Science',
              'courses' => [
                ['id' => 'BTCT-101', 'name' => 'Data Structures'],
                ['id' => 'BTCT-102', 'name' => 'Algorithms & Complexity'],
                ['id' => 'BTCT-103', 'name' => 'Database Management Systems'],
                ['id' => 'BTCT-104', 'name' => 'Web Development'],
                ['id' => 'BTCT-105', 'name' => 'Computer Networks'],
                ['id' => 'BTCT-106', 'name' => 'Artificial Intelligence']
              ]
            ],
            'bsc-it' => [
              'name' => 'Bachelor of Science in Information Technology',
              'courses' => [
                ['id' => 'IT-101', 'name' => 'IT Infrastructure'],
                ['id' => 'IT-102', 'name' => 'System Administration'],
                ['id' => 'IT-103', 'name' => 'Network Security'],
                ['id' => 'IT-104', 'name' => 'IT Project Management'],
                ['id' => 'IT-105', 'name' => 'Cloud Computing'],
                ['id' => 'IT-106', 'name' => 'Business Intelligence']
              ]
            ],
            'bsc-se' => [
              'name' => 'Bachelor of Science in Software Engineering',
              'courses' => [
                ['id' => 'SC-101', 'name' => 'Software Design Patterns'],
                ['id' => 'SC-102', 'name' => 'Software Testing & QA'],
                ['id' => 'SC-103', 'name' => 'DevOps & CI/CD'],
                ['id' => 'SC-104', 'name' => 'Mobile Application Development'],
                ['id' => 'SC-105', 'name' => 'Software Project Management'],
                ['id' => 'SC-106', 'name' => 'Advanced Web Frameworks']
              ]
            ],
            'assoc-it' => [
              'name' => 'Associate Degree in IT Support',
              'courses' => [
                ['id' => 'SUP-101', 'name' => 'Hardware Troubleshooting'],
                ['id' => 'SUP-102', 'name' => 'Help Desk Management'],
                ['id' => 'SUP-103', 'name' => 'User Support & Training'],
                ['id' => 'SUP-104', 'name' => 'IT Operations'],
                ['id' => 'SUP-105', 'name' => 'Customer Service Excellence']
              ]
            ]
          ];

          // Get the program from session and display courses
          $program = $_SESSION['program'] ?? '';
          if ($program && isset($programCourses[$program])) {
            $courses = $programCourses[$program]['courses'];
            echo '<ul class="courses-list">';
            foreach ($courses as $course) {
              echo '<li>' . htmlspecialchars($course['name']) . ' (' . htmlspecialchars($course['id']) . ')</li>';
            }
            echo '</ul>';
          }
        ?>
      </div>
