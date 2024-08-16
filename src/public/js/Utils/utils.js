export function toggleMenuChange() {
    if (localStorage.getItem('hikingAssociationId')) {
        $('.hiking-association-exists').show();
        $('.hiking-association-does-not-exist').hide();
    } else {
        $('.hiking-association-exists').hide();
        $('.hiking-association-does-not-exist').show();
    }
}