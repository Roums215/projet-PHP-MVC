<h2>Mes Pages</h2>
<a href="/my-pages/add" style="padding: 8px 15px; background-color: #28a745; color: white; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 15px;">+ Créer une page</a>
<br><br>

<?php if (empty($pages)): ?>
    <p>Vous n'avez pas encore créé de page.</p>
<?php else: ?>
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
        <tr style="background-color: #f8f9fa;">
            <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Titre</th>
            <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Slug</th>
            <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Publiée</th>
            <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Actions</th>
        </tr>
        <?php foreach($pages as $p): ?>
        <tr style="border: 1px solid #ddd;">
            <td style="padding: 12px; border: 1px solid #ddd;"><?= htmlspecialchars($p['title']) ?></td>
            <td style="padding: 12px; border: 1px solid #ddd;"><code><?= htmlspecialchars($p['slug']) ?></code></td>
            <td style="padding: 12px; border: 1px solid #ddd;">
                <?php if ($p['is_published']): ?>
                    <span style="color: green; font-weight: bold;">✓ Oui</span>
                <?php else: ?>
                    <span style="color: red;">✗ Non</span>
                <?php endif; ?>
            </td>
            <td style="padding: 12px; border: 1px solid #ddd;">
                <a href="/<?= htmlspecialchars($p['slug']) ?>" target="_blank" style="padding: 5px 10px; background-color: #17a2b8; color: white; text-decoration: none; border-radius: 3px; margin-right: 5px;">Voir</a>
                <a href="/my-pages/edit?id=<?= $p['id'] ?>" style="padding: 5px 10px; background-color: #007bff; color: white; text-decoration: none; border-radius: 3px; margin-right: 5px;">Éditer</a>
                <a href="/my-pages/delete?id=<?= $p['id'] ?>" style="padding: 5px 10px; background-color: #dc3545; color: white; text-decoration: none; border-radius: 3px;" onclick="return confirm('Êtes-vous sûr ?');">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
