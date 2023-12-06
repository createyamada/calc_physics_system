<template>
  <div class="container">
    <canvas id="canvas" width="640" height="800"></canvas>

    <CustomInput :name="'height'" :label="'打ち上げ高度(km)'" :value="heightRef" :placeholder="'正の整数で入力'"
      @input="angleRef = $event" />
    <CustomInput :name="'speed'" :label="'初速度(km/s)'" :value="speedRef" :placeholder="'正の整数で入力'"
      @input="speedRef = $event" />
    <CustomInput :name="'mass'" :label="'惑星質量(kg)'" :value="massRef" :placeholder="'正の整数で入力'" @input="massRef = $event" />
    <CustomInput :name="'radius'" :label="'惑星半径(km)'" :value="radiusRef" :placeholder="'正の整数で入力'"
      @input="radiusRef = $event" />
    <CustomInput :name="'universalGravitation'" :label="'万有引力定数'" :value="universalGravitationRef" :placeholder="'正の整数で入力'" />
    <CustomInput :name="'radius'" :label="'スケール(1km/px)'" :value="scaleRef" :placeholder="'正の整数で入力'" />
    <div class="control_content">
      <p>X成分速度:{{ xSpeedRef }}</p>
      <p>Y成分速度:{{ ySpeedRef }}</p>
      <p>X成分位置:{{ xPositionRef }}</p>
      <p>Y成分位置:{{ yPositionRef }}</p>
      <p>仮想日付:{{ dateRef }}</p>
      <CustomButton :label="'計算開始'" @click="calcStart" />
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import CustomButton from '@/Components/CustomButton.vue';
import CustomInput from '@/Components/CustomInput.vue';
import Common from '@/Utils/Common';
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
const heightRef = ref('');
// 初速度
const speedRef = ref('');
// 惑星質量
const massRef = ref(5.972 * 1e24);
// 惑星半径
const radiusRef = ref(6.378 * 1e3);
// 万有引力定数
const universalGravitationRef = ref(6.674 * 1e-20);
// 重力係数
const gravityCoefficientRef = ref(0);
// 日付
const dateRef = ref(0);
// スケール
const scaleRef = ref(30);


// TODO:delete
  /** 定数 */
  const G = 6.674 * 1e-20; // 万有引力定数
  const M = 5.972 * 1e24; // 地球質量
  const R = 6.378 * 1e3; // 地球半径
  const H = 40; // 地球半径

  // 地球の重力係数
  const K = G * M;

  // スケール
  const scale = 30; // [km/px]
// TODO:delete

const calcStart = async () => {

  // 人口衛星クラスインスタンス生成
  let artificialSatelliteObj = []; // 管理用配列
  let artificialSatelliteClass = new ArtificialSatelliteClass({
    r: R,
    deg: 90,
    v: 8.4,
    vdeg: 90,
    k: K,
    scale: scale,
  });
  artificialSatelliteObj.push(artificialSatelliteClass);

  // 惑星クラスインスタンス生成
  let planetClass = new PlanetClass({
      r: R,
      r: M,
      h: H,
      scale: scale,
  });

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

          // 衝突判定
          if (obj.r <= R) {
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
    planetClass.draw(ctx);

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
</script>