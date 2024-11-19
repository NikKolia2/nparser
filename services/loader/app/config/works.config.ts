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

        countProcesses:2,
        countUrlsInOneProcess:5
    },
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
            proxy:{
                host:"109.195.6.234",
                port:"40379",
                username:"405d3f46c3",
                password:"42c4073d6c"
            },
            args:[
                "--disable-blink-features=AutomationControlled",
                "--headless",
                "--user-agent=Mozilla/5.0 (Linux; Linux i683 x86_64; en-US) AppleWebKit/536.47 (KHTML, like Gecko) Chrome/53.0.2099.292 Safari/602"
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

        countProcesses:2,
        countUrlsInOneProcess:5
    },
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
            proxy:{
                host:"109.195.6.234",
                port:"40152",
                username:"3f90ac7624",
                password:"074be92fa3"
            },
            args:[
                "--disable-blink-features=AutomationControlled",
                "--headless",
                "--user-agent=Mozilla/5.0 (Linux x86_64; en-US) AppleWebKit/601.47 (KHTML, like Gecko) Chrome/50.0.3098.182 Safari/536"
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

        countProcesses:2,
        countUrlsInOneProcess:5
    }
]