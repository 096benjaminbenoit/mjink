const burgerBtn = document.getElementById('burgerBtn');
const dropDownNav = document.getElementById('dropDownNav');
burgerBtn.addEventListener("click", () => {
if (dropDownNav.classList.contains('hidden')) {
    dropDownNav.classList.remove('hidden');
} else {
    dropDownNav.classList.add('hidden');
}
});

const activePage = window.location.pathname;
const navLinks = document.querySelectorAll('nav a').forEach(link => {
    if(link.href === window.location.href) {
        link.classList.add('text-gray-900');
        link.classList.add('border-gray-900');
    }
})