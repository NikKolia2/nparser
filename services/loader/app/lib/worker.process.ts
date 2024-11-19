import { workerData, parentPort } from  'worker_threads'
import Work from "./Work";
import * as log4js from "log4js";
import loggerConfig from "../config/logger.config";
import Loader from "./Loader";

let loader = new Loader(workerData.driverConfig, workerData.timeOutsBeforOpenUrl, workerData.timeOutsAfterSaveStep, workerData.pathToSaveHTML);
loader.loop(workerData).then(success => {   
    parentPort?.postMessage(success)
    process.exit();
})

// const pathToSaveHTML =  "/parser/storage/html/";

// process.setMaxListeners(20);

// let logger = log4js.getLogger("index")
// logger.level = loggerConfig.level


// let work = new Work(
//     workerData.driverConfig,
//     workerData.timeOutsBeforOpenUrl,
//     workerData.timeOutsAfterSaveStep,
//     pathToSaveHTML,
//     workerData.countProcesses,
//     workerData.countUrlsInOneProcess
// );

// logger.info("Процесс создан")


// setInterval(async () => {
//     work.run()
// }, 10);

