<h2>Connexion</h2>

<?php if (!empty($message)): ?>
    <div style="padding: 10px; margin-bottom: 15px; border-radius: 4px; background-color: #f8d7da; color: #721c24;">
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<form method="POST">
    <div style="margin-bottom: 10px;">
        <label for="email">Email :</label><br>
        <input 
            type="email" 
            id="email" 
            name="email" 
            required 
            value="<?= isset($oldEmail) ? htmlspecialchars($oldEmail) : '' ?>"
            placeholder="Email"
        >
    </div>

    <div style="margin-bottom: 10px;">
        <label for="password">Mot de passe :</label><br>
        <input type="password" id="password" name="password" required placeholder="Mot de passe">
    </div>

    <button type="submit" style="cursor: pointer; padding: 8px 15px;">Se connecter</button>
</form>

<p style="margin-top: 15px;">
    Pas encore de compte ? <a href="/register">S'inscrire</a>
</p>

<p>
    <a href="/forgot-password">Mot de passe oublié ?</a>
</p>