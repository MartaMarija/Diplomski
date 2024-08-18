import axios from "axios";

export class TripService {

    async loadTripDetails(hikingAssociationId, tripId) {
        return await axios.get(`/hiking-association/${hikingAssociationId}/trips/${tripId}?ajax=true`)
    }
}