<?php
// sidebar.php
?>
<div class="sidebar" id="sidebar">
    <div class="toggle-btn" id="toggleBtn">
        <i class="fas fa-chevron-left"></i>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === '../pages/userMainPage.php' ? 'active' : ''; ?>" href="../pages/userMainPage.php">
                <i class="fas fa-home"></i><span>Home</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === '../orders/my_orders.php' ? 'active' : ''; ?>" href="../orders/my_orders.php">
                <i class="fas fa-box"></i><span>Drinks</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === '../user/logout.php' ? 'active' : ''; ?>" href="../user/logout.php">
            <i class="fas fa-sign-out-alt"></i><span>logout</span>
            </a>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === '../orders/my_orders.php' ? 'active' : ''; ?>" href="../orders/my_orders.php">
                <i class="fas fa-shopping-cart"></i><span>Manual Order</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === '../drinks/adminChecks.php' ? 'active' : ''; ?>" href="../drinks/adminChecks.php">
                <i class="fas fa-file-invoice"></i><span>Checks</span>
            </a>
        </li> -->
        <!-- <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'admin.php' ? 'active' : ''; ?>" href="admin.php">
                <i class="fas fa-cog"></i><span>Admin</span>
            </a>
        </li> -->
    </ul>
</div>

<style>
.sidebar {
    width: 250px;
    background-color: rgb(116, 119, 121);
    border-right: 1px solid #dee2e6;
    padding: 20px 0;
    transition: all 0.3s;
    position: relative;
}
.sidebar.collapsed {
    width: 60px;
    overflow: hidden;
}
.sidebar.collapsed .nav-link {
    padding: 10px;
    text-align: center;
}
.sidebar.collapsed .nav-link span {
    display: none;
}
.sidebar.collapsed .nav-link i {
    margin-right: 0;
    font-size: 1.2rem;
}
.sidebar .nav-link {
    color:rgb(255, 255, 255);
    font-weight: 500;
    padding: 10px 20px;
    border-radius: 0;
    white-space: nowrap;
}
.sidebar .nav-link:hover {
    background-color: #e9ecef;
}
.sidebar .nav-link.active {
    background-color: #e9ecef;
    font-weight: 600;
}
.sidebar .nav-link i {
    margin-right: 10px;
    transition: all 0.3s;
}
.toggle-btn {
    position: absolute;
    right: -15px;
    top: 20px;
    width: 30px;
    height: 30px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 1;
    transition: all 0.3s;
}
.toggle-btn:hover {
    background-color: #e9ecef;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    
    // Toggle sidebar functionality
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        
        // Change the icon based on state
        const icon = this.querySelector('i');
        if (sidebar.classList.contains('collapsed')) {
            icon.classList.remove('fa-chevron-left');
            icon.classList.add('fa-chevron-right');
        } else {
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-left');
        }
        
        // Store the state in localStorage
        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    });
    
    // Check for saved state on page load
    const savedState = localStorage.getItem('sidebarCollapsed');
    if (savedState === 'true') {
        sidebar.classList.add('collapsed');
        const icon = toggleBtn.querySelector('i');
        icon.classList.remove('fa-chevron-left');
        icon.classList.add('fa-chevron-right');
    }
});
</script>