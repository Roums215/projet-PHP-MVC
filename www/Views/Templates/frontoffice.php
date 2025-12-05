<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Site Web</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f5f5f5;
                color: #333;
            }
            
            nav {
                background-color: #2c3e50;
                padding: 15px 30px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 20px;
            }
            
            nav a {
                color: #ecf0f1;
                text-decoration: none;
                padding: 8px 15px;
                border-radius: 4px;
                transition: background-color 0.3s;
            }
            
            nav a:hover {
                background-color: #34495e;
            }
            
            nav span {
                color: #7f8c8d;
            }
            
            .nav-left {
                display: flex;
                gap: 10px;
            }
            
            .nav-right {
                display: flex;
                gap: 10px;
                align-items: center;
            }
            
            .nav-right strong {
                color: #ecf0f1;
                margin-right: 10px;
            }
            
            main {
                max-width: 1200px;
                margin: 40px auto;
                padding: 0 20px;
                min-height: 60vh;
            }
            
            footer {
                background-color: #2c3e50;
                color: #ecf0f1;
                text-align: center;
                padding: 30px;
                margin-top: 60px;
            }
            
            footer p {
                margin: 0;
            }
        </style>
    </head>
    <body>
        <nav>
            <div class="nav-left">
                <a href="/">🏠 Accueil</a>
                <a href="/contact">📧 Contact</a>
            </div>
            
            <div class="nav-right">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="/admin/pages">📄 Gérer Pages</a>
                    <a href="/admin/users">👥 Gérer Users</a>
                    <span>|</span>
                    <strong>Bonjour <?= htmlspecialchars($_SESSION['user']['firstname']) ?></strong>
                    <a href="/logout">🚪 Déconnexion</a>
                <?php else: ?>
                    <a href="/login">🔐 Connexion</a>
                    <a href="/register">✍️ S'inscrire</a>
                <?php endif; ?>
            </div>
        </nav>

        <main>
            <?php include $this->viewPath;?>
        </main>

        <footer>
            <p>&copy; 2024 - Tous droits réservés | Développé avec PHP MVC</p>
        </footer>
    </body>
</html>