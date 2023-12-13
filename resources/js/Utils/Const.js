// 万有引力定数
const G = parseFloat(6.674 * 1e-20);

const PLANET_INFO = [
    // 地球
    {
        id: "0",
        name: "地球",
        key: "earth",
        color: "#25A8FF",
        m: 5.972 * 1e24,
        r: 6378.137,
        scale: 50,
    },
    // 水星
    {
        id: "1",
        name: "水星",
        key: "mercury",
        color: "#25A8FF",
        m: 0.33 * 1e24,
        r: 2439.4,
        scale: 20,
    },
    // 金星
    {
        id: "2",
        name: "金星",
        key: "venus",
        color: "#25A8FF",
        m: 4.87 * 1e24,
        r: 6051.8,
        scale: 40,
    },
    // 火星
    {
        id: "3",
        name: "火星",
        key: "mars",
        color: "#25A8FF",
        m: 0.64 * 1e24,
        r: 3396.19,
        scale: 30,
    },
    // 木星
    {
        id: "4",
        name: "木星",
        key: "jupiter",
        color: "#25A8FF",
        m: 1898.13 * 1e24,
        r: 71492,
        scale: 400,
    },
    // 土星
    {
        id: "5",
        name: "土星",
        key: "saturn",
        color: "#25A8FF",
        m: 568.32 * 1e24,
        r: 60268,
        scale: 400,
    },
    // 天王星
    {
        id: "6",
        name: "天王星",
        key: "uranus",
        color: "#25A8FF",
        m: 86.81 * 1e24,
        r: 25559,
        scale: 200,
    },
    // 海王星
    {
        id: "7",
        name: "海王星",
        key: "neptune",
        color: "#25A8FF",
        m: 102.41 * 1e24,
        r: 24764,
        scale: 200,
    },
    // 太陽
    {
        id: "8",
        name: "太陽",
        key: "sun",
        color: "#25A8FF",
        m: 1.988 * 1e30,
        r: 696000,
        scale: 6000,
    },
    // 月
    {
        id: "9",
        name: "月",
        key: "moon",
        color: "#25A8FF",
        m: 0.0736 * 1e24,
        r: 1737.4,
        scale: 15,
    },
];

export default {
    G,
    PLANET_INFO,
};
