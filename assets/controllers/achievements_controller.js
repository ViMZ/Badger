import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['resultList'];
    static values = {
        'url' : { type: String, default: '/achievement/search' }
    };

    async search(e) {
        const response = await fetch(`${this.urlValue}/${e.currentTarget.value}`);

        this.resultListTarget.innerHTML = await response.text();
    }
}
