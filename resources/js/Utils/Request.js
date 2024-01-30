import axios from "axios";
import { saveAs } from "file-saver";

/**
 * 放物運動計算API
 * @param {*} angle
 * @param {*} speed
 * @param {*} step
 * @param {*} calc_type
 * @returns
 */
const calcParabolicMotion = (
    angle = "",
    speed = "",
    step = "",
    calc_type = ""
) => {
    const params = {
        angle: angle,
        speed: speed,
        step: step,
        calc_type: calc_type,
    };

    return get("api/calcParabolicMotion", params);
};

/**
 * 外部API連携テスト用
 * @returns
 */
const outerApiTest = () => {
    return get("api/outerApiTest");
};

/**
 * 共通get処理
 * @param {*} url
 * @param {*} params
 * @param {*} isGetFile
 * @returns
 */
const get = (url, params = {}, isGetFile = false) => {
    console.log(`${url} called start`);

    let config = {
        responseType: "json",
        params,
        headers: {
            "Content-Type": "application/json",
        },
    };

    if (params.file_type || isGetFile) {
        config = {
            responseType: "blob",
            dataType: "binary",
            params,
        };
    }

    return axios
        .get(url, config)
        .then(function (res) {
            if (params.file_type || isGetFile) {
                console.log(`download start`);
                const file_name = decodeURI(res.headers["fileName"]);
                const url = URL.createObjectURL(new Blob([res.data]));
                saveAs(url, file_name);
                console.log(`download finished`);
            }
            console.log(res);
            return res;
        })
        .catch(function (err) {
            console.log(err);
            throw err;
        })
        .finally(function () {
            console.log(`${url} called finished`);
        });
};

/**
 * 共通post処理
 * @param {*} url
 * @param {*} params
 * @returns
 */
const post = (url, params = {}) => {
    console.log(`${url} called start`);
    return axios
        .post(url, params)
        .then(function (res) {
            console.log(res);
            return res;
        })
        .catch(function (err) {
            console.log(err);
            throw err;
        })
        .finally(function () {
            console.log(`${url} called finished`);
        });
};

export default {
    calcParabolicMotion,
    outerApiTest,
};
