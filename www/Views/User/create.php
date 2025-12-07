<h2>Créer un nouvel utilisateur</h2>

<?php if (!empty($message)): ?>
    <div style="padding: 10px; margin-bottom: 15px; border-radius: 4px; background-color: #f8d7da; color: #721c24;">
        <?= $message ?>
    </div>
<?php endif; ?>

<form method="POST" style="max-width: 500px;">
    <div style="margin-bottom: 15px;">
        <label for="firstname">Prénom :</label><br>
        <input 
            type="text" 
            id="firstname" 
            name="firstname" 
            value="<?= isset($oldFirstname) ? htmlspecialchars($oldFirstname) : '' ?>"
            placeholder="Prénom"
            style="width: 100%; padding: 8px; box-sizing: border-box;"
        >
    </div>

    <div style="margin-bottom: 15px;">
        <label for="lastname">Nom de famille :</label><br>
        <input 
            type="text" 
            id="lastname" 
            name="lastname" 
            value="<?= isset($oldLastname) ? htmlspecialchars($oldLastname) : '' ?>"
            placeholder="Nom"
            style="width: 100%; padding: 8px; box-sizing: border-box;"
        >
    </div>

    <div style="margin-bottom: 15px;">
        <label for="email">Email :</label><br>
        <input 
            type="email" 
            id="email" 
            name="email" 
            value="<?= isset($oldEmail) ? htmlspecialchars($oldEmail) : '' ?>"
            placeholder="Email"
            required
            style="width: 100%; padding: 8px; box-sizing: border-box;"
        >
    </div>

    <div style="margin-bottom: 15px;">
        <label for="password">Mot de passe :</label><br>
        <input 
            type="password" 
            id="password" 
            name="password" 
            required
            placeholder="Minimum 8 caractères (majuscule, minuscule, chiffre)"
            style="width: 100%; padding: 8px; box-sizing: border-box;"
        >
    </div>

    <div style="margin-bottom: 15px;">
        <label for="password_confirm">Confirmation du mot de passe :</label><br>
        <input 
            type="password" 
            id="password_confirm" 
            name="password_confirm" 
            required
            placeholder="Confirmer le mot de passe"
            style="width: 100%; padding: 8px; box-sizing: border-box;"
        >
    </div>

    <div style="margin-bottom: 15px;">
        <label for="role">Rôle :</label><br>
        <select id="role" name="role" style="width:100%; padding:8px;">
            <option value="user" <?= (isset($oldRole) && $oldRole === 'user') ? 'selected' : '' ?>>Utilisateur</option>
            <option value="admin" <?= (isset($oldRole) && $oldRole === 'admin') ? 'selected' : '' ?>>Administrateur</option>
        </select>
    </div>

    <button type="submit" style="cursor: pointer; padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px;">Créer l'utilisateur</button>
    <a href="/admin/users" style="padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; display: inline-block; margin-left: 10px;">Annuler</a>
</form>
