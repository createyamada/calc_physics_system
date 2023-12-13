<template>
    <div class="container">
        <h1 class="logo">
            <!-- <img :src="Common.HEADER_LOGO" alt=""> -->
            物理計算システム
        </h1>
        <div>
            <div class="btn-container">
                <button class="logout">ログアウト</button>
            </div>
        </div>
    </div>
</template>

<script setup>
// 連打防止用ログアウトボタンフラグ
// const isLogoutBtnRef = (true);

// ログアウト処理
// const loguot = async() => {

//     // 連打の場合終了
//     if(isLogoutBtnRef.value === false) return;

//     isLogoutBtnRef.value = false;

//     try {
//         await Request.csrf()
//             .then(async(res) => {
//                 await Request.loguot()
//                     .then((res) => {
//                         if(res.status === 200) {
//                             router.push('/login');
//                             return;
//                         }
//                     })
//                     .catch((err) => {
//                         throw err;
//                     })
//             })
//             .catch((err) => {
//                 // 上位に投げる
//                 let message = Common.axiosErrorHandle(err, false);
//                 if (typeof message === 'object') {
//                     if(message.sessionTimeout) {
//                         // セッション切れはログイン
//                         router.push('/login');
//                         return;
//                     }
//                     throw message;
//                 }
//             })

//     } catch (err) {
//         // エラー時の処理

//     } finally {
//         isLogoutBtnRef.value = true;
//     }

// }
</script>

<style scoped>
.container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 70px;
    width: 100%;
    border-bottom: 2px solid;
}

.logo {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 30px;
}

.btn-container {
    display: flex;
    align-items: center;
}

.logout {
    padding: 10px 40px 9px;
    background-color: var(--dark-color);
    color: white;
    border-radius: 30px;
    margin: 30px;
    position: relative;
    z-index: 1;
    transition: 0.3s;
}

.logout:hover {
    color: white;
    font-weight: bold;
    transition: all 0.4s ease-out;
    z-index: 1;
}

.logout::before {
    content: "";
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    z-index: -1;
    background: var(--active-color);
    border-radius: 30px;
    transform-origin: 100% 50%;
    transform: scaleX(0);
    transition: transform ease 0.3s;
}

.logout:hover::before {
    transform-origin: 0% 50%;
    transform: scaleX(1);
}
</style>
