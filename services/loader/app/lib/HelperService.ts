import ChromeDriver from "./ChromeDriver";
import { ThenableWebDriver } from "selenium-webdriver";
import DriverConfig from "./DriverConfig";
import { error } from "selenium-webdriver";

export default new class HelperServise{
    sleep(ms: number): Promise<void> {
        return new Promise(resolve => global.setTimeout(resolve, ms));
    }

    async getChromedriver(driverConfig:DriverConfig):Promise<ThenableWebDriver>{
        try {
            let builder = await ChromeDriver.init(driverConfig);
            let driver = builder.build();  
            await driver.executeScript("Object.defineProperty(navigator, 'webdriver', {get: () => undefined})");

            return driver;
        }catch(err){
            throw err;
        }  
    }
}