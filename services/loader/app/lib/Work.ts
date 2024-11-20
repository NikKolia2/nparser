import DriverConfig from "./DriverConfig";
import ProcessWorker from "./ProcessWorker";
import processRepository from "../repositories/process.repository";
import Loader from "./Loader";
import * as log4js from "log4js";
import loggerConfig from "../config/logger.config";

export default class Work {
    driverConfig:DriverConfig
    timeOutsBeforOpenUrl: Array<number>
    timeOutsAfterSaveStep: Array<number>
    pathToSaveHTML:string
    countProcesses:number
    countUrlsInOneProcess:number
    secondsFromStartProcess:number = 0
    processes:Array<ProcessWorker> = []
    logger:log4js.Logger

    constructor(
        driverConfig:DriverConfig,
        timeOutsBeforOpenUrl: Array<number>,
        timeOutsAfterSaveStep: Array<number>,
        pathToSaveHTML:string,
        countProcesses:number = 1,
        countUrlsInOneProcess:number = 5,
    ){
        this.driverConfig = driverConfig
        this.timeOutsAfterSaveStep = timeOutsAfterSaveStep
        this.timeOutsBeforOpenUrl = timeOutsBeforOpenUrl
        this.pathToSaveHTML = pathToSaveHTML
        this.countProcesses = countProcesses
        this.countUrlsInOneProcess = countUrlsInOneProcess
        this.logger = log4js.getLogger("Work")
        this.logger.level = loggerConfig.level
        for(let i = 0; i < this.countProcesses; i++){
            this.processes.push(new ProcessWorker(true));
        }
    }

    async run(){
        if(!this.isActiveProcess() || (this.getCurrentSeconds() - this.secondsFromStartProcess) > 5){  
            let process = this.getFreeProcess()
            if(process != null){
                process.isFree = false;
                this.secondsFromStartProcess = this.getCurrentSeconds();
                
                let workerData = await processRepository.getNewProcesses(this.countUrlsInOneProcess);
                
                if(workerData.length){    
                    process.run();
                    
                    await new Promise((resolve) => {
                        process.worker?.on('online', () => {
                            process.worker?.postMessage({
                                data:workerData,
                                driverConfig:this.driverConfig,
                                timeOutsBeforOpenUrl:this.timeOutsBeforOpenUrl,
                                timeOutsAfterSaveStep: this.timeOutsAfterSaveStep,
                                pathToSaveHTML:this.pathToSaveHTML
                            });

                            resolve(true);
                        });
                    })

                    process.worker?.on('message', () => {
                        process.isFree = true;
                    });

                    process.worker?.on('error', (err) => {
                        this.logger.error(err)
                    });
                }
               
            }
        }
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