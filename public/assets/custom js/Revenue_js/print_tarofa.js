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

    var total = 0;

    // Calculate total revenue
    function calculateTotalRevenue() {
        total = 0;

        $("#dynamic-table tbody tr").each(function () {
            var revenue = parseFloat($(this).find("input.revenue-price").val());
            if (!isNaN(revenue)) {
                total += revenue;
            }
        });

        $("#total_amount").val(total);
    }

    // Attach input event listener using event delegation
    $("#dynamic-table").on("input", "input.revenue-price", function () {
        calculateTotalRevenue();
    });

    $("#tarofa_save_btn").prop("disabled", false);

    $("#tarofa_print").prop("disabled", true);

    // Save Tarofa Data

    $("#tarofa_print_form").on("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        showLoading()

        fetch("/revenue/tarofa/print", {
            method: "post",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success == "passed") {

                    hideLoading()
                   

                    $('#error-msg').css('display','none');

                    $(".print_code_province").html(data.province_code);

                    // Create a new Date object representing a Georgian date
                    var georgianDate = new Date();

                    $(".tarofa_no").html(data.tarofa.tarofa_no);
                    $(".print_tarofa_issued_date").html(data.shamsiDate);

                    $(".print_code_organization").html(
                        data.tarofa.organization_code
                    );
                    $(".print_name_organization").html(
                        data.tarofa.orgranization_description
                    );
                    $(".print_tin_name").html(
                        data.tarofa_tin_no + " / " + data.tarofa_taxpayer_name
                    );
                    $(".print_dab_bank").html(data.tarofa.dab_bank_no);
                    $(".tarofa_description").html(data.tarofa.description);

                    var tarofa_revenue_code = data.revenue_code;
                    var tarofa_unit_code = data.unit_code;

                    var tarofa_description = data.description;
                    var tarofa_revenue_amount = data.revenue_amount;

                    // iterate over the tarofa_revenues array and do something with each id
                    $(".showsubdata_print").empty();
                    for (var i = 0; i < tarofa_revenue_code.length; i++) {
                        var datahtml =
                            `
                                <tr class='border border-black text-center'>
                                    <td class=" border border-black text-center" >` +
                            tarofa_unit_code[i] +
                            `</td>
                                    <td class=" border border-black text-center" >` +
                            tarofa_revenue_code[i] +
                            `</td>
                                    <td class=" border border-black text-center" colspan="2" >` +
                            tarofa_description[i] +
                            `</td>
                                    <td class=" border border-black text-center" >` +
                            tarofa_revenue_amount[i].toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 }) +
                            `</td>
                                </tr>
                            `;

                        $(".showsubdata_print").append(datahtml);
                    }

                    $(".print_total").html(data.tarofa.total_amount.toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 }));
                    $(".print_total_alphabetic").html(
                        num2str(data.tarofa.total_amount)
                    );

                    $("#printed-tarofa").css("display", "block");
                    $('#print-btn').css('display','block');

                } else if (data.success == "failed") {
                    hideLoading()
                    $('#print-btn').css('display','none');
                    $("#printed-tarofa").css("display", "none");
                    $('#error-msg').css('display','block');

                    let message = data.message;
                    let status = data.status;
                    new Notify({
                        title: 'خـــطـــا',
                        text: message,
                        autoclose: true,
                        autotimeout: 5000,
                        status: status,
                        effect: 'slide',
                        speed: 300,
                    })
                } else if(data.success == false) {
                    hideLoading()
                    console.log(data.errors);
                    let parentElement =
                        document.querySelector(".parent-element");
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
            .catch((error) => { console.log(error) });
    });

    $(window).on("afterprint", function () {
        location.reload(true);
    });
});
