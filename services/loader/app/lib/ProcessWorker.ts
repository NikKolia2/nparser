import { Worker } from "worker_threads"

export default class ProcessWorker {
    isFree:boolean
    worker:Worker|null
    constructor(isFree:boolean){
        this.isFree = isFree
        this.worker = null
    }

    run(){
        this.isFree = false;
        this.worker = new Worker('/parser/services/loader/dist/lib/worker.process.js')
        return this.worker
    }

    stop(){
        this.isFree = true;
        this.worker?.terminate()
    }
}