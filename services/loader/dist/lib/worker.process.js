"use strict";
var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    var desc = Object.getOwnPropertyDescriptor(m, k);
    if (!desc || ("get" in desc ? !m.__esModule : desc.writable || desc.configurable)) {
      desc = { enumerable: true, get: function() { return m[k]; } };
    }
    Object.defineProperty(o, k2, desc);
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __setModuleDefault = (this && this.__setModuleDefault) || (Object.create ? (function(o, v) {
    Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
    o["default"] = v;
});
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (k !== "default" && Object.prototype.hasOwnProperty.call(mod, k)) __createBinding(result, mod, k);
    __setModuleDefault(result, mod);
    return result;
};
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const worker_threads_1 = require("worker_threads");
const log4js = __importStar(require("log4js"));
const logger_config_1 = __importDefault(require("../config/logger.config"));
const Loader_1 = __importDefault(require("./Loader"));
let logger = log4js.getLogger("worker.process");
logger.level = logger_config_1.default.level;
worker_threads_1.parentPort === null || worker_threads_1.parentPort === void 0 ? void 0 : worker_threads_1.parentPort.on('message', (workerData) => {
    let loader = new Loader_1.default(workerData.driverConfig, workerData.timeOutsBeforOpenUrl, workerData.timeOutsAfterSaveStep, workerData.pathToSaveHTML);
    loader.loop(workerData).then(success => {
        worker_threads_1.parentPort === null || worker_threads_1.parentPort === void 0 ? void 0 : worker_threads_1.parentPort.postMessage(success);
        process.exit();
    });
    logger.info("Процесс создан");
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
