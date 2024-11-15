import DomParser from 'dom-parser';
import Render from '../lib/Render';
import { By, until, WebElement, WebElementPromise } from 'selenium-webdriver';

export default class ProductRender extends Render {
    async get(): Promise<void> {
        try{
            await this.driver.get("https://www.vseinstrumenti.ru/represent/change/?represent_id=-1&represent_type=common");
            await this.driver.get(this.url)
        }catch(err){
            throw err
        } 
    }

    async render():Promise<void>{
        try{
            await this.get()
        }catch(err){
            throw err;
        }
      
        try {
            let spinner = await this.driver.wait(
                until.elementLocated(By.id('id_spinner')),
                1000
            );

            await this.driver.wait(until.elementIsNotVisible(spinner), 5000)
        }catch(err){
           // this.logger.info(err)
        }
        
        try {
            let modal = await this.driver.wait(
                until.elementLocated(By.xpath("//*[contains(@class, 'U8AUVKXLgoAaETSMMbwf')]/*[contains(@class, 'b-modal__main')]/button[@type='button']")),
                5000
            )
            
            modal = await this.driver.wait(until.elementIsVisible(modal), 2000);
            await modal.click()
        }catch(err){
            this.logger.info(4)
            this.logger.info(err)
            //this.logger.info(err)
        }

        let tabCharacters:WebElement
        try {
            tabCharacters = await this.driver.wait(
                until.elementLocated(By.xpath("//*[contains(@class, 'js-card-tabs-anchor')]/div[2]")), 
                20000
            )
        }catch(err){
            this.logger.info(1);
            throw err
        }

        try {
            await this.driver.executeScript(`
                let aboutProduct = document.querySelector('.b-preloader-ajax')?.cloneNode(true);
                if(aboutProduct){
                    aboutProduct.id = 'nparser-about-product';
                    document.body.appendChild(aboutProduct);
                }
            `)
        }catch(err){
            this.logger.info(3);
            throw err
        }

        try{
            await tabCharacters.click()
            let characters = await this.driver.wait(
                until.elementLocated(By.xpath("//*[contains(@class, 'card-product-section-main')]//*[contains(@class, 'eGhYU27ERpoFI9K0pm4e')]")), 
                20000
            )

            characters = await this.driver.wait(
                until.elementIsVisible(tabCharacters), 
                20000
            )
        }catch(err){
            this.logger.info(4);
            this.logger.info(this.url)
            this.logger.info(err);
           
            throw err
        }
          
     
    }

    _getHTML(html: string): string {
        // let dom = DomParser.parseFromString(html);
        // let tags = dom.getElementsByTagName("style");
      
        return html;
    }
}