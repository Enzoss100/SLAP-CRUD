<?php
require_once '../scripts/db_check.php';
$dbPath = realpath('../database/app.sqlite');

try {
    $db = new PDO("sqlite:" . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle delete
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['delete_user'])) {
            $userId = intval($_POST['delete_user']);
            $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
        }

        // Handle role update
        if (isset($_POST['update_role']) && isset($_POST['role']) && isset($_POST['user_id'])) {
            $role = $_POST['role'];
            $userId = intval($_POST['user_id']);
            $stmt = $db->prepare("UPDATE users SET role = :role WHERE id = :id");
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
        }

        // Handle add user
        if (isset($_POST['add_user']) && isset($_POST['new_username'])) {
            $username = $_POST['new_username'];
            $defaultPassword = password_hash('testuser', PASSWORD_DEFAULT);
            $role = 'User';
            $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $defaultPassword);
            $stmt->bindParam(':role', $role);
            $stmt->execute();
        }
    }

    // Fetch users
    $stmt = $db->query("SELECT id, username, role FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admindash.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th colspan="3">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Existing Users -->
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <select name="role">
                            <option value="User" <?= $user['role'] === 'User' ? 'selected' : '' ?>>User</option>
                            <option value="Admin" <?= $user['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                        <button type="submit" name="update_role">Update</button>
                    </form>
                </td>
                <td>
                    <form method="POST" onsubmit="return confirm('Are you sure?');">
                        <input type="hidden" name="delete_user" value="<?= $user['id'] ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>

            <!-- Add New User Row -->
            <tr>
                <form method="POST">
                    <td colspan="2">
                        <input type="text" name="new_username" placeholder="New username" required>
                    </td>
                    <td colspan="2">
                        <button type="submit" name="add_user">Add User (pass: testuser)</button>
                    </td>
                </form>
            </tr>
        </tbody>
    </table>
</body>
</html>

