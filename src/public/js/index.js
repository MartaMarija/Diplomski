import Navigo from 'navigo';
import {Router} from './Router/Router'
import {HikingAssociationSearchController} from "./Controllers/HikingAssociationSearchController";
import {LoginController} from "./Controllers/LoginController";
import {RegistrationController} from "./Controllers/RegistrationController";
import {HikingAssociationController} from "./Controllers/HikingAssociationController";
import { toggleMenuChange } from './Utils/utils.js';

$(function() {
    const Navigator = new Navigo('/')
    const Routing = new Router()

    Navigator.on({
        '/': {
            as: 'HikingAssociationSearchController',
            uses: (match) => {
                Routing.switchRoute(HikingAssociationSearchController)
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
        },
        '/hiking-association/:hikingAssociation': {
            as: 'HikingAssociationController',
            uses: (match) => {
                Routing.switchRoute(HikingAssociationController)
                Routing.onInit(match)
            }
        }
    })

    if (Navigator.match(Navigator.getCurrentLocation().url)) {
        Navigator.resolve(Navigator.getCurrentLocation().url)
    }

    toggleMenuChange();
});