(function($) {
    "use strict";

    // Spinner
    var spinner = function() {
        setTimeout(function() {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();


    // Initiate the wowjs
    new WOW().init();


    // Fixed Navbar
    $(window).scroll(function() {
        if ($(window).width() < 992) {
            if ($(this).scrollTop() > 45) {
                $('.fixed-top').addClass('bg-white shadow');
            } else {
                $('.fixed-top').removeClass('bg-white shadow');
            }
        } else {
            if ($(this).scrollTop() > 45) {
                $('.fixed-top').addClass('bg-white shadow').css('top', -45);
            } else {
                $('.fixed-top').removeClass('bg-white shadow').css('top', 0);
            }
        }
    });


    // Back to top button
    $(window).scroll(function() {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function() {
        $('html, body').animate({ scrollTop: 0 }, 500, 'easeInOutExpo');
        return false;
    });

    //https://www.codeply.com/p/0CWffz76Q9
    let items = document.querySelectorAll('.products .carousel .carousel-item')

    items.forEach((el) => {
        const minPerSlide = 4
        let next = el.nextElementSibling
        for (var i = 1; i < minPerSlide; i++) {
            if (!next) {
                // wrap carousel by using first child
                next = items[0]
            }
            let cloneChild = next.cloneNode(true)
            el.appendChild(cloneChild.children[0])
            next = next.nextElementSibling
        }
    })

})(jQuery);


$(".qtyminus").on("click", function() {
    var now = $(".qty").val();
    if ($.isNumeric(now)) {
        if (parseInt(now) - 1 > 0) { now--; }
        $(".qty").val(now);
    }
})
$(".qtyplus").on("click", function() {
    var now = $(".qty").val();
    if ($.isNumeric(now)) {
        $(".qty").val(parseInt(now) + 1);
    }
});

function preview() {
    frame.src = URL.createObjectURL(event.target.files[0]);
}

function clearImage() {
    document.getElementById('formFile').value = null;
    frame.src = "";
}

document.querySelectorAll(".generatePasswordBtn").forEach(function(btn) {
    btn.addEventListener("click", function(e) {
        e.preventDefault();  // prevent the default form submission behavior

        let targetId = e.target.getAttribute("data-target");
        let passwordInput = document.getElementById(targetId);

        passwordInput.value = generatePassword(8);
    });
});

function generatePassword(length) {
    if (length < 4) {
        console.error("Password length should be at least 4 to meet the criteria");
        return;
    }

    var charset = {
        lowercase: "abcdefghijklmnopqrstuvwxyz",
        uppercase: "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
        numbers: "0123456789",
        special: "!@#$%^&*()-_=+[]{}|;:'\",.<>?/"
    };

    // Ensure one character from each type is included
    var password = [
        charset.lowercase[Math.floor(Math.random() * charset.lowercase.length)],
        charset.uppercase[Math.floor(Math.random() * charset.uppercase.length)],
        charset.numbers[Math.floor(Math.random() * charset.numbers.length)],
        charset.special[Math.floor(Math.random() * charset.special.length)]
    ];

    // Fill up the remaining length of the password
    for (var i = 4; i < length; i++) {
        var chosenSet = Object.values(charset)[Math.floor(Math.random() * 4)];
        password.push(chosenSet[Math.floor(Math.random() * chosenSet.length)]);
    }

    // Shuffle the password to ensure randomness and convert the array to a string
    return password.sort(() => 0.5 - Math.random()).join("");
}