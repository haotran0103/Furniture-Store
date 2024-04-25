<template>
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <input
                type="text"
                class="form-control w-50"
                placeholder="Tìm kiếm sản phẩm..."
                v-model="searchQuery"
                @input="fetchProducts"
            />
            <button class="btn btn-primary" @click="openAddProductModal">
                Thêm Sản Phẩm
            </button>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Tên Sản Phẩm</th>
                    <th>Giá</th>
                    <th>Số Lượng</th>
                    <th>Ảnh</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.id">
                    <td>{{ product.name }}</td>
                    <td>{{ product.price }}</td>
                    <td>{{ product.quantity }}</td>
                    <td>
                        <img
                            v-if="product.images.length > 0"
                            :src="getImageUrl(product.images[0])"
                            alt="Product image"
                            class="img-fluid"
                            style="max-width: 100px; max-height: 100px"
                        />
                        <img
                            v-else
                            src="default-product-image.png"
                            alt="Default product image"
                            class="img-fluid"
                            style="max-width: 100px; max-height: 100px"
                        />
                    </td>

                    <td>
                        <button
                            class="btn btn-info btn-sm"
                            @click="openEditModal(product.id)"
                        >
                            Sửa
                        </button>

                        <button
                            class="btn btn-danger btn-sm"
                            @click="openDeleteConfirmation(product.id)"
                        >
                            Xóa
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item" :disabled="!pagination.prev_page_url">
                    <a
                        class="page-link"
                        href="#"
                        @click="changePage(pagination.current_page - 1)"
                        >Previous</a
                    >
                </li>
                <li class="page-item" :disabled="!pagination.next_page_url">
                    <a
                        class="page-link"
                        href="#"
                        @click="changePage(pagination.current_page + 1)"
                        >Next</a
                    >
                </li>
            </ul>
        </nav>
        <div
            v-if="showAddProductModal"
            class="modal fade show d-block"
            tabindex="-1"
            role="dialog"
            aria-labelledby="modalTitle"
            aria-modal="true"
            style="background-color: rgba(0, 0, 0, 0.5)"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">
                            {{
                                isEditMode
                                    ? "Chỉnh Sửa Sản Phẩm"
                                    : "Thêm Sản Phẩm Mới"
                            }}
                        </h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="productName">Tên sản phẩm</label>
                            <input
                                type="text"
                                class="form-control"
                                id="productName"
                                v-model="newProduct.name"
                                required
                            />
                        </div>
                        <div class="form-group">
                            <label for="productPrice">Giá</label>
                            <input
                                type="number"
                                class="form-control"
                                id="productPrice"
                                v-model="newProduct.price"
                                required
                            />
                        </div>
                        <div class="form-group">
                            <label for="productQuantity">Số lượng</label>
                            <input
                                type="number"
                                class="form-control"
                                id="productQuantity"
                                v-model="newProduct.quantity"
                                required
                            />
                        </div>
                        <div class="form-group mt-2">
                            <select
                                id="productCategory"
                                class="form-control"
                                v-model="newProduct.category_id"
                            >
                                <option value="">Chọn danh mục</option>
                                <template
                                    v-for="category in categories"
                                    :key="category.id"
                                >
                                    <option :value="category.id">
                                        {{ category.name }}
                                    </option>
                                    <template v-if="category.children.length">
                                        <template
                                            v-for="child in category.children"
                                            :key="child.id"
                                        >
                                            <option :value="child.id">
                                                -- {{ child.name }}
                                            </option>
                                        </template>
                                    </template>
                                </template>
                            </select>
                        </div>
                        <div class="form-group" style="position: relative">
                            <label for="tagInput">Tags</label>
                            <input
                                type="text"
                                id="tagInput"
                                class="form-control"
                                placeholder="Nhập và chọn tags..."
                                v-model="tagInput"
                                @input="filterTags"
                            />
                            <div
                                v-if="filteredTags.length > 0"
                                class="tags-suggestions"
                                style="
                                    position: absolute;
                                    top: 100%;
                                    left: 0;
                                    width: 100%;
                                    background: white;
                                    z-index: 1000;
                                    border: 1px solid #ddd;
                                "
                            >
                                <ul class="list-group">
                                    <li
                                        v-for="tag in filteredTags"
                                        :key="tag.id"
                                        class="list-group-item list-group-item-action"
                                        @click="selectTag(tag)"
                                    >
                                        {{ tag.name }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- Hiển thị tags đã chọn -->
                        <div class="selected-tags">
                            <span
                                style="color: black"
                                v-for="(tag, index) in selectedTags"
                                :key="tag.id"
                                class="badge badge-primary"
                            >
                                {{ tag.name }}
                                <span
                                    style="color: black; cursor: pointer"
                                    class="remove-tag"
                                    @click="removeTag(index)"
                                    >x</span
                                >
                            </span>
                        </div>

                        <div class="form-group mt-2">
                            <label for="productImages">Ảnh sản phẩm</label>
                            <input
                                type="file"
                                class="form-control-file"
                                id="productImages"
                                @change="handleFileUpload"
                                multiple
                            />
                        </div>
                        <div class="form-group">
                            <label>Ảnh xem trước:</label>
                            <div>
                                <img
                                    v-for="(image, index) in previewImages"
                                    :src="image.url"
                                    :alt="image.name"
                                    :key="index"
                                    class="img-thumbnail"
                                    style="max-width: 100px; margin-right: 5px"
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="productDescription"
                                >Mô tả sản phẩm</label
                            >
                            <ckeditor
                                :editor="editor"
                                v-model="editorData"
                                @ready="onReady"
                                @input="onChange"
                            ></ckeditor>
                        </div>
                        <button
                            type="button"
                            class="btn btn-primary mt-2"
                            @click="submitForm"
                        >
                            {{
                                isEditMode
                                    ? "Cập Nhật Sản Phẩm"
                                    : "Thêm Sản Phẩm"
                            }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div
        v-if="showDeleteConfirmation"
        class="modal fade show d-block"
        tabindex="-1"
        role="dialog"
        style="background-color: rgba(0, 0, 0, 0.5)"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác Nhận Xóa Sản Phẩm</h5>
                    <button
                        type="button"
                        class="close"
                        @click="showDeleteConfirmation = false"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa sản phẩm này không?
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="showDeleteConfirmation = false"
                    >
                        Hủy Bỏ
                    </button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        @click="confirmDeleteProduct"
                    >
                        Xóa Sản Phẩm
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import CKEditor from "@ckeditor/ckeditor5-vue";
export default {
    name: "app",
    components: {
        ckeditor: CKEditor.component,
    },
    data() {
        return {
            editor: ClassicEditor,
            editorData: "<p>Hello from CKEditor 5!</p>",
        };
    },
    methods: {
        getImageUrl(path) {
            return `http://localhost:8000/${path}`;
        },
        onReady(editor) {
            console.log("CKEditor5 Vue Component is ready to use!", editor);
        },
        onChange(data) {
            console.log(data);
        },
    },
};
</script>
<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const newProduct = ref({
    name: "",
    price: 0,
    quantity: 0,
    category_id: null,
    tags: [],
    content: "",
});
const currentProductId = ref(null);
const editorData = ref("<p>Content of the editor.</p>");
const previewImages = ref([]);
const products = ref([]);
const pagination = ref({
    current_page: 1,
    next_page_url: null,
    prev_page_url: null,
});
const selectedFiles = ref([]);
const handleFileUpload = (event) => {
    previewImages.value = [];

    for (const file of event.target.files) {
        const reader = new FileReader();

        reader.onload = (e) => {
            previewImages.value.push(e.target.result);
            selectedFiles.value = event.target.files;
        };
        reader.readAsDataURL(file);
    }
};

const tagInput = ref("");
const allTags = ref([]);
const filteredTags = ref([]);
const selectedTags = ref([]);
const searchQuery = ref("");
const showAddProductModal = ref(false);
const isEditMode = ref(false);
const categories = ref([]);
const tags = ref([]);
const showDeleteConfirmation = ref(false);
const productIdToDelete = ref(null);
const openDeleteConfirmation = (productId) => {
  productIdToDelete.value = productId;
  showDeleteConfirmation.value = true;
};

const confirmDeleteProduct = async () => {
  try {
    await axios.delete(`/api/products/${productIdToDelete.value}`);
    alert('Sản phẩm đã được xóa thành công');
    fetchProducts();
  } catch (error) {
    console.error('Có lỗi khi xóa sản phẩm:', error.response.data);
    alert('Có lỗi khi xóa sản phẩm');
  } finally {
    showDeleteConfirmation.value = false;
  }
};
const openEditModal = async (productId) => {
    isEditMode.value = true;
    showAddProductModal.value = true;
    currentProductId.value = productId;

    const response = await axios.get(`/api/products/${productId}`);
    const product = response.data;

    newProduct.value = {
        name: product.name,
        price: product.price,
        quantity: product.quantity,
        category_id: product.category_id,
    };
    editorData.value = product.content || "<p>Content of the editor.</p>";
    selectedTags.value = product.tags.map((tag) => tag);
    if (product.feature_image) {
        const imageUrls = JSON.parse(product.feature_image);
        console.log(imageUrls);
        previewImages.value = imageUrls.map((url) => {
            console.log(url);
            return {
                name: url.split("/").pop(), // Lấy tên file từ URL nếu cần
                url: `http://localhost:8000/${url}`,
            };
        });
    } else {
        previewImages.value = []; // Không có ảnh, đặt mảng rỗng
    }
};
const closeModal = () => {
    resetFormState();
    showAddProductModal.value = false;
};
const openAddProductModal = () => {
    resetFormState();
    showAddProductModal.value = true;
};
const resetFormState = () => {
    newProduct.value = {
        name: "",
        price: 0,
        quantity: 0,
        category_id: null,
        tags: [],
        content: "",
    };
    editorData.value = "<p>Content of the editor.</p>";
    previewImages.value = [];
    selectedTags.value = [];
    currentProductId.value = null;
    isEditMode.value = false;
};

const updateProduct = async () => {
    let formData = new FormData();
    formData.append("name", newProduct.value.name);
    formData.append("price", newProduct.value.price);
    formData.append("quantity", newProduct.value.quantity);
    formData.append("category_id", newProduct.value.category_id);
    formData.append("content", editorData.value);
    selectedTags.value.forEach((tagId) => {
        formData.append("tags[]", tagId.id);
    });

    try {
        await axios.post(`/api/products/${currentProductId.value}`, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        alert("Sản phẩm đã được cập nhật thành công");
        showAddProductModal.value = false;
        fetchProducts(); // Làm mới danh sách sản phẩm
    } catch (error) {
        console.error("Có lỗi khi cập nhật sản phẩm:", error.response.data);
        alert("Có lỗi khi cập nhật sản phẩm");
    }
};

const submitForm = () => {
    if (isEditMode.value) {
        updateProduct();
    } else {
        addNewProduct();
    }
};

const fetchTags = async () => {
    try {
        const response = await axios.get("/api/admin/tags");
        allTags.value = response.data.data;
    } catch (error) {
        console.error("Could not fetch tags:", error);
    }
};
const filterTags = () => {
    if (tagInput.value === "") {
        filteredTags.value = [];
    } else {
        filteredTags.value = allTags.value.filter(
            (tag) =>
                tag.name.toLowerCase().includes(tagInput.value.toLowerCase()) &&
                !selectedTags.value.map((t) => t.id).includes(tag.id)
        );
    }
};

const selectTag = (tag) => {
    selectedTags.value.push(tag);
    tagInput.value = "";
    filteredTags.value = [];
};

const removeTag = (index) => {
    selectedTags.value.splice(index, 1);
};
const fetchCategories = async () => {
    try {
        const response = await axios.get("/api/admin/categories");
        categories.value = processCategories(response.data.data.data);
    } catch (error) {
        console.error("Error fetching categories:", error);
    }
};
const processCategories = (categoriesList) => {
    let map = {};
    categoriesList.forEach((category) => {
        if (!map[category.parent_id]) {
            map[category.parent_id] = [];
        }
        map[category.parent_id].push(category);
    });

    function buildTree(parentId) {
        return map[parentId]
            ? map[parentId].map((category) => ({
                  ...category,
                  children: buildTree(category.id),
              }))
            : [];
    }

    return buildTree(0); // Giả sử ID của root là 0
};

const deleteProduct = (id) => {
    console.log("Deleting product with id:", id);
};
const addNewProduct = async () => {
    // Tạo FormData
    let formData = new FormData();
    formData.append("name", newProduct.value.name);
    formData.append("price", newProduct.value.price);
    formData.append("quantity", newProduct.value.quantity);
    formData.append("category_id", newProduct.value.category_id);
    formData.append("content", editorData.value);

    selectedTags.value.forEach((tagId) => {
        formData.append("tags[]", tagId.id);
    });

    for (let i = 0; i < selectedFiles.value.length; i++) {
        formData.append("images[]", selectedFiles.value[i]);
    }

    try {
        const response = await axios.post("/api/products", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        alert("Sản phẩm đã được thêm thành công");
    } catch (error) {
        console.error("Có lỗi khi thêm sản phẩm:", error.response.data);
        alert("Có lỗi khi thêm sản phẩm");
    }
};
const fetchProducts = async (page = 1) => {
    try {
        const response = await axios.get(
            `/api/products?page=${page}&search=${searchQuery.value}`
        );
        products.value = response.data.data.map((product) => {
            product.images = product.feature_image
                ? JSON.parse(product.feature_image)
                : [];
            return product;
        });
        pagination.value = {
            current_page: response.data.current_page,
            next_page_url: response.data.next_page_url,
            prev_page_url: response.data.prev_page_url,
        };
    } catch (error) {
        console.error("Error fetching products:", error);
    }
};

const changePage = (page) => {
    if (
        page > 0 &&
        ((pagination.value.next_page_url &&
            page > pagination.value.current_page) ||
            (pagination.value.prev_page_url &&
                page < pagination.value.current_page))
    ) {
        fetchProducts(page);
    }
};

onMounted(() => {
    fetchProducts();
    fetchCategories();
    fetchTags();
});
</script>
