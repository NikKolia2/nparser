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
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const ProcessWorker_1 = __importDefault(require("./ProcessWorker"));
const process_repository_1 = __importDefault(require("../repositories/process.repository"));
const log4js = __importStar(require("log4js"));
const logger_config_1 = __importDefault(require("../config/logger.config"));
class Work {
    constructor(driverConfig, timeOutsBeforOpenUrl, timeOutsAfterSaveStep, pathToSaveHTML, countProcesses = 1, countUrlsInOneProcess = 5) {
        this.secondsFromStartProcess = 0;
        this.processes = [];
        this.driverConfig = driverConfig;
        this.timeOutsAfterSaveStep = timeOutsAfterSaveStep;
        this.timeOutsBeforOpenUrl = timeOutsBeforOpenUrl;
        this.pathToSaveHTML = pathToSaveHTML;
        this.countProcesses = countProcesses;
        this.countUrlsInOneProcess = countUrlsInOneProcess;
        this.logger = log4js.getLogger("Work");
        this.logger.level = logger_config_1.default.level;
        for (let i = 0; i < this.countProcesses; i++) {
            this.processes.push(new ProcessWorker_1.default(true));
        }
    }
    run() {
        return __awaiter(this, void 0, void 0, function* () {
            var _a, _b;
            if (!this.isActiveProcess() || (this.getCurrentSeconds() - this.secondsFromStartProcess) > 15) {
                let process = this.getFreeProcess();
                if (process != null) {
                    process.isFree = false;
                    this.secondsFromStartProcess = this.getCurrentSeconds();
                    let workerData = yield process_repository_1.default.getNewProcesses(this.countUrlsInOneProcess);
                    if (workerData.length) {
                        process.run();
                        yield new Promise((resolve) => {
                            var _a;
                            (_a = process.worker) === null || _a === void 0 ? void 0 : _a.on('online', () => {
                                var _a;
                                (_a = process.worker) === null || _a === void 0 ? void 0 : _a.postMessage({
                                    data: workerData,
                                    driverConfig: this.driverConfig,
                                    timeOutsBeforOpenUrl: this.timeOutsBeforOpenUrl,
                                    timeOutsAfterSaveStep: this.timeOutsAfterSaveStep,
                                    pathToSaveHTML: this.pathToSaveHTML
                                });
                                resolve(true);
                            });
                        });
                        (_a = process.worker) === null || _a === void 0 ? void 0 : _a.on('message', () => {
                            process.isFree = true;
                            this.secondsFromStartProcess = this.getCurrentSeconds();
                        });
                        (_b = process.worker) === null || _b === void 0 ? void 0 : _b.on('error', (err) => {
                            this.logger.error(err);
                        });
                    }
                }
            }
        });
    }
    getFreeProcess() {
        let process = this.processes.find((process) => process.isFree == true);
        if (process != undefined) {
            return process;
        }
        return null;
    }
    isActiveProcess() {
        let process = this.processes.find((process) => process.isFree == false);
        if (process != undefined) {
            return true;
        }
        return false;
    }
    getCurrentSeconds() {
        return Math.round(new Date().getTime() / 1000);
    }
}
exports.default = Work;
