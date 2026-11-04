import $ from './jquery';

// New text is eligible only after this exact search has successfully returned no matches.
export function createEmptySearchGuard($select) {
    let term = '';
    let currentQuery = null;
    let emptyQuery = null;

    function notify() {
        $select.trigger('prescription:searchEligibility');
    }

    function setTerm(value) {
        const next = String(value || '').trim();
        if (next === term) return;

        term = next;
        currentQuery = null;
        emptyQuery = null;
        notify();
    }

    function begin(params) {
        term = String(params.term || '').trim();
        currentQuery = { term, page: Number(params.page || 1) };
        emptyQuery = null;
        params.creationQuery = currentQuery;
        notify();
    }

    function complete(params, resultCount, more) {
        const query = params.creationQuery;
        if (!query || query !== currentQuery || query.term !== term) return false;

        emptyQuery = query.page === 1 && resultCount === 0 && !more ? query : null;
        notify();
        return true;
    }

    function canCreate(value) {
        return Boolean(term && emptyQuery && term === String(value || '').trim());
    }

    function bindSearch() {
        const picker = $select.data('select2');
        picker.$container.add(picker.$dropdown).find('.select2-search__field')
            .off('input.emptySearch').on('input.emptySearch', function () {
                setTerm($(this).val());
            });
    }

    $select.on('select2:open.emptySearch', bindSearch);
    $select.on('select2:selecting.emptySearch', event => {
        const choice = event.params.args.data;
        if (choice.newTag && !canCreate(choice.name || choice.text)) event.preventDefault();
    });

    return { setTerm, begin, complete, canCreate, bindSearch };
}
