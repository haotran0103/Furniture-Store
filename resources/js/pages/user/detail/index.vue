<template>
    <div class="product-detail-page" v-if="product">
        <div class="container">
            <div class="flex-container">
                <!-- Images Column -->
                <div class="left-column">
                    <img
                        v-if="productImages.length > 0"
                        :src="productImages[0]"
                        class="product-image main-image"
                        :alt="`Product image 1`"
                    />
                    <div v-if="productImages.length > 1" class="option-images">
                        <img
                            v-for="(image, index) in productImages.slice(1)"
                            :key="`option-${index}`"
                            :src="image"
                            class="product-image option-image"
                            :alt="`Product image ${index + 2}`"
                        />
                    </div>
                </div>

                <!-- Information Column -->
                <div class="right-column">
                    <div class="product-info">
                        <h1>{{ product.name }}</h1>
                        <p class="price">
                            Price: {{ formatPrice(product.price) }}
                        </p>
                        <p>Available Quantity: {{ product.quantity }}</p>
                        <p>Sold: {{ product.sold }}</p>
                        <router-link
                            :to="`/add-to-cart/${product.id}`"
                            class="btn add-to-cart-btn"
                            ><i class="fas fa-plus fa-fw"></i> Add to
                            Cart</router-link
                        >
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="product-content">
                <div v-html="product.content" class="content"></div>
            </div>

            <div class="suggested-products" v-if="suggestedProducts.length > 0">
                <h2>Suggested Products</h2>
                <div class="products-container">
                    <div
                        class="product-card"
                        v-for="(suggestedProduct, index) in suggestedProducts"
                        :key="index"
                    >
                        <img
                            :src="suggestedProduct.feature_image"
                            alt="Suggested Product Image"
                            class="product-image"
                        />

                        <div class="product-info">
                            <h3>{{ suggestedProduct.name }}</h3>
                            <p>
                                Price: {{ formatPrice(suggestedProduct.price) }}
                            </p>
                            <router-link
                                :to="`/products/${suggestedProduct.id}`"
                                class="btn btn-primary"
                                >View Product</router-link
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else>
        <p>Product not found or loading...</p>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";

const route = useRoute();
const product = ref(null);
const startIndex = ref(0);
const suggestedProducts = ref([]);
const visibleOptionImages = computed(() => {
    const endIndex = startIndex.value + 4; // Show up to 4 images at a time
    return productImages.value.slice(
        startIndex.value,
        Math.min(endIndex, productImages.value.length)
    );
});

const nextImage = () => {
    if (startIndex.value + 4 < productImages.value.length) {
        startIndex.value += 4;
    }
};

const prevImage = () => {
    if (startIndex.value - 4 >= 0) {
        startIndex.value -= 4;
    }
};
onMounted(async () => {
    const productId = route.params.productId;
    try {
        const response = await axios.get(`/api/products/${productId}`);
        product.value = response.data;
        const suggestionsResponse = await axios.get(
            `/api/products/suggestions/${productId}`
        );
        suggestedProducts.value = suggestionsResponse.data.map((product) => ({
            ...product,
            feature_image: JSON.parse(product.feature_image)[0],
        }));
    } catch (error) {
        console.error("Failed to fetch product details:", error);
    }
});

const productImages = computed(() => {
    if (!product.value || !product.value.feature_image) return [];
    try {
        return JSON.parse(product.value.feature_image);
    } catch (error) {
        console.error("Error parsing product images JSON:", error);
        return [];
    }
});

const formatPrice = (price) => {
    return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
    }).format(price);
};
</script>
<style scoped>
.flex-container {
    display: flex;
    gap: 20px;
    margin-bottom: 20px; /* Space between the flex container and content */
}

.left-column,
.right-column {
    flex: 1;
}

.product-image.main-image {
    width: 100%;
    height: auto;
    display: block; /* Ensure it's a block for proper spacing */
}

.option-images {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
}

.product-image.option-image {
    width: 100px;
    height: auto;
}

.add-to-cart-btn {
    background-color: #007bff;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    font-weight: bold;
    justify-content: center;
}

.add-to-cart-btn:hover {
    background-color: #0056b3;
}

.product-info {
    margin-top: 20px;
}

.price {
    font-size: 24px;
    font-weight: bold;
}

.content {
    margin-top: 20px;
    background-color: #f9f9f9; /* Light background to differentiate from the rest of the page */
    padding: 20px;
    border-radius: 5px;
}

.product-detail-page {
    padding: 20px;
}
.suggested-products {
  margin-top: 40px;
}

.suggested-products .products-container {
  display: flex;
  justify-content: start;
  gap: 20px;
  flex-wrap: wrap;
}

.suggested-products .product-card {
  width: calc(25% - 20px);
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.suggested-products .product-image {
  width: 100%;
  max-height: 200px; /* Adjust based on your needs */
  object-fit: cover;
}

/* Adjustments to ensure suggested products' info is aligned */
.suggested-products .product-info {
  padding: 10px;
}

@media (max-width: 768px) {
  .suggested-products .product-card {
    width: calc(50% - 20px); /* Adjust for smaller screens */
  }
}

@media (max-width: 480px) {
  .suggested-products .product-card {
    width: 100%; /* Full width on very small screens */
  }
}

</style>
