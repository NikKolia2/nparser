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
const js_sha256_1 = require("js-sha256");
const fs_1 = __importDefault(require("fs"));
const dom_parser_1 = require("dom-parser");
class LoadHTML {
    constructor(driver, url) {
        this.driver = driver;
        this.url = url;
    }
    get() {
        return __awaiter(this, void 0, void 0, function* () {
            try {
                yield this.driver.get(this.url);
            }
            catch (err) {
                yield this.driver.quit();
                yield this.sleep(100);
                yield this.get();
            }
        });
    }
    save(pathToSaveHTML_1) {
        return __awaiter(this, arguments, void 0, function* (pathToSaveHTML, overwrite = false) {
            let fullPathSave = pathToSaveHTML + this.getHashURL(this.url) + ".html";
            if (!this.fileExists(fullPathSave) || overwrite) {
                yield this.get();
                let html = yield this.driver.executeScript("return document.getElementsByTagName('html')[0].innerHTML");
                if (!this.validateProductHTML(html)) {
                    return new Promise((resolve, reject) => { resolve(false); });
                }
                return new Promise((resolve, reject) => {
                    fs_1.default.open(fullPathSave, 'w', (err) => {
                        if (err)
                            throw err;
                    });
                    fs_1.default.appendFile(fullPathSave, html, (err) => {
                        if (err)
                            throw err;
                        resolve(true);
                    });
                });
            }
            else {
                return new Promise((resolve, reject) => {
                    resolve(true);
                });
            }
        });
    }
    fileExists(file) {
        return fs_1.default.existsSync(file);
    }
    getHashURL(url) {
        return (0, js_sha256_1.sha256)(url);
    }
    sleep(ms) {
        return new Promise(resolve => global.setTimeout(resolve, ms));
    }
    validateProductHTML(html) {
        var doc = (0, dom_parser_1.parseFromString)(html);
        let h1 = doc.getElementsByTagName("h1");
        if (h1.length != 0 && h1[0].textContent == "This page isn’t working") {
            return false;
        }
        else {
            return true;
        }
    }
}
exports.default = LoadHTML;
