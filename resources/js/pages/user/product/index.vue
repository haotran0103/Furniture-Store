<template>
    <div class="container mt-5">
        <h1>{{ category }}</h1>
        <div class="row">
            <div
                class="col-md-4 mb-3"
                v-for="product in displayedProducts"
                :key="product.id"
            >
                <div class="card">
                    <img
                        :src="getFirstImage(product.feature_image)"
                        class="card-img-top"
                        :alt="product.name"
                    />
                    <div class="card-body">
                        <h5 class="card-title">{{ product.name }}</h5>
                        <p class="card-text">
                            Giá: {{ formatPrice(product.price) }}
                        </p>
                        <div class="product-buttons">
                            <router-link
                                :to="`/products-detail/${product.id}`"
                                class="btn btn-primary mb-2"
                                >Chi tiết sản phẩm</router-link
                            >
                            <a
                                :href="`/add-to-cart/${product.id}`"
                                class="btn btn-secondary"
                                ><i class="fas fa-shopping-cart"></i> Thêm giỏ
                                hàng</a
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                    <a class="page-link" href="#" @click="prevPage">Previous</a>
                </li>
                <li
                    class="page-item"
                    v-for="page in totalPages"
                    :key="page"
                    :class="{ active: page === currentPage }"
                >
                    <a class="page-link" href="#" @click="goToPage(page)">{{
                        page
                    }}</a>
                </li>
                <li
                    class="page-item"
                    :class="{ disabled: currentPage === totalPages }"
                >
                    <a class="page-link" href="#" @click="nextPage">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";

export default {
    name: "ProductCategory",
    data() {
        return {
            category: "",
            products: [],
            currentPage: 1,
            pageSize: 9,
        };
    },
    computed: {
        totalPages() {
            return Math.ceil(this.products.length / this.pageSize);
        },
        displayedProducts() {
            const start = (this.currentPage - 1) * this.pageSize;
            const end = start + this.pageSize;
            return this.products.slice(start, end);
        },
    },
    mounted() {
        this.category = this.$route.params.category;
        this.fetchProducts();
    },
    methods: {
        async fetchProducts() {
            try {
                const response = await axios.get(
                    `/api/products?category=${this.category}`
                );
                this.products = response.data.data;
            } catch (error) {
                console.error("Error fetching products:", error);
            }
        },
        formatPrice(price) {
            return new Intl.NumberFormat("vi-VN", {
                style: "currency",
                currency: "VND",
            }).format(price);
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        goToPage(page) {
            this.currentPage = page;
        },
        getFirstImage(imageJson) {
            try {
                const images = JSON.parse(imageJson);
                return images && images.length > 0 ? images[0] : null;
            } catch (error) {
                console.error("Error parsing image JSON:", error);
                return null;
            }
        },
    },
};
</script>

<style scoped>
.btn-primary,
.btn-secondary {
    color: black; /* Chữ đen */
    background-color: white; /* Nền trắng */
    border: 1px solid black; /* Viền màu đen */
    margin-top: 10px;
}

.btn-primary:hover,
.btn-secondary:hover {
    background-color: #f8f9fa; /* Một màu nền khác khi hover */
}

.btn-secondary i {
    color: red; /* Icon màu đỏ */
}

.product-buttons {
    display: flex;
    flex-direction: column;
}

</style>
