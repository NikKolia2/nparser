"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const worker_threads_1 = require("worker_threads");
const Loader_1 = __importDefault(require("./Loader"));
let loader = new Loader_1.default(worker_threads_1.workerData.driverConfig, worker_threads_1.workerData.timeOutsBeforOpenUrl, worker_threads_1.workerData.timeOutsAfterSaveStep, worker_threads_1.workerData.pathToSaveHTML);
loader.loop(worker_threads_1.workerData).then(success => {
    worker_threads_1.parentPort === null || worker_threads_1.parentPort === void 0 ? void 0 : worker_threads_1.parentPort.postMessage(success);
    process.exit();
});
// const pathToSaveHTML =  "/parser/storage/html/";
// process.setMaxListeners(20);
// let logger = log4js.getLogger("index")
// logger.level = loggerConfig.level
// let work = new Work(
//     workerData.driverConfig,
//     workerData.timeOutsBeforOpenUrl,
//     workerData.timeOutsAfterSaveStep,
//     pathToSaveHTML,
//     workerData.countProcesses,
//     workerData.countUrlsInOneProcess
// );
// logger.info("Процесс создан")
// setInterval(async () => {
//     work.run()
// }, 10);
