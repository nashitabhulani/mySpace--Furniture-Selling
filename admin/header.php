<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../project_root/assets/css/admin.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #34495e;
            color: white;
            position: fixed;
            box-shadow: 2px 0 5px rgba(0,0,0,0.2);
            overflow-y: auto;
            padding-bottom: 50px;
        }
        .sidebar a, .sidebar ul li a {
            color: white;
        }
        .admin ul {
            list-style: none;
            padding: 20px;
           
        }
        .admin ul li {
            padding: 15px;
            margin-bottom: 2px;
            background-color: #2c3e50;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 5px;
            color:white;
        }
        .admin ul li:hover {
            background-color: #f2e9e4;
        }
        .admin ul li a {
            text-decoration: none;
            display: block;
            color:white;
        }
        .submenu ul {
            display: none;
            padding-left: 20px;
            color:white;
        }
        .submenu:hover ul {
            display: block;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.submenu > a').click(function() {
            $(this).next('ul').slideToggle('fast');
            return false; // Prevent the default action of the anchor
        });
    });
    </script>
</head>
<body>
<div class="sidebar">
    <div class="admin">
        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li class="submenu">Users
                <ul>
                    <li><a href="view_users.php">View Users</a></li>
                </ul>
            </li>
            <li class="submenu">Orders
                <ul>
                    <li><a href="view_orders.php">View Orders</a></li>
                </ul>
            </li>
            <li class="submenu">Products
                <ul>
                    <li><a href="add-products.php">Add Products</a></li>
                    <li><a href="view-products.php">View Products</a></li>
                </ul>
            </li>
            <li class="submenu">Testimonials
                <ul>
                    <li><a href="manage_testimonials.php">View Testimonials</a></li>
                </ul>
            </li>
            <li class="submenu">Category
                <ul>
                    <li><a href="manage_category.php">Manage Categories</a></li>
                    <li><a href="add_category.php">Add Category</a></li>
                </ul>
            </li>
            <li class="submenu">Articles
                <ul>
                    <li><a href="view_articles.php">Manage Articles</a></li>
                    <li><a href="add_article.php">Add Article</a></li>
                </ul>
            </li>
            <li><a href="add-admin.php">Add Admin</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</div>
</body>
</html>
