import {Inject} from "injection-js";
import {BaseController} from "./BaseController";
import {TripService} from "../Services/TripService";
import L from 'leaflet';

export class TripController extends BaseController {

    static get parameters() {
        return [new Inject(TripService)];
    }

    constructor(TripService) {
        super();
        this.tripService = TripService
    }

    onInit(config) {
        this.tripId = config.data.trip
        this.addEventListeners()
    }

    addEventListeners() {
        this.tripService.loadTripDetails(this.tripId).then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            this.replaceView(html)

            this.loadOpenStreetMap()
        });
    }

    loadOpenStreetMap() {
        let tripLocation = $('#map').attr('location')
        let longitude = $('#map').attr('longitude')
        let latitude = $('#map').attr('latitude')

        let map = L.map('map').setView([latitude, longitude], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([latitude, longitude]).addTo(map).bindPopup('Dolazak na lokaciju: ' + tripLocation).openPopup();
    }
}