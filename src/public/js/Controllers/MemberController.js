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
            this.addPaginationListeners('loadMemberships')
        }

        if (config.route === 'profile') {
            this.loadMyProfile()
            this.addEventListeners()
        }

        if (config.route === 'trips') {
            this.loadTrips()
            this.addPaginationListeners('loadTrips')
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
        let routeParams = `&page=${this.page}`

        this.memberService.loadMemberships(routeParams).then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            this.mainElement.html(html)
        });
    }

    loadTrips() {

        let routeParams = `&page=${this.page}`

        this.memberService.loadTrips(routeParams).then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            this.mainElement.html(html)
        });
    }

    addPaginationListeners(functionName) {
        $(document).on('click', '#first-page:not(.disabled)', () => {
            this.page = 1
            this[functionName].call(this);
        })

        $(document).on('click', '#previous-page:not(.disabled)', () => {
            let currentPage = parseInt($('#current-page').html())
            this.page = currentPage - 1
            this[functionName].call(this);
        })

        $(document).on('click', '#next-page:not(.disabled)', () => {
            let currentPage = parseInt($('#current-page').html())
            this.page = currentPage + 1
            this[functionName].call(this);
        })

        $(document).on('click', '#last-page:not(.disabled)', () => {
            this.page = parseInt($('#last-page').html())
            this[functionName].call(this);
        })
    }
}