function detailsModal(id) {
  let data = {
    id: id
  };

  /* 
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/Php_ecommerce_website/includes/details_modal.php');
        xhr.send(data);

        xhr.onreadystatechange = function (data) {
            let DONE = 4; // readyState 4 means the request is done.
            let OK = 200; // status 200 is a successful return.
            if (xhr.readyState === DONE) {
                if (xhr.status === OK) {

                    $('body').append(xhr.responseText);
                    $("#details-1").modal('toggle');
                } else {
                    console.log('Error: ' + xhr.status); // An error occurred during the request.
                }
            }
        }; */

  jQuery.ajax({
    url: "/Php_ecommerce_website/includes/details_modal.php",
    method: "post",
    data: data,
    success: function (data) {
      $("#details-1").remove();
      $("body").append(data);
      $("#details-1").modal("toggle");
    },
    error: function () {
      alert("No dice");
    }
  });
}

function updateCart(mode, edit_id, edit_size) {
  let data = {
    "mode": mode,
    "edit_id": edit_id,
    "edit_size": edit_size
  };

  $.ajax({
    url: "/Php_ecommerce_website/admin/parsers/update_cart.php",
    method: "POST",
    data: data,
    success: function () {
      location.reload(); // isto é por causa dos cookies, é necessario a pagina recarregar para o cookie tomar efeito, portanto aqui o js vai fazer isso com esta função 

    },
    error: function () {
      alert("No dice");
    }
  })
}

function add_to_cart() {
  let modalErrors = document.getElementById("modal-errors");
  modalErrors.innerHTML = "";
  let size = document.getElementById("size").value;
  let quantity = document.getElementById("quantity").value;
  let available = document.getElementById("available").value;
  console.log(available);
  let error = "";
  let data = $("#add_product_form").serialize();

  //if empty or 0

  if (size == "" || quantity == "" || quantity == 0) {
    error +=
      '<p class="text-light text-center bg-danger">Tem de escolher um tamanho e uma quantidade.</p>';
    modalErrors.innerHTML = error;
    console.log(error);
    return;
  }
  // if exceeding quantity
  else if (quantity > available) {
    error +=
      '<p class="text-light text-center bg-danger">Escolha um numero igual ou abaixo da quantidade disponivel.</p>';
    modalErrors.innerHTML = error;
    return;
  } else {



    $.ajax({
      url: "/Php_ecommerce_website/admin/parsers/add_cart.php",
      method: "POST",
      data: data,
      success: function () {
        location.reload(); // isto é por causa dos cookies, é necessario a pagina recarregar para o cookie tomar efeito, portanto aqui o js vai fazer isso com esta função

      },
      error: function () {
        alert("No dice");
      }
    });
  }
}