<template>
    <div class="container mt-5">
        <div class="row">
            <!-- Filter Sidebar -->
            <div class="col-md-3">
                <!-- Category Filter -->
                <div class="card mb-3">
                    <div class="card-header">Categories</div>
                    <ul class="list-group list-group-flush">
                        <li
                            class="list-group-item"
                            v-for="category in categories"
                            :key="category.id"
                        >
                            <input
                                type="checkbox"
                                v-model="category.checked"
                                :value="category.id"
                                @change="filterProducts"
                            />

                            {{ category.name }}
                        </li>
                    </ul>
                </div>

                <!-- Price Filter -->
                <div class="card mb-3">
                    <div class="card-header">Price Range</div>
                    <div class="card-body">
                        <input
                            type="range"
                            min="0"
                            max="100000"
                            v-model="priceRange"
                            class="form-range"
                        />
                        <p class="text-center mt-3">
                            Price: {{ formatPrice(priceRange) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Product List -->
            <div class="col-md-9">
                <div class="row">
                    <!-- Product Cards -->
                    <div
                        class="col-md-4 mb-3"
                        v-for="product in paginatedProducts"
                        :key="product.id"
                    >
                        <div class="card">
                            <img
                                :src="product.feature_image"
                                alt="Product Image"
                                class="product-image"
                            />
                            <div class="card-body">
                                <h5 class="card-title">{{ product.name }}</h5>
                                <p class="card-text">
                                    {{ formatPrice(product.price) }}
                                </p>
                                <a
                                    :href="`/products/${product.slug}`"
                                    class="btn btn-primary"
                                    >View Product</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item" :class="{ disabled: currentPage === 1 }">
                            <button class="page-link" @click="currentPage > 1 ? currentPage-- : null" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </button>
                        </li>
                        <li class="page-item" v-for="page in totalPages" :key="page" :class="{ active: page === currentPage }">
                            <button class="page-link" @click="currentPage = page">{{ page }}</button>
                        </li>
                        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                            <button class="page-link" @click="currentPage < totalPages ? currentPage++ : null" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, computed, watch } from "vue";
import axios from "axios";

export default {
    setup() {
        const products = ref([]);
        const categories = ref([]);
        const priceRange = ref(0);
        const currentPage = ref(1);
        const perPage = 12;
        const filteredProducts = ref([]);

        onMounted(async () => {
            try {
                const productsResponse = await axios.get("/api/products-all");
                const categoriesResponse = await axios.get("/api/admin/categories");

                products.value = productsResponse.data.map((product) => {
                    return { ...product, feature_image: parseImageFromJson(product.feature_image) };
                });
                categories.value = categoriesResponse.data.data.data.map((category) => {
                    return { ...category, checked: false };
                });
            } catch (error) {
                console.error("Error fetching products and categories:", error);
            }
        });

        const selectedCategories = computed(() => {
            return categories.value.filter((category) => category.checked).map((category) => category.id);
        });

        const filterProducts = () => {
            filteredProducts.value = products.value.filter((product) => {
                const isInRange = parseFloat(product.price) >= priceRange.value;
                const selectedCategoryIds = selectedCategories.value.map((id) => String(id));
                const productCategoryId = String(product.category_id);
                const isSelectedCategory = selectedCategoryIds.length === 0 || selectedCategoryIds.includes(productCategoryId);
                return isInRange && isSelectedCategory;
            });
        };

        watch([selectedCategories, priceRange], () => {
            filterProducts();
            currentPage.value = 1; // Reset to first page when filters change
        });

        const parseImageFromJson = (jsonString) => {
            try {
                const images = JSON.parse(jsonString);
                return images.length > 0 ? images[0] : null;
            } catch (error) {
                console.error("Error parsing JSON:", error);
                return null;
            }
        };

        const totalPages = computed(() => Math.ceil(filteredProducts.value.length / perPage));

        const paginatedProducts = computed(() => {
            const startIndex = (currentPage.value - 1) * perPage;
            const endIndex = startIndex + perPage;
            return filteredProducts.value.slice(startIndex, endIndex);
        });

        const formatPrice = (price) => {
            return new Intl.NumberFormat("vi-VN", {
                style: "currency",
                currency: "VND",
            }).format(price);
        };

        return {
            products,
            categories,
            priceRange,
            selectedCategories,
            filteredProducts,
            currentPage,
            perPage,
            paginatedProducts,
            totalPages,
            formatPrice,
        };
    },
};
</script>

<style scoped>
/* Custom styling */
</style>
