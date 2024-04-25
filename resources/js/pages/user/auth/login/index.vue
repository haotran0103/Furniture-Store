<template>
    <div class="login-container mt-4">
        <h2>Đăng nhập</h2>
        <form @submit.prevent="login" class="login-form">
            <div class="form-group">
                <label for="email">Email:</label>
                <input
                    type="email"
                    v-model="formData.email"
                    id="email"
                    required
                />
                <div v-if="validationErrors.email" class="error-message">
                    {{ validationErrors.email }}
                </div>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input
                    type="password"
                    v-model="formData.password"
                    id="password"
                    required
                />
                <div v-if="validationErrors.password" class="error-message">
                    {{ validationErrors.password }}
                </div>
            </div>
            <button type="submit">Đăng nhập</button>
        </form>
        <router-link to="/auth/user/register" class="register-link">
            Bạn chưa có tài khoản? Đăng ký ngay.
        </router-link>
        <router-link to="/" class="back-link">
            <i class="fa fa-arrow-left"></i> Trang chủ
        </router-link>
    </div>
</template>

<script>
export default {
    data() {
        return {
            formData: {
                email: "",
                password: "",
            },
            validationErrors: {
                email: null,
                password: null,
            },
        };
    },
    methods: {
        validateEmail(email) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailPattern.test(email);
        },
        validatePassword(password) {
            return password.length >= 6;
        },
        login() {
            this.validationErrors.email = null;
            this.validationErrors.password = null;

            const { email, password } = this.formData;

            let valid = true;

            if (!this.validateEmail(email)) {
                this.validationErrors.email = "Email không đúng định dạng.";
                valid = false;
            }

            if (!this.validatePassword(password)) {
                this.validationErrors.password = "Mật khẩu phải dài hơn 6 ký tự.";
                valid = false;
            }

            if (valid) {
                axios
                    .post("/api/customer/login", { email, password })
                    .then((response) => {
                        localStorage.setItem("customer_token", response.data.token);
                        window.location.href = "/";
                    })
                    .catch((error) => {
                        console.error("Login error:", error);
                    });
            }
        },
    },
};
</script>

<style scoped>
.login-container {
    max-width: 400px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.login-form {
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Đổ bóng cho form */
}

.error-message {
    color: red;
    margin-top: 5px;
    font-size: 12px;
}

button {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

.register-link {
    display: block;
    margin-top: 10px;
    color: #007bff;
    text-decoration: none;
}

.back-link {
    display: block;
    margin-top: 20px;
    color: #007bff;
    text-decoration: none;
}

.back-link i {
    margin-right: 5px;
}
</style>
