import axios from "axios";

export class TripService {

    async loadTripsData(hikingAssociationId, routeParams) {
        return await axios.get(`/hiking-association/${hikingAssociationId}/trips-list?ajax=true${routeParams}`)
    }

    async loadTripDetails(hikingAssociationId, tripId) {
        return await axios.get(`/hiking-association/${hikingAssociationId}/trips/${tripId}?ajax=true`)
    }

    async sendEmail(hikingAssociationId, tripId) {
        return await axios.get(`/hiking-association/${hikingAssociationId}/trips/${tripId}/info-mail`)
    }
}