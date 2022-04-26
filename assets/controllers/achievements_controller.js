import { Controller } from '@hotwired/stimulus';
import { ApplicationController, useDebounce } from 'stimulus-use';
export default class extends Controller {
    static targets = ['resultList'];
    static values = {
        'url' : { type: String, default: '/achievement/search' }
    };
    static debounces = [{name: 'search',wait: 300}]

    connect()
    {
        useDebounce(this)
    }

    async search(e) {
        const response = await fetch(`${this.urlValue}/${e.target.value}`);
        this.resultListTarget.innerHTML = await response.text();
    }
}
