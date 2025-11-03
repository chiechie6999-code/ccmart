<?php
session_start();
require_once 'templates/header-auth.php';

// Retrieve errors and old input from session
$errors = $_SESSION['errors'] ?? [];
$input = $_SESSION['input'] ?? [];
unset($_SESSION['errors'], $_SESSION['input']);
?>

<div class="container">
    <h2>Register</h2>
    <?php if (isset($errors['db_error'])): ?>
        <div class="error-message"><?php echo htmlspecialchars($errors['db_error']); ?></div>
    <?php endif; ?>
    <form action="php/register_process.php" method="post" id="register-form" novalidate>
        <div class="form-group">
            <label for="id_number">ID Number <span class="required">*</span></label>
            <input type="text" name="id_number" id="id_number" placeholder="xxxx-xxxx" value="<?php echo htmlspecialchars($input['id_number'] ?? ''); ?>" required>
            <div class="error" id="id_number_error"><?php echo htmlspecialchars($errors['id_number'] ?? ''); ?></div>
        </div>
        <div class="form-group">
            <label for="first_name">First Name <span class="required">*</span></label>
            <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($input['first_name'] ?? ''); ?>" required>
            <div class="error" id="first_name_error"><?php echo htmlspecialchars($errors['first_name'] ?? ''); ?></div>
        </div>
        <div class="form-group">
            <label for="middle_name">Middle Name/Initial <span class="optional">optional</span></label>
            <input type="text" name="middle_name" id="middle_name" value="<?php echo htmlspecialchars($input['middle_name'] ?? ''); ?>">
            <div class="error" id="middle_name_error"><?php echo htmlspecialchars($errors['middle_name'] ?? ''); ?></div>
        </div>
        <div class="form-group">
            <label for="family_name">Family Name <span class="required">*</span></label>
            <input type="text" name="family_name" id="family_name" value="<?php echo htmlspecialchars($input['family_name'] ?? ''); ?>" required>
            <div class="error" id="family_name_error"><?php echo htmlspecialchars($errors['family_name'] ?? ''); ?></div>
        </div>
        <div class="form-group">
            <label for="name_extension">Name Extension <span class="optional">optional</span></label>
            <input type="text" name="name_extension" id="name_extension" placeholder="e.g., Jr., Sr., III" value="<?php echo htmlspecialchars($input['name_extension'] ?? ''); ?>">
            <div class="error" id="name_extension_error"><?php echo htmlspecialchars($errors['name_extension'] ?? ''); ?></div>
        </div>
        <div class="form-group">
            <label for="birthdate">Birthdate <span class="required">*</span></label>
            <input type="date" name="birthdate" id="birthdate" value="<?php echo htmlspecialchars($input['birthdate'] ?? ''); ?>" required>
            <div class="error" id="birthdate_error"><?php echo htmlspecialchars($errors['birthdate'] ?? ''); ?></div>
        </div>
        <div class="form-group">
            <label for="age">Age</label>
            <input type="text" name="age" id="age" readonly>
        </div>
        <div class="form-group address-group">
            <label>Address <span class="required">*</span></label>
            <input type="text" name="purok_street" id="purok_street" placeholder="Purok/Street" value="<?php echo htmlspecialchars($input['purok_street'] ?? ''); ?>" required>
            <div class="error" id="purok_street_error"><?php echo htmlspecialchars($errors['purok_street'] ?? ''); ?></div>
            <input type="text" name="barangay" id="barangay" placeholder="Barangay" value="<?php echo htmlspecialchars($input['barangay'] ?? ''); ?>" required>
            <div class="error" id="barangay_error"><?php echo htmlspecialchars($errors['barangay'] ?? ''); ?></div>
            <input type="text" name="municipality_city" id="municipality_city" placeholder="Municipal/City" value="<?php echo htmlspecialchars($input['municipality_city'] ?? ''); ?>" required>
            <div class="error" id="municipality_city_error"><?php echo htmlspecialchars($errors['municipality_city'] ?? ''); ?></div>
            <input type="text" name="province" id="province" placeholder="Province" value="<?php echo htmlspecialchars($input['province'] ?? ''); ?>" required>
            <div class="error" id="province_error"><?php echo htmlspecialchars($errors['province'] ?? ''); ?></div>
            <input type="text" name="country" id="country" placeholder="Country" value="<?php echo htmlspecialchars($input['country'] ?? ''); ?>" required>
            <div class="error" id="country_error"><?php echo htmlspecialchars($errors['country'] ?? ''); ?></div>
            <input type="text" name="zip_code" id="zip_code" placeholder="Zip Code" value="<?php echo htmlspecialchars($input['zip_code'] ?? ''); ?>" required>
            <div class="error" id="zip_code_error"><?php echo htmlspecialchars($errors['zip_code'] ?? ''); ?></div>
        </div>
        <div class="form-group">
            <label for="username">Username <span class="required">*</span></label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($input['username'] ?? ''); ?>" required>
            <div class="error" id="username_error"><?php echo htmlspecialchars($errors['username'] ?? ''); ?></div>
        </div>
        <div class="form-group">
            <label for="password">Password <span class="required">*</span></label>
            <div class="password-field">
                <input type="password" name="password" id="password" required>
                <button type="button" class="toggle-password">Show</button>
            </div>
            <div id="password-strength"></div>
            <div class="error" id="password_error"><?php echo htmlspecialchars($errors['password'] ?? ''); ?></div>
        </div>
        <div class="form-group">
            <label for="re_password">Re-enter Password <span class="required">*</span></label>
            <div class="password-field">
                <input type="password" name="re_password" id="re_password" required>
                <button type="button" class="toggle-password">Show</button>
            </div>
            <div class="error" id="re_password_error"><?php echo htmlspecialchars($errors['re_password'] ?? ''); ?></div>
        </div>

        <h4>Authentication Questions</h4>
        <div class="form-group">
            <label for="answer1">Who is your best friend in Elementary? <span class="required">*</span></label>
            <div class="password-field">
                <input type="password" name="answer1" id="answer1" value="<?php echo htmlspecialchars($input['answer1'] ?? ''); ?>" required>
                <button type="button" class="toggle-password">Show</button>
            </div>
            <div class="error" id="answer1_error"><?php echo htmlspecialchars($errors['answer1'] ?? ''); ?></div>
        </div>
        <div class="form-group">
            <label for="answer2">What is the name of your favorite pet? <span class="required">*</span></label>
            <div class="password-field">
                <input type="password" name="answer2" id="answer2" value="<?php echo htmlspecialchars($input['answer2'] ?? ''); ?>" required>
                <button type="button" class="toggle-password">Show</button>
            </div>
            <div class="error" id="answer2_error"><?php echo htmlspecialchars($errors['answer2'] ?? ''); ?></div>
        </div>
        <div class="form-group">
            <label for="answer3">Who is your favorite teacher in high school? <span class="required">*</span></label>
            <div class="password-field">
                <input type="password" name="answer3" id="answer3" value="<?php echo htmlspecialchars($input['answer3'] ?? ''); ?>" required>
                <button type="button" class="toggle-password">Show</button>
            </div>
            <div class="error" id="answer3_error"><?php echo htmlspecialchars($errors['answer3'] ?? ''); ?></div>
        </div>

        <button type="submit">Register</button>
    </form>
</div>

<?php require_once 'templates/footer.php'; ?>