import {ReflectiveInjector} from "injection-js";
import {HikingAssociationController} from "../Controllers/HikingAssociationController";
import {HikingAssociationService} from "../Services/HikingAssociationService";
import {LoginController} from "../Controllers/LoginController";
import {LoginService} from "../Services/LoginService";
import {RegistrationController} from "../Controllers/RegistrationController";
import {RegistrationService} from "../Services/RegistrationService";

export class DependencyInjection {
    constructor() {
        this.injector = ReflectiveInjector.resolveAndCreate([
            HikingAssociationController,
            HikingAssociationService,
            LoginController,
            LoginService,
            RegistrationController,
            RegistrationService,
        ]);
    }
}