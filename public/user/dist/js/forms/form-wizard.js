// Basic Example
$("#example-basic").steps({
  headerTag: "h3",
  bodyTag: "section",
  transitionEffect: "slideLeft",
  autoFocus: true,
});

// Basic Example with form
var form = $("#example-form");
form.children("div").steps({
  headerTag: "h3",
  bodyTag: "section",
  transitionEffect: "slideLeft",
  onStepChanging: function (event, currentIndex, newIndex) {
    // Always allow previous action
    if (currentIndex > newIndex) {
      return true;
    }
    return form.valid(); // Assuming you want to validate
  },
  onFinishing: function (event, currentIndex) {
    return form.valid(); // Assuming you want to validate
  },
  onFinished: function (event, currentIndex) {
    // Trigger the form submission
    form.submit();
  },
});

// Advance Example
var form = $("#example-advanced-form").show();

form.steps({
  headerTag: "h3",
  bodyTag: "fieldset",
  transitionEffect: "slideLeft",
  onStepChanging: function (event, currentIndex, newIndex) {
    if (currentIndex > newIndex) {
      return true;
    }
    if (currentIndex < newIndex) {
      form.find(".body:eq(" + newIndex + ") label.error").remove();
      form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
    }
    return form.valid(); // Assuming you want to validate
  },
  onFinishing: function (event, currentIndex) {
    return form.valid(); // Assuming you want to validate
  },
  onFinished: function (event, currentIndex) {
    form.submit(); // Trigger the form submission
  },
});

// Dynamic Manipulation
$("#example-manipulation").steps({
  headerTag: "h3",
  bodyTag: "section",
  enableAllSteps: true,
  enablePagination: false,
});

// Vertical Steps
$("#example-vertical").steps({
  headerTag: "h3",
  bodyTag: "section",
  transitionEffect: "slideLeft",
  stepsOrientation: "vertical",
});

// Custom design form example
$(".tab-wizard").steps({
  headerTag: "h6",
  bodyTag: "section",
  transitionEffect: "fade",
  titleTemplate: '<span class="step">#index#</span> #title#',
  labels: {
    finish: "Submit",
  },
  onFinished: function (event, currentIndex) {
    $(".tab-wizard").submit(); // Trigger the form submission
  },
});

// Validation wizard
var form = $(".validation-wizard").show();

$(".validation-wizard").steps({
  headerTag: "h6",
  bodyTag: "section",
  transitionEffect: "fade",
  titleTemplate: '<span class="step">#index#</span> #title#',
  labels: {
    finish: "Submit",
  },
  onStepChanging: function (event, currentIndex, newIndex) {
    return currentIndex > newIndex || form.valid();
  },
  onFinishing: function (event, currentIndex) {
    return form.valid();
  },
  onFinished: function (event, currentIndex) {
    $(".validation-wizard").submit(); // Trigger the form submission
  },
});
