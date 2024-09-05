import {Inject} from "injection-js";
import {BaseController} from "./BaseController";
import {TripService} from "../Services/TripService";
import {PaymentService} from "../Services/PaymentService";

export class TripController extends BaseController {

    static get parameters() {
        return [new Inject(TripService), new Inject(PaymentService)];
    }

    constructor(TripService, PaymentService) {
        super();
        this.tripService = TripService
        this.paymentService = PaymentService
    }

    onInit(config) {
        this.hikingAssociationId = config.data.hikingAssociation
        this.tripId = config.data.trip
        this.addEventListeners()
    }

    addEventListeners() {
        this.tripService.loadTripDetails(this.hikingAssociationId, this.tripId).then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            this.replaceView(html)

            if ($('#map').length) {
                this.loadOpenStreetMap()
            }
            this.loadPaymentForm()
            this.addEmailListener()
        });
    }

    loadOpenStreetMap() {
        let tripLocation = $('#map').attr('location')
        let longitude = parseFloat($('#map').attr('longitude'))
        let latitude = parseFloat($('#map').attr('latitude'))

        let map = L.map('map').setView([latitude, longitude], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([latitude, longitude]).addTo(map).bindPopup('Dolazak na lokaciju: ' + tripLocation).openPopup();
    }

    loadPaymentForm() {
        let routeParams = `&paymentDescription=Izlet&paymentObject=${this.tripId}`

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
            let routeParams = `&paymentDescription=Izlet&paymentObject=${this.tripId}`

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

    addEmailListener() {
        $(document).on('click', '#send-trip-mail', (e) => {
            e.preventDefault();
            this.tripService.sendEmail(this.hikingAssociationId, this.tripId).then((response) => {
                if (response.data.data.message) {
                    alert(response.data.data.message)
                }
            });
        })
    }
}