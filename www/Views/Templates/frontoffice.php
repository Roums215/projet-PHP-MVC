<!DOCTYPE html>
<html>
    <head>
        <title>Frontoffice</title>
    </head>
    <body>
            <nav>
        <a href="/">Accueil</a>
        <a href="/contact">Contact</a>
        

        <?php if (isset($_SESSION['user'])): ?>
            <span style="">|</span>
            <a href="/admin/pages">Gérer les Pages</a>
            <a href="/admin/users">Gérer les Users</a>
            
            <span style="float: right;">
                Bonjour <strong><?= htmlspecialchars($_SESSION['user']['firstname']) ?></strong>
                <a href="/logout" style="">Se déconnecter</a>
            </span>
        <?php else: ?>
            <span style="float: right;">
                <a href="/login">Se connecter</a>
                <a href="/register" style="">S'inscrire</a>
            </span>
        <?php endif; ?>
    </nav>
        <h1>Welcome to the Frontoffice</h1>
        <p>This is the WEBSITE</p>

        <?php include $this->viewPath;?>

        <footer>
            <marquee>© Skrzypczyk</marquee>
        </footer>
    </body>
</html>