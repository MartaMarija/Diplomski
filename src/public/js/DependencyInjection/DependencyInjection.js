import {ReflectiveInjector} from "injection-js";
import {HikingAssociationSearchController} from "../Controllers/HikingAssociationSearchController";
import {HikingAssociationService} from "../Services/HikingAssociationService";
import {LoginController} from "../Controllers/LoginController";
import {LoginService} from "../Services/LoginService";
import {RegistrationController} from "../Controllers/RegistrationController";
import {RegistrationService} from "../Services/RegistrationService";
import {HikingAssociationController} from "../Controllers/HikingAssociationController";
import {SectionService} from "../Services/SectionService";
import {SectionController} from "../Controllers/SectionController";
import {TripController} from "../Controllers/TripController";
import {TripService} from "../Services/TripService";
import {PaymentService} from "../Services/PaymentService";
import {ArticleService} from "../Services/ArticleService";
import {ArticleController} from "../Controllers/ArticleController";
import {MemberController} from "../Controllers/MemberController";
import {MemberService} from "../Services/MemberService";

export class DependencyInjection {
    constructor() {
        this.injector = ReflectiveInjector.resolveAndCreate([
            HikingAssociationSearchController,
            HikingAssociationService,
            LoginController,
            LoginService,
            RegistrationController,
            RegistrationService,
            HikingAssociationController,
            SectionController,
            SectionService,
            TripController,
            TripService,
            PaymentService,
            ArticleController,
            ArticleService,
            MemberController,
            MemberService
        ]);
    }
}