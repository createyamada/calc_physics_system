<template>
    <div class="container">
        <label>{{ label }} ：</label>
        <input
            class="text_box"
            :type="type"
            :step="step"
            v-model="textValueRef"
            :name="name"
            :disabled="disableFlag"
            :placeholder="placeholder"
            @input="textChange"
        />
    </div>
</template>

<script setup>
import { ref } from "vue";

// props
const props = defineProps({
    name: String,
    placeholder: String,
    modelValue: String,
    disableFlag: Boolean,
    type: String,
    step: String,
    label: String,
});

// emit
const emit = defineEmits(["input"]);

// テキスト入力値
const textValueRef = ref(props.modelValue);

const textChange = ($e) => {
    emit("input", textValueRef.value);
};
</script>

<style scoped>
.container {
    margin-top: 20px;
}

.text_box {
    border: 2px solid var(--flame-cancel-color);
    border-radius: 3px;
}
</style>
