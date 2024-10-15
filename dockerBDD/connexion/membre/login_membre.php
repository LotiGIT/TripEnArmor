<?php
include('../connect_params.php');
session_start();

$error = "";

try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];



        $stmt = $dbh->prepare("SELECT * FROM sae._compte WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->errorInfo()[0] !== '00000') {
            error_log("SQL Error: " . print_r($stmt->errorInfo(), true));
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log(print_r($user, true));
        
        if ($user && $user['motdepasse'] === $mdp) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['token'] = bin2hex(random_bytes(32));
            header('Location: connected_membre.php?token=' . $_SESSION['token']);
            exit();
        } else {
            $error = "Email ou mot de passe incorrect";
        }
    }
} catch (PDOException $e) {
    echo "Erreur !: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion Membre</title>
</head>
<body>

<?php if (!empty($error)): ?>
    <div style="color: red;"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form action="" method="post">
    <label for="email">Email :</label>
    <input type="text" name="email" id="email" required>

    <br>

    <label for="mdp">mot de passe :</label>
    <input type="password" name="mdp" id="mdp" required>

    <br>

    <input type="submit" value="Se connecter">
</form>

</body>
</html>