import axios from "axios";

export class HikingAssociationService {

    async loadHikingAssociationForm() {
        return await axios.get(`/hiking-association/search-form?ajax=true`)
    }

    async loadHikingAssociations(routeParams) {
        return await axios.get(`/hiking-association/search?ajax=true${routeParams}`)
    }

    async loadHikingAssociationTrips(hikingAssociationId) {
        return await axios.get(`/hiking-association/${hikingAssociationId}/trips`)
    }
}