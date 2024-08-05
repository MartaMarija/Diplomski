import axios from "axios";

export class LoginService {

    async loadLoginForm() {
        return await axios.get(`/login?ajax=true`)
    }
}