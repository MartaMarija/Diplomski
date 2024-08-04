export class BaseController {
    constructor() {
        this.mainElement = null
    }

    replaceView (html = '') {
        if (this.getMainElement) {
            this.getMainElement.append(html)
        }
    }

    destroy () {
        if (this.getMainElement) {
            this.getMainElement.html('')
            this.getMainElement.off()
            this.setMainElement(null)
        }
    }

    get getMainElement () {
        return this.mainElement
    }

    setMainElement (mainElement) {
        this.mainElement = mainElement
    }
}