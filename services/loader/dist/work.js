"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const selenium_webdriver_1 = require("selenium-webdriver");
const chrome_1 = require("selenium-webdriver/chrome");
const Loader_1 = __importDefault(require("./Loader"));
const worker_threads_1 = require("worker_threads");
const pathToSaveHTML = "/parser/storage/html/";
const host = "178.234.28.84";
const port = "40875";
const username = "12ff57a8e5";
const password = "a8271ae51f";
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
            host: "${host}",
            port: parseInt(${port})
        },
        bypassList: ["localhost"]
        }
    };

chrome.proxy.settings.set({value: config, scope: "regular"}, function() {});

function callbackFn(details) {
    return {
        authCredentials: {
            username: "${username}",
            password: "${password}"
        }
    };
}

chrome.webRequest.onAuthRequired.addListener(
    callbackFn,
    {urls: ["<all_urls>"]},
    ['blocking']
);`;
let driver = getChromedriver(true);
driver.executeScript("Object.defineProperty(navigator, 'webdriver', {get: () => undefined})");
let timeOutsBeforOpenUrl = [
    800,
    1100,
    1200,
    1300,
];
let timeOutsAfterSaveStep = [
    5000,
    5100,
    5050,
    4900,
    5200,
    6000
];
driver.get("https://www.vseinstrumenti.ru/represent/change/?represent_id=-1&represent_type=common").then(() => {
    let loader = new Loader_1.default(driver, timeOutsBeforOpenUrl, timeOutsAfterSaveStep, pathToSaveHTML);
    loader.loop(worker_threads_1.workerData).then(success => {
        worker_threads_1.parentPort === null || worker_threads_1.parentPort === void 0 ? void 0 : worker_threads_1.parentPort.postMessage({ success });
        process.exit(0);
    });
});
function getChromedriver(use_proxy = false, user_agent = null) {
    let options = new chrome_1.Options();
    if (use_proxy) {
        // fs.open('manifest.json', 'w', (err) => {
        //     if (err) throw err;
        // });
        // fs.appendFile('manifest.json', JSON.stringify(manifest_json), (err) => {
        //     if (err) throw err;
        // });
        // fs.open('background.js', 'w', (err) => {
        //     if (err) throw err;
        // });
        // fs.appendFile('background.js', ''+background_js, (err) => {
        //     if (err) throw err;
        // });
        let pluginfile = 'proxy_auth_plugin.zip';
        // let zip = new zl.Zip();
        // zip.addFile('manifest.json');
        // zip.addFile('background.js');
        // zip.archive('proxy_auth_plugin.zip').then(function () {
        //     console.log("done");
        // }, function (err) {
        //     console.log(err);
        // });
        // console.log(fs.existsSync(pluginfile));
        options.addExtensions(pluginfile);
    }
    let driver = new selenium_webdriver_1.Builder().withCapabilities({
        'goog:chromeOptions': {
            excludeSwitches: [
                'enable-automation',
                'useAutomationExtension',
            ],
        },
    }).forBrowser(selenium_webdriver_1.Browser.CHROME)
        .setChromeOptions(options)
        .usingServer('http://nparser_vseinstruments_selenium:4444/wd/hub').build();
    return driver;
}
