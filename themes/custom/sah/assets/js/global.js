(function ($) {
  Drupal.behaviors.global = {
    attach: function (context) {

      // Removing the file upload 'Remove' button for students
      $(".is-student #edit-field-as-attachment-wrapper table th:last-child, .is-student #edit-field-as-attachment-wrapper table td:last-child").remove();

    }
  }
})(jQuery);
