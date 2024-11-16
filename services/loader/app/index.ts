
import loggerConfig from "./config/logger.config";
import worksConfig from "./config/works.config";
import Work from "./lib/Work";
import Worker from "./lib/Worker";
import * as log4js from "log4js";

const pathToSaveHTML =  "/parser/storage/html/";

let logger = log4js.getLogger("index")
logger.level = loggerConfig.level
logger.info("Программа запуущена")

let works:Array<Work> = [];
worksConfig.forEach((config, index) => {
    works.push(new Work(
        config.driverConfig,
        config.timeOutsBeforOpenUrl,
        config.timeOutsAfterSaveStep,
        pathToSaveHTML,
        config.countProcesses,
        config.countUrlsInOneProcess
    ));

    logger.info("Добавлено потоков "+index)
})

let worker = new Worker(works)
worker.run()