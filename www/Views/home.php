<div class="home-header" style="text-align: center; margin-bottom: 40px; padding: 40px 0;">
    <h1 style="margin: 0 0 10px 0; color: #333;">Bienvenue sur notre site</h1>
    <p style="font-size: 18px; color: #666; margin: 0;">Explorez nos pages et contenus</p>
    
    <?php if (isset($_SESSION['user'])): ?>
        <?php 
            $isAdmin = ($_SESSION['user']['role'] ?? 'user') === 'admin';
            $link = $isAdmin ? '/admin/pages' : '/my-pages';
            $text = $isAdmin ? '📄 Gérer les pages' : '📄 Mes pages';
        ?>
        <div style="margin-top: 20px;">
            <a href="<?= $link ?>" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; display: inline-block;">
                <?= $text ?>
            </a>
        </div>
    <?php else: ?>
        <div style="margin-top: 20px;">
            <a href="/login" style="padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 4px; display: inline-block; margin-right: 10px;">
                🔐 Se connecter
            </a>
            <a href="/register" style="padding: 10px 20px; background-color: #17a2b8; color: white; text-decoration: none; border-radius: 4px; display: inline-block;">
                ✍️ S'inscrire
            </a>
        </div>
    <?php endif; ?>
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
