
function showMaktobData(data){
    $('#maktobData').css('visibility','visible');
    $('#maktobData').append(`
        <div class="col-span-3">
        <h1 class="font-bold text-center">معلومات تآییدی مکتوب متذکر از سیستم عرایض</h1>
        </div>

        <div class="flex flex-col space-y-1 text-xs">
            <label class="font-bold">نوعیت مکتوب</label>
            <input class="border border-gray-100" title ="نوعیت مکتوب" value="${data.maktob.type}" readonly />
        </div>
        <div class="flex flex-col space-y-1 text-xs">
            <label class="font-bold">تاریخ</label>
            <input class="border border-gray-100" title ="نوعیت مکتوب" value="${data.maktob.date}" readonly />
        </div>
        <div class="flex flex-col space-y-1 text-xs">
            <label class="font-bold">مرسل الیه</label>
            <input class="border border-gray-100" title ="نوعیت مکتوب" value="${data.maktob.destination}" readonly />
        </div>
        <div class="flex flex-col space-y-1 text-xs col-span-3">
            <label class="font-bold">موضوع</label>
            <input class="border border-gray-100" title ="نوعیت مکتوب" value="${data.maktob.subject}" readonly />
        </div>

        `)
}


$(function(){


    $('#getmaktob').on('submit', function(e){

        e.preventDefault();
        const formData = new FormData(e.target);
        showLoading()
        fetch('getmaktob',{
            method:'POST',
            headers:{
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){

                hideLoading()

                // $('#submit-btn').attr('disabled',true)
                $('#maktobData').empty();
                showMaktobData(data);
                $('#maktobNotFoundMessage').css('visibility','hidden')
                $('#maktob_serial_no').val(data.maktob.serial_no)
                    
                
            }else{
 
                hideLoading()
                $('#maktobNotFoundMessage').css('visibility','visible')
                $('#maktobData').css('visibility','hidden');
                
            }
        })
        .then(error => console.log(error));
    })



})
