<template>
    <div class="container-fuild">
        <div class="row m-2">
            <div class="col-12 d-flex justify-content-between">
                <h4>Trang slider</h4>
                <button
                    class="btn btn-primary"
                    @click="showAddslidersModal = true"
                >
                    Thêm slider
                </button>
            </div>
        </div>

        <div class="row m-2">
            <div class="col-12">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Tìm kiếm slider..."
                    v-model="searchQuery"
                    @input="fetchData"
                />
            </div>
            <div class="col-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tên</th>
                            <th>values</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="sliders in slider">
                            <tr>
                                <td>{{ sliders.name }}</td>
                                <td>{{ sliders.description }}</td>
                                <td>
                                        <img
                                            :src="getImageUrl(sliders.image)"
                                            alt="slider Image"
                                            class="img-fluid"
                                            style="max-width: 100px"
                                        />
                                </td>
                                <td>
                                    <button
                                        class="btn btn-info"
                                        @click="prepareEditsliders(sliders)"
                                    >
                                        Sửa
                                    </button>
                                    <button
                                        class="btn btn-danger"
                                        @click="prepareDeletesliders(sliders)"
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
            :class="{ show: showAddslidersModal }"
            style="display: block"
            v-if="showAddslidersModal"
            @click.self="showEditModal = false"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm slider</h5>
                        <button
                            type="button"
                            class="btn-close"
                            aria-label="Close"
                            @click="showAddslidersModal = false"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="slidername">name: </label>
                            <input
                                type="text"
                                id="slidername"
                                v-model="currentslider.name"
                                class="form-control"
                            />
                        </div>
                        <div class="form-group">
                            <label for="sliderdescription">description: </label>
                            <input
                                type="text"
                                id="sliderdescription"
                                v-model="currentslider.description"
                                class="form-control"
                            />
                        </div>
                        <div class="form-group">
                            <label for="sliderImage">Value</label>
                            <input
                                type="file"
                                id="sliderImage"
                                @change="handleImageUpload"
                                class="form-control-file"
                            />
                            <img
                                v-if="previewImage"
                                :src="previewImage"
                                alt="Preview Image"
                                class="img-thumbnail mt-2"
                                style="width: 200px"
                            />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click="showAddslidersModal = false"
                        >
                            Đóng
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="addsliders"
                        >
                            Thêm
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for edit sliders -->
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
                        <h5 class="modal-title">Chỉnh sửa slider</h5>
                        <button
                            type="button"
                            class="btn-close"
                            aria-label="Close"
                            @click="showEditModal = false"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editslidersName">name:</label>
                            <input
                                type="text"
                                class="form-control"
                                id="editslidersName"
                                v-model="currentslider.name"
                            />
                        </div>
                        <div class="form-group">
                            <label for="editsliderType">description</label>
                            <input
                                type="text"
                                class="form-control"
                                id="editslidersdescription"
                                v-model="currentslider.description"
                            />
                        </div>
                        <div
                            class="form-group mt-2"
                        >
                            <img
                                v-if="previewImage"
                                :src="previewImage"
                                class="img-fluid"
                                style="max-width: 200px"
                            />
                            <input
                                type="file"
                                id="editsliderImage"
                                @change="handleImageUpload"
                                class="form-control-file mt-2"
                            />
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
                            @click="updatesliders"
                        >
                            Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for delete sliders -->
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
                        <h5 class="modal-title" id="deleteslidersModalLabel">
                            Xóa slider
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
                        Bạn có chắc chắn muốn xóa slider này không? Việc này
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
                            @click="deletesliders"
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

const slider = ref([]);
const allslider = ref([]);
const currentsliders = ref(null);
const newSliderImage = ref(null);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const currentPage = ref(1);
const totalPages = ref(0);
const searchQuery = ref("");
const currentslider = ref({id: "", name: "", description: "", image: null });
const previewImage = ref("");
let editslidersImage = ref(null);
const showAddslidersModal = ref(false);
function prepareEditsliders(sliders) {
    currentslider.value = {
        id: sliders.id,
        name: sliders.name,
        description: sliders.description,
        image: sliders.image,
    };
    showEditModal.value = true;

    previewImage.value = getImageUrl(sliders.image);
}

function prepareDeletesliders(sliders) {
    currentsliders.value = sliders;
    showDeleteModal.value = true;
}
function getImageUrl(relativePath) {
    return `http://localhost:8000/${relativePath}`;
}
const options = {
    responsive: true,
    select: true,
};
onMounted(() => {
    fetchData();
});
function handleImageUpload(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onloadend = () => {
            previewImage.value = reader.result;
        };
        reader.readAsDataURL(file);
        if (currentslider.value.id) {
            editslidersImage.value = file;
        } else {
            newSliderImage.value = file; 
        }
    }
}
async function fetchData() {
    try {
        const response = await axios.get("/api/sliders", {
            params: {
                page: currentPage.value,
                search: searchQuery.value,
            },
        });
        console.log(response);
        slider.value = response.data.data.data;
        allslider.value = response.data.all;
        totalPages.value = response.data.last_page;
    } catch (error) {
        console.error("Error fetching slider:", error);
    }
}
function changePage(page) {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        fetchData();
    }
}

const addsliders = async () => {
    const formData = new FormData();
    formData.append("name", currentslider.value.name);
    formData.append("description", currentslider.value.description);

    if (newSliderImage.value) {
        formData.append("image", newSliderImage.value);
    }
    
    try {
        const response = await axios.post("/api/sliders", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        slider.value.push(response.data);
        alert("Tạo thành công.");
        showAddslidersModal.value = false;

        fetchData();
    } catch (error) {
        console.error("Error adding sliders:", error);
        alert("Lỗi trong quá trình tạo.");
    }
};
const updatesliders = async () => {
    const formData = new FormData();
    formData.append("name", currentslider.value.name);
    formData.append("description", currentslider.value.description);

    if (editslidersImage.value) {
        formData.append("image", editslidersImage.value);
    }
    try {
        await axios.post(`/api/sliders/${currentslider.value.id}`, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        alert("Cập nhật slider thành công!");
        showEditModal.value = false;
        editslidersImage.value = null;
        fetchData();
    } catch (error) {
        console.error("Lỗi khi cập nhật slider:", error);
        alert("Có lỗi xảy ra trong quá trình cập nhật slider.");
    }
};

const deletesliders = async () => {
    if (!currentsliders.value) {
        alert("Không có slider được chọn để xóa.");
        return;
    }

    const confirmDelete = confirm(
        "Bạn có chắc chắn muốn xóa slider này không? Việc này không thể hoàn tác."
    );
    if (!confirmDelete) {
        return;
    }

    try {
        await axios.delete(`/api/sliders/${currentsliders.value.id}`);
        alert("slider đã được xóa thành công.");
        showDeleteModal.value = false;
        fetchData();
    } catch (error) {
        console.error("Lỗi khi xóa slider: ", error);
        alert("Có lỗi xảy ra trong quá trình xóa slider.");
    }
};
</script>
