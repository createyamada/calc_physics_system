


/**
 * 共通エラーハンドル
 * @param {*} err 
 * @param {*} requiredAuth 
 * @returns 
 */
const axiosErrorHandle = (err , requiredAuth=true) => {
    console.log(err);
    let msg = '';

    // axios以外のエラー
    if(!err.message) return '例外が発生しました。';

    // status_code = 500
    if(err.response.status === 500) msg = err.response.data.message;

    // status_code = 422
    if(err.response.status === 422) {
        Object.values(err.response.data.errors).forEach((val) => {
            val.forEach((v) => {
                msg += v + '\n';
            });
        });
    }

    // session_timeout
    // if(requiredAuth && (err.response.status === 401 || err.response.status === 419)) return { sessionTimeout :true };

    // status_code = 401
    if (err.response.status === 401) msg = err.response.data.message;

    // 例外
    if (msg ==='') msg = '例外が発生しました。'

    return msg;

}

export default {
    axiosErrorHandle
}