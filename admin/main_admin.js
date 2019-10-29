// for products.php @ child input

$(document).ready(function () {
  $('select[name="parent"]').change(function () {
    get_child_options(); //por alguma razao isto tem de ser com uma anonymous function
  }); // .change vai disparar a ajax request de cada vez que houver uma mudança á option

  $('[data-toggle="tooltip"]').tooltip();
});

function get_child_options(selected) {
  //video 19 - tud o que seja selected é relativo á questao do prepopulate aquando do edit
  if (typeof selected === 'undefined') {
    var selected = '';
  }

  let parentId = document.getElementById("parent").value;
  $.ajax({
    url: "/Php_ecommerce_website/admin/parsers/child_categories.php",
    type: "POST",
    data: {
      parentId: parentId,
      selected: selected
    },
    success: function (data) {
      $("#child").html(data);
    },
    error: function () {
      alert("Something went wrong");
    }
  });
}

// for product.php @modal for size & quantity

function updateSizes() {
  let sizeString = "";


  for (let i = 1; i <= 12; i++) {
    if ($("#size" + i).val() != "") {
      sizeString +=
        $("#size" + i).val() + ":" + $("#quantity" + i).val() + ", ";

      let sizeStringCommaLess = sizeString.slice(0, -2) + "";

      $("#sizes").val(sizeStringCommaLess);
      console.log(sizeStringCommaLess);
    }

  }
}