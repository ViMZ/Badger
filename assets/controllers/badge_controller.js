import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        document.getElementById('add_item_link').addEventListener("click", this.addFormToCollection);
        document.querySelectorAll('ul.badges li').forEach(badge => {
            this.addFormDeleteLink(badge)
        });
    }

    addFormDeleteLink(item) {
        const removeFormButton = document.createElement('button');
        removeFormButton.className = 'Button Button-delete Button--xs';
        removeFormButton.innerText = 'Supprimer ce badge';
    
        item.append(removeFormButton);
    
        removeFormButton.addEventListener('click', (e) => {
            e.preventDefault();
            item.remove();
        });
    }
    
    addFormToCollection(e) {
        
        const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
    
        const item = document.createElement('li');
    
        item.innerHTML = collectionHolder.dataset.prototype.replace(/__name__/g, collectionHolder.dataset.index);
        const removeFormButton = document.createElement('button');
        removeFormButton.className = 'Button Button-delete Button--xs';
        removeFormButton.innerText = 'Supprimer ce badge';
    
        item.append(removeFormButton);
    
        removeFormButton.addEventListener('click', (e) => {
            e.preventDefault();
            item.remove();
        });
    
        collectionHolder.appendChild(item);
    
        collectionHolder.dataset.index ++;
    }
}
