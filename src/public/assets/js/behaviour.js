

(function($){

    var toggle = (function () {

            var current = null;

            return function (element) {
                if (current) {
                    current.removeClass('open');
                }
                if (current && element && (current[0] === element[0])) {
                    element = null;
                }
                if (element) {
                    element.addClass('open');
                }
                current = element;
            };
        }()),
        activate = function (element) {
            var parent = element.parent();
            if (!parent._current) {
                parent._current = $(parent.children('.selected')[0]);
            }
            parent._current.removeClass('selected');
            element.addClass('selected');
            parent._current = element;
        };

    $(document).on('click', function(event){

        var target = $(event.target),
            dropdown = target.parents('.dropdown');

        dropdown = target.hasClass('dropdown')
                 ? target
                 : dropdown.hasClass('dropdown')
                     ? dropdown
                     : null;


        toggle(dropdown);
        if (target.hasClass('option')) {
            activate(target);
        }

    });

}(jQuery));
