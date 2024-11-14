"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
class DriverConfig {
    constructor(serverUrl, capabilities = {}, proxy, args = []) {
        this.serverUrl = serverUrl;
        this.capabilities = capabilities;
        this.proxy = proxy;
        this.args = args;
    }
}
exports.default = DriverConfig;
