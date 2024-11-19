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
const logger_config_1 = __importDefault(require("./config/logger.config"));
const works_config_1 = __importDefault(require("./config/works.config"));
//import Work from "./lib/Work";
//import Worker from "./lib/Worker";
const log4js = __importStar(require("log4js"));
const worker_threads_1 = require("worker_threads");
const pathToSaveHTML = "/parser/storage/html/";
let logger = log4js.getLogger("index");
logger.level = logger_config_1.default.level;
logger.info("Программа запуущена");
// let works:Array<Work> = [];
// worksConfig.forEach((config, index) => {
//     works.push(new Work(
//         config.driverConfig,
//         config.timeOutsBeforOpenUrl,
//         config.timeOutsAfterSaveStep,
//         pathToSaveHTML,
//         config.countProcesses,
//         config.countUrlsInOneProcess
//     ));
//     logger.info("Добавлено потоков "+(index+1))
// })
// let worker = new Worker(works)
// worker.run()
works_config_1.default.forEach((workerData) => {
    const worker = new worker_threads_1.Worker('./worker.process.js', { workerData });
    worker.on('message', (message) => {
        logger.info(message);
    });
    worker.on('error', (err) => {
        logger.error(err);
    });
    worker.on('exit', (code) => {
        if (code !== 0)
            logger.error(`Worker stopped with exit code ${code}`);
    });
});
