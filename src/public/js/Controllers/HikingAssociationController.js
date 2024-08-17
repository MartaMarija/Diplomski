import {HikingAssociationService} from "../Services/HikingAssociationService";
import {Inject} from "injection-js";
import {BaseController} from "./BaseController";
import {toggleMenuChange} from "../Utils/utils";

export class HikingAssociationController extends BaseController {

    static get parameters() {
        return [new Inject(HikingAssociationService)];
    }

    constructor(HikingAssociationService) {
        super();
        this.hikingAssociationService = HikingAssociationService
        this.page = 1
    }

    onInit(config) {
        let match = config.match
        this.hikingAssociationId = match.data.hikingAssociation
        this.route = config.route
        this.navigo = config.navigo

        localStorage.setItem('hikingAssociationId', match.data.hikingAssociation);
        toggleMenuChange()

        this.addEventListeners()
    }

    addEventListeners() {
        this.loadHikingAssociationData()
        this.addPaginationListeners()
        this.addTripFilterListeners()
    }

    loadHikingAssociationData() {
        let page = `&page=${this.page}`

        this.hikingAssociationService.loadHikingAssociationData(this.hikingAssociationId, this.route, page).then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            this.setMainElement($('#main-content'))
            this.getMainElement.html('')
            this.replaceView(html)

            /**
             * Most of the routes will generate new links and function updatePageLinks needs to be called in order for
             * Navigo to know about new links with data-navigo attribute
             */
            this.navigo.updatePageLinks()
            // For debugging
            // document.querySelectorAll('[data-navigo]').forEach(link => {
            //     console.log(link.href); // or perform any other action with the links
            // });

            this.loadTripsData()
        });
    }

    addPaginationListeners() {
        $(document).on('click', '#first-page:not(.disabled)', () => {
            this.page = 1
            this.loadTripsData()
        })

        $(document).on('click', '#previous-page:not(.disabled)', () => {
            let currentPage = parseInt($('#current-page').html())
            this.page = currentPage - 1
            this.loadTripsData()
        })

        $(document).on('click', '#next-page:not(.disabled)', () => {
            let currentPage = parseInt($('#current-page').html())
            this.page = currentPage + 1
            this.loadTripsData()
        })

        $(document).on('click', '#last-page:not(.disabled)', () => {
            this.page = parseInt($('#last-page').html())
            this.loadTripsData()
        })
    }

    addTripFilterListeners() {
        $(document).on('change', '#trip_filter_Name', (e) => {
            this.filterName = e.target.value.trim() || null;
            this.loadTripsData()
        })

        $(document).on('change', '#trip_filter_Section', (e) => {
            if (!e.target.selectedOptions[0].value) {
                this.filterSection = null
            } else {
                this.filterSection = e.target.selectedOptions[0].value
            }
            this.loadTripsData()
        })

        $(document).on('change', '#trip_filter_Type', (e) => {
            if (!e.target.selectedOptions[0].value) {
                this.filterType = null
            } else {
                this.filterType = e.target.selectedOptions[0].value
            }
            this.loadTripsData()
        })

        $(document).on('change', '#trip_filter_StartDate', (e) => {
            this.filterStartDate = e.target.value.trim() || null;
            this.loadTripsData()
        })

        $(document).on('change', '#trip_filter_StartDateSort', (e) => {
            this.startDateSort = e.target.selectedOptions[0].value
            this.loadTripsData()
        })
    }

    loadTripsData() {
        let routeParams = ''

        if (this.filterName) {
            routeParams += `&name=${this.filterName}`
        }

        if (this.filterSection) {
            routeParams += `&section=${this.filterSection}`
        }

        if (this.filterType) {
            routeParams += `&type=${this.filterType}`
        }

        if (this.filterStartDate) {
            routeParams += `&startDate=${this.filterStartDate}`
        }

        if (this.startDateSort) {
            routeParams += `&sort=${this.startDateSort}`
        }

        if (this.page) {
            routeParams += `&page=${this.page}`
        }

        this.hikingAssociationService.loadTripsData(this.hikingAssociationId, routeParams).then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            $('#trip-list').html(html)

            /**
             * Most of the routes will generate new links and function updatePageLinks needs to be called in order for
             * Navigo to know about new links with data-navigo attribute
             */
            this.navigo.updatePageLinks()
            // For debugging
            // document.querySelectorAll('[data-navigo]').forEach(link => {
            //     console.log(link.href); // or perform any other action with the links
            // });
        });
    }
}