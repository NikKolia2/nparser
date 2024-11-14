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
const process_repository_1 = __importDefault(require("./repositories/process.repository"));
const LoadHTML_1 = __importDefault(require("./LoadHTML"));
const HelperService_1 = __importDefault(require("./HelperService"));
class Loader {
    constructor(timeOutsBeforOpenUrl, timeOutsAfterSaveStep, pathToSaveHTML) {
        this.timeOutsAfterSaveStep = timeOutsAfterSaveStep;
        this.timeOutsBeforOpenUrl = timeOutsBeforOpenUrl;
        this.pathToSaveHTML = pathToSaveHTML;
    }
    loop(data) {
        return __awaiter(this, void 0, void 0, function* () {
            if (data.length) {
                let urls = data.map((item) => item.url);
                process_repository_1.default.setStatusDownloading(urls);
                return (() => {
                    return new Promise((resolve, reject) => {
                        let process = [];
                        let validHTML = [];
                        let noValidHTML = [];
                        data.forEach(urlData => {
                            process.push(1);
                            HelperService_1.default.getChromedriver().then(driver => {
                                HelperService_1.default.sleep(this.getRandomTimeOut(this.timeOutsBeforOpenUrl)).then(() => {
                                    let loadHTML = new LoadHTML_1.default(driver, urlData.url);
                                    loadHTML.save(this.pathToSaveHTML).then(success => {
                                        if (success) { //Если валидный html то добавляем в массив на парсинг
                                            validHTML.push(urlData.url);
                                        }
                                        else { //Если не валидный то снова ставим на загрузку
                                            //TODO нужно придумать подсчёт кол-во неудачных загрузок чтобы не было вечной очереди
                                            noValidHTML.push(urlData.url);
                                        }
                                        process.splice(0, 1);
                                        driver.close();
                                        if (!process.length) {
                                            resolve({ validHTML, noValidHTML });
                                        }
                                    });
                                });
                            });
                        });
                    });
                })().then(data => {
                    process_repository_1.default.setStatusWaitParsingAndFlagDownloaded(data.validHTML);
                    // processRepository.setStatusNewProcess(data.noValidHTML);
                    return HelperService_1.default.sleep(this.getRandomTimeOut(this.timeOutsAfterSaveStep)).then(() => {
                        return true;
                    });
                });
            }
            else {
                return new Promise((resolve, reject) => { resolve(false); });
            }
        });
    }
    getRandomTimeOut(list) {
        return list[Math.floor((Math.random() * list.length))];
    }
}
exports.default = Loader;
;
