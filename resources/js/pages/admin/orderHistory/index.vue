<template>
    <div class="order-history-page">
        <h1>Lịch sử đơn hàng</h1>
        <div v-if="cancelledOrders.length > 0">
            <div v-for="order in cancelledOrders" :key="order.id" class="order">
                <h3>Người đặt: {{ order.customer_name }}</h3>
                <h5>Đơn hàng số: {{ order.id }}</h5>
                <p>Tổng tiền: {{ formatPrice(order.totalPrice) }}</p>
                <ul>
                    <li v-for="item in order.order_items" :key="item.id">
                        Sản phẩm: {{ item.product_name }} - Số lượng:
                        {{ item.quantity }}
                    </li>
                </ul>
            </div>
        </div>
        <div v-else>
            <p>Không có đơn hàng nào đã bị hủy.</p>
        </div>
        <div class="pagination">
            <button @click="prevPage" :disabled="currentPage === 1">Trang trước</button>
            <span>{{ currentPage }}</span>
            <button @click="nextPage" :disabled="currentPage === totalPages">Trang tiếp theo</button>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            cancelledOrders: [],
            token: "",
            currentPage: 1, // Trang hiện tại
            ordersPerPage: 10, // Số đơn hàng trên mỗi trang
        };
    },
    computed: {
        // Tính toán danh sách đơn hàng trên trang hiện tại
        paginatedOrders() {
            const startIndex = (this.currentPage - 1) * this.ordersPerPage;
            const endIndex = startIndex + this.ordersPerPage;
            return this.cancelledOrders.slice(startIndex, endIndex);
        },
        // Tính toán tổng số trang
        totalPages() {
            return Math.ceil(this.cancelledOrders.length / this.ordersPerPage);
        },
    },
    methods: {
        async fetchCancelledOrders() {
            try {
                const response = await axios.get("/api/admin/orders/success");
                this.cancelledOrders = response.data;
            } catch (error) {
                console.error("Lỗi khi lấy danh sách đơn hàng đã bị hủy:", error);
            }
        },
        formatPrice(amount) {
            const numericAmount = parseFloat(amount);
            return numericAmount.toLocaleString("vi-VN", {
                style: "currency",
                currency: "VND",
            });
        },
        // Phương thức chuyển đến trang trước
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        // Phương thức chuyển đến trang tiếp theo
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
    },
    mounted() {
        this.fetchCancelledOrders();
    },
};
</script>

<style scoped>
.order-history-page {
    padding: 20px;
}

.order {
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.pagination {
    margin-top: 20px;
}

.pagination button {
    margin-right: 10px;
}
</style>