<template>
    <a class="btn btn-flat container" @click="clickHandle">
        <span>
            {{ label }}
            <button ></button>
        </span>
    </a>
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
    const clickHandle = async() => {
        emit('click');
    }


    // ボタンクリックイベント
    const handleClick = () => {
        emit('click');
    }


</script>

<style scoped>

.container {
    margin-top: 20px;
}

    .btn,
    a.btn,
    button.btn {
      font-size: 20px;
      font-weight: 700;
      line-height: 1.5;
      position: relative;
      display: inline-block;
      padding: 3px 7px;
      cursor: pointer;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
      -webkit-transition: all 0.3s;
      transition: all 0.3s;
      text-align: center;
      vertical-align: middle;
      text-decoration: none;
      letter-spacing: 0.1em;
      color: var(--dark-color);
      border-radius: 0.5rem;
    }
    
    a.btn-flat {
      overflow: hidden;
    
      padding: 10px 45px;
    
      color: #fff;
      border-radius: 0;
      background: var(--dark-color);
    }
    
    a.btn-flat span {
      position: relative;
    }
    
    a.btn-flat:before {
      position: absolute;
      top: 0;
      left: 0;
    
      width: 150%;
      height: 500%;
    
      content: "";
      -webkit-transition: all 0.5s ease-in-out;
      transition: all 0.5s ease-in-out;
      -webkit-transform: translateX(-98%) translateY(-25%) rotate(45deg);
      transform: translateX(-98%) translateY(-25%) rotate(45deg);
    
      background:var(--active-color) ;
    }
    
    a.btn-flat:hover:before {
      -webkit-transform: translateX(-9%) translateY(-25%) rotate(45deg);
      transform: translateX(-9%) translateY(-25%) rotate(45deg);
    }
</style>