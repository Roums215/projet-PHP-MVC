<h2>Éditer l'utilisateur</h2>

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
            value="<?= isset($user['firstname']) ? htmlspecialchars($user['firstname']) : '' ?>"
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
            value="<?= isset($user['lastname']) ? htmlspecialchars($user['lastname']) : '' ?>"
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
            value="<?= isset($user['email']) ? htmlspecialchars($user['email']) : '' ?>"
            placeholder="Email"
            required
            style="width: 100%; padding: 8px; box-sizing: border-box;"
        >
    </div>

    <div style="margin-bottom: 15px;">
        <label for="is_active">
            <input 
                type="checkbox" 
                id="is_active" 
                name="is_active"
                <?= isset($user['is_active']) && $user['is_active'] ? 'checked' : '' ?>
            >
            Utilisateur actif
        </label>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="role">Rôle :</label><br>
        <select id="role" name="role" style="width:100%; padding:8px;" <?= (isset($_SESSION['user']) && $_SESSION['user']['id'] === $user['id']) ? 'disabled' : '' ?>>
            <option value="user" <?= (isset($user['role']) && $user['role'] === 'user') ? 'selected' : '' ?>>Utilisateur</option>
            <option value="admin" <?= (isset($user['role']) && $user['role'] === 'admin') ? 'selected' : '' ?>>Administrateur</option>
        </select>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $user['id']): ?>
            <small style="color: #666; display: block; margin-top: 5px;">Vous ne pouvez pas modifier votre propre rôle</small>
        <?php endif; ?>
    </div>

    <button type="submit" style="cursor: pointer; padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px;">Mettre à jour</button>
    <a href="/admin/users" style="padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; display: inline-block; margin-left: 10px;">Annuler</a>
</form>
