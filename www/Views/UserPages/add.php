<h2>Créer une nouvelle page</h2>

<?php if (!empty($message)): ?>
    <div style="padding: 10px; margin-bottom: 15px; border-radius: 4px; background-color: #f8d7da; color: #721c24;">
        <?= $message ?>
    </div>
<?php endif; ?>

<form method="POST" style="max-width: 600px;">
    <div style="margin-bottom: 15px;">
        <label for="title">Titre :</label><br>
        <input 
            type="text" 
            id="title" 
            name="title" 
            value="<?= isset($oldTitle) ? htmlspecialchars($oldTitle) : '' ?>"
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
            value="<?= isset($oldSlug) ? htmlspecialchars($oldSlug) : '' ?>"
            placeholder="slug-de-la-page"
            style="width: 100%; padding: 8px; box-sizing: border-box;"
        >
        <small style="color: #666;">Généralement en minuscules avec des tirets. Si vide, sera généré à partir du titre.</small>
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
        ><?= isset($oldContent) ? htmlspecialchars($oldContent) : '' ?></textarea>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="is_published">
            <input type="checkbox" id="is_published" name="is_published" <?= isset($oldIsPublished) && $oldIsPublished ? 'checked' : '' ?>>
            Page publiée
        </label>
    </div>

    <button type="submit" style="cursor: pointer; padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px;">Créer la page</button>
    <a href="/my-pages" style="padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; display: inline-block; margin-left: 10px;">Annuler</a>
</form>
