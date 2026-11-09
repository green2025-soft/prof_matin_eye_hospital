import $ from './jquery';

// New text is eligible only after this exact search has successfully returned no matches.
export function createEmptySearchGuard($select) {
    let term = '';
    let currentQuery = null;
    let emptyQuery = null;

    // Refresh the caller's Create control when search eligibility changes.
    function notify() {
        $select.trigger('prescription:searchEligibility');
    }

    // Forget the previous result as soon as the user types a different name.
    function setTerm(value) {
        const next = String(value || '').trim();
        if (next === term) return;

        term = next;
        currentQuery = null;
        emptyQuery = null;
        notify();
    }

    // Attach this exact search/page to its request so stale results can be identified.
    function begin(params) {
        term = String(params.term || '').trim();
        currentQuery = { term, page: Number(params.page || 1) };
        emptyQuery = null;
        params.creationQuery = currentQuery;
        notify();
    }

    // Allow creation only when the current first page has no matches or later pages.
    function complete(params, resultCount, more) {
        const query = params.creationQuery;
        if (!query || query !== currentQuery || query.term !== term) return false;

        emptyQuery = query.page === 1 && resultCount === 0 && !more ? query : null;
        notify();
        return true;
    }

    // Confirm the requested new name still belongs to the completed empty search.
    function canCreate(value) {
        return Boolean(term && emptyQuery && term === String(value || '').trim());
    }

    // Track typing in both Select2 inputs without registering duplicate handlers.
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
