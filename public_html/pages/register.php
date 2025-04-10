<?php
// public_html/pages/register.php
require_once '../scripts/db_check.php'; // sets up $db

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'User'; // Default to 'User'

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $hashedPassword);
        $stmt->bindValue(':role', $role);
        $stmt->execute();

        echo "Registration successful!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- Basic HTML form -->
<link rel="stylesheet" href="../css/registeruser.css">
<form method="POST" action="">
    <label>Username: <input type="text" name="username" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <label>Role:
        <select name="role">
            <option value="User">User</option>
            <option value="Admin">Admin</option>
        </select>
    </label><br>
    <button type="submit">Register</button>
</form>

