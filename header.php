
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home/ twitter</title>
    <link rel="stylesheet"href="css/style.css">
    <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        .navbar {
    margin-bottom: 30px;
    background-color: #ffc0cb;
    height: 70px;
    
    
    
}

.navbar-brand, .nav-link {
    color: #040404 !important; 
}

.navbar-brand:hover, .nav-link:hover {
    color: #ffffff !important; 
}

#logoutBtn {
    background-color: #dc3545; 
    border: none;
}

#logoutBtn:hover {
    background-color: #c82333; 
}
    </style>
</head>
<body>
     <!-- Navigation Bar -->
     <nav class="navbar navbar-expand-lg fixed-top">
        <a class="navbar-brand" href="#">
            <img src="images/twitter.png" width="30" height="30" class="d-inline-block align-top " alt="">
            pink twitter
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"style="background-color: white ;"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-danger" id="logoutBtn" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    
</body>
</html>