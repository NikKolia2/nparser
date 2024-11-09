"use strict";
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
const process_repository_1 = __importDefault(require("../repositories/process.repository"));
const Loader_1 = __importDefault(require("./Loader"));
class Worker {
    constructor(count, countUrlsInOneProcess = 5) {
        this.processes = [];
        this.countUrlsInOneProcess = countUrlsInOneProcess;
        for (let i = 0; i < count; i++) {
            this.processes.push({ isFree: true });
        }
    }
    run(driverConfig, timeOutsBeforOpenUrl, timeOutsAfterSaveStep, pathToSaveHTML) {
        let seconds = -1;
        setInterval(() => __awaiter(this, void 0, void 0, function* () {
            if (!this.isActiveProcess() || (this.getCurrentSeconds() - seconds) > 10) {
                let process = this.getFreeProcess();
                if (process != null) {
                    process.isFree = false;
                    seconds = this.getCurrentSeconds();
                    let workerData = yield process_repository_1.default.getNewProcesses(this.countUrlsInOneProcess);
                    if (workerData.length) {
                        seconds = this.getCurrentSeconds();
                        let loader = new Loader_1.default(driverConfig, timeOutsBeforOpenUrl, timeOutsAfterSaveStep, pathToSaveHTML);
                        yield loader.loop(workerData).then(success => {
                            process.isFree = true;
                        });
                    }
                }
            }
        }), 10);
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
exports.default = Worker;
