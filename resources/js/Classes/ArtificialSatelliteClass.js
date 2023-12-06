import Common from '@/Utils/Common';


/**
 * @param Object attr
 *  -@pamra r 惑星半径
 *  -@param deg 角度
 *  -@param v 初速度
 *  -@param vdeg 角度
 *  -@param scale スケール
 */ 
class ArtificialSatelliteClass {
    constructor(attr) {
      // 位置
      this.x = attr.r * Common.cos(attr.deg);
      this.y = attr.r * Common.sin(attr.deg);
      // 速度
      this.vx = attr.v * Common.cos(attr.vdeg + attr.deg);
      this.vy = attr.v * Common.sin(attr.vdeg + attr.deg);

      // 重力係数
      this.k = attr.k;

      // スケール
      this.scale = attr.scale;
    }
    draw(ctx) {
        // 座標系の設定
      ctx.fillStyle = "#ff0000";
      // 4px × 4px の正方形で描画する
      ctx.beginPath();
      ctx.rect(this.x / this.scale - 2, this.y / this.scale - 2, 4, 4);
      ctx.fill();
    }
    update(dt) {
      // 現在位置の重力加速度
      const gravity = -this.k / this.r ** 2; 

      this.x += this.vx * dt;
      this.y += this.vy * dt;
      this.vx += gravity * Common.cos(this.deg) * dt;
      this.vy += gravity * Common.sin(this.deg) * dt;

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

  export default ArtificialSatelliteClass;