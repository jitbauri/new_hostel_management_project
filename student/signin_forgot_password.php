<?php
session_start();
$errmsg = "";
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('../include/dbConnect.php');

    // Check if this is OTP verification
    if (isset($_POST['otp'])) {
        $entered_otp = $_POST['otp'];
        $expected_otp = $_SESSION['otp'];
        $college_id = $_SESSION['reset_id'];
        $new_password = $_SESSION['new_password'];

        if ($entered_otp == $expected_otp) {
            // Hash the new password before storing
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE students SET password = ? WHERE college_id = ?");
            $stmt->bind_param("ss", $hashed_password, $college_id);

            if ($stmt->execute()) {
                // Clear session variables
                unset($_SESSION['otp']);
                unset($_SESSION['reset_id']);
                unset($_SESSION['new_password']);
                unset($_SESSION['student_info']);

                echo "<script>
                    alert('Password updated successfully!'); 
                    window.location.href='signin.php';
                </script>";
                exit();
            } else {
                $errmsg = "Error updating password: " . $conn->error;
            }
        } else {
            $errmsg = "Invalid OTP. Please try again.";
        }
    } 
    // Handle initial password reset request
    else {
        $college_id = $_POST['college_id'];
        $new_password = $_POST['new_password'];

        // Check if user exists using college_id
        $stmt = $conn->prepare("SELECT student_id, name, mobile_number FROM students WHERE college_id = ?");
        $stmt->bind_param("s", $college_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $mobile = $row['mobile_number'];

            // Generate OTP (6 digits)
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['reset_id'] = $college_id;
            $_SESSION['new_password'] = $new_password;
            $_SESSION['student_info'] = $row;

            // ======== SEND OTP using Fast2SMS ========
            $apiKey = "YOUR_FAST2SMS_API_KEY"; // Replace this with your actual Fast2SMS API Key
            $senderId = "FSTSMS";
            $message = "Your Hostel Management OTP is $otp. Do not share this with anyone.";
            $route = "v3";

            $postData = array(
                'authorization' => $apiKey,
                'sender_id' => $senderId,
                'message'   => $message,
                'language'  => 'english',
                'route'     => $route,
                'numbers'   => $mobile
            );

            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($postData),
                CURLOPT_HTTPHEADER => array(
                    "authorization: $apiKey",
                    "cache-control: no-cache"
                ),
            ));

            $response = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                $errmsg = "cURL Error: " . $err;
            } else {
                $success = "OTP sent to your registered mobile number ending with " . substr($mobile, -4);
            }

        } else {
            $errmsg = "College ID not found in our records.";
        }
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password - Student Portal</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans:wght@700&family=Poppins:wght@400;500;600&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background: linear-gradient(120deg, #00467F, #A5CC82);
      height: 100vh;
      overflow: hidden;
    }

    .center {
      position: absolute;
      top: 50%;
      left: 50%;
      height: auto;
      transform: translate(-50%, -50%);
      width: 400px;
      background: white;
      border-radius: 10px;
      box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.05);
    }

    .center h1 {
      text-align: center;
      padding: 20px 0;
      border-bottom: 1px solid silver;
    }

    .center form {
      padding: 0 40px;
      box-sizing: border-box;
    }

    form .txt_field {
      position: relative;
      border-bottom: 2px solid #adadad;
      margin: 30px 0;
    }

    .txt_field input {
      width: 100%;
      padding: 0 5px;
      height: 40px;
      font-size: 16px;
      border: none;
      background: none;
      outline: none;
    }

    .txt_field label {
      position: absolute;
      top: 50%;
      left: 5px;
      color: #adadad;
      transform: translateY(-50%);
      font-size: 16px;
      pointer-events: none;
      transition: .5s;
    }

    .txt_field span::before {
      content: '';
      position: absolute;
      top: 40px;
      left: 0;
      width: 0%;
      height: 2px;
      background: #2691d9;
      transition: .5s;
    }

    .txt_field input:focus ~ label,
    .txt_field input:valid ~ label,
    .txt_field input:not([value=""]) ~ label {
      top: -5px;
      color: #2691d9;
    }

    .txt_field input:focus ~ span::before,
    .txt_field input:valid ~ span::before,
    .txt_field input:not([value=""]) ~ span::before {
      width: 100%;
    }

    input[type="submit"] {
      width: 100%;
      height: 50px;
      border: 1px solid;
      background: #01BF7E;
      border-radius: 25px;
      font-size: 18px;
      color: #e9f4fb;
      font-weight: 700;
      cursor: pointer;
      outline: none;
      margin: 10px 0 20px 0;
      transition: .3s;
    }

    input[type="submit"]:hover {
      background: #00a76d;
    }

    .signup_link {
      margin: 20px 0;
      text-align: center;
      font-size: 16px;
      color: #666666;
    }

    .signup_link a {
      color: #2691d9;
      text-decoration: none;
    }

    .signup_link a:hover {
      text-decoration: underline;
    }

    .message {
      margin: 15px 20px;
      padding: 10px;
      border-radius: 5px;
      text-align: center;
    }

    .error {
      background-color: rgba(231, 76, 60, 0.1);
      color: #e74c3c;
      border: 1px solid rgba(231, 76, 60, 0.3);
    }

    .success {
      background-color: rgba(39, 174, 96, 0.1);
      color: #27ae60;
      border: 1px solid rgba(39, 174, 96, 0.3);
    }

    .otp-section {
      margin: 20px 0;
      padding: 20px 40px;
      border-top: 1px solid #eee;
    }
  </style>
</head>
<body>
  <div class="center">
    <h1>Password Recovery</h1>
    
    <?php if ($errmsg): ?>
      <div class="message error"><?php echo $errmsg; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
      <div class="message success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form method="post" action="">
      <div class="txt_field">
        <input type="text" name="college_id" required 
               value="<?php echo isset($_SESSION['reset_id']) ? htmlspecialchars($_SESSION['reset_id']) : ''; ?>">
        <label>College ID</label>
      </div>
      
      <div class="txt_field">
        <input type="password" name="new_password" required
               value="<?php echo isset($_SESSION['new_password']) ? htmlspecialchars($_SESSION['new_password']) : ''; ?>">
        <label>New Password</label>
      </div>
      
      <input type="submit" value="Send OTP">
    </form>
    
    <?php if (isset($_SESSION['otp'])): ?>
      <div class="otp-section">
        <form method="post" action="">
          <div class="txt_field">
            <input type="text" name="otp" required pattern="[0-9]{6}" 
                   title="Please enter the 6-digit OTP">
            <label>Enter OTP</label>
          </div>
          
          <input type="submit" value="Verify OTP & Change Password">
        </form>
      </div>
    <?php endif; ?>
    
    <div class="signup_link">
      Remember your password? <a href="signin.php">Sign In</a>
    </div>
  </div>

  <script>
    // This ensures labels stay up when fields have values (helps after form submission)
    document.addEventListener('DOMContentLoaded', function() {
      const inputs = document.querySelectorAll('.txt_field input');
      inputs.forEach(input => {
        if(input.value) {
          input.previousElementSibling.style.top = '-5px';
          input.previousElementSibling.style.color = '#2691d9';
        }
      });
    });
  </script>
</body>
</html>