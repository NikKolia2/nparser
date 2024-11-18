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
const process_repository_1 = __importDefault(require("../repositories/process.repository"));
const LoadHTML_1 = __importDefault(require("./LoadHTML"));
const HelperService_1 = __importDefault(require("./HelperService"));
const log4js = __importStar(require("log4js"));
const logger_config_1 = __importDefault(require("../config/logger.config"));
const fs_1 = __importDefault(require("fs"));
class Loader {
    constructor(driverConfig, timeOutsBeforOpenUrl, timeOutsAfterSaveStep, pathToSaveHTML) {
        this.driverConfig = driverConfig;
        this.timeOutsAfterSaveStep = timeOutsAfterSaveStep;
        this.timeOutsBeforOpenUrl = timeOutsBeforOpenUrl;
        this.pathToSaveHTML = pathToSaveHTML;
        this.logger = log4js.getLogger("Loader");
        this.logger.level = logger_config_1.default.level;
    }
    loop(data) {
        return __awaiter(this, void 0, void 0, function* () {
            const EventEmitter = require('events');
            const emitter = new EventEmitter();
            emitter.setMaxListeners(30);
            if (data.length) {
                let urls = data.map((item) => item.url);
                let validHTML = [];
                let noValidHTML = [];
                let drivers = [];
                data.forEach(urlData => {
                    drivers.push(new Promise((resolve) => {
                        HelperService_1.default.getChromedriver(this.driverConfig).then(driver => {
                            resolve({ driver, urlData });
                        }).catch(err => {
                            //this.logger.error(err);
                        });
                    }));
                });
                let responseConnect = yield Promise.all(drivers);
                yield process_repository_1.default.setStatusDownloading(urls);
                let loads = [];
                let lastSeconds = 0;
                responseConnect.forEach((element, index) => {
                    loads.push(new Promise((resolve) => {
                        let timeout;
                        if (index == 0) {
                            timeout = 0;
                        }
                        else {
                            timeout = this.getRandomTimeOut(this.timeOutsBeforOpenUrl) + lastSeconds;
                            lastSeconds = timeout;
                        }
                        HelperService_1.default.sleep(timeout).then(() => {
                            let loadHTML = new LoadHTML_1.default(element.driver, element.urlData.url, element.urlData.type_id);
                            return loadHTML.save(this.pathToSaveHTML).then(success => {
                                if (success) { //Если валидный html то добавляем в массив на парсинг
                                    validHTML.push(element.urlData.url);
                                }
                                else { //Если не валидный то снова ставим на загрузку
                                    //TODO нужно придумать подсчёт кол-во неудачных загрузок чтобы не было вечной очереди
                                    noValidHTML.push(element.urlData.url);
                                }
                                element.driver.quit().then(() => {
                                    let links = fs_1.default.globSync('/tmp/.org.chromium.Chromium.*');
                                    links.forEach(link => {
                                        fs_1.default.rmSync(link, { recursive: true, force: true });
                                    });
                                    resolve(true);
                                });
                            });
                        });
                    }));
                });
                let responseLoads = yield Promise.all(loads);
                if (validHTML.length)
                    process_repository_1.default.setStatusWaitParsingAndFlagDownloaded(validHTML);
                if (noValidHTML.length)
                    process_repository_1.default.setStatusNewProcess(noValidHTML);
                yield HelperService_1.default.sleep(this.getRandomTimeOut(this.timeOutsAfterSaveStep));
                return true;
            }
            else {
                return true;
            }
        });
    }
    getRandomTimeOut(list) {
        return list[Math.floor((Math.random() * list.length))];
    }
}
exports.default = Loader;
;
