import {Inject} from "injection-js";
import {BaseController} from "./BaseController";
import {TripService} from "../Services/TripService";
import {PaymentService} from "../Services/PaymentService";
import {ArticleService} from "../Services/ArticleService";

export class ArticleController extends BaseController {

    static get parameters() {
        return [new Inject(ArticleService)];
    }

    constructor(ArticleService) {
        super();
        this.articleService = ArticleService
    }

    onInit(config) {
        this.hikingAssociationId = config.data.hikingAssociation
        this.articleId = config.data.article
        this.addEventListeners()
    }

    addEventListeners() {
        this.articleService.loadNewsDetails(this.hikingAssociationId, this.articleId).then((response) => {
            let html = JSON.parse(response.data.data.html_string)
            this.replaceView(html)
        });
    }
}