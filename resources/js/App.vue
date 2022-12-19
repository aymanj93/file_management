<template>
    <Loading v-if="loadPage"/>
    <div v-else>
        <FilterComponent :create-folder="createFolder"/>
        <div class="d-flex justify-content-center px-5">
            <div class="folder-container">
                <FolderComponent
                    :toggle-folder="toggleFolder"
                    :show-menu="showMenu"
                    :folders="this.folders"
                    :add-sub-folder="addSubFolder"
                />
            </div>
            <PreviewComponent
                :load-preview="loadPreview"
                :folder="selectedFolder"
                :upload-file="uploadFile"
            />
        </div>
    </div>

    <Modal @save="createFolderApi(folderModalController)"
           :title="folderModal.title"
           v-model="folderModalController.show"
           :disabled="false">
        <input v-on:keyup.enter="createFolderApi(folderModalController)" v-model="folderModal.name" type="text" class="form-control text-center" placeholder="Folder name" maxlength="100">
        <p v-if="folderModalController.errors" class="fw-bold text-danger mt-1">Attachment name is required</p>
    </Modal>

    <Modal @save="uploadFileApi(uploadFileController)"
           title="Upload File"
           v-model="uploadFileController.show"
           :disabled="uploadFileController.maxSize">
        <div class="form-group">
            <label class="form-label">File Name</label>
            <input v-model="uploadFileController.name" type="text" class="form-control text-start" placeholder="File Name" maxlength="100">
            <p v-if="uploadFileController.errors" class="fw-bold text-danger mt-1">Folder name is required</p>
        </div>

        <div class="form-group mt-3">
            <label class="form-label w-100">Upload File</label>
            <input type="file" @change="handleFileUpload()" ref="file">
            <p class="text-muted mt-1 mb-0"><small>*File size must not exceed 5mb</small></p>
        </div>

        <p v-if="uploadFileController.maxSize" class="fw-bold text-danger mt-1">Opps, this file is too big. Please choose a different file.</p>
    </Modal>
</template>

<script>
import FilterComponent from "./components/FilterComponent.vue";
import FolderComponent from "./components/FolderComponent.vue";
import PreviewComponent from "./components/PreviewComponent.vue";
import Modal from "./elements/Modal.vue"
import $api from "./api";

export default {
    components: {
        FilterComponent,
        FolderComponent,
        PreviewComponent,
        Modal,
    },
    data() {
        return {
            uploadFileController: {
                show: false,
                id: null,
                name: '',
                file: null,
                maxSize: false,
                errors: null,
            },
            folderModalController: {
                type: '',
                show: false,
                node: {},
                errors: null,
            },
            selectedFolder: {},
            loadPage: true,
            loadPreview: false,
            folderModal: {
                id: null,
                name: '',
            },
            folders: [],
        }
    },
    validations() {
        return {
            folderModal: {
                name: { required },
            },
        }
    },
    methods: {
        createFolder (){
            this.folderModal.title = 'Add new folder';
            this.folderModal.name = '';

            this.hideAllMenu(this.folders);
            this.folderModalController.type = 'folder';
            this.folderModalController.node = this.folders;
            this.folderModalController.show = true;
            this.folderModalController.errors = null;
        },
        addSubFolder (node){
            this.folderModal.title = 'Add sub folder';
            this.folderModal.name = '';

            this.hideAllMenu(this.folders);
            this.folderModalController.type = 'child';
            this.folderModalController.node = node;
            this.folderModalController.show = true;
            this.folderModalController.errors = null;
        },
        uploadFile(id) {
            this.uploadFileController.id = id;
            this.uploadFileController.name = '';
            this.$refs.file.value = null;
            this.uploadFileController.show = true;

            this.uploadFileController.maxSize = false;
            this.uploadFileController.errors = null;
        },
        showMenu(node) {
            this.toggleMenu(this.folders, node);
        },
        toggleMenu(tree, node) {
            tree.forEach((branch) => {
                if (node === branch) {
                  branch.showMenu = !branch.showMenu;
                } else {
                    branch.showMenu = false;
                }
                if (branch.children.length > 0) {
                    this.toggleMenu(branch.children, node);
                }
            });
        },
        hideAllMenu(tree) {
            tree.forEach((branch) => {
                branch.showMenu = false;
                if (branch.children.length > 0) {
                    this.hideAllMenu(branch.children);
                }
            });
        },
        handleFileUpload() {
            const file = this.$refs.file.files[0];

            if (file.size > 5242880) {
                this.uploadFileController.maxSize = true;
            } else {
                this.uploadFileController.maxSize = false;
                this.uploadFileController.file = this.$refs.file.files[0];
            }
        },
        async toggleFolder (node) {
            this.loadPreview = true;
            this.hideAllMenu(this.folders);

            if (node.showFolder) {
                node.showFolder = false;
                this.selectedFolder = node;
                this.loadPreview = false;
            } else {
                await $api.get('/api/folder/' + node.id)
                    .then(response => {
                        node['attachment'] = response.data.data;
                        this.selectedFolder = node;
                        this.loadPreview = false;
                        node.showFolder = true;
                    });
            }
        },
        async getFolderApi() {
            await $api.get('/api/folder')
                .then(response => {
                    this.folders = response.data.data;
                    this.loadPage = false;
                });
        },
        async createFolderApi(controller) {
            if (controller.type === 'folder') {
                let formData = new FormData();
                formData.append('name', this.folderModal.name);
                await $api.post('/api/folder', formData)
                    .then(response => {
                        controller.node.push({
                            id: response.data.id,
                            name: response.data.name,
                            showFolder: false,
                            showMenu: false,
                            children: [],
                            attachment: []
                        });

                        controller.node.showFolder = true;
                        controller.show = false;
                    }).catch(err => {
                        controller.errors =  err.response.data.message;
                    });
            }
            if (controller.type === 'child') {
                let folder_id = this.folderModalController.node.id;
                let formData = new FormData();
                formData.append('name', this.folderModal.name);

                await $api.post('/api/folder/' + folder_id + '/createFolder', formData)
                    .then(response => {
                        controller.node.children.push({
                            id: response.data.id,
                            name: response.data.name,
                            showFolder: false,
                            showMenu: false,
                            children: [],
                            attachment: []
                        });

                        controller.node.showFolder = true;
                        controller.show = false;

                    }).catch(err => {
                        controller.errors =  err.response.data.message;
                    });
            }
        },
        async uploadFileApi(controller) {
            let formData = new FormData();
            formData.append('name', controller.name);
            if (controller.file !== undefined) {
                formData.append('file', controller.file);
            }

            await $api.post('/api/folder/' + controller.id, formData,
            ).then(response => {

                this.selectedFolder.attachment.push(response.data.data);
                controller.show = false;

            }).catch(err => {
                controller.errors = err.response.data.errors;
            });

        }
    },
    created() {
        this.getFolderApi();
    },
}
</script>
