import { ThenableWebDriver } from "selenium-webdriver";
import * as log4js from "log4js";
import loggerConfig from "../config/logger.config";

export default interface RenderInterface {
    get(url:string):void;
    validate(html:string):boolean;
    render(html:string):Promise<string|null>
}

export default class Render implements RenderInterface {
    driver:ThenableWebDriver
    logger:log4js.Logger
    loggerName: string = "Render"

    constructor(driver:ThenableWebDriver){
        this.driver = driver
        this.logger = log4js.getLogger(this.loggerName)
        this.logger.level = loggerConfig.level
    }

    async get(url:string){
        try{
            await this.driver.get(url)
        }catch(err){
            throw err;
        } 
    }

    validate(html:string):boolean{
        return true;
    }

    async render(html:string):Promise<string|null>{
        if(!this.validate(html)){
            return null;
        }

        return html;
    }
}