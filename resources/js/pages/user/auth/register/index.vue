<template>
    <div class="register-container">
        <h2>Đăng ký</h2>
        <form @submit.prevent="register" class="register-form">
            <div class="form-group">
                <label for="name">Tên:</label>
                <input
                    type="text"
                    v-model="formData.name"
                    id="name"
                    required
                />
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input
                    type="email"
                    v-model="formData.email"
                    id="email"
                    required
                />
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input
                    type="text"
                    v-model="formData.phone"
                    id="phone"
                    required
                />
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input
                    type="password"
                    v-model="formData.password"
                    id="password"
                    required
                />
            </div>
            <div class="form-group">
                <label for="city">Thành phố:</label>
                <input
                    type="text"
                    v-model="formData.city"
                    id="city"
                    required
                />
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <input
                    type="text"
                    v-model="formData.address"
                    id="address"
                    required
                />
            </div>
            <button type="submit">Đăng ký</button>
        </form>
        <div class="login-link">
            <router-link to="/login">Bạn đã có tài khoản? Đăng nhập ngay</router-link>
        </div>
        <div class="back-to-home">
            <router-link to="/"><i class="fa fa-arrow-left"></i> Trở về trang chủ</router-link>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            formData: {
                name: "",
                email: "",
                phone: "",
                password: "",
                city: "",
                address: "",
            },
        };
    },
    methods: {
        register() {
            const { name, email, phone, password, city, address } = this.formData;
            axios
                .post("/api/customer/register", {
                    name,
                    email,
                    phone,
                    password,
                    city,
                    address,
                })
                .then((response) => {
                    console.log("Registration successful:", response.data);
                    this.$router.push('/login');
                })
                .catch((error) => {
                    console.error("Registration error:", error);
                });
        },
    },
};
</script>

<style scoped>
.register-container {
    max-width: 450px;
    margin: 40px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1); /* Đổ bóng cho form */
}

.register-form .form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
}

button:hover {
    background-color: #0056b3;
}

input:focus,
button:focus {
    outline: none;
}

.login-link {
    margin-top: 20px;
}

.login-link a {
    color: #007bff;
    text-decoration: none;
}

.back-to-home {
    margin-top: 10px;
}

.back-to-home a {
    color: #007bff;
    text-decoration: none;
}

.back-to-home i {
    margin-right: 5px;
}
</style>
