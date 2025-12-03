
<h2>Liste des utilisateurs</h2>

<?php if (empty($users)): ?>
    <p>Aucun utilisateur trouvé.</p>
<?php else: ?>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Actif</th>
        </tr>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['id']) ?></td>
                <td><?= htmlspecialchars($u['firstname'] ?? '') ?></td>
                <td><?= htmlspecialchars($u['lastname'] ?? '') ?></td>
                <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
                <td><?= ($u['is_active'] ? 'Oui' : 'Non') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
