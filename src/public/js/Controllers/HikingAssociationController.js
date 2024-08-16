import {HikingAssociationService} from "../Services/HikingAssociationService";
import {Inject} from "injection-js";
import {BaseController} from "./BaseController";
import {toggleMenuChange} from "../Utils/utils";

export class HikingAssociationController extends BaseController {

    static get parameters() {
        return [new Inject(HikingAssociationService)];
    }

    constructor(HikingAssociationService) {
        super();
        this.hikingAssociationService = HikingAssociationService
    }

    onInit(config) {
        let match = config.match
        this.hikingAssociationId = match.data.hikingAssociation
        this.route = config.route
        this.navigo = config.navigo

        localStorage.setItem('hikingAssociationId', match.data.hikingAssociation);
        toggleMenuChange()

        this.addEventListeners()
    }

    addEventListeners() {
        this.loadHikingAssociationData()
    }

    loadHikingAssociationData() {
        this.hikingAssociationService.loadHikingAssociationData(this.hikingAssociationId, this.route).then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            this.setMainElement($('#main-content'))
            this.getMainElement.html('')
            this.replaceView(html)

            /**
             * Most of the routes will generate new links and function updatePageLinks needs to be called in order for
             * Navigo to know about new links with data-navigo attribute
             */
            this.navigo.updatePageLinks()
            // For debugging
            // document.querySelectorAll('[data-navigo]').forEach(link => {
            //     console.log(link.href); // or perform any other action with the links
            // });
        });
    }
}