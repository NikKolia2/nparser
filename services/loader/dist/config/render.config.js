"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const Render_1 = __importDefault(require("../lib/Render"));
const ProductRender_1 = __importDefault(require("../renders/ProductRender"));
exports.default = {
    renders: [
        {
            render: ProductRender_1.default,
            typeProcessId: 1
        }
    ],
    get(typeProcessId) {
        let render = this.renders.find((item) => item.typeProcessId == typeProcessId);
        if (render != undefined)
            return render.render;
        else
            return Render_1.default;
    }
};
