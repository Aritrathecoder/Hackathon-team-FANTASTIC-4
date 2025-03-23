<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login into Streamsync</title>
    <link href="style.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <form method="POST" action="" id="loginForm">
                <h2>Welcome to Streamsync</h2>
                <p class="subtitle">Log in to continue your journey</p>
                <div class="input-group">
                    <input type="text" placeholder="Enter username" name="username" required>
                </div>
                <div class="input-group">
                    <input type="password" placeholder="Enter password" name="password" required>
                </div>
                <div class="terms">
                    <input id="terms" type="checkbox" name="terms">
                    <label for="terms">Agree with <a href="#">Terms & Conditions</a></label>
                </div>
                <span class="error-message" id="termsError" style="display: none;">You must agree to the Terms & Conditions to proceed.</span>
                <p class="privacy">We Care About Your Privacy</p>
                <div class="button-group">
                    <input type="submit" value="Login" name="submit" class="btn">
                    <input type="reset" value="Reset" name="reset" class="btn btn-secondary">
                </div>
            </form>
        </div>
    </div>

    <?php
    // session_start(); // Start session at the top
    include('connect.php');

    if (isset($_POST['submit'])) {
        // Server-side validation for the checkbox
        if (!isset($_POST['terms'])) {
            echo "<p style='color: red; text-align: center;'>You must agree to the Terms & Conditions to proceed.</p>";
        } else {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            
            $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
            $res = mysqli_query($conn, $sql);
            
            if ($res) {
                $count = mysqli_num_rows($res);
                if ($count > 0) {
                    $row = mysqli_fetch_assoc($res);
                    $_SESSION['username'] = $username;
                    $_SESSION['password'] = $password; // Note: Storing passwords in session isn't recommended
                    $_SESSION['id'] = $row['id'];
                    header('Location: streamsync.php');
                    exit();
                } else {
                    echo "<p style='color: red; text-align: center;'>Invalid username or password</p>";
                }
            } else {
                echo "<p style='color: red; text-align: center;'>Error: " . mysqli_error($conn) . "</p>";
            }
        }
    }
    ?>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            const termsCheckbox = document.getElementById('terms');
            const termsError = document.getElementById('termsError');

            if (!termsCheckbox.checked) {
                event.preventDefault(); // Prevent form submission
                termsError.style.display = 'block'; // Show error message
            } else {
                termsError.style.display = 'none'; // Hide error message if checkbox is checked
            }
        });
    </script>
</body>
</html>