import {ReflectiveInjector} from "injection-js";
import {HikingAssociationController} from "../Controllers/HikingAssociationController";
import {HikingAssociationService} from "../Services/HikingAssociationService";

export class DependencyInjection {
    constructor() {
        this.injector = ReflectiveInjector.resolveAndCreate([
            HikingAssociationController,
            HikingAssociationService
        ]);
    }
}