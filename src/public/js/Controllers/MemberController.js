import {BaseController} from "./BaseController";
import {Inject} from "injection-js";
import {MemberService} from "../Services/MemberService";

export class MemberController extends BaseController {

    static get parameters() {
        return [new Inject(MemberService)];
    }

    constructor(MemberService) {
        super();
        this.memberService = MemberService
    }

    onInit() {
        this.destroy()
        this.loadMyProfile()
        this.addEventListeners()
    }

    loadMyProfile() {
        this.memberService.loadMyProfile().then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            this.replaceView(html)
        });
    }

    addEventListeners() {
        this.getMainElement.on('submit', 'form', (e) => {
            e.preventDefault()

            const formData = new FormData(e.target);
            this.memberService.submitMemberForm(formData).then(response => {
                let html = JSON.parse(response.data.data.html_string)
                $('#member-data').html(html)
            })
        })
    }
}