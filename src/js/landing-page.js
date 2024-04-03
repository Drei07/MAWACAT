
$(document).ready(function() {
    $(window).scroll(function() {
        if ($(window).scrollTop() > 100) {
            $('#scrollToTop').fadeIn();
        } else {
            $('#scrollToTop').fadeOut();
        }
    });
});

function scrolltop() {
    $('html, body').animate({
        scrollTop: 0
    }, 300);
}

//scroll to top----------------------------------------------------------------------------------------------------->
AOS.init({
    offset: 150,
    duration: 1500,

});

//modals----------------------------------------------------------------------------------------------------->
(function() {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
})();

//form alert----------------------------------------------------------------------------------------------------->
function IsEmpty() {
    if (document.forms['form'].first_name.value === "") {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Please filled out all the fields';
        setTimeout(function() {
            var msg = document.getElementById("message").innerHTML = '';
        }, 3000);

    }


    if (document.forms['form'].middle_name.value === "") {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Please filled out all the fields';
        setTimeout(function() {
            var msg = document.getElementById("message").innerHTML = '';
        }, 3000);

    }

    if (document.forms['form'].last_name.value === "") {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Please filled out all the fields';
        setTimeout(function() {
            var msg = document.getElementById("message").innerHTML = '';
        }, 3000);

    }
    if (document.forms['form'].phone_number.value === "") {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Please filled out all the fields';
        setTimeout(function() {
            var msg = document.getElementById("message").innerHTML = '';
        }, 3000);

    }


    if (document.forms['form'].email.value === "") {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Please filled out all the fields';
        setTimeout(function() {
            var msg = document.getElementById("message").innerHTML = '';
        }, 3000);

    }
    return true;
};

//numbers only----------------------------------------------------------------------------------------------------->
$('.numbers').keypress(function(e) {
    var x = e.which || e.keycode;
    if ((x >= 48 && x <= 57) || x == 8 ||
        (x >= 35 && x <= 40) || x == 46)
        return true;
    else
        return false;
});

     //cancel
     $('.cancel').on('click', function(e) {
        e.preventDefault();
        const href = $(this).attr('href')

        swal({
                title: "Cancel?",
                text: "Are you sure do you want to cancel?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    document.location.href = href;
                }
            });
    })