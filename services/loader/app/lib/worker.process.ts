const { workerData, parentPort } = require('worker_threads')

setInterval(async () => {
    workerData.run()
}, 10);

