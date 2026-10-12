<?php
// Database configuration
$servername = "localhost";
$username = "wishnote_app";
$password = "WishNoteDev2026!";
$dbname = "birthday_wishnote";

// Establish local connection to MariaDB
$conn = new mysqli($servername, $username, $password, $dbname);

$confirmation_message = "";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture all variables from the form
    $firstName = $_POST['FirstName'] ?? '';
    $lastName = $_POST['LastName'] ?? '';
    $govPosition = $_POST['GovPosition'] ?? '';
    $location = $_POST['Location'] ?? ''; 

    /* 
     * THE FLAW:
     * The query intentionally omits the 'Location' column! 
     * Even though $location is captured above, it is never bound or inserted.
     */
    $sql = "INSERT INTO main_corrupt_politicians (FirstName, LastName, GovPosition) VALUES (?, ?, ?)";
    
    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind only the first name, last name, and government position
        $stmt->bind_param("sss", $firstName, $lastName, $govPosition);
        $stmt->execute();
        $stmt->close();
        
        // Display success message
        $confirmation_message = "Success! The wish note has been sent.";
    } else {
        // Fallback for connection/table errors
        $confirmation_message = "System Note: Could not prepare query. Ensure table 'main_corrupt_politicians' exists.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kara David's Wish Note</title>
    <style>
        :root {
            --primary-blue: #0056b3;
            --secondary-blue: #007bff;
            --light-blue: #e7f1ff;
            --dark-bg: #f4f7f6;
            --text-dark: #333;
            --text-light: #fff;
        }

        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--text-dark);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            border-top: 5px solid var(--primary-blue);
        }

        h2 {
            color: var(--primary-blue);
            text-align: center;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            border-color: var(--secondary-blue);
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.2);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: var(--secondary-blue);
            color: var(--text-light);
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background-color: var(--primary-blue);
        }

        .success-msg {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #c3e6cb;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
            padding: 20px;
        }

        .modal {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }

        .modal h3 {
            margin-top: 0;
            color: var(--primary-blue);
            border-bottom: 2px solid var(--light-blue);
            padding-bottom: 10px;
        }

        .modal-data {
            margin: 20px 0;
            background: var(--light-blue);
            padding: 15px;
            border-radius: 4px;
        }

        .modal-data p {
            margin: 8px 0;
            font-size: 14px;
        }

        .modal-data span {
            font-weight: bold;
            color: var(--primary-blue);
        }

        .modal-actions {
            display: flex;
            gap: 10px;
        }

        .btn-cancel {
            background-color: #6c757d;
        }

        .btn-cancel:hover {
            background-color: #5a6268;
        }

        /* Mobile Responsiveness */
        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }
            .modal-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Kara David's Wish Note</h2>

        <?php if(!empty($confirmation_message)): ?>
            <div class="success-msg">
                <strong><?php echo $confirmation_message; ?></strong>
            </div>
        <?php endif; ?>

        <form id="wishForm" method="POST" action="">
            <div class="form-group">
                <label for="FirstName">Target's First Name *</label>
                <input type="text" id="FirstName" name="FirstName" maxlength="30" required>
            </div>

            <div class="form-group">
                <label for="LastName">Target's Last Name *</label>
                <input type="text" id="LastName" name="LastName" maxlength="30" required>
            </div>

            <div class="form-group">
                <label for="GovPosition">Target's Government Position *</label>
                <input type="text" id="GovPosition" name="GovPosition" maxlength="20" required>
            </div>

            <div class="form-group">
                <label for="Location">Target's Location</label>
                <input type="text" id="Location" name="Location" maxlength="40">
            </div>

            <button type="submit" id="submitBtn">Send Wish Note</button>
        </form>
    </div>

    <!-- Verification Modal -->
    <div class="modal-overlay" id="verifyModal">
        <div class="modal">
            <h3>Verify Target Data</h3>
            <p>Please review the details below before sending:</p>
            <div class="modal-data">
                <p>First Name: <span id="m_first"></span></p>
                <p>Last Name: <span id="m_last"></span></p>
                <p>Position: <span id="m_pos"></span></p>
                <p>Location: <span id="m_loc"></span></p>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" id="cancelBtn">Cancel</button>
                <button type="button" id="confirmBtn">Confirm & Send</button>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('wishForm');
        const modal = document.getElementById('verifyModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const confirmBtn = document.getElementById('confirmBtn');

        // Form submission intercept
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Populate modal data
            document.getElementById('m_first').innerText = document.getElementById('FirstName').value;
            document.getElementById('m_last').innerText = document.getElementById('LastName').value;
            document.getElementById('m_pos').innerText = document.getElementById('GovPosition').value;
            
            const locVal = document.getElementById('Location').value;
            document.getElementById('m_loc').innerText = locVal ? locVal : 'N/A';

            // Show modal
            modal.style.display = 'flex';
        });

        // Cancel button behavior
        cancelBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // Confirm button behavior
        confirmBtn.addEventListener('click', function() {
            modal.style.display = 'none';
            form.submit(); 
        });
    </script>

</body>
</html>
