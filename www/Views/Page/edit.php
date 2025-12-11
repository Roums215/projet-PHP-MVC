<h2>Éditer la page</h2>

<?php if (!empty($message)): ?>
    <div style="padding: 10px; margin-bottom: 15px; border-radius: 4px; background-color: #f8d7da; color: #721c24;">
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<form method="POST" style="max-width: 600px;">
    <div style="margin-bottom: 15px;">
        <label for="title">Titre :</label><br>
        <input 
            type="text" 
            id="title" 
            name="title" 
            value="<?= isset($page['title']) ? htmlspecialchars($page['title']) : '' ?>"
            placeholder="Titre de la page"
            required
            style="width: 100%; padding: 8px; box-sizing: border-box;"
        >
    </div>

    <div style="margin-bottom: 15px;">
        <label for="slug">Slug (URL) :</label><br>
        <input 
            type="text" 
            id="slug" 
            name="slug" 
            value="<?= isset($page['slug']) ? htmlspecialchars($page['slug']) : '' ?>"
            placeholder="slug-de-la-page"
            style="width: 100%; padding: 8px; box-sizing: border-box;"
        >
        <small style="color: #666;">Généralement en minuscules avec des tirets.</small>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="content">Contenu :</label><br>
        <textarea 
            id="content" 
            name="content" 
            rows="12" 
            required
            placeholder="Contenu de la page"
            style="width: 100%; padding: 8px; box-sizing: border-box;"
        ><?= isset($page['content']) ? htmlspecialchars($page['content']) : '' ?></textarea>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="is_published">
            <input 
                type="checkbox" 
                id="is_published" 
                name="is_published"
                <?= isset($page['is_published']) && $page['is_published'] ? 'checked' : '' ?>
            >
            Page publiée
        </label>
    </div>

    <button type="submit" style="cursor: pointer; padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px;">Mettre à jour</button>
    <a href="/admin/pages" style="padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; display: inline-block; margin-left: 10px;">Annuler</a>
</form>
