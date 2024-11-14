import ProcessWorker from "./ProcessWorker";
import processRepository from "../repositories/process.repository";
import Loader from "./Loader";
import DriverConfig from "./DriverConfig";

export default class Worker {
    processes:Array<ProcessWorker> = []
    countUrlsInOneProcess:number
    
    constructor(count:number, countUrlsInOneProcess:number = 5){
        this.countUrlsInOneProcess = countUrlsInOneProcess

        for(let i = 0; i < count; i++){
            this.processes.push({isFree:true})
        }
    }

    run(driverConfig:DriverConfig, timeOutsBeforOpenUrl:Array<number>, timeOutsAfterSaveStep:Array<number>, pathToSaveHTML:string){
        let seconds = -1;
        setInterval(async () => {
            if(!this.isActiveProcess() || (this.getCurrentSeconds() - seconds) > 10){  
                let process = this.getFreeProcess()
                if(process != null){
                    process.isFree = false;
                    seconds = this.getCurrentSeconds();
                    let workerData = await processRepository.getNewProcesses(this.countUrlsInOneProcess);
                    
                    if(workerData.length){
                        seconds = this.getCurrentSeconds();
                        
                        let loader = new Loader(driverConfig,timeOutsBeforOpenUrl, timeOutsAfterSaveStep, pathToSaveHTML);
                        await loader.loop(workerData).then(success => {   
                            process.isFree = true;  
                        })
                    }
                }
            }
        }, 10);
    }

    getFreeProcess(){
        let process = this.processes.find((process) => process.isFree == true);
        if(process != undefined){
            return process;
        }

        return null;
    }

    isActiveProcess(){
        let process = this.processes.find((process) => process.isFree == false);
        if(process != undefined){
            return true;
        }

        return false;
    }
    getCurrentSeconds(){
        return Math.round(new Date().getTime() / 1000)
    }
}