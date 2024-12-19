<?php
// db.php
require_once 'config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Insert function
function insertEntry($user_id, $site_name, $url, $username, $password, $comment) {
    global $pdo;
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO accounts (user_id, site_name, url, username, comment) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $site_name, $url, $username, $comment]);
        $account_id = $pdo->lastInsertId();
        $stmt = $pdo->prepare("INSERT INTO passwords (account_id, password) VALUES (?, AES_ENCRYPT(?, :aes_key))");
        $stmt->bindParam(1, $account_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $password, PDO::PARAM_STR);
        $stmt->bindParam(':aes_key', AES_KEY, PDO::PARAM_STR);
        $stmt->execute();
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return "Insertion failed: " . $e->getMessage();
    }
}

// Search function
function searchEntries($searchTerm) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT
                users.first_name, users.last_name, users.email,
                accounts.site_name, accounts.url, accounts.comment, accounts.username,
                AES_DECRYPT(passwords.password, :aes_key) AS decrypted_password
            FROM accounts
            JOIN users ON accounts.user_id = users.id
            JOIN passwords ON passwords.account_id = accounts.id
            WHERE accounts.site_name LIKE :search
               OR accounts.url LIKE :search
               OR accounts.username LIKE :search
               OR users.email LIKE :search
        ");
        $searchParam = "%$searchTerm%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        $stmt->bindParam(':aes_key', AES_KEY, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return "Search failed: " . $e->getMessage();
    }
}

// Update function
function updateEntry($current_attr, $new_value, $query_attr, $pattern) {
    global $pdo;
    try {
        if ($current_attr === 'password') {
            $stmt = $pdo->prepare("UPDATE passwords JOIN accounts ON passwords.account_id = accounts.id
                                   SET passwords.password = AES_ENCRYPT(:new_value, :aes_key)
                                   WHERE accounts.$query_attr LIKE :pattern");
            $stmt->bindParam(':new_value', $new_value, PDO::PARAM_STR);
            $stmt->bindParam(':aes_key', AES_KEY, PDO::PARAM_STR);
        } else {
            $stmt = $pdo->prepare("UPDATE accounts SET $current_attr = :new_value WHERE $query_attr LIKE :pattern");
            $stmt->bindParam(':new_value', $new_value, PDO::PARAM_STR);
        }
        $patternParam = "%$pattern%";
        $stmt->bindParam(':pattern', $patternParam, PDO::PARAM_STR);
        $stmt->execute();
        return true;
    } catch (Exception $e) {
        return "Update failed: " . $e->getMessage();
    }
}

// Delete function
function deleteEntry($current_attr, $pattern) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE accounts, passwords FROM accounts JOIN passwords ON accounts.id = passwords.account_id WHERE accounts.$current_attr LIKE :pattern");
        $patternParam = "%$pattern%";
        $stmt->bindParam(':pattern', $patternParam, PDO::PARAM_STR);
        $stmt->execute();
        return true;
    } catch (Exception $e) {
        return "Deletion failed: " . $e->getMessage();
    }
}
?>
