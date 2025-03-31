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

function fetchProductData() {
  return $.get(`${BASE_URL}/api/v1/products`);
}

// handle variants 02/10/2024
const renderOptions = (data) => {
  return data
    .map((option) => `<option value=${option.id}>${option.name}</option>`)
    .join(" ");
};

const renderTableVariants = (selectedColors, selectedSizes) => {
  let tableRows = "";

  selectedColors.forEach((color, colorIndex) => {
    selectedSizes.forEach((size, sizeIndex) => {
      tableRows += `<tr class="text-center">
                ${
                  sizeIndex === 0
                    ? `<td rowspan="${selectedSizes.length}" style="vertical-align: middle;">${color.text}</td>`
                    : ""
                }
                <td>${size.text}</td>
                <td>
                <input type="tel" name="product_variants[${color.id}-${
        size.id
      }][quantity]"
                min="1" class="form-control quantity-variant-all" value="0" 
                 oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                  onblur="if(this.value === '') this.value = '0'">
                </td>
                <td>
                <input type="file" name="product_variants[${color.id}-${
        size.id
      }][image]" class="form-control-file">
                </td>
            </tr>`;
    });
  });

  return tableRows;
};

// End

$(document).ready(function () {
  let colors = [];
  let sizes = [];

  initializeSelect2();

  $("#color-name-product").hide();
  $("#size-name-product").hide();

  fetchProductData().done(function (res) {
    colors = res.data.productColors;
    sizes = res.data.productSizes;

    // ------- \\
    $("#select-color-product-multiple").html(`${renderOptions(colors)}`);
    $("#select-size-product-multiple").html(`${renderOptions(sizes)}`);
    // ------- \\
  });

  // ------- \\

  // mặc định hide table product variant preview
  $("#table-product-variant-preview").hide();

  // lắng nghe sự kiện chọn color, size
  $("#select-color-product-multiple, #select-size-product-multiple").on(
    "change",
    function () {
      const selectedColors = $("#select-color-product-multiple").select2(
        "data"
      );
      const selectedSizes = $("#select-size-product-multiple").select2("data");

      // Kiểm tra nếu cả màu và size đều được chọn
      if (selectedColors.length > 0 && selectedSizes.length > 0) {
        // khi chọn cả color và size thì mới hiển thị table
        $("#table-product-variant-preview").show();

        const tableBody = renderTableVariants(selectedColors, selectedSizes);
        $("#render-tbody-product").html(tableBody);
      } else {
        $("#table-product-variant-preview").hide();
        $("#render-tbody-product").empty(); // Xóa bảng nếu không có gì được chọn
      }
    }
  );

  $("#apply-quantity-variant-all").on("click", function () {
    let quantityAll = $("#product-quantity-variant-all").val();

    if (quantityAll !== "" && !isNaN(quantityAll) && quantityAll >= 1) {
      $(".quantity-variant-all").val(quantityAll);
      console.log("upload all");
    } else {
      showAlert("error", "Yêu cầu nhập số hợp lệ", "LuxChill Thông Báo!");
    }

    // console.log(`Quantity All: ${quantityAll}`);
  });

  // ------- \\

  handleSubmit();

  // init form editor

  $("#elm1") &&
    tinymce.init({
      selector: "textarea#elm1",
      height: 350,
      plugins: [
        "advlist",
        "autolink",
        "lists",
        "link",
        "image",
        "charmap",
        "preview",
        "anchor",
        "searchreplace",
        "visualblocks",
        "code",
        "fullscreen",
        "insertdatetime",
        "media",
        "table",
        "help",
        "wordcount",
      ],
      toolbar:
        "undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help",
      content_style:
        'body { font-family:"Poppins",sans-serif; font-size:16px }',
    });
});

const addImageGallery = () => {
  let id =
    "gen" + "_" + Math.random().toString(36).substring(2, 15).toLowerCase();
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
  showAlertConfirmTrash(() => {
    $(`#${id}`).remove();
  });
};

const handleSubmit = () => {
  $("#submit-create-form-product").click(function () {
    $("#form-create-product").submit();
  });
};

const validateProductVariants = () => {
  const selectedColors = $("#select-color-product-multiple").select2("data");
  const selectedSizes = $("#select-size-product-multiple").select2("data");

  // Kiểm tra nếu cả màu và size đều được chọn
  if (selectedColors.length > 0 && selectedSizes.length > 0) {
    // khi chọn cả color và size thì mới hiển thị table
    $("#table-product-variant-preview").show();

    const tableBody = renderTableVariants(selectedColors, selectedSizes);
    $("#render-tbody-product").html(tableBody);
  } else {
    $("#render-tbody-product").empty(); // Xóa bảng nếu không có gì được chọn
  }
};
