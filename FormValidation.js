

(() => {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation')
    const searchInput = document.querySelectorAll('.searchInput')

    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {


            if (!form.checkValidity() || searchInput.value === "") {
                event.preventDefault()
                event.stopPropagation()
                form.classList.add('is-invalid')
            }

            form.classList.add('was-validated')



        }, false)
    })
})()