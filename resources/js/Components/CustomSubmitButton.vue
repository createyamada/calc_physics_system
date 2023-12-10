<template>
  <a class="submit_btn" @click="clickHandle">{{ label }}</a>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

// props
const props = defineProps({
  label: String,
});

// emit
const emit = defineEmits(['click']);

// ボタンクリックイベント
const clickHandle = async () => {
  emit('click');
}


// ボタンクリックイベント
const handleClick = () => {
  emit('click');
}


</script>

<style scoped>
a.submit_btn {
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

a.submit_btn:hover {
  color: #fff;
  background: var(--active-color);
}
</style>