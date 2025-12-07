
<h2>Gestion des Utilisateurs</h2>
<a href="/admin/users/create" style="padding: 8px 15px; background-color: #28a745; color: white; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 15px;">+ Créer un utilisateur</a>
<br><br>

<?php if (empty($users)): ?>
    <p>Aucun utilisateur trouvé.</p>
<?php else: ?>
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
        <tr style="background-color: #f8f9fa;">
            <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">ID</th>
            <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Prénom</th>
            <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Nom</th>
            <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Email</th>
            <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Rôle</th>
            <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Actif</th>
            <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Actions</th>
        </tr>
        <?php foreach ($users as $u): ?>
            <tr style="border: 1px solid #ddd;">
                <td style="padding: 12px; border: 1px solid #ddd;"><?= htmlspecialchars($u['id']) ?></td>
                <td style="padding: 12px; border: 1px solid #ddd;"><?= htmlspecialchars($u['firstname'] ?? '') ?></td>
                <td style="padding: 12px; border: 1px solid #ddd;"><?= htmlspecialchars($u['lastname'] ?? '') ?></td>
                <td style="padding: 12px; border: 1px solid #ddd;"><?= htmlspecialchars($u['email'] ?? '') ?></td>
                <td style="padding: 12px; border: 1px solid #ddd;">
                    <?= htmlspecialchars($u['role'] ?? 'user') ?>
                </td>
                <td style="padding: 12px; border: 1px solid #ddd;">
                    <?php if ($u['is_active']): ?>
                        <span style="color: green; font-weight: bold;">✓ Oui</span>
                    <?php else: ?>
                        <span style="color: red;">✗ Non</span>
                    <?php endif; ?>
                </td>
                <td style="padding: 12px; border: 1px solid #ddd;">
                    <a href="/admin/users/edit?id=<?= $u['id'] ?>" style="padding: 5px 10px; background-color: #007bff; color: white; text-decoration: none; border-radius: 3px; margin-right: 5px;">Éditer</a>
                    <a href="/admin/users/delete?id=<?= $u['id'] ?>" style="padding: 5px 10px; background-color: #dc3545; color: white; text-decoration: none; border-radius: 3px;" onclick="return confirm('Êtes-vous sûr ?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
