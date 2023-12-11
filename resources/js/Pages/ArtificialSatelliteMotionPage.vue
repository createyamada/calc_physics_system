<template>
  <div class="container">
    <div class="config_contents">
      <div class="input_contents">
        <div class="planet_select">
          <CustomSelectBox  :disableFlag="inputDisableFlagRef" @change="selectChange"/>
          <p>※惑星を選択するとその惑星に応じた第一宇宙速度が入力されます</p>
        </div >
        <div class="textbox_input">
          <div class="planet_info">
            <CustomInput type="'number" :name="'height'" :label="'打ち上げ高度(km)'" v-model="heightRef"  :step="'0.01'" :placeholder="'正の整数で入力'" :disableFlag="inputDisableFlagRef" @input="heightRef = $event" />
            <CustomInput v-if="inputResetFlagRef" type="'number" :step="'0.01'" :name="'speed'" :label="'初速度(km/s)'" v-model="speedRef" :placeholder="'正の整数で入力'" :disableFlag="inputDisableFlagRef" @input="speedRef = $event" />
            <CustomInput v-if="inputResetFlagRef" type="'number" :step="'0.01'" :name="'mass'" :label="'惑星質量(kg)'" v-model="massRef" :placeholder="'正の整数で入力'" :disableFlag="inputDisableFlagRef" @input="massRef = $event" />
            <CustomInput v-if="inputResetFlagRef" type="'number" :step="'0.01'" :name="'radius'" :label="'惑星半径(km)'" v-model="radiusRef" :placeholder="'正の整数で入力'" :disableFlag="inputDisableFlagRef" @input="radiusRef = $event" />
          <!-- </div> -->
          <!-- <div class="view_info"> -->
            <CustomInput v-if="inputResetFlagRef" type="'number" :step="'0.01'" :name="'scale'" :label="'スケール(1km/px)'" v-model="scaleRef" :disableFlag="inputDisableFlagRef" :placeholder="'正の整数で入力'" @input="scaleRef = $event"/>
            <CustomInput v-if="inputResetFlagRef" type="'number" :step="'0.01'" :name="'time_speed'" :label="'倍速時間(s)'" v-model="timeSpeedRef" :disableFlag="inputDisableFlagRef" :placeholder="'正の整数で入力'" @input="timeSpeedRef = $event"/>
          </div>

        </div>
      </div>
  
      <div class="btn_contents">
        <CustomSubmitButton :label="'計算開始'" @click="calcStart" />
        <CustomCancelButton :label="'クリア'" @click="calcStop" />
      </div>

    </div>

    <div class="main_contents">
      <div v-if="infoResetFlagRef" class="control_content">
        <p>X成分速度:{{ xSpeedRef }}</p>
        <p>Y成分速度:{{ ySpeedRef }}</p>
        <p>X成分位置:{{ xPositionRef }}</p>
        <p>Y成分位置:{{ yPositionRef }}</p>
        <p>仮想日付:{{ dateRef }}</p>
      </div>
      <div>
        <canvas v-show="canvasFlagRef" id="canvas" width="640" height="500"></canvas>
      </div>
    </div>


  </div>
</template>

<script setup>
import { onMounted, ref , nextTick } from 'vue';
import CustomSubmitButton from '@/Components/CustomSubmitButton.vue';
import CustomCancelButton from '@/Components/CustomCancelButton.vue';
import CustomSelectBox from '@/Components/CustomSelectBox.vue';
import CustomInput from '@/Components/CustomInput.vue';
import Common from '@/Utils/Common';
import Const from '@/Utils/Const';
// 衛星クラス
import ArtificialSatelliteClass from '../Classes/ArtificialSatelliteClass';
// 惑星クラス
import PlanetClass from '../Classes/PlanetClass';

// const
// 速度管理変数
// X軸成分
const xSpeedRef = ref(0);
// Y軸成分
const ySpeedRef = ref(0);

// 位置管理変数
// X軸成分
const xPositionRef = ref(0);
// Y軸成分
const yPositionRef = ref(0);

// 入力値管理変数
// 打ち上げ高度
const heightRef = ref(0);
// 初速度
const speedRef = ref(8.4);
// 惑星質量
const massRef = ref(5.972 * 1e24);
// 惑星半径
const radiusRef = ref(6378.1);
// 重力係数
const gravityCoefficientRef = ref(0);
// 日付
const dateRef = ref(0);
// スケール
const scaleRef = ref(30);
// 時間経過スピード
const timeSpeedRef = ref(200);
// 惑星セレクトボックス
const selectValueRef = ref(0);
// キャンバス描画フラグ
const canvasFlagRef = ref(false);
// インプットリセットフラグ
const inputResetFlagRef = ref(true);
// インプット活性非活性フラグ
const inputDisableFlagRef = ref(false);
// 情報リセットフラグ
const infoResetFlagRef = ref(true);

// セレクトボックスチェンジ
const selectChange = async(event) => {
  // 各情報の更新
  massRef.value = Const.PLANET_INFO?.[event].m;
  radiusRef.value = Const.PLANET_INFO?.[event].r;
  scaleRef.value = Const.PLANET_INFO?.[event].scale;

  // 惑星クラスインスタンス生成
  let planetClass = new PlanetClass({
      r: parseFloat(radiusRef.value),
      m: parseFloat(massRef.value),
      h: parseFloat(heightRef.value),
      scale: parseFloat(scaleRef.value),
  });

  // 速度の部分に第一宇宙速度を代入
  speedRef.value = planetClass.firstCosmicVelocity;

  inputResetFlagRef.value = false;
  await nextTick()
  inputResetFlagRef.value = true;
    
}


const calcStart = async () => {

  // フラグ変更
  // キャンバス描画
  canvasFlagRef.value = true;
  // インプット非活性フラグ
  inputDisableFlagRef.value = true;


  gravityCoefficientRef.value = parseFloat(Const.G) * parseFloat(massRef.value)

  // 人口衛星クラスインスタンス生成
  let artificialSatelliteObj = []; // 管理用配列
  let artificialSatelliteClass = new ArtificialSatelliteClass({
    r: parseFloat(radiusRef.value) + parseFloat(heightRef.value),
    deg: 90,
    v: parseFloat(speedRef.value),
    vdeg: 90,
    k: parseFloat(gravityCoefficientRef.value),
    scale: parseFloat(scaleRef.value),
  });
  artificialSatelliteObj.push(artificialSatelliteClass);

  // 惑星クラスインスタンス生成
  let planetClass = new PlanetClass({
      r: parseFloat(radiusRef.value),
      m: parseFloat(massRef.value),
      h: parseFloat(heightRef.value),
      scale: parseFloat(scaleRef.value),
  });

  if (parseFloat(speedRef.value) <=  planetClass.firstCosmicVelocity) {
    // 速度が第一宇宙速度以下の場合
    console.log('第一宇宙速度以下')
  }

  if (parseFloat(speedRef.value) >=  planetClass.SecondCosmicVelocity) {
    // 速度が第二宇宙速度以下の場合
    console.log('第二宇宙速度以上')
    
  }

  const canvas = document.getElementById("canvas"),
  ctx = canvas.getContext("2d");
 
  // デバイスピクセルレシオ
  const dpr = window.devicePixelRatio || 1; 
  // Canvas横幅
  const width = canvas.width;
  // Canvas縦幅
  const height = canvas.height; 

  /** 高解像度対応 */
  // Canvasをピクセル比で拡大
  canvas.width *= dpr;
  canvas.height *= dpr;
  // CSSで元のサイズに戻す
  canvas.style.width = width + "px";
  canvas.style.height = height + "px";

  // 座標系の設定
  ctx.transform(dpr, 0, 0, -dpr, (width * dpr) / 2, (height * dpr) / 2);
  ctx.fillStyle = "#fff";
  
  /** シミュレーター */
  // FPS
  const fps = 60; 
  // 1フレーム当たりの秒数
  let dt = 1 / fps; 
  // 倍速数
  let speed = timeSpeedRef.value; 
  // 開始時間
  let startTime; 
  // 経過フレーム数
  let frameCount = 0; 


/** 地球描画 */
const drawEarth = () => {
  ctx.save();

  ctx.fillStyle = '#25A8FF'; // 塗り色を青に
  ctx.beginPath();
  ctx.arc(0, 0, radiusRef.value / scaleRef.value, 0, Math.PI * 2); // 中心に円を描く
  ctx.fill();

  ctx.restore();
}

let date = new Date();

  /** tick */
  const tick = (time) => {
    // 初回呼び出し時に開始時間を設定
    if (!startTime) startTime = time; 
    // 現在の経過フレーム数
    const currentFrame = Math.floor(((time - startTime) * fps) / 1000); 
    // 前回からのフレーム増分
    let df = currentFrame - frameCount; 
    // 速度分処理回数を増やす
    df *= speed; 

    // フレーム増分だけ更新処理を繰り返す
    while (df) {
      // オブジェクトの更新
      artificialSatelliteObj.forEach((obj, i) => {
        if (obj) {
          obj.update(dt);
          xSpeedRef.value = obj.x;
          ySpeedRef.value = obj.y;
          xPositionRef.value = obj.vx;
          yPositionRef.value = obj.vy;
          date.setSeconds(date.getSeconds() + 1);
          dateRef.value = date.toLocaleString();
          // 衝突判定
          if (obj.r <= radiusRef.value) {
            // 中心星に衝突した物体は取り除く 
            artificialSatelliteObj[i] = null; 
          }
          // フラグ判定
          if(!canvasFlagRef.value) {
            artificialSatelliteObj[i] = null; 
            xSpeedRef.value = ySpeedRef.value = xPositionRef.value = yPositionRef.value = dateRef.value = 0;
          } 
          
        }
      });
      if(!canvasFlagRef.value) break;
      df--;
    }
    // 衝突した物体を配列から除去 
    artificialSatelliteObj = artificialSatelliteObj.filter(Boolean); 

    // 画面の消去
    ctx.clearRect(-width / 2, -height / 2, width, height);

    // 再描画
    drawEarth();
    // planetClass.draw(ctx);

    artificialSatelliteObj.forEach((obj) => {
      obj.draw(ctx);
    });
    // フレーム数を更新
    frameCount = currentFrame; 
    requestAnimationFrame(tick);
  };
  // 処理開始
  tick(); 
}

// 計算終了
const calcStop = () => {
  // キャンバス描画
  canvasFlagRef.value = false;
  // 非活性を解除
  inputDisableFlagRef.value = false;
}
</script>

<style scoped>

.planet_select {
  display: flex;
  margin-top: 40px;
}

.config_contents {
  display: flex;
  justify-content: space-around;
  align-items: flex-start;
  margin: 20px;
}

.input_contents {
  display: flex;
  flex-wrap: wrap;
}

.textbox_input {
  display: flex;
  flex-flow: column;
}

.btn_contents {
  display: flex;
  flex-flow: column;
}

.control_content {
  width: 100px;
}

.main_contents {
  display: flex;
  justify-content: space-between;

}

.planet_info {
  display: flex;
  align-items: center;
  justify-content: center;
}

.view_info {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;
}


</style>