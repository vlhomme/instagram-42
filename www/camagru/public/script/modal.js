function openModal(e) {
    e.preventDefault();
    const modal = document.querySelector(e.target.getAttribute('href'));
    modal.style.display = null;
    modal.removeAttribute('aria-hidden');
    modal.setAttribute('aria-modal', 'true');
    modal.addEventListener('click', closeModal);
}

const closeModal = function (e) {
    if (modal === null) return;
    e.preventDefault();
    modal.style.display = "none";
    modal.removeAttribute('aria-hidden');
    modal.setAttribute('aria-modal', 'true');
    modal.removeEventListener('click', closeModal);
    modal = null;
}

document.querySelectorAll('.js_modal').forEach(a => {
    a.addEventListener('click', openModal)
})