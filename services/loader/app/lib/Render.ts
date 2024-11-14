import { ThenableWebDriver } from "selenium-webdriver";
import * as log4js from "log4js";
import loggerConfig from "../config/logger.config";

export default interface RenderInterface {
    get():Promise<void>;
    validate(html:string):boolean;
    render():Promise<void>;
    getHTML():Promise<string | null>;
}

export default class Render implements RenderInterface {
    driver:ThenableWebDriver
    logger:log4js.Logger
    loggerName: string = "Render"
    url:string

    constructor(driver:ThenableWebDriver, url:string){
        this.driver = driver
        this.url = url
        this.logger = log4js.getLogger(this.loggerName)
        this.logger.level = loggerConfig.level
    }

    async get(){
        try{
            await this.driver.get(this.url)
        }catch(err){
            this.logger.error("Ошибка получения станицы "+this.url)
            throw err
        }
    }

    validate(html:string):boolean{
        return true;
    }

    async render():Promise<void>{
        try{
            await this.driver.get(this.url)
        }catch(err){
            throw err
        }
    }

    async getHTML():Promise<string | null>{
        let html:string
        
        try{
            html = await this.driver.executeScript("return document.getElementsByTagName('html')[0].innerHTML");
        }catch(err){
            return null;
        }
      
        if(!this.validate(html)){
            return null;
        }

        return this._getHTML(html)
    }

    _getHTML(html:string):string{
        return html
    }
}