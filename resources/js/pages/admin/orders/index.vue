<template>
    <div class="order-management">
        <div class="tabs">
            <button
                :class="{ active: selectedTab === 'status0' }"
                @click="selectTab('status0')"
            >
                Đơn hàng mới (Số lượng: {{ countOrdersStatus0() }} )
            </button>
            <button
                :class="{ active: selectedTab === 'status1' }"
                @click="selectTab('status1')"
            >
                Đang chuẩn bị (Số lượng: {{ countOrdersStatus1() }} )
            </button>
            <button
                :class="{ active: selectedTab === 'status2' }"
                @click="selectTab('status2')"
            >
                Đã vận chuyển (Số lượng: {{ countOrdersStatus2() }} )
            </button>
        </div>

        <div v-if="selectedTab === 'status0'">
            <h2>Đơn hàng mới</h2>
            <div v-for="order in ordersStatus0" :key="order.id" class="order">
                <h3>Mã đơn hàng: {{ order.id }}</h3>
                <p>Tên khách hàng: {{ order.customer_name }}</p>
                <p>Tổng tiền: {{ formatPrice(order.totalPrice) }}</p>
                <button @click="showOrderDetails(order)">Xem chi tiết</button>
            </div>
        </div>

        <div v-if="selectedTab === 'status1'">
            <h2>Đơn hàng đang chuẩn bị</h2>
            <div v-for="order in ordersStatus1" :key="order.id" class="order">
                <h3>Mã đơn hàng: {{ order.id }}</h3>
                <p>Tên khách hàng: {{ order.customer_name }}</p>
                <p>Tổng tiền: {{ formatPrice(order.totalPrice) }}</p>
                <button @click="showOrderDetails(order)">Xem chi tiết</button>
            </div>
        </div>

        <div v-if="selectedTab === 'status2'">
            <h2>Đơn hàng gởi đi</h2>
            <div v-for="order in ordersStatus2" :key="order.id" class="order">
                <h3>Mã đơn hàng: {{ order.id }}</h3>
                <p>Tên khách hàng: {{ order.customer_name }}</p>
                <p>Tổng tiền: {{ formatPrice(order.totalPrice) }}</p>
                <button @click="showOrderDetails(order)">Xem chi tiết</button>
            </div>
        </div>

        <div v-if="showOrderDetailModal" class="modal">
            <div class="modal-content">
                <h3>Chi tiết đơn hàng: {{ currentOrder.id }}</h3>
                <p>Tên khách hàng: {{ currentOrder.customer_name }}</p>
                <p>Tổng tiền: {{ formatPrice(currentOrder.totalPrice) }}</p>
                <ul>
                    <li v-for="item in currentOrder.order_items" :key="item.id">
                        Sản phẩm: {{ item.product_name }} - Số lượng:
                        {{ item.quantity }} - Giá:
                        {{ formatPrice(item.product_price) }}
                    </li>
                </ul>
                <button
                    v-if="currentOrder.status !== -1"
                    @click="confirmOrder(currentOrder)"
                >
                    Xác nhận đơn hàng
                </button>
                <button @click="closeOrderDetailModal">Đóng</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            selectedTab: "status0",
            ordersStatus0: [],
            ordersStatus1: [],
            ordersStatus2: [],
            showOrderDetailModal: false,
            currentOrder: null,
        };
    },
    methods: {
        selectTab(tab) {
            this.selectedTab = tab;
            this.fetchOrders();
        },
        countOrdersStatus0() {
            return this.ordersStatus0.length;
        },
        countOrdersStatus1() {
            return this.ordersStatus1.length;
        },
        countOrdersStatus2() {
            return this.ordersStatus2.length;
        },
        fetchOrders() {
            axios
                .get("/api/admin/orders")
                .then((response) => {
                    const orders = response.data;
                    this.ordersStatus0 = orders.filter(
                        (order) => order.status === 0
                    );
                    this.ordersStatus1 = orders.filter(
                        (order) => order.status === 1
                    );
                    this.ordersStatus2 = orders.filter(
                        (order) => order.status === 2
                    );
                })
                .catch((error) => {
                    console.error("Lỗi khi lấy danh sách đơn hàng:", error);
                });
        },
        showOrderDetails(order) {
            this.currentOrder = order;
            this.showOrderDetailModal = true;
        },
        closeOrderDetailModal() {
            this.currentOrder = null;
            this.showOrderDetailModal = false;
        },
        confirmOrder(order) {
            axios
                .patch(`/api/admin/orders/${order.id}/confirm`)
                .then(() => {
                    alert("Đơn hàng đã được xác nhận!");
                    this.fetchOrders();
                    this.closeOrderDetailModal();
                })
                .catch((error) => {
                    console.error("Lỗi khi xác nhận đơn hàng:", error);
                });
        },
        formatPrice(amount) {
            const numericAmount = parseFloat(amount);
            return numericAmount.toLocaleString("vi-VN", {
                style: "currency",
                currency: "VND",
            });
        },
    },
    mounted() {
        this.fetchOrders();
    },
};
</script>

<style scoped>
.order-management {
    padding: 20px;
    max-width: 800px; /* Giới hạn chiều rộng */
    margin-left: auto; /* Căn giữa theo chiều ngang */
    margin-right: auto; /* Căn giữa theo chiều ngang */
    margin-top: 20px; /* Đặt khoảng cách trên để giao diện nằm ở gần đầu màn hình */
}

.tabs {
    margin-bottom: 20px;
    display: flex;
    justify-content: space-around;
}

.tabs button {
    padding: 10px;
    background-color: #f5f5f5;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
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
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: white;
    padding: 20px;
    width: 50vw;
    border-radius: 5px;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
}

.modal button {
    margin-right: 10px;
}
</style>
