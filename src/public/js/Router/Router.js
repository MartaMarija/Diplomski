import {DependencyInjection} from "../DependencyInjection/DependencyInjection";

export class Router {

    currentViewController;
    #mainElement;

    constructor() {
        this.#mainElement = $('#main-content');
        window.DependencyInjection = new DependencyInjection();
    }

    switchRoute(controller) {
        if (this.currentViewController) {
            this.currentViewController.destroy();
        }

        this.currentViewController = window.DependencyInjection.injector.get(controller);
        this.currentViewController.setMainElement(this.#mainElement);
    }

    onInit(config = {}) {
        this.currentViewController.onInit(config)
    }
}
