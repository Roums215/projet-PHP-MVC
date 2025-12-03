<h2>Gestion des Pages</h2>
<a href="/admin/pages/add" style="">+ Créer une page</a>
<br><br>
<table width="100%">
    <tr>
        <th>Titre</th>
        <th>Lien</th>
        <th>Action</th>
    </tr>
    <?php foreach($pages as $p): ?>
    <tr>
        <td><?= htmlspecialchars($p['title']) ?></td>
        <td><a href="/<?= $p['slug'] ?>" target="_blank">Voir la page</a></td>
        <td><a href="/admin/pages/delete?id=<?= $p['id'] ?>" style="color:red">Supprimer</a></td>
    </tr>
    <?php endforeach; ?>
</table>