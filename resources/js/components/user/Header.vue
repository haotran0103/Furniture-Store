<template>
    <header class="header_section">
        <div class="header_top" style="background-color: #54e57f">
            <div class="container-fluid">
                <div class="top_nav_container">
                    <div class="contact_nav">
                        <a href="">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <span> Call : +01 123455678990 </span>
                        </a>
                        <a href="">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            <span> Email : demo@gmail.com </span>
                        </a>
                    </div>
                    <from class="search_form">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Search here..."
                        />
                        <button class="" type="submit">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </from>
                    <div class="user_option_box">
                        <div v-if="isLoggedIn">
                            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                                <li class="nav-item dropdown">
                                    <a
                                        class="nav-link dropdown-toggle"
                                        id="navbarDropdown"
                                        href="#"
                                        role="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        ><i class="fas fa-user fa-fw"></i
                                    ></a>
                                    <ul
                                        class="dropdown-menu dropdown-menu-end"
                                        aria-labelledby="navbarDropdown"
                                        @mouseenter="toggleDropdown(true)"
                                        @mouseleave="toggleDropdown(false)"
                                    >
                                        <li>
                                            <router-link
                                                class="dropdown-item"
                                                to="/my-account"
                                            >
                                                Trang cá nhân
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link
                                                class="dropdown-item"
                                                to="/orders"
                                            >
                                                Đơn hàng
                                            </router-link>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li>
                                            <a
                                                class="dropdown-item"
                                                @click="logout"
                                                >Đăng xuất</a
                                            >
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div v-else>
                            <router-link
                                to="/auth/user/login"
                                class="account-link"
                            >
                                <i class="fa fa-user" aria-hidden="true"></i>
                                <span>tài khoản</span>
                            </router-link>
                        </div>
                        <div
                            class="cart-container"
                            @mouseenter="showCartDropdown"
                            @mouseleave="hideCartDropdown"
                        >
                            <a href="#" class="cart-link">
                                <i
                                    class="fa fa-shopping-cart"
                                    aria-hidden="true"
                                ></i>
                                <span> Giỏ hàng </span>
                            </a>
                            <div
                                class="cart-dropdown"
                                ref="cartDropdown"
                                @mouseenter="clearHideTimeout"
                                @mouseleave="hideCartDropdown"
                            >
                                <div v-if="cartItems.length > 0">
                                    <div
                                        class="cart-item"
                                        v-for="item in cartItems"
                                        :key="item.id"
                                    >
                                        <img
                                            :src="item.feature_image"
                                            alt="Product Image"
                                            class="product-image"
                                        />
                                        <p class="product-name">
                                            {{ item.name }}
                                        </p>
                                        <p class="price">
                                            Price: {{ formatPrice(item.price) }}
                                        </p>
                                        <div class="quantity-controls">
                                            <button
                                                @click="
                                                    decrementQuantity(item.id)
                                                "
                                            >
                                                -
                                            </button>
                                            <input
                                                type="text"
                                                v-model="item.quantity"
                                            />
                                            <button
                                                @click="
                                                    incrementQuantity(item.id)
                                                "
                                            >
                                                +
                                            </button>
                                        </div>
                                        <p class="total">
                                            Total Price:
                                            {{
                                                formatPrice(
                                                    totalQuantityPrice(
                                                        item.quantity,
                                                        item.price
                                                    )
                                                )
                                            }}
                                        </p>
                                        <button
                                            class="remove-item"
                                            @click="removeFromCart(item.id)"
                                        >
                                            Remove
                                        </button>
                                    </div>
                                </div>
                                <p v-else>không có sản phẩm nào trong giỏ hàng</p>
                                <p class="total-price">
                                    tổng giá: {{ formatPrice(totalPrice) }}
                                </p>
                                <router-link
                                    to="/checkout"
                                    class="checkout-button"
                                    >thanh toán</router-link
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header_bottom" style="background-color: #29bd56">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg custom_nav-container">
                    <a class="navbar-brand" href="/">
                        <span> Minics  Store</span>
                    </a>
                    <button
                        class="navbar-toggler"
                        type="button"
                        data-toggle="collapse"
                        data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                    >
                        <span class=""> </span>
                    </button>
                    <div
                        class="collapse navbar-collapse"
                        id="navbarSupportedContent"
                    >
                        <ul class="navbar-nav">
                            <li
                                class="nav-item"
                                :class="{ active: isCurrentPage('/') }"
                            >
                                <a class="nav-link" href="/"
                                    >Trang chủ<span class="sr-only"
                                        >(current)</span
                                    ></a
                                >
                            </li>
                            <li
                                class="nav-item"
                                :class="{
                                    active: isCurrentPage('/products-all'),
                                }"
                            >
                                <a class="nav-link" href="/products-all"
                                    >Sản phẩm</a
                                >
                            </li>
                            <li
                                class="nav-item"
                                v-for="menu in menus"
                                :key="menu.id"
                                :class="{
                                    active: isCurrentPage(
                                        menu.type === 'product'
                                            ? '/products/' + menu.category
                                            : '/' + menu.type
                                    ),
                                }"
                            >
                                <a
                                    class="nav-link"
                                    :href="'/products/' + menu.category"
                                >
                                    {{ menu.name }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>
</template>

<script>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import { mapState, mapGetters, mapActions, mapMutations } from "vuex";
export default {
    computed: {
        ...mapState("cart", {
            cartItems: "cartItems",
            totalPrice: "totalPrice",
        }),
    },
    methods: {
        ...mapActions("cart", ["removeFromCart", "clearCart"]),
        ...mapMutations("cart", ["updateCartItemQuantity"]),
    },
    setup() {
        const menus = ref([]);
        let hideTimeout = null;
        const quantity = ref(1);
        const total = ref(0);
        const quantityValue = ref(quantity.value);
        const isLoggedIn = ref(false);
        const dropdownVisible = ref(false);
        if (localStorage.getItem("customer_token")) {
            isLoggedIn.value = true;
        }
        const logout = () => {
            localStorage.removeItem("customer_token");
            isLoggedIn.value = false;
            window.location.href = "/";
        };

        onMounted(async () => {
            try {
                const response = await axios.get("/api/menu-all");
                menus.value = response.data;
            } catch (error) {
                console.error("Error fetching menus:", error);
            }
        });
        const currentPath = computed(() => window.location.pathname);

        const isCurrentPage = (path) => {
            return currentPath.value === path;
        };
        const showCartDropdown = () => {
            clearTimeout(hideTimeout);
            document.querySelector(".cart-dropdown").style.display = "block";
        };

        const hideCartDropdown = () => {
            hideTimeout = setTimeout(() => {
                document.querySelector(".cart-dropdown").style.display = "none";
            }, 700);
        };

        const clearHideTimeout = () => {
            clearTimeout(hideTimeout);
        };
        const incrementQuantity = (itemId) => {
            const item = store.state.cartItems.find(
                (item) => item.id === itemId
            );
            if (item) {
                store.commit("updateCartItemQuantity", {
                    productId: itemId,
                    quantity: item.quantity + 1,
                });
            }
        };

        const decrementQuantity = (itemId) => {
            const item = store.state.cartItems.find(
                (item) => item.id === itemId
            );
            if (item && item.quantity > 1) {
                store.commit("updateCartItemQuantity", {
                    productId: itemId,
                    quantity: item.quantity - 1,
                });
            }
        };

        const formatPrice = (amount) => {
            if (amount !== undefined) {
                return amount.toLocaleString("vi-VN", {
                    style: "currency",
                    currency: "VND",
                });
            } else {
                return "";
            }
        };

        const totalQuantityPrice = (quantity, price) => {
            return quantity * price;
        };
        const toggleDropdown = (visible) => {
            dropdownVisible.value = visible;
            const dropdownMenu = document.querySelector(
                ".dropdown-menu.dropdown-menu-end"
            );
            if (dropdownMenu) {
                dropdownMenu.style.display = visible ? "block" : "none";
            }
        };

        return {
            menus,
            isCurrentPage,
            showCartDropdown,
            hideCartDropdown,
            clearHideTimeout,
            quantity,
            total,
            incrementQuantity,
            decrementQuantity,
            quantityValue,
            formatPrice,
            totalQuantityPrice,
            isLoggedIn,
            logout,
            toggleDropdown,
        };
    },
};
</script>

<style scoped>
a {
    text-decoration: none;
}

.cart-container {
    position: relative;
    width: auto;
}

.cart-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    transform: translateX(-80%);
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 10px;
    z-index: 1000;
}

.cart-link:hover + .cart-dropdown {
    display: block;
}
.cart-item {
    display: table-row;
}

.product-image {
    display: table-cell;
    width: 50px;
    height: 50px;
    vertical-align: middle;
}

.product-name,
.price,
.quantity,
.total,
.remove-item {
    display: table-cell;
    padding: 5px 10px;
}

@media screen and (min-width: 775px) {
    .cart-dropdown {
        width: 45vw;
    }
}

@media screen and (max-width: 1128px) {
    .cart-dropdown {
        width: 75vw;
    }
}
.quantity-controls {
    display: flex;
    align-items: center;
}
.dropdown-menu .dropdown-item {
    color: black;
}

.nav-item.dropdown:hover .dropdown-menu {
    position: absolute;
    display: block;
}

.nav-item.dropdown:hover .nav-link.dropdown-toggle {
    color: black;
}

.dropdown-menu {
    display: none;
}
.quantity-controls button {
    padding: 5px 10px;
    border: none;
    background-color: #f5f5f5;
    cursor: pointer;
}

.quantity-controls input {
    padding: 5px 10px;
    border: 1px solid #ccc;
    margin: 0 5px;
    width: 50px;
}

.cart-item {
    display: flex;
    align-items: center;
}

.product-image {
    width: 50px;
    height: 50px;
    margin-right: 10px;
}

.remove-item {
    cursor: pointer;
}
/* Định dạng cho nút Checkout */
.checkout-button {
    display: block;
    padding: 10px 15px;
    background-color: #54e57f; /* Màu nền của nút */
    color: white; /* Màu chữ trắng */
    border: none; /* Bỏ đường viền */
    border-radius: 5px; /* Bo góc nút */
    text-align: center; /* Canh giữa văn bản */
    text-decoration: none; /* Bỏ gạch chân văn bản */
    font-weight: bold; /* Chữ đậm */
    cursor: pointer; /* Thay đổi con trỏ chuột khi di chuột qua */
    margin-top: 10px; /* Khoảng cách trên nút */
    width: 100%; /* Độ rộng của nút là 100% của phần tử chứa nó */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Đổ bóng nhẹ cho nút */
}

.checkout-button:hover {
    background-color: #43d368; /* Màu nền khi di chuột qua */
}
</style>
