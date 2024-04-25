<style scoped>
.register-container {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    background-color: #f9f9f9;
}

.register-form h2 {
    text-align: center;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-control {
    width: 100%;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}

.btn-secondary {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-link {
    display: inline-block;
    color: #007bff;
    text-decoration: none;
    padding: 0;
    background-color: transparent;
}

.error-text {
    color: red;
    font-size: 14px;
}
</style>

<template>
    <div class="register-container">
        <form @submit.prevent="register" class="register-form">
            <h2>Đăng ký</h2>

            <div class="form-group">
                <label for="fullname">Tên đầy đủ</label>
                <input
                    type="text"
                    id="fullname"
                    v-model.trim="registerForm.fullname"
                    class="form-control"
                />
                <span v-if="errors.fullname" class="error-text">{{
                    errors.fullname
                }}</span>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    v-model.trim="registerForm.email"
                    class="form-control"
                />
                <span v-if="errors.email" class="error-text">{{
                    errors.email
                }}</span>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input
                    type="password"
                    id="password"
                    v-model.trim="registerForm.password"
                    class="form-control"
                />
                <span v-if="errors.password" class="error-text">{{
                    errors.password
                }}</span>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input
                    type="text"
                    id="phone"
                    v-model.trim="registerForm.phone"
                    class="form-control"
                />
                <span v-if="errors.phone" class="error-text">{{
                    errors.phone
                }}</span>
            </div>

            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input
                    type="text"
                    id="address"
                    v-model.trim="registerForm.address"
                    class="form-control"
                />
                <span v-if="errors.address" class="error-text">{{
                    errors.address
                }}</span>
            </div>
            <div class="form-group">
                <label for="role">Vai trò</label>
                <select
                    id="role"
                    v-model.trim="registerForm.role"
                    class="form-control"
                >
                    <option value="" disabled selected>Chọn vai trò</option>
                    <option value="user">Người dùng</option>
                    <option value="sales">Nhân viên bán hàng</option>
                </select>
                <span v-if="errors.role" class="error-text">{{
                    errors.role
                }}</span>
            </div>
            <button
                style="margin-right: 5px"
                type="submit"
                class="btn btn-primary"
            >
                Đăng ký
            </button>
            <a href="/" class="btn btn-secondary">Trở về trang chủ</a>
            <a href="/auth/login" class="btn btn-link">Đã có tài khoản? Đăng nhập</a>
        </form>
    </div>
</template>
<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from 'vue-router';
const registerForm = ref({
    fullname: "",
    email: "",
    password: "",
    phone: "",
    address: "",
    role: "",
});

const errors = ref({});

const register = () => {
    errors.value = {};
    if (!registerForm.value.fullname) {
        errors.value.fullname = "Tên đầy đủ không được để trống";
    }
    if (!registerForm.value.role) {
        errors.value.role = "Vai trò không được để trống";
    }

    if (Object.keys(errors.value).length === 0) {
        axios
            .post("/api/register", registerForm.value)
            .then((response) => {
                alert("Đăng ký thành công. Vui lòng đăng nhập.");
                window.location.href = '/auth/login';
            })
            .catch((error) => {
                // Handle error
                console.error(
                    "Có lỗi xảy ra trong quá trình đăng ký:",
                    error.response.data
                );
                if (error.response && error.response.data.errors) {
                    errors.value = error.response.data.errors;
                }
            });
    }
};
</script>
