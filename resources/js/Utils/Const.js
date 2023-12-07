// 万有引力定数
const G = parseFloat(6.674 * 1e-20);

const PLANET_INFO = [
    // 地球
    {
        "name": "地球",
        "i": "0",
        "key": "earth",
        "color": "#25A8FF",
        "m": 5.972 * 1e24,
        "r": 6378.137
    },
    // 水星
    {
        "name": "水星",
        "i": "1",
        "key": "mercury",
        "color": "#25A8FF",
        "m": 0.33 * 1e24,
        "r": 2439.4
    },
    // 金星
    {
        "name": "金星",
        "i": "2",
        "key": "venus",
        "color": "#25A8FF",
        "m": 4.87 * 1e24,
        "r": 6051.8
    },
    // 火星
    {
        "name": "火星",
        "i": "3",
        "key": "mars",
        "color": "#25A8FF",
        "m": 0.64 * 1e24,
        "r": 3396.19
    },
    // 木星
    {
        "name": "木星",
        "i": "4",
        "key": "jupiter",
        "color": "#25A8FF",
        "m": 1898.13 * 1e24,
        "r": 71492
    },
    // 土星
    {
        "name": "土星",
        "i": "5",
        "key": "saturn",
        "color": "#25A8FF",
        "m": 568.32 * 1e24,
        "r": 60268
    },
    // 天王星
    {
        "name": "天王星",
        "i": "6",
        "key": "uranus",        
        "color": "#25A8FF",
        "m": 86.81 * 1e24,
        "r": 25559
    },
    // 海王星
    {
        "name": "海王星",
        "i": "7",
        "key": "neptune",
        "color": "#25A8FF",
        "m": 102.41 * 1e24,
        "r": 24764
    },
    // 太陽
    {
        "name": "太陽",
        "i": "8",
        "key": "sun",
        "color": "#25A8FF",
        "m": 1.988 * 1e30,
        "r": 696000
    },
    // 月
    {
        "name": "月",
        "i": "9",
        "key": "moon",
        "color": "#25A8FF",
        "m": 0.0736 * 1e24,
        "r": 1737.4 
    }
]

export default {
    G,
    PLANET_INFO
}