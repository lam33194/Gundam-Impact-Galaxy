const showAlert = (icon = null, message = null, title = null) => {
  Swal.fire({
    title: `${title ?? ""}`,
    text: `${message}`,
    icon: `${icon}`,
  });
};

const showAlertConfirm = (callback) => {
  Swal.fire({
    title: "Bạn có chắc không?",
    text: "Sau khi xóa sẽ chuyển vào thùng rác 30d!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Xác Nhận",
    cancelButtonText: "Hủy",
  }).then((result) => {
    if (result.isConfirmed) {
      callback();
    }
  });
};

const showAlertConfirmTrash = (callback) => {
  Swal.fire({
    title: "Bạn có chắc không?",
    text: "Sau khi xóa sẽ mất dữ liệu, không thể khôi phục",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Xác Nhận",
    cancelButtonText: "Hủy",
  }).then((result) => {
    if (result.isConfirmed) {
      callback();
    }
  });
};

const showToastr = (icon = "success", message = null, title = null) => {
  toastr[icon](message, title);
};

$(function () {
  $.ajaxSetup({
    beforeSend: function (xhr, settings) {
      if (settings.type !== "GET") {
        xhr.setRequestHeader(
          "X-CSRF-TOKEN",
          $('meta[name="csrf-token"]').attr("content")
        );
      }
    },
  });
});
