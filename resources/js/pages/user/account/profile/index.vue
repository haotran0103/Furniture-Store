<template>
    <div class="profile-page">
        <h1>Trang cá nhân</h1>
        <div class="form-container">
            <form @submit.prevent="updateProfile">
                <div>
                    <label for="name">Họ tên:</label>
                    <input type="text" id="name" v-model="userData.name" />
                </div>
                <div>
                    <label for="phone">Số điện thoại:</label>
                    <input type="text" id="phone" v-model="userData.phone" />
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" v-model="userData.email" disabled />
                </div>
                <div>
                    <label for="city">Thành phố:</label>
                    <input type="text" id="city" v-model="userData.city" />
                </div>
                <div>
                    <label for="address">Địa chỉ:</label>
                    <input type="text" id="address" v-model="userData.address" />
                </div>
                <div class="button-group">
                    <button type="submit" class="btn-save">Lưu Thay Đổi</button>
                    <button type="button" class="btn-password" @click="openPasswordModal">Đổi mật khẩu</button>
                </div>
            </form>
        </div>

        <div v-if="showPasswordModal" class="modal">
            <div class="modal-content">
                <h2>Đổi mật khẩu</h2>
                <form @submit.prevent="changePassword">
                    <div>
                        <label for="currentPassword">Mật khẩu cũ:</label>
                        <input
                            type="password"
                            id="currentPassword"
                            v-model="currentPassword"
                        />
                    </div>
                    <div>
                        <label for="newPassword">Mật khẩu mới:</label>
                        <input
                            type="password"
                            id="newPassword"
                            v-model="newPassword"
                        />
                    </div>
                    <button type="submit">Lưu thay đổi</button>
                    <button type="button" @click="closePasswordModal">Hủy</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            userData: {
                name: "",
                phone: "",
                email: "",
                city: "",
                address: "",
            },
            currentPassword: "",
            newPassword: "",
            token: "",
            showPasswordModal: false,
        };
    },
    methods: {
        async fetchUserData() {
            try {
                const response = await axios.get("/api/customer/profile", {
                    headers: {
                        Authorization: `Bearer ${this.token}`,
                    },
                });
                this.userData = response.data;
            } catch (error) {
                console.error("Lỗi khi lấy thông tin người dùng:", error);
            }
        },
        async updateProfile() {
            try {
                const response = await axios.put(
                    "/api/customer/profile",
                    this.userData,
                    {
                        headers: {
                            Authorization: `Bearer ${this.token}`,
                        },
                    }
                );
                alert("Cập nhật thông tin thành công!");
            } catch (error) {
                console.error("Lỗi khi cập nhật thông tin người dùng:", error);
                alert("Lỗi khi cập nhật thông tin người dùng.");
            }
        },
        openPasswordModal() {
            this.showPasswordModal = true;
        },
        closePasswordModal() {
            this.showPasswordModal = false;
        },
        async changePassword() {
            try {
                const response = await axios.post(
                    "/api/customer/change-password",
                    {
                        currentPassword: this.currentPassword,
                        newPassword: this.newPassword,
                    },
                    {
                        headers: {
                            Authorization: `Bearer ${this.token}`,
                        },
                    }
                );
                if (response.status === 200) {
                    alert("Mật khẩu đã được thay đổi thành công!");
                    this.closePasswordModal();
                }
            } catch (error) {
                console.error("Lỗi khi thay đổi mật khẩu:", error);
                alert("Lỗi khi thay đổi mật khẩu.");
            }
        },
    },
    mounted() {
        this.token = localStorage.getItem("customer_token");
        this.fetchUserData();
    },
};
</script>
<style>
.profile-page {
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.form-container {
    width: 50%;
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

form div {
    margin-bottom: 15px;
}

form label {
    display: block;
    margin-bottom: 5px;
}

form input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.button-group {
    display: flex;
    justify-content: space-between;
}

button {
    padding: 10px 15px;
    background-color: #54e57f;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button.btn-save {
    margin-right: 10px;
}

button:hover {
    background-color: #48b869;
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
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.modal button {
    margin-right: 10px;
    padding: 10px;
}

</style>