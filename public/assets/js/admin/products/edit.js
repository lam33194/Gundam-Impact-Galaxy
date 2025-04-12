function previewImage(event) {
    const img = document.getElementById("projectlogo-img");
    img.src = URL.createObjectURL(event.target.files[0]);
    $("#projectlogo-img").addClass("h-screen");
}

function initializeSelect2() {
    $("#select-tag-product-multiple").select2({
        placeholder: "Choose tags...",
        allowClear: true,
    });

    $("#select-color-product-multiple").select2({
        placeholder: "Choose color...",
        allowClear: true,
    });

    $("#select-size-product-multiple").select2({
        placeholder: "Choose size...",
        allowClear: true,
    });
}

const renderTableVariants = (selectedColors, selectedSizes, existingVariants) => {
    let tableRows = "";

    selectedColors.forEach((color, colorIndex) => {
        selectedSizes.forEach((size, sizeIndex) => {
            const variant = existingVariants.find(v =>
                v.product_color_id == color.id && v.product_size_id == size.id
            ) || { quantity: 0, image: null };

            tableRows += `
                <tr class="text-center">
                    ${sizeIndex === 0
                    ? `<td rowspan="${selectedSizes.length}" style="vertical-align: middle;">${color.text}</td>`
                    : ""
                }
                    <td>${size.text}</td>
                    <td>
                        <input type="tel" 
                               name="product_variants[${color.id}-${size.id}][quantity]" 
                               min="1" 
                               class="form-control quantity-variant-all" 
                               value="${variant.quantity}" 
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               onblur="if(this.value === '') this.value = '0'">
                    </td>
                    <td>
                        <input type="file" 
                               name="product_variants[${color.id}-${size.id}][image]" 
                               class="form-control-file">
                        ${variant.image ? `<img src="${BASE_URL+"/storage/"+variant.image}" style="max-width: 50px;">` : ''}
                    </td>
                </tr>`;
        });
    });

    return tableRows;
};

$(document).ready(function () {
    initializeSelect2();

    // Initially show variants table with existing data
    if (oldColors.length > 0 && oldSizes.length > 0) {
        const tableBody = renderTableVariants(
            oldColors.map(c => ({ id: c.id, text: c.name })),
            oldSizes.map(s => ({ id: s.id, text: s.name })),
            oldVariants
        );
        $("#render-tbody-product").html(tableBody);
        $("#table-product-variant-preview").show();
    } else {
        $("#table-product-variant-preview").hide();
    }

    // Handle variant changes
    $("#select-color-product-multiple, #select-size-product-multiple").on("change", function () {
        const selectedColors = $("#select-color-product-multiple").select2("data");
        const selectedSizes = $("#select-size-product-multiple").select2("data");

        if (selectedColors.length > 0 && selectedSizes.length > 0) {
            $("#table-product-variant-preview").show();
            const tableBody = renderTableVariants(selectedColors, selectedSizes, oldVariants);
            $("#render-tbody-product").html(tableBody);
        } else {
            $("#table-product-variant-preview").hide();
            $("#render-tbody-product").empty();
        }
    });

    $("#apply-quantity-variant-all").on("click", function () {
        let quantityAll = $("#product-quantity-variant-all").val();
        if (quantityAll !== "" && !isNaN(quantityAll) && quantityAll >= 1) {
            $(".quantity-variant-all").val(quantityAll);
        }
    });

    $("#submit-edit-form-product").click(function () {
        $("#form-edit-product").submit();
    });

    // TinyMCE initialization
    tinymce.init({
        selector: "textarea#elm1",
        height: 350,
        plugins: [
            "advlist", "autolink", "lists", "link", "image", "charmap", "preview",
            "anchor", "searchreplace", "visualblocks", "code", "fullscreen",
            "insertdatetime", "media", "table", "help", "wordcount"
        ],
        toolbar: "undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help",
        content_style: 'body { font-family:"Poppins",sans-serif; font-size:16px }',
        setup: function (editor) {
            editor.on('init', function () {
                if (product && product.content) {
                    editor.setContent(product.content);
                }
            });
        }
    });
});

const addImageGallery = () => {
    let id = "gen_" + Math.random().toString(36).substring(2, 15).toLowerCase();
    let html = `
        <div class="col-md-4" id="${id}_item">
            <div class="d-flex">
                <input type="file" class="form-control" name="product_galleries[]" id="${id}">
                <button type="button" class="btn btn-danger" onclick="removeImageGallery('${id}_item')">
                    <span class="bx bx-trash"></span>
                </button>
            </div>
        </div>
    `;
    $("#gallery_list").append(html);
};

const removeImageGallery = (id) => {
    $(`#${id}`).remove();
};