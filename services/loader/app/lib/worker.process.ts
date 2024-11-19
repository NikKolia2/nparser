const { workerData, parentPort } = require('worker_threads')
import Work from "./Work";
import * as log4js from "log4js";
import loggerConfig from "../config/logger.config";

const pathToSaveHTML =  "/parser/storage/html/";

process.setMaxListeners(20);

let logger = log4js.getLogger("index")
logger.level = loggerConfig.level


let work = new Work(
    workerData.driverConfig,
    workerData.timeOutsBeforOpenUrl,
    workerData.timeOutsAfterSaveStep,
    pathToSaveHTML,
    workerData.countProcesses,
    workerData.countUrlsInOneProcess
);

logger.info("Процесс создан")


setInterval(async () => {
    work.run()
}, 10);

