import { ThenableWebDriver, until, By} from "selenium-webdriver";
import { sha256 } from 'js-sha256';
import fs from 'fs'
import renderConfig from "../config/render.config";
import * as log4js from "log4js";
import loggerConfig from "../config/logger.config";
import RenderInterface from "./Render";

export default class LoadHTML {
    driver: ThenableWebDriver
    url: string
    typeProcessId:any
    render: RenderInterface
    logger:log4js.Logger

    constructor(driver: ThenableWebDriver, url: string, typeProcessId:any) {
        this.driver = driver;
        this.url = url;
        this.typeProcessId = typeProcessId
        this.render = new (renderConfig.get(this.typeProcessId))(driver);
        this.logger = log4js.getLogger("LoadHTML")
        this.logger.level = loggerConfig.level
    }

    async get() {
        try{
            await this.render.get(this.url)
        }catch(err){
            this.logger.error("Ошибка получения станицы "+this.url)
            throw err;
        }   
    }

    async save(pathToSaveHTML: string, overwrite = false) {
        let fullPathSave = pathToSaveHTML + this.getHashURL(this.url) + ".html";
        if (!this.fileExists(fullPathSave) || overwrite) {
            try{
                await this.get();
            }catch(err){
                return false;
            }   

            let html:string
            try{
                html = await this.driver.executeScript("return document.getElementsByTagName('html')[0].innerHTML");
            }catch(err){
                this.logger.error("Ошибка получения содержимого станицы")
                return false;
            }

            let renderHtml = await this.render.render(html)
            if(!renderHtml)
                return false;

            html = renderHtml
        

            fs.openSync(fullPathSave, 'w');
            fs.writeFile(fullPathSave, html, (err) => {
                if (err) throw err; 
            });

            return true;
        } else {
            return true;
        }

    }

    fileExists(file: string): boolean {
        return fs.existsSync(file);
    }

    getHashURL(url: string): string {
        return sha256(url)
    }

    sleep(ms: number): Promise<void> {
        return new Promise(resolve => global.setTimeout(resolve, ms));
    }
}