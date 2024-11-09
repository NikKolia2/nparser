"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
class DriverConfig {
    constructor(serverUrl, capabilities = {}, proxy) {
        this.serverUrl = serverUrl;
        this.capabilities = capabilities;
        this.proxy = proxy;
    }
}
exports.default = DriverConfig;
