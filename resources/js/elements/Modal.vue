<template>
    <vue-final-modal v-slot="{ params, close }" v-bind="$attrs" classes="modal-container" content-class="modal-content">
         <div class="p-3">
            <h5 class="text-center mb-4">{{ title }}</h5>
            <slot/>

            <div class="d-flex justify-content-center mt-3">
                <button @click="$emit('save', close)" type="submit" class="btn btn-success border text-center">Save</button>
                <button @click="close" type="button" class="btn btn-outline-secondary ms-2">Cancel</button>
            </div>
        </div>
    </vue-final-modal>
</template>

<script>
export default {
    props: ['title'],
    inheritAttrs: false,
    emits: ['save', 'cancel'],
    methods: {
        uploadFile(){
            this.document.file = this.$refs.file.files[0];
        },
    }

}
</script>
