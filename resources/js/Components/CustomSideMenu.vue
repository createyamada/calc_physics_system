<template>
    <div class="container">
        <div class="side_menu_container">
            <div class="side_menu">
                <div>
                    <!-- <img src="" alt="計算メニュー"> -->
                    <p class="side_menu_header">計算メニュー</p>
                </div>
            </div>

            <div class="side_menu_content">
                <span v-for="(val,key) in CONTENTS" :key="key">
                    <button class="side_menu_content_btn">
                        <router-link :class="['link' , { 'is-selected': isSelect(val) }]" :to="val.path">{{ val.label }}</router-link>
                    </button>
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>

// const
// サイドメニュー表示内容
const CONTENTS = [
    {'path': '/parabolicMotion' , 'label':'放物運動計算'}
];


const isSelect = (val) => {
    return true;
}


</script>

<style scoped>


.side_menu {
    display: flex;
    margin: 20px 20px 18px 8px;
}

.side_menu_header , .link {
    font-style: var(--ft-style-normal);
    font-size: var(--ft-size-14);
}

.side_menu_header {
    font-weight: var(--ft-weight-bold);
    color: var(--side-text);
    margin-left: 18px;
}

.side_menu_content {
    margin-bottom: 14px;
}

.link {
    display: inline-block;
    text-align: left;
    width: 100%;
    font-weight: normal;
    color: var(--dark-color);
    padding-top: 6px;
    padding-left: 20px;
    padding-bottom: 10px;
    position: relative;
    z-index: 1;
    transition: .3s;
}

.link:hover {
    background-color: var(--side-hover-color);
    color: white;
    font-weight: bold;
    transition: all .4s ease-out;
    z-index: 1;
}

.link::before {
    content: "";
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    z-index: -1;
    background: var(--active-color);
    transform-origin: 100% 50%;
    transform: scaleX(0);
    transition: transform ease .3s;
  }


  .link:hover::before {
    transform-origin: 0% 50%;
    transform: scaleX(1);
  }


.link.router-link-active {
    background-color: var(--dark-color);
    color: var(--dark-color);
    color: white;
    font-weight: bold;  
}

.side_menu_content_btn {
    display: flex;
    text-align: start;
    width: calc(100% - 40px);
    margin: 6px 20px;
}


</style>