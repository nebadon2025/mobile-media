$(function() {
  $("li.content").hide();
  $("ul.navigation").delegate("li.toggle", "click", function() { 
      $(this).next().toggle("fast").siblings(".content").hide("fast");
  });
});