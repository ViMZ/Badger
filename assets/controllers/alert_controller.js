import { Controller } from '@hotwired/stimulus';
import { delay } from '../javascript/delay';

export default class extends Controller {
    static targets = ['alert','close'];

    connect() {
        setTimeout(() => this.fadeout(), 3000)
    }

    fadeout() {
        this.alertTarget.classList.add('Alert--fadeout');

        delay(500).then(() => {
            this.alertTarget.parentNode.remove();
        })
    }
}
