<template>
    <vue-final-modal v-slot="{ params, close }" v-bind="$attrs" classes="modal-container" content-class="modal-content">
        <div style="min-width: 30rem;">
            <div class="modal-header">
                <h5 class="modal-title">{{ title }}</h5>
            </div>
            <div class="modal-body">
                <slot/>
            </div>
            <div class="modal-footer">
                <button @click="close" type="button" class="btn btn-outline-secondary me-2">Cancel</button>
                <button @click="$emit('confirm', close)" type="submit" class="btn btn-success border text-center">Save</button>
            </div>

        </div>
    </vue-final-modal>
</template>

<script>
export default {
    props: ['title'],
    inheritAttrs: false,
    emits: ['confirm', 'cancel'],
    methods: {
        uploadFile(){
            this.document.file = this.$refs.file.files[0];
        },
    }

}
</script>
