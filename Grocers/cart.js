class Cart {
    constructor() {
        this.items = JSON.parse(localStorage.getItem('cart')) || [];
        this.total = 0;
        this.updateTotal();
    }

    addItem(product) {
        const existingItem = this.items.find(item => item.id === product.id);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            this.items.push({
                id: product.id,
                name: product.name,
                price: product.price,
                quantity: 1
            });
        }
        this.save();
        this.updateTotal();
        this.updateUI();
    }

    removeItem(productId) {
        this.items = this.items.filter(item => item.id.toString() !== productId.toString());
        this.save();
        this.updateTotal();
        this.updateUI();
    }

    updateQuantity(productId, quantity) {
        const item = this.items.find(item => item.id.toString() === productId.toString());
        if (item) {
            const newQuantity = parseInt(quantity);
            if (isNaN(newQuantity) || newQuantity < 0) {
                return; // Invalid quantity
            }
            if (newQuantity === 0) {
                this.removeItem(productId);
            } else {
                item.quantity = newQuantity;
                this.save();
                this.updateTotal();
                this.updateUI();
            }
        }
    }    

    updateTotal() {
        this.total = this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    }

    save() {
        localStorage.setItem('cart', JSON.stringify(this.items));
    }

    clear() {
        this.items = [];
        this.total = 0;
        this.save();
        this.updateUI();
    }

    updateUI() {
        // Update mini cart count
        const cartCount = document.getElementById('cart-count');
        if (cartCount) {
            const totalItems = this.items.reduce((sum, item) => sum + item.quantity, 0);
            cartCount.textContent = totalItems;
        }

        // Update cart items list if on cart page
        const cartItemsList = document.getElementById('cart-items');
        if (cartItemsList) {
            if (this.items.length === 0) {
                cartItemsList.innerHTML = '<div class="cart-empty">Your cart is empty</div>';
                document.getElementById('checkout-form').style.display = 'none';
            } else {
                cartItemsList.innerHTML = this.items.map(item => `
                    <div class="cart-item">
                        <div class="cart-item-details">
                            <h3>${item.name}</h3>
                            <p>RM${item.price.toFixed(2)} each</p>
                        </div>
                        <div class="cart-item-controls">
                            <input type="number" value="${item.quantity}" min="1" 
                                onchange="cart.updateQuantity(${item.id}, this.value)"
                                oninput="this.value = this.value.replace(/^0+/, '')"
                                class="quantity-input">
                            <button onclick="cart.removeItem(${item.id})" class="remove-btn">Remove</button>
                        </div>
                        <div class="cart-item-total">
                            RM${(item.price * item.quantity).toFixed(2)}
                        </div>
                    </div>
                `).join('');

                // Update total and show checkout form
                const totalElement = document.getElementById('cart-total');
                if (totalElement) {
                    totalElement.textContent = `RM${this.total.toFixed(2)}`;
                }
                document.getElementById('checkout-form').style.display = 'block';
                document.getElementById('cart-data').value = JSON.stringify(this.items);
            }
        }
    }
}

// Initialize cart
const cart = new Cart(); 