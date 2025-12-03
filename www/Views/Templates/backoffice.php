<!DOCTYPE html>
<html>
<head>
    <title>Backoffice</title>
</head>
<body>
        <div class="sidebar">
        <h2 style="text-align: center;">Admin Panel</h2>
        <br>
        <a href="/">Retour au site</a>
        <a href="/admin/pages">Mes Pages</a>
        <a href="/admin/users">Les Utilisateurs</a>
        <br>
        <a href="/logout" style="">Déconnexion</a>
    </div>
    <h1>Welcome to the Backoffice</h1>
    <p>This is a secure area for authorized personnel only.</p>

    <?php include $this->viewPath;?>
</body>
</html>