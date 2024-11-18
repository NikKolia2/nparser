import ChromeDriver from "./ChromeDriver";
import { ThenableWebDriver } from "selenium-webdriver";
import DriverConfig from "./DriverConfig";
import { error } from "selenium-webdriver";
const SeleniumStealth = require("selenium-stealth/selenium_stealth");
import fs from 'fs'

export default new class HelperServise {
    sleep(ms: number): Promise<void> {
        return new Promise(resolve => global.setTimeout(resolve, ms));
    }

    async getChromedriver(driverConfig: DriverConfig): Promise<ThenableWebDriver> {
        try {
            let builder = await ChromeDriver.init(driverConfig);
            let driver = builder.build();
            //await  driver.createCDPConnection("page")
            // const seleniumStealth = new SeleniumStealth(driver);
            // await seleniumStealth.stealth({
            //     languages: ["ja", "en-US", "en"],
            //     vendor: "Google Inc.",
            //     platform: "Win32",
            //     webglVendor: "Intel Inc.",
            //     renderer: "Intel Iris OpenGL Engine",
            //     fixHairline: true
            // })

            await driver.executeScript("Object.defineProperty(navigator, 'webdriver', {get: () => undefined})");


            return driver;
        } catch (err) {
            throw err;
        }
    }

    deleteFolderRecursive(path: string) {
        if (fs.existsSync(path)) {
            fs.readdirSync(path).forEach((file, index) =>  {
                var curPath = path + "/" + file;
                if (fs.lstatSync(curPath).isDirectory()) { // recurse
                    this.deleteFolderRecursive(curPath);
                } else { // delete file
                    fs.unlinkSync(curPath);
                }
            });

            fs.rmdirSync(path);
        }
    }
}