<template>
    <div class="container-fuild">
        <div class="row m-2">
            <div class="col-12 d-flex justify-content-between">
                <h4>Trang Setting</h4>
                <button
                    class="btn btn-primary"
                    @click="showAddsettingsModal = true"
                >
                    Thêm Setting
                </button>
            </div>
        </div>

        <div class="row m-2">
            <div class="col-12">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Tìm kiếm Setting..."
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
                        <template v-for="settings in setting">
                            <tr>
                                <td>{{ settings.key }}</td>
                                <td>
                                    <div v-if="settings.type === 'images'">
                                        <img
                                            :src="getImageUrl(settings.value)"
                                            alt="Setting Image"
                                            class="img-fluid"
                                            style="max-width: 100px"
                                        />
                                    </div>
                                    <div v-else>
                                        {{ settings.value }}
                                    </div>
                                </td>
                                <td>
                                    <button
                                        class="btn btn-info"
                                        @click="prepareEditsettings(settings)"
                                    >
                                        Sửa
                                    </button>
                                    <button
                                        class="btn btn-danger"
                                        @click="prepareDeletesettings(settings)"
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
            :class="{ show: showAddsettingsModal }"
            style="display: block"
            v-if="showAddsettingsModal"
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
                            @click="showAddsettingsModal = false"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="settingKey">Key</label>
                            <input
                                type="text"
                                id="settingKey"
                                v-model="currentSetting.key"
                                class="form-control"
                            />
                        </div>
                        <div class="form-group">
                            <label for="settingType">Type</label>
                            <select
                                id="settingType"
                                v-model="currentSetting.type"
                                class="form-control"
                            >
                                <option value="text">Text</option>
                                <option value="images">Images</option>
                            </select>
                        </div>
                        <div
                            class="form-group"
                            v-if="currentSetting.type === 'text'"
                        >
                            <label for="settingValue">Value</label>
                            <input
                                type="text"
                                id="settingValue"
                                v-model="currentSetting.value"
                                class="form-control"
                            />
                        </div>
                        <div class="form-group" v-else>
                            <label for="settingImage">Value</label>
                            <input
                                type="file"
                                id="settingImage"
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
                            @click="showAddsettingsModal = false"
                        >
                            Đóng
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="addsettings"
                        >
                            Thêm
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for edit settings -->
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
                            <label for="editsettingsName">key:</label>
                            <input
                                type="text"
                                class="form-control"
                                id="editsettingsName"
                                v-model="currentSetting.key"
                            />
                        </div>
                        <div class="form-group">
                            <label for="editSettingType">Type</label>
                            <select
                                id="editSettingType"
                                v-model="currentSetting.type"
                                class="form-control"
                            >
                                <option value="text">Text</option>
                                <option value="images">Images</option>
                            </select>
                        </div>
                        <div
                            v-if="currentSetting.type === 'text'"
                            class="form-group"
                        >
                            <label for="editSettingValue">Value</label>
                            <input
                                type="text"
                                id="editSettingValue"
                                v-model="currentSetting.value"
                                class="form-control"
                            />
                        </div>
                        <div
                            v-if="currentSetting.type === 'images'"
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
                                id="editSettingImage"
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
                            @click="updatesettings"
                        >
                            Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for delete settings -->
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
                        <h5 class="modal-title" id="deletesettingsModalLabel">
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
                            @click="deletesettings"
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

const setting = ref([]);
const allsetting = ref([]);
const currentsettings = ref(null);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const editsettingsName = ref("");
const editsettingsValue = ref("");
const editSelectedParent = ref("");
const currentPage = ref(1);
const totalPages = ref(0);
const searchQuery = ref("");
const currentSetting = ref({ key: "", value: "", type: "text" });
const previewImage = ref("");
const showAddsettingsModal = ref(false);
function prepareEditsettings(settings) {
    currentSetting.value = {
        key: settings.key,
        value: settings.type === "text" ? settings.value : "",
        type: settings.type,
    };
    showEditModal.value = true;

    if (settings.type === "images") {
        previewImage.value = getImageUrl(settings.value);
    } else {
        previewImage.value = "";
    }
}

function prepareDeletesettings(settings) {
    currentsettings.value = settings;
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
        currentSetting.value.value = file; // You might need to adjust this according to your API
    }
}

async function fetchData() {
    try {
        const response = await axios.get("/api/settings", {
            params: {
                page: currentPage.value,
                search: searchQuery.value,
            },
        });
        console.log(response);
        setting.value = response.data.data.data;
        allsetting.value = response.data.all;
        totalPages.value = response.data.last_page;
    } catch (error) {
        console.error("Error fetching setting:", error);
    }
}
function changePage(page) {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        fetchData();
    }
}

const addsettings = async () => {
    const formData = new FormData();
    formData.append("key", currentSetting.value.key);
    formData.append("type", currentSetting.value.type);
    if (
        currentSetting.value.type === "images" &&
        currentSetting.value.value instanceof File
    ) {
        formData.append("value", currentSetting.value.value);
    } else if (currentSetting.value.type === "text") {
        formData.append("value", currentSetting.value.value);
    }
    try {
        const response = await axios.post("/api/settings", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        setting.value.push(response.data);
        alert("Tạo thành công.");
        showAddsettingsModal.value = false;

        fetchData();
    } catch (error) {
        console.error("Error adding settings:", error);
        alert("Lỗi trong quá trình tạo.");
    }
};
const updatesettings = async () => {
    if (!currentsettings.value || !editsettingsName.value) {
        alert("Tên và slug của thể loại là bắt buộc.");
        return;
    }

    try {
        const settingsData = {
            name: editsettingsName.value,
            parent_id:
                editSelectedParent.value === 0 ? 0 : editSelectedParent.value,
        };

        await axios.put(
            `/api/setting/${currentsettings.value.id}`,
            settingsData
        );

        alert("Cập nhật thể loại thành công!");
        showEditModal.value = false;

        fetchData();
    } catch (error) {
        console.error("Lỗi khi cập nhật thể loại:", error);
        alert("Có lỗi xảy ra trong quá trình cập nhật thể loại.");
    }
};
const deletesettings = async () => {
    if (!currentsettings.value) {
        alert("Không có setting được chọn để xóa.");
        return;
    }

    const confirmDelete = confirm(
        "Bạn có chắc chắn muốn xóa setting này không? Việc này không thể hoàn tác."
    );
    if (!confirmDelete) {
        return;
    }

    try {
        await axios.delete(`/api/settings/${currentsettings.value.id}`);
        alert("Thể loại đã được xóa thành công.");
        showDeleteModal.value = false;
        fetchData();
    } catch (error) {
        console.error("Lỗi khi xóa thể loại: ", error);
        alert("Có lỗi xảy ra trong quá trình xóa thể loại.");
    }
};
</script>
