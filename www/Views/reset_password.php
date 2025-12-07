<h2>Réinitialiser votre mot de passe</h2>

<?php if (!empty($message)): ?>
    <div style="padding: 10px; margin-bottom: 15px; border-radius: 4px; 
        <?= isset($success) && $success ? 'background-color: #d4edda; color: #155724;' : 'background-color: #f8d7da; color: #721c24;' ?>">
        <?= $message ?>
    </div>
<?php endif; ?>

<?php if (!isset($success) || !$success): ?>
    <form method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">
        
        <div style="margin-bottom: 10px;">
            <label for="password">Nouveau mot de passe :</label><br>
            <input 
                type="password" 
                id="password" 
                name="password" 
                required
                placeholder="Minimum 8 caractères (majuscule, minuscule, chiffre)"
            >
        </div>

        <div style="margin-bottom: 10px;">
            <label for="password_confirm">Confirmation du mot de passe :</label><br>
            <input 
                type="password" 
                id="password_confirm" 
                name="password_confirm" 
                required
                placeholder="Confirmer le mot de passe"
            >
        </div>

        <button type="submit" style="cursor: pointer; padding: 8px 15px;">Réinitialiser le mot de passe</button>
    </form>
<?php endif; ?>

<p style="margin-top: 15px;">
    <a href="/login">Retour à la connexion</a>
</p>
