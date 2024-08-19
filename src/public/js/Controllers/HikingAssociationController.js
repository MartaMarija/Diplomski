import {HikingAssociationService} from "../Services/HikingAssociationService";
import {Inject} from "injection-js";
import {BaseController} from "./BaseController";
import {toggleMenuChange} from "../Utils/utils";
import {PaymentService} from "../Services/PaymentService";
import {ArticleService} from "../Services/ArticleService";
import {TripService} from "../Services/TripService";

export class HikingAssociationController extends BaseController {

    static get parameters() {
        return [
            new Inject(HikingAssociationService),
            new Inject(PaymentService),
            new Inject(ArticleService),
            new Inject(TripService)
        ];
    }

    constructor(HikingAssociationService, PaymentService, NewsService, TripService) {
        super();
        this.hikingAssociationService = HikingAssociationService
        this.paymentService = PaymentService
        this.newsService = NewsService
        this.tripService = TripService
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

            if (this.route === 'trips') {
                this.loadTripsData()
                this.addPaginationListeners('loadTripsData')
                this.addTripFilterListeners()
            }

            if (this.route === 'membership') {
                this.loadPaymentForm()
            }

            if (this.route === 'news') {
                this.loadNewsData()
                this.addPaginationListeners('loadNewsData')
            }
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

        this.tripService.loadTripsData(this.hikingAssociationId, routeParams).then((response) => {
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

    loadPaymentForm() {
        let routeParams = `&paymentDescription=Članarina&paymentObject=${this.hikingAssociationId}`

        this.paymentService.loadPaymentForm(this.hikingAssociationId, routeParams).then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            $('#payment-container').html(html)

            this.addPaymentFormListener()
        });
    }

    addPaymentFormListener() {
        $(document).on('submit', 'form', (e) => {
            e.preventDefault()

            const formData = new FormData(e.target);
            let routeParams = `&paymentDescription=Članarina&paymentObject=${this.hikingAssociationId}`

            this.paymentService.submitPaymentForm(this.hikingAssociationId, routeParams, formData).then(response => {
                if (response.data.data.message) {
                    alert(response.data.data.message)
                } else {
                    let html = JSON.parse(response.data.data.html_string)
                    $('#payment-container').html(html)
                }
            })
        })
    }

    loadNewsData() {
        let routeParams = ''

        if (this.page) {
            routeParams += `&page=${this.page}`
        }

        this.newsService.loadNewsData(this.hikingAssociationId, routeParams).then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            $('#news-list').html(html)

            /**
             * Most of the routes will generate new links and function updatePageLinks needs to be called in order for
             * Navigo to know about new links with data-navigo attribute
             */
            this.navigo.updatePageLinks()
        });
    }
}