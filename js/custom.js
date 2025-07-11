(function($) {
    $(document).ready(function() {
    /* IF YOU WANT TO APPLY SOME BASIC JQUERY TO REMOVE THE VIDEO BACKGROUND ON A SPECIFIC VIEWPORT MANUALLY
     var is_mobile = false;
    if( $('.player').css('display')=='none') {
        is_mobile = true;       
    }
    if (is_mobile == true) {
        //Conditional script here
        $('.big-background, .small-background-section').addClass('big-background-default-image');
    }else{
        $(".player").mb_YTPlayer(); 
    }
    });
*/
    /*  IF YOU WANT TO USE DEVICE.JS TO DETECT THE VIEWPORT AND MANIPULATE THE OUTPUT  */

        //Device.js will check if it is Tablet or Mobile - http://matthewhudson.me/projects/device.js/
        if (!device.tablet() && !device.mobile()) {
            $(".player").mb_YTPlayer();
        } else {
            //jQuery will add the default background to the preferred class 
            $('.video-background').addClass(
                'video-background-default-image');
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        const observerOptions = {
            threshold: 0.15
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.section-text, .section-wappen').forEach(el => {
            observer.observe(el);
        });

        // Parallax für Header und Wappen
        window.addEventListener('scroll', function() {
            const scrolled = window.scrollY;
            const header = document.querySelector('.parallax-header');
            const wappen = document.querySelectorAll('.parallax-wappen');
            const title = document.querySelector('.gold-title');
            if(header) {
                header.style.backgroundPosition = `center ${scrolled * 0.2}px`;
            }
            wappen.forEach((el, i) => {
                el.style.transform = `translateY(${scrolled * (0.18 + i*0.07)}px)`;
            });
            if(title) {
                title.style.transform = `translateY(${scrolled * 0.09}px)`;
            }
        });

        // Hamburger Off-Canvas Navigation
        const navToggle = document.querySelector('.nav-toggle');
        const navMenu = document.querySelector('.nav-menu');
        const navLinks = document.querySelectorAll('.nav-menu a');
        navToggle && navToggle.addEventListener('click', function(e) {
            navToggle.classList.toggle('active');
            navMenu.classList.toggle('open');
            document.body.classList.toggle('menu-open');
        });
        navLinks.forEach(link => link.addEventListener('click', function() {
            navToggle.classList.remove('active');
            navMenu.classList.remove('open');
            document.body.classList.remove('menu-open');
        }));
        document.addEventListener('click', function(e) {
            if(navMenu.classList.contains('open') && !navMenu.contains(e.target) && !navToggle.contains(e.target)) {
                navToggle.classList.remove('active');
                navMenu.classList.remove('open');
                document.body.classList.remove('menu-open');
            }
        });
    });
})(jQuery);