// Province and District Code
function getDistrict($route, province_name, district_name) {
    // Fetch distracts from database from province id
    $(document).on("change", 'select[name="' + province_name + '"]', function () {
        const formData = new FormData();
        const value = $(this).val(); // get the value of the select field
        formData.append(province_name, value); // append the value to the FormData object

        fetch($route, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    const selectElement = document.querySelector(
                        'select[name="' + district_name + '"]'
                    );
                    selectElement.innerHTML = ""; // clear the options of the select element
                    const alldata = data.alldata; // assuming that the district data is returned in the 'data' property of the response
                    alldata.forEach((district) => {
                        const optionElement = document.createElement("option");
                        optionElement.value = district.id;
                        optionElement.textContent = district.district_dari;
                        selectElement.appendChild(optionElement);
                    });
                }
            })
            .catch((error) => console.log(error));
    });
}

// Function for Maximizing the Letter Image
function maximizeImage(img) {
    // Create a new <div> element to hold the full-screen image
    var fullScreenDiv = document.createElement("div");
    fullScreenDiv.classList.add(
        "fixed",
        "inset-0",
        "z-50",
        "bg-black",
        "flex",
        "justify-center",
        "items-center"
    );

    // Create a new <img> element for the full-screen image
    var fullScreenImg = document.createElement("img");
    fullScreenImg.classList.add("h-full");
    fullScreenImg.src = img.src;

    // Create a new <button> element for the close button
    var closeButton = document.createElement("button");
    closeButton.classList.add(
        "absolute",
        "top-0",
        "right-0",
        "m-4",
        "text-white",
        "text-2xl",
        "font-bold"
    );
    closeButton.innerHTML = "&times;";

    // Add an event listener to the close button to remove the new <div> element from the <body>
    closeButton.addEventListener("click", function () {
        document.body.removeChild(fullScreenDiv);
    });

    // Add the <img> element and the close button to the <div> element
    fullScreenDiv.appendChild(fullScreenImg);
    fullScreenDiv.appendChild(closeButton);

    // Add the <div> element to the <body> element
    document.body.appendChild(fullScreenDiv);

    // Add an event listener to the document for the "Escape" key to close the full-screen image
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            document.body.removeChild(fullScreenDiv);
        }
    });
}

$(function () {
    // Get Letter Data from PMIS Database

    $("#getletter").on("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(e.target);

        showLoading()

        fetch("create/revenueScaleLetterId", {
            method: "POST",
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {

                    hideLoading()

                    $("#showLetterData").html(`
                        <table class="w-full table-auto border border-green-200">
                            <thead>
                                <tr class="border border-green-200">
                                    <th class="w-1/3">نمبر عریضه : </th>
                                    <th>
                                        ${data.alldata.id}
                                    </th>
                                </tr>
                                <tr class="border border-green-200">
                                    <th >اسم (شخص یا شرکت) : </th>
                                    <th>${data.alldata.source_name}</th>
                                </tr>
                                <tr class="border border-green-200">
                                    <th>ولد : </th>
                                    <th>${data.alldata.source_fname}</th>
                                </tr>
                                <tr class="border border-green-200">
                                    <th>موضوع : </th>
                                    <th>${data.alldata.subject}</th>
                                </tr>
                                <tr class="border border-green-200" >
                                    <th>شماره تماس : </th>
                                    <th>${data.alldata.phone}</th>
                                </tr>
                                <tr class="border border-green-200">
                                    <th>ولایت : </th>
                                    <th>${data.alldata.name}</th>
                                </tr>
                                <tr class="border border-green-200">
                                    <th>تاریخ حکم : </th>
                                    <th>${data.alldata.date}</th>
                                </tr>
                                <tr class="border border-green-200">
                                    <th>متن حکم : </th>
                                    <th>${data.alldata.comment}</th>
                                </tr>
                                <tr class="border border-green-200">
                                    <th>تصویر عریضه : </th>

                                    <th >
                                        <div  class="relative mx-auto" style="width:300px;" >
<img id="rev_img"  class="w-full mx-auto  object-cover object-center cursor-pointer border border-red-200 " src="../../../../../../pmis/storage/app/letter_scans/${data.alldata.letter_scan}" alt="Image" >
</div>

                                        </th>
                                </tr>
                            </thead>
                        </table>
`);

                    $("#rev_img").on("click", function () {
                        maximizeImage(this);
                    });

                    $('input[name="letter_id"]').val(data.alldata.id);
                } else {
                    alert("There was an error submitting the form.");
                }
            })
            .catch((error) => {
                $("#showLetterData").html(
                    '<div class="mb-4 rounded-lg bg-danger-100 px-6 py-5 text-base text-danger-700"role="alert">عریضه مذکور موجود نیست !!</div>'
                );
            });
    });

    // Minral amount on change function


    $('input[name="mineral_amount"]').on("input", function () {
        var $minral_qty = $(this).val();
        var $unit_price = $('input[name="unit_price"]').val();
        var $weighing_price = $('input[name="weighing_price"]').val();

        var weighing_price = $weighing_price * $minral_qty;


        var $mineral_total_price = $minral_qty * $unit_price + weighing_price;
        $('input[name="mineral_total_price"]').val($mineral_total_price);
    });

    $('input[name="unit_price"]').on("input", function () {
        var $minral_qty = $('input[name="mineral_amount"]').val();
        var $weighing_price = $('input[name="weighing_price"]').val();
        var $unit_price = $(this).val();

        var weighing_price = $weighing_price;

        var $mineral_total_price = $minral_qty * $unit_price + weighing_price;
        $('input[name="mineral_total_price"]').val($mineral_total_price);
    });

    $('input[name="weighing_price"]').on("input", function () {
        var $minral_qty = $('input[name="mineral_amount"]').val();
        var $unit_price = $('input[name="unit_price"]').val();
        var $weighing_price = $(this).val();
        if ($weighing_price < 0 || $weighing_price == "") $(this).val(0);

        var $mineral_total_price =
            parseInt($minral_qty) * parseInt($unit_price) +
            parseInt($weighing_price);
        $('input[name="mineral_total_price"]').val($mineral_total_price);
    });







    // Save Revenue Details Data

    $("#revenue_details").on("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(e.target);

        showLoading()

        fetch("store/savedata", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {

                    hideLoading()

                    new Notify({
                        title: "موفقانه",
                        text: "دیتا ذخیره شد",
                        autoclose: true,
                        autotimeout: 3000,
                        status: "success",
                        effect: "slide",
                        speed: 300,
                    });
                } else {
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
            .catch((error) => console.log(error));
    });







    $('input[name="search_ids"]').on('keydown', function (event) {
        if (event.key === "Enter") {
            event.preventDefault();

            $("#reveneue_category_form").submit();

        }
    });




    $("#reveneue_category_form").on("submit", function (e) {
        e.preventDefault();

        let id = $('input[name="search_ids"]').val();
        let specifiedRev = $('input[name="specifiedRev"]').val();

        const formData = new FormData();
        formData.append("id", id);
        formData.append("specifiedRev", specifiedRev);

        fetch("/tarofaView/tarofa_detail", {
            method: "post",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    $('#hokomi_balance_data').css('display', 'block');
                    $("#mineral_balance_amount").html(data.mineral_amount_balance);
                    $('input[name="tarofa_tin_no"]').val(data.revenue_details.tin_no);
                    $("#mineral_price_amount").html(data.mineral_price_balance);
                    if (data.source_name !== undefined && data.source_name !== null)
                        $("input[name='tarofa_taxpayer_name']").val(data.source_name);
                    else
                        $("input[name='tarofa_taxpayer_name']").val(data.contractor_name);

                    $("input[name='tarofa_revenue_id']").val(data.revenue_id);

                    $('.unit_name').html(data.unit_name);

                    $('#showMaktoobData').css('display','none');

                } else {
                    $('#showMaktoobData').css('display','block');
                    $("#showMaktoobData").html(
                        '<div class="mb-4 rounded-lg bg-danger-100 px-6 py-5 text-base text-danger-700"role="alert">شماره عواید مذکور موجود نیست !!</div>'
                    );

                    $('#hokomi_balance_data').css('display', 'none');

                    $("input[name='tarofa_taxpayer_name']").val('');
                    $("input[name='tarofa_tin_no']").val('');



                }
            })
            .catch((error) => {
                $("#showMaktoobData").html(
                    '<div class="mb-4 rounded-lg bg-danger-100 px-6 py-5 text-base text-danger-700"role="alert">شماره عواید مذکور موجود نیست !!</div>'
                );
            });
    });




    // Get Maktoob Data from PMIS for Contract Revenue
    $("#getMaktoob").on("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(e.target);

        showLoading()

        fetch("maktoob_data", {
            method: "POST",
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    hideLoading()
                    $("#showMaktoobData").html(`
                        <table class="w-full table-auto border border-green-200">
                            <thead>
                                <tr class="border border-green-200">
                                    <th class="w-1/3">شماره ای مکتوب: </th>
                                    <th>
                                        ${data.alldata.serial_no}
                                    </th>
                                </tr>
                                <tr class="border border-green-200">
                                    <th > نوعیت مکتوب : </th>
                                    <th>${data.alldata.type}</th>
                                </tr>
                                <tr class="border border-green-200">
                                    <th>تاریخ ثبت : </th>
                                    <th>${data.alldata.date}</th>
                                </tr>
                                <tr class="border border-green-200">
                                    <th>مرسل : </th>
                                    <th>${data.alldata.source}</th>
                                </tr>
                                <tr class="border border-green-200" >
                                    <th>مرسل الیه : </th>
                                    <th>${data.alldata.destination}</th>
                                </tr>
                                <tr class="border border-green-200">
                                    <th>موضوع : </th>
                                    <th>${data.alldata.subject}</th>
                                </tr>

                                <tr class="border border-green-200">
                                    <th>تصویر مکتوب : </th>

                                    <th >
                                        <div  class="relative mx-auto" style="width:300px;" >
<img id="rev_img"  class="w-full mx-auto  object-cover object-center cursor-pointer border border-red-200 " src="../../../../pmis/storage/app/letter_scans/${data.alldata.maktob_scan}" alt="Image" >
</div>
                                        </th>
                                </tr>
                            </thead>
                        </table>
`);

                    $("#rev_img").on("click", function () {
                        maximizeImage(this);
                    });

                    let maktoob_ids = $('input[name="maktoobId"]').val();

                    $('input[name="maktoob_id"]').val(maktoob_ids);


                } else {
                    alert("There was an error submitting the form.");
                }
            })
            .catch((error) => {
                $("#showMaktoobData").html(
                    '<div class="mb-4 rounded-lg bg-danger-100 px-6 py-5 text-base text-danger-700"role="alert">مکتوب مذکور موجود نیست !!</div>'
                );
            });
    });











});
