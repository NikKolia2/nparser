
import loggerConfig from "./config/logger.config";
import worksConfig from "./config/works.config";
//import Work from "./lib/Work";
//import Worker from "./lib/Worker";
import * as log4js from "log4js";
import { Worker } from "worker_threads";
const pathToSaveHTML =  "/parser/storage/html/";

let logger = log4js.getLogger("index")
logger.level = loggerConfig.level
logger.info("Программа запуущена")

// let works:Array<Work> = [];
// worksConfig.forEach((config, index) => {
//     works.push(new Work(
//         config.driverConfig,
//         config.timeOutsBeforOpenUrl,
//         config.timeOutsAfterSaveStep,
//         pathToSaveHTML,
//         config.countProcesses,
//         config.countUrlsInOneProcess
//     ));

//     logger.info("Добавлено потоков "+(index+1))
// })

// let worker = new Worker(works)
// worker.run()

worksConfig.forEach((workerData) => {
    const worker = new Worker('dist/libworker.process.js', { workerData });
    worker.on('message', (message) => {
        logger.info(message)
    });

    worker.on('error', (err) => {
        logger.error(err);
    });

    worker.on('exit', (code) => {
      if (code !== 0)
        logger.error(`Worker stopped with exit code ${code}`);
    })
})