import axios from "axios";

export class MemberService {

    async loadMyProfile() {
        return await axios.get(`/my-profile?ajax=true`)
    }

    async submitMemberForm(data) {
        return await axios.post(`/my-profile?ajax=true`, data)
    }

    async loadMemberships(routeParams) {
        return await axios.get(`/my-profile/memberships?ajax=true${routeParams}`)
    }

    async loadTrips(routeParams) {
        return await axios.get(`/my-profile/trips?ajax=true${routeParams}`)
    }
}