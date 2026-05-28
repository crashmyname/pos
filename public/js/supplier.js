        document.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-bs-target="#modalEdit"]');
            if (!btn) return;

            const supplier = JSON.parse(btn.dataset.supplier);

            document.getElementById('sid').value = supplier.id ?? '';
            document.getElementById('usupplier').value = supplier.name ?? '';
            document.getElementById('uphone').value = supplier.phone ?? '';
            document.getElementById('uemail').value = supplier.email ?? '';
            document.getElementById('udescription').value = supplier.description ?? '';
        });
        const table = new TablePlus({
            url : getSupplier,
            columns : {
                action : {
                    label : 'Action',
                    render: (row) => {
                        return `
                        <button
                            class="btn btn-sm btn-yellow"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEdit"
                            data-supplier='${JSON.stringify(row)}'>
                            Edit
                        </button>
                        <button type="submit" class="btn btn-sm btn-danger deletesupplier" data-supplier="${row.id}">Delete</button>
                        `;
                    },
                    exportText: (row) => {
                        return 'Edit / Hapus'
                    }
                },
                name : 'Supplier',
                phone : 'Phone',
                email: 'Email',
                description : 'Description',
            },
            perPage: 10,
            perPageOptions: [10,20,50,100],
            rowIdentifier: 'id',
            savePreferences: true
        })
        table.render('#supplierTable')
$(document).ready(function(){
    crud()
})

function crud()
{
    $('#addsupplier').on('click', function(e){
        e.preventDefault()
        var form = new FormData($('#formaddsupplier')[0])
        const btnAdd = $('#addsupplier')
        const btnLoading = $('#loading')
        btnAdd.hide()
        btnLoading.show()
        $.ajax({
            url : createSupplier,
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
    $('#editsupplier').on('click', function(e){
        e.preventDefault()
        var form = new FormData($('#formeditsupplier')[0])
        const btnAdd = $('#editsupplier')
        const btnLoading = $('#loadingedit')
        btnAdd.hide()
        btnLoading.show()
        $.ajax({
            url : editSupplier +'/'+ $('#sid').val(),
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
    $('#importsupplier').on('click', function(e){
        e.preventDefault()
        const formExcel = new FormData($('#formimportsupplier')[0])
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
                    $('#importsupplier').show()
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
                $('#importsupplier').show()
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
    $(document).on('click','.deletesupplier', function(e){
        e.preventDefault()
        const supplierId = $(this).data('supplier');
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
                        url: deleteSupplier +'/'+ supplierId,
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