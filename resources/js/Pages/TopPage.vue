<template>
    <h1 class="top">物理計算サイト</h1>
    <div class="content">
        <div>
            <h2>様々な物理シミュレーションを行えるサイトです</h2>
        </div>
        <div>
            <h2>
                私立文系大学大学出身で、数学、物理を学んでこなかった管理者が物理学の面白さに気づき学びながら作成しています
            </h2>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from "vue-router";
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
</style>
