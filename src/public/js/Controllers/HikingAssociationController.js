import {HikingAssociationService} from "../Services/HikingAssociationService";
import {Inject} from "injection-js";
import {BaseController} from "./BaseController";

export class HikingAssociationController extends BaseController {

    static get parameters() {
        return [new Inject(HikingAssociationService)];
    }

    constructor(HikingAssociationService) {
        super();
        this.hikingAssociationService = HikingAssociationService
    }

    onInit(match) {
        this.hikingAssociationId = match.data.hikingAssociation;
        this.addEventListeners()
    }


    addEventListeners() {
        this.loadHikingAssociationTrips()
    }

    loadHikingAssociationTrips() {
        this.hikingAssociationService.loadHikingAssociationTrips(this.hikingAssociationId).then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            this.setMainElement($('#main-content'))
            this.getMainElement.html('')
            this.replaceView(html)

            // localStorage.setItem('hikingAssociationId', hikingAssociationId);
            // toggleMenuChange()
        });
    }
}