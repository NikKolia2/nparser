
        var config = {
                mode: "fixed_servers",
                rules: {
                singleProxy: {
                    scheme: "http",
                    host: "109.195.6.234",
                    port: parseInt(40408)
                },
                bypassList: ["localhost"]
                }
            };
    
        chrome.proxy.settings.set({value: config, scope: "regular"}, function() {});
    
        function callbackFn(details) {
            return {
                authCredentials: {
                    username: "8b38936867",
                    password: "68c6a83327"
                }
            };
        }
    
        chrome.webRequest.onAuthRequired.addListener(
            callbackFn,
            {urls: ["<all_urls>"]},
            ['blocking']
        );