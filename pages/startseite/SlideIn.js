


const observer = new IntersectionObserver((eingabe) => {
    eingabe.forEach((eingabe) => {
        if (eingabe.isIntersecting) {
            eingabe.target.classList.add('show');
        } else {
            eingabe.target.classList.remove('show');
        }
    });
});

const hiddenElements = document.querySelectorAll('.hidden');
const hiddenElementsReverse = document.querySelectorAll('.hidden2');
const hiddenTopRatedElements = document.querySelectorAll('.hidden3');
hiddenElements.forEach((el) => observer.observe(el));
hiddenElementsReverse.forEach((el) => observer.observe(el));
hiddenTopRatedElements.forEach((el) => observer.observe(el));