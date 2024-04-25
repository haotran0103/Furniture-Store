<template>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Quản lý Tag</h2>
            <button class="btn btn-primary" @click="showAddTagModal = true">
                Thêm Tag
            </button>
        </div>

        <input
            type="text"
            class="form-control mb-3"
            placeholder="Tìm kiếm tag..."
            v-model="searchQuery"
            @input="fetchTags"
        />
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên Tag</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(tag, index) in tags" :key="tag.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ tag.name }}</td>
                    <td>
                        <button
                            class="btn btn-info"
                            @click="prepareEditTag(tag)"
                        >
                            Sửa
                        </button>
                        <button
                            class="btn btn-danger"
                            @click="prepareDeleteTag(tag.id)"
                        >
                            Xóa
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Pagination Controls -->
        <div class="row m-2">
            <div class="col-12 d-flex justify-content-center">
                <button
                    class="btn btn-info mr-2"
                    @click="changePage(currentPage - 1)"
                    :disabled="currentPage === 1"
                >
                    Prev
                </button>
                <span>Page {{ currentPage }} / {{ totalPages }}</span>
                <button
                    class="btn btn-info ml-2"
                    @click="changePage(currentPage + 1)"
                    :disabled="currentPage === totalPages"
                >
                    Next
                </button>
            </div>
        </div>

        <!-- Modal Thêm Tag -->
        <div
            v-if="showAddTagModal"
            class="modal fade show"
            style="display: block"
            aria-modal="true"
            role="dialog"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm Tag Mới</h5>
                        <button
                            type="button"
                            class="close"
                            @click="showAddTagModal = false"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="addTag">
                            <div class="form-group">
                                <label for="tagName">Tên Tag</label>
                                <input
                                    type="text"
                                    id="tagName"
                                    v-model="newTagName"
                                    class="form-control"
                                    placeholder="Nhập tên tag"
                                    required
                                />
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">
                                Thêm
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="showEditTagModal"
            class="modal fade show"
            style="display: block"
            aria-modal="true"
            role="dialog"
        >
            <!-- Nội dung modal sửa -->
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa Tag</h5>
                        <button
                            type="button"
                            class="close"
                            @click="showEditTagModal = false"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="editTag">
                            <div class="form-group">
                                <label for="editTagName">Tên Tag</label>
                                <input
                                    type="text"
                                    id="editTagName"
                                    v-model="selectedTag.name"
                                    class="form-control"
                                    required
                                />
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">
                                Lưu Thay Đổi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="showDeleteTagModal"
            class="modal fade show"
            style="display: block"
            aria-modal="true"
            role="dialog"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Xác nhận xóa Tag</h5>
                        <button
                            type="button"
                            class="close"
                            @click="showDeleteTagModal = false"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc chắn muốn xóa tag này không?
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click="showDeleteTagModal = false"
                        >
                            Hủy bỏ
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger"
                            @click="deleteTag"
                        >
                            Xóa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import { watch } from "vue";
export default {
    data() {
        return {
            tags: [],
            searchQuery: "",
            showAddTagModal: false,
            newTagName: "",
            selectedTag: null,
            showEditTagModal: false,
            showDeleteTagModal: false,
            selectedTagId: null,
            currentPage: 1,
            pageSize: 10,
            totalTags: 0,
        };
    },
    methods: {
        addTag() {
            if (!this.newTagName.trim()) {
                alert("Tên tag không được để trống!");
                return;
            }
            axios
                .post("/api/admin/tags", { name: this.newTagName })
                .then((response) => {
                    alert("Thêm tag thành công!");
                    this.showAddTagModal = false;
                    this.newTagName = "";
                    this.fetchTags();
                })
                .catch((error) => {
                    console.error("Có lỗi xảy ra:", error);
                    alert("Không thể thêm tag. Vui lòng thử lại.");
                });
        },
        fetchTags() {
            axios
                .get("/api/admin/tags", {
                    params: {
                        page: this.currentPage,
                        pageSize: this.pageSize,
                        search: this.searchQuery,
                    },
                })
                .then((response) => {
                    this.tags = response.data.data;
                    this.totalTags = response.data.total;
                })
                .catch((error) => {
                    console.error("Error fetching tags:", error);
                });
        },

        prepareEditTag(tag) {
            this.selectedTag = Object.assign({}, tag); // Sao chép dữ liệu tag để tránh thay đổi trực tiếp
            this.showEditTagModal = true;
        },
        editTag() {
            axios
                .put(`/api/tags/${this.selectedTag.id}`, this.selectedTag)
                .then((response) => {
                    alert("Cập nhật tag thành công!");
                    this.showEditTagModal = false;
                    this.fetchTags(); // Tải lại danh sách tags
                })
                .catch((error) => {
                    console.error("Có lỗi xảy ra:", error);
                    alert("Không thể cập nhật tag. Vui lòng thử lại.");
                });
        },
        prepareDeleteTag(tagId) {
            this.selectedTagId = tagId;
            this.showDeleteTagModal = true;
        },
        deleteTag() {
            axios
                .delete(`/api/tags/${this.selectedTagId}`)
                .then((response) => {
                    alert("Tag đã được xóa thành công.");
                    this.showDeleteTagModal = false; // Đóng modal
                    this.fetchTags(); // Tải lại danh sách tags
                })
                .catch((error) => {
                    console.error("Có lỗi xảy ra khi xóa tag:", error);
                    alert("Không thể xóa tag. Vui lòng thử lại.");
                });
        },
        changePage(page) {
            if (page >= 1 && page <= totalPages.value) {
                currentPage.value = page;
                fetchData();
            }
        },
    },
    computed: {
        totalPages() {
            return Math.ceil(this.totalTags / this.pageSize);
        },
    },

    mounted() {
        this.fetchTags();
    },
};
</script>
