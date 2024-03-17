<template>
    <div class="container-fuild">
        <div class="row m-2">
            <div class="col-12 d-flex justify-content-between">
                <h4>Trang thể loại</h4>
                <button
                    class="btn btn-primary"
                    @click="showAddCategoryModal = true"
                >
                    Thêm thể loại
                </button>
            </div>
        </div>

        <div class="row m-2">
            <div class="col-12">
              
            </div>
        </div>
        <div
            class="modal"
            tabindex="-1"
            role="dialog"
            :class="{ show: showEditDeleteModal }"
            style="display: block"
            v-if="showEditDeleteModal"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa hoặc xóa thể loại</h5>
                        <button
                            type="button"
                            class="btn-close"
                            aria-label="Close"
                            @click="showEditDeleteModal = false"
                        ></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="editCategory">Chỉnh sửa</button>
                        <button type="button" class="btn btn-danger" @click="deleteCategory">Xóa</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for adding category -->
        <div
            class="modal"
            tabindex="-1"
            role="dialog"
            :class="{ show: showAddCategoryModal }"
            style="display: block"
            v-if="showAddCategoryModal"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm thể loại</h5>
                        <button
                            type="button"
                            class="btn-close"
                            aria-label="Close"
                            @click="showAddCategoryModal = false"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="categoryName">Tên thể loại:</label>
                            <input
                                type="text"
                                class="form-control"
                                id="categoryName"
                                v-model="newCategoryName"
                                @input="generateSlug"
                            />
                        </div>
                        <div class="form-group">
                            <label for="categorySlug" class="form-label"
                                >Slug:</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="categorySlug"
                                v-model="newCategorySlug"
                            />
                        </div>
                        <div class="form-group">
                            <label for="parentCategory">Thể loại cha:</label>
                            <select
                                v-model="selectedParent"
                                class="form-control"
                            >
                                <option value="" disabled selected>
                                    Vui lòng chọn
                                </option>
                                <template v-for="category in categories">
                                    <optgroup :label="category.name">
                                        <option
                                            v-for="child in category.children"
                                            :value="child.id"
                                        >
                                            {{ child.name }}
                                        </option>
                                    </optgroup>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click="showAddCategoryModal = false"
                        >
                            Đóng
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="addCategory"
                        >
                            Thêm
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for edit category -->
    <div
        class="modal"
        tabindex="-1"
        role="dialog"
        :class="{ show: showEditCategoryModal }"
        style="display: block"
        v-if="showEditCategoryModal"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa thể loại</h5>
                    <button
                        type="button"
                        class="btn-close"
                        aria-label="Close"
                        @click="showEditCategoryModal = false"
                    ></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editCategoryName">Tên thể loại:</label>
                        <input
                            type="text"
                            class="form-control"
                            id="editCategoryName"
                            v-model="editCategoryName"
                        />
                    </div>
                    <div class="form-group">
                        <label for="editCategorySlug" class="form-label"
                            >Slug:</label
                        >
                        <input
                            type="text"
                            class="form-control"
                            id="editCategorySlug"
                            v-model="editCategorySlug"
                        />
                    </div>
                    <div class="form-group">
                        <label for="editParentCategory">Thể loại cha:</label>
                        <select
                            v-model="editSelectedParent"
                            class="form-control"
                        >
                            <option value="" disabled>
                                Vui lòng chọn
                            </option>
                            <template v-for="category in categories">
                                <optgroup :label="category.name">
                                    <option
                                        v-for="child in category.children"
                                        :value="child.id"
                                    >
                                        {{ child.name }}
                                    </option>
                                </optgroup>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="showEditCategoryModal = false"
                    >
                        Đóng
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="editCategory"
                    >
                        Lưu thay đổi
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- modal for delete category -->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCategoryModalLabel">Xóa Thể Loại</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa thể loại này không? Việc này không thể hoàn tác.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-danger" @click="deleteCategory">Xóa</button>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import slugify from "slugify";

const newCategoryName = ref("");
const newCategorySlug = ref("");
const selectedParent = ref("");
const categories = ref([]);
const allCategories = ref([]);

const showAddCategoryModal = ref(false);

const options = {
  responsive: true,
  select: true,
};
onMounted(() => {
    fetchData();
});

async function fetchData() {
    try {
        const response = await axios.get("/api/admin/categories");
        categories.value = response.data.data;
        allCategories.value = response.data.all;
    } catch (error) {
        console.error("Error fetching categories:", error);
    }
}

const addCategory = async () => {
    try {
        if (!newCategoryName.value || !newCategorySlug.value) {
            alert("Category name and slug are required.");
            return;
        }
        const response = await axios.post("/api/admin/categories", {
            name: newCategoryName.value,
            slug: newCategorySlug.value,
        });
        categories.value.push(response.data);
        alert("Tạo thành công.");
        showAddCategoryModal.value = false;

        fetchData();
    } catch (error) {
        console.error("Error adding category:", error);
        alert("Lỗi trong quá trình tạo.");
    }
};
function generateSlug() {
    const slug = slugify(newCategoryName.value, { lower: true });
    newCategorySlug.value = slug;
}

</script>
