"use strict";
var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    var desc = Object.getOwnPropertyDescriptor(m, k);
    if (!desc || ("get" in desc ? !m.__esModule : desc.writable || desc.configurable)) {
      desc = { enumerable: true, get: function() { return m[k]; } };
    }
    Object.defineProperty(o, k2, desc);
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __setModuleDefault = (this && this.__setModuleDefault) || (Object.create ? (function(o, v) {
    Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
    o["default"] = v;
});
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (k !== "default" && Object.prototype.hasOwnProperty.call(mod, k)) __createBinding(result, mod, k);
    __setModuleDefault(result, mod);
    return result;
};
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
const render_config_1 = __importDefault(require("../config/render.config"));
const log4js = __importStar(require("log4js"));
const logger_config_1 = __importDefault(require("../config/logger.config"));
class LoadHTML {
    constructor(driver, url, typeProcessId) {
        this.driver = driver;
        this.url = url;
        this.typeProcessId = typeProcessId;
        this.render = new (render_config_1.default.get(this.typeProcessId))(driver, url);
        this.logger = log4js.getLogger("LoadHTML");
        this.logger.level = logger_config_1.default.level;
    }
    save(pathToSaveHTML_1) {
        return __awaiter(this, arguments, void 0, function* (pathToSaveHTML, overwrite = false) {
            let fullPathSave = pathToSaveHTML + this.getHashURL(this.url) + ".html";
            if (!this.fileExists(fullPathSave) || overwrite) {
                try {
                    yield this.render.render();
                }
                catch (err) {
                    var base64Data = (yield this.driver.takeScreenshot()).replace(/^data:image\/png;base64,/, "");
                    fs_1.default.writeFile("/parser/storage/error/" + this.getHashURL(this.url) + ".png", base64Data, 'base64', function (err) {
                        console.log(err);
                    });
                    let html = yield this.render.getHTML();
                    if (html)
                        fs_1.default.writeFile("/parser/storage/error/" + this.getHashURL(this.url) + ".html", this.render.getHTML(), function (err) {
                            console.log(err);
                        });
                    return false;
                }
                let renderHtml = yield this.render.getHTML();
                if (!renderHtml)
                    return false;
                let html = renderHtml;
                fs_1.default.openSync(fullPathSave, 'w');
                fs_1.default.writeFile(fullPathSave, html, (err) => {
                    if (err)
                        throw err;
                });
                return true;
            }
            else {
                return true;
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
}
exports.default = LoadHTML;
