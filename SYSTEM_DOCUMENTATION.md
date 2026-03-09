# Course Registration System - Code Documentation

## System Overview
This is a **College of Digital Media and Technology (CODM) Course Registration System** built with PHP, HTML, and CSS. It allows students to register for programs, log in, and view their assigned courses.

---

## File Structure & Purpose




### 1. **index.html** - Registration Form
**Purpose:** Collects new student information to create an account

**Form Fields:**
1. **Profile Picture Upload** - JPG, JPEG, PNG format
2. **First Name** - Student's first name
3. **Last Name** - Student's last name
4. **Email** - Student's email address
5. **Password** - Account password
6. **Program Selection** - Choose from 4 IT programs:
   - Bachelor of Science in Computer Science (bsc-cs)
   - Bachelor of Science in Information Technology (bsc-it)
   - Bachelor of Science in Software Engineering (bsc-se)
   - Associate Degree in IT Support (assoc-it)
7. **Document Upload** - PDF, DOC, DOCX format

**Form Submission:**
- Method: POST
- Action: Sends data to `register.php`
- Enctype: `multipart/form-data` (required for file uploads)

---

### 2. **register.php** - Registration Handler
**Purpose:** Processes registration form data and stores it in session

**Step-by-Step Process:**

#### Step 1: Start Session
```php
session_start();
```
Creates a PHP session to store user data across pages

#### Step 2: Collect Form Data
```php
$fname = $_POST['fname'] ?? '';
$email = $_POST['email'] ?? '';
// ... etc
```
Extracts data from the form using the null coalescing operator `??` (returns empty string if data doesn't exist)

#### Step 3: Validate Input
Checks:
- ✓ All required fields are filled
- ✓ Program selection is valid (not "none")
- ✓ Both files were uploaded without errors

#### Step 4: Validate File Types
- **Profile Picture:** Only JPG, JPEG, PNG allowed
- **Document:** Only PDF, DOC, DOCX allowed

#### Step 5: Save Uploaded Files
```php
$profilepicname = time() . "_" . basename($profilepic['name']);
$profilepicpath = "profiles/" . $profilepicname;
move_uploaded_file($profilepic['tmp_name'], $profilepicpath);
```
- Creates unique filename using timestamp (prevents duplicate names)
- Moves file from temporary location to `profiles/` folder

#### Step 6: Store Data in Session
```php
$_SESSION['fname'] = $fname;
$_SESSION['email'] = $email;
$_SESSION['program'] = $program;
// ... etc
```
Saves all user information in session (persists across pages)

#### Step 7: Set Cookies
```php
setcookie("student_email", $email, time() + (86400 * 30), "/");
```
Creates cookies with 30-day expiration (optional convenience feature)

#### Step 8: Convert Program Code to Full Name
```php
$programNames = [
  'bsc-cs' => 'Bachelor of Science in Computer Science',
  // ... etc
];
$_SESSION['programName'] = isset($programNames[$program]) ? $programNames[$program] : 'Unknown';
```
Converts program code (bsc-cs) to readable names for display

#### Step 9: Redirect to Login
```php
header('Location: login.php');
exit();
```
Sends user to login page after successful registration

---

### 3. **login.php** - Login Authentication
**Purpose:** Verifies student credentials and allows access to dashboard

**How Authentication Works:**

#### Check if Form Submitted
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
```
Only processes login if form was submitted via POST method

#### Get Credentials
```php
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
```

#### Verify Credentials
```php
if ($email === $_SESSION['email'] && $password === $_SESSION['password']) {
  header('Location: dashboard.php');
  exit();
}
```
Compares entered email and password with session data from registration

**If Match:** → Redirects to dashboard.php ✓
**If No Match:** → Shows error message "Invalid email or password" ✗

**Important Note:** 
> Storing plaintext passwords is NOT secure for production. Should use password hashing (bcrypt, argon2) instead.

**Features:**
- Retains email in input field if login fails (better UX)
- Displays error message if credentials are wrong
- Link to registration page for new users

---

### 4. **dashboard.php** - Student Dashboard
**Purpose:** Shows student profile and courses for their selected program

**Security Check:**
```php
if (!isset($_SESSION['email'])) {
  header('Location: login.php');
  exit();
}
```
Only logged-in users can access this page. Others are redirected to login.

**Dashboard Layout:**

#### Header Section
- Welcome message: "Welcome, [Student Name]!"
- Logout button that links to logout.php

#### Profile Section
Displays uploaded profile picture:
```php
if (!empty($_SESSION['profile']) && file_exists($_SESSION['profile'])) {
  echo '<img src="' . $_SESSION['profile'] . '">';
}
```
If no picture exists, shows "No profile picture uploaded" message

#### Registration Details Table
Shows student information:
| Field | Source |
|-------|--------|
| First Name | `$_SESSION['fname']` |
| Last Name | `$_SESSION['lname']` |
| Email | `$_SESSION['email']` |
| Program | `$_SESSION['programName']` |
| Document | Download link to `$_SESSION['doc']` |

#### Your Courses Section
**This is the key feature:**

Defines all programs and their courses:
```php
$programCourses = [
  'bsc-cs' => [
    'name' => 'Bachelor of Science in Computer Science',
    'courses' => [
      ['id' => 'BTCT-101', 'name' => 'Data Structures'],
      ['id' => 'BTCT-102', 'name' => 'Algorithms & Complexity'],
      // ... 6 total courses
    ]
  ],
  // ... other programs
];
```

**How Courses Display:**
```php
$program = $_SESSION['program'] ?? '';
if ($program && isset($programCourses[$program])) {
  $courses = $programCourses[$program]['courses'];
  foreach ($courses as $course) {
    echo '<li>' . $course['name'] . ' (' . $course['id'] . ')</li>';
  }
}
```

1. Gets program from session (e.g., 'bsc-cs')
2. Finds that program in $programCourses array
3. Loops through all courses for that program
4. Displays each course as a list item

**Example Output:**
```
Your Courses
- Data Structures (BTCT-101)
- Algorithms & Complexity (BTCT-102)
- Database Management Systems (BTCT-103)
- Web Development (BTCT-104)
- Computer Networks (BTCT-105)
- Artificial Intelligence (BTCT-106)
```

---

### 5. **logout.php** - Logout Handler
**Purpose:** Destroys session and logs out user

**Code:**
```php
<?php
session_start();
session_destroy();
echo "You have logged out";
?>
```

**What Happens:**
1. Starts session (accesses existing session data)
2. Destroys entire session (`$_SESSION` becomes empty)
3. Shows logout confirmation message

**Result:** User data is cleared, user must log in again to access dashboard

---

### 7. **styles.css** - Styling & Layout
**Purpose:** Makes all pages visually appealing

**Key Style Sections:**

#### Welcome Page Styles
- Gradient background (blue shades)
- Centered hero section with card design
- "Click to Register" button with hover effects

#### Registration Form Styles
- White background with shadow
- Input fields with consistent styling
- Blue submit button (turns green on hover)

#### Dashboard Styles
- Two-column layout: Profile picture (left) + Details (right)
- Professional table formatting
- Responsive design (stacks on mobile)
- Profile picture: 250x250px with border-radius

#### Footer Styles
- Dark background (#2c3e50)
- Light text with hyperlinks
- Top border accent

**Color Scheme:**
- Primary: #004a99 (Dark Blue)
- Secondary: #2c3e50 (Dark Grey)
- Accent: #3498db (Light Blue)
- Success: Green
- Error: #e74c3c (Red)

---

## Data Flow Diagram

```

   
1. REGISTRATION (index.html → register.php)
   ├─ Collect: Name, Email, Password, Program, Files
   ├─ Validate: Fields, File types
   ├─ Save: Files to folders (profiles/, docs/)
   ├─ Store: User data in $_SESSION
   └─ Convert: Program code to full name
   ↓ Redirect to
   
2. LOGIN (login.php)
   ├─ User enters email & password
   ├─ Verify against $_SESSION data
   └─ If correct:
   ↓ Redirect to
   
3. DASHBOARD (dashboard.php)
   ├─ Check: Is user logged in?
   ├─ Display: User profile & registration info
   ├─ Get: Program from $_SESSION
   ├─ Find: All courses for that program
   ├─ Show: Course list
   └─ Option: Download document
   ↓ Click
   
4. LOGOUT (logout.php)
   └─ Destroy session
   ↓ Back to
   
5. LOGIN (repeat)
```

---

## Session Data Storage

When a user registers and logs in, the `$_SESSION` array contains:

```php
$_SESSION['fname']        = "John"
$_SESSION['lname']        = "Doe"
$_SESSION['email']        = "john@example.com"
$_SESSION['password']     = "securepass123"  // NOT RECOMMENDED
$_SESSION['program']      = "bsc-cs"
$_SESSION['programName']  = "Bachelor of Science in Computer Science"
$_SESSION['profile']      = "profiles/1709639400_photo.jpg"
$_SESSION['doc']          = "docs/1709639400_cv.pdf"
```

This data persists across all pages for that user until:
- User clicks logout (destroys session)
- Browser is closed and cookies expire
- Session timeout occurs

---

## File Upload Security

**Upload Process:**
1. User selects file from computer
2. File sent to server through HTTP POST
3. Temporarily stored in `/tmp/` folder
4. File extension validated (JPG, PNG, PDF, DOC, DOCX)
5. Unique filename created: `timestamp_originalname`
6. File moved to permanent location:
   - Profiles: `profiles/1234567890_photo.jpg`
   - Documents: `docs/1234567890_resume.pdf`

**Why Unique Filenames?**
- Prevents overwriting if two users upload "resume.pdf"
- Timestamp ensures uniqueness
- Safe file storage and retrieval

---

## Programs & Course Structure

### 4 IT Programs Available:

**1. Bachelor of Science in Computer Science (bsc-cs)**
- BTCT-101: Data Structures
- BTCT-102: Algorithms & Complexity
- BTCT-103: Database Management Systems
- BTCT-104: Web Development
- BTCT-105: Computer Networks
- BTCT-106: Artificial Intelligence

**2. Bachelor of Science in Information Technology (bsc-it)**
- IT-101: IT Infrastructure
- IT-102: System Administration
- IT-103: Network Security
- IT-104: IT Project Management
- IT-105: Cloud Computing
- IT-106: Business Intelligence

**3. Bachelor of Science in Software Engineering (bsc-se)**
- SC-101: Software Design Patterns
- SC-102: Software Testing & QA
- SC-103: DevOps & CI/CD
- SC-104: Mobile Application Development
- SC-105: Software Project Management
- SC-106: Advanced Web Frameworks

**4. Associate Degree in IT Support (assoc-it)**
- SUP-101: Hardware Troubleshooting
- SUP-102: Help Desk Management
- SUP-103: User Support & Training
- SUP-104: IT Operations
- SUP-105: Customer Service Excellence

---

## Security Notes & Improvements

### ⚠️ Current Security Issues:
1. **Plaintext Passwords** - Stored and compared as plain text
2. **No Password Hashing** - Vulnerable to exposure
3. **Simple Session Storage** - No database, all in memory
4. **No HTTPS** - Credentials sent unencrypted

### ✅ Recommended Improvements:
1. **Use Password Hashing:**
   ```php
   $hashedPassword = password_hash($_POST['pass'], PASSWORD_BCRYPT);
   ```

2. **Use Database (MySQL/SQLite):**
   - Store user data persistently
   - More secure than session storage
   - Better for multiple users

3. **Input Sanitization:**
   ```php
   $email = filter_var($email, FILTER_SANITIZE_EMAIL);
   ```

4. **Validate File Uploads:**
   - Check MIME type, not just extension
   - Limit file size
   - Store outside web root

5. **Use HTTPS:**
   - Encrypt data in transit
   - Protect credentials

---

## How to Use the System

### For New Students:

1. Click "Click to Register"
2. Fill in registration form (all fields required)
3. Upload profile picture and document
4. Select your program
5. Click "Register"
6. Enter email and password to log in
7. View your dashboard with courses

### For Returning Students:
1. Go to login.php
2. Enter email and password (from registration)
3. Click "Login"
4. View dashboard
5. See your program and all courses for it

---

## Summary

This is a complete student registration and course management system that:
- ✓ Registers new students
- ✓ Stores user data in sessions
- ✓ Authenticates logins
- ✓ Manages file uploads (profile, documents)
- ✓ Displays program-specific courses
- ✓ Provides logout functionality
- ✓ Uses responsive, modern design

**Technology Stack:**
- Backend: PHP (server-side processing)
- Frontend: HTML (structure) + CSS (styling)
- Storage: PHP Sessions (not database)
- Server: Apache with XAMPP

