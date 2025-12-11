<h2>Inscription</h2>

<?php if (!empty($message)): ?>
    <div style="padding: 10px; margin-bottom: 15px; border-radius: 4px; 
        <?= isset($success) && $success ? 'background-color: #d4edda; color: #155724;' : 'background-color: #f8d7da; color: #721c24;' ?>">
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<form method="POST">
    <div style="margin-bottom: 10px;">
        <label for="firstname">Prénom :</label><br>
        <input
            type="text"
            id="firstname"
            name="firstname"
            value="<?= isset($oldFirstname) ? htmlspecialchars($oldFirstname) : '' ?>"
            placeholder="Prénom"
        >
    </div>

    <div style="margin-bottom: 10px;">
        <label for="lastname">Nom de famille :</label><br>
        <input
            type="text"
            id="lastname"
            name="lastname"
            value="<?= isset($oldLastname) ? htmlspecialchars($oldLastname) : '' ?>"
            placeholder="Nom"
        >
    </div>

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
        <input 
            type="password" 
            id="password" 
            name="password" 
            required
            placeholder="Minimum 8 caractères (majuscule, minuscule, chiffre, caractère spécial)"
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

    <button type="submit" style="cursor: pointer; padding: 8px 15px; margin-top: 10px;">S'inscrire</button>
</form>

<p style="margin-top: 15px;">
    Déjà inscrit ? <a href="/login">Se connecter</a>
</p>