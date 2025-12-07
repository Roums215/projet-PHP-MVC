<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backoffice - Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            display: flex;
        }
        
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 30px 0;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
        }
        
        .sidebar h2 {
            padding: 0 20px;
            margin-bottom: 30px;
            font-size: 20px;
            border-bottom: 2px solid #34495e;
            padding-bottom: 15px;
        }
        
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: background-color 0.3s;
            border-left: 4px solid transparent;
        }
        
        .sidebar a:hover {
            background-color: #34495e;
            border-left-color: #3498db;
        }
        
        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 30px;
        }
        
        .header {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            color: #2c3e50;
            margin: 0;
        }
        
        .content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="/">🏠 Retour au site</a>
        <a href="/admin/pages">📄 Gestion Pages</a>
        <a href="/admin/users">👥 Gestion Utilisateurs</a>
        <hr style="border: none; border-top: 1px solid #34495e; margin: 20px 0;">
        <a href="/logout" style="color: #e74c3c;">🚪 Déconnexion</a>
    </div>
    
    <div class="main-content">
        <div class="header">
            <h1>Backoffice</h1>
            <div>
                <?php if(isset($_SESSION['user'])): ?>
                    <small style="color: #666;">Connecté en tant que <?= htmlspecialchars($_SESSION['user']['firstname'] ?? 'Admin') ?></small>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="content">
            <?php include $this->viewPath;?>
        </div>
    </div>
</body>
</html>