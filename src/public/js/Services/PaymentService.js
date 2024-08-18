import axios from "axios";

export class PaymentService {

    async submitPaymentForm(hikingAssociationId, routeParams, data) {
        return await axios.post(`/payment/${hikingAssociationId}?ajax=true${routeParams}`, data)
    }

    async loadPaymentForm(hikingAssociationId, routeParams) {
        return await axios.get(`/payment/${hikingAssociationId}?ajax=true${routeParams}`);
    }
}