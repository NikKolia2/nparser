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
const Work_1 = __importDefault(require("./lib/Work"));
const Worker_1 = __importDefault(require("./lib/Worker"));
const log4js = __importStar(require("log4js"));
const pathToSaveHTML = "/parser/storage/html/";
const EventEmitter = require('events');
const emitter = new EventEmitter();
emitter.setMaxListeners(30);
let logger = log4js.getLogger("index");
logger.level = logger_config_1.default.level;
logger.info("Программа запуущена");
let works = [];
works_config_1.default.forEach((config, index) => {
    works.push(new Work_1.default(config.driverConfig, config.timeOutsBeforOpenUrl, config.timeOutsAfterSaveStep, pathToSaveHTML, config.countProcesses, config.countUrlsInOneProcess));
    logger.info("Добавлено потоков " + index);
});
let worker = new Worker_1.default(works);
worker.run();
