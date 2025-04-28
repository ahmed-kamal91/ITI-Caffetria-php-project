<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sidebar Toggle Menu</title>
  <!-- FontAwesome 6 CDN -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Optional: smooth transition for ul show/hide */
    #sidebarMenu {
      transition: all 0.3s ease;
    }
  </style>
</head>

<body class="bg-dark text-light">

  <div class="container border border-white">
    <div class="row">
      
      <!-- Left Sidebar -->
      <div class="col-md-1 bg-secondary p-0 min-vh-100 d-flex flex-column align-items-center">

        <!-- Button at top -->
        <button id="toggleBtn" class="btn btn-light my-3">
          <i class="fas fa-bars"></i>
        </button>

        <!-- Sidebar Links -->
        <ul id="sidebarMenu" class="nav flex-column w-100 text-center" style="display: none;">
          <li class="nav-item">
            <a class="nav-link text-light d-flex flex-column align-items-center" href="#">
              <i class="fa-solid fa-user fa-2x"></i>
              <span>sfsdf</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link text-light d-flex flex-column align-items-center" href="#">
              <i class="fa-solid fa-user fa-2x"></i>
              <span>sdad</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link text-light d-flex flex-column align-items-center" href="#">
              <i class="fa-solid fa-user fa-2x"></i>
              <span>dfasdf</span>
            </a>
          </li>
        </ul>

      </div>

      <!-- Main Content -->
      <div class="col-md-11 p-4">
        <h1>Main Content</h1>
        <p>This is the main area where you can add your page content. It takes most of the width.</p>
      </div>

    </div>
  </div>

  <!-- Bootstrap Bundle JS (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Simple JavaScript to toggle menu -->
  <script>
    const toggleBtn = document.getElementById('toggleBtn');
    const sidebarMenu = document.getElementById('sidebarMenu');

    toggleBtn.addEventListener('click', () => {
      if (sidebarMenu.style.display === 'none') {
        sidebarMenu.style.display = 'flex';
        sidebarMenu.classList.add('flex-column', 'align-items-center');
      } else {
        sidebarMenu.style.display = 'none';
      }
    });
  </script>

</body>
</html>
