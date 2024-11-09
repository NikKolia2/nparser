"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.default = [
    {
        driverConfig: {
            serverUrl: "http://nparser_vseinstruments_selenium:4444/wd/hub",
            capabilities: {
                'goog:chromeOptions': {
                    excludeSwitches: [
                        'enable-automation',
                        'useAutomationExtension',
                    ],
                },
            },
            proxy: {
                host: "178.234.28.84",
                port: "40875",
                username: "12ff57a8e5",
                password: "a8271ae51f"
            }
        },
        timeOutsBeforOpenUrl: [
            800,
            900,
            1100,
            1150,
            1200,
            1050
        ],
        timeOutsAfterSaveStep: [
            2050,
            3200,
            3500,
            4000
        ],
        countProcesses: 4,
        countUrlsInOneProcess: 5
    },
    {
        driverConfig: {
            serverUrl: "http://nparser_vseinstruments_selenium:4444/wd/hub",
            capabilities: {
                'goog:chromeOptions': {
                    excludeSwitches: [
                        'enable-automation',
                        'useAutomationExtension',
                    ],
                },
            },
            proxy: {
                host: "109.195.6.226",
                port: "40812",
                username: "6e4b272a62",
                password: "75eb222a76"
            }
        },
        timeOutsBeforOpenUrl: [
            800,
            900,
            1100,
            1150,
            1200,
            1050
        ],
        timeOutsAfterSaveStep: [
            2050,
            3200,
            3500,
            4000
        ],
        countProcesses: 4,
        countUrlsInOneProcess: 5
    }
];
