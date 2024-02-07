import { createRouter, createWebHistory } from "vue-router";
import MainPage from "@/Pages/MainPage.vue";
import TopPage from "@/Pages/TopPage.vue";
import ParabolicMotionPage from "@/Pages/ParabolicMotionPage.vue";
import ArtificialSatelliteMotionPage from "@/Pages/ArtificialSatelliteMotionPage.vue";
import KeplerProblem from "@/Pages/KeplerProblem.vue";

const routes = [
    {
        path: "/",
        name: "MainPage",
        component: MainPage,
        meta: {
            title: "トップページ",
            requiredAuth: true,
        },

        children: [
            {
                path: "/",
                name: "topPage",
                component: TopPage,
                meta: {
                    title: "物理計算サイト",
                },
            },
            {
                path: "/parabolicMotion",
                name: "parabolicMotion",
                component: ParabolicMotionPage,
                meta: {
                    title: "放物運動計算",
                },
            },
            {
                path: "/ArtificialSatelliteMotionPage",
                name: "ArtificialSatelliteMotionPage",
                component: ArtificialSatelliteMotionPage,
                meta: {
                    title: "人口衛星シミュレーション",
                },
            },
            {
                path: "/KeplerProblem",
                name: "KeplerProblem",
                component: KeplerProblem,
                meta: {
                    title: "ケプラー問題",
                },
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// router.beforeEach(async(to , from ,next) =>  {
// 指定のルートにアクセスする前の処理
// });

router.afterEach((to, from) => {
    document.title = to.meta.title;
});

export default router;
