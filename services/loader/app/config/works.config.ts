
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
            proxy:null
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