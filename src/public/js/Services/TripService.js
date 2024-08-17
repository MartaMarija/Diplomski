import axios from "axios";

export class TripService {

    async loadTripDetails(tripId) {
        return await axios.get(`/trips/${tripId}?ajax=true`)
    }
}