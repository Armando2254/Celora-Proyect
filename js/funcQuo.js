// Función para mostrar el modal
function mostrarModal() {
    document.getElementById("myModal").style.display = "block";
}

// Función para cerrar el modal
function cerrarModal() {
    document.getElementById("myModal").style.display = "none";
}








    // Manejar clic en las tarjetas
    document.querySelectorAll('.ola').forEach(card => {
        card.addEventListener('click', function () {
            const requisitionId = this.getAttribute('data-id'); // Obtener el ID de la tarjeta
            fetch(`getQuotationDetails.php?id=${requisitionId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('modal-content').innerHTML = data; // Cargar tabla dinámica
                    const modal = new bootstrap.Modal(document.getElementById('requisitionModal'));
                    modal.show(); // Mostrar el modal
                })
                .catch(error => console.error('Error:', error));
        });
    });
  // Validar cantidad en tiempo real
document.addEventListener("input", function (event) {
    if (event.target.name === "cantidad[]") {
        const cantidad = event.target.value;
        const errorMessage = event.target.nextElementSibling; // Busca el <small> después del input

        if (cantidad <= 0) {
            errorMessage.style.display = "block"; // Mostrar mensaje de error
        } else {
            errorMessage.style.display = "none"; // Ocultar mensaje de error
        }
    }
});

// Validar al enviar el formulario
document.querySelector("form").addEventListener("submit", function (event) {
    const cantidades = document.querySelectorAll("input[name='cantidad[]']");
    let valid = true;

    cantidades.forEach(cantidad => {
        const errorMessage = cantidad.nextElementSibling;

        if (cantidad.value <= 0) {
            errorMessage.style.display = "block";
            valid = false;
        }
    });

    if (!valid) {
        event.preventDefault(); // Evitar envío del formulario
        alert("Por favor, corrige los errores antes de continuar.");
    }
});


















$(document).ready(function () {
    // Cargar el folio con PHP
    $.ajax({
        url: 'obtener_folio.php',
        method: 'GET',
        success: function (data) {
            $('#folio').val(data);
        }
    });

    // Mostrar la fecha de hoy
    const today = new Date().toISOString().split('T')[0];
    $('#fecha').val(today);

    // Lógica para añadir/eliminar productos
    $('#addProductButton').click(function () {
        const productGroup = $('.product-group:first').clone();
        productGroup.find('input, select').val('');
        productGroup.find('.btn-remove-product').prop('disabled', false);
        $('#productsContainer').append(productGroup);
    });

    $(document).on('click', '.btn-remove-product', function () {
        if ($('.product-group').length > 1) {
            $(this).closest('.product-group').remove();
        }
    });
});