<h2>Inscription</h2>

<?php if (!empty($message)): ?>
    <div style="">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<form method="post">
    <div style="margin-bottom: 10px;">
        <label for="firstname">Prénom :</label><br>
        <input
            type="text"
            id="firstname"
            name="firstname"
            required
            value="<?= isset($oldFirstname) ? htmlspecialchars($oldFirstname) : '' ?>"
        >
    </div>

    <div style="margin-bottom: 10px;">
        <label for="lastname">Nom de famille :</label><br>
        <input
            type="text"
            id="lastname"
            name="lastname"
            required
            value="<?= isset($oldLastname) ? htmlspecialchars($oldLastname) : '' ?>"
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
        >
    </div>

    <div style="margin-bottom: 10px;">
        <label for="password">Mot de passe (mini 6 caractères) :</label><br>
        <input type="password" id="password" name="password" required>
    </div>

    <div style="margin-bottom: 10px;">
        <label for="password_confirm">Confirmation du mot de passe :</label><br>
        <input type="password" id="password_confirm" name="password_confirm" required>
    </div>

    <button type="submit" style="cursor: pointer; padding: 5px 15px;">Créer mon compte</button>
</form>

<p>Deja un compte mon poulet ? <a href="/login">Se connecter</a></p>