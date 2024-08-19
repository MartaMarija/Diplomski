import axios from "axios";

export class ArticleService {

    async loadNewsData(hikingAssociationId, routeParams) {
        return await axios.get(`/hiking-association/${hikingAssociationId}/article-list?ajax=true${routeParams}`)
    }

    async loadNewsDetails(hikingAssociationId, articleId) {
        return await axios.get(`/hiking-association/${hikingAssociationId}/articles/${articleId}?ajax=true`)
    }
}