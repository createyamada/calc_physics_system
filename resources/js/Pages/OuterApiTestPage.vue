<template>
    <h1 class="top">物理計算サイト</h1>
    <div class="content">
        <button class="api-btn" @click="clickHandle">
            外部API呼び出しテスト
        </button>
    </div>
</template>

<script setup>
import { useRouter } from "vue-router";
import Request from "@/Utils/Request";

const router = useRouter();

// ボタンクリックイベント
const clickHandle = async () => {
    const res = await Request.outerApiTest();
    console.log(res);
};
</script>

<style scoped>
.top {
    margin-top: 30px;
    font-size: 50px;
    text-align: center;
}

.content {
    margin-top: 50px;
    display: flex;
    flex-flow: column;
    justify-content: center;
    align-items: center;
}

.api-btn {
    font-size: 100px;
    border: solid 2px black;
    background-color: aqua;
}
</style>
