jQuery(document).ready(function ($) {

    $.CLCRegistration = {

        init: function () {

            window.onload = this.onLoad;

            if(Cookies) {
                let saved_data = Cookies.get('register_cf7_data');

                if(saved_data) {
                    const obj = JSON.parse(saved_data);

                    $('.clc-name-form-control-wrap').val(obj?.name);
                    $('.clc-phone-form-control-wrap').val(obj?.phone);
                    $('.clc-date-form-control-wrap').val(obj?.birthday);
                    $('.clc-register-form input[type=email]').val(obj?.email);
                }
            }


            $('.clc-name-form-control-wrap').mask('S', {
                translation: {
                    'S': {
                        pattern: /[^0-9]/,
                        recursive: true,
                    }
                }
            });


            $('.clc-phone-form-control-wrap').mask('+(84) 00 000 0000', {
                placeholder: "+(84) __ ___ ____"
            });

            $('.clc-date-form-control-wrap').mask('00/00/0000', {
                placeholder: "DD/MM/YYYY"
            });

            let loan = localStorage.getItem("loan_amount");

            if (loan) {
                let loanObject = JSON.parse(loan);
                $('input[name="clc_loan_amount"]').val(loanObject.loanAmount)
            }
        },

        onLoad: function() {
            if (window.history && history.pushState) {
                console.log(document.location.pathname, document.location.pathname === "/registration/")
                if (document.location.pathname === "/registration/") {
                    console.log("inside")
                    if (history.state == null) {
                        history.pushState({'status': 'ongoing'}, null, null);
                    }
                    window.onpopstate = function(event) {
                        const endProgress = confirm("This will end your progress, are you sure you want to go back?");
                        if (endProgress) {
                            window.onpopstate = null;
                            history.back();
                        } else {
                            history.pushState(null, null, null);
                        }
                    };
                }
            }
        }
    }

    $.CLCRegistration.init();

}(jQuery))
