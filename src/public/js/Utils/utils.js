export function toggleMenuChange() {
    if (localStorage.getItem('hikingAssociationId')) {
        $('.hiking-association-exists').show();
        $('.hiking-association-exists-not').hide();
    } else {
        $('.hiking-association-exists').hide();
        $('.hiking-association-exists-not').show();
    }
}