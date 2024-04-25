<template>
    <div class="checkout-confirmation">
        <h1>Xác nhận thanh toán</h1>
        <div class="cart-items">
            <div v-for="item in cartItems" :key="item.id" class="cart-item">
                <div class="product-item">
                    <img :src="item.feature_image" alt="Product Image" class="product-image" />
                    <h5 class="product-name">{{ item.name }}</h5>
                    <p class="product-price">Giá: {{ formatPrice(item.price) }}</p>
                    <p class="quantity">Số lượng: {{ item.quantity }}</p>
                    <p class="total-price">Tổng: {{ formatPrice(item.price * item.quantity) }}</p>
                </div>
            </div>
            <p class="total-cart-price">
                Tổng cộng: {{ formatPrice(totalPrice) }}
            </p>
        </div>

        <div class="user-info">
            <h2>Thông tin người dùng</h2>
            <p>Số điện thoại: {{ userPhone }}</p>
            <p>Email: {{ userEmail }}</p>
            <p>Thành phố: {{ userCity }}</p>
            <p>Địa chỉ: {{ userAddress }}</p>
        </div>

        
        <button @click="confirmPayment" class="confirm-button">
            Xác nhận thanh toán
        </button>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    data() {
        return {
            customerId: '',
            paymentMethod: 'direct', 
            userPhone: '',
            userEmail: '',
            userCity: '',
            userAddress: '',
            token: '' 
        };
    },
    computed: {
        ...mapGetters(['cartItems', 'totalPrice'])
    },
    methods: {
        async fetchUserData() {
            try {
                const response = await axios.get('/api/customer/profile', {
                    headers: {
                        'Authorization': `Bearer ${this.token}`
                    }
                });
                this.customerId = response.data.id;
                this.userPhone = response.data.phone;
                this.userEmail = response.data.email;
                this.userCity = response.data.city;
                this.userAddress = response.data.address;
            } catch (error) {
                console.error('Lỗi khi lấy thông tin người dùng:', error);
            }
        },
        async confirmPayment() {
            const storedCartItems = localStorage.getItem('cartItems');
            const stored = JSON.parse(storedCartItems);
            try {
                const orderData = {
                    customer_id: this.customerId,
                    total: this.totalPrice,
                    status: this.paymentMethod === 'direct' ? 0 : 1, // Trạng thái của đơn hàng
                    order_items: stored.map(item => ({
                        product_id: item.id,
                        quantity: item.quantity
                    }))
                };

                const response = await axios.post('/api/orders', orderData, {
                    headers: {
                        'Authorization': `Bearer ${this.token}`
                    }
                });

                if (response.status === 201) {
                    alert('Đơn hàng đã được lưu thành công!');
                    localStorage.setItem('cartItems', JSON.stringify([]));
                    window.location.href = "/orders";

                } else {
                    alert('Đã xảy ra lỗi khi lưu đơn hàng.');
                }
            } catch (error) {
                console.error('Lỗi khi gọi API:', error);
                alert('Đã xảy ra lỗi khi lưu đơn hàng.');
            }
        },
        formatPrice(amount) {
            const numericAmount = parseFloat(amount);
            return numericAmount.toLocaleString('vi-VN', {
                style: 'currency',
                currency: 'VND'
            });
        },
    },
    created() {
        this.token = this.$store.getters.userToken || localStorage.getItem('customer_token');
        this.fetchUserData();
    }
};

</script>

<style scoped>
.checkout-confirmation {
    padding: 20px;
}

.cart-items, .user-info, .payment-method {
    margin-bottom: 20px;
}

.product-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.product-image {
    width: 100px;
    height: auto;
    margin-right: 10px;
}

.product-name, .product-price, .quantity, .total-price {
    margin-right: 20px;
}

.payment-options label {
    margin-right: 20px;
}

.confirm-button {
    background-color: #54e57f;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.confirm-button:hover {
    background-color: #29bd56;
}
</style>
