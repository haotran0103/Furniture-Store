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
                <input
                    type="text"
                    class="form-control"
                    placeholder="Tìm kiếm thể loại..."
                    v-model="searchQuery"
                    @input="fetchData"
                />
            </div>
            <div class="col-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tên</th>
                            <th>Slug</th>
                            <th>Thể loại cha</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="category in categories">
                            <tr>
                                <td>{{ category.name }}</td>
                                <td>{{ category.slug }}</td>
                                <td>
                                    {{
                                        category.parent
                                            ? category.parent.name
                                            : "Không có"
                                    }}
                                </td>
                                <td>
                                    <button
                                        class="btn btn-info"
                                        @click="prepareEditCategory(category)"
                                    >
                                        Sửa
                                    </button>
                                    <button
                                        class="btn btn-danger"
                                        @click="prepareDeleteCategory(category)"
                                    >
                                        Xóa
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Pagination Buttons -->
        <div class="row m-2">
            <div class="col-12 d-flex justify-content-center">
                <button
                    class="btn btn-info mr-2"
                    @click="changePage(currentPage - 1)"
                    :disabled="currentPage === 1"
                >
                    Trước
                </button>
                <span>Trang {{ currentPage }} / {{ totalPages }}</span>
                <button
                    class="btn btn-info ml-2"
                    @click="changePage(currentPage + 1)"
                    :disabled="currentPage === totalPages"
                >
                    Sau
                </button>
            </div>
        </div>
        <!-- add modal -->
        <div
            class="modal"
            tabindex="-1"
            role="dialog"
            :class="{ show: showAddCategoryModal }"
            style="display: block"
            v-if="showAddCategoryModal"
            @click.self="showEditModal = false"
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
            :class="{ show: showEditModal }"
            style="display: block"
            v-if="showEditModal"
            @click.self="showEditModal = false"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa thể loại</h5>
                        <button
                            type="button"
                            class="btn-close"
                            aria-label="Close"
                            @click="showEditModal = false"
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
                                @input="
                                    editCategorySlug.value = slugify(
                                        editCategoryName.value,
                                        { lower: true, strict: true }
                                    )
                                "
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
                            <label for="editParentCategory"
                                >Thể loại cha:</label
                            >
                            <select
                                class="form-control"
                                v-model="editSelectedParent"
                            >
                                <option value="0">Chọn thể loại</option>
                                <template v-for="parentCategory in categories">
                                    <option :value="parentCategory.id">
                                        {{ parentCategory.name }}
                                    </option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click="showEditModal = false"
                        >
                            Đóng
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="updateCategory"
                        >
                            Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for delete category -->
        <div
            class="modal"
            tabindex="-1"
            role="dialog"
            :class="{ show: showDeleteModal }"
            style="display: block"
            v-if="showDeleteModal"
            @click.self="showDeleteModal = false"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteCategoryModalLabel">
                            Xóa Thể Loại
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                            @click="showDeleteModal = false"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc chắn muốn xóa thể loại này không? Việc này
                        không thể hoàn tác.
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click="showDeleteModal = false"
                        >
                            Đóng
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger"
                            @click="deleteCategory"
                        >
                            Xóa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import { watch } from "vue";

const newCategoryName = ref("");
const newCategorySlug = ref("");
const selectedParent = ref("");
const categories = ref([]);
const allCategories = ref([]);
const currentCategory = ref(null);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const editCategoryName = ref("");
const editCategorySlug = ref("");
const editSelectedParent = ref("");
const currentPage = ref(1);
const totalPages = ref(0);
const searchQuery = ref("");

const showAddCategoryModal = ref(false);
function prepareEditCategory(category) {
    currentCategory.value = category;
    showEditModal.value = true;
    editCategoryName.value = category.name;
    editCategorySlug.value = category.slug;
    editSelectedParent.value = category.parent ? category.parent.id : 0;
}

function prepareDeleteCategory(category) {
    currentCategory.value = category;
    showDeleteModal.value = true;
}

const options = {
    responsive: true,
    select: true,
};
onMounted(() => {
    fetchData();
});

async function fetchData() {
    try {
        const response = await axios.get("/api/admin/categories", {
            params: {
                page: currentPage.value,
                search: searchQuery.value,
            },
        });

        categories.value = response.data.data.data;
        allCategories.value = response.data.all;
        totalPages.value = response.data.last_page;
    } catch (error) {
        console.error("Error fetching categories:", error);
    }
}
function changePage(page) {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        fetchData();
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
            parent_id: selectedParent.value,
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
const updateCategory = async () => {
    if (
        !currentCategory.value ||
        !editCategoryName.value ||
        !editCategorySlug.value
    ) {
        alert("Tên và slug của thể loại là bắt buộc.");
        return;
    }

    try {
        const categoryData = {
            name: editCategoryName.value,
            slug: editCategorySlug.value,
            parent_id:
                editSelectedParent.value === 0 ? 0 : editSelectedParent.value,
        };

        await axios.put(
            `/api/categories/${currentCategory.value.id}`,
            categoryData
        );

        alert("Cập nhật thể loại thành công!");
        showEditModal.value = false;

        fetchData();
    } catch (error) {
        console.error("Lỗi khi cập nhật thể loại:", error);
        alert("Có lỗi xảy ra trong quá trình cập nhật thể loại.");
    }
};
const deleteCategory = async () => {
    if (!currentCategory.value) {
        alert("Không có thể loại được chọn để xóa.");
        return;
    }

    const confirmDelete = confirm(
        "Bạn có chắc chắn muốn xóa thể loại này không? Việc này không thể hoàn tác."
    );
    if (!confirmDelete) {
        return;
    }

    try {
        await axios.delete(`/api/categories/${currentCategory.value.id}`);
        alert("Thể loại đã được xóa thành công.");
        showDeleteModal.value = false; // Đóng modal xóa
        fetchData(); // Gọi lại hàm để cập nhật danh sách thể loại
    } catch (error) {
        console.error("Lỗi khi xóa thể loại: ", error);
        alert("Có lỗi xảy ra trong quá trình xóa thể loại.");
    }
};

function slugify(text) {
    const from =
        "àáãảạăằắẵẳặâầấẫẩậèéẽẻẹêềếễểệìíĩỉịòóõỏọôồốỗổộơờớỡởợùúũủụưừứữửựỳýỹỷỵđÀÁÃẢẠĂẰẮẴẲẶÂẦẤẪẨẬÈÉẼẺẸÊỀẾỄỂỆÌÍĨỈỊÒÓÕỎỌÔỒỐỖỔỘƠỜỚỠỞỢÙÚŨỦỤƯỪỨỮỬỰỲÝỸỶỴĐ";
    const to =
        "aaaaaaaaaaaaaaaaaeeeeeeeeeeeiiiiiooooooooooooooooouuuuuuuuuuuyyyyydAAAAAAAAAAAAAAAAAEEEEEEEEEEEIIIIIOOOOOOOOOOOOOOOOOOOUUUUUUUUUUUYYYYYD";
    let updatedText = text.toString().toLowerCase().trim();

    for (let i = 0; i < from.length; i++) {
        updatedText = updatedText.replace(new RegExp(from[i], "g"), to[i]);
    }

    return updatedText
        .replace(/\s+/g, "-")
        .replace(/[^\w\-]+/g, "")
        .replace(/\-\-+/g, "-");
}

function generateSlug() {
    const slug = slugify(newCategoryName.value, { lower: true });
    newCategorySlug.value = slug;
}
watch(editCategoryName, (newValue) => {
    editCategorySlug.value = slugify(newValue, { lower: true });
});
</script>
