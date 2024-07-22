<?php
//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include "includes/config.php";
$groupsQuery = mysqli_query($mysql, 'SELECT group_name, html_path FROM mailing_groups');
$groups = mysqli_fetch_all($groupsQuery, MYSQLI_ASSOC);


// Get logged in user
$logged_user = mysqli_real_escape_string($mysql, $_SESSION['login_user']);

// Fetch SMTP configuration
$smtpQuery = mysqli_query($mysql, "SELECT smtp_host, smtp_username, smtp_password FROM users WHERE username = '$logged_user'");
$smtpConfig = mysqli_fetch_assoc($smtpQuery);
?>

<?php
// HTML email content variable
$body = '';

// Forming the HTML email content based on the selected group's HTML path
if (!empty($_POST['recipientGroup'])) {
    $selectedGroup = $_POST['recipientGroup'];
    foreach ($groups as $group) {
        if ($group['group_name'] === $selectedGroup) {
            $htmlPath = $group['html_path'];
            // Load HTML content from the specified path
            $body = file_get_contents($htmlPath);
            break; // Stop looping once the correct group is found
        }
    }
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_reporting(E_STRICT | E_ALL);
    date_default_timezone_set('Etc/UTC');
    require './vendor/autoload.php';

    //Passing `true` enables PHPMailer exceptions
    $mail = new PHPMailer(true);

    $selectedGroup = $_POST['recipientGroup'];
    $htmlPath = '';

    // Find the HTML path for the selected group
    foreach ($groups as $group) {
        if ($group['group_name'] === $selectedGroup) {
            $htmlPath = $group['html_path'];
            break;
        }
    }

    if (!empty($htmlPath)) {
        // Load HTML content from the specified path
        $body = file_get_contents($htmlPath);
        
        
         $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

        // SMTP configuration
        $mail->isSMTP();
        $mail->Host =  $smtpConfig['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to

        // Host username (full email address) and password
        $mail->Username = $smtpConfig['smtp_username'];
        $mail->Password = $smtpConfig['smtp_password'];

        // Sender information
        $mail->setFrom('admin@infinweb.pro', 'Hallo');
        $mail->addCustomHeader(
            'List-Unsubscribe',
            '<mailto:unsubscribes@example.com>, <https://www.example.com/unsubscribe.php>'
        );
        $mail->Subject = $group['group_name'];

        // Email content
        $mail->msgHTML($body);
        $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';

        // Connect to the database and select the recipients from your mailing list
        $result = mysqli_query($mysql, "SELECT email FROM mailinglist WHERE mailing_group = '$selectedGroup'");

        foreach ($result as $row) {
            try {
                $mail->addAddress($row['email']);
            } catch (Exception $e) {
                echo 'Invalid address skipped: ' . htmlspecialchars($row['email']) . '<br>';
                continue;
            }

            // Send email
            try {
                $mail->send();
                echo 'Message sent to: ' . htmlspecialchars($row['email']) . ' (' . htmlspecialchars($row['email']) . ')<br>';
            } catch (Exception $e) {
                echo 'Mailer Error (' . htmlspecialchars($row['email']) . '): ' . $mail->ErrorInfo . '<br>';
            }

            // Clear all addresses and attachments for the next iteration
            $mail->clearAddresses();
            $mail->clearAttachments();
        }
    } else {
        echo 'Error: HTML path not found for the selected group.';
    }
}
?>

<div class=class="col-lg-6 ml-auto">
    <form method="post">
        <!-- Choose recipient group -->
        <div class="form-group">
            <label for="recipientGroup">Choose Recipient Group</label>
            <div class="input-group custom-form">
                <select class="form-control" id="mailingGroup" name="recipientGroup">
                    <?php foreach ($groups as $group): ?>
                        <option value="<?php echo $group['group_name']; ?>"><?php echo $group['group_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <!-- Submit button -->
        <button type="submit" class="btn btn-primary btn-block custom-btn">Send Email</button>
    </form>
</div>

