import axios from "axios";

export class MemberService {

    async loadMyProfile() {
        return await axios.get(`/my-profile?ajax=true`)
    }

    async submitMemberForm(data) {
        return await axios.post(`/my-profile?ajax=true`, data)
    }
}