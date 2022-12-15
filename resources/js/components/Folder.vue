<template>
    <div class="" v-for="f in folders" :key="f.id">
        <button @click="handleClick(f)" class="btn btn-success mb-2">{{f.name}}</button>
        <div class="d-inline-block ms-5">
            <button class="btn btn-light mx-1" @click="showMenu(f)">show menu</button>
            <button class="btn btn-success mx-1" v-if="f.showMenu">add</button>
            <button class="btn btn-danger mx-1" v-if="f.showMenu">delete</button>

        </div>
        <div v-if="f.children && f.showFolder" class="ms-4">
            <folder :handle-click="handleClick" :show-menu="showMenu" :folders="f.children"/>
        </div>
    </div>

</template>

<script>
export default {
    name: 'folder',
    props: {
        folders: Object,
        handleClick: Function,
        showMenu: Function,
    },
}
</script>
