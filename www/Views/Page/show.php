<?php if (isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? 'user') === 'admin' && isset($page['is_published']) && $page['is_published'] == 0): ?>
    <div style="background:#fff3cd;color:#856404;padding:8px;border-radius:4px;margin-bottom:12px;">
        Visualisation — Page NON PUBLIÉE (visible que par l'admin)
    </div>
<?php endif; ?>

<article>
    <h1><?= htmlspecialchars($page['title']) ?></h1>
    <div class="content">
        <?= nl2br(htmlspecialchars($page['content'])) ?>
    </div>
</article>

<div class="page-footer" style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center;">
    <a href="/" style="color: #007bff; text-decoration: none;">← Retour à l'accueil</a>
</div>
