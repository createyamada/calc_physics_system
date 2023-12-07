<template>
  <div class="container">
    <div class="up_contents">
      <div class="planet_select">
        <select v-model="selectValueRef" :disable="inputFlagRef" @change="selectChange">
          <option v-for="planet in Const.PLANET_INFO" :key="key" :value="planet.i"> {{ planet.name }}</option>
        </select>
      </div>
      <div>
        <CustomButton :label="'計算開始'" @click="calcStart" />
        <CustomButton :label="'クリア'" @click="calcStop" />
      </div>

    </div>

    <div class="down_contents">
      <CustomInput :name="'height'" :label="'打ち上げ高度(km)'" :v-model="heightRef" :value="heightRef" :placeholder="'正の整数で入力'" :disableFlag="inputFlagRef" @input="heightRef = $event" />
      <CustomInput :name="'speed'" :label="'初速度(km/s)'" :value="speedRef" :placeholder="'正の整数で入力'" :disableFlag="inputFlagRef" @input="speedRef = $event" />
      <CustomInput :name="'mass'" :label="'惑星質量(kg)'" :v-model="massRef" :value="massRef" :placeholder="'正の整数で入力'" :disableFlag="inputFlagRef" @input="massRef = $event" />
      <CustomInput :name="'radius'" :label="'惑星半径(km)'" :v-model="radiusRef" :value="radiusRef" :placeholder="'正の整数で入力'" :disableFlag="inputFlagRef" @input="radiusRef = $event" />
      <CustomInput :name="'radius'" :label="'スケール(1km/px)'" :value="scaleRef" :disableFlag="inputFlagRef" :placeholder="'正の整数で入力'" />
    </div>

    <div class="control_content">
      <p>X成分速度:{{ xSpeedRef }}</p>
      <p>Y成分速度:{{ ySpeedRef }}</p>
      <p>X成分位置:{{ xPositionRef }}</p>
      <p>Y成分位置:{{ yPositionRef }}</p>
      <p>仮想日付:{{ dateRef }}</p>
    <canvas :v-show="canvasFlagRef"  id="canvas" width="640" height="800"></canvas>

    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import CustomButton from '@/Components/CustomButton.vue';
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
const gravityCoefficientRef = ref(Number(Const.G) * Number(massRef.value));
// 日付
const dateRef = ref(0);
// スケール
const scaleRef = ref(30);
// 惑星セレクトボックス
const selectValueRef = ref(0);
// キャンバス描画フラグ
const canvasFlagRef = ref(false);
// インプット活性非活性フラグ
const inputFlagRef = ref(false);

// セレクトボックスチェンジ
const selectChange = async(event) => {
  console.log(Const.PLANET_INFO?.[event.target.value].name)
  massRef.value = Const.PLANET_INFO?.[event.target.value].m;
  radiusRef.value = Const.PLANET_INFO?.[event.target.value].r;
}


const calcStart = async () => {

  // フラグ変更
  // キャンバス描画
  canvasFlagRef.value = true;
  // インプット非活性フラグ
  inputFlagRef.value = true;


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

  console.log('planetClass.firstCosmicVelocity')
  console.log(planetClass.firstCosmicVelocity)
  console.log('planetClass.SecondCosmicVelocity')
  console.log(planetClass.SecondCosmicVelocity)

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
  let speed = 200; 
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
        }
      });
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
  // 非活性を解除
  inputFlagRef.value = false;

}
</script>

<style scoped>


.up_contents {
  display: flex;
  margin: 20px;
}


.down_contents {
  display: flex;
  margin: 20px;
}

</style>