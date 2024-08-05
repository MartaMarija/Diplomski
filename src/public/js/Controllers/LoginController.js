import {BaseController} from "./BaseController";
import {Inject} from "injection-js";
import {LoginService} from "../Services/LoginService";

export class LoginController extends BaseController {

    static get parameters() {
        return [new Inject(LoginService)];
    }

    constructor(LoginService) {
        super();
        this.loginService = LoginService
    }

    onInit() {
        this.loadLoginForm()
    }

    loadLoginForm() {
        this.loginService.loadLoginForm().then((response) => {
            let html = response.data.data.html_string
            this.replaceView(html)
        });
    }
}