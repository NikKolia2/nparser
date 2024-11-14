
        var config = {
                mode: "fixed_servers",
                rules: {
                singleProxy: {
                    scheme: "http",
                    host: "109.195.6.226",
                    port: parseInt(40812)
                },
                bypassList: ["localhost"]
                }
            };
    
        chrome.proxy.settings.set({value: config, scope: "regular"}, function() {});
    
        function callbackFn(details) {
            return {
                authCredentials: {
                    username: "6e4b272a62",
                    password: "75eb222a76"
                }
            };
        }
    
        chrome.webRequest.onAuthRequired.addListener(
            callbackFn,
            {urls: ["<all_urls>"]},
            ['blocking']
        );