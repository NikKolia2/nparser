import { Builder, Browser, Capabilities } from "selenium-webdriver";
import {  Options } from "selenium-webdriver/chrome";
import fs from 'fs'
import * as zl from "zip-lib";
import { PageLoadStrategy } from "selenium-webdriver/lib/capabilities";
import DriverConfig from "./DriverConfig";
import Proxy from "./Proxy";

export default class ChromeDriver extends Builder{
    options: Options

    constructor(){
        super()
        this.options = new Options();
        //this.options.setPageLoadStrategy(PageLoadStrategy.NONE);
      
      //  this.
    }

    static async init(
        config:null|DriverConfig = null
    ):Promise<Builder>{
        let builder = new ChromeDriver();
        if(config?.proxy){
            await builder.addExtProxy(config.proxy)
        }

        if(config?.capabilities){
            builder.withCapabilities(config.capabilities);
        }
        
        builder.forBrowser(Browser.CHROME);
        if(config?.serverUrl){
            builder.usingServer(config.serverUrl);
        }

        return builder;
    }

    setChromeOptions(options: Options): Builder {
        this.options.merge(options);
        super.setChromeOptions(options);
        return this;
    }

    addExtProxy(proxy:Proxy){
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

        let dir = "/parser/loader/app/lib/proxy/"+proxy.username;
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
        
        let pluginfile = dir+'/proxy_auth_plugin.zip'
        // let zip = new zl.Zip();
        // zip.addFile(dir+'/manifest.json');
        // zip.addFile(dir+'/background.js');
        
        return new Promise<ChromeDriver>((resolve, reject) => {
            let options = new Options();
            options.addExtensions(pluginfile);
            this.setChromeOptions(options)
            resolve(this)
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