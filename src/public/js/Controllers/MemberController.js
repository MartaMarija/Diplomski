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

    onInit(config) {
        this.destroy()
        this.page = 1
        this.hikingAssociationId = localStorage.getItem('hikingAssociationId')
        this.navigo = config.navigo

        if (config.route === 'memberships') {
            this.loadMemberships()
        }

        if (config.route === 'profile') {
            this.loadMyProfile()
            this.addEventListeners()
        }

        if (config.route === 'trips') {
            this.loadTrips()
        }
    }

    loadMyProfile() {
        this.memberService.loadMyProfile().then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            this.replaceView(html)

            this.navigo.updatePageLinks()
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

    loadMemberships() {
        let routeParams = `&page=${this.page}&hikingAssociationId=${this.hikingAssociationId}`

        this.memberService.loadMemberships(routeParams).then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            this.replaceView(html)
        });
    }

    loadTrips() {

        let routeParams = `&page=${this.page}&hikingAssociationId=${this.hikingAssociationId}`

        this.memberService.loadTrips(routeParams).then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            this.replaceView(html)
        });
    }
}