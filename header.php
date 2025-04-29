<html lang="en">
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container d-flex align-items-center justify-content-between">

        
            <!-- logo on the left -->
            <a class="navbar-brand d-flex align-items-center" href="#">
                <h2 class="d-flex align-items-center mb-0">
                    <img src="./../uploads/icons/logo3.png" alt="" width="80px">
                    affetria
                </h2>
            </a>

            <!-- Button on the right -->
            <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#waiterNote'>click</button>
            <div class="user-info d-flex justify-content-end align-items-center">
                <span class="me-2"><?php echo $userName; ?></span><i class="fa-solid fa-user"></i>
            </div>

        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>





