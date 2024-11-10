
export default [
    {
        driverConfig:{
            serverUrl:"http://nparser_selenium:4444/wd/hub",
            capabilities:{
                'goog:chromeOptions': {
                    excludeSwitches: [
                        'enable-automation',
                        'useAutomationExtension',
                    ],
                },
            },
            proxy:{
                host:"178.234.28.84",
                port:"40875",
                username:"12ff57a8e5",
                password:"a8271ae51f"
            },

            args:[
                "--disable-blink-features=AutomationControlled",
                "--user-agent=Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 YaBrowser/24.7.0.0 Safari/537.36"
            ]
        },
        timeOutsBeforOpenUrl:[
            800,
            900,
            1100,
            1150,
            1200,
            1050
        ],

        timeOutsAfterSaveStep:[
            2050,
            3200,
            3500,
            4000
        ],

        countProcesses:4,
        countUrlsInOneProcess:5
    }
]