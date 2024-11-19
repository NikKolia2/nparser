"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const worker_threads_1 = require("worker_threads");
class ProcessWorker {
    constructor(isFree) {
        this.isFree = isFree;
        this.worker = null;
    }
    run() {
        this.isFree = false;
        this.worker = new worker_threads_1.Worker('/parser/services/loader/dist/lib/worker.process.js');
        return this.worker;
    }
    stop() {
        var _a;
        this.isFree = true;
        (_a = this.worker) === null || _a === void 0 ? void 0 : _a.terminate();
    }
}
exports.default = ProcessWorker;
