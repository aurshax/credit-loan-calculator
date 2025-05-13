jQuery(document).ready(function($){

    $.CreditLoanCalculator = {
        sliderClass: '.cls-loan-calculator-wrapper .cls-range-slider',

        sliderAmountId: '#cls-amount',

        sliderAmountClass: '.cls-amount',

        submitButtonId: '#cls-submit-for-loan',

        moneyFormat: wNumb({
            decimals: 0,
            thousand: ' ',
            suffix: ' ₫'
        }),

        init: function() {
            var slider = $('#cls-loan-calculator-slider');

            noUiSlider.create(slider[0], {
                start: [500000],
                connect: 'lower',
                range: {
                    'min': 500000,
                    'max': 200000000,
                },
                step: 250000,
                pips: {
                    mode: 'positions',
                    values: [0,  100],
                    density: 20,
                    format: wNumb({
                        decimals: 0,
                        thousand: ' ',
                        suffix: ' ₫'
                    })
                }
            }).on('update', this.fnSlide);

            $( this.submitButtonId ).on('click', this.fnSubmit )
        },

        fnSlide: function(values) {
            var money = parseFloat(values[0]);
            var formatted = $.CreditLoanCalculator.moneyFormat.to(money);

            $( $.CreditLoanCalculator.sliderAmountId ).val( formatted );
            $( $.CreditLoanCalculator.sliderAmountClass ).text( formatted );
        },

        fnSubmit: function(e) {
            e.preventDefault();

            const loan = $($.CreditLoanCalculator.sliderAmountId).val();

            localStorage.setItem("loan_amount", JSON.stringify({ loanAmount: loan }));

            if(clc && clc.registration_link) {
                window.location.href = clc.registration_link
            }
        }
    }

    $.CreditLoanCalculator.init();

}( jQuery ) )
