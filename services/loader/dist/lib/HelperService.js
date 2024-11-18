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
const ChromeDriver_1 = __importDefault(require("./ChromeDriver"));
const SeleniumStealth = require("selenium-stealth/selenium_stealth");
const fs_1 = __importDefault(require("fs"));
const rimraf_1 = require("rimraf");
exports.default = new class HelperServise {
    sleep(ms) {
        return new Promise(resolve => global.setTimeout(resolve, ms));
    }
    getChromedriver(driverConfig) {
        return __awaiter(this, void 0, void 0, function* () {
            try {
                let builder = yield ChromeDriver_1.default.init(driverConfig);
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
                yield driver.executeScript("Object.defineProperty(navigator, 'webdriver', {get: () => undefined})");
                return driver;
            }
            catch (err) {
                throw err;
            }
        });
    }
    deleteFolderRecursive(path) {
        if (fs_1.default.existsSync(path)) {
            (0, rimraf_1.rimrafSync)(path);
        }
    }
};
