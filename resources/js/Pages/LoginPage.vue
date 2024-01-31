<template>
    <div class="config_contents">
        <div class="input_contents">
            <CustomInput
                type="'text"
                :name="'userId'"
                :label="'ユーザID'"
                v-model="userIdRef"
                :placeholder="'ユーザID'"
                @input="userIdRef = $event"
            />
            <CustomInput
                type="'text"
                :name="'password'"
                :label="'パスワード'"
                v-model="passwordRef"
                :placeholder="'パスワード'"
                @input="passwordRef = $event"
            />
            {{ passwordRef }}
        </div>

        <div class="btn_contents">
            <CustomSubmitButton :label="'ログイン'" @click="login" />
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import Request from "@/Utils/Request";
import CustomSubmitButton from "@/Components/CustomSubmitButton.vue";
import CustomInput from "@/Components/CustomInput.vue";

// const
const userIdRef = ref("");
const passwordRef = ref("");

const router = useRouter();

// ボタンクリックイベント
const login = async () => {
    const res = await Request.login(userIdRef.value, passwordRef.value);
    // ログイン成功の場合
    if (res.status === 200) {
        // ローカルストレージに認証情報を保存
        window.localStorage.setItem(["user_id"], [userIdRef.value]);
        // window.localStorage.setItem(["login"], 1);
        // メイン画面に遷移
        router.push("/");
    } else {
        alert("エラーです");
    }
};
</script>

<style scoped></style>
