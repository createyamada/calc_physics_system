<template>
    <label class="select_box">
        <select :disabled="disableFlag" @change="handleChange">
            <option
                v-for="planet in values"
                :key="planet.id"
                :value="planet.id"
            >
                {{ planet.name }}
            </option>
        </select>
    </label>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import Const from "@/Utils/Const";

// props
const props = defineProps({
    values: Object,
    modelValue: String,
    disableFlag: Boolean,
});

// emit
const emit = defineEmits(["change"]);

// セレクトチェンジイベント
const handleChange = (e) => {
    emit("change", e.target.value);
};
</script>

<style scoped>
.select_box {
    display: inline-flex;
    align-items: center;
    position: relative;
}

.select_box::after {
    position: relative;
    right: 15px;
    width: 10px;
    height: 7px;
    background-color: var(--flame-cancel-color);
    clip-path: polygon(0 0, 100% 0, 50% 100%);
    content: "";
    pointer-events: none;
}

.select_box select {
    appearance: none;
    min-width: 230px;
    height: 2.4em;
    padding: 0.4em calc(0.8em + 30px) 0.4em 0.8em;
    border: 2px solid var(--flame-cancel-color);
    border-radius: 3px;
    background-color: #fff;
    font-size: 1em;
    cursor: pointer;
}
</style>
