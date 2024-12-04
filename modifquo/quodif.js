$(document).ready(function() {
    // Cargar cotizaciones en el primer select
    $.ajax({
        url: '../eliquo/load_quotations.php',
        method: 'GET',
        success: function(data) {
            const quotations = JSON.parse(data);
            const quotationSelect = $('#quotationSelect');
            quotationSelect.empty();
            quotations.forEach(quotation => {
                quotationSelect.append(new Option(quotation.id, quotation.id));
            });
        },
        error: function() {
            alert('Error al cargar las cotizaciones.');
        }
    });

    // Cargar proveedores en el segundo modal
    $.ajax({
        url: 'load_suppliers.php',
        method: 'GET',
        success: function(data) {
            const suppliers = JSON.parse(data);
            const supplierSelect = $('#supplierSelect');
            supplierSelect.empty();
            suppliers.forEach(supplier => {
                supplierSelect.append(new Option(supplier.legalName, supplier.id));
            });
        },
        error: function() {
            alert('Error al cargar los proveedores.');
        }
    });

    // Mostrar datos de cotización seleccionada
    $('#modifyButton').click(function() {
        const quotationId = $('#quotationSelect').val();
        $.ajax({
            url: 'get_quotation.php',
            method: 'GET',
            data: { id: quotationId },
            success: function(data) {
                const quotation = JSON.parse(data);
                $('#issueDate').val(quotation.issueDate);
                $('#total').val(quotation.total);
                $('#subtotal').val(quotation.subtotal);
                $('#supplierSelect').val(quotation.supplierId);
            },
            error: function() {
                alert('Error al cargar los datos de la cotización.');
            }
        });
    });

    // Actualizar datos de cotización
    $('#updateButton').click(function() {
        const quotationId = $('#quotationSelect').val();
        const updatedData = {
            id: quotationId,
            issueDate: $('#issueDate').val(),
            total: $('#total').val(),
            subtotal: $('#subtotal').val(),
            supplierId: $('#supplierSelect').val()
        };

        $.ajax({
            url: 'update_quotation.php',
            method: 'POST',
            data: updatedData,
            success: function(response) {
                alert(response);
                $('#modifyQuotationModal').modal('hide');
            },
            error: function() {
                alert('Error al actualizar la cotización.');
            }
        });
    });
});


document.addEventListener('hidden.bs.modal', function () {
    document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
        backdrop.remove();
    });
  });
  