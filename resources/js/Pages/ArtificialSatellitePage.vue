<template>
  <div class="container">
    <div class="control_content">
      <p>X成分速度:{{ xSpeedRef }}</p>
      <p>Y成分速度:{{ ySpeedRef }}</p>
      <p>X成分位置:{{ xPositionRef }}</p>
      <p>Y成分位置:{{ yPositionRef }}</p>
      <CustomButton :label="'計算開始'" @click="calcStart" />
    </div>
    <canvas id="canvas" width="640" height="800"></canvas>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import CustomButton from '@/Components/CustomButton.vue';

// const
// 速度管理変数
const xSpeedRef = ref(0);
const ySpeedRef = ref(0);

// 位置管理変数
const xPositionRef = ref(0);
const yPositionRef = ref(0);


const calcStart = async () => {
  const canvas = document.getElementById("canvas"),
    ctx = canvas.getContext("2d");

  const dpr = window.devicePixelRatio || 1, // デバイスピクセルレシオ
    width = canvas.width, // Canvas横幅
    height = canvas.height; // Canvas縦幅

  /** 高解像度対応 */
  // Canvasをピクセル比で拡大
  canvas.width *= dpr;
  canvas.height *= dpr;
  // CSSで元のサイズに戻す
  canvas.style.width = width + "px";
  canvas.style.height = height + "px";

  // 座標系の設定
  ctx.transform(dpr, 0, 0, -dpr, (width * dpr) / 2, (height * dpr) / 2);

  ctx.fillStyle = "#ff0000";

  /** 定数 */
  const G = 6.674 * 1e-20; // 万有引力定数
  const Me = 5.972 * 1e24; // 地球質量
  const Re = 6.378 * 1e3; // 地球半径

  // 地球の重力係数
  const K = G * Me;

  // スケール
  const scale = 30; // [km/px]

  /** ヘルパー関数 */
  // sin
  const sin = (deg) => {
    deg %= 360;

    if (deg === 180) {
      return 0; // Math.sin では誤差が出るので正確な値を返す
    }

    return Math.sin((deg / 180) * Math.PI);
  };
  // cos
  const cos = (deg) => {
    deg %= 360;

    if (deg === 90 || deg === 270) {
      return 0; // Math.cos では誤差が出るので正確な値を返す
    }

    return Math.cos((deg / 180) * Math.PI);
  };

  /** 天体クラス */
  let objectList = []; // 管理用配列

  class AstroObject {
    constructor(attr) {
      // 位置
      this.x = attr.r * cos(attr.deg);
      this.y = attr.r * sin(attr.deg);
      // 速度
      this.vx = attr.v * cos(attr.vdeg + attr.deg);
      this.vy = attr.v * sin(attr.vdeg + attr.deg);

      // 配列に格納
      objectList.push(this);
    }
    draw() {
      // 4px × 4px の正方形で描画する
      ctx.beginPath();
      ctx.rect(this.x / scale - 2, this.y / scale - 2, 4, 4);
      ctx.fill();
    }
    update(dt) {
      const gravity = -K / this.r ** 2; // 現在位置の重力加速度

      this.x += this.vx * dt;
      xPositionRef.value = this.x;
      this.y += this.vy * dt;
      yPositionRef.value = this.y;
      this.vx += gravity * cos(this.deg) * dt;
      xSpeedRef.value = this.vx;
      this.vy += gravity * sin(this.deg) * dt;
      ySpeedRef.value = this.vy;

    }
    // 中心からの距離
    get r() {
      return Math.hypot(this.x, this.y);
    }
    // 角度
    get rad() {
      // x = 0
      if (!this.x) {
        if (!this.y) {
          return 0; // [0, 0] の場合は0を返す
        }
        if (this.y < 0) {
          return Math.PI * 2.5; // 270°
        } else {
          return Math.PI * 0.5; // 90°
        }
      }
      let rad = Math.atan(this.y / this.x); // arctan(y/x) : -π/2 〜 π/2
      if (this.x < 0) {
        // 第二・第三象限
        rad += Math.PI;
      } else if (this.x > 0 && this.y < 0) {
        // 第四象限 : 負数になってるので正数にする
        rad += Math.PI * 2;
      }
      return rad;
    }
    get deg() {
      return (this.rad * 180) / Math.PI;
    }
  }

/** シミュレーター */
const fps = 60, // FPS
  dt = 1 / fps, // 1フレーム当たりの秒数
  speed = 200; // 倍速数

let startTime, // 開始時間
  frameCount = 0; // 経過フレーム数

/** 地球描画 */
const drawEarth = () => {
  ctx.save();

  ctx.fillStyle = "#25A8FF"; // 塗り色を青に
  ctx.beginPath();
  ctx.arc(0, 0, Re / scale, 0, Math.PI * 2); // 中心に円を描く
  ctx.fill();

  ctx.restore();
};

/** tick */
const tick = (time) => {
  if (!startTime) startTime = time; // 初回呼び出し時に開始時間を設定
  const currentFrame = Math.floor(((time - startTime) * fps) / 1000); // 現在の経過フレーム数
  let df = currentFrame - frameCount; // 前回からのフレーム増分

  df *= speed; // 速度分処理回数を増やす

  // フレーム増分だけ更新処理を繰り返す
  while (df) {
    // オブジェクトの更新
    objectList.forEach((obj, i) => {
      if (obj) {
        obj.update(dt);

        // 衝突判定
        if (obj.r <= Re) {
          objectList[i] = null; // 中心星に衝突した物体は取り除く
        }
      }
    });
    df--;
  }

  objectList = objectList.filter(Boolean); // 衝突した物体を配列から除去

  // 画面の消去
  ctx.clearRect(-width / 2, -height / 2, width, height);

  // 再描画
  drawEarth();
  objectList.forEach((obj) => {
    obj.draw();
  });

  frameCount = currentFrame; // フレーム数を更新
  requestAnimationFrame(tick);
};

/** 人工衛星 */
const satellite = new AstroObject({
  r: Re + 10,
  deg: 90,
  v: 8.5,
  vdeg: 90
});

tick(); // 開始
}
</script>