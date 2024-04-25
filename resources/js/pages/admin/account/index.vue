<template>
    <div class="container">
        <h1>Danh sách khách hàng</h1>

        <div class="input-group mb-3">
            <input type="text" class="form-control" v-model="searchQuery" placeholder="Tìm kiếm...">
            <button class="btn btn-primary" @click="fetchCustomers">Tìm kiếm</button>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th @click="sort('name')">Tên</th>
                    <th @click="sort('email')">Email</th>
                    <th @click="sort('phone')">Số điện thoại</th>
                    <th @click="sort('city')">Thành phố</th>
                    <th @click="sort('address')">Địa chỉ</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="customer in customers" :key="customer.id">
                    <td>{{ customer.name }}</td>
                    <td>{{ customer.email }}</td>
                    <td>{{ customer.phone }}</td>
                    <td>{{ customer.city }}</td>
                    <td>{{ customer.address }}</td>
                </tr>
            </tbody>
        </table>

        <nav>
            <ul class="pagination">
                <li v-if="currentPage > 1" class="page-item">
                    <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)">Trước</a>
                </li>
                <li v-for="page in totalPages" :key="page" class="page-item" :class="{ active: page === currentPage }">
                    <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
                </li>
                <li v-if="currentPage < totalPages" class="page-item">
                    <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)">Tiếp</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            customers: [],
            searchQuery: "",
            sortField: "name",
            sortOrder: "asc",
            currentPage: 1,
            perPage: 10,
            totalPages: 1
        };
    },
    methods: {
        async fetchCustomers() {
            try {
                const response = await axios.get("/api/customer", {
                    params: {
                        search: this.searchQuery,
                        sortField: this.sortField,
                        sortOrder: this.sortOrder,
                        page: this.currentPage,
                        perPage: this.perPage
                    }
                });
                const data = response.data;

                this.customers = data.data;
                this.totalPages = data.last_page;
            } catch (error) {
                console.error("Lỗi khi lấy danh sách khách hàng:", error);
            }
        },
        changePage(page) {
            this.currentPage = page;
            this.fetchCustomers();
        },
        sort(field) {
            if (this.sortField === field) {
                this.sortOrder = this.sortOrder === "asc" ? "desc" : "asc";
            } else {
                this.sortField = field;
                this.sortOrder = "asc";
            }
            this.fetchCustomers();
        }
    },
    created() {
        this.fetchCustomers();
    }
};
</script>

<style>
</style>
