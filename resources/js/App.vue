<template>
    <Loading v-if="loadPage"/>
    <div v-else>
        <FilterComponent :create-folder="addFolder"/>
        <div class="d-flex justify-content-center px-5">
            <div class="folder-container">
                <FolderComponent
                    :preview="previewFilesApi"
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

    <Modal @save="addFolderApi(addFolderController)"
           :title="addFolderController.title"
           v-model="addFolderController.show"
           :disabled="addFolderController.disabled">
        <input v-on:keyup.enter="addFolderApi(addFolderController)" v-model="inputFolderName" type="text" class="form-control text-center" placeholder="Folder name" maxlength="100">
<!--        <p v-if="addFolderController.errors" class="fw-bold text-danger mt-1">Attachment name is required</p>-->
    </Modal>

    <Modal @save="uploadFileApi(uploadFileController)"
           title="Upload File"
           v-model="uploadFileController.show"
           :disabled="uploadFileController.disabled">
        <div class="form-group">
            <label class="form-label">File Name</label>
            <input v-model="inputFileName" type="text" class="form-control text-start" placeholder="File Name" maxlength="100">
        </div>

        <div class="form-group mt-3">
            <label class="form-label w-100">Upload File</label>
            <input type="file" @change="handleFileUpload()" ref="file">
            <p class="text-muted mt-1 mb-0"><small>*File size must not exceed 5mb</small></p>
        </div>

        <p v-if="uploadFileController.fileEmpty" class="fw-bold text-danger mt-1">Please upload file</p>
        <p v-else-if="uploadFileController.maxSize" class="fw-bold text-danger mt-1">Opps, this file is too big. Please choose a different file.</p>
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
            addFolderController: {
                title: '',
                name: '',
                type: '',
                show: false,
                node: {},
                errors: null,
                disabled: true,
            },
            uploadFileController: {
                show: false,
                id: null,
                name: '',
                file: null,
                maxSize: false,
                fileEmpty: false,
                disabled: true,
                errors: null,
            },
            folders: [],
            selectedFolder: {},
            loadPage: true,
            loadPreview: false,
            inputFileName: '',
            inputFolderName: ''
        }
    },
    created() {
        this.getFolderApi();
    },
    watch: {
        inputFolderName(newValue, oldValue) {
            if (newValue === '' ) {
                this.addFolderController.disabled = true;
            } else {
                this.addFolderController.name = newValue;
                this.addFolderController.disabled = false;
            }
        },
        inputFileName(newValue, oldValue) {
            if (newValue === '' ) {
                this.uploadFileController.disabled = true;
            } else {
                this.uploadFileController.name = newValue;
                this.uploadFileController.disabled = false;
            }
        }
    },
    methods: {
        addFolder(){
            this.hideAllMenu(this.folders);
            this.inputFolderName = '';

            this.addFolderController.title = 'Add new folder';
            this.addFolderController.type = 'folder';
            this.addFolderController.node = this.folders;
            this.addFolderController.show = true;
            this.addFolderController.errors = null;
        },
        addSubFolder (node){
            this.hideAllMenu(this.folders);
            this.inputFolderName = '';

            this.addFolderController.title = 'Add sub folder';
            this.addFolderController.type = 'child';
            this.addFolderController.node = node;
            this.addFolderController.show = true;
            this.addFolderController.errors = null;
        },
        uploadFile(id) {
            this.$refs.file.value = null;

            this.inputFileName = '';
            // console.log('controller: ' + this.uploadFileController.file, '|'+this.$refs.file.files[0])
            this.uploadFileController.id = id;
            this.uploadFileController.name = '';
            this.uploadFileController.file = null;
            this.uploadFileController.show = true;
            this.uploadFileController.maxSize = false;
            this.uploadFileController.fileEmpty = false;
            this.uploadFileController.errors = null;
            this.uploadFileController.disabled = true;
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

            if (file.size > 5542880) {
                this.uploadFileController.maxSize = true;
                this.uploadFileController.disabled = true;
            } else {
                this.uploadFileController.file = this.$refs.file.files[0];
                if (this.uploadFileController.name !== '') {
                    this.uploadFileController.maxSize = false;
                    this.uploadFileController.disabled = false;
                }
            }
        },
        toggleFolder (node) {
            this.hideAllMenu(this.folders);
            node.showFolder = !node.showFolder;

        },
        async previewFilesApi(node){
            if (node.apiCall) {
                this.selectedFolder = node;
            }
            else{
                this.loadPreview = true;
                await $api.get('/api/folder/' + node.id)
                    .then(response => {
                        console.log('show attachments api call once');
                        this.selectedFolder = node;
                        this.loadPreview = false;
                        node['attachment'] = response.data.data;
                        node.apiCall = true;
                        node.showFolder = !node.showFolder;
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
        async addFolderApi(controller) {
            if (controller.type === 'folder') {
                let formData = new FormData();
                formData.append('name', controller.name);

                await $api.post('/api/folder', formData)
                    .then(response => {
                        controller.node.push({
                            id: response.data.id,
                            name: response.data.name,
                            showFolder: false,
                            showMenu: false,
                            apiCall: false,
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
                let folder_id = controller.node.id;
                let formData = new FormData();
                formData.append('name', controller.name);

                await $api.post('/api/folder/' + folder_id + '/addFolder', formData)
                    .then(response => {
                        controller.node.children.push({
                            id: response.data.id,
                            name: response.data.name,
                            showFolder: false,
                            showMenu: false,
                            apiCall: false,
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
            if (controller.file == null) {
                this.uploadFileController.fileEmpty = true;
                this.uploadFileController.disabled = true;
            } else {
                let formData = new FormData();
                formData.append('name', controller.name);
                formData.append('file', controller.file);


                await $api.post('/api/folder/' + controller.id, formData,
                ).then(response => {

                    this.selectedFolder.attachment.push(response.data.data);
                    controller.show = false;

                }).catch(err => {
                    controller.errors = err.response.data.errors;
                });

            }
        }
    }
}
</script>
