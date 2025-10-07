/* Creator: ghost1473 */
function showPopup(message, type = 'error') {
    if (!document.body) return;
    let popup = document.createElement('div');
    popup.className = 'popup ' + type;
    popup.setAttribute('role', 'alert');
    popup.setAttribute('aria-live', 'assertive');
    popup.setAttribute('tabindex', '0');
    popup.innerText = message;
    document.body.appendChild(popup);
    popup.focus();
    setTimeout(() => {
        popup.style.opacity = '0';
        popup.style.top = '10px';
        setTimeout(() => { popup.remove(); }, 400);
    }, 2600);
}

// Real-time transaction status polling (for dashboard)
function pollTransactionStatus(accountId, callback) {
    setInterval(function() {
        fetch(`/Group/public/index.php?url=transaction/status&account_id=${accountId}`)
            .then(res => res.json())
            .then(data => callback(data));
    }, 3000);
}
