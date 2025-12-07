<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Activation</title>

</head>
<body>
    <div class="container">
        <h1>Activation de compte</h1>
        
        <?php if($success): ?>
            <div class="icon success">ACTIVER !</div>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
        <?php else: ?>
            <div class="icon error">ERREUR</div>
            <div class="message error"><?= htmlspecialchars($message) ?></div>
            <a href="/register" class="btn">Créer un compte</a>
        <?php endif; ?>
    </div>
</body>
</html>