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
class ProductRender extends Render_1.default {
    static get(driver, url) {
        return __awaiter(this, void 0, void 0, function* () {
            try {
                yield driver.get("https://www.vseinstrumenti.ru/represent/change/?represent_id=-1&represent_type=common");
                yield driver.get(url);
            }
            catch (err) {
                throw err;
            }
        });
    }
    static validate(html) {
        // var doc = parseFromString(html);
        // let h1 = doc.getElementsByTagName("h1");
        // if(h1.length != 0){
        //     let span = h1[0].getElementsByTagName("span")
        //     if((span.length && span[0].textContent == "This page isn’t working" ))
        //         return false;
        // }
        return true;
    }
}
exports.default = ProductRender;
