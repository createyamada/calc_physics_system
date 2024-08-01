<template>
    <div class="input_content">
        <h2 class="err_msg" v-show="isValidateErrRef">
            {{ validateErrMsgRef }}
        </h2>
        <CustomInput
            :type="'number'"
            :step="'0.01'"
            :name="'k'"
            :label="'ばね定数'"
            :value="kRef"
            :placeholder="'1以上の数値で入力'"
            @input="kRef = $event"
        />
        <CustomInput
            :type="'number'"
            :step="'0.01'"
            :name="'m'"
            :label="'小球を質量'"
            :value="mRef"
            :placeholder="'1以上の数値で入力'"
            @input="mRef = $event"
        />
        <CustomInput
            v-if="inputResetFlagRef"
            type="'number'"
            :step="'0.01'"
            :name="'x'"
            :label="'自然長からの位置'"
            v-model="xRef"
            :placeholder="'1以上の数値で入力'"
            @input="xRef = $event"
            :disableFlag="false"
        />
        {{ xRef }}
        <CustomInput
            :type="'number'"
            :step="'0.01'"
            :name="'phi'"
            :label="'摩擦係数'"
            :value="phiRef"
            :placeholder="'1以上の数値で入力'"
            @input="phiRef = $event"
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
                    { id: 0, name: '運動方程式' },
                    { id: 1, name: '数値計算：オイラー法' },
                    { id: 0, name: '数値計算：ルンゲ・クッタ法' },
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
    <canvas id="canvas" width="640" height="500"></canvas>

    <LineGraph
        :chartLabel="chartLabelRef"
        :chartElemLabel="chartElemLabelRef"
        :chartData="chartDataRef"
    />

    <LineGraph
        :chartLabel="chartLabelPyRef"
        :chartElemLabel="chartElemLabelPyRef"
        :chartData="chartDataPyRef"
    />

    <ErrorModal
        v-show="isErrorRef"
        :message="errorMsgRef"
        @close="errorModalClose()"
    >
    </ErrorModal>
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from "vue";
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

//　グラフのラベル
const chartLabelPyRef = ref([]);
//　グラフのラベル
const chartElemLabelPyRef = ref([]);
//　グラフのデータ
const chartDataPyRef = ref([]);

// リクエストのための変数
// ばね定数
const kRef = ref("");
// 質量
const mRef = ref("");
// 自然長からの位置
const xRef = ref(0);
// 摩擦係数
const phiRef = ref("");
// 初速度
const speedRef = ref("");
// 計測間隔
const stepRef = ref("");
// 計算方法
const calcTypeRef = ref(0);
// 小球の情報
const currentXRef = ref(0);
const radiusRef = ref(10);
const initPositionRef = ref(0);

// キャンバス情報
const canvasWidthRef = ref(0);
const canvasHeightRef = ref(0);
const canvasRef = ref(null);
const ctxRef = ref(null);
const lineWidthRef = ref(10);

// 位置情報
const pointRef = ref({});

// クリックフラグ
const obfClickFlagRef = ref(false);
// インプットリセットフラグ
const inputResetFlagRef = ref(true);
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
onMounted(async () => {
    try {
        canvasRef.value = document.getElementById("canvas");
        ctxRef.value = canvasRef.value.getContext("2d");

        //　キャンバスオブジェクトに小球初期表示
        iniDraw();

        canvasRef.value.addEventListener("mouseup", (e) => {
            console.log("mouseup");
            draw(false, false);
        });

        canvasRef.value.addEventListener("mousedown", (e) => {
            setNowPosition(e);
            if (
                pointRef.value["x"] < currentXRef.value + radiusRef.value &&
                pointRef.value["x"] > currentXRef.value - radiusRef.value &&
                pointRef.value["y"] < canvas.height / 2 + radiusRef.value &&
                pointRef.value["y"] > canvas.height / 2 - radiusRef.value
            ) {
                console.log("mousedown");
                draw(true, true);
            }
        });

        canvasRef.value.addEventListener("mousemove", async (e) => {
            console.log("mousemove");
            //　クリック状態であれば処理する
            if (obfClickFlagRef.value) {
                setNowPosition(e);
                draw(true, true);
                inputResetFlagRef.value = false;
                await nextTick();
                inputResetFlagRef.value = true;
            }
        });
    } catch (err) {
        console.log(err);
    }
});

// 自然長からの位置変数を監視
watch(
    () => xRef.value,
    (val) => {
        if (!obfClickFlagRef.value) {
            // テキストボックスからの入力であればキャンバスの位置を変更する
            console.log(`count is: ${val}`);
            currentXRef.value = initPositionRef.value;
            currentXRef.value = Number(currentXRef.value) + Number(val);
            draw(false, false);
        }
    }
);

// グラフ更新
const updateChart = async () => {
    try {
        const res = await Request.calcSimpleHarmonizeMotion(
            kRef.value,
            mRef.value,
            xRef.value,
            phiRef.value,
            speedRef.value,
            stepRef.value,
            calcTypeRef.value
        );
        // chartDataRef.value = res.data?.position ?? [];
        let data = res.data ?? [];
        chartDataRef.value.push(data["datas"]);
        animation(data["datas"]);
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

// アニメーション描画
const animation = (data) => {
    console.log("data.length");
    console.log(data.length);

    // 点の位置 [m]
    let x = 0;
    // 位置情報データのインデックス管理用
    let index = 0;

    const update = () => {
        // インデックスをインクリメント
        x = data[index]["y"];
        console.log(x);
        // console.log(index);

        if (index === data.length - 1) {
            // 配列が最後まで終了したらインデックスを初期化
            index = 0;
        } else {
            index++;
        }
    };

    const animationDraw = () => {
        // 画面の消去
        ctxRef.value.clearRect(
            0,
            0,
            canvasWidthRef.value,
            canvasHeightRef.value
        );

        // 点の描画
        ctxRef.value.beginPath();
        ctxRef.value.arc(
            Number(initPositionRef.value + x),
            canvasHeightRef.value / 2,
            radiusRef.value,
            0,
            Math.PI * 2
        );
        ctxRef.value.fill();
    };

    const tick = () => {
        update();
        animationDraw();
        requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
};

/**
 * キャンバスオブジェクト移動後再描画
 * @param {boolen} changeFlag 描画位置移動フラグ
 * @param {boolen} clickFlag クリックフラグ
 */
const draw = (changeFlag, clickFlag) => {
    if (changeFlag) {
        currentXRef.value = pointRef.value["x"];
    }

    if (clickFlag) {
        ctxRef.value.fillStyle = "red";
    } else {
        ctxRef.value.fillStyle = "blue";
    }

    obfClickFlagRef.value = clickFlag;

    // 画面の消去
    ctxRef.value.clearRect(0, 0, canvasWidthRef.value, canvasHeightRef.value);

    // 線の基本スタイル
    ctxRef.value.strokeStyle = "#666";
    ctxRef.value.lineWidth = lineWidthRef.value;

    // 点の描画
    ctxRef.value.beginPath();
    ctxRef.value.arc(
        currentXRef.value,
        canvasHeightRef.value / 2,
        radiusRef.value,
        0,
        Math.PI * 2
    );
    ctxRef.value.closePath();
    ctxRef.value.fill();

    // 線の描画
    ctxRef.value.beginPath();
    ctxRef.value.moveTo(
        0,
        canvasHeightRef.value / 2 - (radiusRef.value + lineWidthRef.value / 2)
    );
    ctxRef.value.lineTo(
        canvasWidthRef.value,
        canvasHeightRef.value / 2 - (radiusRef.value + lineWidthRef.value / 2)
    );
    ctxRef.value.closePath();
    ctxRef.value.stroke();
};

/**
 * キャンバス初期表示
 * @param {void}
 */
const iniDraw = () => {
    console.log("iniDraw");

    const dpr = window.devicePixelRatio || 1;
    canvasWidthRef.value = canvasRef.value.width;
    canvasHeightRef.value = canvasRef.value.height;

    // Canvasをピクセル比で拡大
    canvasRef.value.width *= dpr;
    canvasRef.value.height *= dpr;
    // CSSで元のサイズに戻す
    canvasRef.value.style.width = canvasWidthRef.value + "px";
    canvasRef.value.style.height = canvasHeightRef.value + "px";
    // Canvasの描画自体を拡大
    ctxRef.value.scale(dpr, dpr);

    // y座標を反転
    ctxRef.value.scale(1, -1);
    // y軸に沿って高さ分下にずらす
    ctxRef.value.translate(0, -canvasHeightRef.value);

    currentXRef.value = initPositionRef.value = canvasWidthRef.value / 4;

    draw(false, false);
    console.log("iniDrawfinish");
};

/**
 * クリック現在位置を取得
 * @param {Object} eventObj
 */
const setNowPosition = (eventObj) => {
    // マウスの座標をCanvas内の座標とあわせるため
    const rect = canvas.getBoundingClientRect();
    pointRef.value = {
        x: eventObj.clientX - rect.left,
        y: eventObj.clientY - rect.top,
    };
    xRef.value = String(
        Number(xRef.value) + Number(pointRef.value["x"] - currentXRef.value)
    );

    console.log("currentx");
    console.log(xRef.value);
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
