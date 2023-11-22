<template>
    <Teleport to="#app">
        <div class="container">
            <div class="close_modal" @click="click()"></div>
            <div class="modal_mask">
                <slot></slot>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
    // emit
    const emit = defineEmits(['modalClose']);

    const click = () => {
        emit('modalClose');
    }
</script>

<style scoped>
.container {
    z-index: 100;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.close_modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: red;
}

.modal_mask {
    position: fixed;
    display: flex;
    align-items: center;
    justify-content: space-between;
    top: 50%;
    left: 50%;
    transform: translate(-50% , -50%);
}

</style>