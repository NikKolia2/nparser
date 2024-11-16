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
const selenium_webdriver_1 = require("selenium-webdriver");
const chrome_1 = require("selenium-webdriver/chrome");
const fs_1 = __importDefault(require("fs"));
const zl = __importStar(require("zip-lib"));
class ChromeDriver extends selenium_webdriver_1.Builder {
    constructor() {
        super();
        this.options = new chrome_1.Options();
        //this.options.setPageLoadStrategy(PageLoadStrategy.NONE);
        //  this.
    }
    static init() {
        return __awaiter(this, arguments, void 0, function* (config = null) {
            let builder = new ChromeDriver();
            if (config === null || config === void 0 ? void 0 : config.proxy) {
                yield builder.addExtProxy(config.proxy);
            }
            if (config === null || config === void 0 ? void 0 : config.capabilities) {
                builder.withCapabilities(config.capabilities);
            }
            builder.forBrowser(selenium_webdriver_1.Browser.CHROME);
            if (config === null || config === void 0 ? void 0 : config.serverUrl) {
                // builder.usingServer(config.serverUrl);
            }
            if (config === null || config === void 0 ? void 0 : config.args.length) {
                builder.options.addArguments(...config.args);
            }
            return builder;
        });
    }
    setChromeOptions(options) {
        this.options.merge(options);
        super.setChromeOptions(options);
        return this;
    }
    addExtProxy(proxy) {
        let manifest_json = {
            "version": "1.0.0",
            "manifest_version": 2,
            "name": "Chrome Proxy",
            "permissions": [
                "proxy",
                "tabs",
                "unlimitedStorage",
                "storage",
                "<all_urls>",
                "webRequest",
                "webRequestBlocking"
            ],
            "background": {
                "scripts": ["background.js"]
            },
            "minimum_chrome_version": "22.0.0"
        };
        let background_js = `
        var config = {
                mode: "fixed_servers",
                rules: {
                singleProxy: {
                    scheme: "http",
                    host: "${proxy.host}",
                    port: parseInt(${proxy.port})
                },
                bypassList: ["localhost"]
                }
            };
    
        chrome.proxy.settings.set({value: config, scope: "regular"}, function() {});
    
        function callbackFn(details) {
            return {
                authCredentials: {
                    username: "${proxy.username}",
                    password: "${proxy.password}"
                }
            };
        }
    
        chrome.webRequest.onAuthRequired.addListener(
            callbackFn,
            {urls: ["<all_urls>"]},
            ['blocking']
        );`;
        let dir = "/parser/services/loader/app/lib/proxy/" + proxy.username;
        try {
            if (!fs_1.default.existsSync(dir)) {
                fs_1.default.mkdirSync(dir);
            }
            fs_1.default.openSync(dir + '/manifest.json', 'w');
            fs_1.default.writeFileSync(dir + '/manifest.json', JSON.stringify(manifest_json));
            fs_1.default.openSync(dir + '/background.js', 'w');
            fs_1.default.writeFileSync(dir + '/background.js', '' + background_js);
        }
        catch (err) {
            //console.error(err);
        }
        let pluginfile = dir + '/proxy_auth_plugin.zip';
        let zip = new zl.Zip();
        zip.addFile(dir + '/manifest.json');
        zip.addFile(dir + '/background.js');
        return new Promise((resolve, reject) => {
            zip.archive(dir + '/proxy_auth_plugin.zip').then(() => {
                let options = new chrome_1.Options();
                options.addExtensions(pluginfile);
                this.setChromeOptions(options);
                resolve(this);
            }, (err) => {
                // console.log(err);
                reject(this);
            });
        });
    }
}
exports.default = ChromeDriver;
