


$('#form').on('submit', function (e) {
    alert('abc');
    // e.preventDefault();
    // const formData = new FormData(e.target);
    showLoading();
    // fetch('getmaktob', {
    //     method: 'POST',
    //     headers: {
    //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //     },
    //     body: formData
    // })
    //     .then(response => response.json())
    //     .then(data => {
    //         if (data.success) {

    //             hideLoading()

    //             // $('#submit-btn').attr('disabled',true)
    //             $('#maktobData').empty();
    //             showMaktobData(data);
    //             $('#maktobNotFoundMessage').css('visibility', 'hidden')
    //             $('#maktob_serial_no').val(data.maktob.serial_no)


    //         } else {

    //             hideLoading()
    //             $('#maktobNotFoundMessage').css('visibility', 'visible')
    //             $('#maktobData').css('visibility', 'hidden');

    //         }
    //     })
    //     .then(error => console.log(error));
})
