<template>
    <div class="checkout-page mt-4">
        <h1>Thanh toán</h1>
        <div v-if="cartItems.length > 0" class="mt-4">
            <div v-for="item in cartItems" :key="item.id" class="cart-item">
                <div class="product-item">
                    <img
                        :src="item.feature_image"
                        alt="Product Image"
                        class="product-image"
                    />
                    <h3 class="product-name">{{ item.name }}</h3>
                    <p class="product-price">
                        Giá: {{ formatPrice(item.price) }}
                    </p>
                    <button @click="decrementQuantity(item.id)">-</button>
                    <input
                        type="text"
                        v-model="item.quantity"
                        readonly
                        class="quantity-input"
                    />
                    <button @click="incrementQuantity(item.id)">+</button>
                    <p class="total">
                        Tổng: {{ formatPrice(item.price * item.quantity) }}
                    </p>
                    <a 
                        class="remove-item"
                        @click="removeFromCart(item.id)"
                    >
                        Remove
                    </a>
                </div>
            </div>

            <div class="cart-summary">
                <p>Tổng cộng: {{ formatPrice(totalPrice) }}</p>
                <div class="coupon-section">
                    <input
                        v-model="couponCode"
                        type="text"
                        placeholder="Enter coupon code"
                        class="coupon-input"
                    />
                    <button @click="applyCoupon" class="apply-coupon-button">
                        Apply
                    </button>
                    <p v-if="discount">
                        Discount: -{{ formatPrice(discount) }}
                    </p>
                </div>

                <p>Giá cuối: {{ formatPrice(totalAfterDiscount) }}</p>
            </div>
        </div>

        <div v-else>
            <p>Your cart is empty.</p>
        </div>

        <button @click="completeCheckout" class="checkout-button">
            Complete Checkout
        </button>
    </div>
</template>

<script>
import { ref, computed } from "vue";
import { useStore } from "vuex";
import { mapGetters, mapActions, mapMutations } from "vuex";

export default {
    computed: {
        ...mapGetters(["cartItems", "totalPrice"]),

        totalAfterDiscount() {
            return this.totalPrice - this.discount;
        },
    },

    data() {
        return {
            couponCode: "",
            discount: 0,
        };
    },
    methods: {
        ...mapActions(["removeFromCart", "clearCart"]),
        ...mapMutations(["updateCartItemQuantity"]),

        incrementQuantity(itemId) {
            const item = this.cartItems.find((item) => item.id === itemId);
            if (item) {
                this.updateCartItemQuantity({
                    productId: itemId,
                    quantity: item.quantity + 1,
                });
            }
        },

        decrementQuantity(itemId) {
            const item = this.cartItems.find((item) => item.id === itemId);
            if (item && item.quantity > 1) {
                this.updateCartItemQuantity({
                    productId: itemId,
                    quantity: item.quantity - 1,
                });
            }
        },

        formatPrice(amount) {
            const numericAmount = parseFloat(amount);
            if (numericAmount !== undefined) {
                return numericAmount.toLocaleString("vi-VN", {
                    style: "currency",
                    currency: "VND",
                });
            }
            return "";
        },

        async applyCoupon() {
            try {
                const response = await axios.get(`/api/coupons/${this.couponCode}`);
                const coupon = response.data;
                const currentDate = new Date();

                if (currentDate <= new Date(coupon.expiry_date)) {
                    this.discount = (this.totalPrice * coupon.discount) / 100;
                    alert(`Áp dụng giảm giá ${coupon.discount}% thành công!`);
                } else {
                    alert("Mã coupon đã hết hạn.");
                    this.discount = 0;
                }
            } catch (error) {
                console.error("Lỗi khi áp dụng coupon:", error);
                alert("Mã coupon không hợp lệ.");
                this.discount = 0;
            }
        },

        completeCheckout() {
            window.location.href = "/OrderConfirmation";
        },
    },
};
</script>

<style scoped>
.product-item {
    display: flex;
    flex-wrap: wrap; 
    justify-content: space-between;
    margin-bottom: 10px;
}

.product-image {
    width: 100px;
    height: auto;
    margin-right: 10px;
}

.product-name,
.product-price,
.total {
    flex: 1;
    margin-right: 10px;
}

button {
    margin: 0 5px;
}

.checkout-page {
    padding: 20px;
}

.product-price,
.product-total-price {
    margin: 5px 0;
}

.quantity-controls {
    display: flex;
    align-items: center;
    margin: 5px 0;
}

.quantity-input {
    width: 50px;
    text-align: center;
    margin: 0 5px;
}

.cart-summary {
    margin-top: 20px;
}

.coupon-section {
    margin-bottom: 10px;
}
.remove-item {
    background-color: #54e57f; 
    color: white; 
    border: none; 
    padding: 5px 10px; 
    cursor: pointer; 
    border-radius: 4px; 
    align-items: center;
}

.remove-item:hover {
    background-color: #29bd56; 
}

button {
    padding: 10px 20px;
    background-color: #54e57f;
    border: none;
    color: white;
    cursor: pointer;
}

button:hover {
    background-color: #29bd56;
}
</style>
