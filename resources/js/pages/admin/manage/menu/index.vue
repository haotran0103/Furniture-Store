<template>
    <div class="container-fuild">
        <div class="row m-2">
            <div class="col-12 d-flex justify-content-between">
                <h4>Trang menus</h4>
                <button
                    class="btn btn-primary"
                    @click="showAddmenuModal = true"
                >
                    Thêm menus
                </button>
            </div>
        </div>

        <div class="row m-2">
            <div class="col-12">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Tìm kiếm menus..."
                    v-model="searchQuery"
                    @input="fetchData"
                />
            </div>
            <div class="col-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tên</th>
                            <th>menus cha</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="menu in menus">
                            <tr>
                                <td>{{ menu.name }}</td>
                                <td>
                                    {{
                                        menu.parent
                                            ? menu.parent.name
                                            : "Không có"
                                    }}
                                </td>
                                <td>
                                    <button
                                        class="btn btn-info"
                                        @click="prepareEditmenu(menu)"
                                    >
                                        Sửa
                                    </button>
                                    <button
                                        class="btn btn-danger"
                                        @click="prepareDeletemenu(menu)"
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
            :class="{ show: showAddmenuModal }"
            style="display: block"
            v-if="showAddmenuModal"
            @click.self="showEditModal = false"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm menus</h5>
                        <button
                            type="button"
                            class="btn-close"
                            aria-label="Close"
                            @click="showAddmenuModal = false"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="menuType">Loại Menu:</label>
                            <select
                                v-model="menuType"
                                class="form-control"
                                id="menuType"
                            >
                                <option value="normal">Bình Thường</option>
                                <option value="category">Danh Mục</option>
                            </select>
                        </div>

                        <div class="form-group" v-if="menuType === 'normal'">
                            <label for="menuName">Tên Menu:</label>
                            <input
                                type="text"
                                class="form-control"
                                id="menuName"
                                v-model="menuName"
                            />
                        </div>

                        <div class="form-group" v-else>
                            <label for="category">Chọn Danh Mục:</label>
                            <select
                                v-model="selectedCategoryId"
                                class="form-control"
                                id="category"
                            >
                                <option
                                    v-for="category in categories"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click="showAddmenuModal = false"
                        >
                            Đóng
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="addmenu"
                        >
                            Thêm
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for edit menu -->
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
                        <h5 class="modal-title">Chỉnh sửa menus</h5>
                        <button
                            type="button"
                            class="btn-close"
                            aria-label="Close"
                            @click="showEditModal = false"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="menuType">Loại Menu:</label>
                            <select
                                v-model="menuType"
                                class="form-control"
                                id="menuType"
                            >
                                <option value="normal">Bình Thường</option>
                                <option value="category">Danh Mục</option>
                            </select>
                        </div>

                        <div class="form-group" v-if="menuType === 'normal'">
                            <label for="menuName">Tên Menu:</label>
                            <input
                                type="text"
                                class="form-control"
                                id="menuName"
                                v-model="menuName"
                            />
                        </div>

                        <div class="form-group" v-else>
                            <label for="category">Chọn Danh Mục:</label>
                            <select
                                v-model="selectedCategoryId"
                                class="form-control"
                                id="category"
                            >
                                <option
                                    v-for="category in categories"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
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
                            @click="updatemenu"
                        >
                            Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for delete menu -->
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
                        <h5 class="modal-title" id="deletemenuModalLabel">
                            Xóa menus
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
                        Bạn có chắc chắn muốn xóa menus này không? Việc này
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
                            @click="deletemenu"
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

const newmenuName = ref("");
const selectedParent = ref("");
const menus = ref([]);
const allmenus = ref([]);
const currentmenu = ref(null);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const editmenuName = ref("");
const editmenuSlug = ref("");
const editSelectedParent = ref("");
const currentPage = ref(1);
const totalPages = ref(0);
const searchQuery = ref("");
const menuType = ref("normal"); // 'normal' hoặc 'category'
const menuName = ref("");
const selectedCategoryId = ref(null);
const categories = ref([]);

const showAddmenuModal = ref(false);
function prepareEditmenu(menu) {
    currentmenu.value = menu;
    showEditModal.value = true;
    menuType.value = menu.type;
    if (menu.type === "category") {
        const category = categories.value.find(
            (cat) => cat.id.toString() === menu.category.toString()
        );
        if (category) {
            selectedCategoryId.value = category.id;
            editmenuName.value = category.name;
        } else {
            selectedCategoryId.value = null;
            editmenuName.value = "";
        }
    } else {
        editmenuName.value = menu.name;
        selectedCategoryId.value = null;
    }
    editSelectedParent.value = menu.parent_id;
}

function prepareDeletemenu(menu) {
    currentmenu.value = menu;
    showDeleteModal.value = true;
}
function resetForm() {
    newmenuName.value = "";
    selectedParent.value = "";
    menuType.value = "normal";
    menuName.value = "";
    selectedCategoryId.value = null;
}

onMounted(() => {
    fetchData();
    fetchCategories();
});
async function fetchCategories() {
    const response = await axios.get("/api/admin/categories");
    categories.value = response.data.data.data;
}
async function fetchData() {
    try {
        const response = await axios.get("/api/menu", {
            params: {
                page: currentPage.value,
                search: searchQuery.value,
            },
        });
        menus.value = response.data.data.data;
        allmenus.value = response.data.all;
        totalPages.value = response.data.last_page;
    } catch (error) {
        console.error("Error fetching menus:", error);
    }
}
function changePage(page) {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        fetchData();
    }
}

const addmenu = async () => {
    let menuNameValue =
        menuType.value === "category" && selectedCategoryId.value
            ? categories.value.find(
                  (cat) => cat.id === selectedCategoryId.value
              ).name
            : menuName.value;
    try {
        let payload = {
            name: menuNameValue,
            parent_id: selectedParent.value || 0,
            type: menuType.value,
            category:
                menuType.value === "category" ? selectedCategoryId.value : null,
        };

        const response = await axios.post("/api/menu", payload);
        menus.value.push(response.data);
        alert("Tạo thành công.");
        showAddmenuModal.value = false;
        resetForm();
        fetchData();
    } catch (error) {
        console.error("Error adding menu:", error);
        alert("Lỗi trong quá trình tạo.");
    }
};
const updatemenu = async () => {
    try {
        let payload = {
            name: editmenuName.value,
            parent_id:
                editSelectedParent.value === 0 ? 0 : editSelectedParent.value,
            type: menuType.value,
            category:
                menuType.value === "category" ? selectedCategoryId.value : null,
        };
        await axios.post(`/api/menu/${currentmenu.value.id}`, payload);

        alert("Cập nhật menus thành công!");
        showEditModal.value = false;
        resetForm();
        fetchData();
    } catch (error) {
        console.error("Lỗi khi cập nhật menus:", error);
        alert("Có lỗi xảy ra trong quá trình cập nhật menus.");
    }
};
const deletemenu = async () => {
    if (!currentmenu.value) {
        alert("Không có menus được chọn để xóa.");
        return;
    }

    const confirmDelete = confirm(
        "Bạn có chắc chắn muốn xóa menus này không? Việc này không thể hoàn tác."
    );
    if (!confirmDelete) {
        return;
    }

    try {
        await axios.delete(`/api/menu/${currentmenu.value.id}`);
        alert("menus đã được xóa thành công.");
        showDeleteModal.value = false;
        fetchData();
    } catch (error) {
        console.error("Lỗi khi xóa menus: ", error);
        alert("Có lỗi xảy ra trong quá trình xóa menus.");
    }
};
</script>
