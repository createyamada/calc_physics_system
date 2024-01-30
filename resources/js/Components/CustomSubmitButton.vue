<template>
    <button class="submit_btn" @click="clickHandle" :disabled="disableFlag">
        {{ label }}
    </button>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

// props
const props = defineProps({
    label: String,
    disableFlag: Boolean,
});

// emit
const emit = defineEmits(["click"]);

// ボタンクリックイベント
const clickHandle = async () => {
    emit("click");
};
</script>

<style scoped>
button.submit_btn {
    display: block;
    text-align: center;
    text-decoration: none;
    width: 120px;
    margin: auto;
    padding: 10px 20px;
    font-weight: bold;
    border: 2px solid var(--active-color);
    color: var(--active-color);
    border-radius: 100vh;
    transition: 0.5s;
}

button.submit_btn:hover {
    color: #fff;
    background: var(--active-color);
}
</style>
