<template>
    <vue-final-modal v-slot="{ params, close }" v-bind="$attrs" classes="modal-container" content-class="modal-content">
         <div class="px-3">
            <h5 class="text-center mb-4">Opps, something went wrong</h5>

             <ol>
                 <li v-for="error of errors" class="mb-2">
                     <span class="text-capitalize text-danger">{{ error.$property }}&nbsp;</span>
                     <span class="text-lowercase">{{ error.$message }}</span>
                 </li>
             </ol>

            <div class="d-flex justify-content-center mt-3">
                <button @click="close" type="button" class="btn btn-outline-secondary ms-2">Close</button>
            </div>
        </div>
    </vue-final-modal>
</template>

<script>
export default {
    props: ['errors'],
    inheritAttrs: false,
    emits: ['save', 'cancel'],
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
