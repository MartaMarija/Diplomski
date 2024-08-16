import {Inject} from "injection-js";
import {BaseController} from "./BaseController";
import {SectionService} from "../Services/SectionService";

export class SectionController extends BaseController {

    static get parameters() {
        return [new Inject(SectionService)];
    }

    constructor(SectionService) {
        super();
        this.sectionService = SectionService
    }

    onInit(match) {
        this.match = match

        this.addEventListeners()
    }

    addEventListeners() {
        this.loadSingleSection()
    }

    loadSingleSection() {
        this.sectionService.loadSingleSection(this.match.data.section).then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            this.setMainElement($('#main-content'))
            this.getMainElement.html('')
            this.replaceView(html)
        });
    }
}