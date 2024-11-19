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
Object.defineProperty(exports, "__esModule", { value: true });
const workerTh = __importStar(require("worker_threads"));
class Worker {
    constructor(works) {
        this.works = works;
    }
    run() {
        let workers = [];
        this.works.forEach((workerData) => {
            const worker = new workerTh.Worker('/parser/services/loader/dist/lib/worker.process.js', { workerData });
            worker.on('message', (message) => {
                console.log(message);
            });
            worker.on('error', (err) => {
                console.log(err);
            });
            worker.on('exit', (code) => {
                if (code !== 0)
                    console.log(`Worker stopped with exit code ${code}`);
            });
            workers.push(worker);
        });
    }
}
exports.default = Worker;
