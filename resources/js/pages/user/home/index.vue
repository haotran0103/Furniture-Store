<template>
    <Slider />
    <section class="featured-products section-padding">
        <div class="container">
            <h2 class="section-title">SẢN PHẨM NỖI BẬT</h2>
            <div class="row">
                <div
                    class="col-md-4"
                    v-for="product in featuredProducts"
                    :key="product.id"
                >
                    <div class="product-card">
                        <img
                            :src="product.feature_image"
                            alt="Product Image"
                            class="product-image"
                        />
                        <h3>{{ product.name }}</h3>
                        <p>Giá: {{ formatPrice(product.price) }} VND</p>
                        <p>Còn lại: {{ product.quantity }} sản phẩm</p>
                        <router-link
                            :to="`/products-detail/${product.id}`"
                            class="btn btn-primary"
                            >Chi tiết sản phẩm</router-link
                        >

                        <a
                            @click.prevent="addToCart(product)"
                            class="btn btn-secondary"
                        >
                            <i class="fas fa-shopping-cart"></i>Thêm giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="categories section-padding">
        <div class="container">
            <h2 class="section-title">CÁC MẶT HÀNG CHÍNH</h2>
            <div class="row">
                <div
                    class="col-md-4"
                    v-for="category in categories"
                    :key="category.id"
                >
                    <a
                        @click.prevent="fetchProductsForCategory(category.id)"
                        class="category-box"
                    >
                        <div class="category-box-inner">
                            <h3>{{ category.name }}</h3>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section
        class="products-by-category section-padding"
        v-if="currentCategoryProducts.length > 0"
    >
        <div class="container">
            <div class="row">
                <div
                    class="col-md-4"
                    v-for="product in currentCategoryProducts"
                    :key="product.id"
                >
                    <div class="product-card">
                        <img
                            :src="product.feature_image"
                            alt="Product Image"
                            class="product-image"
                        />
                        <h3>{{ product.name }}</h3>
                        <p>Giá: {{ formatPrice(product.price) }} VND</p>
                        <router-link
                            :to="`/products-detail/${product.id}`"
                            class="btn btn-primary"
                            >Chi tiết sản phẩm</router-link
                        >
                        <a
                            @click.prevent="addToCart(product)"
                            class="btn btn-secondary"
                        >
                            <i class="fas fa-shopping-cart"></i>Thêm giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="popular-tags section-padding">
        <div class="container">
            <h2 class="section-title">Popular Tags</h2>
            <div class="tags">
                <a
                    v-for="tag in popularTags"
                    :key="tag.id"
                    :href="`/tag/${tag.id}`"
                    class="tag"
                    >{{ tag.name }},
                </a>
            </div>
        </div>
    </section>
</template>
<script>
import Slider from '../../../components/user/Slider.vue';

export default {
    components:{
        Slider
    },
    mounted() {
        
    }
}
</script>
<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";
import { useStore } from "vuex";

const featuredProducts = ref([]);
const categories = ref([]);
const popularTags = ref([]);
const currentCategoryProducts = ref([]);
const store = useStore();
const parseImageFromJson = (jsonString) => {
    try {
        const images = JSON.parse(jsonString);
        return images.length > 0 ? images[0] : null;
    } catch (error) {
        console.error("Error parsing JSON:", error);
        return null;
    }
};
const formatPrice = (price) => {
    return parseInt(price).toLocaleString("vi-VN");
};
const addToCart = (product) => {
    console.log("product" , product);
    store.dispatch("addToCart", product);
};

onMounted(async () => {
    try {
        const [prodRes, catRes, tagRes, RandRes] = await Promise.all([
            axios.get("/api/featured-products"),
            axios.get("/api/admin/categories"),
            axios.get("/api/popular-tags"),
            axios.get("/api/products-random"),
        ]);
        featuredProducts.value = prodRes.data.map((product) => {
            return {
                ...product,
                feature_image: parseImageFromJson(product.feature_image),
            };
        });
        currentCategoryProducts.value = RandRes.data.map((product) => {
            return {
                ...product,
                feature_image: parseImageFromJson(product.feature_image),
            };
        });
        categories.value = catRes.data.data.data;
        popularTags.value = tagRes.data;
    } catch (error) {
        console.error("Failed to load data:", error);
    }
});

const fetchProductsForCategory = async (categoryId) => {
    try {
        const response = await axios.get(
            `/api/products-by-category/${categoryId}`
        );
        currentCategoryProducts.value = response.data.map((product) => ({
            ...product,
            feature_image: parseImageFromJson(product.feature_image),
        }));
    } catch (error) {
        console.error("Failed to fetch products for category:", error);
    }
};
</script>
<style scoped>
.product-card {
    border: 1px solid #ddd;
    padding: 20px;
    margin-bottom: 20px;
    text-align: center;
}

.product-image {
    width: 100%;
    height: auto;
    margin-bottom: 15px;
}

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

/* Existing styles */
.section-padding {
    padding: 40px 0;
}

.section-title {
    margin-bottom: 35px;
    text-align: center;
}
</style>
