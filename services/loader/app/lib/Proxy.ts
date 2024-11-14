export default class Proxy {
    host:string
    port:string
    username:string|null
    password:string|null

    constructor(
        host:string,
        port:string,
        username:string|null,
        password:string|null
    ){
        this.host = host
        this.port = port
        this.username =username
        this.password = password
    }
}