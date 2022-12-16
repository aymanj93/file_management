<template>
    <div class="d-block d-md-flex flex-row px-5 pt-5">
        <div class="search-prefix">
            <i class="bi bi-search"></i>
            <input class="mb-1 mb-md-0" type="text" placeholder="Search...">
        </div>
        <div class="filter-container d-flex justify-content-center bg-white border mx-md-1 mb-1 mb-md-0">
            <button class="btn disabled fw-bold w-100 border-end"><i class="bi bi-funnel me-2"></i>Filter By</button>
            <div class="border-end"></div>
            <button class="btn disabled fw-bold w-100">Labels<i class="bi bi-chevron-down ms-2"></i></button>
        </div>
        <button @click="createFolder" class="flat btn fw-bold border mb-1 mb-md-0" style="width: 230px;"><i class="bi bi-folder-plus me-2"></i>Add Folder</button>
    </div>
</template>

<script>
export default {
    props: {
        createFolder: Function,
    }
}
</script>
