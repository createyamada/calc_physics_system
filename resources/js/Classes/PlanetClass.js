import Common from '@/Utils/Common';
import Const from '@/Utils/Const';

/**
 * @param Object attr
 *  -@pamra r 惑星半径
 *  -@param m 惑星質量
 *  -@pamra h 高度
 *  -@param scale スケール
 */ 
class PlanetClass {
    constructor(attr) {
      // 惑星半径
      this.r = attr.r;
      // 惑星質量
      this.m = attr.m;
      // 高度
      this.h = attr.h;
      // スケール
      this.scale = attr.scale;

    }
    // 惑星描画
    draw(ctx) {
      ctx.save();
      ctx.fillStyle = "#25A8FF";
      ctx.beginPath();
      ctx.arc(0, 0, this.r / this.scale, 0, Math.PI * 2);
      ctx.fill();
  
      ctx.restore();
    }
    // 第一宇宙速度を求める
    get firstCosmicVelocity() {
      return this.r * Math.sqrt(this.g / this.r + this.h);
    }
    // 第二宇宙速度を求める
    get SecondCosmicVelocity() {
      return Math.sqrt(2*this.g+r);
    }
    // 惑星重力を計算
    get g() {
      return Const.G * ( this.m / this.r * this.r )
    }
  }

  export default PlanetClass;