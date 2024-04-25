<template>
    <div class="coupon-management-page">
        <h1>Quản lý Coupon</h1>

        <!-- Form để thêm/sửa coupon -->
        <form @submit.prevent="handleSubmit">
            <input type="text" v-model="coupon.name" placeholder="Mã coupon" required />
            <input type="number" v-model="coupon.value" placeholder="Giảm giá (%)" min="1" max="100" required />
            <input type="date" v-model="coupon.expiry_date" placeholder="Ngày hết hạn" required />
            <button type="submit">{{ isEditing ? 'Cập nhật' : 'Thêm' }} Coupon</button>
        </form>

        <!-- Danh sách coupon -->
        <ul>
            <li v-for="(c, index) in coupons" :key="c.id">
                <span>Mã: {{ c.name }}</span> 
                <span>Giảm giá: {{ c.value }}%</span>
                <span>Hết hạn: {{ c.expiry_date }}</span>
                <button @click="editCoupon(c)">Sửa</button>
                <button @click="deleteCoupon(c.id)">Xóa</button>
            </li>
        </ul>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            coupons: [], // Danh sách coupon
            coupon: { // Dữ liệu coupon hiện tại
                name: "",
                value: "",
                expiry_date: ""
            },
            isEditing: false, // Cờ để kiểm tra xem đang ở trạng thái thêm mới hay chỉnh sửa
            editingIndex: -1 // Chỉ số của coupon đang chỉnh sửa
        };
    },
    methods: {
        fetchCoupons() {
            axios.get("/api/coupons")
                .then(response => {
                    this.coupons = response.data;
                })
                .catch(error => {
                    console.error("Lỗi khi lấy danh sách coupon:", error);
                });
        },
        handleSubmit() {
            if (this.isEditing) {
                const couponId = this.coupons[this.editingIndex].id;
                axios.put(`/api/admin/coupons/${couponId}`, this.coupon)
                    .then(response => {
                        this.coupons[this.editingIndex] = response.data;
                        this.resetForm();
                    })
                    .catch(error => {
                        console.error("Lỗi khi cập nhật coupon:", error);
                    });
            } else {
                axios.post("/api/coupons", this.coupon)
                    .then(response => {
                        this.coupons.push(response.data);
                        this.resetForm();
                    })
                    .catch(error => {
                        console.error("Lỗi khi tạo coupon:", error);
                    });
            }
        },
        editCoupon(coupon) {
            this.isEditing = true;
            this.coupon = { ...coupon };
            this.editingIndex = this.coupons.findIndex(c => c.id === coupon.id);
        },
        deleteCoupon(couponId) {
            axios.delete(`/api/admin/coupons/${couponId}`)
                .then(() => {
                    this.coupons = this.coupons.filter(c => c.id !== couponId);
                })
                .catch(error => {
                    console.error("Lỗi khi xóa coupon:", error);
                });
        },
        // Reset form
        resetForm() {
            this.coupon = { code: "", discount: "", expiry_date: "" };
            this.isEditing = false;
            this.editingIndex = -1;
        }
    },
    mounted() {
        // Lấy danh sách coupon khi component được gắn vào DOM
        this.fetchCoupons();
    }
};
</script>

<style scoped>
.coupon-management-page {
    padding: 20px;
}

form {
    margin-bottom: 20px;
}

input {
    margin-right: 10px;
}

button {
    margin-right: 10px;
}

li {
    margin-bottom: 10px; /* Tạo khoảng cách giữa các mục */
    padding: 10px; /* Thêm khoảng cách bên trong các mục */
    border-bottom: 1px solid #ccc; /* Tạo đường viền dưới mỗi mục */
    display: flex; /* Hiển thị các phần tử theo hàng */
    justify-content: space-between; /* Căn chỉnh các phần tử con */
}

li span {
    margin-right: 10px; /* Tạo khoảng cách giữa các nhãn */
}

li button {
    margin-left: 10px; /* Tạo khoảng cách giữa các nút */
}

</style>
