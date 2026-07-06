        document.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-bs-target="#modalEdit"]');
            if (!btn) return;

            const product = JSON.parse(btn.dataset.product);

            document.getElementById('pid').value = product.id ?? '';
            document.getElementById('uproduct').value = product.name ?? '';
            document.getElementById('ucategory_id').value = product.category_id ?? '';
            document.getElementById('usupplier_id').value = product.supplier_id ?? '';
            document.getElementById('uqr_code').value = product.qrcode ?? '';
            document.getElementById('ubuy_price').value = product.buy_price ?? '';
            document.getElementById('usell_price').value = product.sell_price ?? '';
            document.getElementById('uuom').value = product.uom ?? '';
            document.getElementById('udescription').value = product.description ?? '';
            document.getElementById('uis_active').value = product.is_active ?? '';
        });
        localStorage.removeItem(`tableplus_${location.pathname}_${getProduct}`);
        const table = new TablePlus({
            url : getProduct,
            columns : {
                action : {
                    label : 'Action',
                    render: (row) => {
                        return `
                        <button
                            class="btn btn-sm btn-yellow"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEdit"
                            data-product='${JSON.stringify(row)}'>
                            Edit
                        </button>
                        <button type="submit" class="btn btn-sm btn-danger deleteproduct" data-product="${row.id}">Delete</button>
                        `;
                    },
                    exportText: (row) => {
                        return 'Edit / Hapus'
                    }
                },
                name : 'Product',
                supplier : 'Supplier',
                category : 'Category',
                qrcode: 'QR/Bar Code',
                buy_price: 'Buy Price',
                sell_price: 'Sell Price',
                uom: 'Unit of Material',
                description : 'Description',
                is_active : {
                    label : 'Status',
                    render : (row) => {
                        return row.is_active == 1 ? '<span class="badge bg-green text-green-fg">Active</span>' : '<span class="badge bg-red text-red-fg">Inactive</span>'
                    }
                }
            },
            perPage: 10,
            perPageOptions: [10,20,50,5000],
            rowIdentifier: 'id',
            savePreferences: true,
            forceDefaultPerPage: true
        })
        table.render('#productTable')
$(document).ready(function(){
    crud()
})

function crud()
{
    $('#addproduct').on('click', function(e){
        e.preventDefault()
        var form = new FormData($('#formaddproduct')[0])
        const btnAdd = $('#addproduct')
        const btnLoading = $('#loading')
        btnAdd.hide()
        btnLoading.show()
        $.ajax({
            url : createProduct,
            type: 'POST',
            data : form,
            processData: false,
            contentType: false,
            success: function(response){
                if(response.statusCode === 201){
                    btnAdd.show()
                    btnLoading.hide()
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then((result) => {
                        table.refresh()
                    })
                } else {
                    btnAdd.show()
                    btnLoading.hide()
                    var errmes = ''
                    if(response.statusCode === 422 && typeof response.message === 'object'){
                        for(var field in response.message){
                            if(response.message.hasOwnProperty(field)){
                                response.message[field].forEach(function(message){
                                    errmes += message + '\n'
                                })
                            }
                        }
                    } else {
                        errmes = 'An unexcpected error occured.'
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errmes.trim()
                    })
                }
            }, error: function(err,res,message){
                btnAdd.show()
                btnLoading.hide()
                var errmes = ''
                if(err.responseJSON.statusCode === 422 && typeof err.responseJSON.message === 'object'){
                    for(var field in err.responseJSON.message){
                        if(err.responseJSON.message.hasOwnProperty(field)){
                            err.responseJSON.message[field].forEach(function(message){
                                errmes += message + '\n'
                            })
                        }
                    }
                } else {
                    errmes = err.responseJSON.message
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errmes.trim()
                })
            }
        })
    })
    $('#editproduct').on('click', function(e){
        e.preventDefault()
        var form = new FormData($('#formeditproduct')[0])
        const btnAdd = $('#editproduct')
        const btnLoading = $('#loadingedit')
        btnAdd.hide()
        btnLoading.show()
        $.ajax({
            url : editProduct +'/'+ $('#pid').val(),
            type: 'POST',
            data : form,
            processData: false,
            contentType: false,
            success: function(response){
                if(response.statusCode === 200){
                    btnAdd.show()
                    btnLoading.hide()
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then((result) => {
                        table.refresh()
                    })
                } else {
                    btnAdd.show()
                    btnLoading.hide()
                    var errmes = ''
                    if(response.statusCode === 422 && typeof response.message === 'object'){
                        for(var field in response.message){
                            if(response.message.hasOwnProperty(field)){
                                response.message[field].forEach(function(message){
                                    errmes += message + '\n'
                                })
                            }
                        }
                    } else {
                        errmes = 'An unexcpected error occured.'
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errmes.trim()
                    })
                }
            }, error: function(err,res,message){
                btnAdd.show()
                btnLoading.hide()
                var errmes = ''
                if(err.responseJSON.statusCode === 422 && typeof err.responseJSON.message === 'object'){
                    for(var field in err.responseJSON.message){
                        if(err.responseJSON.message.hasOwnProperty(field)){
                            err.responseJSON.message[field].forEach(function(message){
                                errmes += message + '\n'
                            })
                        }
                    }
                } else {
                    errmes = err.responseJSON.message
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errmes.trim()
                })
            }
        })
    })
    $('#importproduct').on('click', function(e){
        e.preventDefault()
        const formExcel = new FormData($('#formimportproduct')[0])
        $(this).hide()
        $('#loadingimport').show()
        $.ajax({
            type : 'POST',
            url: urlImport,
            data : formExcel,
            processData: false,
            contentType: false,
            success: function(res,status,xhr){
                if(res.statusCode === 200){
                    $('#loadingimport').hide()
                    $('#importproduct').show()
                    Swal.fire({
                        title: 'success',
                        icon: 'success',
                        text: res.message
                    }).then((result)=>{
                        $('#datares').html('')
                        res.results.results.forEach((data) => {
                            const table = 
                            `<tr>
                                    <td class="fw-semibold">${data.name ?? '-'}</td>
                                    <td class="text-muted">${data.message}</td>
                                    <td>${data.row}</td>
                                    <td>
                                        <span class="${data.status == 'skipped' ? 'badge bg-yellow text-yellow-fg' : data.status == 'success' ? 'badge bg-green text-green-fg' : 'badge bg-red text-red-fg'}}">
                                            ${data.status}
                                        </span>
                                    </td>
                                </tr>
                            `;
                            $('#datares').append(table)
                        })
                        $('#tableresult-wrapper').show()
                        table.refresh
                    })
                }
            }, error: function(xhr, status, error){
                $('#loadingimport').hide()
                $('#importproduct').show()
                const errMes = JSON.parse(xhr.responseText)
                Swal.fire({
                    title: 'error',
                    icon: 'error',
                    text: errMes.message
                })
            }
        })
    })
    $('#modal-import').on('show.bs.modal', function () {
        $('#datares').html('')
        $('#tableresult-wrapper').hide()
    })
    $(document).on('click','.deleteproduct', function(e){
        e.preventDefault()
        const productId = $(this).data('product');
            Swal.fire({
                title: 'Delete',
                icon: 'warning',
                text: 'Yakin ingin dihapus?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'DELETE',
                        url: deleteProduct +'/'+ productId,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            if (response.statusCode === 200) {
                                Swal.fire({
                                    title: 'Success',
                                    icon: 'success',
                                    text: response.message,
                                    timer: 1500,
                                    timerProgressBar: true,
                                });
                                table.refresh();
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    icon: 'error',
                                    text: 'Data Error',
                                    timer: 1500,
                                    timerProgressBar: true,
                                });
                            }
                        }, error: function(err,res,message){
                            var errmes = ''
                            if(err.responseJSON.statusCode === 422 && typeof err.responseJSON.message === 'object'){
                                for(var field in err.responseJSON.message){
                                    if(err.responseJSON.message.hasOwnProperty(field)){
                                        err.responseJSON.message[field].forEach(function(message){
                                            errmes += message + '\n'
                                        })
                                    }
                                }
                            } else {
                                errmes = err.responseJSON.message
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errmes.trim()
                            })
                        }
                    })
                }
            })
    })
}