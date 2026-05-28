        document.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-bs-target="#modalEdit"]');
            if (!btn) return;

            const category = JSON.parse(btn.dataset.category);

            document.getElementById('cid').value = category.id ?? '';
            document.getElementById('ucategory').value = category.name ?? '';
            document.getElementById('udescription').value = category.description ?? '';
        });
        const table = new TablePlus({
            url : getCategory,
            columns : {
                action : {
                    label : 'Action',
                    render: (row) => {
                        return `
                        <button
                            class="btn btn-sm btn-yellow"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEdit"
                            data-category='${JSON.stringify(row)}'>
                            Edit
                        </button>
                        <button type="submit" class="btn btn-sm btn-danger deletecategory" data-category="${row.id}">Delete</button>
                        `;
                    },
                    exportText: (row) => {
                        return 'Edit / Hapus'
                    }
                },
                name : 'Category',
                description : 'Description',
            },
            perPage: 10,
            perPageOptions: [10,20,50,100],
            rowIdentifier: 'id',
            savePreferences: true
        })
        table.render('#categoryTable')
$(document).ready(function(){
    crud()
})

function crud()
{
    $('#addcategory').on('click', function(e){
        e.preventDefault()
        var form = new FormData($('#formaddcategory')[0])
        const btnAdd = $('#addcategory')
        const btnLoading = $('#loading')
        btnAdd.hide()
        btnLoading.show()
        $.ajax({
            url : createCategory,
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
    $('#editcategory').on('click', function(e){
        e.preventDefault()
        var form = new FormData($('#formeditcategory')[0])
        const btnAdd = $('#editcategory')
        const btnLoading = $('#loadingedit')
        btnAdd.hide()
        btnLoading.show()
        $.ajax({
            url : editCategory +'/'+ $('#cid').val(),
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
    $('#importcategory').on('click', function(e){
        e.preventDefault()
        const formExcel = new FormData($('#formimportcategory')[0])
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
                    $('#importcategory').show()
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
                $('#importcategory').show()
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
    $(document).on('click','.deletecategory', function(e){
        e.preventDefault()
        const categoryId = $(this).data('category');
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
                        url: deleteCategory +'/'+ categoryId,
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