<div class="home-header" style="text-align: center; margin-bottom: 40px; padding: 40px 0;">
    <h1 style="margin: 0 0 10px 0; color: #333;">Bienvenue sur notre site</h1>
    <p style="font-size: 18px; color: #666; margin: 0;">Explorez nos pages et contenus</p>
</div>

<?php if (!empty($pages) && count($pages) > 0): ?>
    <div class="pages-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
        <?php foreach ($pages as $page): ?>
            <div class="page-card" style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; background-color: #f9f9f9; transition: box-shadow 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <h3 style="margin: 0 0 10px 0; color: #333;">
                    <a href="/<?= htmlspecialchars($page['slug']) ?>" style="color: #007bff; text-decoration: none;">
                        <?= htmlspecialchars($page['title']) ?>
                    </a>
                </h3>
                <p style="color: #666; margin: 0 0 15px 0; line-height: 1.5;">
                    <?= htmlspecialchars(substr($page['content'], 0, 150)) ?>...
                </p>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <small style="color: #999;">
                        <?= date('d/m/Y', strtotime($page['created_at'])) ?>
                    </small>
                    <a href="/<?= htmlspecialchars($page['slug']) ?>" style="color: #007bff; text-decoration: none; font-weight: bold;">
                        Lire la suite →
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div style="text-align: center; padding: 40px; background-color: #f9f9f9; border-radius: 8px;">
        <p style="color: #666; font-size: 18px;">Aucune page publiée pour le moment.</p>
        <p style="color: #999;">Revenez plus tard pour découvrir nos contenus.</p>
    </div>
<?php endif; ?>
