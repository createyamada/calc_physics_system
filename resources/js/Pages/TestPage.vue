<template>
    <div>
        <button class="get" type="button" @click="callAPI">python呼び出すよ(GET)</button>
    </div>
    <div>
        <button class="post" type="button" @click="callAPI_post">python呼び出すよ(POST)</button>
    </div>
    <div>
        <button class="stop" type="button" @click="stopAPI">呼び出すの止めるよ</button>
    </div>
    <span class="notice" >※結果はコンソール画面を見てね★</span>
</template>

<script setup>

let controller;

async function callAPI() {
    console.log('call python get');
    controller = new AbortController();
    for(let i=0; i<=100; i++) {
        let abortFlag = false;
        await fetch('http://localhost:8000/api/test/test', {
                signal: controller.signal
            })
            .then(result => result.json())
            .then(res => {
                console.log(String(i) + res['test_num'] + res['sleep_time']);
            })
            .catch(err => {
                if (err.name == 'AbortError') {
                    console.log(i + "Aborted!");
                    abortFlag = true;
                } else {
                    console.error(err);
                }
            });
        if(abortFlag) {
            break;
        }
    }
            
}

async function callAPI_post() {
    console.log('call python post');
    controller = new AbortController();
    const data = {
        test_num: 7
    };
    await fetch('http://localhost:8000/api/test/test', {
                method: 'post',
                headers: {
                    'Content-type': 'application.json'
                },
                body: JSON.stringify(data),
                signal: controller.signal
            })
            .then(result => result.json())
            .then(res => {
                console.log(res);
            })
            .catch(err => {
                if (err.name == 'AbortError') {
                    console.log("Aborted!");
                } else {
                    console.error(err);
                }
            });
        }

function stopAPI() {
    controller.abort();
    console.log('stop python');
}
</script>

<style scoped>
.get{
    background-color: palegreen;
}

.post {
    background-color: skyblue;
}

.stop {
    background-color: tomato;
}

.notice {
    color: green;
}

</style>