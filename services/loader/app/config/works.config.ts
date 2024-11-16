import driverConfig from "./driver.config";

export default [
    {
        driverConfig:{
            serverUrl:"http://nparser_vseinstruments_selenium:4444/wd/hub",
            capabilities:{
                'goog:chromeOptions': {
                    excludeSwitches: [
                        'enable-automation',
                        'useAutomationExtension',
                    ],
                },
            },
            proxy:null,
            args:[
                "--disable-blink-features=AutomationControlled",
                "--headless",
                "--user-agent=Mozilla/5.0 (Linux x86_64) AppleWebKit/535.32 (KHTML, like Gecko) Chrome/49.0.2863.238 Safari/601"
            ]
        },
        timeOutsBeforOpenUrl:[
            800,
            900,
            1250,
            1300
        ],

        timeOutsAfterSaveStep:[
            2250,
            3250,
            3500,
            3000
        ],

        countProcesses:1,
        countUrlsInOneProcess:5
    },
   
]