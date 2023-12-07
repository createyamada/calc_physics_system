<template>
    <div class="container">
        <label>{{ label }} ：</label>
        <input class="text_box" type="text"
        :v-model="value"
        :value="value"
        :name="name"
        :disabled="disableFlag"
        :placeholder="placeholder" 
        @input="textChange">
    </div>
</template>

<script setup>
    import { ref , watch } from 'vue';
 
    // props
    const props = defineProps({
        name: String,
        placeholder: String,
        disableFlag: Boolean,
        label: String,
        value: String,
    });

    // emit
    const emit = defineEmits([
        'input',
        'value'
    ]);

    // テキスト入力値
    // const textValueRef = ref(props.value);

    const textChange = ($e) => {
        console.log($e)
        console.log(textValueRef.value);
        
        emit('input' , textValueRef.value);
    }

    watch(props.value, () => {
        console.log('ここ来ています？')
        textValueRef.value = props.value;
    })
</script>'

<style scoped>

    .container {
        margin-top: 20px;
    }
    
    .text_box {
        border: 1px solid black;
    }
</style>