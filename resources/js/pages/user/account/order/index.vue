<template>
    <div class="order-page">
        <div class="tabs">
            <button
                @click="selectTab('pending')"
                :class="{ active: selectedTab === 'pending' }"
            >
                Đơn hàng của bạn
            </button>
            <button
                @click="selectTab('completed')"
                :class="{ active: selectedTab === 'completed' }"
            >
                Đơn đã mua
            </button>
        </div>

        <div v-if="selectedTab === 'pending'">
            <div v-for="order in pendingOrders" :key="order.id" class="order">
                <h3>Đơn hàng số: {{ order.id }}</h3>
                <p>Tổng tiền: {{ formatPrice(order.totalPrice) }}</p>
                <ul>
                    <li v-for="item in order.order_items" :key="item.id">
                        Sản phẩm: {{ item.product_name }} - Số lượng:
                        {{ item.quanlity }}
                    </li>
                </ul>
                <button @click="cancelOrder(order.id)">Hủy đơn hàng</button>
            </div>
        </div>

        <div v-else>
            <div v-for="order in completedOrders" :key="order.id" class="order">
                <h3>Đơn hàng số: {{ order.id }}</h3>
                <p>Tổng tiền: {{ formatPrice(order.total) }}</p>
                <ul>
                    <li v-for="item in order.items" :key="item.id">
                        Sản phẩm: {{ item.product_name }} - Số lượng:
                        {{ item.quanlity }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            selectedTab: "pending",
            pendingOrders: [],
            completedOrders: [],
            token: "",
            userPhone: "",
            userEmail: "",
            userCity: "",
            userAddress: "",
            customerId: "",
        };
    },
    methods: {
        async cancelOrder(orderId) {
        try {
            const response = await axios.patch(`/api/orders/${orderId}/cancel`, null, {
                headers: {
                    Authorization: `Bearer ${this.token}`,
                },
            });

            if (response.status === 200) {
                alert("Đơn hàng đã được hủy thành công!");
                this.fetchOrders(); 
            } else {
                alert("Đã xảy ra lỗi khi hủy đơn hàng.");
            }
        } catch (error) {
            console.error("Lỗi khi hủy đơn hàng:", error);
            alert("Đã xảy ra lỗi khi hủy đơn hàng.");
        }
    },
        async fetchUserData() {
            try {
                const response = await axios.get("/api/customer/profile", {
                    headers: {
                        Authorization: `Bearer ${this.token}`,
                    },
                });
                this.customerId = response.data.id;
                this.userPhone = response.data.phone;
                this.userEmail = response.data.email;
                this.userCity = response.data.city;
                this.userAddress = response.data.address;
                return Promise.resolve();
            } catch (error) {
                return Promise.reject(error);
                console.error("Lỗi khi lấy thông tin người dùng:", error);
            }
        },
        selectTab(tab) {
            this.selectedTab = tab;
        },
        formatPrice(amount) {
            const numericAmount = parseFloat(amount);
            return numericAmount.toLocaleString("vi-VN", {
                style: "currency",
                currency: "VND",
            });
        },
        fetchOrders() {
            axios
                .get("/api/orders", {
                    headers: {
                        Authorization: `Bearer ${this.token}`,
                    },
                    params: {
                        user_id: this.customerId,
                    },
                })
                .then((response) => {
                    const orders = response.data;
                    this.pendingOrders = orders.filter(
                        (order) => order.status !== -1
                    ); // Đơn hàng đang chờ xử lý
                    this.completedOrders = orders.filter(
                        (order) => order.status === -1
                    ); // Đơn hàng đã hoàn thành
                })
                .catch((error) => {
                    console.error("Error fetching orders:", error);
                });
        },
    },
    mounted() {
        this.token = localStorage.getItem("customer_token");

        this.fetchUserData()
        .then(() => {
            this.fetchOrders();
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });
    },
};
</script>

<style scoped>
.order-page {
    padding: 20px;
}

.tabs {
    margin-bottom: 20px;
}

.tabs button {
    margin-right: 10px;
    padding: 10px;
    border: none;
    background-color: #f5f5f5;
    cursor: pointer;
}

.tabs button.active {
    background-color: #54e57f; /* Màu xanh lá cây cho tab được chọn */
    color: white;
}

.order {
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
</style>
