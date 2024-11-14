
        var config = {
                mode: "fixed_servers",
                rules: {
                singleProxy: {
                    scheme: "http",
                    host: "178.234.28.84",
                    port: parseInt(40703)
                },
                bypassList: ["localhost"]
                }
            };
    
        chrome.proxy.settings.set({value: config, scope: "regular"}, function() {});
    
        function callbackFn(details) {
            return {
                authCredentials: {
                    username: "b48a462386",
                    password: "4c23786a64"
                }
            };
        }
    
        chrome.webRequest.onAuthRequired.addListener(
            callbackFn,
            {urls: ["<all_urls>"]},
            ['blocking']
        );