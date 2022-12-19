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
                    :rename-folder="renameFolder"
                    :add-sub-folder="addSubFolder"
                />
            </div>
            <PreviewComponent
                :load-preview="loadPreview"
                :folder="selectedFolder"
            />
        </div>
    </div>
    <Modal @save="saveFolderModal(folderModalController)" :title="folderModal.title" v-model="folderModalController.show">
        <input v-on:keyup.enter="saveFolderModal(folderModalController)" v-model="folderModal.name" type="text" class="form-control text-center" placeholder="Folder name">
        <span v-if="errorController.isError" class="text-danger">{{ errorController.message }}</span>
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
            Modal
        },
        data() {
            return {
                folderModalController: {
                    type: '',
                    show: false,
                    node: {}
                },
                selectedFolder: {},
                loadPage: true,
                loadPreview: false,
                folderModal: {
                    id: null,
                    name: '',
                },
                folders: [],
                errorController: {
                    message: '',
                    isError: false,
                }
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
            },
            renameFolder (node){
                this.folderModal.title = 'Rename this folder';
                this.folderModal.name = node.name;
            },
            addSubFolder (node){
                this.folderModal.title = 'Add sub folder';
                this.folderModal.name = '';

                this.hideAllMenu(this.folders);
                this.folderModalController.type = 'child';
                this.folderModalController.node = node;
                this.folderModalController.show = true;
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
                            console.log(response.data.data);
                            node['attachment'] = response.data.data;
                            this.selectedFolder = node;
                            this.loadPreview = false;
                            node.showFolder = true;
                        });
                }



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
            async saveFolderModal(controller) {
                if (controller.type === 'folder') {
                    let formData = new FormData();
                    formData.append('name', this.folderModal.name);
                    await $api.post('/api/folder', formData)
                        .then(response => {
                            this.errorController.isError = false;

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
                            this.errorController.isError = true;
                            this.errorController.message = err.response.data.message;
                        });

                }

                if (controller.type === 'child') {
                    let folder_id = this.folderModalController.node.id;
                    let formData = new FormData();
                    formData.append('name', this.folderModal.name);

                    await $api.post('/api/folder/' + folder_id + '/createFolder', formData)
                        .then(response => {
                            this.errorController.isError = false;

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
                            this.errorController.isError = true;
                            this.errorController.message = err.response.data.message;
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
        },
        created() {
            this.getFolderApi();
        }
    }
</script>

<style scoped>
:deep(.modal-container) {
    display: flex;
    justify-content: center;
    align-items: center;
}
:deep(.modal-content) {
    display: flex;
    flex-direction: column;
    margin: 0 1rem;
    padding: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.25rem;
    background: #fff;
    width: auto;

}
.modal__title {
    font-size: 1.5rem;
    font-weight: 700;
}
.dark-mode div:deep(.modal-content)  {
    border-color: #2d3748;
    background-color: #1a202c;
}
</style>
