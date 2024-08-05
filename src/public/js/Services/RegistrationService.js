import axios from "axios";

export class RegistrationService {

    async loadRegistrationForm() {
        return await axios.get(`/registration?ajax=true`)
    }

    async submitMemberForm(data) {
        return await axios.post(`/registration?ajax=true`, data)
    }
}