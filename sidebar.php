
<div class="sidebar" id="sidebar">

    <ul class="nav flex-column">

        <li class="nav-item">
            <a class="nav-link" href="../pages/adminMainPage.php">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../drinks/adminViewDrinks.php">
                <i class="fas fa-box"></i>
                <span>Drinks</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../user/viewAllUsers.php">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../orders/viewAllOrders.php">
                <i class="fas fa-shopping-cart"></i>
                <span>Manual Order</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../drinks/adminChecks.php">
                <i class="fas fa-file-invoice"></i>
                <span>Checks</span>
            </a>
        </li>


        <li class="nav-item">
            <a class="nav-link" href="../user/logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>logout</span>
            </a>
        </li>


        <li class='nav-item'>
            <a class="nav-link" href="../drinks/adminCreateDrink.php">
                add zeft
            </a>
        </li>

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
