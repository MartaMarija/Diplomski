export class BaseController {
    constructor() {
        this.mainElement = "#main-content"
    }

    replaceView (html = '') {
        if (this.getMainElement) {
            this.getMainElement.append(html)
        }
    }

    destroy () {
        if (this.getMainElement) {
            this.getMainElement.html('')
        }
        this.removeEventListeners()
    }

    get getMainElement () {
        return this.mainElement
    }

    setMainElement (mainElement) {
        this.mainElement = mainElement
    }

    removeEventListeners() {
        $(document).unbind()
    }
}