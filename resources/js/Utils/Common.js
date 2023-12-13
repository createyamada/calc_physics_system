/**
 * 共通エラーハンドル
 * @param {*} err
 * @param {*} requiredAuth
 * @returns
 */
const axiosErrorHandle = (err, requiredAuth = true) => {
    console.log(err);
    let msg = "";

    // axios以外のエラー
    if (!err.message) return "例外が発生しました。";

    // status_code = 500
    if (err.response.status === 500) msg = err.response.data.message;

    // status_code = 422
    if (err.response.status === 422) {
        Object.values(err.response.data.errors).forEach((val) => {
            val.forEach((v) => {
                msg += v + "\n";
            });
        });
    }

    // session_timeout
    // if(requiredAuth && (err.response.status === 401 || err.response.status === 419)) return { sessionTimeout :true };

    // status_code = 401
    if (err.response.status === 401) msg = err.response.data.message;

    // 例外
    if (msg === "") msg = "例外が発生しました。";

    return msg;
};

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

export default {
    axiosErrorHandle,
    sin,
    cos,
};
