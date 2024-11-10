import { Capabilities } from "selenium-webdriver"
import Proxy from "./Proxy"

export default class DriverConfig{
    serverUrl:string
    capabilities:{}|Capabilities
    proxy:Proxy|null
    args:Array<string>

    constructor(
        serverUrl:string,
        capabilities:{}|Capabilities = {},
        proxy:Proxy|null,
        args:Array<string> = []
    ){
        this.serverUrl = serverUrl
        this.capabilities = capabilities
        this.proxy = proxy
        this.args = args
    }
}