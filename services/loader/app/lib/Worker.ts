import Work from "./Work"

export default class Worker {
    works:Array<Work>

    constructor(works:Array<Work>){
        this.works = works
    }

    async run(){
        setInterval(async () => {
            this.works.forEach((work) => {
                let response = work.run()
            })
        }, 10)
    }
}