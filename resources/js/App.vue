<template>
    <Loading v-if="loadPage"/>
    <div v-else>
        <FilterComponent :create-folder="addFolder"/>
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
            <PreviewComponent :folder="selectedFolder"/>
        </div>
    </div>
    <Modal @save="saveModal(folderModalController)" :title="folderModal.title" v-model="folderModalController.show">
        <input v-on:keyup.enter="saveModal(folderModalController)" v-model="folderModal.name" type="text" class="form-control text-center" placeholder="Folder name">
    </Modal>
</template>

<script>
import FilterComponent from "./components/FilterComponent.vue";
import FolderComponent from "./components/FolderComponent.vue";
import PreviewComponent from "./components/PreviewComponent.vue";
import Modal from "./elements/Modal.vue"

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
                    hasChildren: false,
                    show: false,
                    node: {}
                },
                selectedFolder: {},
                loadPage: true,
                folderModal: {
                    id: null,
                    name: '',
                    showFolder: false,
                    showMenu: false,
                    children: []
                },
                folders: [
                    {
                        id: 1,
                        name: 'root 1',
                        showFolder: false,
                        showMenu: false,
                        children: [
                            {
                                id: 2,
                                name: 'sub folder',
                                showFolder: false,
                                showMenu: false,
                                children: []
                            },
                            {
                                id: 3,
                                name: 'sub folder s2',
                                showFolder: false,
                                showMenu: false,
                                children: []
                            }
                        ]
                    },
                    {
                        id: 4,
                        name: 'root 1',
                        showFolder: false,
                        showMenu: false,
                        children: [
                            {
                                id: 5,
                                name: 'sub folder',
                                showFolder: false,
                                showMenu: false,
                                children: []
                            },
                            {
                                id: 6,
                                name: 'sub folder s2',
                                showFolder: false,
                                showMenu: false,
                                children: []
                            }
                        ]
                    }

                ]
            }
        },
        methods: {
            addFolder (){
                this.folderModal.title = 'Add new folder';
                this.folderModal.name = '';

                this.hideAllMenu(this.folders);
                this.folderModalController.hasChildren = false;
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
                this.folderModalController.hasChildren = true;
                this.folderModalController.node = node;
                this.folderModalController.show = true;
            },
            toggleFolder (node) {
                this.selectedFolder = node;
                node.showFolder = !node.showFolder;
                this.hideAllMenu(this.folders);
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
            saveModal(controller) {
                (controller.hasChildren) ?
                    controller.node.children.push({
                        id: 10,
                        name: this.folderModal.name,
                        showFolder: false,
                        showMenu: false,
                        children: []
                    }) :
                    controller.node.push({
                        id: 10,
                        name: this.folderModal.name,
                        showFolder: false,
                        showMenu: false,
                        children: []
                    })

                controller.node.showFolder = true;
                controller.show = false;
            },
            async getFolderApi() {
                setTimeout(()=>{
                    this.loadPage = false;
                }, 1500);
            }
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
