<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mail form</title>
</head>
<body>
    <h1>Mail form</h1>


    <?php

        // Check if the HTTP Request is a POST or GET.
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // If it's a GET request we'll show the signup form
        // Using POST for form method (it's a bit cleaner than GET),
        // and it will allow us to show different things for the same URI
        if ('GET' === $requestMethod) {
        echo '<form method="post">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" required>
        <input type="submit">
    </form>
';
        }

        if ('POST' === $requestMethod) {
            $status = '';

            // Get all the info you want to store from the form
            // We're getting the form data from an array by using the value for the name attribute on the input element.
            $email = $_POST['email'];

            // Make sure that the email is in valid format
            $isValid = filter_var($email, FILTER_VALIDATE_EMAIL);
            if ($isValid) {

                // If you fill out these credentials with production info be sure to not store the raw file
                // where anyone can read it (like a public repo on GitHub).
                // I'm using dummy info here.
                $dbURL = '127.0.0.1';
                $dbName = 'emails';
                $dbUser = 'test@example.com';
                $dbPassword = 'example';

                // I'm using mysqli function, read about it here: https://www.php.net/manual/en/book.mysqli.php
                // and store the email there (provided it isn't already stored)
                try {
                    // connect to the database
                    $connection = mysqli_connect($dbURL, $dbUser, $dbPassword, $dbName);
                    if ($connection->connect_errno) {
                        $status =  "Failed to connect to database!";
                    } else {
                        $insertIsSuccess = $connection->query("INSERT INTO email(email) VALUES ('$email')");
                        if (!$insertIsSuccess) {
                            // This will be executed if something went wrong on insert.
                            // Example on this is if you want to insert an email that is already inserted,
                            // and it's set to be unique.
                            $status = "Something went wrong";
                        }

                    }


                } catch (Exception $e) {
                    $status = 'Something went wrong';
                }

                if (empty($status)) {
                    $status = "You've successfully signed up your email: " . $email . "!";
                }
            } else {
                $status = "Entered email is not valid.";
            }


            // Give a feedback on the status of the email signup
            echo '<p>' . $status . '</p>';

        }
    ?>
</body>
</html>