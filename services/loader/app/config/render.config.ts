import Render from "../lib/Render";
import ProductRender from "../renders/ProductRender";

export default {
    renders: [
        {
            render:ProductRender,
            typeProcessId:1
        }
    ],

    get(typeProcessId:number){
        let render = this.renders.find((item) => item.typeProcessId == typeProcessId);
        if(render != undefined)
            return render.render
        else return Render
    }
}