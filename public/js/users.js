        document.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-bs-target="#modalEdit"]');
            if (!btn) return;

            const user = JSON.parse(btn.dataset.user);

            document.getElementById('uid').value = user.id ?? '';
            document.getElementById('uusername').value = user.username ?? '';
            document.getElementById('uname').value = user.name ?? '';
            document.getElementById('urole').value = user.role ?? '';
        });
        const table = new TablePlus({
            url : getUser,
            columns : {
                action : {
                    label : 'Action',
                    render: (row) => {
                        return `
                        <button
                            class="btn btn-sm btn-yellow"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEdit"
                            data-user='${JSON.stringify(row)}'>
                            Edit
                        </button>
                        <button type="submit" class="btn btn-sm btn-danger deleteuser" data-user="${row.id}">Delete</button>
                        `;
                    },
                    exportText: (row) => {
                        return 'Edit / Hapus'
                    }
                },
                username : 'NIK',
                name : 'Name',
                role : 'Role',
            },
            perPage: 10,
            perPageOptions: [10,20,50,100],
            rowIdentifier: 'id',
            savePreferences: true
        })
        table.render('#userTable')
$(document).ready(function(){
    crud()
})

function crud()
{
    $('#adduser').on('click', function(e){
        e.preventDefault()
        var form = new FormData($('#formadduser')[0])
        const btnAdd = $('#adduser')
        const btnLoading = $('#loading')
        btnAdd.hide()
        btnLoading.show()
        $.ajax({
            url : createUser,
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
    $('#edituser').on('click', function(e){
        e.preventDefault()
        var form = new FormData($('#formedituser')[0])
        const btnAdd = $('#edituser')
        const btnLoading = $('#loadingedit')
        btnAdd.hide()
        btnLoading.show()
        $.ajax({
            url : editUser +'/'+ $('#uid').val(),
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
    $(document).on('click','.deleteuser', function(e){
        e.preventDefault()
        const username = $(this).data('user');
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
                        url: deleteUser +'/'+ username,
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