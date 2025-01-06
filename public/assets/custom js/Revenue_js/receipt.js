

$(function(){

    // fetching tarofa data by tarofa number.

    $('#tarofa_number').on('input', function(){
        let sourceValue = $(this).val();

        $("#tarofaNumber").val(sourceValue);
    })

    $("#tarofa_search").on("submit",function(e){

        e.preventDefault();
        const formData = new FormData(e.target);
        showLoading()
        fetch('/revenue/getTarofaData',{
            method:'post',
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                hideLoading()

                $('#add-row-awaze').css('display','none')
               
                $('#tarofaNumber').val(data.tarofa.tarofa_no)
                $("#province_id_awaze").append(`
                    <option selected>${data.province_code}</option>
                `)
                $('#district_code').append(`
                    <option selected>${data.district_code}</option>
                `)
                $('#tarofa_taxpayer_name').val(data.taxpayer_name)
                $('#tarofa_tin_no').val(data.tin_no)
                $('#bank_account').append(`
                    <option selected>${data.tarofa.dab_bank_no}</option>
                `)
                $('#mineral_exported_amount').val(data.tarofa.mineral_exported_amount)
                $('#tarofa_description').val(data.tarofa.description)
                $('#organization_code').append(`
                    <option selected>${data.tarofa.organization_code === 32 ? "وزارت معادن و پترولیم" : ""}</option>
                `)
                $('#revenue_codes').empty();
                $.each(data.tarofa_revenue_details, function(index, item) {
                    // Access individual properties of each item`
                    let revenue_amount = item.revenue_amount
                    let revenues = `
                    <tr>
                    
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                            ${item.unit_code === 32000 ? "وزارت معادن و پترولیم" :
                            item.unit_code === 32100 ? "معینیت سروی جیولوجی افغانستان" : ""}
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">

                            <div>
                                ${item.description + " " + item.revenue_code }
                            </div>

                        </td>


                        <td class="px-6 py-4 whitespace-nowrap">

                           <div>
                           ${item.revenue_amount}
                           </div>

                        </td>
                        <td class="">

                            <x-btnte title="حذف" bg="bg-danger" class="remove-row-awaze" id="remove"/>

                        </td>
                    </tr>
                    `
    
                    $('#revenue_code_details').append(revenues)
                })

                $('#tarofa_no').empty()
                $('#tarofa_issue_date').empty()
                $('#customer_name').empty()
                $('#tin').empty()
                $('#total_money').empty()
                $('#description').empty()

                $('#tarofa_no').append(data.tarofa.tarofa_no)
                $('#tarofa_issue_date').append(data.tarofa_issue_date)
                $('#customer_name').append(data.taxpayer_name)
                $('#tin').append(data.tin_no)
                $('#total_money').append(data.tarofa.total_amount)
                $('#description').append(data.tarofa.description)

                $('#total_amount_awaze').val(data.tarofa.total_amount)



            }else if(data.success == false){

                hideLoading()
                for (let field in data.errors) {
                    let inputField = document.querySelector(
                        `[name="${field}"]`
                    );
                    let errorSpan = document.querySelector(
                        `#${field}-error`
                    );
                    errorSpan.textContent = data.errors[field][0];
                    inputField.classList.add("is-invalid");
                }
            }
            else{
                hideLoading()
                console.log("data not found")
            }
        })
        .catch(error => {
            hideLoading()
            $("#error_message").css('display','block')
            $("#tarofa_confirmation").css('display','none')
        })

    })

    // saving receip data
    // $('#save_receipt_btn').on('click', function() {
    //     $confirm("آیا معلومات تعرفه و آویز درست میباشد و مطمئن هستید که آویز ثبت سیستم گردد؟", "#3061AF")
    //         .then(() => {
    //            return true;
    //         })
    // });

    $('#save_receipt').on('submit', function(e){
        e.preventDefault();
        const formData = new FormData(e.target);
        showLoading()
        fetch('/revenue/store/receipt',{
            method: "post",
            headers:{
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success == "passed"){
                hideLoading()
                // setTimeout(() => $('#savereceipt').modal('hide'),2000)

                let message = data.reciept;
                let status = data.status;
                window.location.href = "/revenue"
                
                new Notify({
                    title: 'آویز',
                    text: message,
                    autoclose: true,
                    autotimeout: 5000,
                    status: status,
                    effect: 'slide',
                    speed: 300,
                })

                

            }else if(data.success == "failed"){
                
                hideLoading()
                let message = data.message
                let status = data.status
                new Notify({
                    title: 'خـــطـــا',
                    text: message,
                    autoclose: true,
                    autotimeout: 5000,
                    status: status,
                    effect: 'slide',
                    speed: 300,
                })
            }
            else{

                hideLoading()

                for (let field in data.errors) {
                    let inputField = document.querySelector(
                        `[name="${field}"]`
                    );
                    let errorSpan = document.querySelector(
                        `#${field}-error`
                    );
                    errorSpan.textContent = data.errors[field][0];
                    inputField.classList.add("is-invalid");
                }
            }
        })
        .catch(error => {
            console.log("Receipt not save" + error);
        })


    })


})
