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
Object.defineProperty(exports, "__esModule", { value: true });
const selenium_webdriver_1 = require("selenium-webdriver");
const chrome_1 = require("selenium-webdriver/chrome");
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
                builder.usingServer(config.serverUrl);
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
        // let manifest_json = {
        //     "version": "1.0.0",
        //     "manifest_version": 2,
        //     "name": "Chrome Proxy",
        //     "permissions": [
        //         "proxy",
        //         "tabs",
        //         "unlimitedStorage",
        //         "storage",
        //         "<all_urls>",
        //         "webRequest",
        //         "webRequestBlocking"
        //     ],
        //     "background": {
        //         "scripts": ["background.js"]
        //     },
        //     "minimum_chrome_version":"22.0.0"
        // };
        // let background_js = `
        // var config = {
        //         mode: "fixed_servers",
        //         rules: {
        //         singleProxy: {
        //             scheme: "http",
        //             host: "${proxy.host}",
        //             port: parseInt(${proxy.port})
        //         },
        //         bypassList: ["localhost"]
        //         }
        //     };
        // chrome.proxy.settings.set({value: config, scope: "regular"}, function() {});
        // function callbackFn(details) {
        //     return {
        //         authCredentials: {
        //             username: "${proxy.username}",
        //             password: "${proxy.password}"
        //         }
        //     };
        // }
        // chrome.webRequest.onAuthRequired.addListener(
        //     callbackFn,
        //     {urls: ["<all_urls>"]},
        //     ['blocking']
        // );`
        let dir = "/parser/loader/app/lib/proxy/" + proxy.username;
        // try{
        //     if (!fs.existsSync(dir)) {
        //         fs.mkdirSync(dir);
        //     }
        //     fs.openSync(dir+'/manifest.json', 'w');
        //     fs.writeFileSync(dir+'/manifest.json', JSON.stringify(manifest_json));
        //     fs.openSync(dir+'/background.js', 'w');
        //     fs.writeFileSync(dir+'/background.js', ''+background_js);
        // } catch (err) {
        //     console.error(err);
        // }
        let pluginfile = dir + '/proxy_auth_plugin.zip';
        // let zip = new zl.Zip();
        // zip.addFile(dir+'/manifest.json');
        // zip.addFile(dir+'/background.js');
        return new Promise((resolve, reject) => {
            let options = new chrome_1.Options();
            options.addExtensions(pluginfile);
            this.setChromeOptions(options);
            resolve(this);
            // zip.archive(dir+'/proxy_auth_plugin.zip').then(() => {
            //     let options = new Options();
            //     options.addExtensions(pluginfile);
            //     this.setChromeOptions(options)
            //     resolve(this)
            // }, (err) => {
            //     console.log(err);
            //     reject(this);
            // });
        });
    }
}
exports.default = ChromeDriver;
