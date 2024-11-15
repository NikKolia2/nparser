"use strict";
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const Render_1 = __importDefault(require("../lib/Render"));
const selenium_webdriver_1 = require("selenium-webdriver");
class ProductRender extends Render_1.default {
    get() {
        return __awaiter(this, void 0, void 0, function* () {
            try {
                yield this.driver.get("https://www.vseinstrumenti.ru/represent/change/?represent_id=-1&represent_type=common");
                yield this.driver.get(this.url);
            }
            catch (err) {
                throw err;
            }
        });
    }
    render() {
        return __awaiter(this, void 0, void 0, function* () {
            try {
                yield this.get();
            }
            catch (err) {
                throw err;
            }
            try {
                let spinner = yield this.driver.wait(selenium_webdriver_1.until.elementLocated(selenium_webdriver_1.By.id('id_spinner')), 1000);
                yield this.driver.wait(selenium_webdriver_1.until.elementIsNotVisible(spinner), 5000);
            }
            catch (err) {
                // this.logger.info(err)
            }
            try {
                let modal = yield this.driver.wait(selenium_webdriver_1.until.elementLocated(selenium_webdriver_1.By.xpath("//*[contains(@class, 'U8AUVKXLgoAaETSMMbwf')]/*[contains(@class, 'b-modal__main')]/button[@type='button']")), 5000);
                modal = yield this.driver.wait(selenium_webdriver_1.until.elementIsVisible(modal), 2000);
                yield modal.click();
            }
            catch (err) {
                //this.logger.info(err)
            }
            let tabCharacters;
            try {
                tabCharacters = yield this.driver.wait(selenium_webdriver_1.until.elementLocated(selenium_webdriver_1.By.xpath("//*[contains(@class, 'js-card-tabs-anchor')]/div[2]")), 20000);
            }
            catch (err) {
                this.logger.info(1);
                throw err;
            }
            try {
                yield this.driver.executeScript(`
                let aboutProduct = document.querySelector('.b-preloader-ajax')?.cloneNode(true);
                if(aboutProduct){
                    aboutProduct.id = 'nparser-about-product';
                    document.body.appendChild(aboutProduct);
                }
            `);
            }
            catch (err) {
                this.logger.info(3);
                throw err;
            }
            try {
                yield tabCharacters.click();
                let characters = yield this.driver.wait(selenium_webdriver_1.until.elementLocated(selenium_webdriver_1.By.xpath("//*[contains(@class, 'card-product-section-main')]//*[contains(@class, 'eGhYU27ERpoFI9K0pm4e')]")), 20000);
                characters = yield this.driver.wait(selenium_webdriver_1.until.elementIsVisible(tabCharacters), 20000);
            }
            catch (err) {
                this.logger.info(4);
                throw err;
            }
        });
    }
    _getHTML(html) {
        // let dom = DomParser.parseFromString(html);
        // let tags = dom.getElementsByTagName("style");
        return html;
    }
}
exports.default = ProductRender;
