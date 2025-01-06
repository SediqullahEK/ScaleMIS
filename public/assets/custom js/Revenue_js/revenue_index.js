function num2str(num) {
    const ones = [
        "صفر",
        "یک",
        "دو",
        "سه",
        "چهار",
        "پنج",
        "شش",
        "هفت",
        "هشت",
        "نه",
        "ده",
        "یازده",
        "دوازده",
        "سیزده",
        "چهارده",
        "پانزده",
        "شانزده",
        "هفده",
        "هجده",
        "نوزده",
    ];
    const tens = [
        "",
        "",
        "بیست",
        "سی",
        "چهل",
        "پنجاه",
        "شصت",
        "هفتاد",
        "هشتاد",
        "نود",
    ];
    const hundreds = [
        "",
        "صد",
        "دوصد",
        "سه صد",
        "چهارصد",
        "پنج صد",
        "شش صد",
        "هفت صد",
        "هشت صد",
        "نه صد",
    ];
    const thousands = [
        "",
        "هزار",
        "میلیون",
        "میلیارد",
        "تریلیون",
        "کوآدریلیون",
        "کوینتیلیون",
        "سکستیلیون",
        "سپتیلیون",
        "اکتیلیون",
        "نانیلیون",
        "دسیلیون",
    ];

    if (num === 0) {
        return ones[num];
    }

    let numStr = "";
    let numLen = num.toString().length;
    let numArr = Array.from(num.toString()).reverse();

    for (let i = 0; i < numLen; i += 3) {
        let chunk = numArr.slice(i, i + 3);
        let chunkLen = chunk.length;
        let chunkStr = "";

        if (chunkLen === 3) {
            if (chunk[2] > 0) {
                chunkStr += hundreds[chunk[2]] + " و ";
            }
            if (chunk[1] == 1) {
                chunkStr += ones[chunk[1] * 10 + parseInt(chunk[0])] + " ";
            } else if (chunk[1] > 1) {
                chunkStr += tens[chunk[1]] + " ";
            }
            if (chunk[1] != 1 && chunk[0] > 0) {
                chunkStr += "و ";
            }
            if (chunk[0] > 0 && chunk[1] != 1) {
                chunkStr += ones[chunk[0]] + " ";
            }
            chunkStr += thousands[Math.floor(i / 3)];
        } else if (chunkLen === 2) {
            if (chunk[1] == 1) {
                chunkStr += ones[chunk[1] * 10 + parseInt(chunk[0])] + " ";
            } else if (chunk[1] > 1) {
                chunkStr += tens[chunk[1]] + " ";
            }
            if (chunk[1] != 1 && chunk[0] > 0) {
                chunkStr += "و ";
            }
            if (chunk[0] > 0 && chunk[1] != 1) {
                chunkStr += ones[chunk[0]] + " ";
            }
            chunkStr += thousands[Math.floor(i / 3)];
        } else {
            chunkStr += ones[chunk[0]] + " " + thousands[Math.floor(i / 3)];
        }

        numStr = chunkStr + " " + numStr;
    }

    return numStr.trim();
}

$(function () {
    //  Remove revenue generated row from table and set closet row data to inputs

    $(document).on("click", ".remove-row", function () {
        $(this).closest("tr").remove();
    });

    $(document).on("click", ".remove-row-awaze", function () {
        $(this).closest("tr").remove();
    })

    $(document).on("click", ".get-row-data", function () {
        // Find the closest row element
        var row = $(this).closest("tr");

        // Get the row ID, name, and TIN from the row data attributes
        var id = row.find("td:eq(1)").text();
        var name = row.find("td:eq(3)").text();
        var tin = row.find("td:eq(4)").text();

        $('input[name="tarofa_revenue_id"]').val(id);
        $('input[name="tarofa_taxpayer_name"]').val(name);
        $('input[name="tarofa_tin_no"]').val(tin);

        $("span#modal_identfication").html(id);

        // Fetch Data of Remaining Amount of Mineral and it's Price
        const formData = new FormData();
        formData.append("id", id);

        fetch("revenue/getMineral_Price_Balance", {
            method: "post",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    $("#mineral_balance_amount").html(
                        data.mineral_amount_balance
                    );
                    $("#mineral_price_amount").html(data.mineral_price_balance);
                }
            })
            .catch((error) => console.log(error));
    });

    // Calculate total revenue and set total to revenue total input field


    // Calculate total revenue
    function calculateTotalRevenue() {
        let total = 0;

        $("#dynamic-table tbody tr").each(function () {
            var revenue = parseFloat($(this).find("input.revenue-price").val());

            if (!isNaN(revenue)) {
                total += revenue;
            }
        });


        $("input[name='total_amount']").val(total);


    }

    function calculateTotalForAwaze() {
        let totalForAwaze = 0;
        // caculating total revenue for awaze section
        $("#dynamic-table-awaze tbody tr").each(function () {
            let revenues = parseFloat($(this).find("input.revenue-price-awaze").val());
            if (!isNaN(revenues)) {
                totalForAwaze += revenues;
            }
        })

        $("input[name='total_amount_awaze']").val(totalForAwaze);
    }

    // Attach input event listener using event delegation
    $("#dynamic-table").on("input", "input.revenue-price", function () {
        calculateTotalRevenue();
    });

    $("#dynamic-table-awaze").on("input", "input.revenue-price-awaze", function () {
        calculateTotalForAwaze()
    })

    $("#tarofa_save_btn").prop("disabled", false);

    $("#tarofa_print").prop("disabled", true);







    // Save Tarofa Data

    // $("#tarofa").on("submit", function (e) {
    //     e.preventDefault();
    //     const formData = new FormData(e.target);
    //     showLoading()
    //     fetch("/tarofaView/tarofa", {
    //         method: "post",
    //         headers: {
    //             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //         },
    //         body: formData,
    //     })
    //         .then((response) => response.json())
    //         .then((data) => {
    //             if (data.success == "passed") {

    //                     window.location.href='/revenue';


    //                 let message = data.message;
    //                 let status = data.status;

    //                 hideLoading()

    //                 new Notify({
    //                     title: "تعــرفه",
    //                     text: message,
    //                     autoclose: true,
    //                     autotimeout: 5000,
    //                     status: status,
    //                     effect: "slide",
    //                     speed: 300,
    //                 });






    //                 $("#tarofa_save_btn").prop("disabled", true);

    //                 $("#tarofa_save_Confirm").prop("disabled", true);

    //                 // $("#tarofa_print").prop("disabled", false);

    //                 // $(".print_code_province").html(data.province_code);

    //                 // // Create a new Date object representing a Georgian date
    //                 // var georgianDate = new Date();

    //                 // $(".tarofa_no").html(data.tarofa.tarofa_no);
    //                 // $(".print_tarofa_issued_date").html(data.shamsiDate);

    //                 // $(".print_code_organization").html(
    //                 //     data.tarofa.organization_code
    //                 // );
    //                 // $(".print_name_organization").html(
    //                 //     data.tarofa.orgranization_description
    //                 // );
    //                 // $(".print_tin_name").html(
    //                 //     data.tarofa_tin_no + " / " + data.tarofa_taxpayer_name
    //                 // );
    //                 // $(".print_dab_bank").html(data.tarofa.dab_bank_no);
    //                 // $(".tarofa_description").html(data.tarofa.description);

    //                 // var tarofa_revenue_code = data.revenue_code;
    //                 // var tarofa_unit_code = data.unit_code;

    //                 // var tarofa_description = data.description;
    //                 // var tarofa_revenue_amount = data.revenue_amount;

    //                 // // iterate over the tarofa_revenues array and do something with each id
    //                 // for (var i = 0; i < tarofa_revenue_code.length; i++) {
    //                 //     var datahtml =
    //                 //         `
    //                 //             <tr class='border border-black text-center'>
    //                 //                 <td class=" border border-black text-center" >` +
    //                 //         tarofa_unit_code[i] +
    //                 //         `</td>
    //                 //                 <td class=" border border-black text-center" >` +
    //                 //         tarofa_revenue_code[i] +
    //                 //         `</td>
    //                 //                 <td class=" border border-black text-center" colspan="2" >` +
    //                 //         tarofa_description[i] +
    //                 //         `</td>
    //                 //                 <td class=" border border-black text-center" >` +
    //                 //         tarofa_revenue_amount[i] +
    //                 //         `</td>
    //                 //             </tr>
    //                 //         `;

    //                 //     $(".showsubdata_print").append(datahtml);
    //                 // }

    //                 // $(".print_total").html(data.tarofa.total_amount);
    //                 // $(".print_total_alphabetic").html(
    //                 //     num2str(data.tarofa.total_amount)
    //                 // );

    //                 // $("#land_a4_size").css("display", "block");

    //             } else if (data.success == "failed") {
    //                 hideLoading()
    //                 let message = data.message;
    //                 let status = data.status;

    //                 new Notify({
    //                     title: 'خـــطـــا',
    //                     text: message,
    //                     autoclose: true,
    //                     autotimeout: 5000,
    //                     status: status,
    //                     effect: 'slide',
    //                     speed: 300,
    //                 })
    //             } else {
    //                 hideLoading()
    //                 let parentElement =
    //                     document.querySelector(".parent-element");
    //                 for (let field in data.errors) {
    //                     let inputField = document.querySelector(
    //                         `[name="${field}"]`
    //                     );
    //                     let errorDiv = document.createElement("div");

    //                     errorDiv.classList.add(
    //                         "text-red-700",
    //                     );

    //                     errorDiv.textContent = data.errors[field][0];

    //                     let parentElement = inputField.parentElement;
    //                     parentElement.appendChild(errorDiv);
    //                 }
    //             }
    //         })
    //         .catch((error) => { console.log(error) });
    // });







});
