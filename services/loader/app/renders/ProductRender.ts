import Render from '../lib/Render';
import { By, until } from 'selenium-webdriver';

export default class ProductRender extends Render {
    async get(url:string): Promise<void> {
        try{
            await this.driver.get(url)
        }catch(err){
            throw err;
        } 
    }

    async render(html: string): Promise<string | null> {
        try {
            let spinner = this.driver.findElement(By.id('id_spinner'))
            await this.driver.wait(until.elementIsNotVisible(spinner), 20000)
        }catch(err){
            this.logger.info(err)
        }
        
        try {
            let modal = await this.driver.wait(
                until.elementLocated(By.xpath("//*[contains(@class, 'U8AUVKXLgoAaETSMMbwf')]/*[contains(@class, 'b-modal__main')]/button[@type='button']")),
                20000
            )
            
            modal = await this.driver.wait(until.elementIsVisible(modal), 2000);
            await modal.click()
        }catch(err){
            this.logger.info(err)
        }

        try {
            let tabCharacters = await this.driver.wait(
                until.elementLocated(By.xpath("//*[contains(@class, 'js-card-tabs-anchor')]/div[2]")), 
                20000
            )

            tabCharacters = await this.driver.wait(
                until.elementIsVisible(tabCharacters), 
                20000
            )

            await tabCharacters.click()
            let characters = await this.driver.wait(
                until.elementLocated(By.xpath("//*[contains(@class, 'card-product-section-main')]//*[contains(@class, 'eGhYU27ERpoFI9K0pm4e')]")), 
                20000
            )

            characters = await this.driver.wait(
                until.elementIsVisible(tabCharacters), 
                20000
            )

            this.driver.executeScript(`
                let aboutProduct = document.querySelector('.b-preloader-ajax')?.cloneNode(true);
                    aboutProduct.id = 'nparser-about-product';
                    document.body.appendChild(aboutProduct);
            `)
        }catch(err){
            this.logger.info(err)
            return null
        }

        return html
    }
}