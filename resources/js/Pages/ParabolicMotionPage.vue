<template>
    <div class="input_content">
        <h2 class="err_msg" v-show="isValidateErrRef">
            {{ validateErrMsgRef }}
        </h2>

        <CustomInput
            :type="'number'"
            :step="'0.01'"
            :name="'angle'"
            :label="'角度'"
            :value="angleRef"
            :placeholder="'1~90の数値で入力'"
            @input="angleRef = $event"
        />
        <CustomInput
            :type="'number'"
            :step="'0.01'"
            :name="'speed'"
            :label="'速度'"
            :value="speedRef"
            :placeholder="'1以上の数値で入力'"
            @input="speedRef = $event"
        />
        <CustomInput
            :type="'number'"
            :step="'0.01'"
            :name="'step'"
            :label="'計測間隔(秒)'"
            :value="stepRef"
            :placeholder="'0より大きい数値で入力'"
            @input="stepRef = $event"
        />
        <div class="margin_top20">
            <CustomSelectBox
            @change="selectChange"
            :values="[
                {id:0,name:'運動方程式'},
                {id:1,name:'数値計算：オイラー法'},
                {id:0,name:'数値計算：ルンゲ・クッタ法'},
            ]"
        />
        </div>
        <div class="margin_top20">
            <CustomSubmitButton
            type="'number'"
            :step="'0.01'"
            :label="'計算開始'"
            @click="calcStart"
        />
        </div>

    </div>

    <LineGraph
        :chartLabel="chartLabelRef"
        :chartElemLabel="chartElemLabelRef"
        :chartData="chartDataRef"
    />

    <ErrorModal
        v-show="isErrorRef"
        :message="errorMsgRef"
        @close="errorModalClose()"
    >
    </ErrorModal>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Request from "@/Utils/Request";
import LineGraph from "@/Components/Graphs/LineGraph.vue";
import CustomInput from "@/Components/CustomInput.vue";
import CustomSelectBox from "@/Components/CustomSelectBox.vue";
import CustomSubmitButton from "@/Components/CustomSubmitButton.vue";
import ErrorModal from "@/Components/ErrorModal.vue";
import Common from "@/Utils/Common";

// const
// グラフ描画のための変数
//　グラフのラベル
const chartLabelRef = ref([]);
//　グラフのラベル
const chartElemLabelRef = ref([]);
//　グラフのデータ
const chartDataRef = ref([]);

// リクエストのための変数
// 排出角度
const angleRef = ref("");
// 初速度
const speedRef = ref("");
// 計測間隔
const stepRef = ref("");
// 計算方法
const calcTypeRef = ref(0);

// 連打防止用ボタンフラグ
const isCalcBtnRef = ref(true);

// エラー時の変数
// エラーフラグ
const isErrorRef = ref(false);
// エラーメッセージ
const errorMsgRef = ref("");
// バリデーションエラーフラグ
const isValidateErrRef = ref(false);
// バリデーションエラーメッセージ
const validateErrMsgRef = ref("");

// マウント前処理
// onMounted(async () => {
//     try {
//     } catch (err) {
//         console.log(err);
//     }
// });

// グラフ更新
const updateChart = async () => {
    try {
        const res = await Request.calcParabolicMotion(
            angleRef.value,
            speedRef.value,
            stepRef.value,
            calcTypeRef.value,
        );
        // chartDataRef.value = res.data?.position ?? [];
        let data = res.data?.position ?? [];
        chartDataRef.value.push(data);
    } catch (err) {
        console.log(err.message);
        // isErrorRef.value = true;
        let message = Common.axiosErrorHandle(err);
        err.response.status !== 422
            ? errorModalOpen(message)
            : validateErr(message);
    } finally {
        isCalcBtnRef.value = false;
    }
};

// セレクトボックスチェンジ
const selectChange = async (event) => {
    // 各情報の更新
    calcTypeRef.value = event;
};


// 計算開始ボタンクリック
const calcStart = async () => {
    // 連打の場合終了
    if (isCalcBtnRef.value === false) return;

    isCalcBtnRef.value = false;

    // バリデーションメッセージをOFF
    isValidateErrRef.value = false;

    // グラフ更新
    await updateChart();
    isCalcBtnRef.value = true;
};

// モーダル閉じるボタンクリック
const errorModalClose = async () => {
    isErrorRef.value = false;
};

// エラーモーダルを開く
const errorModalOpen = async (msg) => {
    isErrorRef.value = true;
    errorMsgRef.value = msg;
};

// バリデーションエラー発生
const validateErr = async (msg) => {
    validateErrMsgRef.value = msg;
    isValidateErrRef.value = true;
};
</script>

<style scoped>
.input_content {
    display: flex;
    flex-flow: column;
    justify-content: center;
    align-items: center;
}

.margin_top20 {
    margin-top: 20px;
}
</style>
