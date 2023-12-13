<template>
    <div id="modal">
        <div id="modal-content" class="modal">
            <p id="modal-message" class="modal_message">{{ message }}</p>
            <button class="modal_btn" @click="modalClose()">閉じる</button>
        </div>
        <div id="modal-overlay"></div>
    </div>
</template>

<script setup>
import Modal from "./Layouts/ModalLayout.vue";

// props
const props = defineProps({
    message: String,
});

// emit
const emit = defineEmits(["close"]);

// 閉じるボタンクリっく
const modalClose = async () => {
    emit("close");
};
</script>

<style scoped>
.modal {
    width: 500px;
    height: 150px;
    padding: 10px 20px;
    border: 2px solid #a5272a;
    background: #faebd7;
    z-index: 2;
    display: block;
    text-align: center;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 10px;
}

.modal_message {
    margin-top: 10px;
}

.modal_cancel {
    margin-right: 30px;
    font-size: 15px;
}

.modal_cancel:hover {
    cursor: pointer;
    color: rgb(14, 48, 240);
    font-weight: bold;
}

.modal_btn {
    display: inline-block;
    margin: 60px auto;
    text-decoration: none;
    width: 80px;
    height: 30px;
    text-decoration: none;
    color: #000000;
    border: solid 2px #a5272a;
    border-radius: 3px;
    transition: 0.4s;
    text-align: center;
    vertical-align: middle;
    font-size: 15px;
    background-color: #faebd7;
}

.modal_btn:hover {
    background: #a5272a;
    color: white;
    cursor: pointer;
}

#modal-overlay {
    z-index: 1;
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 120%;
    background-color: rgba(0, 0, 0, 0.75);
}
</style>
