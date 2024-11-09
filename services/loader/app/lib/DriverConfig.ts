import { Capabilities } from "selenium-webdriver"
import Proxy from "./Proxy"

export default class DriverConfig{
    serverUrl:string
    capabilities:{}|Capabilities
    proxy:Proxy|null

    constructor(
        serverUrl:string,
        capabilities:{}|Capabilities = {},
        proxy:Proxy|null
    ){
        this.serverUrl = serverUrl
        this.capabilities = capabilities
        this.proxy = proxy
    }
}