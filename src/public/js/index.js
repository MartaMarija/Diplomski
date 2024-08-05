import Navigo from 'navigo';
import {Router} from './Router/Router'
import {HikingAssociationController} from "./Controllers/HikingAssociationController";
import {LoginController} from "./Controllers/LoginController";
import {RegistrationController} from "./Controllers/RegistrationController";

$(function() {
    const Navigator = new Navigo('/')
    const Routing = new Router()

    Navigator.on({
        '/': {
            as: 'HikingAssociationController',
            uses: (match) => {
                Routing.switchRoute(HikingAssociationController)
                Routing.onInit(match.url)
            },
        },
        '/login': {
            as: 'LoginController',
            uses: (match) => {
                Routing.switchRoute(LoginController)
                Routing.onInit(match.url)
            },
        },
        '/registration': {
            as: 'RegistrationController',
            uses: (match) => {
                Routing.switchRoute(RegistrationController)
                Routing.onInit(match.url)
            },
        }
    })

    if (Navigator.match(Navigator.getCurrentLocation().url)) {
        Navigator.resolve(Navigator.getCurrentLocation().url)
    }
});