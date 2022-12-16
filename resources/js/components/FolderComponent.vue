<template>
    <div class="d-flex flex-column" v-for="f in folders" :key="f.id">
        <div class="d-flex justify-content-between align-items-center">
            <button @click="toggleFolder(f)" class="btn p-0 mb-2">
                <i v-if="f.children.length > 0" :class="[f.showFolder ? 'bi-chevron-down' : 'bi-chevron-right']" class="bi me-2"></i>
                <i class="bi bi-folder me-1"></i>
                {{f.name}}
            </button>
            <div class="options">
                <button :class="{'btn-light' : f.showMenu}" class="btn" @click="showMenu(f)"><i class="bi bi-three-dots"></i></button>
                <div class="d-flex shadow flex-column bg-light menu" v-if="f.showMenu">
                    <button @click="renameFolder(f)" class="btn text-start text-secondary">Rename Folder</button>
                    <button @click="addSubFolder(f)" class="btn text-start text-success">Add Folder</button>
                    <button class="btn text-start text-danger">Delete Folder</button>
                </div>
            </div>
        </div>

        <div v-if="f.children && f.showFolder" class="ms-4">
            <FolderComponent
                :toggle-folder="toggleFolder"
                :show-menu="showMenu"
                :folders="f.children"
                :rename-folder="renameFolder"
                :add-sub-folder="addSubFolder"
            />
        </div>
    </div>

</template>

<script>
export default {
    name: 'FolderComponent',
    props: {
        folders: Object,
        toggleFolder: Function,
        showMenu: Function,
        renameFolder: Function,
        addSubFolder: Function,
    },
}
</script>

