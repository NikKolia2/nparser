import Work from "./Work"
import * as workerTh from "worker_threads"
export default class Worker {
    works:Array<Work>

    constructor(works:Array<Work>){
        this.works = works
    }

    run(){
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
            })

            workers.push(worker)
        })
    }
}