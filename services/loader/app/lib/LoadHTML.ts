import { ThenableWebDriver, until, By} from "selenium-webdriver";
import { sha256 } from 'js-sha256';
import fs from 'fs'
import renderConfig from "../config/render.config";
import Render from "./Render";
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
        this.render = new (renderConfig.get(this.typeProcessId))(driver, url);
        this.logger = log4js.getLogger("LoadHTML")
        this.logger.level = loggerConfig.level
    }

    async save(pathToSaveHTML: string, overwrite = false) {
        let fullPathSave = pathToSaveHTML + this.getHashURL(this.url) + ".html";
        if (!this.fileExists(fullPathSave) || overwrite) {
            try{
                await this.render.render()
            }catch(err){
                fs.openSync("/parser/storage/error/"+this.getHashURL(this.url) + ".html", 'w');
                let renderHtml:string|null = await this.render.getHTML();
                let html:string;
                if(!renderHtml)
                    html = "";
                else
                    html = renderHtml

                fs.writeFile("/parser/storage/error/"+this.getHashURL(this.url) + ".html", html, (err) => {
                    if (err) throw err; 
                });
                
                var base64Data = await this.driver.takeScreenshot();
                base64Data = base64Data.replace(/^data:image\/png;base64,/, "");

                fs.writeFile("out.png", base64Data, 'base64', function(err) {
                console.log(err);
                });
                return false
            }

            let renderHtml:string|null = await this.render.getHTML();
            if(!renderHtml)
                return false
            
            let html:string = renderHtml
        
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