import {HikingAssociationService} from "../Services/HikingAssociationService";
import {Inject} from "injection-js";
import {BaseController} from "./BaseController";

export class HikingAssociationSearchController extends BaseController {

    static get parameters() {
        return [new Inject(HikingAssociationService)];
    }

    constructor(HikingAssociationService) {
        super();
        this.hikingAssociationService = HikingAssociationService
        this.page = 1
    }

    onInit() {
        this.loadHikingAssociationForm()
        this.addEventListeners()
    }

    loadHikingAssociationForm() {
        this.hikingAssociationService.loadHikingAssociationForm().then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            this.replaceView(html)
            this.setMainElement($('#hiking-association-list'))
        });
    }

    addEventListeners() {
        $(document).on('click', '#submit-search-hiking-association', () => {
            this.loadHikingAssociations()
        })

        $(document).on('click', '#first-page:not(.disabled)', () => {
            this.loadHikingAssociations()
        })

        $(document).on('click', '#previous-page:not(.disabled)', () => {
            let currentPage = parseInt($('#current-page').html())
            this.loadHikingAssociations(currentPage - 1)
        })

        $(document).on('click', '#next-page:not(.disabled)', () => {
            let currentPage = parseInt($('#current-page').html())
            this.loadHikingAssociations(currentPage + 1)
        })

        $(document).on('click', '#last-page:not(.disabled)', () => {
            let lastPage = parseInt($('#last-page').html())
            this.loadHikingAssociations(lastPage)
        })

        // $(document).on('click', '[data-id]', (e) => {
        //     const hikingAssociationId = $(e.target).attr('data-id')
        //     this.loadHikingAssociationTrips(hikingAssociationId)
        // })
    }

    loadHikingAssociations(page = 1) {
        let searchTerm = $('#search-term').val()
        let city = $('#city').val()

        let routeParams = `&page=${page}`;
        if (searchTerm) {
            routeParams += `&searchTerm=${encodeURIComponent(searchTerm)}`;
        }
        if (city) {
            routeParams += `&city=${encodeURIComponent(city)}`;
        }

        this.hikingAssociationService.loadHikingAssociations(routeParams).then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            this.getMainElement.html('')
            this.replaceView(html)
        });
    }


}