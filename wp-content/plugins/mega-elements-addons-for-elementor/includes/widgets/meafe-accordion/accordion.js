var MEAFEAccordionHandler = function($scope, $) {
    var $advanceAccordion = $scope.find(".meafe-accordion"),
        $accordionHeader = $scope.find(".meafe-accordion-header"),
        $accordionType = $advanceAccordion.data("accordion-type"),
        $accordionSpeed = $advanceAccordion.data("toogle-speed");

    // Open default actived tab
    $accordionHeader.each(function() {
        if ($(this).hasClass("active-default")) {
            $(this).addClass("show active");
            $(this)
                .next()
                .slideDown($accordionSpeed);
        }
    });

    // Remove multiple click event for nested accordion
    $accordionHeader.unbind("click");

    $accordionHeader.click(function(e) {
        e.preventDefault();

        var $this = $(this);

        if ($accordionType === "accordion") {
            if ($this.hasClass("show")) {
                $this.removeClass("show active");
                $this.next().slideUp($accordionSpeed);
            } else {
                $this
                    .parent()
                    .parent()
                    .find(".meafe-accordion-header")
                    .removeClass("show active");
                $this
                    .parent()
                    .parent()
                    .find(".meafe-accordion-content")
                    .slideUp($accordionSpeed);
                $this.toggleClass("show active");
                $this.next().slideToggle($accordionSpeed);
            }
        } else {
            // For acccordion type 'toggle'
            if ($this.hasClass("show")) {
                $this.removeClass("show active");
                $this.next().slideUp($accordionSpeed);
            } else {
                $this.addClass("show active");
                $this.next().slideDown($accordionSpeed);
            }
        }
    });
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/meafe-accordion.default",
        MEAFEAccordionHandler
    );
});
