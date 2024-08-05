import {BaseController} from "./BaseController";
import {Inject} from "injection-js";
import {LoginService} from "../Services/LoginService";
import {RegistrationService} from "../Services/RegistrationService";

export class RegistrationController extends BaseController {

    static get parameters() {
        return [new Inject(RegistrationService)];
    }

    constructor(RegistrationService) {
        super();
        this.registrationService = RegistrationService
    }

    onInit() {
        this.loadRegistrationForm()
        this.addEventListeners()
    }

    loadRegistrationForm() {
        this.registrationService.loadRegistrationForm().then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            this.replaceView(html)
        })
    }

    addEventListeners() {
        this.getMainElement.on('submit', 'form', (e) => {
            e.preventDefault()

            const formData = new FormData(e.target);
            this.registrationService.submitMemberForm(formData).then(response => {
                console.log(response)
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    let html = JSON.parse(response.data.data.html_string)
                    this.replaceView(html)
                }
            })
        })
    }
}