<div class="page-header" style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #ddd;">
    <h1 style="margin: 0; color: #333;"><?= htmlspecialchars($page['title']) ?></h1>
    <small style="color: #666;">Publié le <?= date('d/m/Y', strtotime($page['created_at'])) ?></small>
</div>

<article class="page-content" style="line-height: 1.6; color: #333;">
    <?= nl2br(htmlspecialchars($page['content'])) ?>
</article>

<div class="page-footer" style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center;">
    <a href="/" style="color: #007bff; text-decoration: none;">← Retour à l'accueil</a>
</div>
