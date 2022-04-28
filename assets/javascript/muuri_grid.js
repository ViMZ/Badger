import debounce from 'debounce';
import Muuri from 'muuri';

if (document.getElementsByClassName('grid').length !== 0) {
    var grid = new Muuri('.grid');
    var ownedFilter = document.getElementById('grid-filter-owned');
    var notOwnedFilter = document.getElementById('grid-filter-not-owned');
    var noFilter = document.getElementById('grid-filter-none');
    var searchFilter = document.getElementById('grid-filter-search');

    const debouncedSearch = debounce((e) => {
        const query = e.target.value;
        grid.filter((item) => {
            const text = item._element.innerText;

            if (typeof window.gridFilter !== 'undefined') {
                return text.includes(query) && item._element.hasAttribute(window.gridFilter);
            } else {
                return text.includes(query);
            }
        })
    }, 200);

    const filters = document.getElementsByClassName('gridfilter');
    function focus(item) {
        for (const filter of filters) {
            filter.classList.remove('Button--selected');
        }
        item.classList.add('Button--selected');
    }

    ownedFilter.addEventListener('click', (e) => {
        window.gridFilter = 'data-owned';
        grid.filter('[data-owned]');
        focus(e.currentTarget);
    })
    notOwnedFilter.addEventListener('click', (e) => {
        window.gridFilter = 'data-not-owned';
        grid.filter('[data-not-owned]');
        focus(e.currentTarget);
    })
    noFilter.addEventListener('click', (e) => {
        grid.filter(() => {return true});
        focus(e.currentTarget);
    })

    searchFilter.addEventListener('keyup', (e) => debouncedSearch(e));
}
