import processRepository from "../repositories/process.repository";
import LoadHTML from "./LoadHTML";
import Process from "../models/process.model";
import HelperService from "./HelperService";
import { Logs, ThenableWebDriver } from "selenium-webdriver";
import DriverConfig from "./DriverConfig";
import * as log4js from "log4js";
import loggerConfig from "../config/logger.config";

export default class Loader {
    timeOutsBeforOpenUrl: Array<number>
    timeOutsAfterSaveStep: Array<number>
    pathToSaveHTML:string
    driverConfig:DriverConfig
    logger:log4js.Logger

    constructor(
        driverConfig:DriverConfig,
        timeOutsBeforOpenUrl: Array<number>,
        timeOutsAfterSaveStep: Array<number>,
        pathToSaveHTML:string,
    ){
        this.driverConfig = driverConfig
        this.timeOutsAfterSaveStep = timeOutsAfterSaveStep
        this.timeOutsBeforOpenUrl = timeOutsBeforOpenUrl
        this.pathToSaveHTML = pathToSaveHTML
        this.logger = log4js.getLogger("Loader")
        this.logger.level = loggerConfig.level
    }

    async loop(data:Process[]){
        if(data.length){
            this.logger.info("Загрузка данных начата")
            let urls = data.map((item) => item.url);
            
            let validHTML:Array<string> = [];
            let noValidHTML:Array<string> = [];
            let drivers:Array<Promise<{driver:ThenableWebDriver, urlData:Process}>> = [];
            data.forEach(urlData => {
                drivers.push(new Promise((resolve) => {
                    HelperService.getChromedriver(this.driverConfig).then(driver =>{
                        resolve({driver, urlData})
                    }).catch(err => {
                        this.logger.error(err);
                    })
                }))
            });

            let responseConnect = await Promise.all(drivers);
            await processRepository.setStatusDownloading(urls);
            
            let loads:Array<Promise<boolean>> = [];
            responseConnect.forEach(element => {
                loads.push(new Promise((resolve) => {
                    HelperService.sleep(this.getRandomTimeOut(this.timeOutsBeforOpenUrl)).then(() => {
                        let loadHTML = new LoadHTML(element.driver, element.urlData.url, element.urlData.type_id);
                        
                        return loadHTML.save(this.pathToSaveHTML).then(success => {
                            if(success){//Если валидный html то добавляем в массив на парсинг
                                validHTML.push(element.urlData.url)
                            }else{//Если не валидный то снова ставим на загрузку
                                //TODO нужно придумать подсчёт кол-во неудачных загрузок чтобы не было вечной очереди
                                noValidHTML.push(element.urlData.url);
                            }
    
                            element.driver.quit();
                            resolve(true)
                        });
                    })
                }));
            });

            let responseLoads = await Promise.all(loads);

            if(validHTML.length)
                processRepository.setStatusWaitParsingAndFlagDownloaded(validHTML);

            if(noValidHTML.length)
                processRepository.setStatusNewProcess(noValidHTML);

            await HelperService.sleep(this.getRandomTimeOut(this.timeOutsAfterSaveStep));
            
            this.logger.info("Загрузка завершена")
            return true;
        }else{
            return true;
        }
    }

    getRandomTimeOut (list:Array<any>) {
        return list[Math.floor((Math.random()*list.length))];
    }
};