export default {
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
        host:"178.234.28.84",
        port:"40875",
        username:"12ff57a8e5",
        password:"a8271ae51f"
    }
};