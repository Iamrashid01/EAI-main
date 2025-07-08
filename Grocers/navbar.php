<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
.navbar {
    background-color: #ffffff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 1rem 2rem;
    margin-bottom: 2rem;
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-brand {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.nav-brand:hover {
    color: #007bff;
}

.nav-links {
    display: flex;
    gap: 2rem;
    align-items: center;
}

.nav-link {
    color: #666;
    text-decoration: none;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    transition: all 0.2s;
}

.nav-link:hover {
    color: #007bff;
    background-color: #f8f9fa;
}

.nav-link.active {
    color: #007bff;
    background-color: #e9ecef;
}

.nav-cart {
    position: relative;
    text-decoration: none;
    font-size: 1.25rem;
    color: #333;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.nav-cart-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

@media (max-width: 768px) {
    .nav-container {
        flex-direction: column;
        gap: 1rem;
    }
    
    .nav-links {
        flex-direction: column;
        gap: 0.5rem;
        width: 100%;
    }
    
    .nav-link {
        width: 100%;
        text-align: center;
    }
}
</style>

<nav class="navbar">
    <div class="nav-container">
        <a href="products.php" class="nav-brand">
            🛒 Grocers
        </a>
        <div class="nav-links">
            <a href="products.php" class="nav-link <?php echo $current_page === 'products.php' ? 'active' : ''; ?>">
                Products
            </a>
            <a href="order_status.php" class="nav-link <?php echo $current_page === 'order_status.php' ? 'active' : ''; ?>">
                Track Order
            </a>
            <a href="cart.php" class="nav-cart">
                Cart
                <span class="nav-cart-count" id="cart-count">0</span>
            </a>
        </div>
    </div>
</nav> 