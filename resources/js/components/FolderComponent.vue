<template>
    <p v-if="folders.length === 0" class="text-muted mt-1"><i class="bi bi-folder-x"></i> There are no folders here</p>
    <div class="d-flex flex-column folders" v-for="f in folders" :key="f.id">
        <div class="d-flex justify-content-between align-items-center">
            <button v-on:dblclick="toggleFolder(f)" @click="preview(f)" class="btn text-truncate p-0 mb-2">
                <i v-if="f.children.length > 0" @click="toggleFolder(f)"
                   :class="[f.showFolder ? 'bi-chevron-down' : 'bi-chevron-right']" class="bi me-2">
                </i>
                <i v-if="f.children.length > 0" :class="[f.showFolder ? 'bi-folder2-open' : 'bi-folder']" class="bi me-1"></i>
                <i v-else class="bi bi-folder me-1"></i>
                {{f.name}}
            </button>
            <div class="options">
                <button :class="{'btn-light' : f.showMenu}" class="btn" @click="showMenu(f)"><i class="bi bi-three-dots"></i></button>
                <div v-if="f.showMenu" class="d-flex shadow flex-column bg-light menu">
                    <button @click="addSubFolder(f)" class="btn text-start text-success">
                        Add Sub Folder
                    </button>
                </div>
            </div>
        </div>

        <div v-if="f.children.length > 0 && f.showFolder" class="ms-4">
            <FolderComponent
                :preview="preview"
                :toggle-folder="toggleFolder"
                :show-menu="showMenu"
                :folders="f.children"
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
        addSubFolder: Function,
        preview: Function,
    },
}
</script>

