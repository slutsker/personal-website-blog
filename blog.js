function init() {
  $(".contact").blur(function() {
    if ($(this).val() == "") {
      $(this).val("None");
    }
  });
  $(".contact").focus(function() {
    if ($(this).val() == "None") {
      $(this).val("");
    }
  });
  $(".name").blur(function() {
    if ($(this).val() == "") {
      $(this).val("Anonymous");
    }
  });
  $(".name").focus(function() {
    if ($(this).val() == "Anonymous") {
      $(this).val("");
    }
  });

  $("a.reply").click(function() {
    $(this).parent().parent().next().toggle(100);
  });
}

function nothing() {}

$(document).ready(init);