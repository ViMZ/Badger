import Muuri from 'muuri';

if (document.getElementsByClassName('grid').length !== 0) {
    var grid = new Muuri('.grid');
    var ownedFilter = document.getElementById('grid-filter-owned');
    var notOwnedFilter = document.getElementById('grid-filter-not-owned');
    var noFilter = document.getElementById('grid-filter-none');
    
    ownedFilter.addEventListener('click', () => {
        grid.filter('[data-owned]');
    })
    notOwnedFilter.addEventListener('click', () => {
        grid.filter('[data-not-owned]');
    })
    noFilter.addEventListener('click', () => {
        grid.filter((item) => {return true});
    })
}
