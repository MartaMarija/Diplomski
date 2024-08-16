import axios from "axios";

export class SectionService {

    async loadSingleSection(sectionId) {
        return await axios.get(`/sections/${sectionId}?ajax=true`)
    }
}