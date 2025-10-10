<?php
session_start();
require_once 'db_connect.php';

// Helper function for name validation
function validateName($name) {
    if (empty($name)) return true; // Optional fields can be empty
    if (!preg_match('/^[a-zA-Z\s\.\']*$/', $name)) return false; // Allow letters, spaces, dots, apostrophes
    if (preg_match('/\d/', $name)) return false; // No numbers
    if (preg_match('/\s\s/', $name)) return false; // No double spaces
    if (strtoupper($name) === $name && strlen($name) > 1) return false; // Not all caps (allow single initial)
    if (preg_match('/([a-zA-Z])\\1\\1/', $name)) return false; // No three consecutive same letters

    // Check for correct capitalization
    $words = explode(' ', $name);
    foreach ($words as $word) {
        if (empty($word)) continue;
        if (ucfirst(strtolower($word)) !== $word) {
            // Allow for extensions like Jr. or III
            if (!preg_match('/^(Jr|Sr|I|V|X|II|III|IV|VI|VII|VIII|IX)$/i', $word)) {
                 // Check for initials like M.
                if (strlen($word) > 2 || (strlen($word) == 2 && substr($word, -1) != '.')) {
                     if(ucfirst(strtolower($word)) !== $word) return false;
                } else if(strlen($word) == 1 && strtoupper($word) !== $word){
                    return false;
                }
            }
        }
    }
    return true;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $input = [];

    // Sanitize and retrieve input
    $input['id_number'] = trim($_POST['id_number']);
    $input['first_name'] = trim($_POST['first_name']);
    $input['middle_name'] = trim($_POST['middle_name']);
    $input['family_name'] = trim($_POST['family_name']);
    $input['name_extension'] = trim($_POST['name_extension']);
    $input['birthdate'] = trim($_POST['birthdate']);
    $input['purok_street'] = trim($_POST['purok_street']);
    $input['barangay'] = trim($_POST['barangay']);
    $input['municipality_city'] = trim($_POST['municipality_city']);
    $input['province'] = trim($_POST['province']);
    $input['country'] = trim($_POST['country']);
    $input['zip_code'] = trim($_POST['zip_code']);
    $input['username'] = trim($_POST['username']);
    $input['password'] = $_POST['password'];
    $input['re_password'] = $_POST['re_password'];
    $input['answer1'] = trim($_POST['answer1']);
    $input['answer2'] = trim($_POST['answer2']);
    $input['answer3'] = trim($_POST['answer3']);

    // Validation
    // ID Number
    if (!preg_match('/^\d{4}-\d{4}$/', $input['id_number'])) {
        $errors['id_number'] = 'ID Number must be in the format xxxx-xxxx.';
    } else {
        $stmt = $pdo->prepare("SELECT id_number FROM users WHERE id_number = ?");
        $stmt->execute([$input['id_number']]);
        if ($stmt->fetch()) {
            $errors['id_number'] = 'ID Number is already registered.';
        }
    }

    // Names
    if (empty($input['first_name'])) $errors['first_name'] = 'First Name is required.';
    elseif (!validateName($input['first_name'])) $errors['first_name'] = 'Invalid First Name format.';

    if (!validateName($input['middle_name'])) $errors['middle_name'] = 'Invalid Middle Name format.';

    if (empty($input['family_name'])) $errors['family_name'] = 'Family Name is required.';
    elseif (!validateName($input['family_name'])) $errors['family_name'] = 'Invalid Family Name format.';

    if (!empty($input['name_extension']) && !preg_match('/^[a-zA-Z\s\.]*$/', $input['name_extension'])) {
        $errors['name_extension'] = 'Name Extension contains invalid characters.';
    }

    // Age
    if (empty($input['birthdate'])) {
        $errors['birthdate'] = 'Birthdate is required.';
    } else {
        $birthDate = new DateTime($input['birthdate']);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;
        if ($age < 18) {
            $errors['birthdate'] = 'You must be at least 18 years old.';
        }
    }

    // Address
    if (empty($input['purok_street'])) $errors['purok_street'] = 'Purok/Street is required.';
    if (empty($input['barangay'])) $errors['barangay'] = 'Barangay is required.';
    if (empty($input['municipality_city'])) $errors['municipality_city'] = 'Municipal/City is required.';
    if (empty($input['province'])) $errors['province'] = 'Province is required.';
    if (empty($input['country'])) $errors['country'] = 'Country is required.';
    if (empty($input['zip_code'])) $errors['zip_code'] = 'Zip Code is required.';


    // Username
    if (empty($input['username'])) {
        $errors['username'] = 'Username is required.';
    } else {
        $stmt = $pdo->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->execute([$input['username']]);
        if ($stmt->fetch()) {
            $errors['username'] = 'Username is already taken.';
        }
    }

    // Password
    if (empty($input['password'])) {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($input['password']) < 8) {
        $errors['password'] = 'Password must be at least 8 characters long.';
    } elseif ($input['password'] !== $input['re_password']) {
        $errors['re_password'] = 'Passwords do not match.';
    }

    // Auth Answers
    if (empty($input['answer1'])) $errors['answer1'] = 'Answer to question 1 is required.';
    if (empty($input['answer2'])) $errors['answer2'] = 'Answer to question 2 is required.';
    if (empty($input['answer3'])) $errors['answer3'] = 'Answer to question 3 is required.';

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        $_SESSION['input'] = $input;
        header('Location: ../register.php');
        exit();
    } else {
        // Hash password and insert into database
        $hashed_password = password_hash($input['password'], PASSWORD_DEFAULT);

        try {
            $pdo->beginTransaction();

            $sql = "INSERT INTO users (id_number, first_name, middle_name, family_name, name_extension, birthdate, purok_street, barangay, municipality_city, province, country, zip_code, username, password)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $input['id_number'], $input['first_name'], $input['middle_name'], $input['family_name'], $input['name_extension'],
                $input['birthdate'], $input['purok_street'], $input['barangay'], $input['municipality_city'], $input['province'],
                $input['country'], $input['zip_code'], $input['username'], $hashed_password
            ]);

            $answers = [
                ['user_id_number' => $input['id_number'], 'question_id' => 1, 'answer' => $input['answer1']],
                ['user_id_number' => $input['id_number'], 'question_id' => 2, 'answer' => $input['answer2']],
                ['user_id_number' => $input['id_number'], 'question_id' => 3, 'answer' => $input['answer3']]
            ];

            $sql_answers = "INSERT INTO auth_answers (user_id_number, question_id, answer) VALUES (?, ?, ?)";
            $stmt_answers = $pdo->prepare($sql_answers);

            foreach ($answers as $answer) {
                $stmt_answers->execute([$answer['user_id_number'], $answer['question_id'], $answer['answer']]);
            }

            $pdo->commit();

            $_SESSION['success_message'] = 'Registration successful! Please log in.';
            header('Location: ../login.php');
            exit();

        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['errors'] = ['db_error' => 'Registration failed. Please try again. ' . $e->getMessage()];
            $_SESSION['input'] = $input;
            header('Location: ../register.php');
            exit();
        }
    }
} else {
    header('Location: ../register.php');
    exit();
}
?>