<template>
    <div class="pt-4 ps-3 w-100">
        <div v-if="folder.id">
            <p class="text-secondary fw-bold" style="font-size: 12px;">{{folder.name}}</p>
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
                <tr class="border-top">
                    <td>aaaaaaa</td>
                    <td>-</td>
                    <td>16/01/2020</td>
                    <td></td>
                    <td>0</td>
                </tr>
                </tbody>
            </table>
            <button class="flat btn border fw-bold mt-4"><i class="bi bi-file-earmark-arrow-up me-2"></i>Upload Document</button>
        </div>
        <div v-else class="text-center text-secondary py-5">
            <h3 class="fw-light">Select one of the folders to preview files</h3>
            <h3 class="fw-light">or create a new folder</h3>
        </div>
    </div>
</template>

<script>
export default {
    props: ['folder'],
    methods: {
    }
}
</script>


