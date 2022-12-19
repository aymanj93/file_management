<template>
    <Loading v-if="loadPreview"/>
    <div v-else class="pt-4 ps-3 w-100">
        <div v-if="folder.name">
            <p class="text-secondary fw-bold" style="font-size: 12px;">{{folder.name}}</p>
            <div v-if="folder.attachment.length">
                <table class="border w-100">
                    <thead class="bg-light">
                    <tr>
                        <th style="width: 25%">File</th>
                        <th style="width: 25%">Labels</th>
                        <th style="width: 16%">Uploaded</th>
                        <th style="width: 16%">Expiry</th>
                        <th style="width: 17%">Size</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="a in folder.attachment" :key="a.id" class="border-top">
                        <td>{{ a.name }}</td>
                        <td>-</td>
                        <td>{{ a.uploaded }}</td>
                        <td>-</td>
                        <td>0</td>
                    </tr>
                    </tbody>
                </table>
                <button class="flat btn border fw-bold mt-4"><i class="bi bi-file-earmark-arrow-up me-2"></i>Upload File</button>
            </div>
            <div v-else class="text-center text-secondary py-5">
                <h3 class="fw-light">There are no files in this folder</h3>
                <button class="flat btn border fw-bold"><i class="bi bi-file-earmark-arrow-up me-2"></i>Upload File</button>
            </div>
        </div>
        <div v-else class="text-center text-secondary py-5">
            <h3 class="fw-light">Select one of the folders to preview files</h3>
            <h3 class="fw-light">or create a new folder</h3>
        </div>
    </div>
</template>

<script>
export default {
    props: ['folder', 'loadPreview'],
    methods: {
    }
}
</script>


