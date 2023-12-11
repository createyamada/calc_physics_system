<template>
    <label class="select_box">
        <select :disabled="disableFlag" @change="handleChange">
            <option v-for="planet in Const.PLANET_INFO" :key="key" :value="planet.id"> {{ planet.name }}</option>
        </select>
    </label>
</template>

<script setup>
    import { ref } from 'vue';
    import { useRouter } from 'vue-router';
    import Const from '@/Utils/Const';
    
    // props
    const props = defineProps({
        modelValue: String,
        disableFlag: Boolean,
    });

    // emit
    const emit = defineEmits(['change']);

    // セレクトチェンジイベント
    const handleChange = (e) => {
        emit('change' , e.target.value);
    }

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
    content: '';
    pointer-events: none;
}

.select_box select {
    appearance: none;
    min-width: 230px;
    height: 2.8em;
    padding: .4em calc(.8em + 30px) .4em .8em;
    border: 2px solid var(--flame-cancel-color);
    border-radius: 3px;
    background-color: #fff;
    font-size: 1em;
    cursor: pointer;
}
</style>