<h2>Mot de passe oublié</h2>

<p>Entrez votre adresse email pour recevoir un lien de réinitialisation de mot de passe.</p>

<?php if (!empty($message)): ?>
    <div style="padding: 10px; margin-bottom: 15px; border-radius: 4px; 
        <?= isset($success) && $success ? 'background-color: #d4edda; color: #155724;' : 'background-color: #f8d7da; color: #721c24;' ?>">
        <?= $message ?>
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

    <button type="submit" style="cursor: pointer; padding: 8px 15px;">Envoyer le lien</button>
</form>

<p style="margin-top: 15px;">
    <a href="/login">Retour à la connexion</a>
</p>
