<h2>Connexion</h2>

<?php if (!empty($message)): ?>
    <div style="background-color: ">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<form method="post">

    <div style="margin-bottom: 10px;">
        <label for="email">Email :</label><br>
        <input 
            type="email" 
            id="email" 
            name="email" 
            required 
            value="<?= isset($oldEmail) ? htmlspecialchars($oldEmail) : '' ?>"
        >
    </div>

    <div style="margin-bottom: 10px;">
        <label for="password">Mot de passe :</label><br>
        <input type="password" id="password" name="password" required>
    </div>

    <button type="submit" style="cursor: pointer; padding: 5px 15px;">Se connecter</button>

</form>

<p>Pas encore de compte ? <a href="/register">S'inscrire</a></p>