<style scoped>
.login-box {
    width: 100%;
    max-width: 400px;
    background: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.error-text {
    color: #e74c3c;
}

.form-control {
    border-radius: 5px;
}

.btn-primary {
    border-radius: 5px;
}
a {
    text-decoration: none;
}
</style>

<template>
    <div
        class="container d-flex align-items-center justify-content-center"
        style="min-height: 100vh"
    >
        <div class="login-box shadow">
            <form @submit.prevent="handleLogin" class="p-4">
                <h2 class="text-center mb-4">Đăng Nhập</h2>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        v-model.trim="loginForm.email"
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
                        v-model.trim="loginForm.password"
                        class="form-control"
                    />
                    <span v-if="errors.password" class="error-text">{{
                        errors.password
                    }}</span>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-2 mt-2">
                    Đăng nhập
                </button>
                <div class="text-center">
                    <a href="/" class="d-inline-block mb-2">Trở về trang chủ</a>
                    <br />
                    <a href="/auth/register" class="d-inline-block"
                        >Chưa có tài khoản?</a
                    >
                </div>
            </form>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import { mapState, mapGetters, mapActions, mapMutations } from "vuex";
export default {
    data() {
        return {
            loginForm: {
                email: "",
                password: "",
            },
            errors: {},
        };
    },
    computed: {
        ...mapGetters("authStore", ["isAuthenticated", "getUser"]),
    },
    methods: {
        ...mapActions("authStore", ["login"]),

        async handleLogin() {
            this.errors = {};

            if (!this.loginForm.email) {
                this.errors.email = "Email không được để trống";
            } else if (!this.validEmail(this.loginForm.email)) {
                this.errors.email = "Email không hợp lệ";
            }

            if (!this.loginForm.password) {
                this.errors.password = "Mật khẩu không được để trống";
            } else if (this.loginForm.password.length < 6) {
                this.errors.password = "Mật khẩu phải dài ít nhất 6 ký tự";
            }

            if (Object.keys(this.errors).length === 0) {
                try {
                    console.log("loginForm:", this.loginForm);
                    await this.login(this.loginForm);
                    console.log("isAuthenticated:", this.isAuthenticated);
                    console.log("getUser:", this.getUser);

                    if (this.isAuthenticated) {
                        if (this.getUser.role === "admin") {
                            this.$router.push("/admin/dashboard");
                        } else {
                            this.$router.push("/");
                        }
                    }
                } catch (error) {
                    if (error.response && error.response.status === 401) {
                        this.errors.general =
                            "Email hoặc mật khẩu không chính xác";
                    } else {
                        console.error("Lỗi khi đăng nhập:", error);
                    }
                }
            }
        },

        validEmail(email) {
            const re =
                /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email.toLowerCase());
        },
    },
};
</script>
